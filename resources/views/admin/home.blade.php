@extends('layouts.app')

@section('title')
Admin Home - Genesis Design
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
                            <h6 class="white-text light-blue-text">Customers</h6>
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
                            <h6 class="white-text">Engineers</h6>
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
                            <h6 class="white-text text-darken-2">Active Projects</h6>
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
                            <h2 class="white-text m-b-5">{{ $projectsInActive }}</h2>
                            <h6 class="white-text">In Active Projects</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="ti-info-alt"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PIE CHART JS -->
    <div class="row">
                    <div class="col s12 l7">
                    <div class="card card-hover">
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

                <div class="col s12 l5">
                        <div class="card card-hover">
                       
                            <div class="card-content analytics-info">
                            
                                <h5 class="card-title">Project Status</h5>
                                <div id="basic-pie"  style="height: 350px;"></div>
                            </div>
                          
                        </div>
                </div>
                    <!-- <div class="col s12 l4">
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
        </div> -->
                    

    </div>
    <div class="row">
        <div class="col s12">
            <div class="card card-hover">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                        <h5 class="card-title">Projects Summary - (Monthly)</h5>
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
                                        <div class="col s3">
                                            <div class="input-field col s12">
                                                <select id="status" name="status">
                                                    <option value="" disabled selected>Select Status</option>
                                                    @foreach(\App\Statics\Statics::PROJECT_STATUSES as $projectStatus)
                                                        <option value="{{$projectStatus}}">{{Str::ucfirst($projectStatus)}}</option>
                                                    @endforeach
                                                </select>
                                                <label>Status</label>
                                            </div>
                                        </div>
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
                                <th>Customer Name</th>
                                <th>Assigned To</th>
                                <th>Assigned Date</th>
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
                                    <td class="capitalize">
                                        {{ $monthly->customer->first_name }} {{ $monthly->customer->last_name }}
                                    </td>
                                    <td>
                                        @if($monthly->engineer_id == "")
                                            <span class="red-text darken-1">Not yet assigned</span>
                                        @else
                                            <a href="@if(Auth::user()->role == 'admin'){{ route('admin.engineer.edit', $monthly->engineer->id) }}@else{{ route('manager.engineer.edit', $monthly->engineer->id) }}@endif">
                                                {{ $monthly->engineer['first_name'] }} {{ $monthly->engineer['last_name'] }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse( $monthly->assigned_date)->format('d M, Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse( $monthly->updated_at)->format('d M, Y') }}
                                    </td>
                                    <td>
                                    @if($monthly->status == 'in active')
                                        <span class="label label-red capitalize">{{ $monthly->status }} / {{ $monthly->project_status }}</span>
                                    @else
                                        <span class="label label-success capitalize">{{ $monthly->status }} / {{ $monthly->project_status }}</span>
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>            </div>
        </div>
       
       
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card card-hover">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                        <h5 class="card-title">Projects Summary - (Weekly)</h5>
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
                            @if(count($projectsWeekly)>0)
                            @foreach($projectsWeekly as $weekly)
                                <tr>
                                    <td>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif">
                                            {{ $weekly->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($weekly->engineer_id == "")
                                            <span class="helper-text red-text">Not Assigned</span>
                                        @else
                                            {{ $weekly->engineer->first_name }} {{ $weekly->engineer->last_name }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($weekly->created_at)->format('d M, Y') }}
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
                            @else
                            <tr><td></td><td></td><td></td><td></td></tr>
                            @endif
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
        $.ajax({
            url:"{{ route('admin.project.monthly') }}",
            method:"GET",
            data: {from_date: fromDate, to_date: toDate, status: statusExport},
            datatype:"JSON",
            success:function(data){
                console.log(data);
                tableRow = "";

                for(i=0;i<data.length;i++){
                    tableRow += "<tr><td>"+data[i]['name']+"</a></td><td>"+data[i]['first_name']+data[i]['last_name']+"</td><td>"+data[i]['engineer_id']+"</td><td>"+data[i]['assigned_date']+"</td><td>"+data[i]['created_at']+"</td><td> @if("+data[i][status]+" == 'in active') <span class='label label-red capitalize'>"+data[i]['status']+" / "+data[i]['project_status']+"</span> @else <span class='label label-success capitalize'>"+data[i]['status']+" / "+data[i]['project_status']+"</span> @endif </td></tr>";
                }
                $("#tableContent").html(tableRow);
            }
        });
    });
    $("#exportExcel").click(function(){
        $("#exportForm").attr("action", "{{ route('admin.export.excel') }}");
        $("#exportForm").submit();
    });

    $("#exportPdf").click(function(){
        $("#exportForm").attr("action", "{{ route('admin.export.pdf') }}");
        $("#exportForm").submit();
    });
</script>
<script src="{{ asset('assets/libs/echarts/dist/echarts-en.min.js') }}"></script>
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
                    data: ['Assigned', 'Not Assigned', 'In Process', 'Completed']
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
                        {value: {{$projectsAssigned }}, name: 'Assigned'},
                        {value: {{$projectsInProcess }}, name: 'In Process'},
                        {value: {{$projectsNotAssigned }}, name: 'Not Assigned'},                        
                        {value: {{$projectsCompleted }}, name: 'Completed'}
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
                 title: {
                    text: 'Employee\'s salary review',
                    subtext: 'Senior front end developer',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: +{c}$ ({d}%)"
                },

                // Add legend
                legend: {
                    x: 'left',
                    y: 'top',
                    orient: 'vertical',
                    data: ['January','February','March','April','May','June','July','August','September','October','November','December']
                },

                color: ['#ffbc34', '#4fc3f7', '#212529', '#f62d51', '#2962FF', '#FFC400', '#006064', '#FF1744', '#1565C0', '#FFC400', '#64FFDA', '#607D8B'],

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
                        dataView: {
                            show: true,
                            readOnly: false,
                            title: 'View data',
                            lang: ['View chart data', 'Close', 'Update']
                        },
                        magicType: {
                            show: true,
                            title: {
                                pie: 'Switch to pies',
                                funnel: 'Switch to funnel',
                            },
                            type: ['pie', 'funnel']
                        },
                        restore: {
                            show: true,
                            title: 'Restore'
                        },
                        saveAsImage: {
                            show: true,
                            title: 'Same as image',
                            lang: ['Save']
                        }
                    }
                },

                // Enable drag recalculate
                calculable: true,

                // Add series
                series: [
                    {
                        name: 'Increase (brutto)',
                        type: 'pie',
                        radius: ['15%', '73%'],
                        center: ['50%', '57%'],
                        roseType: 'area',

                        // Funnel
                        width: '40%',
                        height: '78%',
                        x: '30%',
                        y: '17.5%',
                        max: 450,
                        sort: 'ascending',

                        data: [
                            {value: 440, name: 'January'},
                            {value: 260, name: 'February'},
                            {value: 350, name: 'March'},
                            {value: 250, name: 'April'},
                            {value: 210, name: 'May'},
                            {value: 350, name: 'June'},
                            {value: 300, name: 'July'},
                            {value: 430, name: 'August'},
                            {value: 400, name: 'September'},
                            {value: 450, name: 'October'},
                            {value: 330, name: 'November'},
                            {value: 200, name: 'December'}
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