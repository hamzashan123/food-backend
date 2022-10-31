<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\ImageService;
use App\Traits\ImageUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\PeopleType;
use App\Models\MealType;
use App\Models\Dish;
use App\Models\Day;
use App\Http\Requests\Backend\MealRequest;


use App\Models\Meal;

class MealController extends Controller
{
    use ImageUploadTrait;

    public function index(): View
    {
        $this->authorize('access_meal');

        $meals = Meal::with('peopleType', 'mealTypes', 'tags', 'firstMedia')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sortBy ?? 'id', \request()->orderBy ?? 'desc')
            ->paginate(\request()->limitBy ?? 10);

        return view('backend.meals.index', compact('meals'));
    }

    public function create(): View
    {
        $this->authorize('create_meal');

        $people_types = PeopleType::active()->get(['id', 'name']);
        $mealTypes = MealType::active()->get(['id', 'name']);
        $dishes = Dish::active()->get(['id', 'name']);
        //$days = Day::active()->get(['id', 'short_name', 'name']);
        $tags = Tag::active()->get(['id', 'name']);

        //return view('backend.meals.create', compact('tags', 'meal_types', 'people_types', 'dishes', 'days'));
        return view('backend.meals.create', compact('tags', 'mealTypes', 'people_types', 'dishes'));
    }


    public function store(MealRequest $request): RedirectResponse
    {
        $request->request->remove('dish_id');
        $this->authorize('create_meal');
        
        if ($request->validated()){           
           $meal = Meal::create($request->except('tags', 'mealTypes', 'images', 'dishes','_token'));

            $meal->tags()->attach($request->tags);
            $meal->mealTypes()->attach($request->mealTypes);

            $dishes = $request->input('dishes', []);           

            for ($dish=0; $dish < count($dishes); $dish++) {
                if ($dishes[$dish] != '') {                    
                    $meal->dishes()->attach($dishes[$dish]);
                }
            }

            if ($request->images && count($request->images) > 0) {
                (new ImageService())->storeMealImages($request->images, $meal);
            }

            clear_cache();

            return redirect()->route('admin.meals.index')->with([
                'message' => 'Create meal successfully',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Something was wrong, please try again late',
            'alert-type' => 'error'
        ]);
    }
    public function edit(Meal $meal): View
    {
        $this->authorize('edit_meal');

        $mealTypes = MealType::whereStatus(1)->get(['id', 'name']);
        $peopleTypes = PeopleType::whereStatus(true)->get(['id', 'name']);
        $dishes = Dish::whereStatus(true)->get(['id', 'name']);
        $days = Day::whereStatus(true)->get(['id', 'short_name', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        
        //dd($meal->dishes);
        

        return view('backend.meals.edit', compact('meal', 'mealTypes', 'peopleTypes', 'tags', 'dishes', 'days'));
    }

    public function update(MealRequest $request, Meal $meal): RedirectResponse
    {

       // dd($meal->id);

        $request->request->remove('dish_id');
        $this->authorize('edit_meal');

        if ($request->validated()) {
            $meal->update($request->except('tags', 'mealTypes', 'images', 'dishes','_token'));
            $meal->tags()->sync($request->tags);
            $meal->mealTypes()->sync($request->mealTypes);

            $dishes = $request->input('dishes', []);           
            

            //Remove Previous Records
            $meal->removeMealDetail($meal->id);

            //Insert Update Meal Detail
            for ($dish=0; $dish < count($dishes); $dish++) {
                if ($dishes[$dish] != '') {                    
                    $meal->dishes()->attach($dishes[$dish]);
                }
            }

            $i = $meal->media()->count() + 1;

            if ($request->images && count($request->images) > 0) {
                (new ImageService())->storeMealImages($request->images, $meal, $i);
            }

            clear_cache();
            return redirect()->route('admin.meals.index')->with([
                'message' => 'Updated meal successfully',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Something was wrong, please try again late',
            'alert-type' => 'error'
        ]);
    }

    public function destroy(Meal $meal): RedirectResponse
    {
        $this->authorize('delete_meal');

        if ($meal->media->count() > 0) {
            foreach ($meal->media as $media) {
                (new ImageService())->unlinkImage($media->file_name, 'meals');
                $media->delete();
            }
        }

        $meal->delete();

        clear_cache();
        return redirect()->route('admin.meals.index')->with([
            'message' => 'Deleted meal successfully',
            'alert-type' => 'success'
        ]);
    }

    public function removeImage(Request $request): bool
    {
        $this->authorize('delete_meal');

        $meal = Meal::findOrFail($request->meal_id);
        $image = $meal->media()->whereId($request->image_id)->first();

        (new ImageService())->unlinkImage($image->file_name, 'meals');
        $image->delete();
        clear_cache();

        return true;
    }
    
}
