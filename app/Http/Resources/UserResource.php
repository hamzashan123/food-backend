<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use URL;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\File;
use App\Models\UserRole;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $Avatarurl = '';
        if (($this->user_image == 'placeholder.png')  || ($this->user_image == null)){
            $Avatarurl = URL::to('/') . Storage::disk('local')->url('public/users/placeholder.png');
        } else {
            $Avatarurl = URL::to('/') . Storage::disk('local')->url('public/users/' .$this->id . '/' . $this->user_image);
        }

        return [
            'id'=> $this->id,
            'first_name'=> $this->first_name,
            'last_name'=> $this->last_name,
            'full_name'=> $this->full_name,            
            'username'=> $this->username,
            'email'=> $this->email,
            'contact'=> $this->phone,
            'status'=> $this->status,
            'avatar'=> $Avatarurl,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
