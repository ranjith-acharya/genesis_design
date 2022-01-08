<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Monthly Report</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table, th, td {
            font-size: small;
        }
        .invoice table {
            border: 1px solid black;
            border-collapse:collapse;
            padding-left: 10px;
            padding-right: 10px;
        }
        #invoiceTable table, th, td {
            padding:10px;
            border-collapse: collapse;
        }
        .invoice h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #1D3557;
            color: #FFF;
        }
        .logo {
            margin-top: -80px;
            margin-left:-80px;
        }
        .information table {
            padding: 20px;
        }
        .red-text{
            color:#F44336;
        }
        .active-text{
            color: #2dce89;
        }
        .archived-text{
            color: #5e72e4;
        }
    </style>

</head>
<body>
<div class="information">
    <table width="100%" style="border:none;">
        <tr>
            <td align="left" style="width: 40%;">

            </td>
            <td align="center">
                <img src="{{ public_path('assets/images/logo-lightversion.png') }}" alt="Logo" width="250px" height="50px" class="logo"/>
            </td>
            <td align="right" style="width: 40%;">
                <h3>Genesis Design</h3>
                <pre>
                    https://company.com
                    Street 26
                    123456 City
                    United Kingdom
                    <br><br>
                    Date: {{ Carbon\Carbon::now()->format('d M, Y') }}
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h3>Project Monthly Report</h3>
    <table id="invoiceTable" width="100%">
        <thead>
        <tr>
            <th>Project Name</th>
            <th>Customer City</th>
            <th>Customer State</th>
            <th>Assigned to</th>
            <th>Assigned Date</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            @foreach($projects as $data)
            <tr>
                <td>{{ $data->name }}</td>
                <td>{{ $data->city }}</td>
                <td>{{ $data->state }}</td>
                <td>
                    @if($data->engineer['first_name'] == "")
                        <span class="red-text">Not yet assigned</span>
                    @else
                        {{ $data->engineer['first_name'] }} {{ $data->engineer['last_name'] }}
                    @endif
                </td>
                <td>
                    @if($data->engineer['first_name'] == "")
                        <span class="red-text">Not yet assigned</span>
                    @else
                        {{ \Carbon\Carbon::parse( $data->updated_at)->format('d M, Y') }}
                    @endif
                </td>
                <td>
                    @if($data->status == 'pending')
                        <span class="label label-red capitalize">{{ $data->status }}</span>
                    @elseif($data->status == 'archived')
                        <span class="label label-primary capitalize">{{ $data->status }}</span>
                    @else
                        <span class="label label-success capitalize">{{ $data->status }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
            </td>
            <td align="center" style="width:50%;">

            </td>
            <td align="right" style="width: 50%;">
                Company Slogan
            </td>
        </tr>
    </table>
</div>
</body>
</html>