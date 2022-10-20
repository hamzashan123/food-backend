@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Create Meal
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
            <form action="{{ route('admin.meals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="name" class="text-small text-uppercase">{{ __('Meal Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg" name="name"
                                   value="{{ old('name') }}">
                            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="price" class="text-small text-uppercase">{{ __('Price') }}</label>
                            <input id="price" type="number" class="form-control form-control-lg" name="price"
                                   value="{{ old('price') }}" >
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
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('tags')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>    
                    <div class="col-6">
                        <div class="form-group">
                            <label for="mealTypes">Meal Category</label>
                            <select name="mealTypes[]" id="mealTypes" class="form-control select2" multiple="multiple">
                                @forelse($mealTypes as $mealType)
                                    <option value="{{ $mealType->id }}">{{ $mealType->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('mealTypes')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="people_types_id">People Type</label>
                            <select name="people_types_id" id="people_types_id" class="form-control">
                                <option value="">---</option>
                                @forelse($people_types as $people_type)
                                    <option value="{{ $people_type->id }}" {{ old('people_types_id') == $people_type->id ? 'selected' : null }}>
                                        {{ $people_type->name }}
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
                                <option value="">---</option>
                                <option value="1" {{ old('status') == "1" ? 'selected' : null }}>Active</option>
                                <option value="0" {{ old('status') == "0" ? 'selected' : null }}>Inactive</option>
                            </select>
                            @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="background: #224abe; color: white; font-size: 18px; ">
                                Dish Menu
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="dish_id">Select Dish</label>
                                            <select name="dish_id" id="dish_id" class="form-control">
                                                <option value="">---</option>
                                                @forelse($dishes as $dish)
                                                    <option value="{{ $dish->id }}">
                                                        {{ $dish->name }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('dish_id')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>

                                    <div class="col-2 btn btn-primary" id="btnAddDish" style="height:50%; margin-top:32px; margin-left: 12px;">                                            
                                        <span class="icon text-white-50">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <span class="text">Add Dish</span>
                                        </a>                                      
                                    </div>                                    
                                    
                                    
                                   

                                    
                                </div>                                
                                
                            </div>

                            <div class="row" style="margin-bottom: 10px;">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card" style="margin-left:5px; margin-right:5px; padding-left: 10px; padding-right: 10px;">                                                        
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th style="display:none;">ID</th>
                                                            <th>Image</th>
                                                            <th>Name</th>                                                                        
                                                            <th>Price</th>
                                                            <th>Tags</th>
                                                            <th>People Type</th>                                                                                                                                  
                                                            <th class="text-center" style="width: 30px;">Action</th>
                                                        </tr>
                                                        </thead>
                                                            <tbody id="tbody_dish">
                                                                
                                                            </tbody>
                                                        <tfoot>                                                                    
                                                        </tfoot>
                                                    </table>
                                                </div>                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                     
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description" class="text-small text-uppercase">{{ __('Description') }}</label>
                            <textarea name="description" rows="3" class="form-control summernote">{!! old('description') !!}</textarea>
                            @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="details" class="text-small text-uppercase">{{ __('Details') }}</label>
                            <textarea name="details" rows="3" class="form-control summernote">{!! old('details') !!}</textarea>
                            @error('details')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="images">images</label>
                        <br>
                        <div class="file-loading">
                            <input type="file" name="images[]" id="product-images" class="note-icon-picture" multiple="multiple">
                        </div>
                        @error('images')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create') }}
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
            })

            // upload images
            $("#product-images").fileinput({
                theme: "fas",
                maxFileCount: 5,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
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

            $("#mealTypes").select2({
                mealTypes: true,
                closeOnSelect: false,
                minimumResultsForSearch: Infinity,
                matcher: matchStart
            });  
        })

        $('#btnAddDish').on('click', function(e) {
            
                    


            let dishId = $('#dish_id').val();                
            $.get("{{ route('admin.cities.get_dishes') }}", { dish_id: dishId }, function (data) {
               
                var data_ = data[0];               
                 
                var imageUrl = "{{ asset('storage/images/dishes/') }}" + "/" + data_["first_media"]["file_name"];
                var tags = "";

                $.each(data_["tags"], function (index, value) {
                    //tags+= value["name"] + ", ";
                    tags+= ' <span class="badge badge-danger">' + value["name"] + '</span>';
                });

                tags = tags.slice(0,-2);                
                //var idtd_ = '<td style="display:none;">' + data_["id"] + '</td>'
                var idtd_ = '<td style="display:none;"> <input type="number" name="dishes[]" value="' + data_["id"] + '" class="form-control"</td>'
                var imagetd_ = '<td><img src=' + imageUrl + ' height="100" style="object-fit: contain;" alt="' + data_["name"] + '"></td>';
                var nametd_ = '<td>' + data_["name"] + '</td>'
                var pricetd_ = '<td>' + data_["price"] + '</td>'
                var tagstd_ = '<td class="text-danger">' + tags + '</td>'
                var peopletypetd_ = '<td>' + data_["people_type"]["name"] + '</td>'
                
                var actiontd_ = '<td><a class="btn btn-sm btn-danger" onClick="deleteDish(tr_id_' + data_["id"] + ')" style="background: white; color: red;"><i class="fa fa-trash"></i></a></td>';
                var tr = '<tr id="tr_id_' + data_["id"] + '">' + idtd_ + imagetd_ + nametd_ +  pricetd_ + tagstd_ + peopletypetd_ + actiontd_ + '</tr>';
                
                
                if(getHTML("tr_id_" + data_["id"])) {                        
                    $("#tbody_dish").append(tr);
                }  

            }, "json");

        })

        function deleteDish(rowid) {
            
            $("#" + rowid.id).remove();
        }

        function getHTML(trid) {
            
            var returnVal = false;

            if($("#" + trid).length == 0) {
                var returnVal = true;
            }

            return returnVal;
        }

/*
        $(document).ready(function(){
            let row_number = 1;
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
        });
*/

    </script>
@endsection
