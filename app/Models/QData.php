<?php

namespace App\Models;

use App\Casts\QProperties;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QData extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $fillable = [
        "client_id",
        "object_id",
        "properties",
    ];

    protected $casts = [
        "properties" => QProperties::class,
    ];

    // mutators


    // relationships
    public function ObjectType()
    {
        return $this->belongsTo(QObject::class, 'object_id', "id");
    }
}
