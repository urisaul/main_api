<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function GetAll(Request $request = null)
    {
        $data = Comment::where('status', "=", "published")
            ->orderBy('title')
            ->take(10)
            ->get();

        return $data;
    }


    public function get_for_recipe(Request $request = null, $recipe_id)
    {
        if (!$recipe_id) {
            return response(["success" => false, "message" => "Recipe ID is requierd!"], 400);
        }
        $data = Comment::where([['status', "=", "published"], ["recipe_id", "=", $recipe_id]])
            ->orderBy('title')
            ->take(10)
            ->get();

        return $data;
    }


    public function Add(Request $request)
    {
        $new_comment = $request->all();
        $new_comment['auth_id'] = $request->user()->id;
        $new_comment['rating'] = $new_comment['rating'] > 5 ? 5 : ($new_comment['rating'] < 1? 1 : intval($new_comment['rating']));
        $comment = Comment::create($new_comment);

        $comment->Update(["status" => "published"]);

        RecipeController::eval_recipe_rating($new_comment['recipe_id']??0);

        return [
            "success" => true,
            "message" => "The comment was stored successfuly!",
            "res" => $comment,
        ];
    }

    public function Update(Request $request)
    {
        $id = $request->input("id") ?? false;
        if (!$id) {
            return [
                "success" => false,
                "message" => "ID is requierd!!"
            ];
        }
        $comment = Comment::find($id);
        $user = $request->user();
        if (($user['role'] && $user['role'] == "admin") || $user['id'] == $comment['auth_id']) {
            // update
            $comment->update($request->all());
            $res = [
                "seccess" => true,
                "message" => "Updated successfuly!!"
            ];
        } else {
            $res = [
                "success" => false,
                "message" => "You don't have premission to update this comm$comment!"
            ];
        }
        return $res;
    }

    public function Delete(Request $request)
    {
        $id = $request->input("id") ?? false;
        if (!$id) {
            return [
                "success" => false,
                "message" => "ID is requierd!!"
            ];
        }
        $comment = Comment::find($id);
        $user = $request->user();
        if (($user['role'] && $user['role'] == "admin") || $user['id'] == $comment['auth_id']) {
            // delete
            $comment->delete();
            $res = [
                "seccess" => true,
                "message" => "Deleted successfuly!!"
            ];
        } else {
            $res = [
                "success" => false,
                "message" => "You don't have premission to delete this comment!"
            ];
        }
        return $res;
    }
}
