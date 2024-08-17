@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col">
                    <div class="card mb-4">
                        <h5 class="card-header">Sifariş Detalları</h5>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Adı</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                            {{$order->name}}
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Telefon
                                            Nömrəsi</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                            {{$order->phone}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Adresi</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                            {{$order->address}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle"><small class="text-light fw-semibold">Mesajı</small></td>
                                    <td class="py-3">
                                        <p class="mb-0">
                                            <mark>{{$order->message}}</mark>
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="card">
                                <h5 class="card-header">Sifariş olunan məhsullar</h5>
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <thead>
                                        <tr class="text-nowrap">
                                            <th>Şəkli</th>
                                            <th>Adı</th>
                                            <th>Qiyməti</th>
                                            <th>Sayı</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                @php
                                                    $image = $item->product->images->first();
                                                @endphp
                                                <th scope="row"><img src="{{asset('storage/'.$image->image)}}"
                                                                     style="width: 150px;height: 100px;" alt=""></th>
                                                <td>{{$item->product->title}}</td>
                                                <td>{{$item->product->price." AZN"}}</td>
                                                <td>{{$item->quantity}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                                @if($order->is_confirmation == 0)
                                    <form action="{{route('admin.orders.confirmation')}}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="is_confirmation" value="{{$order->id}}">
                                        <button type="submit" class="btn btn-primary">Təsdiqlə</button>
                                    </form>
                                @endif
                            </div>
                            <!--/ Responsive Table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
