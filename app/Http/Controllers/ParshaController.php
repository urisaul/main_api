<?php

namespace App\Http\Controllers;

use App\Mail\ParshaQuset;
use App\Models\ParshaUser;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public static function send_email($parsha, $template_id)
    {
        $users = ParshaUser::where("status", "sub")->get();

        Log::info("sending parsha email to users", ["users" => $users->pluck("email")->toArray() ?? ""]);

        $template = null;
        if (!$template_id || intval($template_id) === 1) {
            $template = new ParshaQuset($parsha);
        } else if (intval($template_id) === 2) {
            # template with anoncemant of Chabura
        } else if (intval($template_id) === 3) {
            # template with live link to Chabura
        }

        Mail::mailer('smtp_2')
            // ->to("urisaul36@gmail.com")
            ->bcc($users)
            ->send($template);

        return [
            "success" => true,
            "message" => "emails were sent successfuly!!"
        ];
    }

    public static function send_email_req(Request $request)
    {
        $users = ParshaUser::where("status", "sub")->get();

        Log::info("sending parsha email to users", ["users" => $users->pluck("email")->toArray() ?? ""]);

        $parsha = $request->input("parsha", 1);
        $template = intval($request->input("template", 1));
        $data_for_template = $request->input("data_for_template", []);

        $email = new ParshaQuset($parsha, $template, $data_for_template);

        Mail::mailer('smtp_2')
            // ->to("urisaul36@gmail.com")
            ->bcc($users)
            ->send($email);

        return [
            "success" => true,
            "message" => "emails were sent successfuly!!",
            "count"   => $users->count(),
        ];
    }

    public static function schedule_send($parsha, $date = null, $template = 1)
    {
        // check that file exsits
        ParshaController::get_file($parsha);
        
        return Task::create([
            "user_id" => Auth::user()->id ?? 0,
            "task_name" => "\\App\\Http\\Controllers\\ParshaController::send_email",
            "data" => [$parsha, $template],
            "available_from" => $date ?: date("Y-m-d H:i:s"),
        ]);
    }

    public function schedule_send_req(Request $request)
    {
        return ParshaController::schedule_send($request->input('parsha'), $request->input('date'), $request->input('template'));
    }

    public static function get_file($parsha)
    {
        $files = scandir(storage_path('app/public/parsha_files/'));
        $filename = array_values(array_filter($files, function ($i) use ($parsha) {
            return str_contains($i, $parsha);
        }))[0]??false;
        if (!$filename) {
            throw new Exception("Could not find parsha file", 1);  
        }
        Log::info("Parsha file", [
            "req" => $parsha,
            "res" => storage_path('app/public/parsha_files/' . $filename),
        ]);
        return storage_path('app/public/parsha_files/' . $filename);
    }
}
