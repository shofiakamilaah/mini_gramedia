<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //Request $request mengambil value data : bisa dari input atau url
    public function register(Request $request){
        // validasi
        $validateData = $request->validate([
            'name'=>['required', 'min:3'],
            //uniqe : table, field : data email tidak boleh duplikat
            'email'=>['required', 'email:rfc,dns', 'unique:users,email'],
            //confirm : diform ada inputan "konfirmasi password"
            //uncompromised : mengecak pw yg dibuat apakah sudah pernah di bobol
            'password'=>['required', 'min:8', 'max:10', 'confirmed', Password::min(8)->max(10)->uncompromised()]
        ],
        // pesan error
        [
            'name.required'=>'Nama harus diisi',
            'name.min'=>'Nama minimal 3 karakter',
            'email.required'=>'Email harus diisi',
            'email.unique'=>'Email harus diisi dengan data yang belum terdaftar',
            'password.required'=>'Password harus diisi',
            'password.min'=>'Password minimal 8 karakter',
            'password.max'=>'Password maksimal 10 karakter',
            'password.confirmed'=>'Konfirmasi Password tidak sesuai dengan password yang diberikan',
        ]);

        //eloquent mengubah sql menjadi
        //simpan data ke database : model
        $creatAccount = User::create([
            //nama field -> isi data
            'name'=>$validateData['name'],
            'email'=>$validateData['email'],
            //hash::make mengubah password plain text menjadi karakter acak yang tidak bisa dibaca/dikembalikan ke text aslinya
            'password'=>Hash::make($validateData['password'])
        ]);

        //menentukan jika brhasil disimpan akan diaarahkan ke halaman mana : return redirect()->route{} mengirim session untuk nonaktifkan info berhasil : with 'nama' , 'pesan'

        return redirect()->route('login')->with('success', 'Berhasil membuat akun, silahkan login');
    }

    public function login(Request $request){
        // validasi
        $validateData = $request->validate([
            'email'=>['required'],
            'password'=>['required']
        ], [
            'email.required'=>'Email harus diisi',
            'password.required'=>'Password harus diisi'
        ]);

        //dd($request->all());
        //untuk proses auth ambil data selain _token csrf (email dan password saja)
        $auth = $request->except(['_token']);
        //Auth::attempt() untuk
        // 1. mengecek apakah data yang dikirim sesuai dengan data di database
        //2. jika  bener, simpan data di session/cokies web
        //3. kalau salah, tentukan aksi yang akan dilakukan
        // $checkAuth = Auth::attempt($auth);
        // if ($checkAuth) {
            // bikin ulang id session
        //     $request->session()->regenerate();
        //     return redirect()->route('home')->with('success', 'Berhasil login');
        // } else {
        //     return redirect()->route('login')->with('error', 'Email dan password salah, coba lagi!')->withInput();
        // }

        if(Auth::attempt($validateData)){
            $request->session()->regenerate();

            if (Auth::user()->role == 'admin'){
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai admin');
            }   else{
                return redirect()->route('home')->with('success', 'Berhasil login sebagai user');
            }
        } else {
            return redirect()->route('login')->with('error', 'Email dan password salah. coba lagi')->withInput();
        }
    }

    public function logout(Request $request){
        Auth::logout();
        //memastikan semua session yang ada dibuat invalid atau expired
        //bikin ulang token session baru
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil logout!');
    }
};
