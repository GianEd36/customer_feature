<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;

class ShippingController extends Controller
{
    // Show all shipping methods (admin view)
    public function index()
    {
        $shippings = Shipping::all();
        return view('admin.shipping.index', compact('shippings'));
    }

    // Store a new shipping method
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Shipping::create($request->all());

        return back()->with('success', 'Shipping method added.');
    }

    // Update existing method
    public function update(Request $request, Shipping $shipping)
    {
        $request->validate([
            'method' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $shipping->update($request->all());

        return back()->with('success', 'Shipping method updated.');
    }

    // Delete method
    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return back()->with('success', 'Shipping method deleted.');
    }
}
