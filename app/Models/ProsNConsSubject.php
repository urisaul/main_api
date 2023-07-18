<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsNConsSubject extends Model
{
    use HasFactory;

    const UPDATED_AT = 'date_updated';
    const CREATED_AT = 'date_created';

    protected $fillable = [
        "user_id",
        "data",
    ];

    protected $casts = [
        "data" => "array",
    ];
}
