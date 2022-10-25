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
use App\Http\Resources\WeekResource;
use App\Http\Resources\IngrediantsResource;
use App\Http\Resources\LandingMealPlanDaysResource;

class LandingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ImageArray = [];
        $rownumber = 1;
        foreach ($this->media as $image) {         

            $imageurl = '';
            if (($image->file_name == 'placeholder.png')  || ($image->file_name == null)){
                $imageurl = URL::to('/') . Storage::disk('local')->url('public/users/placeholder.png');
            } else {
                $imageurl = URL::to('/') . Storage::disk('local')->url('public/images/mealplans/' .$image->file_name);
            }

            $ImageArray[] = $imageurl;
            $rownumber = ($rownumber + 1);
        }  

        //$mondayMeals = $this->meals;
        $mondayMeals = $this->meals()->wherePivot('day_id', 1)->get();
        $tuesdayMeals = $this->meals()->wherePivot('day_id', 2)->get();        
        $wednesdayMeals = $this->meals()->wherePivot('day_id', 3)->get();
        $thursdayMeals = $this->meals()->wherePivot('day_id', 4)->get();
        $fridayMeals = $this->meals()->wherePivot('day_id', 5)->get();
        $saturdayMeals = $this->meals()->wherePivot('day_id', 6)->get();
        $sundayMeals = $this->meals()->wherePivot('day_id', 7)->get();
        
        //$daysMeals = [
                //'Monday_Meals' => $mondayMeals,
                //'Tuesday_Meals' => $tuesdayMeals,
                /*
                'Wednesday_Meals' => $wednesdayMeals,
                'Thursday_Meals' => $thursdayMeals,
                'Friday_Meals' => $fridayMeals,
                'Saturday_Meals' => $saturdayMeals,
                'Sunday_Meals' => $sundayMeals,*/
          //  ];

        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'price'=> $this->price,
            'status'=> $this->status,
            'description'=> $this->description,
            'details'=> $this->details,
            'tags'=> TagResource::collection($this->tags),
            'weeks'=> WeekResource::collection($this->weeks),
            'Monday_Meals'=> LandingMealPlanDaysResource::collection($mondayMeals),
            'Tuesday_Meals'=> LandingMealPlanDaysResource::collection($tuesdayMeals),
            'Wednesday_Meals'=> LandingMealPlanDaysResource::collection($wednesdayMeals),
            'Thursday_Meals'=> LandingMealPlanDaysResource::collection($thursdayMeals),
            'Friday_Meals'=> LandingMealPlanDaysResource::collection($fridayMeals),
            'Saturday_Meals'=> LandingMealPlanDaysResource::collection($saturdayMeals),
            'Sunday_Meals'=> LandingMealPlanDaysResource::collection($sundayMeals),
            'images'=> $ImageArray,
        ];
    }
}
