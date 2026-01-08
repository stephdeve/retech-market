<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductComments extends Component
{
    public Product $product;
    public $content;

    protected $rules = [
        'content' => 'required|min:5|max:500',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function save()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $this->product->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->content,
        ]);

        $this->content = '';
        
        session()->flash('message', 'Votre commentaire a été publié.');
    }

    public function delete($commentId)
    {
        $comment = Comment::find($commentId);

        if ($comment && (Auth::id() === $comment->user_id || Auth::user()->hasRole('admin'))) {
            $comment->delete();
        }
    }

    public function render()
    {
        return view('livewire.product-comments', [
            'comments' => $this->product->comments()->with('user')->latest()->get(),
        ]);
    }
}
