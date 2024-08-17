@extends('front.layouts.app')
@section('content')
        <section class="">
            <div class="container">
                <div class="checkout-succes">
                    <h2>{{__('website.thanks_for_your_order')}}</h2>
                    <!--<span>{{__('website.pay_at_the_door')}}</span>-->
                                        <h2>{{__('website.checkout_success')}}</h2>

                    <div class="home-link">
                        <a href="{{localized_route('home')}}">{{__('website.return_to_home_page')}}</a>
                    </div>
                </div>
            </div>
        </section>
@endsection
