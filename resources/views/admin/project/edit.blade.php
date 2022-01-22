@extends('layouts.app')

@section('title')
Update Project Details - Genesis Design
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col s12">
        @if ($project)
            {{ Breadcrumbs::render('project', $project) }}
        @else
            {{ Breadcrumbs::render('project_new') }}
        @endif
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                            <h3 class="capitalize">Edit {{ $projectType->name }} project</h3>
                        </div>
                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')
                        <div class="col s6 row right-align">
                            <div class="col s6"></div>
                            <div class="col s6">
                                <select onchange="setStatus(this)">
                                    <option value="" selected disabled>Set Status</option>
                                    @foreach(\App\Statics\Statics::PROJECT_STATUSES as $projectStatus)
                                        <option value="{{ $projectStatus}}">{{Str::ucfirst($projectStatus)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <form id="lead_form" method="post">
                        @csrf
                        <input type="hidden" name="project_type_id" validate="string" value="{{$projectType->id}}">
                        <input type="hidden" name="project_id" validate="string" value="{{($project)?$project->id:"0"}}">
                        <input type="hidden" name="country" validate="string" value="United States of America">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" class="main_text_fields" name="name" value="{{($project)?$project->name:""}}" validate="string" type="text"/>
                                <label for="name">Project Name</label>
                                <span class="helper-text">Required</span>
                            </div>
                            <div class="input-field col s12">
                                <textarea id="description" class="materialize-textarea" name="description">{{($project)?$project->description:""}}</textarea>
                                <label for="description">Project Description</label>
                                <span class="helper-text">Required</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="row" style="margin-top: 0;">
                                    <div class="input-field col s12">
                                        <input type="text" id="autocomplete-input" class="autocomplete"/>
                                        <label for="autocomplete-input">Search for an address</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="lat" class="main_text_fields" name="latitude" validate="lat_long" value="{{($project)?$project->latitude:""}}" data-id="759" type="number"/>
                                        <label for="lat">Latitude</label>
                                        <span class="helper-text" data-error="Invalid latitude" data-success="">Required</span>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="long" class="main_text_fields" name="longitude" validate="lat_long" value="{{($project)?$project->longitude:""}}" data-id="760" type="number"/>
                                        <label for="long">Longitude</label>
                                        <span class="helper-text" data-error="Invalid longitude" data-success="">Required</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="street" name="street_1" class="main_text_fields" validate="string" value="{{($project)?$project['street_1']:""}}" data-id="11" type="text"/>
                                        <label for="street">Street</label>
                                        <span class="helper-text" data-error="This field is required" data-success="">Required</span>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="city" name="city" class="main_text_fields" validate="string" value="{{($project)?$project->city:""}}" data-id="13" type="text"/>
                                        <label for="city">City</label>
                                        <span class="helper-text" data-error="This field is required" data-success="">Required</span>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 0">
                                    <div class="input-field col s6">
                                        <select name="address" id="state">
                                            <option value="" selected>Select State</option>
                                        </select>
                                        <label for="state">State</label>
                                        <span class="helper-text" data-error="This field is required" data-success="">Required</span>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="zip" name="zip" class="main_text_fields" validate="postcode" value="{{($project)?$project->zip:""}}" data-id="15" type="number"/>
                                        <label for="zip">Zip</label>
                                        <span class="helper-text" data-error="Invalid postcode. Must be 5 digits" data-success="">Required. Must be 5 digits</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 center">
                                <div id="map" style="height: 30em"></div>
                                <span class="helper-text grey-text small" style="font-size: 0.8rem">Click on the map to load Latitude and Longitude</span>
                            </div>
                        </div>
                    </form>

                    @isset($project)
                        <div class="row">
                            <div class="col s12 center">
                                <p class="imperial-red-text">Project Information cannot be updated, contact support if you think there is a mistake in the information you've provided. You can still attach new files.</p>
                            </div>
                        </div>
                    @endisset

                    <div class="row">
                        <h4 class="mt-2">File Attachments</h4>
                        @isset($project)
                            @component('components.list-project-files', ['fileTypes' => $fileTypes, 'project' => $project, 'path' => route('project.file.get')])@endcomponent
                        @endisset
                        <div id="uppies">
                            @foreach($projectType->fileCategories as $cat)
                                <div class="col s12 container" style="height:100%;">
                                    <h5 class="capitalize">{{$cat->name}} </h5>
                                    <span class="helper-text grey-text small" style="font-size: 0.8rem">{{($cat->pivot->is_required)?"Required":""}}</span>
                                    @if($cat->description)
                                        <blockquote style="padding-left: 0.5em">{{$cat->description}}</blockquote>
                                    @endif
                                    <div class="mh-a" id="{{Str::snake($cat->name)}}"></div>
                                    <div class="center">
                                        <span class="helper-text imperial-red-text" id="{{$cat->name}}_error"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col s12 center">
                            @unless($project)
                                <button type="button" class="btn imperial-red-outline-button" onclick="insert()">Create&nbsp;project</button>
                            @endunless
                            @if($project && $project->status !== \App\Statics\Statics::PROJECT_STATUS_ARCHIVED)
                                <form action="{{route('project.archive', $project->id)}}" method="post">
                                    @csrf
                                    <button type="submit" class="btn imperial-red-outline-button m-xxxs">Archive Project</button>
                                </form>
                            @endif
                            @if($project)
                                <a class="btn imperial-red-outline-button m-xxxs" href="{{route('engineer.design.list', $project->id)}}">View Designs</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUREr9JDgVEAJu_yv-LQFvWjfNDMY2NIU&libraries=places"></script>
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script src="{{asset('js/validate/validate.min.js')}}"></script>
    <script type="text/javascript">
        const fileTypes = '{!! $projectType->fileCategories->toJson() !!}';
        const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';
        const post = "{{route('admin.projects.update', $project->id)}}";
        const postUpdate = '';
        const fileInsert = "{{route('admin.projects.file')}}";
        const sunStorage = "{{env('SUN_STORAGE')}}";
        const sunStorageKey = "{{env('SUN_STORAGE_KEY')}}";
        const latOverload = '{{($project)?$project->latitude:""}}';
        const longOverload = '{{($project)?$project->longitude:""}}';
        const stateOverload = '{{($project)?$project->state:""}}';
        const projectIdOverload = '{{($project)?$project->id:""}}';
        const projectStatus = '{{($project)?$project->status:""}}';
        const hideUploadButton = '{{($project)?"no":"yes"}}';
        const redirect = "{{route('admin.home')}}";
    </script>
    <script src="{{asset('js/project/form.js')}}"></script>
    <script>
        function setStatus(a){
            //alert(a.value);
            var status = a.value;
            var id = {{ $project->id }};
            var _token=$('input[name="_token"]').val();
            //alert(id);
            //alert(status);
            $.ajax({
                url:"@if(Auth::user()->role == 'admin'){{ route('admin.projects.set.status') }}@else{{ route('manager.projects.set.status') }}@endif",
                method:"POST",
                data:{statusName: status, projectId: id, _token:_token},
                success:function(data){
                    toastr.success('Status Set Successfully!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    window.location = "@if(Auth::user()->role == 'admin'){{ route('admin.projects.list') }}@else{{ route('manager.projects.list') }}@endif";
                }      
            });
        }
    </script>
@endsection