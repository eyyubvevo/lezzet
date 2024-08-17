@extends('admin.layouts.master')
@push('styles')
    <style>
        .bootstrap-tagsinput .tag {
            color: #0d6efd;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light">SEO Parametrləri /</span> {{ isset($page_meta_tag) ? $page_meta_tag->page_name. ' Yenilə' : 'SEO Parametrlərinə Əlavə Et' }}
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
                                            action="{{ isset($page_meta_tag) ? route('admin.page_meta_tags.update',['id' => $page_meta_tag->id]) : route('admin.page_meta_tags.store')}}"
                                            method="post" enctype="multipart/form-data">
                                            @csrf
                                            @if(isset($page_meta_tag))
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
                                                                   for="title_{{ $language }}">Səhifə SEO
                                                                Başlığı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="title_{{ $language }}"
                                                                       name="title_{{ $language }}"
                                                                       placeholder="Səhifə SEO başlığını {{ $language }} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       @if(isset($page_meta_tag)) value="{{$page_meta_tag->getTranslation('title',$language)}}" @endif
                                                                />
                                                                <br>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="description_{{ $language }}">Səhifə SEO
                                                                Mətni</label>
                                                            <div class="col-sm-10">
                                                                <textarea @if($language == 'az') required @endif class="form-control" id="description_{{$language}}" name="description_{{$language}}"> @if(isset($page_meta_tag)){{$page_meta_tag->getTranslation('description',$language)}}@endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="keywords_{{ $language }}">Səhifə SEO
                                                                Açar Sözləri</label>
                                                            <div class="col-sm-10">
                                                                <input @if($language == 'az') required @endif
                                                                class="form-control"
                                                                       id="keywords_{{ $language }}"
                                                                       name="keywords_{{ $language }}"
                                                                       type="text"
                                                                       @if(isset($page_meta_tag))
                                                                           value=" {{$page_meta_tag->getTranslation('keywords',$language)}}"
                                                                       @endif
                                                                       data-role="tagsinput"/>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                            @if(!isset($page_meta_tag))
                                            <div class="row mb-3">
                                                <label class="col-sm-2 col-form-label"
                                                       for="page_name">Səhifə Adı</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control"
                                                           id="page_name"
                                                           name="page_name"
                                                           placeholder="Səhifə adını daxil edin..."
                                                           required
                                                    />
                                                </div>
                                            </div>
                                            @endif
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
@endpush
