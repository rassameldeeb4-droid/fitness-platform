<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store')->with('success', 'السلة فارغة');
        }
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('store.checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store');
        }

        $data = $request->validate([
            'payment_method' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        DB::transaction(function () use ($cart, $total, $data) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        });

        session()->forget('cart');

        return redirect()->route('store.orders')->with('success', 'تم إرسال الطلب بنجاح');
    }

    public function orders()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('store.orders', compact('orders'));
    }
}
