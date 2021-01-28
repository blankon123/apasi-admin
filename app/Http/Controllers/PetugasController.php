<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;

class PetugasController extends Controller
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
        if ($request->user()->role == "ADMIN") {
            $newPetugas = new Petugas;
            $newPetugas->nama = $request->nama;
            $newPetugas->nama_singkat = $request->nama_singkat;
            $newPetugas->save();
            return response("Sukses Menambahkan Petugas", 200);
        } else {
            return response("Ups, Anda Bukan Admin ", 500);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        $petugas = Petugas::find($req->id);
        try {
            $petugas->delete();
            response('Sukses Delete', 200);
        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Delete Petugas' . $th, 500);
        }
    }

    /**
     * Show all resource.
     *
     * @param  String  $keyword
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        return Petugas::all();
    }
}