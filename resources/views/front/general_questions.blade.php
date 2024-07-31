@extends('front.layouts.app')
@section('content')
    <section>
        <div class="section-title">
            <h1>{{__('website.general_questions')}}</h1>
        </div>
        <div class="container">
            <div class="faq-area">
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
