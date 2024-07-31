@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light">Haqqımızda Səhifəsi </span>
            </h4>
            <div class="row">
                <div class="col-xxl">
                    <div class="card mb-4">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

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

                                        <form action="{{  route('admin.about.update',['id' => $about->id])}}"
                                              method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="tab-content">
                                                @foreach($languages as $key => $language)
                                                    <div class="tab-pane fade{{ $key === 0 ? ' show active' : '' }}"
                                                         id="{{$language}}"
                                                         role="tabpanel"
                                                    >
                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="title">Haqqımızda
                                                                Səhifə Başlığı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="title_{{ $language }}"
                                                                       name="title_{{ $language }}"
                                                                       placeholder="Haqqımızda səhifə başlığı {{ $language }} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       value="{{$about->getTranslation('title',$language)}}"
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="content">Haqqımızda
                                                                Səhifə Mətni</label>
                                                            <div class="col-sm-10">
                                                                <textarea name="content_{{ $language }}" @if($language == 'az') required @endif
                                                                          id="content_{{ $language }}">{!! $about->getTranslation('content',$language) !!}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="row mb-3">
                                                <h5 class="card-header">Haqqımızda Səhifəsi Şəkli</h5>
                                                <!-- Account -->

                                                <div class="card-body">
                                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                        <img
                                                            src=" @if($about && $about->image) {{ asset('storage/'.$about->image) }} @else {{asset('backend/assets/img/default.jpg')}} @endif"
                                                            alt="service-avatar"
                                                            class="d-block rounded"
                                                            height="100"
                                                            width="100"
                                                            id="uploadedAvatar"
                                                        />
                                                        <div class="button-wrapper">
                                                            <label for="upload" class="btn btn-primary me-2 mb-4"
                                                                   tabindex="0">
                                                                <span class="d-none d-sm-block">Upload new photo</span>
                                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                                <input
                                                                    type="file"
                                                                    id="upload"
                                                                    class="account-file-input"
                                                                    hidden
                                                                    name="image"
                                                                    @if(isset($about)) @else required @endif
                                                                    accept="image/png, image/jpeg"
                                                                />
                                                            </label>
                                                            <button type="button"
                                                                    class="btn btn-outline-secondary account-image-reset mb-4">
                                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">Reset</span>
                                                            </button>

                                                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size
                                                                of 800K</p>
                                                        </div>
                                                    </div>
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
                    .create(document.querySelector('#content_{{ $language }}'), {
                        ckfinder: {
                            uploadUrl: "{{route('admin.about.upload',['_token' => csrf_token()])}}"
                        },
                        height: '600px',
                        placeholder: '{{'Haqqımızda Səhifəsi Mətni '. $language.' dilində daxil edin...' }}'
                    })
                    .catch(error => {
                        console.error(error);
                    });
                @endforeach

                $(document).ready(function () {
                    $('#is_published').select2();
                });
            </script>
    @endpush
