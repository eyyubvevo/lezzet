@extends('front.layouts.app')
@section('content')
    <section>
        <div class="section-title">
            <h1>{{$about->title}}</h1>
        </div>
        <div class="container">
            <div class="about-img">
                <img src="{{asset('storage/'.$about->image)}}" alt="{{$about->title}}">
            </div>
            <div class="about-text">
                {!! $about->content !!}
            </div>
        </div>
    </section>
@endsection
