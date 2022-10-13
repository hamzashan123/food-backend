@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('backend/css/checkbox.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/tabstyles.css') }}">

@endsection
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                Create Meal Plan
            </h6>
            <div class="ml-auto">
                <a href="{{ route('admin.mealplans.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Back to Meal Plans</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.mealplans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="name" class="text-small text-uppercase">{{ __('Meal Plan Name') }}</label>
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
                <div class="col-6">
                        <div class="form-group">
                            <label for="weeks">Weeks</label>
                            <select name="weeks[]" id="weeks" class="form-control select2" multiple="multiple">                                    
                                    @forelse($weeks as $week)
                                    <option value="{{ $week->id }}">{{ $week->name }}</option>
                                    @empty
                                    @endforelse
                            </select>
                            @error('weeks')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                </div>

                <input type="hidden" id="meal_cat" name="meal_cat" class="form-control">
                </div>
                

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="background: #224abe; color: white; font-size: 18px; ">
                                Meal Menu
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="meal_id">Meals</label>
                                            <select name="meal_id" id="meal_id" class="form-control">
                                                <option value="">---</option>
                                                @forelse($meals as $meal)
                                                    <option value="{{ $meal->id }}">
                                                        {{ $meal->name }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('meal_id')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>


                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="day_id">Days</label>
                                            <div class="weekDays-selector">
                                            <input type="checkbox" id="weekday-mon" class="weekday" />
                                            <label for="weekday-mon">MON</label>
                                            <input type="checkbox" id="weekday-tue" class="weekday" />
                                            <label for="weekday-tue">TUE</label>
                                            <input type="checkbox" id="weekday-wed" class="weekday" />
                                            <label for="weekday-wed">WED</label>
                                            <input type="checkbox" id="weekday-thu" class="weekday" />
                                            <label for="weekday-thu">THU</label>
                                            <input type="checkbox" id="weekday-fri" class="weekday" />
                                            <label for="weekday-fri">FRI</label>
                                            <input type="checkbox" id="weekday-sat" class="weekday" />
                                            <label for="weekday-sat">SAT</label>
                                            <input type="checkbox" id="weekday-sun" class="weekday" />
                                            <label for="weekday-sun">SUN</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="meal_category">Meal Category</label>
                                            <select name="meal_category[]" id="meal_category" class="form-control select2" multiple="multiple">
                                                @forelse($meal_category as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('meal_category')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-2 btn btn-primary" id="btnAddMeal" style="height:50%; margin-top:32px; margin-left: 12px;">                                       
                                            <span class="icon text-white-50">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                            <span class="text">Add Meal</span>
                                        </a>
                                    </div>                           
                                </div>
                                
                            </div>

                            <div class="row" style="margin-bottom: 10px;">                               
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" href="#monday" role="tab" aria-controls="monday" aria-selected="true">Mon Day</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"  href="#tuesday" role="tab" aria-controls="tuesday" aria-selected="false">Tues Day</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#wednesday" role="tab" aria-controls="wednesday" aria-selected="false">Wednes Day</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#thursday" role="tab" aria-controls="thursday" aria-selected="false">Thurs Day</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#friday" role="tab" aria-controls="friday" aria-selected="false">Fri Day</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#saturday" role="tab" aria-controls="saturday" aria-selected="false">Satur Day</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#sunday" role="tab" aria-controls="sunday" aria-selected="false">Sun Day</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">                                               
                                            
                                                <div class="tab-content mt-3">
                                                        <div class="tab-pane active" id="monday" role="tabpanel">
                                                            <h4 class="card-title">Monday Meals</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Image</th>
                                                                        <th>Name</th>                                                                        
                                                                        <th>Tags</th>                   
                                                                        <th>People Type</th>
                                                                        <th>Meal Category</th>                                                                        
                                                                        <th class="text-center" style="width: 30px;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                        <tbody id="tbody_mon">
                                                                            
                                                                        </tbody>
                                                                    <tfoot>                                                                    
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="tab-pane" id="tuesday" role="tabpanel" aria-labelledby="tuesday-tab">  
                                                            <h4 class="card-title">Tuesday Meals</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Image</th>
                                                                        <th>Name</th>                                                                        
                                                                        <th>Tags</th>                   
                                                                        <th>People Type</th>
                                                                        <th>Meal Category</th>                                                                        
                                                                        <th class="text-center" style="width: 30px;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                        <tbody id="tbody_tue">
                                                                            
                                                                        </tbody>
                                                                    <tfoot>                                                                    
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="tab-pane" id="wednesday" role="tabpanel" aria-labelledby="wednesday-tab">  
                                                            <h4 class="card-title">Wednesday Meals</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Image</th>
                                                                        <th>Name</th>                                                                        
                                                                        <th>Tags</th>                   
                                                                        <th>People Type</th>
                                                                        <th>Meal Category</th>                                                                        
                                                                        <th class="text-center" style="width: 30px;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                        <tbody id="tbody_wed">
                                                                            
                                                                        </tbody>
                                                                    <tfoot>                                                                    
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane" id="thursday" role="tabpanel" aria-labelledby="thursday-tab">  
                                                            <h4 class="card-title">Thursday Meals</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Image</th>
                                                                        <th>Name</th>                                                                        
                                                                        <th>Tags</th>                   
                                                                        <th>People Type</th>
                                                                        <th>Meal Category</th>                                                                        
                                                                        <th class="text-center" style="width: 30px;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                        <tbody id="tbody_thu">
                                                                            
                                                                        </tbody>
                                                                    <tfoot>                                                                    
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane" id="friday" role="tabpanel" aria-labelledby="friday-tab">  
                                                            <h4 class="card-title">Friday Meals</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Image</th>
                                                                        <th>Name</th>                                                                        
                                                                        <th>Tags</th>                   
                                                                        <th>People Type</th>
                                                                        <th>Meal Category</th>                                                                        
                                                                        <th class="text-center" style="width: 30px;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                        <tbody id="tbody_fri">
                                                                            
                                                                        </tbody>
                                                                    <tfoot>                                                                    
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane" id="saturday" role="tabpanel" aria-labelledby="saturday-tab">  
                                                            <h4 class="card-title">Saturday Meals</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Image</th>
                                                                        <th>Name</th>                                                                        
                                                                        <th>Tags</th>                   
                                                                        <th>People Type</th>
                                                                        <th>Meal Category</th>                                                                        
                                                                        <th class="text-center" style="width: 30px;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                        <tbody id="tbody_sat">
                                                                            
                                                                        </tbody>
                                                                    <tfoot>                                                                    
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane" id="sunday" role="tabpanel" aria-labelledby="sunday-tab">  
                                                            <h4 class="card-title">Sunday Meals</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Image</th>
                                                                        <th>Name</th>                                                                        
                                                                        <th>Tags</th>                   
                                                                        <th>People Type</th>
                                                                        <th>Meal Category</th>                                                                        
                                                                        <th class="text-center" style="width: 30px;">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                        <tbody id="tbody_sun">
                                                                            
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
/*
            $("#mealTypes").select2({
                tags: true,
                closeOnSelect: false,
                minimumResultsForSearch: Infinity,
                matcher: matchStart
            });
*/
            $("#meal_category").select2({
                meal_category: true,
                closeOnSelect: false,
                minimumResultsForSearch: Infinity,
                matcher: matchStart
            });

            $("#weeks").select2({
                weeks: true,
                closeOnSelect: false,
                minimumResultsForSearch: Infinity,
                matcher: matchStart
            });
        })

        $('#bologna-list a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })

        $('#btnAddMeal').on('click', function(e) {
            
            debugger

            //var categoryId = $('#meal_category').val();
            let mealId = $('#meal_id').val(); 

            var meal_category = $('#meal_category').select2('data');
            

            if(meal_category.length > 0) {
                AddMeal(mealId, meal_category);
            }            
        })

        var weekmeals_cate = [];

        function AddMealCategoryInJSON(mealid, dayid, categories) {
            $.each(categories, function (index, value) {
                    weekmeals_cate.push({
                                        'meal_id' : mealid, 
                                        'day_id' : dayid, 
                                        'meal_category' : (value["id"] == "1" ? 1 : value["id"] == "2" ? 2 : value["id"] == "3" ? 3 : value["id"] == "4" ? 4 : 0) 
                                    });
                });            

            const myJSON = JSON.stringify(weekmeals_cate);
            $("[name=meal_cat]").val(JSON.stringify(myJSON));
        }

        function AddMeal(mealId, mealCategory) {

            var monday_val = $('#weekday-mon').prop('checked');
            var tuesday_val = $('#weekday-tue').prop('checked');
            var wednesday_val = $('#weekday-wed').prop('checked');
            var thursday_val = $('#weekday-thu').prop('checked');
            var friday_val = $('#weekday-fri').prop('checked');
            var saturday_val = $('#weekday-sat').prop('checked');
            var sunday_val = $('#weekday-sun').prop('checked');


                           
            $.get("{{ route('admin.cities.get_cities') }}", { meal_id: mealId }, function (data) {
               
                var data_ = data[0];               
                 
                var imageUrl = "{{ asset('storage/images/meals/') }}" + "/" + data_["first_media"]["file_name"];
                var tags = "";
                var mealTypes = "";
                var categoryIds = "";

                $.each(data_["tags"], function (index, value) {
                    //tags+= value["name"] + ", ";
                    tags+= ' <span class="badge badge-danger">' + value["name"] + '</span>';
                });

                $.each(mealCategory, function (index, value) {
                    debugger
                    mealTypes+= ' <span class="badge badge-danger">' + value["text"] + '</span>';
                    categoryIds += value["id"] + ", ";                    
                });
                
                tags = tags.slice(0,-2);
                //mealTypes = mealTypes.slice(0,-2);                
                categoryIds = categoryIds.slice(0,-2);
                
                var idtd_ = '<td style="display:none;"> <input type="number" name="weekmeals[]" value="' + data_["id"] + '" class="form-control"</td>'                
                var imagetd_ = '<td><img src=' + imageUrl + ' height="100" style="object-fit: contain;" alt="' + data_["name"] + '"></td>';
                var nametd_ = '<td>' + data_["name"] + '</td>'
                var tagstd_ = '<td class="text-danger">' + tags + '</td>'
                var peopletypetd_ = '<td>' + data_["people_type"]["name"] + '</td>'
                var mealtypetd_ = '<td class="text-danger">' + mealTypes + '</td>'
                var actiontd_ = '<td><a class="btn btn-sm btn-danger" onClick="deleteMeal(tr_id_' + data_["id"] + ')" style="background: white; color: red;"><i class="fa fa-trash"></i></a></td>';
                var tr = '<tr id="tr_id_' + data_["id"] + '">' + idtd_ + imagetd_ + nametd_ + tagstd_ + peopletypetd_ + mealtypetd_ + actiontd_ + '</tr>';                
                
                
                if(monday_val) {
                    if(getHTML("tr_mon_" + data_["id"])) {
                        var trVal = tr.replace("tr_id","tr_mon");
                        trVal = trVal.replace("tr_id","tr_mon");
                        trVal = trVal.replace("weekmeals[]","mondaymeals[]");                        
                        $("#tbody_mon").append(trVal);

                        AddMealCategoryInJSON(data_["id"], 1, mealCategory);
                    }                    
                }
                if(tuesday_val) {
                    if(getHTML("tr_tue_" + data_["id"])) {
                        var trVal = tr.replace("tr_id","tr_tue");
                        trVal = trVal.replace("tr_id","tr_tue");
                        trVal = trVal.replace("weekmeals[]","tuesdaymeals[]");                        
                        $("#tbody_tue").append(trVal);

                        AddMealCategoryInJSON(data_["id"], 2, mealCategory);
                    }                    
                }
                if(wednesday_val) {
                    if(getHTML("tr_wed_" + data_["id"])) {
                        var trVal = tr.replace("tr_id","tr_wed");
                        trVal = trVal.replace("tr_id","tr_wed");
                        trVal = trVal.replace("weekmeals[]","wednesdaymeals[]");                        
                        $("#tbody_wed").append(trVal);

                        AddMealCategoryInJSON(data_["id"], 3, mealCategory);
                    }
                }
                if(thursday_val) {
                    if(getHTML("tr_thu_" + data_["id"])) {
                        var trVal = tr.replace("tr_id","tr_thu");
                        trVal = trVal.replace("tr_id","tr_thu");
                        trVal = trVal.replace("weekmeals[]","thursdaymeals[]");                        
                        $("#tbody_thu").append(trVal);

                        AddMealCategoryInJSON(data_["id"], 4, mealCategory);
                    }                    
                }
                if(friday_val) {
                    if(getHTML("tr_fri_" + data_["id"])) {
                        var trVal = tr.replace("tr_id","tr_fri");
                        trVal = trVal.replace("tr_id","tr_fri");
                        trVal = trVal.replace("weekmeals[]","fridaymeals[]");                        
                        $("#tbody_fri").append(trVal);

                        AddMealCategoryInJSON(data_["id"], 5, mealCategory);
                    }                    
                }
                if(saturday_val) {
                    if(getHTML("tr_sat_" + data_["id"])) {
                        var trVal = tr.replace("tr_id","tr_sat");
                        trVal = trVal.replace("tr_id","tr_sat");
                        trVal = trVal.replace("weekmeals[]","saturdaymeals[]");                        
                        $("#tbody_sat").append(trVal);

                        AddMealCategoryInJSON(data_["id"], 6, mealCategory);
                    }
                }
                if(sunday_val) {
                    if(getHTML("tr_sun_" + data_["id"])) {
                        var trVal = tr.replace("tr_id","tr_sun");
                        trVal = trVal.replace("tr_id","tr_sun");
                        trVal = trVal.replace("weekmeals[]","sundaymeals[]");                        
                        $("#tbody_sun").append(trVal);

                        AddMealCategoryInJSON(data_["id"], 7, mealCategory);
                    }                    
                }

            }, "json");
        }

        function deleteMeal(rowid) {
            /*
            var 
            
            const indexOfObject = weekmeals_cate.findIndex(object => {
            return object.meal_id === id;
            });

            tr_mon
            */
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
