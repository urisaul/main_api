<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrvlIndividual extends Model
{
    use HasFactory;

    // setup
    const UPDATED_AT = 'date_updated';
    const CREATED_AT = 'date_created';


    protected $fillable = [
        'firstname',
        'lastname',
        'address',
    ];


}
