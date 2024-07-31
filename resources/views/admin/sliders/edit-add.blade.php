@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light">Slayderlər /</span> {{ isset($slider) ? $slider->title. ' Yenilə' : 'Slayder Əlavə Et' }}
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
                                        {!! Form::open(['method' => isset($slider) ? 'PUT' : 'POST','route' => isset($slider) ? ['admin.sliders.update',$slider->id] : ['admin.sliders.store'], 'files' => true]) !!}
                                        {!! Form::token() !!}
                                        @if(isset($slider))
                                            @method('PUT')
                                        @else
                                            @method('POST')
                                        @endif
                                        <div class="tab-content">
                                            @foreach($languages as $key => $language)
                                                <div class="tab-pane fade{{ $key === 0 ? ' show active' : '' }}"
                                                     id="{{$language}}"
                                                     role="tabpanel">
                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label" for="title_{{$language}}">Tərəfdaş adı</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="title_{{$language}}" name="title_{{$language}}"
                                                                   placeholder="Slayder adını {{$language}} dilində daxil edin..." @if($language == 'az') required @endif
                                                                   @if(isset($slider)) value="{{$slider->getTranslation('title',$language)}}" @endif
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="row mb-3">
                                                <h5 class="card-header">Slayder Şəkli</h5>
                                                <!-- Account -->
                                                <div class="card-body">
                                                    <div
                                                        class="d-flex align-items-start align-items-sm-center gap-4">
                                                        <img
                                                            src=" @if(isset($slider) && $slider->image) {{ asset('storage/'.$slider->image) }} @else {{asset('backend/assets/img/default.jpg')}} @endif"
                                                            alt="slider-avatar"
                                                            class="d-block rounded"
                                                            height="100"
                                                            width="100"
                                                            id="uploadedAvatar"
                                                        />
                                                        <div class="button-wrapper">
                                                            <label for="upload" class="btn btn-primary me-2 mb-4"
                                                                   tabindex="0">
                                                                    <span
                                                                        class="d-none d-sm-block">Upload new photo</span>
                                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                                <input
                                                                    type="file"
                                                                    id="upload"
                                                                    class="account-file-input"
                                                                    hidden
                                                                    name="image"
                                                                    @if(!isset($slider)) required @endif
                                                                    accept="image/png, image/jpeg"
                                                                />
                                                            </label>
                                                            <button type="button"
                                                                    class="btn btn-outline-secondary account-image-reset mb-4">
                                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">Reset</span>
                                                            </button>

                                                            <p class="text-muted mb-0"> Allowed JPG, GIF or PNG. Max
                                                                size of 800K</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                {!! Form::label('is_active', 'Slayder Statusu', ['class' => 'col-sm-2 col-form-label'])!!}

                                                <div class="col-sm-10">
                                                    <select class="form-control" id="status" name="status">
                                                        <option @if(isset($slider) && $slider->status) selected
                                                                @endif value="1">
                                                            Aktiv
                                                        </option>
                                                        <option @if(isset($slider) && !$slider->status) selected
                                                                @endif value="0">Gözləmədə
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end">
                                                <div class="col-sm-10">
                                                    {!! Form::button('Yadda Saxla!',['class' => 'btn btn-primary','type' => 'submit']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
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
            .create(document.querySelector('#description_{{ $language }}'), {
                {{--ckfinder: {--}}
                {{--    uploadUrl: "{{route('admin.sliders.upload',['_token' => csrf_token()])}}"--}}
                {{--},--}}
                height: '600px',
                placeholder: '{{'Slayder Mətni '. $language.' dilində daxil edin...' }}'
            })
            .catch(error => {
                console.error(error);
            });
        @endforeach
        $(document).ready(function () {
            $('#is_active').select2();
        });
    </script>
@endpush
