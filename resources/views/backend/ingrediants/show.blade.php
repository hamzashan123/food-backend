@extends('layouts.admin')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ $ingrediant->name }}
            </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.ingrediants.index') }}" class="btn btn-primary">
                    <span class="text">Back to ingrediants</span>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created at</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $ingrediant->name }}</td>
                    <td>{{ $ingrediant->status }}</td>
                    <td>{{ $ingrediant->created_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
