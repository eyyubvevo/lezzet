@extends('front.layouts.app')
@section('og')
    @php
        $page_meta_tag = App\Models\PageMetaTag::where('page_name','Blog')->first();
    @endphp
    <meta name="og:type" content="website">
    <meta name="og:site_name" content="{{$page_meta_tag->getTranslation('title', app()->getLocale())}}">
    <meta name="og:title" content="{{$new->getTranslation('title', app()->getLocale())}}">
    <meta name="og:description" content="{{$new->getTranslation('content', app()->getLocale())}}" />
    <meta name="og:image" content="{{asset('storage/'.$new->image)}}" />
    <meta name="og:url" content="{{localized_route('news.show',['slug' => $new->slug])}}">
    <meta name="og:locale" content="{{app()->getLocale()}}">
    <meta name="og:image:type" content="image/png">
@endsection
@section('content')
<section>
    <div class="section-title">
        <h1>{{$new->title}}</h1>
    </div>
    <div class="container">
        <div class="about-img">
            <img src="{{asset('storage/'.$new->image)}}" alt="{{$new->title}}">
        </div>
        <div class="about-text">
            {!! $new->content !!}
        </div>
    </div>
</section>
@endsection
