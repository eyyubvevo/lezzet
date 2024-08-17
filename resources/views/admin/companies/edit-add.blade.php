@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light">Kompaniyalar /</span> {{ isset($company) ? ' Yenilə' : 'Kompaniya Əlavə Et' }}
            </h4>
            <div class="row">
                <div class="col-xxl">
                    <div class="card mb-4">

                        <div class="card-body">
                            <form
                                action="{{ isset($company) ? route('admin.companies.update',['id' => $company->id]) : route('admin.companies.store')}}"
                                method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @if(isset($company))
                                    @method('PUT')
                                @else
                                    @method('POST')
                                @endif

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="category_id">Endirim Olunacaq
                                        Kateqoriyanı
                                        Seçin </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="category_id" required
                                                name="category_id">
                                            @foreach($categories as $category)
                                                <option
                                                    @if(isset($company) && $company->category_id == $category->id) selected
                                                    @endif value="{{$category->id}}">
                                                    {{$category->getTranslation('name','az')}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"
                                           for="discount">Endirim Miqdarı</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text">%</span>
                                            <input type="text" class="form-control"
                                                   id="discount" name="discount"
                                                   placeholder="Endirim Miqdarını daxil edin..."
                                                   required
                                                   aria-label="Amount (to the nearest dollar)"
                                                   @if(isset($company)) value="{{$company->discount}}" @endif
                                            />
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <h5 class="card-header">Kompaniya Şəkli</h5>
                                    <!-- Account -->
                                    <div class="card-body">
                                        <div
                                            class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img
                                                src=" @if(isset($company) && $company->image) {{ asset('storage/'.$company->image) }} @else {{asset('backend/assets/img/default.jpg')}} @endif"
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
                                                        @if(isset($company)) @else required @endif
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
                                    <label class="col-sm-2 col-form-label" for="discount_start_date">Endirim Başlama vaxtı</label>
                                    <div class="col-sm-10">
                                        <input @if(isset($company)) value="{{$company->discount_start_date}}" @endif type="datetime-local" id="discount_start_date" class="form-control" name="discount_start_date">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="discount_end_date">Endirim Bitmə vaxtı</label>
                                    <div class="col-sm-10">
                                        <input @if(isset($company)) value="{{$company->discount_end_date}}" @endif  type="datetime-local" id="discount_end_date" class="form-control" name="discount_end_date">
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
        @endsection
        @push('scripts')
            <script>
                $(document).ready(function () {
                    $('#category_id').select2();
                    flatpickr("input[type=datetime-local]");
                });
            </script>
    @endpush
