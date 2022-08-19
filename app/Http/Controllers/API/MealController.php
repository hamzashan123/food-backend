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
use App\Models\Meal;
use App\Http\Resources\MealResource;

class MealController extends Controller
{


    public $successStatus = 200;

    public function getMeals(Request $request) {

        $meals = Meal::active()->paginate(10);        

        if(count($meals) > 0)
        {
            $response_data = [
                'success' => true,
                'message' =>  'Meal List',
                'data' => MealResource::collection($meals),
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

    public function getMealById(Request $request) {
        $validator = Validator::make($request->all(), [
            'meal_id'          => 'required',
        ]);

        if ($validator->fails()) {
            $response_data = [
                'success' => false,
                'message' => 'Incomplete data provided!',
                'errors' => $validator->errors()
            ];
            return response()->json($response_data);
        }

        $meals = Meal::active()->where('id',$request->meal_id)->first();

        if($meals != null)
        {
            $response_data = [
                'success' => true,
                'message' =>  'Meal Record',
                'data' => new MealResource($meals),
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

