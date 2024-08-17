<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Tag;
use Livewire\Component;

class Search extends Component
{
    public $searchTerm = '';

    public function render()
    {
        $products = [];

        if ($this->searchTerm) {
            // $tags = Tag::where('name', 'like', '%' . $this->searchTerm . '%')->get();
            $tags = Tag::where('name->' . app()->getLocale(), 'like', '%' . $this->searchTerm . '%')->get();
           
            foreach ($tags as $tag) {
                $products = array_merge($products, $tag->products->toArray());
            }
            
            $products = array_unique($products, SORT_REGULAR);
        }

        return view('livewire.search', compact('products'));
    }
}
