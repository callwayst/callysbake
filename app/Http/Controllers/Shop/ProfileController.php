<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile Page
    |--------------------------------------------------------------------------
    */
    public function edit()
    {
        return view('shop.profile.edit', [
            'user' => Auth::user()
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Basic Info
    |--------------------------------------------------------------------------
    */
    public function updateInfo(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profile berhasil diperbarui');
    }


    /*
    |--------------------------------------------------------------------------
    | Update Password (lebih aman)
    |--------------------------------------------------------------------------
    */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        // cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated');
    }


    /*
    |--------------------------------------------------------------------------
    | Update Avatar
    |--------------------------------------------------------------------------
    */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        $user = auth()->user();

        // hapus avatar lama kalau ada
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return back()->with('success', 'Photo updated');
    }

    /*
    |--------------------------------------------------------------------------
    | Orders History (pakai pagination)
    |--------------------------------------------------------------------------
    */
    public function orders()
    {
        $orders = Auth::user()
            ->orders()
            ->latest()
            ->paginate(10);

        return view('shop.profile.orders', compact('orders'));
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Account
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // logout dengan facade (no warning)
        Auth::logout();

        // bersihkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // hapus avatar kalau ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect('/');
    }
}