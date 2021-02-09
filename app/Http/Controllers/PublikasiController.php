<?php

namespace App\Http\Controllers;

use App\Events\PublikasiAdded;
use App\Events\PublikasiEdited;
use App\Events\PublikasiSPRPCommited;
use App\Imports\PublikasiImport;
use App\Models\Publikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $publikasis = Publikasi::orderBy('arc', 'DESC')->whereDate('arc', '<=', Carbon::now())->with('user');
        if (Gate::allows('isAdmin')) {
            return $publikasis->paginate($request->total);
        }
        return $publikasis->where('user_id', $request->user()->id)->paginate($request->total);
    }

    /**
     * Display a listing of the resource of current year.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexYear(Request $request)
    {
        $publikasis = Publikasi::orderBy('arc', 'ASC')->whereDate('arc', '>', Carbon::now())->with('user');
        if (Gate::allows('isAdmin')) {
            return $publikasis->paginate($request->total);
        }
        return $publikasis->where('user_id', $request->user()->id)->paginate($request->total);
    }

    /**
     * Display number of the resources of current year.
     *
     * @return \Illuminate\Http\Response
     */
    public function countIndexYear(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            return Publikasi::whereDate('arc', '>', Carbon::now())->count();
        }
        return Publikasi::where('user_id', $request->user()->id)->whereDate('arc', '>', Carbon::now())->count();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
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
            $request->arc = $request->arc ? date('Y-m-d', strtotime($request->arc)) : null;
            $request->tahun_rilis = $request->arc ? date('Y', strtotime($request->arc)) : null;
            $newPublikasi = Publikasi::create($request->all());
            event(new PublikasiAdded($newPublikasi, $request->user()));
            return response("Sukses Menambahkan Publikasi", 200);
        } catch (\Throwable $th) {
            return response("Ups, Terjadi Kesalahan " . $th, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        try {
            $publikasi = Publikasi::findOrFail($id);
            if (Gate::allows('isAdmin') || ($publikasi->user_id == $request->user()->id)) {
                return Publikasi::where('id', '=', $id)->with('user', 'historis', 'historis.file', 'historis.user', 'uploadedBy')->get();
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
    public function update($id, Request $request)
    {
        try {
            $request->arc = $request->arc ? date('Y-m-d', strtotime($request->arc)) : null;
            $publikasi = Publikasi::where('id', $id)->update($request->all());
            event(new PublikasiEdited(Publikasi::find($id), $request->user()));
            return response("Sukses Mengubah Publikasi", 200);
        } catch (\Throwable $th) {
            return response("Ups, Terjadi Kesalahan " . $th, 500);
        }
    }

    /**
     * Import multiple resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);
        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $nama_file = rand() . " " . $file->getClientOriginalName();
            $file->move('publikasi_folder', $nama_file);
            Excel::import(new PublikasiImport(Auth::user()), public_path('/publikasi_folder/' . $nama_file));
            DB::commit();
            return response('Sukses Import', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response('Terdapat Kesalahan saat Import FIle, Pastikan sesuai dengan format. Pesan: ' . $th, 500);
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
            Publikasi::find($req->id)->delete();
            response('Sukses Delete', 200);
        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Delete Publikasi' . $th, 500);
        }
    }

    /**
     * Search resource from storage by keyword.
     *
     * @param  String  $keyword
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $publikasis = Publikasi::where('judul_publikasi', 'LIKE', "%{$request->key}%")
            ->whereDate('arc', '<=', Carbon::now())
            ->orderBy('arc', 'DESC')
            ->with('user')
        ;
        if (Gate::allows('isAdmin')) {
            return $publikasis->paginate($request->total);
        }
        return $publikasis->where('user_id', $request->user()->id)->paginate($request->total);
    }

    /**
     * Search resource from storage by keyword.
     *
     * @param  String  $keyword
     * @return \Illuminate\Http\Response
     */
    public function searchYear(Request $request)
    {
        $publikasis = Publikasi::where('judul_publikasi', 'LIKE', "%{$request->key}%")
            ->whereDate('arc', '>', Carbon::now())
            ->orderBy('arc', 'ASC')
            ->with('user');
        if (Gate::allows('isAdmin')) {
            return $publikasis->paginate($request->total);
        }
        return $publikasis->where('user_id', $request->user()->id)->paginate($request->total);
    }

    /**
     * Update SPRP Publikasi.
     *
     * @param  String  $keyword
     * @return \Illuminate\Http\Response
     */
    public function sprp($id, Request $request)
    {
        try {
            $publikasi = Publikasi::where('id', $id)->update($request->all());
            event(new PublikasiSPRPCommited(Publikasi::find($id), $request->user()));
            return response("Sukses Melengkapi Detail Rancangan Publikasi", 200);
        } catch (\Throwable $th) {
            return response("Ups, Terjadi Kesalahan " . $th, 500);
        }
    }
}