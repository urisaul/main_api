<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfierArchitectsContactSub extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'ofier_architects_contact_submissions';

    protected $fillable = [
        'submission',
    ];

    protected $casts = [
        'submission' => 'array',
    ];
}
