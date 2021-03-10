<?php

namespace App\Http\Controllers;

use App\Events\PekerjaanDeleted;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return Pekerjaan::latest('updated_at')->get();
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
}