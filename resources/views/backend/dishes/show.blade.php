@extends('layouts.admin')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ $dish->name }}
            </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.dishes.index') }}" class="btn btn-primary">
                    <span class="text">Back to dishes</span>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>Dish Name</th>
                    <td>{{ $dish->name }}</td>
                    <th>Price</th>
                    <td>{{ $dish->price }}</td>
                </tr>
                <tr>
                    <td>People Type</td>
                    <td>{{ $dish->peopleType->name }}</td>
                    <th>Status</th>
                    <td>{{ $dish->status }}</td>
                </tr>                
                <tr>
                    <td>Created At</td>
                    <td>{{ $dish->created_at ? $dish->created_at->format('Y-m-d') : "Undefined" }}</td>
                    <td>Updated At</td>
                    <td>{{ $dish->updated_at ? $dish->updated_at->format('Y-m-d') : "Undefined" }}</td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td colspan="3">{{ $dish->description }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
