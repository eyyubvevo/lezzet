@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light">Tez Tez Verilən Suallar /</span> {{ isset($general_questions) ? $general_questions->title. ' Yenilə' : 'Tez Tez Verilən Suallara Əlavə Et' }}
            </h4>
            <div class="row">
                <div class="col-xxl">
                    <div class="card mb-4">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="nav-align-top mb-4">
                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach($languages as $key => $language)
                                                <li class="nav-item">
                                                    <button
                                                        type="button"
                                                        class="nav-link{{ $key === 0 ? ' active' : '' }}"
                                                        role="tab"
                                                        data-bs-toggle="tab"
                                                        data-bs-target="#{{$language}}"
                                                        aria-controls="{{$language}}"
                                                        aria-selected="true"
                                                    >
                                                        {{ $language }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <form
                                            action="{{ isset($general_questions) ? route('admin.general_questions.update',['id' => $general_questions->id]) : route('admin.general_questions.store')}}"
                                            method="post" enctype="multipart/form-data">
                                            @csrf
                                            @if(isset($general_questions))
                                                @method('PUT')
                                            @else
                                                @method('POST')
                                            @endif
                                            <div class="tab-content">
                                                @foreach($languages as $key => $language)
                                                    <div class="tab-pane fade{{ $key === 0 ? ' show active' : '' }}"
                                                         id="{{$language}}"
                                                         role="tabpanel"
                                                    >
                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="question_{{ $language }}">Sual</label>
                                                            <div class="col-sm-10">
                                                                    <input type="text" class="form-control" id="question_{{ $language }}"
                                                                           name="question_{{ $language }}"
                                                                           placeholder="Sualı {{ $language }} dilində daxil edin..." @if($language == 'az') required @endif
                                                                           @if(isset($general_questions)) value="{{$general_questions->getTranslation('question',$language)}}" @endif
                                                                    />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="answer_{{ $language }}">Cavab</label>
                                                            <div class="col-sm-10">
                                                                    <textarea @if($language == 'az') required @endif name="answer_{{ $language }}" id="answer_{{ $language }}">@if(isset($general_questions)){!! $general_questions->getTranslation('answer',$language) !!}@endif</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-2 col-form-label" for="status">Statusu</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" id="status" name="status">
                                                        <option
                                                            @if(isset($general_questions) && $general_questions->status) selected
                                                            @endif value="1">Aktiv
                                                        </option>
                                                        <option
                                                            @if(isset($general_questions) && !$general_questions->status) selected
                                                            @endif value="0">Gözləmədə
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-sm-10">
                                                    <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @push('scripts')
            <script>
                @foreach($languages as $language)
                ClassicEditor
                    .create(document.querySelector('#answer_{{ $language }}'), {
                        ckfinder: {
                            uploadUrl: "{{route('admin.general_questions.upload',['_token' => csrf_token()])}}"
                        },
                        height: '600px',
                        placeholder: '{{'Cavabı '. $language.' dilində daxil edin...' }}'
                    })
                    .catch(error => {
                        console.error(error);
                    });
                @endforeach
                $(document).ready(function () {
                    $('#status').select2();
                });
            </script>
    @endpush
