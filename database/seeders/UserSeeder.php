<?php

use Illuminate\Database\Seeder;

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
                'name' => 'Integrasi Pengolahan dan Diseminasi Statistik',
                'role' => 'admin',
                'password' => Hash::make('rahasia'),
            ),
            array(
                'username' => 'tatausaha6200',
                'nama_bidang' => 'Tata Usaha',
                'name' => 'Bagian Tata Usaha',
                'role' => 'sm',
                'password' => Hash::make('tatausaha6200'),
            ),
            array(
                'username' => 'sosial6200',
                'nama_bidang' => 'Sosial',
                'name' => 'Bidang Statistik Sosial',
                'role' => 'sm',
                'password' => Hash::make('sosial6200'),
            ),
            array(
                'username' => 'distribusi6200',
                'nama_bidang' => 'Distribusi',
                'name' => 'Bidang Statistik Distribusi',
                'role' => 'sm',
                'password' => Hash::make('distribusi6200'),
            ),
            array(
                'username' => 'produksi6200',
                'nama_bidang' => 'Produksi',
                'name' => 'Bidang Statistik Produksi',
                'role' => 'sm',
                'password' => Hash::make('produksi6200'),
            ),
            array(
                'username' => 'nerwilis6200',
                'nama_bidang' => 'Nerwilis',
                'name' => 'Neraca Wilayah dan Analisis Statistik',
                'role' => 'sm',
                'password' => Hash::make('nerwilis6200'),
            )];
        foreach ($data_bidang as $bidang) {
            DB::table('users')->insert($bidang);
        }
    }
}