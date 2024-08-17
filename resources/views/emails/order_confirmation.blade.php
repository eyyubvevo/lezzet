<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Detalları</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('storage/'.setting('logo')) }}" alt="Site Logo" style="max-width: 200px;">
    </div>

    <h2>Sifariş Detalları</h2>

    <div style="margin-bottom: 20px;">
        <p><strong>Müştəri Bilgiləri:</strong></p>
        <p><strong>Ad Soyad:</strong> {{$order->name}}</p>
        <p><strong>Telefon:</strong>{{$order->phone}} </p>
        <p><strong>Adres:</strong> {{$order->address}}</p>
        <p><strong>Mesaj;:</strong> {{$order->message}}</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>Məhsul</th>
            <th>Sayı</th>
            <th>Endirim Qiyməti</th>
            <th>Qiyməti</th>
        </tr>
        </thead>
        <tbody>
        @php $discount = 0.00; @endphp
        @foreach($order->items as $item)
            @php
                if($item->product->category->company){
                    $price = $item->product->getPriceWithDiscount();
                    $discount = $item->product->price - $price;
                }elseif($item->product->is_discountable){
                     $price = $item->product->price - ($item->product->discount * $item->product->price / 100);
                     $discount = $item->product->price - $price;
                }else{
                     $discount = 0.00;
                }
                                                if ($item->product->images){
                                                     $image = $item->product->images->first();
                                                }
            @endphp
         
            <tr>
                <td><img style="width:200px;height:150px;" src="{{asset('storage/'.$image->image)}}">
                    <p>{{$item->product->title}}</p></td>
                <td>{{$item->quantity}}</td>
                <td>{{$discount}} AZN</td>
                <td>{{$item->product->price}} AZN</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" style="text-align: right;"><strong>{{__('website.Price')}}:</strong></td>
            <td>{{$order->total}} AZN</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Endirimli məbləğ:</strong></td>
            <td>{{$order->discount_total}} AZN</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Toplam məbləğ:</strong></td>
            <td>{{$order->subtotal}} AZN</td>
        </tr>
        </tfoot>
    </table>

    <div style="text-align: center; margin-bottom: 20px;">
        <a href="{{route('admin.orders.show', ['id' => $order->id])}}" class="button">Sifarişə Bax</a>
    </div>
</div>

<div class="footer">
    <p>Bizimlə əlaqə: {{setting('phone')}} | {{setting('email')}}</p>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</div>
</body>
</html>
