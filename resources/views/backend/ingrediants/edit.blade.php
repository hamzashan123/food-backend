@extends('layouts.admin')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Edit ingrediant: ({{ $ingrediant->name }})
            </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.ingrediants.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Back to ingrediants</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ingrediants.update', $ingrediant) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" id="name" type="text" name="name" value="{{ old('name', $ingrediant->name) }}">
                            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ old('status', $ingrediant->status) == "Active" ? 'selected' : null }}>Active</option>
                                <option value="0" {{ old('status', $ingrediant->status) == "Inactive" ? 'selected' : null }}>Inactive</option>
                            </select>
                            @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group pt-4">
                    <button class="btn btn-primary" type="submit" name="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
