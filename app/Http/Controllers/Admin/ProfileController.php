<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bio'     => 'nullable|string',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'address', 'bio'));

        return redirect()->route('admin.profile.edit')->with('status', 'Profile updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Wrong password']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.edit')->with('status', 'Password updated');
    }
}