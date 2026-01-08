<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Livewire\Component;

class ProductQuickMessage extends Component
{
    public Product $product;
    public string $newMessage = '';

    protected $rules = [
        'newMessage' => 'required|string|max:1000',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function send()
    {
        $this->validate();

        // Détermine le destinataire: le vendeur du produit
        $receiverId = $this->product->user_id;
        if (auth()->id() === $receiverId) {
            $this->addError('newMessage', 'Vous ne pouvez pas vous envoyer un message.');
            return;
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'product_id' => $this->product->id,
            'content' => $this->newMessage,
        ]);

        // Diffuse l'événement pour rafraîchir la navbar du vendeur en temps réel
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            report($e);
        }

        // Notifie le vendeur (base de données + broadcast) pour NavbarNotifications
        if ($receiver = User::find($receiverId)) {
            try {
                $receiver->notify(new NewMessageNotification($message));
            } catch (\Throwable $e) {
                // ignore
            }
        }

        $this->reset('newMessage');
        $this->dispatch('messageSent');
        session()->flash('message_sent', '✅ Message envoyé au vendeur.');
    }

    public function render()
    {
        return view('livewire.product-quick-message');
    }
}
