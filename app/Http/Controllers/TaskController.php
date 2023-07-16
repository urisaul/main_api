<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public static function run_tasks() {
        $tasks = Task::where("available_from", "<=", date("Y-m-d H:i:s"))->limit(12)->orderBy("priority")->get();

        $tasks->map(function ($task) {
            Log::debug("Rinning Task", ["Task" => $task->getAttributes()]);
            return $task->run();
        });

        return true;
    }
}
