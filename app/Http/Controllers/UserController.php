<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Libraries\Utils;
use App\Mail\GeneralEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'name' => "data not valid!!",
            'email' => "data not valid!!",
            'password' => "data not valid!!",
        ]);

        $user = User::create($request->all());

        return $user;
    }

    public function update(Request $request, $id)
    {
        return User::where('id', $id)
            ->update($request->all());
    }

    public function delete($id)
    {
        return $user = User::destroy($id);
    }



    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return  User::find($id);
        // return  User::findOrFail($id);
    }




    public function login(Request $request)
    {

        return "hey";
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Mail::to("urisaul36@gmail.com")->send($user['email']);
     
        return $user->createToken($request->device_name)->plainTextToken;

        // $user = User::find(8);
        // return $user->createToken($request->token_name?? "main_tok", ['server:update']);
        // return true;
    }
}

