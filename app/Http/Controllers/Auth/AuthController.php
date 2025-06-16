<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Meskipun User model menghash, bisa juga digunakan di sini jika perlu.
use Illuminate\Validation\Rules\Password;
use App\Models\User; // Pastikan ini di-import
use Illuminate\Validation\Rule; // Untuk validasi role jika diperlukan


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware 'guest' akan diterapkan ke semua method di controller ini
        // kecuali untuk method 'logout'.
        $this->middleware('guest')->except('logout');
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nomor_anggota' => ['nullable', 'string', 'max:50', 'unique:users,nomor_anggota'], // Pastikan unique pada tabel users, kolom nomor_anggota
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()], // Contoh password policy
            // 'role' => ['sometimes', 'string', Rule::in(['admin', 'pengurus', 'anggota'])], // Umumnya role tidak diset oleh user saat registrasi publik
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_anggota' => $request->nomor_anggota,
            'password' => $request->password, // Akan di-hash otomatis oleh User model karena $casts
            'role' => $request->input('role', 'anggota'), // Default 'anggota' jika tidak ada input 'role'
                                                        // Hati-hati jika field 'role' bisa di-submit dari form registrasi publik
        ]);

        // Secara otomatis login user setelah registrasi
        Auth::login($user);

        // Redirect ke halaman yang diinginkan setelah registrasi, misalnya halaman home
        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Coba untuk mengautentikasi user
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Jika berhasil, regenerate session ID untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman yang dituju sebelumnya atau default ke 'home'
            return redirect()->intended(route('home'))->with('success', 'Login berhasil!');
        }

        // Jika autentikasi gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email'); // Hanya mengembalikan input 'email'
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}