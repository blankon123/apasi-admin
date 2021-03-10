<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabelDinamisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabel_dinamises', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('judul_tabel');
            $table->foreignId('user_id')->nullable();
            $table->integer('stage_id')->nullable()->default(0);
            $table->integer('tabel_web_id')->unique()->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('is_revisi')->nullable();
            $table->integer('is_deleted')->nullable();
            $table->integer('category_id')->nullable();
            $table->json('data')->nullable();
            $table->softDeletes();
        });

        Schema::table('tabel_dinamises', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('tabel_dinamises');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}