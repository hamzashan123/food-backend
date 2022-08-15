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

class Dish extends Model
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
            'dishes.name' => 10,
            'dishes.slug' => 10
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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'dish_tags', 'dish_id', 'tag_id');
    }

    public function ingrediants(): BelongsToMany
    {
        return $this->belongsToMany(Ingrediant::class, 'dish_ingrediants', 'dish_id', 'ingrediant_id');
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
