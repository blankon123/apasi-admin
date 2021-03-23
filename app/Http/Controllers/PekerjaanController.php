<?php

namespace App\Http\Controllers;

use App\Events\PekerjaanDeleted;
use App\Events\PublikasiRevisiDone;
use App\Events\PublikasiUploaded;
use App\Events\TabelDinamisAddedDone;
use App\Events\TabelDinamisDataEditedDone;
use App\Events\TabelDinamisDeleted;
use App\Events\TabelDinamisEditedDone;
use App\Models\Pekerjaan;
use App\Models\Publikasi;
use App\Models\TabelDinamis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return Pekerjaan::latest('updated_at')->with('pekerjaanable')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Do the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kerjakan(Request $request, $id)
    {
        try {
            return Pekerjaan::find($request->id)->update([
                "petugas_id" => $request->petugas_id,
                "status" => 1,
            ]);
        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Update Pekerjaan' . $th->getMessage(), 500);
        }
    }

    /**
     * Cancel the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function batal(Request $request, $id)
    {
        try {
            return Pekerjaan::find($request->id)->update([
                "petugas_id" => null,
                "status" => 0,
            ]);
        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Update Pekerjaan' . $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        try {
            $pekerjaan = Pekerjaan::find($req->id);
            event(new PekerjaanDeleted($pekerjaan, $req->user()));
            $pekerjaan->delete();
            response('Sukses Delete', 200);
        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Delete Pekerjaan' . $th->getMessage(), 500);
        }
    }

    /**
     * Do layout for specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kerjakanLayout($pekerjaan_id, $petugas_id, $publikasi_id, Request $request)
    {
        DB::beginTransaction();
        try {
            $publikasi = Publikasi::find($publikasi_id);
            $rand = rand();
            $fileName = [];

            $file_draft = $request->file('draft');
            $file_desain = $request->file('desain');
            $file_rilis = $request->file('rilis');

            $fileName['draft'] = "Draft " . $publikasi->judul_publikasi . " " . $rand . "." . $file_draft->extension();
            $fileName['desain'] = "Desain " . $publikasi->judul_publikasi . " " . $rand . "." . $file_desain->extension();
            $fileName['rilis'] = "Rilis " . $publikasi->judul_publikasi . " " . $rand . "." . $file_rilis->extension();

            $file_draft->move('publikasi_draft', $fileName['draft']);
            $file_desain->move('publikasi_desain', $fileName['desain']);
            $file_rilis->move('publikasi_rilis', $fileName['rilis']);

            $publikasi->stage_id = 14;
            $publikasi->save();

            event(new PublikasiUploaded($publikasi, $request->user(), $fileName));

            $pekerjaan = Pekerjaan::find($pekerjaan_id)->update(['status' => 2, 'petugas_id' => $petugas_id]);
            DB::commit();
            return response('Sukses Layout', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Import File Draft. Pesan: ' . $th, 500);
        }
    }

    /**
     * Do layout for specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kerjakanRevisi($pekerjaan_id, $petugas_id, $publikasi_id, Request $request)
    {
        DB::beginTransaction();
        try {
            $publikasi = Publikasi::find($publikasi_id);
            $rand = rand();
            $fileName = [];

            $file_draft = $request->file('draft');
            $file_desain = $request->file('desain');

            $fileName['draft'] = "Draft " . $publikasi->judul_publikasi . " " . $rand . "." . $file_draft->extension();
            $fileName['desain'] = "Desain " . $publikasi->judul_publikasi . " " . $rand . "." . $file_desain->extension();

            $file_draft->move('publikasi_draft', $fileName['draft']);
            $file_desain->move('publikasi_desain', $fileName['desain']);

            $publikasi->is_revisi = 0;
            $publikasi->save();

            event(new PublikasiRevisiDone($publikasi, $request->user(), $fileName));

            $pekerjaan = Pekerjaan::find($pekerjaan_id)->update(['status' => 2, 'petugas_id' => $petugas_id]);
            DB::commit();
            return response('Sukses Lakukan Revisi', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Import File Draft. Pesan: ' . $th, 500);
        }
    }

    /**
     * Confirm Delete for Tabel Dinamis.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kerjakanHapusTabel(Request $request)
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::find($request->pekerjaan_id)->update(['status' => 2, 'petugas_id' => $request->petugas_id]);
            $tabel = TabelDinamis::find($request->tabel_id);
            event(new TabelDinamisDeleted($tabel, $request->user()));
            $tabel->delete();
            DB::commit();
            return response('Sukses Upload Draft', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Hapus Tabel. Pesan: ' . $th, 500);
        }
    }

    /**
     * Confirm Penambahan Data for Tabel Dinamis.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kerjakanTambahDataTabel(Request $request)
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::find($request->pekerjaan_id)->update(['status' => 2, 'petugas_id' => $request->petugas_id]);
            $tabel = TabelDinamis::find($request->tabel_id);
            event(new TabelDinamisDataEditedDone($tabel, $request->user()));
            DB::commit();
            return response('Sukses Tambah Data', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Hapus Tabel. Pesan: ' . $th, 500);
        }
    }

    /**
     * Confirm Edit Details for Tabel Dinamis.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kerjakanEditTabel(Request $request)
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::find($request->pekerjaan_id)->update(['status' => 2, 'petugas_id' => $request->petugas_id]);
            $tabel = TabelDinamis::find($request->tabel_id);
            event(new TabelDinamisEditedDone($tabel, $request->user()));
            DB::commit();
            return response('Sukses Edit Tabel', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Hapus Tabel. Pesan: ' . $th, 500);
        }
    }

    /**
     * Confirm Add for Tabel Dinamis.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kerjakanTambahTabel(Request $request)
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::find($request->pekerjaan_id)->update(['status' => 2, 'petugas_id' => $request->petugas_id]);
            $tabel = TabelDinamis::find($request->tabel_id);
            event(new TabelDinamisAddedDone($tabel, $request->user()));
            DB::commit();
            return response('Sukses Edit Tabel', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Hapus Tabel. Pesan: ' . $th, 500);
        }
    }

    /**
     * Change petugas for resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ubahPetugas(Request $request)
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::find($request->pekerjaan_id)->update(['petugas_id' => $request->petugas_id]);
            DB::commit();
            return response('Sukses Upload Draft', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Import File Draft. Pesan: ' . $th, 500);
        }
    }
}