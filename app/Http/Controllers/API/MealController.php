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

        $meals = Meal::active();       

        if($request->filter != null) {

            $meals = $meals->where('name', 'like', '%' . $request->filter . '%')
                        ->orWhere('description', 'like', '%' . $request->filter . '%')
                        ->orWhere('details', 'like', '%' . $request->filter . '%');

            
            //Filter People Types
            $meals = $meals->orWhereHas('peopleType', function($query) use ($request) {
                $query = $query->where('status', '1')->where('name', 'like', "%" . $request->filter . "%");
                return $query;
            });

             //Filter Meal Types
             $meals = $meals->orWhereHas('mealType', function($query) use ($request) {
                $query = $query->where('status', '1')->where('name', 'like', "%" . $request->filter . "%");
                return $query;
            });

            //Filter Tags
            $meals = $meals->orWhereHas('tags', function($query) use ($request) {
                $query = $query->where('status', '1')->where('name', 'like', "%" . $request->filter . "%");
                return $query;
            });
        }

        $meals = $meals->paginate(10);
        

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

