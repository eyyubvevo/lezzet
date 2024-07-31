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
                    class="text-muted fw-light">Kateqoriyalər /</span> {{ isset($category) ? $category->title. ' Yenilə' : 'Kateqoriya Əlavə Et' }}
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
                                            action="{{ isset($category) ? route('admin.categories.update',['id' => $category->id]) : route('admin.categories.store')}}"
                                            method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @if(isset($category))
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
                                                                   for="meta_title_{{ $language }}">Kateqoriya SEO
                                                                Başlığı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="meta_title_{{ $language }}"
                                                                       name="meta_title_{{ $language }}"
                                                                       placeholder="Kateqoriya SEO başlığını {{ $language }} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       @if(isset($category)) value="{{$category->getTranslation('meta_title',$language)}}" @endif
                                                                />
                                                                <br>
                                                            </div>
                                                        </div>
                                                        
                                                

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_description_{{ $language }}">Kateqoriya SEO
                                                                Mətni</label>
                                                            <div class="col-sm-10">
                                                                        <textarea @if($language == 'az') required @endif class="form-control" id="meta_description_{{$language}}" name="meta_description_{{$language}}"> @if(isset($category)){{$category->getTranslation('meta_description',$language)}}@endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_keywords_{{ $language }}">Kateqoriya SEO
                                                                Açar Sözləri</label>
                                                            <div class="col-sm-10">
                                                                <input @if($language == 'az') required @endif
                                                                class="form-control"
                                                                       id="meta_keywords_{{ $language }}"
                                                                       name="meta_keywords_{{ $language }}"
                                                                       type="text"
                                                                       @if(isset($category))
                                                                           value=" {{$category->getTranslation('meta_keywords',$language)}}"
                                                                       @endif
                                                                       data-role="tagsinput"/>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="name_{{ $language }}">Kateqoriya
                                                                adı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="name_{{ $language }}"
                                                                       name="name_{{ $language }}"
                                                                       placeholder="Kateqoriya adını {{ $language }} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       @if(isset($category)) value="{{$category->getTranslation('name',$language)}}" @endif
                                                                />
                                                                <br>
                                                            </div>
                                                        </div>
                                                         @if(isset($category))
                                                            <div class="row mb-3">
                                                                <label class="col-sm-2 col-form-label"
                                                                       for="slug_{{$language}}">Kateqoriya Slug</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control"
                                                                           id="slug_{{$language}}"
                                                                           name="slug_{{$language}}"
                                                                           placeholder="Kateqoriya slug {{$language}} dilində daxil edin..."
                                                                           @if($language == 'az') required @endif
                                                                           @if(isset($category)) value="{{$category->getTranslation('slug',$language)}}" @endif
                                                                    />
                                                                </div>
                                                            </div>
                                                       @endif
                                                    </div>
                                                @endforeach

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="parent_id">Kateqoriya
                                                        Valideyn</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="parent_id"
                                                                name="parent_id">
                                                            <option value="">
                                                                Ana Kateqoriya
                                                            </option>
                                                            @foreach($categories as $subcategory)
                                                                @if(isset($category) && $subcategory->id != $category->id)
                                                                    <option
                                                                        @if(isset($category) && $category->id == $subcategory->id) selected
                                                                        @endif value="{{$subcategory->id}}">
                                                                        {{$subcategory->getTranslation('name','az')}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <h5 class="card-header">Kateqoriya Şəkli</h5>
                                                    <!-- Account -->
                                                    <div class="card-body">
                                                        <div
                                                            class="d-flex align-items-start align-items-sm-center gap-4">
                                                            <img
                                                                src=" @if(isset($category) && $category->image) {{ asset('storage/'.$category->image) }} @else {{asset('backend/assets/img/default.jpg')}} @endif"
                                                                alt="category-avatar"
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
                                                                        @if(isset($category)) @else required @endif
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
                                                    <label class="col-sm-2 col-form-label" for="status">Kateqoriya
                                                        Statusu</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="status"
                                                                name="status">
                                                            <option @if(isset($category) && $category->status) selected
                                                                    @endif value="1">
                                                                Aktiv
                                                            </option>
                                                            <option @if(isset($category) && !$category->status) selected
                                                                    @endif value="0">
                                                                Gözləmədə
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                    
                                                            <div class="row mb-3">
                                                                <label class="col-sm-2 col-form-label"
                                                                       for="order">Kateqoriya Sıra</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control"
                                                                           id="order"
                                                                           name="order"
                                                                           placeholder="Kateqoriya sıra dilində daxil edin..."
                                                                           @if(isset($category)) value="{{$category->order}}" @endif
                                                                    />
                                                                </div>
                                                            </div>
                                                       
                                                    </div>
                                             
                                                
                                                

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="home_status">Kateqoriya
                                                        Ana Səhifədə Görünsün?</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="home_status"
                                                                name="home_status">
                                                            <option
                                                                @if(isset($category) && $category->home_status) selected
                                                                @endif value="1">
                                                                Görünsün
                                                            </option>
                                                            <option
                                                                @if(isset($category) && !$category->home_status) selected
                                                                @endif value="0">
                                                                Görünməsin
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="attribute_id">Kateqoriya
                                                        Atributları</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="attribute_id" multiple
                                                                name="attribute_id[]">
                                                            @foreach($attributes as $attribute)
                                                                <option
                                                                    @if(isset($category) && $category->attributes->contains($attribute->id)) selected
                                                                    @endif
                                                                    value="{{$attribute->id}}">
                                                                    {{$attribute->getTranslation('name','az')}}
                                                                </option>
                                                            @endforeach
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
                $(document).ready(function () {

                    $('#status').select2();
                    $('#parent_id ').select2();
                    $('#home_status').select2();
                    $('#attribute_id').select2();

                });
            </script>
    @endpush
