<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request) {
        // validasi
        $validasi = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:10'],
        ],
        [
            'name.required' => 'Nama harus diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 10 karakter',
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
        $auth = $request->except('_token');
        $checkAuth = Auth::attempt($auth);
        if($checkAuth){
            return redirect()->route('home')->with('success', 'Berhasil login');
        } else{
        return redirect()->route('login')->with('error', 'Email atau password salah. coba lagi!')->withInput();
        }
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('home')->with('success', 'Berhasil logout');
    }
}
