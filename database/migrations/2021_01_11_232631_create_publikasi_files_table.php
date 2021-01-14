<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublikasiFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publikasi_files', function (Blueprint $table) {
            $table->id();
            $table->string('file')->nullable();
            $table->string('keterangan')->nullable(); //Draft, Cover, File Cetak, Surat Rilis, Erata, Surat Lainnya,
            $table->foreignId('user_id')->nullable();
            $table->foreignId('publikasi_id')->nullable();
            $table->timestamps();
        });

        Schema::table('publikasi_files', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('publikasi_id')->references('id')->on('publikasis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publikasi_files');
    }
}