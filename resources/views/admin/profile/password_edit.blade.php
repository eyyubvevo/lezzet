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
                        <h5 class="card-header">Şifrə Parametrləri</h5>
                        <!-- Account -->
                        <form enctype="multipart/form-data" id="formAccountSettings" method="POST" action="{{route('profile.password.update')}}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="old_password" class="form-label">Indiki Şifrəniz</label>
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="old_password"
                                            name="old_password"
                                            required
                                            autofocus
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="new_password" class="form-label">Yeni Şifrəniz</label>
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="new_password"
                                            required
                                            name="new_password"
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="password_confirmation" class="form-label">Şifrənin Təsdiqi</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password_confirmation"
                                            required
                                            name="password_confirmation"
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
