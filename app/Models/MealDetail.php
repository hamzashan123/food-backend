<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Day;

class MealDetail extends Model
{

    use HasFactory;

    protected $table = 'meal_details';

    protected $fillable = [
        'meal_id',
        'dish_id'
    ];
}
