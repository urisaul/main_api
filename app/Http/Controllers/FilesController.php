<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public static function uploadFileFromReq(Request $request, $prop)
    {
        $base64Image = $request->input($prop);

        // Extract the data and mime type from the base64 string
        list($type, $data) = explode(';', $base64Image);
        list(, $data)      = explode(',', $data);

        // Decode the base64 image
        $image = base64_decode($data);

        // Determine the file extension based on the mime type
        $extension = explode('/', $type)[1];

        // Generate a unique filename for the image
        $filename = uniqid("upload_", true) . $extension;

        // Store the image in the storage
        Storage::disk('public')->put($filename, $image);

        $request->merge([
            $prop => asset('storage/' . $filename),
        ]);

        return $request;
    }

    public static function uploadFilesFromReq(Request $request, $props)
    {
        foreach ($props as $prop) {
            $request = FilesController::uploadFileFromReq($request, $prop);
        }
        return $request;
    }

    public static function uploadFileFromArr(&$arr, $prop)
    {
        $base64Image = $arr[$prop];

        // Extract the data and mime type from the base64 string
        list($type, $data) = explode(';', $base64Image);
        list(, $data)      = explode(',', $data);

        // Decode the base64 image
        $image = base64_decode($data);

        // Determine the file extension based on the mime type
        $extension = explode('/', $type)[1];

        // Generate a unique filename for the image
        $filename = uniqid("upload_", true) . "." . $extension;

        // Store the image in the storage
        Storage::disk('public')->put($filename, $image);

        $arr[$prop] = asset('storage/' . $filename);

        return $arr;
    }
}
