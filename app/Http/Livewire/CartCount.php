<?php

namespace App\Http\Livewire;
//use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartCount extends Component
{
    public $count;

    protected $listeners = ['updateCart' => 'render'];
    public function render()
    {
//        $this->count = \Cart::count();
        $this->count = \Cart::getContent()->sum('quantity');
        return view('livewire.cart-count');
    }
}
