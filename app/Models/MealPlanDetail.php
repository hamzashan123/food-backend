<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealPlanDetail extends Model
{

    use HasFactory;

    protected $table = 'meal_plan_details';

    protected $fillable = [
        'meal_plan_id',
        'meal_id',
        'day_id'
    ];
}
