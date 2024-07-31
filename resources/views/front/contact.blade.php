@extends('front.layouts.app')
@section('content')
    <div class="section-title">
        <h1>{{__('website.contact')}}</h1>
    </div>
    <section>
        <div class="container">
            <h2 class="heading-primary" style="margin-top: 60px;">{{__('website.you_created_relationship_with_us')}} </h2>

            <div class="contact-area">
                <form action="{{route('contact.store')}}" method="post" class="contact-form">
                    @csrf
                    @method('POST')
                    <div class="contact-form-top">
                        <input type="text" placeholder="{{__('website.first_name')}}" name="name" class="contact-input" required>
                    </div>
                    <div class="contact-form-middle">
                        <input type="email" placeholder="{{__('website.email')}}" name="email" class="contact-input" required>
                        <input type="number" placeholder="{{__('website.phone')}}" name="phone" class="contact-input" required>
                    </div>
                    <div class="contact-form-bottom">
                        <textarea name="message" id="" placeholder="{{__('website.message')}}" cols="30" rows="7" class="contact-input" required></textarea>
                    </div>
                    <div style="display: flex; justify-content: center; align-items:center; height: 80px;">
                        <button class="btn-black" type="submit">{{__('website.send_to_message')}}</button>
                    </div>
                </form>

                <div class="contact-communication">
                    <h2 style="margin-bottom: 20px;">{{__('website.contact_info')}}</h2>

                    <div style="display: flex; flex-direction: column; gap: 10px">
                        <span style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-phone"></i>{{setting('phone')}}</span>
                        <span style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-envelope"></i>{{setting('email')}}</span>
                        <span style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-map-marker-alt"></i>{{setting('address_'.app()->getLocale())}}</span>
                        <span style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-map-marker-alt"></i>{{setting('address2_'.app()->getLocale())}}</span>
                    </div>
                    <div class="contact-communication-social">
                        @if(setting('facebook') != null)
                         <a target="_blank" href="{{setting('facebook')}}"><i class="fa-brands fa-facebook fa-lg" style="color:#fff;"></i></a>
                        @endif
                        @if(setting('whatsapp') != null)
                        <a target="_blank" href="{{setting('whatsapp')}}"><i class="fa-brands fa-whatsapp fa-lg" style="color: #fff;"></i></a>
                        @endif
                        @if(setting('youtube') != null)
                        <a target="_blank" href="{{setting('youtube')}}"><i class="fa-brands fa-youtube fa-lg" style="color:#fff"></i></a>
                        @endif
                        @if(setting('tiktok') != null)
                        <a target="_blank" href="{{setting('tiktok')}}"><i class="fa-brands fa-tiktok fa-lg"  style="color:#fff"></i></a>
                        @endif
                        @if(setting('instagram') != null)
                        <a target="_blank" href="{{setting('instagram')}}"><i class="fa-brands fa-instagram fa-lg"  style="color:#fff"></i></a>
                        @endif
                        @if(setting('linkedin') != null)
                        <a target="_blank" href="{{setting('linkedin')}}"><i class="fa-brands fa-linkedin fa-lg" style="color: #fff"></i></a>
                        @endif

                    </div>
                </div>
            </div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="shop-location">
                <iframe src="{{ setting('map_1') }}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
</div>
<div class=" col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="shop-location">
                <iframe src="{{ setting('map_2') }}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
</div>
</div>



        </div>
    </section>
@endsection
