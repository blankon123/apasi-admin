<?php

namespace App\Http\Controllers;

use App\Events\TabelDinamisRequestAdded;
use App\Events\TabelDinamisRequestDeleted;
use App\Events\TabelDinamisRequestEdited;
use App\Models\TabelDinamis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TabelDinamisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $tabels = TabelDinamis::latest()->where('judul_tabel', 'LIKE', "%{$request->key}%");
            if ($request->subject_id != "") {
                $tabels = $tabels->where('subject_id', '=', $request->subject_id);
            }
            if ($request->category_id != "") {
                $tabels = $tabels->where('category_id', '=', $request->category_id);
            }
            if ($request->bidang_id != "") {
                $tabels = $tabels->where('user_id', '=', $request->bidang_id);
            }
            if (!Gate::allows('isAdmin')) {
                $tabels = $tabels->where('user_id', $request->user()->id);
            }
            return response($tabels->with('user')->paginate($request->total), 200);
        } catch (\Throwable $th) {
            return response("Ups, Terjadi Kesalahan " . $th->getMessage(), 500);
        }

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
        try {
            $newTabel = TabelDinamis::create($request->all());
            event(new TabelDinamisRequestAdded($newTabel, $request->user()));
            return response("Sukses Menambahkan Tabel", 200);
        } catch (\Throwable $th) {
            return response("Ups, Terjadi Kesalahan " . $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $tabel = TabelDinamis::findOrFail($id);
            if (Gate::allows('isAdmin') || ($tabel->user_id == $request->user()->id)) {
                return TabelDinamis::where('id', '=', $id)->with('user', 'historis', 'historis.user', 'historis.pekerjaan')->first();
            } else {
                return response("Ups, Anda Tidak Berhak Mengakses ", 403);
            }
        } catch (\Throwable $th) {
            return response("Ups, Ada yang salah " . $th->getMessage(), 500);
        }
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
        try {
            $tabel_old = TabelDinamis::find($id);
            TabelDinamis::where('id', $id)->update($request->all());
            $tabel = TabelDinamis::find($id);
            event(new TabelDinamisRequestEdited($tabel_old, $tabel, $request->user()));
            return response("Permintaan Perubahan Sukses", 200);
        } catch (\Throwable $th) {
            return response("Ups, Terjadi Kesalahan " . $th, 500);
        }
    }

    /**
     * Request to remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function requestDestroy(Request $req)
    {
        $tabel = TabelDinamis::find($req->id);
        response('Permintaan Hapus Sukses', 200);
        event(new TabelDinamisRequestDeleted($tabel, $req->user()));
        try {

        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Delete Tabel' . $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tabel = TabelDinamis::find($req->id);
            // event(new PublikasiDeleted($publikasi, $request->user()));
            // $tabel->delete();
            response('Permintaan Dihapus Sukses', 200);
        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Delete Tabel' . $th->getMessage(), 500);
        }
    }

    /**
     * Sync Tabels from WebApi and resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request)
    {
        try {
            TabelDinamis::upsert($request->data, ['tabel_web_id'], ['judul_tabel', 'subject_id', 'category_id']);
            $tabels = TabelDinamis::latest()->where('judul_tabel', 'LIKE', "%{$request->key}%");
            if ($request->subject_id != "") {
                $tabels = $tabels->where('subject_id', '=', $request->subject_id);
            }
            if ($request->category_id != "") {
                $tabels = $tabels->where('category_id', '=', $request->category_id);
            }
            if ($request->bidang_id != "") {
                $tabels = $tabels->where('user_id', '=', $request->bidang_id);
            }
            if (!Gate::allows('isAdmin')) {
                $tabels = $tabels->where('user_id', $request->user()->id);
            }
            return response(['tabel' => $tabels->with('user')->paginate($request->total), 'msg' => "Sukses Sync Web"], 200);
        } catch (\Throwable $th) {
            return response("Ups, Terjadi Kesalahan " . $th->getMessage(), 500);
        }
    }

    /**
     * Display number of the dinamis table.
     *
     * @return \Illuminate\Http\Response
     */
    public function countDinamis(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            return TabelDinamis::count();
        }
        return TabelDinamis::where('user_id', $request->user()->id)->count();
    }

}