<div class="card card-content p-10">
<table id="" class="responsive-table striped display black-text">
                        <thead>
                            <tr class="black-text">
                                <th>Project Name</th>
                                <th>Service Name</th>
                                <!-- <th>Customer Name</th> -->
                                <th>Assigned To</th>
                                <th>Assigned Date</th>
                                <th>Design Status</th>
                                <th>Status</th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($projects)>0)
                            @foreach($projects as $weekly)
                                @if($weekly->designs->count()==0)
                                <tr>
                                    <td class="capitalize">
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif">
                                            {{ $weekly->name }}
                                        </a>
                                    </td>
                                    <td>
                                        NO DESIGN
                                    </td>
                                    <!-- <td>
                                        {{$weekly->customer->first_name}}{{$weekly->customer->last_name}}
                                    </td> -->
                                    <td class="capitalize">
                                        @if($weekly->engineer_id == "")
                                            <span class="helper-text red-text">Not Assigned</span>
                                        @else
                                            {{ $weekly->engineer->first_name }} {{ $weekly->engineer->last_name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($weekly->engineer_id == "")
                                            <span class="helper-text red-text">Not Assigned</span>
                                        @else
                                            {{ Carbon\Carbon::parse($weekly->assigned_date)->format('d M, Y') }} 
                                        @endif
                                    </td>
                                   
                                    <td> <label class="label label-inverse white-text"> --- </label> </td>
                                    <td>
                                        @if($weekly->status == 'in active')
                                            <span class="label label-red capitalize">{{ $weekly->status }}</span>
                                        @else
                                            <span class="label label-success capitalize">{{ $weekly->status }}</span>
                                        @endif
                                    </td>
                                 
                                    <td class="center">
                                        <button href="#" onclick="setProjectID('{{ $weekly->name }}',{{$weekly->id}},'A123')" class="btn btn-small light-green modal-trigger tooltipped" data-position="bottom" data-tooltip="Assign to an Engineer"><i class="ti-check-box small"></i></button>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif" class="indigo-text"><button class="btn btn-small indigo tooltipped" data-position="bottom" data-tooltip="Edit the Project" type="button"><i class="ti-pencil small"></i></button></a>
                                    </td>
                                </tr>
                                @else
                                @foreach($weekly->designs as $design)
                                <tr>
                                    <td class="capitalize">
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif">
                                            {{ $weekly->name }}
                                        </a>
                                    </td>
                                    <td class="capitalize">
                                       {{$design->type->name}}
                                    </td>
                                    <!-- <td>
                                        {{$weekly->customer->first_name}}{{$weekly->customer->last_name}}
                                    </td> -->
                                    <td class="capitalize">
                                        @if($weekly->engineer_id == "")
                                            <span class="helper-text red-text">Not Assigned</span>
                                        @else
                                            {{ $weekly->engineer->first_name }} {{ $weekly->engineer->last_name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($weekly->engineer_id == "")
                                            <span class="helper-text red-text">Not Assigned</span>
                                        @else
                                            {{ Carbon\Carbon::parse($weekly->assigned_date)->format('d M, Y') }} 
                                        @endif
                                    </td>
                                   
                                    <td class="capitalize"> {{ $design->status_engineer }} </td>
                                    <td>
                                        @if($weekly->status == 'in active')
                                            <span class="label label-red capitalize">{{ $weekly->status }}</span>
                                        @else
                                            <span class="label label-success capitalize">{{ $weekly->status }}</span>
                                        @endif
                                    </td>
                                    <td class="center">
                                        <button class="btn btn-small light-green modal-trigger tooltipped" data-position="bottom" data-tooltip="Assign to an Engineer" href="#assignModel" onclick="setProjectID('{{ $weekly->name }}',{{$weekly->id}},{{$design->id}})"><i class="ti-check-box small"></i></button>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif" class="indigo-text"><button class="btn btn-small indigo tooltipped" data-position="bottom" data-tooltip="Edit the Project" type="button"><i class="ti-pencil small"></i></button></a>
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
                   