<?php

namespace App\Http\Controllers;

use App\Models\QData;
use App\Models\QObject;
use Illuminate\Http\Request;

class GenManagerController extends Controller
{

    public $models = [
        "objects" => App\Models\QObject::class,
        "properties" => App\Models\QProperty::class,
        "property_option" => App\Models\QPropertyOption::class,
    ];


    public function create_(Request $request, $client_id, $object_id)
    {
        $request->validate([
            "properties" => ["array"]
        ]);

        $obj_t = QObject::where("client_id", $client_id)->where("id", $object_id)->firstOrFail();
        $obj = new QData([
            // "client_id" => $client_id,
            "object_id" => $obj_t->id,
            "properties" => $request->input("properties", []),
        ]);
        $success = $obj->save();

        return [
            "success" => $success,
            "data" => $obj,
        ];
    }

    public function get_(Request $request, $client_id, $object_id)
    {

        $obj_t = QObject::where("client_id", $client_id)->where("id", $object_id)->firstOrFail();
        $data = QData::where("object_id", $obj_t->id);

        if ($q = $request->input("q")) {
            $data->where("properties", "like", "%$q%");
        }

        return $data->paginate($request->input("per_page", 50));
    }

    public function get_one_(Request $request, $client_id, $object_id, $id)
    {
        $obj_t = QObject::where("client_id", $client_id)->where("id", $object_id)->firstOrFail();
        $data = QData::where("object_id", $obj_t->id)
            ->where("id", $id)->firstOrFail();

        return $data;
    }

    public function create_ad(Request $request, $client_id, $model)
    {
        $request->validate([
            "properties" => ["array"]
        ]);

        $obj = new $this->models[$model]([
            "client_id" => $client_id,
        ]);

        $obj->fill($request->input("properties", []));
        $success = $obj->save();

        return [
            "success" => $success,
            "data" => $obj,
        ];
    }
}
