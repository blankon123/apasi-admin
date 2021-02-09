<?php

use App\Models\Enums;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul_publikasi');
            $table->string('ukuran')->nullable();
            $table->string('bahasa')->nullable();
            $table->string('orientasi')->nullable();
            $table->string('cover_oleh')->nullable();
            $table->string('diterbitkan_untuk')->nullable();
            $table->string('jenis_arc');
            $table->string('numbering')->nullable();
            $table->date('arc')->nullable();
            $table->date('batas_upload')->nullable();

            $table->longText('abstraksi')->nullable();
            $table->integer('eksemplar')->nullable();

            $table->foreignId('stage_id')->nullable()->default(Enums::STAGE_PUBLIKASI['PUBLIKASI_BARU']['kode']);
            $table->foreignId('user_id')->nullable();
            $table->foreignId('uploaded_by')->nullable();
            $table->date('uploaded_on')->nullable();
            $table->integer('tahun_rilis')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('publikasis', function (Blueprint $table) {
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
        Schema::dropIfExists('publikasis');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}