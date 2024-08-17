@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col">
                    <div class="card mb-4">
                        <h5 class="card-header">Müraciətə Bax</h5>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Adı</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                            {{$contact->name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Telefon Nömrəsi</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                            {{$contact->phone}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Emaili</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                            {{$contact->email}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Mesajı</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                        <mark>{{$contact->message}}</mark>
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
