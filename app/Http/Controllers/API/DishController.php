<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Helper;
use Notification;
use App\Notifications\EmailVerification;
use URL;
use Validator;
use Mail;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Dish;
use App\Http\Resources\DishResource;

class DishController extends Controller
{


    public $successStatus = 200;

    public function getDishes(Request $request) {

        $dishes = Dish::active();

        if($request->filter != null) {
            $dishes = $dishes->where('name', 'like', '%' . $request->filter . '%')
                             ->orWhere('description', 'like', '%' . $request->filter . '%')
                             ->orWhere('details', 'like', '%' . $request->filter . '%');
                   
            //$dishes = $dishes->orWhereHas()
        }

        $dishes = $dishes->paginate(10);

        if(count($dishes) > 0)
        {
            $response_data = [
                'success' => true,
                'message' =>  'Dish List',
                'data' => DishResource::collection($dishes),
            ];
            return response()->json($response_data, $this->successStatus);
        } else {
            $response_data = [
                'success' => false,
                'message' => 'Data Not Found',
            ];
            return response()->json($response_data, $this->successStatus);
        }
    }

    public function getDishById(Request $request) {

        $validator = Validator::make($request->all(), [
            'dish_id'          => 'required',                
        ]);

        if ($validator->fails()) {
            $response_data = [
                'success' => false,
                'message' => 'Incomplete data provided!',
                'errors' => $validator->errors()
            ];
            return response()->json($response_data);
        }

        $dishes = Dish::active()->where('id',$request->dish_id)->first();

        if($dishes != null)
        {
            $response_data = [
                'success' => true,
                'message' =>  'Dish Record',
                'data' => new DishResource($dishes),
            ];
            return response()->json($response_data, $this->successStatus);
        } else {
            $response_data = [
                'success' => false,
                'message' => 'Data Not Found',
            ];
            return response()->json($response_data, $this->successStatus);
        }
    }

}

