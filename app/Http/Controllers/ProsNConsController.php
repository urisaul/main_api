<?php

namespace App\Http\Controllers;

use App\Models\ProsNConsSubject;
use Illuminate\Http\Request;

class ProsNConsController extends Controller
{
    public function GetAll (Request $request) 
    {
        return ProsNConsSubject::where("id", ">", 0)->get();
    }
    public function GetOne (Request $request, $id) 
    {
        return ProsNConsSubject::where("id", $id)->firstOrFail();
    }
    public function Add (Request $request) 
    {
        $new_obj = new ProsNConsSubject($request->all());
        $new_obj->save();
        return $new_obj;
    }
    public function Update (Request $request) 
    {
        $obj = ProsNConsSubject::where("id", $request->input('id'))->firstOrFail();
        $obj->update($request->all());

        return $obj;
    }
    public function Delete (Request $request) 
    {
        return ProsNConsSubject::where("id", $request->input('id'))->delete();
    }
}
