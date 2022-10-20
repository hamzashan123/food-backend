<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use URL;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\File;
use App\Models\UserRole;
use App\Http\Resources\TagResource;
use App\Http\Resources\DaysResource;
use App\Models\MealDetail;
use App\Models\Day;
use App\Models\Meal;
use App\Http\Resources\MealResource;

class LandingMealPlanDaysResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $day = Day::active()->where("id", $this->pivot->day_id)->first();
        $meals = Meal::where("id", $this->pivot->meal_id)->first();

        //dd($meals);

        return [           
            'id'=> $day->id,
            'short_name'=> $day->short_name,
            'name'=> $day->name,
            //'meals'=> MealResource::collection($meals),
            'meals'=> $meals,
        ];
    }
}
