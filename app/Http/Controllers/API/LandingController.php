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
        
        $thisWeekNo = $this->weekOfMonth(strtotime(date('Y-m-d')));
        $weekNo = ($thisWeekNo > 4 ? 1 : $thisWeekNo);

        $upcoming = ($thisWeekNo + 1);
        $upcoming = ($upcoming > 4 ? 1 : $upcoming);
        
        //Get Current Week
        $weeks = MealPlan::with('weeks')->whereHas('weeks',function($query) use($weekNo) {
            return $query->where('id', $weekNo);
        })->active()->get();

        //Get Upcoming Week Meals
        $upcomingWeeks = MealPlan::with('weeks')->whereHas('weeks',function($query) use($upcoming) {
            return $query->where('id', $upcoming);
        })->active()->get();
        

        if(count($weeks) > 0)
        {
            $response_data = [
                'success' => true,
                'message' =>  'Meal Plan List',
                'meals_served_this_week' => LandingResource::collection($weeks),
                'upcoming_week_meals' => LandingResource::collection($upcomingWeeks),
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

