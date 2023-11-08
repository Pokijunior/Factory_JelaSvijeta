<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $hidden = ['created_at', 'updated_at', 'category_id'];
    use HasFactory;

    public function category()
    {
        return $this -> belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this -> belongsToMany(Ingredient::class);
    }

    public function tags()
    {
        return $this -> belongsToMany(Tag::class);
    }
}
