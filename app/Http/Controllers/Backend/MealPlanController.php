<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Week;
use App\Services\ImageService;
use App\Traits\ImageUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\PeopleType;
use App\Models\MealType;
use App\Models\Meal;
use App\Models\City;
use App\Models\Dish;
use App\Models\Day;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Backend\MealPlanRequest;


use App\Models\MealPlan;

class MealPlanController extends Controller
{
    use ImageUploadTrait;

    public function index(): View
    {
        $this->authorize('access_mealplan');

        $mealplans = MealPlan::with('tags', 'firstMedia')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sortBy ?? 'id', \request()->orderBy ?? 'desc')
            ->paginate(\request()->limitBy ?? 10);

        return view('backend.mealplans.index', compact('mealplans'));
    }

    public function create(): View
    {
        $this->authorize('create_mealplan');

        //$people_types = PeopleType::active()->get(['id', 'name']);
        
        $meals = Meal::active()->get(['id', 'name']);
        $meal_category = MealType::active()->get(['id', 'name']);
        //$dishes = Dish::active()->get(['id', 'name', 'price']);
        //$days = Day::active()->get(['id', 'short_name', 'name']);
        $tags = Tag::active()->get(['id', 'name']);
        $weeks = Week::get(['id', 'name']);

        //return view('backend.meals.create', compact('tags', 'meal_types', 'people_types', 'dishes', 'days'));
        return view('backend.mealplans.create', compact('tags', 'weeks', 'meal_category', 'meals'));
    }

    public function store(MealPlanRequest $request): RedirectResponse
    {        
        $request->request->remove('meal_id');
        $request->request->remove('meal_category');
        $mdealDetail_MealCategories = json_decode(json_decode($request['meal_cat']),true);

        $meals=[];
        if(isset($request['mondaymeals'])) {
            for ($m=0; $m < count($request['mondaymeals']); $m++) {                
                $data = ["meal_id" => $request['mondaymeals'][$m], "day_id" => 1];
                $meals[] = $data;                
            }            
        }
        if(isset($request['tuesdaymeals'])) {
            for ($m=0; $m < count($request['tuesdaymeals']); $m++) {                
                $data = ["meal_id" => $request['tuesdaymeals'][$m], "day_id" => 2];
                $meals[] = $data;                
            }            
        }
        if(isset($request['wednesdaymeals'])) {
            for ($m=0; $m < count($request['wednesdaymeals']); $m++) {                
                $data = ["meal_id" => $request['wednesdaymeals'][$m], "day_id" => 3];
                $meals[] = $data;                
            }            
        }
        if(isset($request['thursdaymeals'])) {
            for ($m=0; $m < count($request['thursdaymeals']); $m++) {                
                $data = ["meal_id" => $request['thursdaymeals'][$m], "day_id" => 4];
                $meals[] = $data;                
            }            
        }
        if(isset($request['fridaymeals'])) {
            for ($m=0; $m < count($request['fridaymeals']); $m++) {                
                $data = ["meal_id" => $request['fridaymeals'][$m], "day_id" => 5];
                $meals[] = $data;                
            }            
        }
        if(isset($request['saturdaymeals'])) {
            for ($m=0; $m < count($request['saturdaymeals']); $m++) {                
                $data = ["meal_id" => $request['saturdaymeals'][$m], "day_id" => 6];
                $meals[] = $data;                
            }            
        }
        if(isset($request['sundaymeals'])) {
            for ($m=0; $m < count($request['sundaymeals']); $m++) {                
                $data = ["meal_id" => $request['sundaymeals'][$m], "day_id" => 7];
                $meals[] = $data;                
            }            
        }
        
        $request->request->add(['meals' => $meals]);
        $request->request->add(['mealPlanCategories' => $mdealDetail_MealCategories]);
        $request->request->remove('mondaymeals');
        $request->request->remove('tuesdaymeals');
        $request->request->remove('wednesdaymeals');
        $request->request->remove('thursdaymeals');
        $request->request->remove('fridaymeals');
        $request->request->remove('saturdaymeals');
        $request->request->remove('sundaymeals');

        $request->request->remove('meal_cat');
        
        $this->authorize('create_mealplan');
        
        if ($request->validated()) {
           $meal = MealPlan::create($request->except('tags', 'weeks', 'images', 'meals', 'mealPlanCategories', '_token'));

            $meal->tags()->attach($request->tags);
            $meal->weeks()->attach($request->weeks);
            $meal->meals()->attach($request->meals);
            $meal->mealPlanCategories()->attach($request->mealPlanCategories);

            if ($request->images && count($request->images) > 0) {
                (new ImageService())->storeMealPlanImages($request->images, $meal);
            }

            clear_cache();

            return redirect()->route('admin.mealplans.index')->with([
                'message' => 'Create meal plan successfully',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Something was wrong, please try again late',
            'alert-type' => 'error'
        ]);
    }

    public function show(MealPlan $mealplan): View
    {
        $this->authorize('show_mealplan');

        return view('backend.show_mealplan.show', compact('mealplan'));
    }

    public function edit(MealPlan $mealplan): View
    {
        $this->authorize('edit_mealplan');

        $meals = Meal::whereStatus(true)->get(['id', 'name']);        
        $meal_category = MealType::whereStatus(true)->get(['id', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        $weeks = Week::get(['id', 'name']);        

        $mealPlanMon = $mealplan->mealPlanCategories()->wherePivot('day_id', 1)->get();
        $mealPlanTur = $mealplan->mealPlanCategories()->wherePivot('day_id', 2)->get();
        $mealPlanWed = $mealplan->mealPlanCategories()->wherePivot('day_id', 3)->get();
        $mealPlanThu = $mealplan->mealPlanCategories()->wherePivot('day_id', 4)->get();
        $mealPlanFri = $mealplan->mealPlanCategories()->wherePivot('day_id', 5)->get();
        $mealPlanSat = $mealplan->mealPlanCategories()->wherePivot('day_id', 6)->get();
        $mealPlanSun = $mealplan->mealPlanCategories()->wherePivot('day_id', 7)->get();
        
        

        return view('backend.mealplans.edit', compact('mealplan', 'meals', 'meal_category', 'tags', 'weeks', 'mealPlanMon', 'mealPlanTur','mealPlanWed','mealPlanThu','mealPlanFri','mealPlanSat','mealPlanSun'));
    }

    public function update(MealPlanRequest $request, MealPlan $mealplan): RedirectResponse
    {
        $request->request->remove('meal_id');
        $request->request->remove('meal_category');
        $mdealDetail_MealCategories = json_decode(json_decode($request['meal_cat']),true);

        $meals=[];
        if(isset($request['mondaymeals'])) {
            for ($m=0; $m < count($request['mondaymeals']); $m++) {                
                $data = ["meal_id" => $request['mondaymeals'][$m], "day_id" => 1];
                $meals[] = $data;                
            }            
        }
        if(isset($request['tuesdaymeals'])) {
            for ($m=0; $m < count($request['tuesdaymeals']); $m++) {                
                $data = ["meal_id" => $request['tuesdaymeals'][$m], "day_id" => 2];
                $meals[] = $data;                
            }            
        }
        if(isset($request['wednesdaymeals'])) {
            for ($m=0; $m < count($request['wednesdaymeals']); $m++) {                
                $data = ["meal_id" => $request['wednesdaymeals'][$m], "day_id" => 3];
                $meals[] = $data;                
            }            
        }
        if(isset($request['thursdaymeals'])) {
            for ($m=0; $m < count($request['thursdaymeals']); $m++) {                
                $data = ["meal_id" => $request['thursdaymeals'][$m], "day_id" => 4];
                $meals[] = $data;                
            }            
        }
        if(isset($request['fridaymeals'])) {
            for ($m=0; $m < count($request['fridaymeals']); $m++) {                
                $data = ["meal_id" => $request['fridaymeals'][$m], "day_id" => 5];
                $meals[] = $data;                
            }            
        }
        if(isset($request['saturdaymeals'])) {
            for ($m=0; $m < count($request['saturdaymeals']); $m++) {                
                $data = ["meal_id" => $request['saturdaymeals'][$m], "day_id" => 6];
                $meals[] = $data;                
            }            
        }
        if(isset($request['sundaymeals'])) {
            for ($m=0; $m < count($request['sundaymeals']); $m++) {                
                $data = ["meal_id" => $request['sundaymeals'][$m], "day_id" => 7];
                $meals[] = $data;                
            }            
        }
        
        $request->request->add(['meals' => $meals]);
        $request->request->add(['mealPlanCategories' => $mdealDetail_MealCategories]);
        $request->request->remove('mondaymeals');
        $request->request->remove('tuesdaymeals');
        $request->request->remove('wednesdaymeals');
        $request->request->remove('thursdaymeals');
        $request->request->remove('fridaymeals');
        $request->request->remove('saturdaymeals');
        $request->request->remove('sundaymeals');

        $request->request->remove('meal_cat');


        $this->authorize('edit_mealplan');

        if ($request->validated()) {
            
            $mealplan->update($request->except('tags', 'weeks', 'images', 'meals', 'mealPlanCategories', '_token'));            
            
            $mealplan->tags()->sync($request->tags);
            $mealplan->weeks()->sync($request->weeks);

            $mealplan->removeMealPlanDetail($mealplan->id);

            $mealplan->meals()->sync($request->meals);
            $mealplan->mealPlanCategories()->sync($request->mealPlanCategories);
 
            $i = $mealplan->media()->count() + 1;

            if ($request->images && count($request->images) > 0) {
                (new ImageService())->storeMealPlanImages($request->images, mealplan, $i);
            }
          
            clear_cache();
            return redirect()->route('admin.mealplans.index')->with([
                'message' => 'Updated meal plan successfully',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Something was wrong, please try again late',
            'alert-type' => 'error'
        ]);
    }

    

    public function destroy(MealPlan $mealplan): RedirectResponse
    {
        $this->authorize('delete_meal');

        if ($mealplan->media->count() > 0) {
            foreach ($mealplan->media as $media) {
                (new ImageService())->unlinkImage($media->file_name, 'mealplan');
                $media->delete();
            }
        }

        $mealplan->delete();

        clear_cache();
        return redirect()->route('admin.mealplans.index')->with([
            'message' => 'Deleted meal plan successfully',
            'alert-type' => 'success'
        ]);
    }

    public function removeImage(Request $request): bool
    {
        $this->authorize('delete_mealplan');

        $meal = MealPlan::findOrFail($request->mealplan_id);
        $image = $meal->media()->whereId($request->image_id)->first();

        (new ImageService())->unlinkImage($image->file_name, 'mealplan');
        $image->delete();
        clear_cache();

        return true;
    }

    public function get_meals(Request $request): JsonResponse
    {        
        $meals = Meal::whereStatus(true)
            ->whereId($request->meal_id)
            ->get(['id', 'name'])
            ->toArray();

        return response()->json($meals);
    }
}
