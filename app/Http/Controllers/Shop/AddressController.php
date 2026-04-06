<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string',
            'city'          => 'required|string|max:100',
            'postal_code'   => 'required|string|max:10',
        ]);

        $isFirst = Address::where('user_id', Auth::id())->count() === 0;

        $address = Address::create([
            'user_id'       => Auth::id(),
            'receiver_name' => $request->receiver_name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'city'          => $request->city,
            'postal_code'   => $request->postal_code,
            'is_default'    => $isFirst,
        ]);

        session(['checkout_address_id' => $address->id]);

        return redirect()->route('checkout.index', [
            'selected_products' => session('checkout_selected_products', [])
        ])->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->setAsDefault();
        session(['checkout_address_id' => $address->id]);
        return back()->with('success', 'Alamat default diubah.');
    }
}