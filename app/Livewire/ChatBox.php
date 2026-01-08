<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Order;
use App\Events\MessageSent;
use Livewire\Component;
use Livewire\Attributes\On;

use Livewire\Attributes\Validate;

class ChatBox extends Component
{
    public $order;
    public $chatMessages;

    #[Validate('required|string|max:1000')]
    public $newMessage;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->chatMessages = Message::where('order_id', $order->id)->with('sender')->get();
    }

    #[On('echo-private:chat.{order.id},MessageSent')]
    public function refreshMessages($event)
    {
        $this->chatMessages->push(Message::find($event['message']['id']));
    }

    public function sendMessage()
    {
        $this->validate();

        $receiverId = (auth()->id() === $this->order->buyer_id) 
            ? $this->order->product->user_id 
            : $this->order->buyer_id;

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'order_id' => $this->order->id,
            'content' => $this->newMessage,
        ]);

        broadcast(new MessageSent($message));

        $this->chatMessages->push($message);
        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
