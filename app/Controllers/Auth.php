<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Set session
            $session->set([
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'nama_lengkap' => $user['nama_lengkap'],
                'no_pelanggan'  => $user['no_pelanggan'],
                'role'      => $user['role'],
                'logged_in' => true
            ]);

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                return redirect()->to('/dashboard/admin');
            } elseif ($user['role'] === 'pelanggan') {
                return redirect()->to('/user/dashboard');
            } else {
                return redirect()->to('/login')->with('error', 'Role tidak valid.');
            }
        } else {
            return redirect()->back()->with('error', 'Username atau password salah.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout!');
    }

    public function registerForm()
    {
        return view('auth/register');
    }

    public function register()
    {
        helper('text');
        $model = new UserModel();

        $username   = $this->request->getPost('username');
        $password   = $this->request->getPost('password');
        $confirm    = $this->request->getPost('password_confirm');
        $nama       = $this->request->getPost('nama_lengkap');
        $alamat     = $this->request->getPost('alamat');
        $no_hp      = $this->request->getPost('no_hp');

        // Validasi
        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak cocok.');
        }

        if ($model->where('username', $username)->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        // Generate nomor pelanggan
        $noPelanggan = 'PLG' . strtoupper(random_string('numeric', 6));

        $model->save([
            'username'      => $username,
            'password'      => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'pelanggan',
            'nama_lengkap'  => $nama,
            'alamat'        => $alamat,
            'no_hp'         => $no_hp,
            'no_pelanggan'  => $noPelanggan,
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}
