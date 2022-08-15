<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Nicolaslopezj\Searchable\SearchableTrait;

class Meal extends Model
{
    use HasFactory, Sluggable, SearchableTrait;

    protected $guarded = [];

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
            'meals.name' => 10,
            'meals.slug' => 10
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

    public function scopeActivePeopleType($query)
    {
        return $query->whereHas('peopleType', function ($query) {
            $query->whereStatus(1);
        });
    }

    public function peopleType(): BelongsTo
    {
        return $this->belongsTo(PeopleType::class, 'people_types_id', 'id');
    }

    public function scopeActiveMealType($query)
    {
        return $query->whereHas('mealType', function ($query) {
            $query->whereStatus(1);
        });
    }

    public function mealType(): BelongsTo
    {
        return $this->belongsTo(MealType::class, 'meal_types_id','id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'meal_tags', 'meal_id', 'tag_id');
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'meal_details', 'meal_id', 'dish_id')->withPivot(['day_id']);
    }    

    public function days()
    {
        return $this->belongsToMany(Day::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
  
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function firstMedia(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->orderBy('file_sort', 'asc');
    }
}
