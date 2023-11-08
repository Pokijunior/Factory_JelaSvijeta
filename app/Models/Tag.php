<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $hidden = ['created_at', 'updated_at', 'pivot'];
    use HasFactory;

    public function meals()
    {
        return $this -> belongsToMany(Meal::class);
    }
}
