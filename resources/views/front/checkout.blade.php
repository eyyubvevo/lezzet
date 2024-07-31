@extends('front.layouts.app')
@section('content')
    <section>
        <div class="section-title">
            <h1>{{__('website.complete_the_order')}}</h1>
        </div>
        <div class="container">
            <div class="row">

                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12" style="margin-bottom: 50px;">
                    <div class="checkout-order-heading" style="margin-bottom: 20px;">
                        <h4>{{__('website.your_order')}}</h4>
                        <span>{{__('website.total')}}: <b>{{$subtotal}} {{__('website.azn')}}</b></span>
                    </div>
                    <table class="order-table">
                        <thead>
                        <tr>
                            <th scope="col">{{__('website.shape_of_the_product')}}</th>
                            <th scope="col">{{__('website.product_name')}}</th>
                            <th scope="col">{{__('website.price_of_the_product')}}</th>
                            <th scope="col">{{__('website.quantity_of_the_product')}}</th>
{{--                            <th scope="col">Endirim</th>--}}
                            <th scope="col">{{__('website.total')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($carts as $cart)
                            <tr>
                                <td scope="row" data-label="Şəkil"><img src="{{asset('storage/'.$cart->attributes->image->image)}}" alt="{{$cart->name}}"></td>
                                <td data-label="Məhsul">{{$cart->name}}</td>
                                <td data-label="Qiymət">
                                    @if($cart->attributes->discount != null)
                                        <span style="text-decoration: line-through;color: #999;">{{$cart->price}} {{__('website.azn')}}</span>
                                        <span
                                            style="color: red;"> {{$cart->attributes->discount}} {{__('website.azn')}}</span>
                                    @elseif($cart->attributes->custom_discount != null)
                                        <span style="text-decoration: line-through;color: #999;">{{$cart->price}} {{__('website.azn')}}</span>
                                        <span
                                            style="color: red;"> {{$cart->attributes->custom_discount}} {{__('website.azn')}}</span>
                                    @else
                                        <span>{{$cart->price}} {{__('website.azn')}}.</span>
                                    @endif
                                </td>
                                <td data-label="Miqdar">{{$cart->quantity}}</td>
{{--                                <td data-label="Endirim">0</td>--}}
                                <td data-label="Toplam">{{$cart->getPriceSum()}} {{__('website.azn')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12">
                    <h4 style="font-size: 22px; text-align: center; margin-bottom: 30px;">{{__('website.fill_in_your_information')}}</h4>
                    <form action="{{route('order.form.store')}}" class="checkout-data" method="POST">
                        @csrf
                        @method('POST')
                        <input type="text" placeholder="{{__('website.first_name')}}" name="name" required>
                        <!-- <input type="email" placeholder="{{__('website.email')}}" name="email" required> -->
                        <!-- <input type="text" placeholder="{{__('website.country')}}" name="country" required> -->
                        <!-- <input type="text" placeholder="{{__('website.city')}}" name="city" required> -->
                        <input type="text" placeholder="{{__('website.address')}}" name="address" required>
                        <input type="text" placeholder="{{__('website.phone')}}" name="phone" required>
                        <textarea name="message" id="" cols="30" rows="3" placeholder="{{__('Əlavə qeydlərinizi bura yazın')}}"></textarea>
                        <div style="display: contents; justify-content: center; align-items: center;">
                            <button type="submit" class="comfirm-btn">{{__('Sifariş edin')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
