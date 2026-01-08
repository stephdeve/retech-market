<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ToggleFavorite extends Component
{
    public Product $product;
    public bool $isFavorite = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        if (Auth::check()) {
            $this->isFavorite = Auth::user()->favorites()->where('product_id', $product->id)->exists();
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($this->isFavorite) {
            $user->favorites()->detach($this->product->id);
            $this->isFavorite = false;
        } else {
            $user->favorites()->attach($this->product->id);
            $this->isFavorite = true;
        }
    }

    public function render()
    {
        return view('livewire.toggle-favorite');
    }
}
