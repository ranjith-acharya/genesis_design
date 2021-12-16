@extends('layouts.app')

@php($types = \App\SystemDesignType::all())

@section('title', "Designs: $project->name")

@section('css')
    <link rel="stylesheet" href="{{asset('css/cards.css')}}">
@endsection

@section('js')
    <script type="text/javascript">
        const projectID = "{{$project->id}}";
        const paginationOptions = {
            currentPage: 1,
            lastPage: 1,
            searchTerm: "",
            filers: null,
            url: '@if(Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER){{route('design.get')}}@else{{route('engineer.design.get')}}@endif?id={{$project->id}}'
        };

        document.addEventListener('DOMContentLoaded', function () {
            Handlebars.registerPartial('aurora', document.getElementById('aurora_row').innerText);
            Handlebars.registerPartial('empty', document.getElementById('fallback_row').innerText);

            Handlebars.registerHelper('whichPartial', function (context, options) {

                if (context.type.name === '{{\App\Statics\Statics::DESIGN_TYPE_AURORA}}')
                    return 'aurora'
                else
                    return 'empty'
            });

            loadThings();
        });

        function loadThings() {
            paginationOptions.filers = [
                {field: "type", value: M.FormSelect.getInstance(document.getElementById('design_type_select')).getSelectedValues()[0]},
                {field: "status", value: M.FormSelect.getInstance(document.getElementById('design_status_select')).getSelectedValues()[0]}
            ]

            paginate().then(() => {
                let instances = M.Collapsible.init(document.querySelectorAll('.collapsible'), {
                    accordion: true
                });
                instances[0].open(0);
            });
        }

        function nextPage() {
            if (paginationOptions.currentPage < paginationOptions.lastPage) {
                paginationOptions.currentPage++;
                paginate();
            }
        }

        function prevPage() {
            if (paginationOptions.currentPage > 1) {
                paginationOptions.currentPage--;
                paginate();
            }
        }

        function filter() {
            M.FormSelect.init(document.querySelectorAll('select'));
            paginationOptions.filers = [
                {field: "system_design_type_id", value: M.FormSelect.getInstance(document.getElementById('design_type_select')).getSelectedValues()[0]},
                {field: "status", value: M.FormSelect.getInstance(document.getElementById('design_status_select')).getSelectedValues()[0]}
            ]
            paginate();
        }

        async function paginate() {

            document.getElementById('loading').style.display = 'block';

            const url = `${paginationOptions.url}&page=${paginationOptions.currentPage}&search=${paginationOptions.searchTerm}&filters=${JSON.stringify(paginationOptions.filers)}`;
            return await fetch(url).then(response => {
                return response.json();
            }).then(json => {

                document.getElementById('loading').style.display = 'none';
                let template = Handlebars.compile(document.getElementById('basic_row').innerText);

                if (json.data.length === 0)
                    document.getElementById('pagination_target').innerHTML = document.getElementById('row_empty').innerText;
                else
                    document.getElementById('pagination_target').innerHTML = template({data: json.data});

                paginationOptions.currentPage = json.current_page;
                paginationOptions.lastPage = json.last_page;
                document.getElementById('current_page').innerText = paginationOptions.currentPage;
                document.getElementById('last_page').innerText = paginationOptions.lastPage;
                return true;
            }).catch(error => {
                console.error(error);
                M.toast({html: "There was an error when fetching list. Please try again.", classes: "imperial-red"});
                return false;
            });
        }
    </script>
    <script id="basic_row" type="text/html">
        @{{#each data}}
        <li class="mb-xxs">
            <div class="collapsible-header" style="flex-direction: column">
                <div class="row mb-0 w100">
                    <div class="col s12 center">
                        <div class="valign-wrapper">
                            <div class="col s4 m2 left-align imperial-red-text bold center capitalize">@{{ this.type.name }}</div>
                            <div class="col s4 m2 left-align center capitalize">@{{ this.status }}</div>
                            <div class="col s1 m1 left-align center">@{{ this.proposals_count }}</div>
                            <div class="col s4 m2 left-align center">$ @{{ this.price }}</div>
                            <div class="col s3 m3 left-align center">@{{ this.created_at }} (UTC)</div>
                            <div class="col s12 m2 right-align hide-on-med-and-down center">
                                @if(Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER)
                                    <a class="btn steel-blue-outline-button" href="{{route('design.view')}}/@{{ this.id  }}">View</a>
                                @else
                                    <a class="btn steel-blue-outline-button" href="{{route('engineer.design.view')}}/@{{ this.id  }}">View</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row hide-on-med-and-up mt-s">
                    <div class="col s12 center">
                        @if(Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER)
                            <a class="btn steel-blue-outline-button" href="{{route('design.view')}}/@{{ this.id  }}">View</a>
                        @else
                            <a class="btn steel-blue-outline-button" href="{{route('engineer.design.view')}}/@{{ this.id  }}">View</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="collapsible-body">
                @{{> (whichPartial this) }}
            </div>
        </li>
        @{{/each}}
    </script>
    <script id="aurora_row" type="text/html">
        <div class="row">
            <div class="col s12">
                <span class="prussian-blue-text"><b>Note</b></span>
                <blockquote class="mt-xxs">
                    @{{ this.fields.notes }}
                </blockquote>
            </div>
            <div class="col s12 m6">
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Annual Usage: </b></span>
                    @{{ this.fields.annual_usage }}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Max Offset: </b></span>
                    @{{ this.fields.max_offset }}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Installation restrictions: </b></span>
                    @{{#if this.fields.installation}}
                    @{{ this.fields.installation }}
                    @{{else}}
                    -
                    @{{/if}}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Payment date: </b></span>
                    @{{#if this.payment_date}}
                    @{{ this.payment_date }}
                    @{{else}}
                    -
                    @{{/if}}
                </div>
            </div>
            <div class="col s12 m6">
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>System Size: </b></span>
                    @{{ this.fields.system_size }}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>HOA?: </b></span>
                    @{{#if this.fields.hoa }}
                    Yes
                    @{{ else }}
                    No
                    @{{/if}}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Remarks: </b></span>
                    @{{ this.fields.remarks }}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Created On: </b></span>
                    @{{ this.created_at }} (UTC)
                </div>
            </div>
        </div>
    </script>
    <script id="fallback_row" type="text/html">
        <div class="row">
            <div class="col s12">
                <blockquote class="mt-xxs">
                    Click <b><i>View</i></b> to view details for this type of design
                </blockquote>
            </div>
        </div>
    </script>
    <script id="row_empty" type="text/x-handlebars-template">
        <li class="mb-xxs">
            <div class="collapsible-header center imperial-red-text">
                No designs found
            </div>
            <div class="collapsible-body center">Even more nothingness...</div>
        </li>
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            {{ Breadcrumbs::render('design_list', $project) }}
            <div class="col s12 m9">
                <h3>Requested Designs</h3>
                <h6>For <span class="imperial-red-text bold ">{{$project->name}}</span></h6>
            </div>
            @if(Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER && $project->status !== \App\Statics\Statics::PROJECT_STATUS_ARCHIVED)
                <div class="col s12 m3 pt-s hide-on-med-and-down right-align mt-xs">
                    <a class="btn btn-large imperial-red-outline-button dropdown-trigger" data-target='dropdown1'>Request&nbsp;a&nbsp;design</a>
                </div>
                <ul id='dropdown1' class='dropdown-content'>
                    @foreach($types as $designType)
                        <li><a href="{{route('design.form', ["type" => Str::slug($designType->name), "project_id" => $project->id])}}">{{Str::ucfirst($designType->name)}}</a></li>
                        <li class="divider" tabindex="-1"></li>
                    @endforeach
                </ul>
            @endif
        </div>
        @if(Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER)
            <div class="row">
                <div class="col s12 m4" style="min-width: 250px">
                    <div class="card info_card steel-blue">
                        <span id="designs">{{$designs}}</span>
                        <i class="fal fa-draw-polygon"></i>
                        <div class="card-action steel-blue darken-1 honeydew-text text-darken-1">
                            Designs Requested
                        </div>
                    </div>
                </div>
                <div class="col s12 m4" style="min-width: 250px">
                    <div class="card info_card amber steel-blue">
                        <span id="proposals_received">{{$change_requests}}</span>
                        <i class="fal fa-exchange-alt"></i>
                        <div class="card-action steel-blue darken-1 honeydew-text">
                            Change Requests
                        </div>
                    </div>
                </div>
                <div class="col s12 m4" style="min-width: 250px">
                    <div class="card info_card amber steel-blue">
                        <span id="proposals_received">{{$proposals}}</span>
                        <i class="fal fa-drafting-compass"></i>
                        <div class="card-action steel-blue darken-1 honeydew-text">
                            Proposals Received
                        </div>
                    </div>
                </div>
                <div class="col s12 m4" style="min-width: 250px">
                    <div class="card info_card steel-blue">
                        <span id="design_costs">${{$cost}}</span>
                        <i class="fal fa-money-bill-wave"></i>
                        <div class="card-action steel-blue darken-1 honeydew-text">
                            Design Costs
                        </div>
                    </div>
                </div>
                <div class="col s12 m4" style="min-width: 250px">
                    <div class="card info_card steel-blue">
                        <span id="design_costs">${{$change_request_cost}}</span>
                        <i class="fal fa-money-bill-wave"></i>
                        <div class="card-action steel-blue darken-1 honeydew-text">
                            Change Request Costs
                        </div>
                    </div>
                </div>
                <div class="col s12 m4" style="min-width: 250px">
                    <div class="card info_card steel-blue">
                        <span id="design_costs">${{$total}}</span>
                        <i class="fal fa-usd-circle"></i>
                        <div class="card-action steel-blue darken-1 honeydew-text">
                            Total
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col s12 m9 center-on-small-only">
                <div class="input-field inline w100-on-small-only">
                    <select id="design_type_select" onchange="filter()">
                        <option value="all">All</option>
                        @foreach($types as $designType)
                            <option value="{{$designType->id}}">{{Str::ucfirst($designType->name)}}</option>
                        @endforeach
                    </select>
                    <label for="design_type_select">Design Type</label>
                </div>
                <div class="input-field inline w100-on-small-only">
                    <select id="design_status_select" onchange="filter()">
                        <option value="all" selected>All</option>
                        @foreach(\App\Statics\Statics::DESIGN_STATUSES as $designStatus)
                            <option value="{{$designStatus}}">{{Str::ucfirst($designStatus)}}</option>
                        @endforeach
                    </select>
                    <label for="design_status_select">Design Status</label>
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
            <div class="col s12">
                <div class="pb-xxs mb-0 mt-0" style="flex-direction: column; padding: 1rem">
                    <div class="row mb-0 w100">
                        <div class="col s12 center">
                            <div class="col s4 m2 left-align imperial-red-text bold center">Design Type</div>
                            <div class="col s4 m2 left-align center">Status</div>
                            <div class="col s1 m1 left-align center">Proposals</div>
                            <div class="col s4 m2 left-align center">Price</div>
                            <div class="col s3 m3 left-align center">Date Created</div>
                            <div class="col m2 left-align center"></div>
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
@endsection
