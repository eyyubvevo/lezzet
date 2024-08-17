@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            {{--            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Müraciətlər /</span>Baxılmış Müraciətlər</h4>--}}
            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-body">
                    <iframe src="/filemanager" style="width: 100%; height: 600px; overflow: hidden; border: none;"></iframe>
                </div>
            </div>
            <hr class="my-5" />
        </div>
    </div>
@endsection
