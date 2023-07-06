<?php

namespace App\Http\Controllers;

use App\Models\Task;

class TaskController extends Controller
{
    public static function run_tasks() {
        $tasks = Task::where("available_from", "<=", date("Y-m-d H:i:s"))->limit(12)->orderBy("priority")->get();

        foreach ($tasks as $task) {
            $task->run();
        }
        return true;
    }
}
