<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealPlan_MealCategory extends Model
{

    use HasFactory;

    protected $table = 'meal_plan_meal_category';

    protected $fillable = [
        'meal_plan_id',
        'meal_id',
        'day_id',
        'meal_category'
    ];
}
