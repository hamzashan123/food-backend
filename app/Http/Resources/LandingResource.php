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

        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'price'=> $this->price,
            'status'=> $this->status,
            'description'=> $this->description,
            'details'=> $this->details,
            'tags'=> TagResource::collection($this->tags),
            'weeks'=> WeekResource::collection($this->weeks),
            'days'=> LandingMealPlanDaysResource::collection($this->meals),
            'images'=> $ImageArray,
        ];
    }
}
