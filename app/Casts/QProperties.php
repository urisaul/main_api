<?php

namespace App\Casts;

use App\Http\Controllers\FilesController;
use App\Models\QData;
use App\Models\QObject;
use App\Models\QProperty;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use SebastianBergmann\Type\ObjectType;

class QProperties implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return json_decode($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        $data = (array)$model->getAttribute("properties");
        $props = QObject::find($attributes['object_id'])->Properties;

        foreach ($props as $prop) {
            if ($value[$prop->internal_name] ?? false) {
                // check type of property
                if ($prop->type === 'file') {
                    FilesController::uploadFileFromArr($value, $prop->internal_name);
                }

                $data[$prop->internal_name] = $value[$prop->internal_name];
            }
        }

        return json_encode($data);
    }
}
