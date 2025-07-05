<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfierArchitectsProject extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'ofier_architects_projects';

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
