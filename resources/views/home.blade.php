@extends('layouts.app')

@section('title', 'Home')

@section('js')
    <script src="{{asset('js/paginator.js')}}"></script>
    <script type="text/javascript">
        const paginationOptions = {
            currentPage: 1,
            lastPage: 1,
            searchTerm: "",
            filers: null,
            url: '@if(Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN){{route('admin.get')}}@elseif(Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER){{route('project.get')}}@elseif(Auth::user()->role === \App\Statics\Statics::USER_TYPE_MANAGER){{route('manager.get')}}@else{{route('engineer.project.get')}}@endif'
        };

        document.addEventListener('DOMContentLoaded', function () {
            loadThings();
            document.getElementById('project_search').addEventListener("keyup", debouncer(search, 100));
        });

        function loadThings() {
            paginationOptions.filers = [
                {field: "project_type_id", value: M.FormSelect.getInstance(document.getElementById('project_type_select')).getSelectedValues()[0]},
                {field: "status", value: M.FormSelect.getInstance(document.getElementById('project_status_select')).getSelectedValues()[0]}
            ]
            paginate().then(() => {
                let instances = M.Collapsible.init(document.querySelectorAll('.collapsible'), {
                    accordion: true
                });
                instances[0].open(0);
            });
        }

        function filter() {
            M.FormSelect.init(document.querySelectorAll('select'));
            paginationOptions.filers = [
                {field: "project_type_id", value: M.FormSelect.getInstance(document.getElementById('project_type_select')).getSelectedValues()[0]},
                {field: "project_status", value: M.FormSelect.getInstance(document.getElementById('project_status_select')).getSelectedValues()[0]},
                {field: "status", value: M.FormSelect.getInstance(document.getElementById('status_select')).getSelectedValues()[0]}
            ]
            paginate();
        }
    </script>
    <script id="row" type="text/html">
        @{{#each data}}
        <li class="mb-xxs">
            <div class="collapsible-header" style="flex-direction: column">
                <div class="row mb-0 w100">
                    <div class="col s12 center">
                        <div class="valign-wrapper">
                            <div class="col s4 m3 left-align prussian-blue-text bold center">@{{ this.name }}</div>
                            <div class="col s3 m2 left-align prussian-blue-text center capitalize">@{{ this.status }} / @{{ this.project_status }}</div>
                            <div class="col s3 m2 left-align prussian-blue-text center capitalize">@{{ this.type.name }}</div>
                            <div class="col s2 m2 left-align prussian-blue-text center capitalize">@{{ this.created_at }} (UTC)</div>
                            <div class="col s12 m5 right-align prussian-blue-text hide-on-med-and-down center">
                                @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER||Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                                    <a class="btn prussian-blue" href="{{route('project.edit', '')}}/@{{ this.id }}">View / Edit</a>
                                    <a class="btn prussian-blue ml-xxxs" href="{{route('design.list')}}/@{{ this.id  }}">Designs</a>
                                @else
                                    <a class="btn prussian-blue" href="{{route('engineer.project.view', '')}}/@{{ this.id }}">View</a>
                                    <a class="btn prussian-blue ml-xxxs" href="{{route('engineer.design.list')}}/@{{ this.id  }}">Designs</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row hide-on-med-and-up mt-s">
                    <div class="col s12 center">
                        @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER||Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                            <a class="btn prussian-blue" href="{{route('project.edit', '')}}/@{{ this.id }}">View / Edit</a>
                            <a class="btn prussian-blue" href="{{route('design.list')}}">Designs</a>
                        @else
                            <a class="btn prussian-blue" href="{{route('engineer.project.view', '')}}/@{{ this.id }}">View</a>
                            <a class="btn prussian-blue" href="{{route('engineer.design.list')}}">Designs</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="collapsible-body">
                <div class="row">
                    <div class="col s12">
                        <span class="prussian-blue-text"><b>Description</b></span>
                        <blockquote class="mt-xxs">
                            @{{ this.description }}
                        </blockquote>
                    </div>
                    <div class="col s12 m6">
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Street 1: </b></span>
                            @{{ this.street_1 }}
                        </div>
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>City: </b></span>
                            @{{ this.city }}
                        </div>
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Zip: </b></span>
                            @{{ this.zip }}
                        </div>
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Latitude: </b></span>
                            @{{ this.latitude }}
                        </div>
                    </div>
                    <div class="col s12 m6">
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Street 2: </b></span>
                            @{{ this.street-2 }}
                        </div>
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>State: </b></span>
                            @{{ this.state }}
                        </div>
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Country: </b></span>
                            @{{ this.country }}
                        </div>
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Longitude: </b></span>
                            @{{ this.longitude }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6">
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Project Type: </b></span>
                            @{{ this.type.name }}
                        </div>
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Project Status: </b></span>
                            @{{ this.status }}
                        </div>
                    </div>
                    <div class="col s12 m6">
                        <div class="mb-xxxs">
                            <span class="prussian-blue-text"><b>Created On: </b></span>
                            @{{ this.created_at }} (UTC)
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @{{/each}}
    </script>
    <script id="row_empty" type="text/x-handlebars-template">
        <li class="mb-xxs">
            <div class="collapsible-header center imperial-red-text">
                No projects found
            </div>
            <div class="collapsible-body center">Even more nothingness...</div>
        </li>
    </script>
@endsection

@section('content')
    <div class="container-fluid">
        @if (!Auth::user()->email_verified_at)
            <div class="row">
                <div class="col s12">
                    <div class="card-panel imperial-red center white-text">
                        Please verify you email address to enable full functionality. If you did not receive an email from us <a href="{{route('verification.notice')}}">click here</a> to resend the email.
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col s6">
                <h3>Your Projects</h3>
            </div>
            <div class="col s6 right-align">
                @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER||Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                    <a href="{{ route('project.bulk') }}"><button class="btn prussian-blue">Multiple Project</button></a>
                    <a class="btn prussian-blue dropdown-trigger" data-target='dropdown1'>   Single Project</a>
                    <!-- Dropdown Structure -->
                    <ul id='dropdown1' class='dropdown-content prussian-blue-text'>
                        @foreach($projectTypes as $projectType)
                            <li><a href="{{route('project.form', Str::slug($projectType->name))}}">{{Str::ucfirst($projectType->name)}}</a></li>
                            <li class="divider" tabindex="-1"></li>
                        @endforeach
                    </ul>
                @else
                    <!-- <a class="btn imperial-red-outline-button" href="{{route('engineer.project.available')}}">Start&nbsp;a&nbsp;project</a> -->
                @endif
            </div>
        </div><br><br>
        <div class="row mb-0">
            <div class="col s12 m9 center-on-small-only">
                <div class="input-field inline w100-on-small-only" style="min-width: 33%">
                    <input id="project_search" type="text" data-type="projects">
                    <label for="project_search">Search for project(s)...</label>
                </div>
                <div class="input-field inline w100-on-small-only">
                    <select id="project_type_select" onchange="filter()">
                        <option value="all">All</option>
                        @foreach($projectTypes as $projectType)
                            <option value="{{$projectType->id}}">{{Str::ucfirst($projectType->name)}}</option>
                        @endforeach
                    </select>
                    <label for="project_type_select">Project Type</label>
                </div>
                <div class="input-field inline w100-on-small-only">
                    <select id="status_select" onchange="filter()">
                        <option value="all">All</option>
                            @foreach(\App\Statics\Statics::STATUSES as $Status)
                                <option value="{{$Status}}">{{Str::ucfirst($Status)}}</option>
                            @endforeach
                    </select>
                    <label for="status_select"> Status</label>
                </div>
                <div class="input-field inline w100-on-small-only">
                    <select id="project_status_select" onchange="filter()">
                        <option value="all">All</option>
                            @foreach(\App\Statics\Statics::PROJECT_STATUSES as $projectStatus)
                                <option value="{{$projectStatus}}">{{Str::ucfirst($projectStatus)}}</option>
                            @endforeach
                    </select>
                    <label for="project_status_select">Project Status</label>
                </div>
            </div>
            <div class="col s12 m3 center-on-small-only right-on-lg-and-up" style="padding-top: 20px">
                <a class="btn-flat tooltipped waves-effect" data-position="top" data-tooltip="Previous Page" onclick="prevPage()">
                    <i class="fal fa-angle-left"></i>
                </a>
                Page <span id="current_page">1</span> of <span id="last_page">1</span>
                <button class="btn-flat tooltipped waves-effect" data-position="top" data-tooltip="Next Page" onclick="nextPage()">
                    <i class="fal fa-angle-right"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col s12 mt-0 mb-0">
                <div class="card-panel z-depth-0 transparent pb-xxs mb-0 mt-0" style="flex-direction: column; padding: 1rem">
                    <div class="row mb-0 w100">
                        <div class="col s12 center">
                            <div class="col s4 m3 left-align prussian-blue-text bold center">Name</div>
                            <div class="col s3 m1 left-align black-text center">Status</div>
                            <div class="col s3 m2 left-align black-text center">Type</div>
                            <div class="col s2 m2 left-align black-text center">Created at</div>
                            <div class="col m5 left-align center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div id="loading">
                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
                <ul class="collapsible popout" id="pagination_target"></ul>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-on-small-only right-on-lg-and-up">
                <a class="btn-flat tooltipped waves-effect" data-position="top" data-tooltip="Previous Page" onclick="prevPage()">
                    <i class="fal fa-angle-left"></i>
                </a>
                <button class="btn-flat tooltipped waves-effect" data-position="top" data-tooltip="Next Page" onclick="nextPage()">
                    <i class="fal fa-angle-right"></i>
                </button>
            </div>
        </div>
    </div>
    {{--    @TODO: new project FAB on small screens--}}
@endsection
