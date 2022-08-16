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
use App\Http\Resources\IngrediantsResource;

class DishResource extends JsonResource
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
                $imageurl = URL::to('/') . Storage::disk('local')->url('public/images/dishes/' .$image->file_name);
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
            'peopleType'=> $this->peopleType->name,
            'tags'=> TagResource::collection($this->tags),
            'ingrediants'=> IngrediantsResource::collection($this->ingrediants),
            'images'=> $ImageArray,
        ];
    }
}
