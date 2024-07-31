<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Cart;
use App\Repositories\EloquentContactRepository;
use App\Repositories\EloquentPageMetaTagRepository;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Webmaster;
use App\Models\PageMetaTag;

class ShoppingCartList extends Component
{
    public $carts;
    public $total;
    public $subtotal;
    public $tax;
    public $discount;
    
    protected EloquentContactRepository $contactRepository;
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;
    
    
   
    
    public function mount(EloquentContactRepository $contactRepository,EloquentPageMetaTagRepository $pageMetaTagRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->pageMetaTagRepository = $pageMetaTagRepository;
    }

    
    
    
    public function cartRemoveItem($id)
    {
        \Cart::remove($id);
        noty()->addFlash('success', __('website.remove_success_cart'), ['timeout' => 3000, 'position' => 'top-center']);
        if (\Cart::getContent()->sum('quantity') == 0){
            return redirect()->to('/');
        }
        $this->emit('updateCart');
    }
     public function qty_minus($id)
     {
         \Cart::update($id,[
             'quantity' => -1
         ]);
         $this->emit('updateCart');
     }

    public function qty_plus($id)
    {
        \Cart::update($id,[
            'quantity' => +1
        ]);
        $this->emit('updateCart');
    }
    public function render()
    {
        $this->total = \Cart::getTotal();
        $this->subtotal = \Cart::getSubTotal();
        $this->carts = Cart::getContent()->sortDesc();
     
       $page_meta_tag = PageMetaTag::where('page_name','like','%Səbət%')->first();
          
     
        if ($page_meta_tag) {
              
            Meta::setTitleSeparator('->')
                ->prependTitle(setting('title'))
                ->setTitle($page_meta_tag->getTranslation('title', app()->getLocale()))
                ->setDescription($page_meta_tag->getTranslation('description', app()->getLocale()))
                ->setKeywords($page_meta_tag->getTranslation('keywords', app()->getLocale()))
                ->setRobots('Vaqif,Shamil')
                ->setContentType('text/html')
                ->setViewport('width=device-width, initial-scale=1')
                ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
                ->setCanonical(config('app.url'));
        }
        
        return view('livewire.shopping-cart-list')->extends('front.layouts.app')->section('content');
    }
}
