@extends('front.layouts.app')
@section('og')
    @php
        $page_meta_tag = App\Models\PageMetaTag::where('page_name','Blog')->first();
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
        <div class="section-title">
            <h1>{{__('website.news')}}</h1>
        </div>
        <div class="container">
            <div class="row">
                @foreach($news as $new)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                        <a href="{{localized_route('news.show',['slug' => $new->slug])}}">
                            <div class="blog-cart">
                                <div class="blog-cart-img">
                                    <img src="{{asset('storage/'.$new->image)}}" alt="{{$new->title}}">
                                </div>

                                <div class="blog-cart-content">
                                    <h3>{{$new->title}}</h3>
                                    <!-- {!! $new->short_content !!} -->
                                    {!! Str::limit($new->short_content, 100) !!}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endsection
