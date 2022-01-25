<div class="card card-content p-10">
                    <table  class="responsive-table display striped">
                        <thead>
                            <tr class="black-text">
                                <!-- <th>Select</th> -->
                                <th>Project Name</th>
                                <th>Service Name</th>
                                <th>State</th>
                                <th>Project Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if ($projectQuery->count() == 0)
                        <tr>
                            <td colspan="5">No Projects to display.</td>
                        </tr>
                        @endif
                            @foreach($projectQuery as $data)
                            @if($data->designs->count()==0)
                            <tr>
                                
                                <td>{{ $data->name }}</td>
                                <td> No Design</td>
                              
                                <td class="capitalize">
                                @if($data->status == 'in active')
                                    <span class="label label-red capitalize"> {{ $data->status }}</span>
                                @else
                                    <span class="label label-success capitalize"> {{ $data->status }}</span>
                                @endif</td>

                                </td>   
                                <td class="capitalize">
                                    <span class="label label-inverse capitalize"> - </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('d M, Y') }}
                                </td>
                                <td class="">
                                @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER || Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                                    <a class="btn btn-small prussian-blue" href="{{route('project.edit', '')}}/{{ $data->id }}">View / Edit</a>
                                    <a class="btn btn-small prussian-blue ml-xxxs" href="{{route('design.list')}}/{{ $data->id  }}">Designs</a>
                                @else
                                    <a class="btn btn-small prussian-blue" href="{{route('engineer.project.view', '')}}/{{ $data->id }}">View</a>
                                    <a class="btn btn-small prussian-blue ml-xxxs" href="{{route('engineer.design.list')}}/{{ $data->id  }}">Designs</a>
                                @endif
                                   
                                </td>
                               
                            </tr>
                            @endif
                            @foreach($data->designs as $design)
                            <tr>
                                <!-- <td class="center">
                                   <p><label>
                                        <input type="checkbox" class="filled-in checkboxAll" id="{{ $data->id }}" value="{{ $data->id }}"/>
                                        <span> </span>
                                    </label></p>
                                </td> -->
                                <td>{{ $data->name }}</td>
                                <td class="capitalize">{{ $design->type->name }}</td>
                                <!-- <td>
                                    @if($data->engineer_id == "")
                                        <span class="helper-text red-text">Not Assigned</span>
                                    @else
                                        {{ $data->engineer->first_name }} {{ $data->engineer->last_name }}
                                    @endif
                                </td> -->
                                <!-- <td>
                                    @if($data->engineer_id == "")
                                        <span class="helper-text red-text">Not Assigned</span>
                                    @else
                                        {{ Carbon\Carbon::parse($data->assigned_date)->format('M d, Y') }}
                                    @endif
                                </td> -->
                                <!-- <td>
                                   abc
                                </td> -->
                                <td class="capitalize">
                                @if($data->status == 'in active')
                                    <span class="label label-red capitalize"> {{ $data->status }}</span>
                                @else
                                    <span class="label label-success capitalize"> {{ $data->status }}</span>
                                @endif</td>

                                </td>   
                                <td class="capitalize">
                                    @if(Auth::user()->role == 'customer')
                                        <span class="label label-success capitalize"> {{ $design->status_customer }}</span>
                                    @else
                                        <span class="label label-success capitalize"> {{ $design->status_engineer }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('d M, Y') }}
                                </td>
                                <td class="">
                                @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER || Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                                    <a class="btn btn-small prussian-blue" href="{{route('project.edit', '')}}/{{ $data->id }}">View / Edit</a>
                                    <a class="btn btn-small prussian-blue ml-xxxs" href="{{route('design.list')}}/{{ $data->id  }}">Designs</a>
                                @else
                                    <a class="btn btn-small prussian-blue" href="{{route('engineer.project.view', '')}}/{{ $data->id }}">View</a>
                                    <a class="btn btn-small prussian-blue ml-xxxs" href="{{route('engineer.design.list')}}/{{ $data->id  }}">Designs</a>
                                @endif
                                   
                                </td>
                                <!-- <td>
                                <button type="submit" class="btn indigo">Design </button>
                                </td> -->
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    <div>
        <style>
            .pager{
                display: inline-flex !important;
            }
            .pager > li{
                padding-inline: 10px;
            }
        </style>
        {{ $projectQuery->links('vendor.pagination.custom') }}
    </div>
        <p style="padding-top:2%;">
            Displaying {{$projectQuery->count()}} of {{ $projectQuery->total() }} Project(s).
        </p>
</div>