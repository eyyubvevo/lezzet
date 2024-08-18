
@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Xəbərlər /</span> {{ isset($news) ? $news->title. ' Yenilə' : 'Xəbər Əlavə Et' }}</h4>
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
                                                    <button type="button" class="nav-link{{ $key === 0 ? ' active' : '' }}" role="tab" data-bs-toggle="tab" data-bs-target="#{{$language}}" aria-controls="{{$language}}" aria-selected="true">
                                                        {{ $language }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <form action="{{ isset($news) ? route('admin.news.update',['id' => $news->id]) : route('admin.news.store')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @if(isset($news))
                                                @method('PUT')
                                            @else
                                                @method('POST')
                                            @endif
                                            <div class="tab-content">
                                                @foreach($languages as $key => $language)
                                                    <div class="tab-pane fade{{ $key === 0 ? ' show active' : '' }}" id="{{$language}}" role="tabpanel">
                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="meta_title_{{ $language }}">Xəbər SEO Başlığı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="meta_title_{{ $language }}" name="meta_title_{{ $language }}" placeholder="Xəbər SEO başlığını {{ $language }} dilində daxil edin..." @if($language == 'az') required @endif @if(isset($news)) value="{{$news->getTranslation('meta_title',$language)}}" @endif />
                                                                <br>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="meta_description_{{ $language }}">Xəbər SEO Mətni</label>
                                                            <div class="col-sm-10">
                                                                        <textarea @if($language == 'az') required @endif class="form-control" id="meta_description_{{$language}}" name="meta_description_{{$language}}">@if(isset($news)){{$news->getTranslation('meta_description',$language)}}@endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="meta_keywords_{{ $language }}">Xəbər SEO Açar Sözləri</label>
                                                            <div class="col-sm-10">
                                                                <input @if($language == 'az') required @endif class="form-control" id="meta_keywords_{{ $language }}" name="meta_keywords_{{ $language }}" type="text" @if(isset($news)) value=" {{$news->getTranslation('meta_keywords',$language)}}" @endif data-role="tagsinput"/>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="title_{{ $language }}">Xəbər adı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="title_{{ $language }}" name="title_{{ $language }}" placeholder="Xəbər adını {{ $ language }} dilində daxil edin..." @if($language == 'az') required @endif @if(isset($news)) value="{{$news->getTranslation('title',$language)}}" @endif />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="short_content_{{ $language }}">Xəbər Qısa Mətni</label>
                                                            <div class="col-sm-10">
                                                                <textarea class="form-control" id="short_content_{{ $language }}" name="short_content_{{ $language }}">@if(isset($news)){{$news->getTranslation('short_content',$language)}}@endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="content_{{ $language }}">Xəbər Mətni</label>
                                                            <div class="col-sm-10">
                                                                <textarea class="form-control" id="content_{{ $language }}" name="content_{{ $language }}">@if(isset($news)){{$news->getTranslation('content',$language)}}@endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label" for="news_image">Xəbər şəkli</label>
                                                            <div class="col-sm-10">
                                                                <input type="file" class="form-control" id="news_image" name="news_image[]" multiple>
                                                                <div id="image-preview-zone" class="preview-images-zone"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="is_active">Xəbərin aktivliyi</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="is_active" name="is_active">
                                                            <option value="1" @if(isset($news) && $news->is_active == 1) selected @endif>Aktiv</option>
                                                            <option value="0" @if(isset($news) && $news->is_active == 0) selected @endif>Deaktiv</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Yadda Saxla</button>
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

            $('#news_image').on('change', function () {
                let files = this.files;
                let previewZone = $('#image-preview-zone');
                previewZone.empty(); // Clear previous previews

                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            let previewImage = `
                                <div class="preview-image preview-show-${i}">
                                    <div class="image-zone"><img src="${e.target.result}" /></div>
                                    <div class="image-cancel" data-no="${i}">x</div>
                                </div>`;
                            previewZone.append(previewImage);
                        }
                        reader.readAsDataURL(files[i]);
                    }

                    $('.image-cancel').on('click', function () {
                        let no = $(this).data('no');
                        $(".preview-image.preview-show-" + no).remove();
                    });
                }
            });
        });
    </script>
@endpush
