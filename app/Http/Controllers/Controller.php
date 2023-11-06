<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $models = [];

    public function get(Request $request, $model)
    {
        $data = $this->models[$model]::get();
        return $data;
    }

    public function get_one(Request $request, $model, $id)
    {
        $data = $this->models[$model]::find($id);
        return $data;
    }

    public function create(Request $request, $model)
    {
        $obj = new $this->models[$model]($request->all());
        $success = $obj->save();

        return [
            "success" => $success,
            "data" => $obj,
        ];
    }

    public function update(Request $request, $model)
    {

        return true;
    }

    public function delete(Request $request, $model)
    {

        return true;
    }

}
