<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request) {
        // validasi
        $validasi = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required','email:rfc,dns', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed',
                            Password::min(8)->max(10)->uncompromised()],
        ],
        [
            'name.required' => 'Nama harus diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 10 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $createAccount = User::create([
            'name' => $validasi['name'],
            'email' => $validasi['email'],
            // hasil ::make -> mengubah pw text mejadi enscript
            'password' => Hash::make($validasi['password']),
        ]);
        // menentukan jika berhasil disimpan akan diarahkan ke halaman nama: return()->route()
        // mengirimkan session untuk notifikasi/info berhasil: with(nama, pesan)
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silahkan login');
    }

    public function login(Request $request){

        $validatedata = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ],
        [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
        ]);
        if (Auth::attempt($validatedata)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai admin');
            }

            return redirect()->route('home')->with('success', 'Berhasil login');
        }

        return redirect()->route('login')->with('error', 'Email atau password salah. coba lagi!')->withInput();
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil logout');
    }
}
