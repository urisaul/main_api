<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QData extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $fillable = [
        "client_id",
        "object_id",
        "properties",
    ];

    protected $casts = [
        "properties" => "array",
    ];
}
