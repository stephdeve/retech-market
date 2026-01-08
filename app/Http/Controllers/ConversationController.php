<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $uid = auth()->id();

        $messages = Message::with(['sender', 'receiver', 'product.user'])
            ->where(function ($q) use ($uid) {
                $q->where('sender_id', $uid)->orWhere('receiver_id', $uid);
            })
            ->latest()
            ->get();

        // Regroupe par (product_id, autre utilisateur)
        $threads = $messages->groupBy(function ($m) use ($uid) {
            $otherId = $m->sender_id == $uid ? $m->receiver_id : $m->sender_id;
            $productKey = $m->product_id ?: 'no-product';
            return $productKey . '|' . $otherId;
        })->map(function ($msgs) use ($uid) {
            $latest = $msgs->first();
            $product = $latest->product;
            $otherUser = $latest->sender_id == $uid ? $latest->receiver : $latest->sender;
            $unreadCount = $msgs->where('receiver_id', $uid)->where('is_read', false)->count();

            return compact('latest', 'product', 'otherUser', 'unreadCount');
        });

        return view('messages.index', compact('threads'));
    }

    public function show(Product $product, User $user)
    {
        // Autorisation: l'utilisateur connecté doit être le vendeur du produit OU l'autre participant $user
        if (!in_array(auth()->id(), [$product->user_id, $user->id])) {
            abort(403);
        }

        $currentId = auth()->id();
        $otherId = $product->user_id === $currentId ? $user->id : $product->user_id;

        $messages = Message::with(['sender', 'receiver'])
            ->where('product_id', $product->id)
            ->where(function ($q) use ($currentId, $otherId) {
                $q->where(function ($q) use ($currentId, $otherId) {
                    $q->where('sender_id', $currentId)->where('receiver_id', $otherId);
                })->orWhere(function ($q) use ($currentId, $otherId) {
                    $q->where('sender_id', $otherId)->where('receiver_id', $currentId);
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Marque comme lus les messages destinés à l'utilisateur courant
        Message::where('product_id', $product->id)
            ->where('receiver_id', $currentId)
            ->where('sender_id', $otherId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', [
            'product' => $product,
            'otherUser' => User::findOrFail($otherId),
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, Product $product, User $user)
    {
        if (!in_array(auth()->id(), [$product->user_id, $user->id])) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $currentId = auth()->id();
        $receiverId = $product->user_id === $currentId ? $user->id : $product->user_id;

        $message = Message::create([
            'sender_id' => $currentId,
            'receiver_id' => $receiverId,
            'product_id' => $product->id,
            'content' => $validated['content'],
        ]);

        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('messages.show', ['product' => $product->id, 'user' => $user->id]);
    }
}
