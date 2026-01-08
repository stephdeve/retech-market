<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;

class NavbarMessages extends Component
{
    public function getListeners()
    {
        $userId = auth()->id();
        return [
            // Écoute le canal privé des messages de l'utilisateur connecté
            "echo:private-messages.{$userId},MessageSent" => '$refresh',
            "messageReceived" => '$refresh',
        ];
    }

    public function getUnreadCountProperty()
    {
        return Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    public function getMessagesProperty()
    {
        // Get latest message for each conversation
        // This is a simplified version, ideally we would group by sender
        return Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->with(['sender', 'product'])
            ->latest()
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.navbar-messages');
    }
}
