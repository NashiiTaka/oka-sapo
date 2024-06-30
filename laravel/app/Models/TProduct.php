<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TProducts extends Model
{
    use HasFactory;

    static function getRecommendations()
    {
        return TProducts::limit(3)->get();
    }
}
