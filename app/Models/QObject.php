<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QObject extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $fillable = [
        "client_id",
        "name",
        // "internal_name",
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->internal_name = Str::snake(Str::replace(["'",'"',"-"], "_", $model->name));
        });
    }

}
