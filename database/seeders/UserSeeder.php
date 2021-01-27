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
                'username' => 'ipds6200',
                'nama_bidang' => 'IPDS',
                'name' => 'Bidang Integrasi Pengolahan dan Diseminasi Statistik',
                'role' => 'ADMIN',
                'color' => 'pink',
                'password' => Hash::make('rahasia'),
            ),
            array(
                'username' => 'tatausaha6200',
                'nama_bidang' => 'Tata Usaha',
                'name' => 'Bagian Tata Usaha',
                'role' => 'SM',
                'color' => 'indigo',
                'password' => Hash::make('tatausaha6200'),
            ),
            array(
                'username' => 'sosial6200',
                'nama_bidang' => 'Sosial',
                'name' => 'Bidang Statistik Sosial',
                'role' => 'SM',
                'color' => 'light-blue',
                'password' => Hash::make('sosial6200'),
            ),
            array(
                'username' => 'distribusi6200',
                'nama_bidang' => 'Distribusi',
                'name' => 'Bidang Statistik Distribusi',
                'role' => 'SM',
                'color' => 'yellow',
                'password' => Hash::make('distribusi6200'),
            ),
            array(
                'username' => 'produksi6200',
                'nama_bidang' => 'Produksi',
                'name' => 'Bidang Statistik Produksi',
                'role' => 'SM',
                'color' => 'green',
                'password' => Hash::make('produksi6200'),
            ),
            array(
                'username' => 'nerwilis6200',
                'nama_bidang' => 'Nerwilis',
                'name' => 'Bidang Neraca Wilayah dan Analisis Statistik',
                'color' => 'deep-orange',
                'role' => 'SM',
                'password' => Hash::make('nerwilis6200'),
            )];
        foreach ($data_bidang as $bidang) {
            DB::table('users')->insert($bidang);
        }
    }
}