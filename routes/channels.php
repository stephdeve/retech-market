<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{orderId}', function ($user, $orderId) {
    $order = Order::find($orderId);
    return $order && ($user->id === $order->buyer_id || $user->id === $order->product->user_id);
});

// Canal privé par utilisateur pour la réception des nouveaux messages
Broadcast::channel('messages.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
