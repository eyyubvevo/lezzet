@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tərəfdaşlar /</span>   {{ isset($partner) ? $partner->name. ' Yenilə' : 'Tərəfdaş Əlavə Et' }}</h4>
            <div class="row">
                <div class="col-xxl">
                    <div class="card mb-4">

                        <div class="card-body">
                            <form action="{{ isset($partner) ? route('admin.partners.update',['id' => $partner->id]) : route('admin.partners.store')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @if(isset($partner))
                                    @method('PUT')
                                @else
                                    @method('POST')
                                @endif
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="name">Tərəfdaş adı</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name"
                                               placeholder="Tərəfdaş adını daxil edin..." required
                                               @if(isset($partner)) value="{{$partner->name}}" @endif
                                        />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="name">Tərəfdaş URL</label>
                                    <div class="col-sm-10">
                                        <input type="url" class="form-control" id="url" name="url"
                                               placeholder="Tərəfdaş adını daxil edin..." required
                                               @if(isset($partner)) value="{{$partner->url}}" @endif
                                        />
                                    </div>
                                </div>
                                @if(isset($partner) && $partner->image)
                                    <img style="height: 200px;width: 300px;" src="{{ asset('storage/'.$partner->image) }}" alt="Slider Image" width="100">
                                @endif
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="image">Tərəfdaş Şəkli</label>
                                    <input type="file" class="form-control" id="image" name="image" @if(isset($partner)) @else required @endif />
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="status">Tərəfdaş Statusu</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="status" name="status">
                                            <option   @if(isset($partner) && $partner->status) selected @endif value="1">Aktiv</option>
                                            <option @if(isset($partner) && !$partner->status) selected @endif value="0">Gözləmədə</option>
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
        @endsection
        @push('scripts')
            <script>
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        height: '600px'
                    })
                    .catch(error => {
                        console.error(error);
                    });
                $(document).ready(function() {
                    $('#status').select2();
                });
            </script>
    @endpush
