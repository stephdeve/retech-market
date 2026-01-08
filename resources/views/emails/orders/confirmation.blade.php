<x-mail::message>
# 🎉 Merci pour votre commande !

Bonjour {{ $orders->first()->buyer->name }},

Nous avons bien reçu votre paiement de **{{ number_format($total, 2) }}
                                                @auth
                                                   {{ Auth::user()->currency }}
                                                @endauth 
                                                @guest
                                                    XOF
                                                @endguest**. Voici le récapitulatif de vos achats :

<x-mail::table>
| Produit       | Vendeur         | Prix      |
| :------------ | :-------------- | :-------- |
@foreach($orders as $order)
| {{ $order->product->name }} | {{ $order->product->user->name }} | {{ number_format($order->total_price, 2) }} € |
@endforeach
</x-mail::table>

<x-mail::button :url="route('purchases')">
Voir mes achats
</x-mail::button>

Merci de faire confiance à **ReTech Market** !

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
