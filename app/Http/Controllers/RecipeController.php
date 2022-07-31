<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function GetAll(Request $request = null)
    {
        $data = Recipe::find(1)
                ->orderBy('title')
                ->take(10)
                ->get();

        return $data;
    }

    public function Add (Request $request)
    {
        $new_recipe = $request->all();
        $new_recipe['auth_id'] = $request->user()->id;
        // return $new_recipe;
        $recipe = Recipe::create($new_recipe);

        return [
            "success" => true,
            "message" => "The recipe was stored successfuly!",
            "res" => $recipe,
        ];
    }
}
