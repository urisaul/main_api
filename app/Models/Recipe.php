<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql2';
    protected $table = 'recipes_1';

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $fillable = ['title', 'auth_id', 'recipe_kind', 'description', 'ingredients', 'body', 'categories', 'shared_with'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'recipe_id', 'id');
    }


}
