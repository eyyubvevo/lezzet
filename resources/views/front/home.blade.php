@extends('front.layouts.app')
@section('og')
    @php
        $page_meta_tag = App\Models\PageMetaTag::where('page_name','Ana Səhifə')->first();
    @endphp
    <meta name="og:type" content="website">
    <meta name="og:site_name" content="{{$page_meta_tag->getTranslation('title', app()->getLocale())}}">
    <meta name="og:title" content="{{$page_meta_tag->getTranslation('title', app()->getLocale())}}">
    <meta name="og:description" content="{{$page_meta_tag->getTranslation('description', app()->getLocale())}}" />
    <meta name="og:image" content="{{asset('storage/'.setting('logo'))}}" />
    <meta name="og:url" content="{{config('app.url')}}">
    <meta name="og:locale" content="{{app()->getLocale()}}">
    <meta name="og:image:type" content="image/png">
@endsection
@section('content')
    <section>
        <div class="site-area">
            <div class="site-slider">
                @foreach($sliders as $slider)
                    <div class="site-slider-cart">
                        <img src="{{asset('storage/'.$slider->image)}}" alt="{{$slider->title}}">
                        <div class="site-text">
                            <h1 style="text-align: center">{{$slider->title}}<br></h1>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="catalog-area">
                <h2 class="catalog-area-title">{{__('website.catalog')}}</h2>

                <div class="row">

                    @foreach($categories as $category)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12 ">
                            <div class="catalog-cart">
                                <div class="catalog-cart-img">
                                    <a href="{{localized_route('home.products',['slug' => $category->slug])}}" class="catalog-cart-image">
                                        <img src="{{asset('storage/'.$category->image)}}"
                                                                               alt="{{$category->name}}"></a>
                                    <div class="catalog-cart-img-absolute">
                                        <a href="{{localized_route('home.products',['slug' => $category->slug])}}" alt="{{$category->name}}">{{__('website.buy_now')}}</a>
                                        <span>{{$category->products->where('status',1)->count()}} {{__('website.product')}}</span>
                                    </div>
                                </div>

                                <div class="catalog-cart-title">
                                    <a href="{{localized_route('home.products',['slug' => $category->slug])}}"><h3 style="text-align: center;">{{$category->name}}</h3></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="faq-area">
                <h2 class="faq-area-title">{{__('website.general_questions')}}</h2>
                <div class="faq-card">
                    <div class="acc-container">
                        @foreach($general_questions as $gn)

                            <button class="acc-btn">{{$gn->question}}</button>
                            <div class="acc-content">
                                <p>
                                    {!! $gn->answer !!}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

