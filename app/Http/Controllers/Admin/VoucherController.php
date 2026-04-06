<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        // load voucher terbaru, 10 per halaman
        $vouchers = Voucher::latest()->paginate(10);

        return view('admin.vouchers.index', compact('vouchers'));
    }


    public function create()
    {
        return view('admin.vouchers.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
        'code' => 'required|unique:vouchers,code',
        'type' => 'required|in:percent,fixed',
        'value' => 'required|integer|min:0',
        'max_discount' => 'nullable|integer|min:0',
        'min_purchase' => 'nullable|integer|min:0',
        'usage_limit' => 'nullable|integer|min:0',
        'expired_at' => 'required|date|after_or_equal:today',
    ]);

    $data['is_active'] = $request->has('is_active');
    $data['used_count'] = 0; // default baru

    Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success','Voucher created');
    }


    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'min_purchase' => 'nullable|integer|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'expired_at' => 'required|date',
        ]);

        // Handle checkbox
        $data['is_active'] = $request->has('is_active');

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success','Voucher updated');
    }

    public function show(Voucher $voucher)
    {
        // Bisa langsung passing voucher ke view detail
        return view('admin.vouchers.show', compact('voucher'));
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return back()->with('success','Voucher deleted');
    }
}