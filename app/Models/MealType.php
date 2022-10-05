<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class MealType extends Model
{
    protected $guarded = [];

    use HasFactory;
    use Sluggable;
    use SearchableTrait;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $searchable = [

        'columns' => [
            'meal_types.name' => 10,
            'meal_types.slug' => 10,
        ],
    ];

    public function getStatusAttribute(): string
    {
        return $this->attributes['status'] == 0 ? 'Inactive' : 'Active';
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }

    public function meals()
    {
        //return $this->hasMany(Meal::class);
        return $this->belongsToMany(Meal::class, 'meal_meal_type');
    }

    //public function mealplans()
    //{
    //    return $this->belongsToMany(MealPlan::class, 'meal_plan_types');
    //}
}
