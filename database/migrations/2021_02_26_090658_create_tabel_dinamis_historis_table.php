<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabelDinamisHistorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabel_dinamis_historis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabel_id');
            $table->string('keterangan');
            $table->string('perubahan')->nullable();
            $table->json('data')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::table('tabel_dinamis_historis', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tabel_id')->references('id')->on('tabel_dinamises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabel_historis');
    }
}