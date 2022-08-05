<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function GetAll(Request $request = null)
    {
        $data = Recipe::with(['comments'])
                ->orderBy('title')
                ->take(10)
                ->get();

        return $data;
    }

    public function Add (Request $request)
    {
        $new_recipe = $request->all();
        $new_recipe['auth_id'] = $request->user()->id;
        $recipe = Recipe::create($new_recipe);

        return [
            "success" => true,
            "message" => "The recipe was stored successfuly!",
            "res" => $recipe,
        ];
    }

    public function Update (Request $request)
    {
        $id = $request->input("id")?? false;
        if (!$id) {
            return [
                "success" => false,
                "message" => "ID is requierd!!"
            ];
        }
        $recipe = Recipe::find($id);
        $user = $request->user();
        if (($user['role'] && $user['role'] == "admin") || $user['id'] == $recipe['auth_id']) {
            // update
            $recipe->update($request->all());
            $res = [
                "seccess" => true,
                "message" => "Updated successfuly!!"
            ];

        } else {
            $res = [
                "success" => false,
                "message" => "You don't have premission to update this recipe!"
            ];
        }
        return $res;
    }

    public function Delete (Request $request)
    {
        $id = $request->input("id")?? false;
        if (!$id) {
            return [
                "success" => false,
                "message" => "ID is requierd!!"
            ];
        }
        $recipe = Recipe::find($id);
        $user = $request->user();
        if (($user['role'] && $user['role'] == "admin") || $user['id'] == $recipe['auth_id']) {
            // delete
            $recipe->delete();
            $res = [
                "seccess" => true,
                "message" => "Deleted successfuly!!"
            ];

        } else {
            $res = [
                "success" => false,
                "message" => "You don't have premission to delete this recipe!"
            ];
        }
        return $res;
    }
}
