<?php

namespace App\Observers;

use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ModelObserver
{
    /**
     * Handle the  "created" event.
     *
     * @param  \App\Models\  $model
     * @return void
     */
    public function created($model)
    {
        $changes = $model->getDirty();
        unset($changes['updated_at']);
        ActionLog::create([
            "user_id" => Auth::user()->id ?? 0,
            "action_source" => Request::getMethod(),
            "action_type" => "create",
            "model" => class_basename($model),
            "data" => $changes,
        ]);
    }

    /**
     * Handle the  "updated" event.
     *
     * @param  \App\Models\  $model
     * @return void
     */
    public function updated($model)
    {
        $changes = $model->getDirty();
        unset($changes['updated_at']);
        ActionLog::create([
            "user_id" => Auth::user()->id ?? 0,
            "action_source" => Request::getMethod(),
            "action_type" => "update",
            "model" => class_basename($model),
            "data" => $changes,
        ]);
    }

    /**
     * Handle the  "deleted" event.
     *
     * @param  \App\Models\  $model
     * @return void
     */
    public function deleted($model)
    {
        $changes = $model->getDirty();
        unset($changes['updated_at']);
        ActionLog::create([
            "user_id" => Auth::user()->id ?? 0,
            "action_source" => Request::getMethod(),
            "action_type" => "delete",
            "model" => class_basename($model),
            "data" => $changes,
        ]);
    }

    /**
     * Handle the  "restored" event.
     *
     * @param  \App\Models\  $model
     * @return void
     */
    public function restored($model)
    {
        //
    }

    /**
     * Handle the  "force deleted" event.
     *
     * @param  \App\Models\  $model
     * @return void
     */
    public function forceDeleted($model)
    {
        //
    }
}
