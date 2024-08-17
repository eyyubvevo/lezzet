<div>
    <section>
        <div class="container" >
            <div class="row" style = "justify-content: center;">
<!--                <div class="col-xl-3 col-lg-3">-->
<!--                    <h3>{{__('website.filters')}}</h3>-->
<!--                    <div class="products-filter">-->
<!--                        <form class="form-filter">-->
<!--                            <div class="products-filter-types flex">-->
<!--                                <div class="products-filter-item accordion ">-->
<!--                                    <input type="checkbox" name="collapse" id="handle1">-->
<!--                                    <h2 class="handle">-->
<!--                                        <label for="handle1">-->
<!--                                            <span>{{__('website.strips')}}</span>-->
<!--                                            <i class="fas fa-chevron-down"></i>-->
<!--                                        </label>-->
<!--                                    </h2>-->
<!--                                    <div class="content">-->
<!--                                        <ul class="occasion-list filter-list ">-->
<!--                                            @foreach($category->attributes as $attribute)-->
<!--                                                <li>-->
<!--                                                    <label><input type="checkbox"-->
<!--                                                                  wire:model="selectedAttributes"-->
<!--                                                                  value="{{ $attribute->id }}"-->
<!--                                                                  class="checkbox-round"-->
<!--                                                        >-->
<!--                                                        {{$attribute->getTranslation('name','az')}}-->
<!--                                                        <span>({{ $attributeProductCounts[$attribute->id] ?? 0 }})</span>-->
<!--                                                    </label>-->
<!--                                                </li>-->
<!--                                            @endforeach-->
<!--{{--                                           @foreach($selectedAttributes as $a)--}}-->
<!--{{--                                               {{$a}}--}}-->
<!--{{--                                           @endforeach--}}-->
<!--                                        </ul>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="products-filter-item accordion">-->
<!--                                    <input type="checkbox" name="collapse" id="handle2">-->
<!--                                    <h2 class="handle">-->
<!--                                        <label for="handle2">-->
<!--                                            <span>{{__('website.the_price')}}</span>-->
<!--                                            <i class="fas fa-chevron-down"></i>-->
<!--                                        </label>-->
<!--                                    </h2>-->
<!--                                    <div class="content">-->
<!--                                        <div class="card-price-filter">-->

<!--                                            <div class="filter-price d-flex">-->
<!--                                                <div class="filter-price-min">-->
<!--                                                    <div class="filter-price-top" style="margin-bottom: 10px">-->
<!--                                                        <span>Min:</span>-->
<!--                                                        <span><b>{{$minPrice}}</b></span>-->
<!--                                                    </div>-->
<!--                                                    <input id="minPriceInput" wire:model.live="minPriceInput" type="text">-->
<!--                                                </div>-->

<!--                                                <div class="filter-price-max">-->
<!--                                                    <div class="filter-price-top" style="margin-bottom: 10px">-->
<!--                                                        <span>Max:</span>-->
<!--                                                        <span><b>{{$maxPrice}}</b></span>-->
<!--                                                    </div>-->
<!--                                                    <input id="maxPriceInput" wire:model.live="maxPriceInput" type="text">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                             <h4 class="card-price-filter-title">Price Range Slider</h4> -->
<!--{{--                                            <div class="price-content">--}}-->
<!--{{--                                                <div>--}}-->
<!--{{--                                                    <label>Min</label>--}}-->
<!--{{--                                                    <p id="min-value"> {{$minPrice}}</p>--}}-->
<!--{{--                                                </div>--}}-->
<!--{{--                                                <div>--}}-->
<!--{{--                                                    <label>Max</label>--}}-->
<!--{{--                                                    <p id="max-value"> {{$maxPrice}}</p>--}}-->
<!--{{--                                                </div>--}}-->
<!--{{--                                            </div>--}}-->

<!--{{--                                            <div class="range-slider">--}}-->
<!--{{--                                                <div class="range-fill"></div>--}}-->
<!--{{--                                                <input type="range" class="min-price" wire:model="minPrice"--}}-->
<!--{{--                                                       value="15" min="10" max="500"--}}-->
<!--{{--                                                       step="10"/>--}}-->
<!--{{--                                                <input type="range" class="max-price" wire:model="maxPrice"--}}-->
<!--{{--                                                       value="25" min="10" max="500"--}}-->
<!--{{--                                                       step="10"/>--}}-->
<!--{{--                                            </div>--}}-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                             <div class="search-button">-->
<!--                                <button class="search-btn btn-black">SEARCH</button>-->
<!--                            </div> -->
<!--                        </form>-->
<!--                    </div>-->


<!--                </div>-->
                <div class="col-xl-9 col-lg-9">
                    <!-- your-livewire-component.blade.php -->
                    <div class="product-sorting">
                        <select wire:model="sortType">
                            <option value="">{{__('website.sort_it')}}</option>
                            <option value="recommendation">{{__('website.recommendation')}}</option>
                            <option value="price_high_to_low">{{__('website.price_from_high_to_low')}}</option>
                            <option value="price_low_to_high">{{__('website.price_from_low_to_high')}}</option>
                        </select>
                    </div>
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="product-cart">
                                    <a href="{{localized_route('home.product.show',['slug' => $product->slug,'categorySlug' => $category->slug])}}">
                                        <div class="product-img">
                                            @php
                                                if ($product->images){
                                                     $image = $product->images->first();
                                                }
                                            @endphp
                                            <img src="@if($image) {{asset('storage/'.$image->image)}} @endif" alt="{{$product->attribute->name}}">
                                            <span>{{$product->attribute->name}}</span>
                                        </div>
                                        <div class="product-content">
                                            <h3 class="product-name">{{$product->title}}</h3>
                                            <span class="product-price">
                                                   @if($product->category->company)
                                                    <span style="text-decoration: line-through;color: #999;">{{$product->price}} {{__('website.azn')}}</span>
                                                    <span
                                                        style="color: red;">{{$product->getPriceWithDiscount() }}  {{__('website.azn')}}</span>
                                                @elseif($product->is_discountable)
                                                    <span style="text-decoration: line-through;color: #999;">{{$product->price}}  {{__('website.azn')}}</span>
                                                    <span
                                                        style="color: red;">{{$product->price - ($product->discount * $product->price / 100) }}  {{__('website.azn')}}</span>
                                                @else
                                                    <span>{{$product->price}}  {{__('website.azn')}}.</span>
                                                @endif
                                            </span>
                                        </div>
                                    </a>
                                    <div class="product-btn">
                                        <button type="button" wire:click.prevent="addToCart({{ $product->id }})">{{__('website.add_to_cart')}}</button>
                                        <a target="__blank" href="{{setting('whatsapp')}}?text={{ __('website.whatsapp_text') }} {{localized_route('home.product.show',['slug' => $product->slug,'categorySlug' => $category->slug])}}" id="whatsappal" class="product_whatsapp-link"><i class="fa-brands fa-whatsapp fa-lg"></i>{{__('website.order_on_WhatsApp')}}</a>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    // Min fiyat input alanı
    document.getElementById('minPriceInput').addEventListener('input', function(event) {
        this.value = this.value.replace(/\D/g, ''); // Sadece sayıları tutar
    });

    // Max fiyat input alanı
    document.getElementById('maxPriceInput').addEventListener('input', function(event) {
        this.value = this.value.replace(/\D/g, ''); // Sadece sayıları tutar
    });
</script>

