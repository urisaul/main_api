<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelController extends Controller
{
    public $models = [
        "drivers"     => \App\Models\TrvlDriver::class,
        "routes"      => \App\Models\TrvlRoute::class,
        "individuals" => \App\Models\TrvlIndividual::class,
    ];
    
}
