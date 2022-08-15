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
use App\Models\Ingrediant;
use App\Http\Requests\Backend\DishRequest;


use App\Models\Dish;

class DishController extends Controller
{
    use ImageUploadTrait;

    public function index(): View
    {
        $this->authorize('access_dish');

        $dishes = Dish::with('peopleType', 'tags', 'ingrediants', 'firstMedia')
            ->when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sortBy ?? 'id', \request()->orderBy ?? 'desc')
            ->paginate(\request()->limitBy ?? 10);

        return view('backend.dishes.index', compact('dishes'));
    }

    public function create(): View
    {
        $this->authorize('create_dish');

        $people_types = PeopleType::active()->get(['id', 'name']);
        $tags = Tag::active()->get(['id', 'name']);
        $ingrediants = Ingrediant::active()->get(['id', 'name']);

        return view('backend.dishes.create', compact('tags', 'ingrediants', 'people_types'));
    }

    public function store(DishRequest $request): RedirectResponse
    {
        $this->authorize('create_dish');
        
        if ($request->validated()){
            $dish = Dish::create($request->except('tags', 'ingrediants', 'images', '_token'));            
            $dish->tags()->attach($request->tags);
            $dish->ingrediants()->attach($request->ingrediants);

            if ($request->images && count($request->images) > 0) {
                (new ImageService())->storeDishImages($request->images, $dish);
            }

            clear_cache();

            return redirect()->route('admin.dishes.index')->with([
                'message' => 'Create dish successfully',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Something was wrong, please try again late',
            'alert-type' => 'error'
        ]);
    }

    public function show(Dish $dish): View
    {
        $this->authorize('show_dish');

        return view('backend.dishes.show', compact('dish'));
    }

    public function edit(Dish $dish): View
    {
        $this->authorize('edit_dish');

        $peopleTypes = PeopleType::whereStatus(true)->get(['id', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        $ingrediants = Ingrediant::whereStatus(1)->get(['id', 'name']);

        return view('backend.dishes.edit', compact('dish', 'peopleTypes', 'tags', 'ingrediants'));
    }

    public function update(DishRequest $request, Dish $dish): RedirectResponse
    {
        $this->authorize('edit_dish');

        if ($request->validated()) {
            $dish->update($request->except('tags', 'ingrediants', 'images', '_token'));
            $dish->tags()->sync($request->tags);
            $dish->ingrediants()->sync($request->ingrediants);

            if ($request->images && $dish->media->count() > 0) {
                foreach ($dish->media as $media) {
                    (new ImageService())->unlinkImage($media->file_name, 'dishes');
                    $media->delete();
                }
            }

            $i = $dish->media()->count() + 1;

            if ($request->images && count($request->images) > 0) {
                (new ImageService())->storeDishImages($request->images, $dish, $i);
            }

            clear_cache();
            return redirect()->route('admin.dishes.index')->with([
                'message' => 'Updated dish successfully',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Something was wrong, please try again late',
            'alert-type' => 'error'
        ]);
    }

    public function destroy(Dish $dish): RedirectResponse
    {
        $this->authorize('delete_dish');

        if ($dish->media->count() > 0) {
            foreach ($dish->media as $media) {
                (new ImageService())->unlinkImage($media->file_name, 'dishes');
                $media->delete();
            }
        }

        $dish->delete();

        clear_cache();
        return redirect()->route('admin.dishes.index')->with([
            'message' => 'Deleted dish successfully',
            'alert-type' => 'success'
        ]);
    }

    public function removeImage(Request $request): bool
    {
        $this->authorize('delete_dish');

        $dish = Dish::findOrFail($request->dish_id);
        $image = $dish->media()->whereId($request->image_id)->first();

        (new ImageService())->unlinkImage($image->file_name, 'dishes');
        $image->delete();
        clear_cache();

        return true;
    }
}
