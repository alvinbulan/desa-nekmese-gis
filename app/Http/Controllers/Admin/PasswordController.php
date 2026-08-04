<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('admin.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->onlyInput('current_password');
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()
            ->route('admin.password.edit')
            ->with('success', 'Password berhasil diubah. Gunakan password baru pada login berikutnya.');
    }
}