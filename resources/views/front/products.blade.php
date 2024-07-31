@extends('front.layouts.app')
@section('og')
    @php
        $page_meta_tag = App\Models\PageMetaTag::where('page_name','Məhsullar')->first();
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

    <div class="section-title">
        <h1>{{ $category->name }}</h1>
    </div>
   @livewire('products',['category' => $category,'attributeProductCounts' => $attributeProductCounts])
@endsection
