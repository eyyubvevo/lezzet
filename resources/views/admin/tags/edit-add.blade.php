@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light">Açar Sözlər /</span> {{ isset($tag) ? $tag->name. ' Yenilə' : 'Açar Söz Əlavə Et' }}
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
                                        {!! Form::open(['method' => isset($tag) ? 'PUT' : 'POST','route' => isset($tag) ? ['admin.tags.update',$tag->id] : ['admin.tags.store'], 'files' => true]) !!}
                                        {!! Form::token() !!}
                                        @if(isset($tag))
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
                                                        <label class="col-sm-2 col-form-label" for="name_{{$language}}">Açar Söz</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control"
                                                                   id="name_{{$language}}"
                                                                   name="name_{{$language}}"
                                                                   placeholder="Açar Söz daxil edin..." @if($language == 'az') required @endif
                                                                   @if(isset($tag)) value="{{$tag->getTranslation('name',$language)}}" @endif
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="form-check">
                                                <input @if(isset($tag) && $tag->products) checked @endif class="form-check-input" type="checkbox" value=""
                                                       id="defaultCheck2"/>
                                                <label class="form-check-label" for="defaultCheck2">
                                                    Açar Söz Üçün Məhsul Seçmək İstəyirsən? </label>
                                            </div>
                                            <br>
                                            <div class="row mb-3" style="display: none" id="productSelect">
                                                <label class="col-sm-2 col-form-label" for="product_id">Məhsul
                                                    Seçin</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" id="product_id"
                                                            name="product_id[]" multiple>
                                                        <option value="">Seçin</option>
                                                        @foreach($products as $subproduct)
                                                            <option
                                                                @if(isset($tag) && $tag->products->contains($subproduct->id)) selected
                                                                @endif
                                                                value="{{$subproduct->id}}">
                                                                {{$subproduct->getTranslation('title','az')}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
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
        $(document).ready(function () {
            $('#defaultCheck2').change(function () {
                $('#productSelect').toggle(this.checked);
                $('#product_id').select2();
            });
            @if(isset($tag))
            $('#productSelect').show();
            $('#product_id').select2();
            @endif
        });
    </script>
@endpush
