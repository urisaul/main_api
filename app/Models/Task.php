<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Task extends Model
{
    use HasFactory;

     // setup
    
     // protected $connection = "";
    // protected $table = ""
    const UPDATED_AT = 'date_updated';
    const CREATED_AT = 'date_created';


    protected $fillable = [
        "user_id",
        "task_name",
        "data",
        "available_from",
        "attempts",
        "errors",
    ];

    protected $casts = [
        "data" => "array",
        "errors" => "array",
    ];

    protected $attributes = [
        "user_id" => 0,
        "data" => "[]",
        "attempts" => 0,
    ];


    // actions

    public function run () {

        if ($this->attempts >= 3) {
            $this->move_to_faild_tasks();
            return false;
        }
        $this->attempts++;
        $this->save();

        // check if task exists
        if (!is_callable($this->task_name)) {
            $this->errors = ["task not found"];
            $this->save();
            $this->move_to_faild_tasks();
            return false;
        }

        // execute the function
        try {
            $res = call_user_func_array($this->task_name, $this->data);
            return $this->delete();
        } catch (\Throwable $th) {
            $this->errors = array_merge($this->errors ?: [], [$th->getMessage()]);
            $this->save();
        }

        return false;
    }

    public function move_to_faild_tasks () {
        Log::error("Task Faild", $this->getAttributes());
        DB::table("tasks_faild")->insert($this->getAttributes());
        Mail::mailer('smtp_2')
            ->send([], [], function ($message) {
                $message->to("urisaul36@gmail.com")
                  ->subject("Task Failed")
                  ->setBody('This task failed ' . ($this->task_name ?? "unknown") . ' and was moved to the failed tasks table.');
              });
        return $this->delete();
    }
}
