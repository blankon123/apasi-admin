<?php

namespace App\Http\Controllers;

use App\Imports\PublikasiImport;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return Publikasi::with('user')->orderBy('arc', 'ASC')->paginate($request->total);
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
        //
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
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . " " . $file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
        $file->move('publikasi_folder', $nama_file);

        // import data
        try {
            Excel::import(new PublikasiImport(Auth::user()), public_path('/publikasi_folder/' . $nama_file));
            return response('Sukses Import', 200);
        } catch (\Throwable $th) {
            return response('Terdapat Kesalahan saat Import FIle, Pastikan sesuai dengan format. Pesan: ' . $th->getMessage(), 500);
        }
        // Excel::import(new PublikasiImport(Auth::user()), public_path('/publikasi_folder/' . $nama_file));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        $publikasi = Publikasi::find($req->id);
        try {
            $publikasi->delete();
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
        return Publikasi::where('judul_publikasi', 'LIKE', "%{$request->key}%")
            ->with('user')
            ->orderBy('arc', 'ASC')
            ->paginate($request->total);
    }
}