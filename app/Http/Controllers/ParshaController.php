<?php

namespace App\Http\Controllers;

use App\Mail\ParshaQuset;
use App\Models\ParshaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ParshaController extends Controller
{
    public function email_pref(Request $request)
    {
        $request->validate([
            "user_e" => "string|email",
            "action" => ["in:sub,unsub"],
        ]);

        $user = ParshaUser::firstOrCreate(
            ['email' => $request['user_e']]
        );
        $user->status = $request['action'] === "sub" ? "sub" : "unsub";
        $user->save();

        $success = (bool) $user;

        $full_action = $user->status === "sub" ? "subscribed to" : "unsubscribed from";

        return response([
            "success" => $success,
            "message" => $success ? "You have successfuly {$full_action} the email list!" : "an error has occurred"
        ], $success ? 200 : 400);
    }

    public function send_email(Request $request)
    {
        $parsha = $request->input("parsha", "כי תשא");
        $users = ParshaUser::where("status", "sub")->get();

        foreach ($users as $user) {
            Mail::mailer('smtp_2')->to("urisaul36@gmail.com")
                ->bcc($user)
                ->send(new ParshaQuset($user, $parsha));
        }

        return [
            "success" => true,
            "message" => "emails were sent successfuly!!"
        ];
    }
}
