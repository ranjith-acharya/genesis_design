@extends('layouts.app')

@section('title')
Customer Project - Reports
@endsection

@section('content')
<div class="container-fluid">
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
                                <th>Created Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableContent">
                            @foreach($projects as $monthly)
                                <tr>
                                    <td>                                        
                                        {{ $monthly->name }}
                                    </td>
                                    <td>
                                        {{ $monthly->city }}
                                    </td>
                                    <td>
                                        {{ $monthly->state }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse( $monthly->created_at)->format('d M, Y') }}
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
</div>
@endsection

@section('js')
<script>
    $("#exportSearch").click(function(){
        // alert("Hello");
        $("#exportForm").attr("action", "{{ route('customer.export') }}");
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        var statusExport = $("#status").val();
        //alert(statusExport);
        //alert(fromDate);
        $.ajax({
            url:"{{ route('customer.projects.list') }}",
            method:"GET",
            data: {from_date: fromDate, to_date: toDate, status: statusExport},
            datatype:"JSON",
            success:function(data){
                console.log(data);
                tableRow = "";

                for(i=0;i<data.length;i++){
                    tableRow += "<tr><td>"+data[i]['name']+"</a></td><td>"+data[i]['city']+"</td><td>"+data[i]['state']+"</td><td>"+data[i]['created_at']+"</td><td>"+data[i]['status']+"</td></tr>";
                }
                $("#tableContent").html(tableRow);
            }
        });
    });

    $("#exportExcel").click(function(){
        $("#exportForm").attr("action", "{{ route('customer.export.excel') }}");
        $("#exportForm").submit();
    });

    $("#exportPdf").click(function(){
        $("#exportForm").attr("action", "{{ route('customer.export.pdf') }}");
        $("#exportForm").submit();
    });
</script>
@endsection