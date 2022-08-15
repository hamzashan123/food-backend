<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\IngrediantRequest;
use App\Models\Ingrediant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class IngrediantController extends Controller
{
    public function index(): View
    {
        $this->authorize('access_ingrediant');
        $ingrediants = Ingrediant::when(\request()->keyword != null, function ($query) {
                $query->search(\request()->keyword);
            })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sortBy ?? 'id', \request()->orderBy ?? 'desc')
            ->paginate(\request()->limitBy ?? 10);
        return view('backend.ingrediants.index', compact('ingrediants'));
    }

    public function create(): View
    {
        $this->authorize('create_ingrediant');
        return view('backend.ingrediants.create');
    }

    public function store(IngrediantRequest $request): RedirectResponse
    {
        $this->authorize('create_ingrediant');

        Ingrediant::create($request->validated());

        return redirect()->route('admin.ingrediants.index')->with([
            'message' => 'Created successfully',
            'alert-type' => 'success'
        ]);
    }

    public function show(Ingrediant $ingrediant): View
    {
        $this->authorize('show_ingrediant');
        return view('backend.ingrediants.show', compact('ingrediant'));
    }

    public function edit(Ingrediant $ingrediant): View
    {
        $this->authorize('edit_ingrediant');
        return view('backend.ingrediants.edit', compact('ingrediant'));
    }

    public function update(IngrediantRequest $request, Ingrediant $ingrediant): RedirectResponse
    {
        $this->authorize('edit_ingrediant');

        $ingrediant->update($request->validated());

        return redirect()->route('admin.ingrediants.index')->with([
            'message' => 'Updated successfully',
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Ingrediant $ingrediant): RedirectResponse
    {
        $this->authorize('delete_ingrediant');

        $ingrediant->delete();

        return redirect()->route('admin.ingrediants.index')->with([
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ]);
    }
}
