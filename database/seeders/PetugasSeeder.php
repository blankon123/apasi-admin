<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetugasSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_petugas = [
            array(
                'nama' => 'Thosan Girisona Suganda SST',
                'nama_singkat' => 'Thosan',
            ), array(
                'nama' => 'Anandari SST, M.Si',
                'nama_singkat' => 'Anandari',
            ), array(
                'nama' => 'Monica Windi Triasturi SST',
                'nama_singkat' => 'Monica',
            ), array(
                'nama' => 'Grasela Novita Trifosa SST',
                'nama_singkat' => 'Grasela',
            )];
        foreach ($data_petugas as $petugas) {
            DB::table('petugases')->insert($petugas);
        }
    }
}