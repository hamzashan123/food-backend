@extends('layouts.admin')
<style>
    td img {
    object-fit: cover;
    border-radius: 30px;
    }
</style>
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Meals
            </h6>
            <div class="ml-auto">
                @can('create_category')
                    <a href="{{ route('admin.meals.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                        <span class="text">New Meal</span>
                    </a>
                @endcan
            </div>
        </div>

        @include('partials.backend.filter', ['model' => route('admin.meals.index')])

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Tags</th>
                    <th>People Type</th>
                    <th>Meal Types</th>                    
                    <th>Status</th>
                    <th>Created at</th>
                    <th class="text-center" style="width: 30px;">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($meals as $meal)
                    <tr>
                        <td>{{ $meal->id }}</td>
                        <td>
                            @if($meal->firstMedia)
                            <img src="{{ asset('storage/images/meals/' . $meal->firstMedia->file_name) }}"
                                 width="60" height="60" alt="{{ $meal->name }}">
                            @else
                                <img src="{{ asset('img/no-img.png') }}" width="60" height="60" alt="{{ $meal->name }}">
                            @endif
                        </td>
                        <td><a href="{{ route('admin.meals.show', $meal->id) }}">{{ $meal->name }}</a></td>                        
                        <td>$ {{ $meal->price }}</td>
                        <td class="text-danger">@foreach($meal->tags as $tag) <span class="badge badge-danger">{{ $tag->name }}</span>@endforeach</td>
                        <td>{{ $meal->peopleType ? $meal->peopleType->name : NULL }}</td>
                        <td class="text-danger">@foreach($meal->mealTypes as $mealType) <span class="badge badge-danger">{{ $mealType->name }}</span>@endforeach</td>
                        <td>{{ $meal->status }}</td>
                        <td>{{ $meal->created_at }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.meals.edit', $meal) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);"
                                   onclick="if (confirm('Are you sure to delete this record?'))
                                       {document.getElementById('delete-meal-{{ $meal->id }}').submit();} else {return false;}"
                                   class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.meals.destroy', $meal) }}"
                                  method="POST"
                                  id="delete-meal-{{ $meal->id }}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="10">No meals found.</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="10">
                        <div class="float-right">
                            {!! $meals->appends(request()->all())->links() !!}
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
