@extends('front.layouts.app')
@section('og')
    @php
        $page_meta_tag = App\Models\PageMetaTag::where('page_name','Məhsullar')->first();
        $image = $product->images->first();
    @endphp
    <meta name="og:type" content="website">
    <meta name="og:site_name" content="{{$page_meta_tag->getTranslation('title', app()->getLocale())}}">
    <meta name="og:title" content="{{$product->getTranslation('title', app()->getLocale())}}">
    <meta name="og:description" content="{!!$product->getTranslation('content', app()->getLocale())!!}" />
    <meta name="og:image" content="{{asset('storage/'.$image->image)}}" />
    <meta name="og:url" content="{{localized_route('home.product.show',['slug' => $product->slug,'categorySlug' => $product->category->slug])}}">
    <meta name="og:locale" content="{{app()->getLocale()}}">
    <meta name="og:image:type" content="image/png">
@endsection
@section('content')
    <section>
        <div class="section-title">
            <p  class = "section-title-p">{{$product->title}}</p>
        </div>
       @livewire('product-detail',['product' => $product])
    </section>
@endsection
