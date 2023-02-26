<?php

namespace App\Http\Libraries;

class Utils {


    /**
     * converts obj into array
     * 
     */
    public static function obj_to_arr($obj)
    {
        return json_decode(json_encode($obj), true);
    }


}