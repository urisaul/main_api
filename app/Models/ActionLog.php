<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    use HasFactory;

    // setup
    // protected $connection = "";
    // protected $table = ""
    const UPDATED_AT = 'date_updated';
    const CREATED_AT = 'date_created';


    protected $fillable = [
        "user_id",
        "action_source",
        "action_type",
        "model",
        "obj_id",
        "data",
    ];

    protected $casts = [
        "data" => "array"
    ];

    protected $attributes = [
        "user_id" => 0,
        // "date_created" => date("Y-m-d H:i:s"),
    ];
}
