<div>

    <div class="section-title">
        <h1>{{__('website.order_basket')}}</h1>
    </div>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="row">

                        @foreach($carts as $cart)
                            <div class="col-12" wire:key="{{ $loop->index }}">
                                <div class="order-cart">
                                    <div class="order-cart-left">
                                        <div class="order-cart-img">
                                            <img src="{{asset('storage/'.$cart->attributes->image->image)}}"  alt="{{$cart->name}}">
                                        </div>
                                        <div class="order-cart-content">
                                            <a href="#"><h3>{{$cart->name}} </h3></a>
                                            <span>
                                                @if($cart->attributes->discount != null)
                                                    <span style="text-decoration: line-through;color: #999;">{{$cart->price}} {{__('website.azn')}}</span>
                                                    <span style="color: red;"> {{$cart->attributes->discount}} {{__('website.azn')}}</span>
                                                @elseif($cart->attributes->custom_discount != null)
                                                    <span style="text-decoration: line-through;color: #999;">{{$cart->price}} {{__('website.azn')}}</span>
                                                    <span style="color: red;"> {{$cart->attributes->custom_discount}} {{__('website.azn')}}</span>
                                                @else
                                                    <span>{{$cart->price}} {{__('website.azn')}}.</span>
                                                @endif
                                            </span>
                                            <div class="order-cart-btns">
                                                <button wire:click.prevent="qty_minus({{$cart->id}})">-</button>
                                                <span>{{$cart->quantity}}</span>
                                                <button wire:click.prevent="qty_plus({{$cart->id}})">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="remove-order">

                                        <button type="button" wire:click.prevent="cartRemoveItem({{$cart->id}})">
                                           {{__('website.delete_the_order')}}
                                        </button>
                                        <i wire:click.prevent="cartRemoveItem({{$cart->id}})" class="fas fa-trash-alt fa-lg delete-icon" style="color: red;"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
                    <!-- <div class="order-summary">
                        <h4>Səbətin xülasəsi</h4>

                    </div> -->

                    <div class="shopping-basket-summary">
                        <div class="shopping-basket-summary-content">
                            <h5 class="summary-basket">{{__('website.cart_summary')}}</h5>
                            <div class="payment-data ">
                                <div class="payment-type">
                                    <span class="total">{{__('website.amount')}}</span>
                                    {{--                                    <span class="fianl-payment">Ödəniləcək məbləğ</span>--}}
                                    <span class="total">{{__('website.total_amount')}}</span>
                                    {{--                                    <input type="text" placeholder="kupon">--}}
                                    <!-- <label class="cash ">
                                        <input type="radio" name="payment-type">
                                        <span>Nəğd</span>
                                    </label> -->
                                </div>

                                <div class="payment-type ">
                                    <span>{{$total}} {{__('website.azn')}}</span>
                                    <span>{{$subtotal}} {{__('website.azn')}}</span>
                                    {{--                                    <button>Yadda saxla</button>--}}
                                    <!-- <label class="credit flex">
                                        <input type="radio" name="payment-type">
                                        <span>Kredit</span>
                                    </label> -->
                                </div>

                            </div>

                        </div>
                        @if(\Cart::isEmpty() == false)
                            <div class="shopping-button ">
                                <form action="{{route('order.form')}}" method="POST">
                                    @csrf
                                    <button type="submit" class="comfirm-btn">{{__('website.confirm')}}</button>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
