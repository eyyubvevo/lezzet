<?php

namespace App\Http\Livewire;

use App\Models\Product;
//use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;

class Products extends Component
{
    // use WithPagination;
    // protected $paginationTheme = 'bootstrap';
    public $category;
    public $attributeProductCounts;
    public $product;
    public $products;
    public $selectedAttributes = [];
    public $minPrice;
    public $maxPrice;

    public $sortType = '';

    public $minPriceInput;
    public $maxPriceInput;
    public $perPage = 15;
    protected $listeners = [
        'load-more' => 'loadMore'
    ];
     public function loadMore()
    {
        $this->perPage = $this->perPage + 5;
    }
    public function mount()
    {
        $this->minPrice =Product::getMinPrice();
        $this->maxPrice =Product::getMaxPrice();
    }

    public function addToCart($id)
    {
        $this->product = Product::find($id);
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

        $this->emit('updateCart');
    }
    public function render()
    {
        $this->products = $this->category->products;
        
     
        
        // $this->minPrice = $this->products->min('price');
        // $this->maxPrice = $this->products->max('price');
        
        //  if (count($this->selectedAttributes, COUNT_RECURSIVE) > 0) {
        //     $attributeIds = collect($this->selectedAttributes)->flatten()->toArray();
        //     $this->products = $this->products->filter(function ($product) use ($attributeIds) {
        //         return in_array($product->attribute_id, $attributeIds);
        //     });
        // }

        // if (!empty($this->minPriceInput) && !empty($this->maxPriceInput)) {
        //     $this->products = $this->products->whereBetween('price', [$this->minPriceInput, $this->maxPriceInput]);
        // }
        // elseif (!empty($this->minPriceInput)) {
        //     $this->products = $this->products->where('price', '>=', $this->minPriceInput);
        // }
        // elseif (!empty($this->maxPriceInput)) {
        //     $this->products = $this->products->where('price', '<=', $this->maxPriceInput);
        // }


        if ($this->sortType === 'price_high_to_low') {
            $this->products = $this->products->sortByDesc('price');
        } elseif ($this->sortType === 'price_low_to_high') {
            $this->products = $this->products->sortBy('price');
        }

        return view('livewire.products');
    }

}
