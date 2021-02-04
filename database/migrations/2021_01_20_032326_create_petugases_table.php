<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petugases', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_singkat');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pekerjaans', function (Blueprint $table) {
            $table->foreign('petugas_id')->references('id')->on('petugases')->onDelete('cascade');
        });
        Schema::table('publikasis', function (Blueprint $table) {
            $table->foreign('uploaded_by')->references('id')->on('petugases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('petugases');
    }
}