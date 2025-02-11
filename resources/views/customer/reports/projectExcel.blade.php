<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    &nbsp;&nbsp;<h2 style="text-align:center;">Genesis Design</h2>
    @php
        $date=$projects->first()->created_at;
        $month =date("F",strtotime($date));
        $year =date("Y",strtotime($date));
    @endphp
    <h3>PROJECT REPORT FOR THE MONTH OF {{$month}} - {{$year}}</h3><br>
    <table class="table table-bordered" border="1"  id="tickets-table">
    <thead>                                                
        <tr>
            <th>Project Name</th>
            <th>Assigned Date</th>
            <th>Service Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Design Status</th>
            <th>Price</th>
            <th>Payment Date</th>
        </tr>
    </thead>
    <tbody id="tableData">
    @foreach($projects as $project)
        @foreach($project->designs as $design)
        <tr>
            <td>
                {{$project['name']}}
            </td>
            <td>    
                {{$project['assigned_date']}}
            </td>
            <td>    
                {{$design->type['name']}}
            </td>
            <td>    
                {{$design['start_date']}}
            </td>
            <td>    
                {{$design['end_date']}}
            </td>
            <td>    
                {{$design['status_engineer']}}
            </td>
            <td>    
                $ {{$design['price']}}
            </td>
            <td>    
                {{$design['payment_date']}}
            </td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
    </table>
</body>
</html>