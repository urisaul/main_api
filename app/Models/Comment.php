<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $fillable = ['auth_id', 'recipe_id', 'rating', 'title', 'body'];

    public function recipe ()
    {
        $this->belongsTo(Recipe::class, 'id', 'recipe_id');
    }

}
