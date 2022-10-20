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
use App\Models\MealPlan;
use App\Http\Resources\DishResource;
use App\Http\Resources\LandingResource;


class LandingController extends Controller
{

    function weekOfMonth($date) {
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        return $this->weekOfYear($date) - $this->weekOfYear($firstOfMonth) + 1;
    }

    function weekOfYear($date) {
        $weekOfYear = intval(date("W", $date));
        if (date('n', $date) == "1" && $weekOfYear > 51) {
            // It's the last week of the previos year.
            return 0;
        }
        else if (date('n', $date) == "12" && $weekOfYear == 1) {
            // It's the first week of the next year.
            return 53;
        }
        else {
            // It's a "normal" week.
            return $weekOfYear;
        }
    }

    public $successStatus = 200;

    public function getLandingPage(Request $request) {
        
        $weekNo = $this->weekOfMonth(strtotime(date('Y-m-d')));
        $weekNo = ($weekNo > 4 ? 1 : $weekNo);
        
        //Get Current Week
        $weeks = MealPlan::with('weeks')->whereHas('weeks',function($query) use($weekNo) {
            return $query->where('id', $weekNo);
        })->active()->get();



        if(count($weeks) > 0)
        {
            $response_data = [
                'success' => true,
                'message' =>  'Meal Plan List',
                'data' => LandingResource::collection($weeks),
            ];
            return response()->json($response_data, $this->successStatus);
        } else {
            $response_data = [
                'success' => false,
                'message' => 'Data Not Found',
            ];
            return response()->json($response_data, $this->successStatus);
        }


        $dishes = Dish::active();

        if($request->filter != null) {
            $dishes = $dishes->where('name', 'like', '%' . $request->filter . '%')
                             ->orWhere('description', 'like', '%' . $request->filter . '%')
                             ->orWhere('details', 'like', '%' . $request->filter . '%');
            
            //Filter People Types
            $dishes = $dishes->orWhereHas('peopleType', function($query) use ($request) {
                $query = $query->where('status', '1')->where('name', 'like', "%" . $request->filter . "%");
                return $query;
            });

            //Filter Tags
            $dishes = $dishes->orWhereHas('tags', function($query) use ($request) {
                $query = $query->where('status', '1')->where('name', 'like', "%" . $request->filter . "%");
                return $query;
            });

            //Filter Ingregiats
            $dishes = $dishes->orWhereHas('ingrediants', function($query) use ($request) {
                $query = $query->where('status', '1')->where('name', 'like', "%" . $request->filter . "%");
                return $query;
            });
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

   

}

