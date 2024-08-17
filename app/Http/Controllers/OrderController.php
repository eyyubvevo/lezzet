<?php

namespace App\Http\Controllers;

use App\Jobs\OrderConfirm;
use App\Repositories\EloquentOrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected EloquentOrderRepository $eloquentOrderRepository;

    public function __construct(EloquentOrderRepository $eloquentOrderRepository)
    {
        $this->eloquentOrderRepository = $eloquentOrderRepository;
    }

    public function orderForm()
    {
        $total = \Cart::getTotal();
        $subtotal = \Cart::getSubTotal();
        $carts = \Cart::getContent()->sortDesc();
        return view('front.checkout', compact('carts', 'subtotal', 'total'));
    }

    public function store(Request $request)
    {
        try {
            $relatedData = ['items' => []];
            $total = \Cart::getTotal();
            $subtotal = \Cart::getSubTotal();
            $carts = \Cart::getContent();

            foreach ($carts as $cart) {
                $relatedData['items'][] = [
                    'product_id' => (int)$cart->id,
                    'quantity' => (int)$cart->quantity
                ];
            }
            $discount_total = 0.00;
            foreach ($carts as $cart) {
                if($cart->attributes->discount != null){
                    $discount_total += $cart->price - $cart->attributes->discount;
                }elseif($cart->attributes->custom_discount != null){
                    $discount_total += $cart->price - $cart->attributes->custom_discount;
                }else{
                    $discount_total += 0.00;
                }
            }
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'message' => $request->message,
                'total' => $total,
                'subtotal' => $subtotal,
                'discount_total' => $discount_total
            ];

            $orderCreatew = $this->eloquentOrderRepository->create(data: $data, relatedData: $relatedData);

            if ($orderCreatew) {
                \Cart::clear();
                dispatch(new OrderConfirm($orderCreatew));
                return redirect()->route(app()->getLocale().'.order.order_success');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Sifariş zamanı xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function order_success()
    {
        return view('front.checkout_success');
    }
}
