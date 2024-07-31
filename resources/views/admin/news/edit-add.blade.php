@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light">Xəbərlər /</span> {{ isset($news) ? $news->title. ' Yenilə' : 'Xəbər Əlavə Et' }}
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
                                            action="{{ isset($news) ? route('admin.news.update',['id' => $news->id]) : route('admin.news.store')}}"
                                            method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @if(isset($news))
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
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_title_{{ $language }}">Xəbər SEO
                                                                Başlığı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="meta_title_{{ $language }}"
                                                                       name="meta_title_{{ $language }}"
                                                                       placeholder="Xəbər SEO başlığını {{ $language }} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       @if(isset($news)) value="{{$news->getTranslation('meta_title',$language)}}" @endif
                                                                />
                                                                <br>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_description_{{ $language }}">Xəbər SEO
                                                                Mətni</label>
                                                            <div class="col-sm-10">
                                                                        <textarea @if($language == 'az') required @endif class="form-control" id="meta_description_{{$language}}" name="meta_description_{{$language}}"> @if(isset($news)){{$news->getTranslation('meta_description',$language)}}@endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_keywords_{{ $language }}">Xəbər SEO
                                                                Açar Sözləri</label>
                                                            <div class="col-sm-10">
                                                                <input @if($language == 'az') required @endif
                                                                class="form-control"
                                                                       id="meta_keywords_{{ $language }}"
                                                                       name="meta_keywords_{{ $language }}"
                                                                       type="text"
                                                                       @if(isset($news))
                                                                           value=" {{$news->getTranslation('meta_keywords',$language)}}"
                                                                       @endif
                                                                       data-role="tagsinput"/>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="title_{{ $language }}">Xəbər
                                                                adı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="title_{{ $language }}"
                                                                       name="title_{{ $language }}"
                                                                       placeholder="Xəbər adını {{ $language }} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       @if(isset($news)) value="{{$news->getTranslation('title',$language)}}" @endif
                                                                />
                                                                <br>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="short_content_{{ $language }}">Xəbər
                                                                Qısa Mətni</label>
                                                            <div class="col-sm-10">
                                                                    <textarea name="short_content_{{ $language }}"
                                                                              id="short_content_{{ $language }}">@if(isset($news))
                                                                            @if($language == 'az') required @endif
                                                                            {!! $news->getTranslation('short_content',$language) !!}
                                                                        @endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="content_{{ $language }}">Xəbər
                                                                Yazısı</label>
                                                            <div class="col-sm-10">
                                                                <textarea name="content_{{ $language }}"
                                                                          @if($language == 'az') required @endif
                                                                          id="content_{{ $language }}">@if(isset($news))
                                                                        {!! $news->getTranslation('content',$language) !!}
                                                                    @endif</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <div class="row mb-3">
                                                    <h5 class="card-header">Xəbər Şəkli</h5>
                                                    <!-- Account -->
                                                    <div class="card-body">
                                                        <div
                                                            class="d-flex align-items-start align-items-sm-center gap-4">
                                                            <img
                                                                src=" @if(isset($news) && $news->image) {{ asset('storage/'.$news->image) }} @else {{asset('backend/assets/img/default.jpg')}} @endif"
                                                                alt="news-avatar"
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
                                                                        @if(isset($news)) @else required @endif
                                                                        accept="image/png, image/jpeg"
                                                                    />
                                                                </label>
                                                                <button type="button"
                                                                        class="btn btn-outline-secondary account-image-reset mb-4">
                                                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                                                    <span class="d-none d-sm-block">Reset</span>
                                                                </button>

                                                                <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max
                                                                    size of 800K</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="status">Xəbər
                                                        Statusu</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="status"
                                                                name="status">
                                                            <option @if(isset($news) && $news->status) selected
                                                                    @endif value="1">
                                                                Aktiv
                                                            </option>
                                                            <option @if(isset($news) && !$news->status) selected
                                                                    @endif value="0">
                                                                Gözləmədə
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row justify-content-end">
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Yadda Saxla
                                                        </button>
                                                    </div>
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
                        height: '600px',
                        placeholder: '{{'Xəbər Mətni '. $language.' dilində daxil edin...' }}'
                    })
                    .catch(error => {
                        console.error(error);
                    });
                @endforeach
                @foreach($languages as $language)
                ClassicEditor
                    .create(document.querySelector('#short_content_{{ $language }}'), {
                        ckfinder: {
                            uploadUrl: "{{route('admin.news.upload',['_token' => csrf_token()])}}"
                        },
                        height: '600px',
                        placeholder: '{{'Xəbər Qısa Mətni '. $language.' dilində daxil edin...' }}'
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
