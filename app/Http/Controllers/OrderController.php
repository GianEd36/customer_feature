<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string',
            'shipping_zip'     => 'required|string',
            'shipping_country' => 'required|string',
        ]);

        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['Your cart is empty.']);
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        // Apply coupon if available
        if ($coupon = session('coupon')) {
            if ($coupon->discount_type === 'percent') {
                $total -= $total * ($coupon->discount_amount / 100);
            } else {
                $total -= $coupon->discount_amount;
            }
        }

        $order = Order::create([
            'user_id'           => $user->id,
            'total_price'       => $total,
            'tracking_number'   => strtoupper(Str::random(10)),
            'shipping_name'     => $request->shipping_name,
            'shipping_address'  => $request->shipping_address,
            'shipping_city'     => $request->shipping_city,
            'shipping_zip'      => $request->shipping_zip,
            'shipping_country'  => $request->shipping_country,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product->id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        // Clear cart and session
        Cart::where('user_id', $user->id)->delete();
        session()->forget('coupon');

        return redirect()->route('order.show', $order->id)
                         ->with('success', 'Order placed successfully!');
    }

    public function show($id)
    {
        $order = Order::with('items.product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('orders.show', compact('order'));
    }

    public function history()
    {
        $orders = Order::with('items.product')->where('user_id', Auth::id())->latest()->get();
        return view('orders.history', compact('orders'));
    }
}
