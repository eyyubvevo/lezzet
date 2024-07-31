<div>
    <input type="text" wire:model="searchTerm" placeholder="{{__('website.search')}}" id="myInput"
           oninput="livewireSearch()">
         @foreach($products as $product)
        @php
            $image = App\Models\Product::find($product['id'])->images->first();
            $category = App\Models\Product::find($product['id'])->category->first();
        @endphp
        <!--<a href="{{localized_route('home.product.show',['slug' => $product['slug']['az'],'categorySlug' => $category->slug])}}">-->
        <a href="{{route(app()->getLocale().'.home.product.show',['slug' => $product['slug'][app()->getLocale()],'categorySlug' => $category->slug])}}">

            <span style="font-size: 14px;">{{ $product['title'][app()->getLocale()] }}</span>

            <img style="width: 50px; height: 50px; object-fit: contain; border-radius: 10px;"
                 src="{{ asset('storage/'.$image->image) }}" alt="img">

        </a>
    @endforeach
</div>

