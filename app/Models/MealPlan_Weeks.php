<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealPlan_Weeks extends Model
{

    use HasFactory;

    protected $table = 'meal_plan_weeks';

    protected $fillable = [
        'meal_plan_id',
        'week_id'
    ];
}
