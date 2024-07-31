@extends('admin.layouts.master')

@push('styles')
    <style>
        .preview-images-zone {
            width: 100%;
            border: 1px solid #ddd;
            min-height: 180px;
            /* display: flex; */
            padding: 5px 5px 0px 5px;
            position: relative;
            overflow: auto;
        }

        .preview-images-zone > .preview-image:first-child {
            height: 185px;
            width: 185px;
            position: relative;
            margin-right: 5px;
        }

        .preview-images-zone > .preview-image {
            height: 90px;
            width: 90px;
            position: relative;
            margin-right: 5px;
            float: left;
            margin-bottom: 5px;
        }

        .preview-images-zone > .preview-image > .image-zone {
            width: 100%;
            height: 100%;
        }

        .preview-images-zone > .preview-image > .image-zone > img {
            width: 100%;
            height: 100%;
        }

        .preview-images-zone > .preview-image > .tools-edit-image {
            position: absolute;
            z-index: 100;
            color: #fff;
            bottom: 0;
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
            display: none;
        }

        .preview-images-zone > .preview-image > .image-cancel {
            font-size: 18px;
            position: absolute;
            top: 0;
            right: 0;
            font-weight: bold;
            margin-right: 10px;
            cursor: pointer;
            display: none;
            z-index: 100;
        }

        .preview-image:hover > .image-zone {
            cursor: move;
            opacity: .5;
        }

        .preview-image:hover > .tools-edit-image,
        .preview-image:hover > .image-cancel {
            display: block;
        }

        .ui-sortable-helper {
            width: 90px !important;
            height: 90px !important;
        }

        .container {
            padding-top: 50px;
        }
    </style>
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
                    class="text-muted fw-light">Məhsullar /</span> {{ isset($product) ? $product->title. ' Yenilə' : 'Məhsul Əlavə Et' }}
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
                                            action="{{ isset($product) ? route('admin.products.update',['id' => $product->id]) : route('admin.products.store')}}"
                                            method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @if(isset($product))
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
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_title_{{ $language }}">Məhsul SEO
                                                                Başlığı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="meta_title_{{ $language }}"
                                                                       name="meta_title_{{ $language }}"
                                                                       placeholder="Məhsul SEO başlığını {{ $language }} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       @if(isset($product)) value="{{$product->getTranslation('meta_title',$language)}}" @endif
                                                                />
                                                                <br>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_description_{{ $language }}">Məhsul SEO
                                                                Mətni</label>
                                                            <div class="col-sm-10">
                                                                <textarea @if($language == 'az') required
                                                                          @endif class="form-control"
                                                                          id="meta_description_{{$language}}"
                                                                          name="meta_description_{{$language}}">@if(isset($product)){{$product->getTranslation('meta_description',$language)}} @endif</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="meta_keywords_{{ $language }}">Məhsul SEO
                                                                Açar Sözləri</label>
                                                            <div class="col-sm-10">
                                                                <input @if($language == 'az') required @endif
                                                                class="form-control"
                                                                       id="meta_keywords_{{ $language }}"
                                                                       name="meta_keywords_{{ $language }}"
                                                                       type="text"
                                                                       @if(isset($product))
                                                                           value=" {{$product->getTranslation('meta_keywords',$language)}}"
                                                                       @endif
                                                                       data-role="tagsinput"/>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="title_{{$language}}">Məhsulun adı</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"
                                                                       id="title_{{$language}}"
                                                                       name="title_{{$language}}"
                                                                       placeholder="Məhsulun adını {{$language}} dilində daxil edin..."
                                                                       @if($language == 'az') required @endif
                                                                       @if(isset($product)) value="{{$product->getTranslation('title',$language)}}" @endif
                                                                />
                                                            </div>
                                                        </div>
                                                        @if(isset($product))
                                                            <div class="row mb-3">
                                                                <label class="col-sm-2 col-form-label"
                                                                       for="slug_{{$language}}">Məhsul Slug</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control"
                                                                           id="slug_{{$language}}"
                                                                           name="slug_{{$language}}"
                                                                           placeholder="Məhsulun slug {{$language}} dilində daxil edin..."
                                                                           @if($language == 'az') required @endif
                                                                           @if(isset($product)) value="{{$product->getTranslation('slug',$language)}}" @endif
                                                                    />
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="row mb-3">
                                                            <label class="col-sm-2 col-form-label"
                                                                   for="content_{{$language}}">Məhsulun Mətni</label>
                                                            <div class="col-sm-10">
                                                            <textarea @if($language == 'az') required
                                                                      @endif class="form-control"
                                                                      id="content_{{$language}}"
                                                                      name="content_{{$language}}"> @if(isset($product))
                                                                    {{$product->getTranslation('content',$language)}}
                                                                @endif</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="category_id">Məhsulun
                                                        Kateqoriyası</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="category_id" required
                                                                name="category_id">
                                                            @foreach($categories as $category)
                                                                <option
                                                                    @if(isset($product) && $product->category_id == $category->id) selected
                                                                    @endif
                                                                    value="{{$category->id}}">
                                                                    {{$category->getTranslation('name',app()->getLocale())}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{--                                                <div class="row mb-3">--}}
                                                {{--                                                    <label class="col-sm-2 col-form-label" for="attribute_id">Məhsulun--}}
                                                {{--                                                        Atributu</label>--}}
                                                {{--                                                    <div class="col-sm-10">--}}
                                                {{--                                                        <select class="form-control" id="attribute_id"--}}
                                                {{--                                                                name="attribute_id">--}}

                                                {{--                                                        </select>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </div>--}}

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="attribute_id">Məhsulun
                                                        Atributu</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="attribute_id"
                                                                name="attribute_id" required>

                                                            @foreach($attributes as $attribute)
                                                                <option
                                                                    @if(isset($product) && $product->attribute_id == $attribute->id))
                                                                    selected
                                                                    @endif
                                                                    value="{{$attribute->id}}">
                                                                    {{$attribute->getTranslation('name','az')}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="tag_id">Məhsulun
                                                        Axtarış Teqləri</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="tag_id" multiple
                                                                name="tag_id[]">
                                                            @foreach($tags as $tag)
                                                                <option
                                                                    @if(isset($product) && $product->tags->contains($tag->id)) selected
                                                                    @endif
                                                                    value="{{$tag->id}}">
                                                                    {{$tag->getTranslation('name','az')}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label"
                                                           for="price">Məhsulun Qiyməti</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group">
                                                            <span class="input-group-text">AZN</span>
                                                            <input type="text" class="form-control"
                                                                   id="price" name="price"
                                                                   placeholder="Məhsulun qiymətini daxil edin..."
                                                                   required
                                                                   aria-label="Amount (to the nearest dollar)"
                                                                   @if(isset($product)) value="{{$product->price}}" @endif
                                                            />
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    {!! Form::label('status', 'Məhsulun Statusu', ['class' => 'col-sm-2 col-form-label'])!!}
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="status" name="status">
                                                            <option @if(isset($product) && $product->status) selected
                                                                    @endif value="1">
                                                                Aktiv
                                                            </option>
                                                            <option @if(isset($product) && !$product->status) selected
                                                                    @endif value="0">Gözləmədə
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                      <div class="row mb-3">
                                                                <label class="col-sm-2 col-form-label"
                                                                       for="order">Məhsulun Sırası</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control"
                                                                           id="order"
                                                                           name="order"
                                                                           placeholder="Məhsulu sıra dilində daxil edin..."
                                                                         
                                                                           @if(isset($product)) value="{{$product->order}}" @endif
                                                                    />
                                                                </div>
                                                            </div>
                                                            

                                                <div class="container">
                                                    <fieldset class="form-group">
                                                        <a href="javascript:void(0)" onclick="$('#pro-image').click()">Upload
                                                            Image</a>
                                                        <input type="file" id="pro-image" name="images[]"
                                                               style="display: none" class="form-control" multiple>
                                                    </fieldset>
                                                    <div class="preview-images-zone">
                                                        @if(isset($product))
                                                            @foreach($product->images as $key => $image)
                                                                <div class="preview-image preview-show-{{$image->id}}">
                                                                    <div class="image-cancel" data-no="{{$image->id}}">
                                                                        <i style="color: red"
                                                                           class="bx bx-trash me-2"></i></div>
                                                                    <div class="image-zone"><img
                                                                            id="pro-img-{{$image->id}}"
                                                                            src="{{asset('storage/'.$image->image)}}">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                <br>
                                                @if(isset($product) && !$product->category->company)
                                                    <div class="form-check">
                                                        <input @if(isset($product) && $product->is_discountable) checked
                                                               @endif class="form-check-input" type="checkbox"
                                                               name="is_discountable"
                                                               id="is_discountable"/>
                                                        <label class="form-check-label" for="defaultCheck2">
                                                            Məhsula Özəl Endirim Etmək İstəyirsən? </label>
                                                    </div>
                                                    <br>
                                                @endif

                                                <div id="discountDiv" style="display: none;">
                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label"
                                                               for="discount">Endirim Miqdarı</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group">
                                                                <span class="input-group-text">%</span>
                                                                <input type="text" class="form-control"
                                                                       id="discount" name="discount"
                                                                       placeholder="Endirim Miqdarını daxil edin..."
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       @if(isset($product)) value="{{$product->discount}}" @endif
                                                                />
                                                                <span class="input-group-text">.00</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label"
                                                               for="discount_start_date">Endirim Başlama vaxtı</label>
                                                        <div class="col-sm-10">
                                                            <input
                                                                @if(isset($product)) value="{{$product->discount_start_date}}"
                                                                @endif type="datetime-local" id="discount_start_date"
                                                                class="form-control" name="discount_start_date">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label" for="discount_end_date">Endirim
                                                            Bitmə vaxtı</label>
                                                        <div class="col-sm-10">
                                                            <input
                                                                @if(isset($product)) value="{{$product->discount_end_date}}"
                                                                @endif  type="datetime-local" id="discount_end_date"
                                                                class="form-control" name="discount_end_date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-end">
                                                    <div class="col-sm-10">
                                                        {!! Form::button('Yadda Saxla!',['class' => 'btn btn-primary','type' => 'submit']) !!}
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
                    .create(document.querySelector('#content_{{ $language }}'), {
                        ckfinder: {
                            uploadUrl: "{{route('admin.products.upload',['_token' => csrf_token()])}}"
                        },
                        height: '600px',
                        placeholder: '{{'Məhsul Mətni '. $language.' dilində daxil edin...' }}'
                    })
                    .catch(error => {
                        console.error(error);
                    });
                @endforeach
                var num = 4;
                var selectedFiles = [];

                function readImage() {
                    if (window.File && window.FileList && window.FileReader) {
                        var files = event.target.files;
                        var output = $(".preview-images-zone");

                        for (let i = 0; i < files.length; i++) {
                            var file = files[i];
                            if (!file.type.match('image')) continue;

                            var picReader = new FileReader();

                            picReader.addEventListener('load', function (event) {
                                var picFile = event.target;
                                var html = '<div class="preview-image preview-show-' + num + '">' +
                                    '<div class="image-cancel" data-no="' + num + '">x</div>' +
                                    '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
                                    // '<div class="tools-edit-image"><a href="javascript:void(0)" data-no="' + num + '" class="btn btn-light btn-edit-image">x</a></div>' +
                                    '</div>';
                                selectedFiles.push(picFile.result);
                                output.append(html);
                                num = num + 1;
                            });

                            picReader.readAsDataURL(file);

                        }
                        // $("#pro-image").val('');
                    } else {
                        console.log('Browser not support');
                    }
                }

                $(document).ready(function () {

                    $('#is_discountable').change(function () {
                        $('#discountDiv').toggle(this.checked);
                        flatpickr("input[type=datetime-local]");
                    });
                    @if(isset($product) && $product->is_discountable)
                    $('#discountDiv').show();
                    flatpickr("input[type=datetime-local]");
                    @endif
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    document.getElementById('pro-image').addEventListener('change', readImage, false);

                    $('#category_id').select2();
                    $('#attribute_id').select2();
                    $('#status').select2();
                    $('#tag_id').select2();
                    $(".preview-images-zone").sortable();
                    @if(isset($product))
                    $(document).on('click', '.image-cancel', function () {
                        let no = $(this).data('no');
                        $(".preview-image.preview-show-" + no).remove();
                        $.ajax({
                            type: 'GET',
                            url: '{{route('admin.products.imageDelete')}}',
                            data: {
                                id: no
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data) {
                                    $(".preview-image.preview-show-" + no).remove();
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        });
                    });
                    @else
                    $(document).on('click', '.image-cancel', function () {
                        let no = $(this).data('no');
                        $(".preview-image.preview-show-" + no).remove();
                    });
                    @endif
                    {{--$('#category_id').change(function () {--}}
                    {{--    var categoryId = $(this).val();--}}

                    {{--    $.ajax({--}}
                    {{--        url: '{{ route('admin.categories.getAttributes', ['id' => '']) }}/' + categoryId,--}}
                    {{--        type: 'GET',--}}
                    {{--        data: {id: categoryId},--}}
                    {{--        success: function (response) {--}}
                    {{--            $('#attribute_id').empty();--}}
                    {{--            $.each(response.attributes, function (key, attribute) {--}}
                    {{--                var optionValue = key === 'id' ? attribute : '';--}}
                    {{--                var optionText = key === 'name' ? attribute : '';--}}
                    {{--                if (optionValue || optionText) {--}}
                    {{--                    $('#attribute_id').append('<option value="' + optionValue + '">' + optionText + '</option>');--}}
                    {{--                }--}}
                    {{--            });--}}
                    {{--        },--}}
                    {{--        error: function (error) {--}}
                    {{--            console.log(error);--}}
                    {{--        }--}}
                    {{--    });--}}
                    {{--});--}}
                    {{--$('#category_id').change();--}}
                });
            </script>
    @endpush
