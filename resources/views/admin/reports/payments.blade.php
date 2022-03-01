<div class="card card-content p-10">
<table id="" class="responsive-table striped display black-text">
                        <thead>
                            <tr class="black-text">
                                <th>Project Name</th>
                                <th>Service Name</th>
                                <th>Customer Name</th>
                                <!-- <th>Assigned To</th> -->
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($projects)>0)
                            @foreach($projects as $weekly)
                                @if($weekly->designs->count()==0)
                                <tr>
                                    <td>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif">
                                            {{ $weekly->name }}
                                        </a>
                                    </td>
                                    <td>
                                        No Design
                                    </td>
                                    <td>
                                        {{$weekly->customer->first_name}}{{$weekly->customer->last_name}}
                                    </td>
                                    <!-- <td>
                                        @if($weekly->engineer_id == "")
                                            <span class="helper-text red-text">Not Assigned</span>
                                        @else
                                            {{ $weekly->engineer->first_name }} {{ $weekly->engineer->last_name }}
                                        @endif
                                    </td> -->
                                   
                                   
                                    <td> <label class="label label-inverse white-text"> --- </label> </td>
                                    <td>
                                        @if($weekly->status == 'in active')
                                            <span class="label label-red capitalize">{{ $weekly->status }}</span>
                                        @else
                                            <span class="label label-success capitalize">{{ $weekly->status }}</span>
                                        @endif
                                    </td>
                                 
                                    <td class="center">
                                    @if($weekly->engineer_id == "")
                                        <button class="btn btn-small indigo tooltipped"><i class="ti-dustbin small"></i></button>                                   
                                    @else
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif" class="indigo-text"><button class="btn btn-small indigo tooltipped" data-position="bottom" data-tooltip="Refund Project" type="button"><i class="ti-pencil small"></i></button></a>
                                    @endif
                                    </td>
                                </tr>
                                @else
                                @foreach($weekly->designs as $design)
                                <tr>
                                    <td>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif">
                                            {{ $weekly->name }}
                                        </a>
                                    </td>
                                    <td class="capitalize">
                                       {{$design->type->name}}
                                    </td>
                                    <td>
                                        {{$weekly->customer->first_name}}{{$weekly->customer->last_name}}
                                    </td>
                                    <!-- <td>
                                        @if($weekly->engineer_id == "")
                                            <span class="helper-text red-text">Not Assigned</span>
                                        @else
                                            {{ $weekly->engineer->first_name }} {{ $weekly->engineer->last_name }}
                                        @endif
                                    </td> -->
                                  
                                   
                                    <td class="capitalize"> {{ $design->payment_status }} </td>
                                    <td>
                                        @if($weekly->status == 'in active')
                                            <span class="label label-red capitalize">{{ $weekly->status }}</span>
                                        @else
                                            <span class="label label-success capitalize">{{ $weekly->status }}</span>
                                        @endif
                                    </td>
                                    <td class="center">
                                    @if($design->payment_date == "")
                                    <button  onclick="cancelProjectPayment('{{$design['stripe_payment_code']}}',{{$design->id}})" class="btn btn-small indigo tooltipped">Cancel Project</button>     
                                    @else
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif" class="indigo-text"><button class="btn btn-small indigo tooltipped" data-position="bottom" data-tooltip="Refund Project" type="button"><i class="ti-pencil small"></i></button></a>
                                    @endif
                                 </td>
                                </tr>
                                @endforeach
                                @endif
                            @endforeach
                            @else
                            <tr><td></td><td></td><td></td><td></td><td></td></tr>
                            @endif
                        </tbody>
                    </table>
</div>
<div>
        <style>
            .pager{
                display: inline-flex !important;
            }
            .pager > li{
                padding-inline: 10px;
            }
        </style>
        {{ $projects->links('vendor.pagination.custom') }}
    </div>
                   