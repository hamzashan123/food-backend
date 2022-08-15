@extends('layouts.admin')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Dishes
            </h6>
            <div class="ml-auto">
                @can('create_category')
                    <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                        <span class="text">New Dish</span>
                    </a>
                @endcan
            </div>
        </div>

        @include('partials.backend.filter', ['model' => route('admin.dishes.index')])

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Tags</th>
                    <th>Ingrediants</th>
                    <th>People Type</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th class="text-center" style="width: 30px;">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($dishes as $dish)
                    <tr>
                        <td>{{ $dish->id }}</td>
                        <td>
                            @if($dish->firstMedia)
                            <img src="{{ asset('storage/images/dishes/' . $dish->firstMedia->file_name) }}"
                                 width="60" height="60" alt="{{ $dish->name }}">
                            @else
                                <img src="{{ asset('img/no-img.png') }}" width="60" height="60" alt="{{ $dish->name }}">
                            @endif
                        </td>
                        <td><a href="{{ route('admin.dishes.show', $dish->id) }}">{{ $dish->name }}</a></td>                        
                        <td>SR {{ $dish->price }}</td>
                        <td class="text-danger">{{ $dish->tags->pluck('name')->join(', ') }}</td>
                        <td class="text-danger">{{ $dish->ingrediants->pluck('name')->join(', ') }}</td>
                        <td>{{ $dish->peopleType ? $dish->peopleType->name : NULL }}</td>
                        <td>{{ $dish->status }}</td>
                        <td>{{ $dish->created_at }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.dishes.edit', $dish) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);"
                                   onclick="if (confirm('Are you sure to delete this record?'))
                                       {document.getElementById('delete-dish-{{ $dish->id }}').submit();} else {return false;}"
                                   class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.dishes.destroy', $dish) }}"
                                  method="POST"
                                  id="delete-dish-{{ $dish->id }}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="10">No dishes found.</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="10">
                        <div class="float-right">
                            {!! $dishes->appends(request()->all())->links() !!}
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
