@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Edit Meal ({{ $meal->name }})
            </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.meals.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Back to meals</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.meals.update', $meal) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="name" class="text-small text-uppercase">{{ __('Meal Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg" name="name"
                                   value="{{ old('name', $meal->name) }}">
                            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="price" class="text-small text-uppercase">{{ __('Price') }}</label>
                            <input id="price" type="number" class="form-control form-control-lg" name="price"
                                   value="{{ old('price', $meal->price) }}">
                            @error('price')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <select name="tags[]" id="tags" class="form-control select2" multiple="multiple">
                                @forelse($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        {{ in_array($tag->id, $meal->tags->pluck('id')->toArray()) ? 'selected' : null }}>
                                        {{ $tag->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                            @error('tags')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="meal_types_id">Meal Type</label>
                            <select name="meal_types_id" id="meal_types_id" class="form-control">
                                <option value="">---</option>
                                @forelse($meal_types as $meal_type)
                                    <option value="{{ $meal_type->id }}" 
                                        {{ old('meal_types_id', $meal->meal_types_id) == $meal_type->id ? 'selected' : null }}>
                                        {{ $meal_type->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                            @error('meal_types_id')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="people_types_id">People Type</label>
                            <select name="people_types_id" id="people_types_id" class="form-control">
                                <option value="">---</option>
                                @forelse($peopleTypes as $peopleType)
                                    <option value="{{ $peopleType->id }}"
                                        {{ old('people_types_id', $meal->people_types_id) == $peopleType->id ? 'selected' : null }}>
                                        {{ $peopleType->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                            @error('people_types_id')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ old('status', $meal->status) == "Active" ? 'selected' : null }}>
                                    Active
                                </option>
                                <option value="0" {{ old('status', $meal->status) == "Inactive" ? 'selected' : null }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="background: lightblue; font-size: 18px; font-weight: bold;">
                                Dishes
                            </div>

                            <div class="card-body">
                                <table class="table" id="dishes_table">
                                    <thead>
                                        <tr>
                                            <th>Dish</th>
                                            <th>Day</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="dish0">
                                            <td>
                                                <select name="dishes[]" id="dishes_" class="form-control">
                                                    <option value="">-- choose dish --</option>
                                                    @foreach ($dishes as $dish)
                                                        <option value="{{ $dish->id }}"
                                                            {{ $dish->id == $meal->dishes[0]->id ? 'selected' : null }}>
                                                            {{ $dish->name }} (${{ number_format($dish->price, 2) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="days[]" class="form-control">
                                                    <option value="">-- choose day --</option>
                                                    @foreach ($days as $day)
                                                        <option value="{{ $day->id }}"
                                                        {{ $day->id == $meal->dishes[0]->pivot->day_id ? 'selected' : null }}>                                                        
                                                            {{ $day->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr id="dish1"></tr>
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="add_row" class="btn btn-default pull-left">+ Add Row</button>
                                        <button id='delete_row' class="pull-right btn btn-danger">- Delete Row</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description" class="text-small text-uppercase">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control summernote">
                                {!! old('description', $meal->description) !!}
                            </textarea>
                            @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="details" class="text-small text-uppercase">{{ __('Details') }}</label>
                            <textarea name="details" class="form-control summernote">
                                {!! old('details', $meal->description) !!}
                            </textarea>
                            @error('details')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="images">images</label>
                        <br>
                        <div class="file-loading">
                            <input type="file" name="images[]" id="product-images" class="file-input-overview"
                                   multiple="multiple">
                        </div>
                        @error('images')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {

            // summernote
            $('.summernote').summernote({
                tabSize: 2,
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // select2
            function matchStart(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Skip if there is no 'children' property
                if (typeof data.children === 'undefined') {
                    return null;
                }

                // `data.children` contains the actual options that we are matching against
                var filteredChildren = [];
                $.each(data.children, function (idx, child) {
                    if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                        filteredChildren.push(child);
                    }
                });

                // If we matched any of the timezone group's children, then set the matched children on the group
                // and return the group object
                if (filteredChildren.length) {
                    var modifiedData = $.extend({}, data, true);
                    modifiedData.children = filteredChildren;

                    // You can return modified objects from here
                    // This includes matching the `children` how you want in nested data sets
                    return modifiedData;
                }

                // Return `null` if the term should not be displayed
                return null;
            }

            $("#tags").select2({
                tags: true,
                closeOnSelect: false,
                minimumResultsForSearch: Infinity,
                matcher: matchStart
            });

            $("#ingrediants").select2({
                ingrediants: true,
                closeOnSelect: false,
                minimumResultsForSearch: Infinity,
                matcher: matchStart
            });

            

            // upload images
            $("#product-images").fileinput({
                theme: "fas",
                maxFileCount: 5,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                initialPreview: [
                    @if($meal->media()->count() > 0)
                        @foreach($meal->media as $media)
                            "{{ asset('storage/images/meals/' . $media->file_name) }}",
                        @endforeach
                    @endif
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                        @if($meal->media()->count() > 0)
                            @foreach($meal->media as $media)
                                {
                                    caption: "{{ $media->file_name }}",
                                    size: "{{ $media->file_size }}",
                                    width: "120px",
                                    url: "{{ route('admin.meals.remove_image', [
                                                            'image_id' => $media->id,
                                                            'meal_id' => $meal->id,
                                                            '_token' => csrf_token()
                                                        ]) }}",
                                    key: {{ $media->id }}
                                },
                            @endforeach
                        @endif
                ]
            }).on('filesorted', function (event, params) {
               console.log(params.previewId, params.oldIndex, params.newIndex, params.stack)
            });

            
        })

        $(document).ready(function(){
            let row_number = 0;
            $("#add_row").click(function(e){
            e.preventDefault();
            let new_row_number = row_number - 1;
            $('#dish' + row_number).html($('#dish' + new_row_number).html()).find('td:first-child');
            $('#dishes_table').append('<tr id="dish' + (row_number + 1) + '"></tr>');
            row_number++;
            });

            $("#delete_row").click(function(e){
            e.preventDefault();
            if(row_number > 1){
                $("#dish" + (row_number - 1)).html('');
                row_number--;
            }
            });

            
            
            
            


            @if($meal->dishes()->count() > 0)
                @foreach($meal->dishes as $dish)
                    {
                        let new_row_number = row_number - 1;
                        $('#dish' + row_number).html($('#dish' + new_row_number).html()).find('td:first-child');
                        $('#dishes_table').append('<tr id="dish' + (row_number + 1) + '"></tr>');
                        
                        row_number++;                        
                    }
                @endforeach
            @endif


        });

        


    </script>
@endsection
