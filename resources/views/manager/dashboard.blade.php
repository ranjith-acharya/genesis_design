@extends('layouts.app')

@section('title')
Manager Home - Genesis Design
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col l3 m6 s12">
            <div class="card danger-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{ $customerCount }}</h2>
                            <h6 class="white-text op-5 light-blue-text">Customers</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="ti-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l3 m6 s12">
            <div class="card info-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{ $engineerCount }}</h2>
                            <h6 class="white-text op-5">Engineers</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="fal fa-user-hard-hat"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l3 m6 s12">
            <div class="card success-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{$projectsActive }}</h2>
                            <h6 class="white-text op-5 text-darken-2">Active Projects</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="material-icons">equalizer</i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l3 m6 s12">
            <div class="card warning-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{ $projectsPending }}</h2>
                            <h6 class="white-text op-5">Pending Projects</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="ti-info-alt"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 l8">
            <div class="card">
                <div class="card-content">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="card-title">Yearly Sales</h5>
                        </div>
                        <div class="ml-auto">
                            <ul class="list-inline font-12 dl m-r-10">
                                <li class="cyan-text"><i class="fa fa-circle"></i> Earnings</li>
                                <li class="blue-text text-accent-4"><i class="fa fa-circle"></i> Sales</li>
                            </ul>
                        </div>
                    </div>
                    <div class="p-t-20">
                        <div class="row">
                            <div class="col s12">
                                <div id="sales" style="height: 332px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 l4">
            <div class="card card-hover">
                <div class="card-content">
                    <div class="d-flex align-items-center">
                        <div class="m-r-20">
                            <h1 class=""><i class="ti-light-bulb"></i></h1></div>
                        <div>
                            <h3 class="card-title">Sales Analytics</h3>
                            <h6 class="card-subtitle">March  2017</h6> </div>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col s6">
                            <h3 class="font-light m-t-10"><sup><small><i class="ti-arrow-up"></i></small></sup>35487</h3>
                        </div>
                        <div class="col s6 right-align">
                            <div class="p-t-10 p-b-10">
                                <div class="spark-count" style="height:65px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-hover">
                <div class="card-content">
                    <div class="d-flex align-items-center">
                        <div class="m-r-20">
                            <h1 class=""><i class="ti-pie-chart"></i></h1></div>
                        <div>
                            <h3 class="card-title">Bandwidth usage</h3>
                            <h6 class="card-subtitle">March  2017</h6> 
                        </div>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col s6">
                            <h3 class="font-light m-t-10">50 GB</h3>
                        </div>
                        <div class="col s6 p-t-10 p-b-20 right-align">
                            <div class="p-t-10 p-b-10 m-r-20">
                                <div class="spark-count2" style="height:65px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                            <h3 class="card-title">Projects Summary - (Monthly)</h3>
                        </div>
                        <div class="col s6 right-align">
                            <a class='dropdown-trigger btn btn-small green' href='#' data-target='exportBtn'>EXPORT</a>
                            <ul id='exportBtn' class='dropdown-content'>
                                <li><a type="submit" id="exportExcel">EXCEL</a></li>
                                <li><a type="submit" id="exportPdf">PDF</a></li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <form method="get" id="exportForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col s3">
                                            <div class="input-field">
                                                <input type="date" name="from_date" id="from_date">
                                            </div>
                                        </div>
                                        <div class="col s3">
                                            <div class="input-field">
                                                <input type="date" name="to_date" id="to_date">
                                            </div>
                                        </div>
                                        <!-- <div class="col s3">
                                            <div class="input-field col s12">
                                                <select id="status" name="status">
                                                    <option value="" disabled selected>Select Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="archived">Archived</option>
                                                    <option value="pending">Pending</option>
                                                </select>
                                                <label>Status</label>
                                            </div>
                                        </div> -->
                                        <div class="col s3" style="margin-top:2%;">
                                            <button type="button" id="exportSearch" class="btn btn-flat green white-text"><i class="material-icons left">search</i>Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table id="" class="responsive-table display black-text">
                        <thead>
                            <tr class="black-text">
                                <th>Project Name</th>
                                <th>Customer City</th>
                                <th>Customer State</th>
                                <th>Assigned To</th>
                                <th>Created Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableContent">
                            @foreach($projectsMonthly as $monthly)
                                <tr>
                                    <td>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $monthly->id) }}@else{{ route('manager.projects.edit', $monthly->id) }}@endif">
                                            {{ $monthly->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $monthly->city }}
                                    </td>
                                    <td>
                                        {{ $monthly->state }}
                                    </td>
                                    <td>
                                        @if($monthly->engineer['first_name'] == "")
                                            <span class="red-text darken-1">Not yet assigned</span>
                                        @else
                                            <a href="@if(Auth::user()->role == 'admin'){{ route('admin.engineer.edit', $monthly->engineer->id) }}@else{{ route('manager.engineer.edit', $monthly->engineer->id) }}@endif">
                                                {{ $monthly->engineer['first_name'] }} {{ $monthly->engineer['last_name'] }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($monthly->engineer['first_name'] == "")
                                            <span class="red-text darken-1">Not yet assigned</span>
                                        @else
                                            {{ \Carbon\Carbon::parse( $monthly->updated_at)->format('d M, Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($monthly->status == 'pending')
                                            <span class="label label-red capitalize">{{ $monthly->status }}</span>
                                        @elseif($monthly->status == 'archived')
                                            <span class="label label-primary capitalize">{{ $monthly->status }}</span>
                                        @else
                                            <span class="label label-success capitalize">{{ $monthly->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                            <h3>Projects Summary - (Weekly)</h3>
                        </div>
                        <div class="col s6 right-align">
                            
                        </div>
                    </div>
                    <table id="" class="responsive-table display black-text">
                        <thead>
                            <tr class="black-text">
                                <th>Project Name</th>
                                <th>Assigned To</th>
                                <th>Created Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projectsWeekly as $weekly)
                                <tr>
                                    <td>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif">
                                            {{ $weekly->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($weekly->engineer['first_name'] == "")
                                            <span class="red-text darken-1">Not yet assigned</span>
                                        @else
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.engineer.edit', $weekly->engineer->id) }}@else{{ route('manager.engineer.edit', $weekly->engineer->id) }}@endif">
                                                {{ $weekly->engineer['first_name'] }} {{ $weekly->engineer['last_name'] }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($weekly->engineer['first_name'] == "")
                                            <span class="red-text darken-1">Not yet assigned</span>
                                        @else
                                            {{ \Carbon\Carbon::parse( $weekly->updated_at)->format('d M, Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($weekly->status == 'pending')
                                            <span class="label label-red capitalize">{{ $weekly->status }}</span>
                                        @else
                                            <span class="label label-success capitalize">{{ $weekly->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $("#exportSearch").click(function(){
        // alert("Hello");
        $("#exportForm").attr("action", "{{ route('admin.home') }}");
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var statusExport = $("#status").val();
        //alert(statusExport);
        //alert(fromDate);
        $.ajax({
            url:"{{ route('manager.project.monthly') }}",
            method:"GET",
            data: {from_date: fromDate, to_date: toDate, status: statusExport},
            datatype:"JSON",
            success:function(data){
                console.log(data);
                tableRow = "";

                for(i=0;i<data.length;i++){
                    tableRow += "<tr><td>"+data[i]['name']+"</a></td><td>"+data[i]['city']+"</td><td>"+data[i]['state']+"</td><td>"+data[i]['engineer_id']+"</td><td>"+data[i]['created_at']+"</td><td> @if ("+data[i]['status']+" == 'pending')  <span class='label label-red capitalize'>"+data[i]['status']+"</span>  @elseif("+data[i]['status']+" == 'archived')  <span class='label label-primary capitalize'>"+data[i]['status']+"</span>  @else <span class='label label-success capitalize'>"+data[i]['status']+"</span> @endif </td></tr>";
                }
                $("#tableContent").html(tableRow);
            }
        });
    });

    $("#exportExcel").click(function(){
        $("#exportForm").attr("action", "{{ route('manager.export.excel') }}");
        $("#exportForm").submit();
    });

    $("#exportPdf").click(function(){
        $("#exportForm").attr("action", "{{ route('manager.export.pdf') }}");
        $("#exportForm").submit();
    });
</script>
@endsection