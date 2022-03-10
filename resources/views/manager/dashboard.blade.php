@extends('layouts.app')

@section('title')
Manager Home - Genesis Design
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col l3 m6 s12">
            <a href="{{ route('manager.customer.index') }}"><div class="card danger-gradient card-hover">
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
            </div></a>
        </div>
        <div class="col l3 m6 s12">
            <a href="{{ route('manager.engineer.index') }}"><div class="card info-gradient card-hover">
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
            </div></a>
        </div>
        <div class="col l3 m6 s12">
            <a href="{{ url('manager/projects/list?type=active') }}"><div class="card success-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{$projectsActive }}</h2>
                            <h6 class="white-text op-5 text-darken-2">Active Projects</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="ti-bar-chart"></i></span>
                        </div>
                    </div>
                </div>
            </div></a>
        </div>
        <div class="col l3 m6 s12">
            <a href="{{ url('manager/projects/list?type=inactive') }}"><div class="card warning-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{ $projectsInActive }}</h2>
                            <h6 class="white-text op-5">In Active Projects</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="ti-info-alt"></i></span>
                        </div>
                    </div>
                </div>
            </div></a>
        </div>
    </div>
    <div class="row">
        <div class="col s12 l7">
            <div class="card card-hover">
                <div class="card-content">
                    <div class="d-flex align-items-center">
                    <div>
                        <h5 class="card-title">Designs</h5>
                    </div>
                </div>
                <div class="p-t-20">
                    <div class="row">
                        <div class="col s12">
                            <div id="nightingale-chart" style="height: 332px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
            <div class="col s12 l5">
                <div class="card card-hover">                       
                    <div class="card-content analytics-info">                            
                        <h5 class="card-title">Project Status</h5>
                        <div id="basic-pie"  style="height: 350px;"></div>
                    </div>                      
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card card-hover">
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
                                        <div class="col s2">
                                            <div class="input-field">
                                                <input type="date" name="from_date" id="from_date">
                                            </div>
                                        </div>
                                        <div class="col s2">
                                            <div class="input-field">
                                                <input type="date" name="to_date" id="to_date">
                                            </div>
                                        </div>
                                        <div class="col s3">
                                            <div class="input-field col s12">
                                                <select id="state" name="state">
                                                    <option value="" disabled selected>Select State</option>
                                                    @foreach(\App\Statics\Statics::STATUSES as $Status)
                                        <option value="{{$Status}}">{{Str::ucfirst($Status)}}</option>
                                    @endforeach
                                                </select>
                                                <label>State</label>
                                            </div>
                                        </div>
                                        <div class="col s3">
                                            <div class="input-field col s12">
                                                <select id="status" name="status">
                                                <option value="" disabled selected>Select Status</option>
                                    @foreach(\App\Statics\Statics::DESIGN_STATUS_ENGINEER as $projectStatus)
                                        <option value="{{$projectStatus}}">{{Str::ucfirst($projectStatus)}}</option>
                                    @endforeach
                                                </select>
                                                <label>Status</label>
                                            </div>
                                        </div>
                                        <div class="col s2" style="margin-top:2%;">
                                            <button type="button" id="exportSearch" class="btn btn-flat green white-text"><i class="material-icons left">search</i>Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="monthlyData">
                        @include('admin.reports.monthlyData')
                    </div>
                </div>            </div>
        </div>
       
       
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card card-hover">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                            <h3 class="card-title">Projects Summary - (Weekly)</h3>
                        </div>
                        <div class="col s6 right-align">
                            
                        </div>
                    </div>
                    <table id="" class="responsive-table striped display black-text">
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
                                        @if($weekly->engineer_id == "")
                                            <span class="red-text darken-1">Not yet assigned</span>
                                        @else
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.engineer.edit', $weekly->engineer->id) }}@else{{ route('manager.engineer.edit', $weekly->engineer->id) }}@endif">
                                                {{ $weekly->engineer['first_name'] }} {{ $weekly->engineer['last_name'] }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse( $weekly->updated_at)->format('d M, Y') }}
                                    </td>
                                    <td>
                                        @if($weekly->status == 'in active')
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
        var state = $("#state").val();
        //alert(statusExport);
        $.ajax({
            url:"{{ route('manager.project.monthly') }}",
            method:"GET",
            data: {from_date: fromDate, to_date: toDate, status: statusExport,state:state},
            datatype:"JSON",
            success:function(data){
                console.log(data);
                // tableRow = "";

                // for(i=0;i<data.length;i++){
                //     tableRow += "<tr><td>"+data[i]['name']+"</a></td><td>"+data[i]['first_name']+data[i]['last_name']+"</td><td>"+data[i]['engineer_id']+"</td><td>"+data[i]['assigned_date']+"</td><td>"+data[i]['created_at']+"</td><td> @if("+data[i][status]+" == 'in active') <span class='label label-red capitalize'>"+data[i]['status']+" / "+data[i]['project_status']+"</span> @else <span class='label label-success capitalize'>"+data[i]['status']+" / "+data[i]['project_status']+"</span> @endif </td></tr>";
                // }
                $("#monthlyData").html(data);
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
</script><script src="{{ asset('assets/libs/echarts/dist/echarts-en.min.js') }}"></script>
<script>

$(function() {
    "use strict";
    // ------------------------------
    // Basic pie chart
    // ------------------------------
    // based on prepared DOM, initialize echarts instance
        var basicpieChart = echarts.init(document.getElementById('basic-pie'));
        var option = {
            // Add title
               

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'horizontal',
                    x: 'left',
                    data: ['Assigned', 'Not Assigned', 'In Progress', 'Completed']
                },

                // Add custom colors
                color:["#92DFF3", "#E97452", "#4bc0c0", "#ffcd56"],

                // Display toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    feature: {
                        mark: {
                            show: true,
                            title: {
                                mark: 'Markline switch',
                                markUndo: 'Undo markline',
                                markClear: 'Clear markline'
                            }
                        },
                        // dataView: {
                        //     show: true,
                        //     readOnly: false,
                        //     title: 'View data',
                        //     lang: ['View chart data', 'Close', 'Update']
                        // },
                //         magicType: {
                //             show: true,
                //             title: {
                //                 pie: 'Switch to pies',
                //                 funnel: 'Switch to funnel',
                //             },
                //             type: ['pie', 'funnel'],
                //             option: {
                //                 funnel: {
                //                     x: '25%',
                //                     y: '20%',
                //                     width: '50%',
                //                     height: '70%',
                //                     funnelAlign: 'left',
                //                     max: 1548
                //                 }
                //             }
                //         },
                //         restore: {
                //             show: true,
                //             title: 'Restore'
                //         },
                //         saveAsImage: {
                //             show: true,
                //             title: 'Same as image',
                //             lang: ['Save']
                //         }
                    }
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [{
                    name: 'Status',
                    type: 'pie',
                    radius: '56%',
                    center: ['42%', '60%'],
                    data: [
                        {value: {{$designsAssigned }}, name: 'Assigned'},
                        {value: {{$designsInProcess }}, name: 'In Progress'},
                        {value: {{$designsNotAssigned }}, name: 'Not Assigned'},                        
                        {value: {{$designsCompleted }}, name: 'Completed'}
                    ]
                }]
        };
    
    basicpieChart.setOption(option);

      // ------------------------------
        // nightingale chart
        // ------------------------------
        // based on prepared DOM, initialize echarts instance
        var nightingaleChart = echarts.init(document.getElementById('nightingale-chart'));
            var option = {
                 

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    x: 'left',
                    y: 'top',
                    orient: 'horizontal',
                    data: ['Aurora','Structural Load','PE Stamping','Electric Load','Engineering Permit']
                },

                color: ['#ffcd56', '#E97452', '#4bc0c0', '#2962FF', '#1D3557'],

                // Display toolbox
                

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [
                    {
                        name: 'Design',
                        type: 'pie',
                        radius: ['15%', '66%'],
                        center: ['40%', '57%'],
                        roseType: 'area',

                        // Funnel
                        width: '40%',
                        height: '78%',
                        x: '30%',
                        y: '17.5%',
                        max: 450,
                        sort: 'ascending',

                        data: [
                            {value: {{ $auroraDesignCount }}, name: 'Aurora'},
                            {value: {{ $structuralCount }}, name: 'Structural Load'},
                            {value: {{ $pestampingCount }}, name: 'PE Stamping'},
                            {value: {{ $electricalCount }}, name: 'Electric Load'},
                            {value: {{ $permitCount }}, name: 'Engineering Permit'}
                        ]
                    }
                ]
            };
        nightingaleChart.setOption(option);
        //------------------------------------------------------
       // Resize chart on menu width change and window resize
       //------------------------------------------------------
        $(function () {

                // Resize chart on menu width change and window resize
                $(window).on('resize', resize);
                $(".sidebartoggler").on('click', resize);

                // Resize function
                function resize() {
                    setTimeout(function() {

                        // Resize chart
                        basicpieChart.resize();
                        basicdoughnutChart.resize();
                        customizedChart.resize();
                        nestedChart.resize();
                        poleChart.resize();
                    }, 200);
                }
            });
});
</script>


@endsection