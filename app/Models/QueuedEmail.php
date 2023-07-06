<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueuedEmail extends Model
{
    use HasFactory;

      // setup
    // protected $connection = "";
    // protected $table = ""
    const UPDATED_AT = 'date_updated';
    const CREATED_AT = 'date_created';


    protected $fillable = [
        "user_id",
        "send_to",
        "data",
        "available_from",
        "attempts",
    ];

    protected $casts = [
        "data" => "array"
    ];

    protected $attributes = [
        "user_id" => 0,
        "data" => "[]",
        "available_from" => date("Y-m-d H:i:s"),
        "attempts" => 0,
        "date_created" => date("Y-m-d H:i:s"),
    ];
}
