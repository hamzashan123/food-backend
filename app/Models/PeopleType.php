<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Nicolaslopezj\Searchable\SearchableTrait;

class PeopleType extends Model
{
    protected $guarded = [];

    use Sluggable, SearchableTrait;

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
            'people_types.name' => 10,
            'people_types.slug' => 10,
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

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
