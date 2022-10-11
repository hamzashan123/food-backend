@extends('layouts.admin')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Meal Plans
            </h6>
            <div class="ml-auto">
                @can('create_category')
                    <a href="{{ route('admin.mealplans.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                        <span class="text">New Meal Plan</span>
                    </a>
                @endcan
            </div>
        </div>

        @include('partials.backend.filter', ['model' => route('admin.mealplans.index')])

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Tags</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th class="text-center" style="width: 30px;">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($mealplans as $mealplan)
                    <tr>
                        <td>{{ $mealplan->id }}</td>
                        <td>
                            @if($mealplan->firstMedia)
                            <img src="{{ asset('storage/images/mealplans/' . $mealplan->firstMedia->file_name) }}"
                                 width="60" height="60" alt="{{ $mealplan->name }}">
                            @else
                                <img src="{{ asset('img/no-img.png') }}" width="60" height="60" alt="{{ $mealplan->name }}">
                            @endif
                        </td>
                        <td><a href="{{ route('admin.mealplans.show', $mealplan->id) }}">{{ $mealplan->name }}</a></td>                        
                        <td>SR {{ $mealplan->price }}</td>
                        <td class="text-danger">@foreach($mealplan->tags as $tag) <span class="badge badge-danger">{{ $tag->name }}</span>@endforeach</td>
                        <td>{{ $mealplan->status }}</td>
                        <td>{{ $mealplan->created_at }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.mealplans.edit', $mealplan) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);"
                                   onclick="if (confirm('Are you sure to delete this record?'))
                                       {document.getElementById('delete-meal-plan-{{ $mealplan->id }}').submit();} else {return false;}"
                                   class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.mealplans.destroy', $mealplan) }}"
                                  method="POST"
                                  id="delete-meal-plan-{{ $mealplan->id }}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="10">No meal plans found.</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="10">
                        <div class="float-right">
                            {!! $mealplans->appends(request()->all())->links() !!}
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
