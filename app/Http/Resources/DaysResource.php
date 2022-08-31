<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use URL;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\File;
use App\Models\UserRole;
use App\Http\Resources\DaysResource;
use App\Models\MealDetail;
use App\Models\Dish;

class DaysResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->meal_id);
        $mealDishes = MealDetail::where('meal_id', $this->meal_id)->where('day_id', $this->id)->pluck('dish_id');
        $dishes = Dish::whereIn('id', $mealDishes)->get();

        //dd($dishes);


        return [
            'id'=> $this->id,
            'short_name'=> $this->short_name,
            'name'=> $this->name,
            //'dishes'=> $this->getDishes,
            //'dishes'=> DishResource::collection($this->$dishes) ?? '',
        ];
    }
}
