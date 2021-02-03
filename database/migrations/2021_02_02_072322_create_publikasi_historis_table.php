<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublikasiHistorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publikasi_historis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publikasi_id');
            $table->string('Keterangan');
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::table('publikasi_historis', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('publikasi_id')->references('id')->on('publikasis')->onDelete('cascade');
        });

        Schema::table('publikasi_files', function (Blueprint $table) {
            $table->foreign('publikasi_histori_id')->references('id')->on('publikasi_historis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publikasi_historis');
    }
}
