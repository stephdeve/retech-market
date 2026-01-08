<x-mail::message>
# Félicitations {{ $product->user->name }} ! 🎉

Votre annonce **{{ $product->name }}** a été publiée avec succès sur ReTech Market.

<x-mail::panel>
**Prix :** {{ number_format($product->price, 2) }} 
                                                @auth
                                                    {{ Auth::user()->currency }}
                                                @endauth 
                                                @guest
                                                    XOF
                                                @endguest
**Catégorie :** {{ $product->category->name }}
</x-mail::panel>

Vous pouvez voir votre annonce en cliquant sur le bouton ci-dessous :

<x-mail::button :url="route('products.show', $product)">
Voir mon annonce
</x-mail::button>

Merci de faire confiance à **ReTech Market** pour vos ventes !

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
