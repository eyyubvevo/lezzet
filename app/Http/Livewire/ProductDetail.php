<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Cart;
class ProductDetail extends Component
{
    public $product;

    public function addToCart()
    {
        if ($this->product->category->company){
            $price =  $this->product->getPriceWithDiscount();
        }elseif ($this->product->is_discountable){
            $price =  $this->product->price - ($this->product->discount * $this->product->price / 100);
        }else{
            $price = $this->product->price;
        }
        \Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->getTranslation('title','az'),
            'quantity' => 1,
            'price' => $price,
            'attributes' => [
                'image' => $this->product->images->first(),
                'discount' => $this->product->category->company ? $this->product->getPriceWithDiscount() : null,
                'custom_discount' => $this->product->is_discountable ? $this->product->price - ($this->product->discount * $this->product->price / 100) : null,
            ]
        ]);
        noty()->addFlash('success', __('website.add_success_cart'), ['timeout' => 3000, 'position' => 'top-center']);
//        sweetalert()->addFlash('success', 'Məhsulunuz uğurla səbətə əlavə olundu', 'Təbriklər', ['timeout' => 3000, 'position' => 'top-center']);
//        sweetalert()->addWarning('Məhsulunuz uğurla səbətə əlavə olundu');

        $this->emit('updateCart');
    }
    public function render()
    {
        return view('livewire.product-detail');
    }
}
