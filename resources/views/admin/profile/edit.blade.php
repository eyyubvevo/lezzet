@extends('admin.layouts.master')
@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Profil /</span> Profili Yenilə</h4>

            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->routeIs('profile.index')) ? 'active' : '' }}" href="{{route('profile.index')}}"><i class="bx bx-user me-1"></i> Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (request()->routeIs('profile.password.index')) ? 'active' : '' }}" href="{{route('profile.password.index')}}"
                            ><i class="bx bx-bell me-1"></i>Şifrəni Dəyiş</a
                            >
                        </li>

                    </ul>
                    <div class="card mb-4">
                        <h5 class="card-header">Profil Parametrləri</h5>
                        <!-- Account -->
                        <form enctype="multipart/form-data" id="formAccountSettings" method="POST" action="{{route('profile.update')}}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                    <img
                                        src=" @if(\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->profile_image) {{ asset('storage/'.\Illuminate\Support\Facades\Auth::user()->profile_image) }} @else {{asset('backend/assets/img/default.jpg')}} @endif"
                                        alt="user-avatar"
                                        class="d-block rounded"
                                        height="100"
                                        width="100"
                                        id="uploadedAvatar"
                                    />
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input
                                                type="file"
                                                id="upload"
                                                name="profile_image"
                                                class="account-file-input"
                                                hidden
                                                accept="image/png, image/jpeg"
                                            />
                                        </label>
                                        <button type="button"
                                                class="btn btn-outline-secondary account-image-reset mb-4">
                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>

                                        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0"/>
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="firstName" class="form-label">Ad Soyad</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            id="firstName"
                                            name="name"
                                            required
                                            value="{{\Illuminate\Support\Facades\Auth::user()->name}}"
                                            autofocus
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            id="email"
                                            name=""
                                            disabled
                                            value="{{\Illuminate\Support\Facades\Auth::user()->email}}"
                                            placeholder=""
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="organization" class="form-label">TEL No</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="organization"
                                            name="phone"
                                            value="{{\Illuminate\Support\Facades\Auth::user()->phone}}"
                                        />
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2">Yenilə</button>
                                    <button type="reset" class="btn btn-outline-secondary">Ləğv Et</button>
                                </div>
                            </div>
                            <!-- /Account -->
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- / Content -->
@endsection
