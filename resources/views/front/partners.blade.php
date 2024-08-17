@extends('front.layouts.app')
@section('content')
    <div class="section-title">
        <h1>{{__('website.partners')}}</h1>
    </div>
    <section>
        <div class="container">
            <div class="row">
                @foreach($partners as $partner)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                    <a target="_blank" href="{{$partner->url}}">
                        <div class="partners-cart">
                            <img src="{{asset('storage/'.$partner->image)}}" alt="{{$partner->name}}">
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
