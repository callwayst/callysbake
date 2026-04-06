<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class UserVoucherController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $vouchers = Voucher::where('is_active', true)
            ->where(function($q) use ($userId) {
                $q->whereNull('user_id')          // voucher umum
                ->orWhere('user_id', $userId); // voucher spesifik user
            })
            ->get();

        return view('user.vouchers', compact('vouchers'));
    }

    public function claim($id)
    {
        $user = auth()->user();
        $voucher = Voucher::findOrFail($id);

        if ($voucher->user_id) {
            return back()->with('error', 'Voucher sudah diklaim.');
        }

        $voucher->user_id = $user->id;
        $voucher->save();

        return back()->with('success', 'Voucher berhasil diklaim!');
    }
}