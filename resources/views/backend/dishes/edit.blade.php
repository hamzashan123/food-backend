@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Edit dish ({{ $dish->name }})
            </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.dishes.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Back to dishes</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.dishes.update', $dish) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="name" class="text-small text-uppercase">{{ __('Dish Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg" name="name"
                                   value="{{ old('name', $dish->name) }}">
                            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="price" class="text-small text-uppercase">{{ __('Price') }}</label>
                            <input id="price" type="number" class="form-control form-control-lg" name="price"
                                   value="{{ old('price', $dish->price) }}">
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
                                        {{ in_array($tag->id, $dish->tags->pluck('id')->toArray()) ? 'selected' : null }}>
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
                            <label for="ingrediants">ingrediants</label>

                            <select name="ingrediants[]" id="ingrediants" class="form-control select2" multiple="multiple">
                                @forelse($ingrediants as $ingrediant)
                                    <option value="{{ $ingrediant->id }}"
                                        {{ in_array($ingrediant->id, $dish->ingrediants->pluck('id')->toArray()) ? 'selected' : null }}>
                                        {{ $ingrediant->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                            @error('ingrediants')<span class="text-danger">{{ $message }}</span>@enderror
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
                                        {{ old('people_types_id', $dish->people_types_id) == $peopleType->id ? 'selected' : null }}>
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
                                <option value="1" {{ old('status', $dish->status) == "Active" ? 'selected' : null }}>
                                    Active
                                </option>
                                <option value="0" {{ old('status', $dish->status) == "Inactive" ? 'selected' : null }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description" class="text-small text-uppercase">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control summernote">
                                {!! old('description', $dish->description) !!}
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
                                {!! old('details', $dish->description) !!}
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
                    @if($dish->media()->count() > 0)
                        @foreach($dish->media as $media)
                            "{{ asset('storage/images/dishes/' . $media->file_name) }}",
                        @endforeach
                    @endif
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                        @if($dish->media()->count() > 0)
                            @foreach($dish->media as $media)
                                {
                                    caption: "{{ $media->file_name }}",
                                    size: "{{ $media->file_size }}",
                                    width: "120px",
                                    url: "{{ route('admin.dishes.remove_image', [
                                                            'image_id' => $media->id,
                                                            'dish_id' => $dish->id,
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
    </script>
@endsection
