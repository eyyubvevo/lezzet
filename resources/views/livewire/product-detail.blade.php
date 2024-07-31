<div class="container">
    <div class="row">
        <div class="col-lg-5">
            <div class="product-detail-swiper">
                <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                    <div class="swiper-wrapper">
                        @foreach($product->images as $image)
                            <div class="swiper-slide" onclick="openModal();currentSlide(1)">
                                <img src="{{asset('storage/'.$image->image)}}" alt="{{$product->title}}"/>
                            </div>
                        @endforeach

                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>


                <div thumbsSlider="" class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($product->images as $image)
                            <div class="swiper-slide">
                                <img src="{{asset('storage/'.$image->image)}}" alt="{{$product->title}}"/>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="product-detail-right">
                <h1>{{$product->title}}</h1>
                {!! $product->content !!}
                <span class="product-detail-price">
                    @if($product->category->company)
                        <span style="text-decoration: line-through;color: #999;">{{$product->price}} {{__('website.azn')}}</span>
                        <span style="color: red;">{{$product->getPriceWithDiscount() }} {{__('website.azn')}}</span>
                    @elseif($product->is_discountable)
                        <span style="text-decoration: line-through;color: #999;">{{$product->price}} {{__('website.azn')}}</span>
                        <span style="color: red;">{{$product->price - ($product->discount * $product->price / 100) }} {{__('website.azn')}}</span>
                    @else
                        <span>{{$product->price}} {{__('website.azn')}}</span>
                    @endif

                </span>

                <div class="product-detail-btns">
                    <button type="button" wire:click.prevent="addToCart" class="add-basket">{{__('website.add_to_cart')}}</button>
                    <a class = "product-btn-link" href = "{{localized_route('shoppingCart')}}">{{__('website.look_at_the_basket')}}</a>
                    <a target="__blank" href="{{setting('whatsapp')}}?text={{ __('website.whatsapp_text') }} {{localized_route('home.product.show',['slug' => $product->slug,'categorySlug' => $product->category->slug])}}" id="whatsappal"  class="whatsapp-link"><i class="fa-brands fa-whatsapp fa-lg"></i>{{__('website.order_on_WhatsApp')}}</a>
                </div>
            </div>
        </div>
    </div>


    <!-- The Modal/Lightbox -->
    <div id="galery-myModal" class="galery-modal">
        <span class="galery-close cursor" onclick="closeModal()">&times;</span>
        <div class="galery-modal-content">
            @foreach($product->images as $image)
                <div class="galery-mySlides">
                    {{--                <div class="galery-numbertext">1 / 4</div>--}}
                    <img src="{{asset('storage/'.$image->image)}}" lt="{{$product->title}}" style="width:100%;object-fit:contain">
                </div>
            @endforeach

            <!-- Next/previous controls -->
            <a class="galery-prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="galery-next" onclick="plusSlides(1)">&#10095;</a>

        </div>
    </div>

</div>
