<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Week extends Model
{
    use HasFactory;
    use Sluggable;
    use SearchableTrait;

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'name' => [
                'source' => 'name'
            ]
        ];
    }

    protected $searchable = [
        'columns' => [
            'weeks.name' => 10,
        ],
    ];    

    public function mealplans()
    {
        return $this->belongsToMany(MealPlan::class, 'meal_plan_weeks');
    }    
}
