<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_bidang = [
            array(
                'username' => env('AKUN_IPDS','ipds'),
                'nama_bidang' => 'IPDS',
                'name' => 'Integrasi Pengolahan dan Diseminasi Statistik',
                'role' => 'ADMIN',
                'color' => 'pink',
                'password' => Hash::make(env('PASSWORD_IPDS','password')),
                'email' => '',
            ),
            array(
                'username' => env('AKUN_TU','tatausaha'),
                'nama_bidang' => 'Tata Usaha',
                'name' => 'Tata Usaha',
                'role' => 'SM',
                'color' => 'indigo',
                'password' => Hash::make(env('PASSWORD_TU','password')),
                'email' => '',
            ),
            array(
                'username' => env('AKUN_SOSIAL','sosial'),
                'nama_bidang' => 'Sosial',
                'name' => 'Statistik Sosial',
                'role' => 'SM',
                'color' => 'light-blue',
                'password' => Hash::make(env('PASSWORD_SOSIAL','password')),
                'email' => '',
            ),
            array(
                'username' => env('AKUN_DISTRIBUSI','disjas'),
                'nama_bidang' => 'Distribusi',
                'name' => 'Statistik Distribusi',
                'role' => 'SM',
                'color' => 'yellow',
                'password' => Hash::make(env('PASSWORD_DISTRIBUSI','password')),
                'email' => '',
            ),
            array(
                'username' => env('AKUN_PRODUKSI','produksi'),
                'nama_bidang' => 'Produksi',
                'name' => 'Statistik Produksi',
                'role' => 'SM',
                'color' => 'green',
                'password' => Hash::make(env('PASSWORD_PRODUKSI','password')),
                'email' => '',
            ),
            array(
                'username' => env('AKUN_NERWILIS','neraca'),
                'nama_bidang' => 'Nerwilis',
                'name' => 'Neraca Wilayah dan Analisis Statistik',
                'color' => 'deep-orange',
                'role' => 'SM',
                'password' => Hash::make(env('PASSWORD_NERWILIS','password')),
                'email' => '',
            )];
        foreach ($data_bidang as $bidang) {
            DB::table('users')->insert($bidang);
        }
    }
}