<div class="card card-content p-10">
<table id="" class="responsive-table display black-text">
                        <thead>
                            <tr class="black-text">
                                <th>Project Name</th>
                                <th>Customer Name</th>
                                <th>Assigned To</th>
                                <th>Assigned Date</th>
                                <th>Created Date</th>
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
                                    <td>
                                        <a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif">
                                            {{ $weekly->name }}
                                        </a>
                                    </td>
                                   
                                    <td>
                                        {{$weekly->customer->first_name}}{{$weekly->customer->last_name}}
                                    </td>
                                    <td>
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
                                    <td>
                                        {{ Carbon\Carbon::parse($weekly->created_at)->format('d M, Y') }}
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
                                <a class='dropdown-trigger white black-text' href='#' data-target='action{{ $weekly->id }}'><i class="ti-view-list"></i></a>
                                    <ul id='action{{$weekly->id}}' class='dropdown-content'>
                                        <li><a href="#assignModel" onclick="setProjectID('{{ $weekly->name }}',{{$weekly->id}},{{$weekly->id}})" class="blue-text modal-trigger">Assign</a></li>
                                        <li><a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif" class="indigo-text">Edit</a></li>
                                        <li>
                                            <form id="archiveForm{{$weekly->id}}" action="{{route('project.archive', $weekly->id)}}" method="post">
                                                @csrf
                                                
                                            </form>
                                            <a onclick="archiveProject({{$weekly->id}})" class="imperial-red-text ">Archive</a>
                                        </li>
                                    </ul>
                                   
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
                                   
                                    <td>
                                        {{$weekly->customer->first_name}}{{$weekly->customer->last_name}}
                                    </td>
                                    <td>
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
                                    <td>
                                        {{ Carbon\Carbon::parse($weekly->created_at)->format('d M, Y') }}
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
                                <a class='dropdown-trigger white black-text' href='#' data-target='action{{ $design->id }}'><i class="ti-view-list"></i></a>
                                    <ul id='action{{$design->id}}' class='dropdown-content'>
                                        <li><a href="#assignModel" onclick="setProjectID('{{ $weekly->name }}',{{$weekly->id}},{{$design->id}})" class="blue-text modal-trigger">Assign</a></li>
                                        <li><a href="@if(Auth::user()->role == 'admin'){{ route('admin.projects.edit', $weekly->id) }}@else{{ route('manager.projects.edit', $weekly->id) }}@endif" class="indigo-text">Edit</a></li>
                                        <li>
                                            <form id="archiveForm{{$weekly->id}}" action="{{route('project.archive', $weekly->id)}}" method="post">
                                                @csrf
                                                
                                            </form>
                                            <a onclick="archiveProject({{$weekly->id}})" class="imperial-red-text ">Archive</a>
                                        </li>
                                    </ul>
                                   
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
                   