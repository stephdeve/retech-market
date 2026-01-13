<x-mail::message>
# 💰 Bonne nouvelle ! Vous avez réalisé une vente !

Bonjour {{ $order->product->user->name }},

Félicitations ! Votre produit **{{ $order->product->name }}** vient d'être acheté par **{{ $order->buyer->name }}**.

## Détails de la transaction :
- **Produit :** {{ $order->product->name }}
- **Prix de vente :** {{ number_format($order->total_price, 2) }}
                                            @auth
                                                FCFA
                                            @endauth 
                                            @guest
                                                XOF
                                            @endguest
- **Acheteur :** {{ $order->buyer->name }} ({{ $order->buyer->email }})
- **Date :** {{ $order->created_at->format('d/m/Y H:i') }}

<x-mail::button :url="route('sales')">
Voir mes ventes
</x-mail::button>

Veuillez prendre contact avec l'acheteur pour organiser la livraison si nécessaire.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
