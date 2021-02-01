<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user();

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
            $newUser = new User;
            $color = ['red', 'purple', 'blue', 'amber', 'brown'];
            $newUser->username = $request->username;
            $newUser->nama_bidang = $request->nama_bidang;
            $newUser->name = $request->name;
            $newUser->password = Hash::make($request->password);
            $newUser->color = $color[array_rand($color, 1)];
            $newUser->save();
            return response("Sukses Menambahkan User", 200);
        } else {
            return response("Ups, Anda Bukan Admin ", 401);
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
    public function update(Request $request)
    {
        if ($request->user()->role == "ADMIN") {
            $user = User::find($request->id);
            $user->username = $request->username;
            $user->nama_bidang = $request->nama_bidang;
            $user->name = $request->name;
            $user->save();
            return response("Sukses Merubah User", 200);
        } else {
            return response("Ups, Anda Bukan Admin ", 401);
        }
    }

    /**
     * Update the specified resource password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        if ($request->user()->role == "ADMIN") {
            $user = User::find($request->id);
            $user->password = Hash::make($request->password);
            $user->save();
            return response("Sukses Merubah User", 200);
        } else {
            return response("Ups, Anda Bukan Admin ", 500);
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
        $user = User::find($req->id);
        try {
            $user->delete();
            response('Sukses Delete', 200);
        } catch (\Throwable $th) {
            response('Terdapat Kesalahan saat Delete' . $th, 500);
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
        if ($request->user()->role == "ADMIN") {
            return User::all();
        }
        return response("Ups, Anda Bukan Admin ", 401);

    }

    /**
     * Show all bidang .
     *
     * @param  String  $keyword
     * @return \Illuminate\Http\Response
     */
    public function bidangAll(Request $request)
    {
        return User::select('id', 'nama_bidang')->get();
    }
}