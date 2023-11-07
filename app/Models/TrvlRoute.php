<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrvlRoute extends Model
{
    use HasFactory;

    // setup
    const UPDATED_AT = 'date_updated';
    const CREATED_AT = 'date_created';


    protected $fillable = [
        "name",
    ];
}
