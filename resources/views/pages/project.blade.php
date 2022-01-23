<div class="card-content">  
                    <div class="row">
                        <div class="col s3">
                            <h3>List of Projects</h3>
                        </div>
                        <div class="col s3 ">
                            <p><label>
                                <input type="button" class="btn btn-primary" style="display:none;" id="archive" onclick="archiveAll()" value="Archive All"/>
                            </label></p>
                        </div>
                    <div class="col s3 ">
                            <p><label>
                                <input type="button" class="btn btn-primary" style="display:none;" id="assign" onclick="assignAll()" value="Assign All"/>
                            </label></p>
                    </div>
                        <!-- <div class="col s3 right-align">
                            <p><label>
                                <input type="checkbox" class="filled-in" id="selectAll"/><span>Select All</span>
                            </label></p> -->
                    
                    <!-- <button class="btn indigo dropdown-trigger" data-target='dropdown1'><i class="material-icons left">add</i>NEW PROJECT</button>
                        <ul id='dropdown1' class='dropdown-content'>
                            <li><a href="http://127.0.0.1:8000/project/new/residential">Residential</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="http://127.0.0.1:8000/project/new/commercial">Commercial</a></li>
                            <li class="divider" tabindex="-1"></li>
                        </ul> -->                   
                        <!-- </div> -->
                    </div>
                    <table  class="responsive-table display">
                        <thead>
                            <tr class="black-text">
                                <!-- <th>Select</th> -->
                                <th>Project Name</th>
                                <th>Service Name</th>
                                <th>State</th>
                                <!-- <th>Design Type</th> -->
                                <th>Project Status</th>
                                <!-- <th>Project Type</th> -->
                                <th>Action</th>
                                <!-- <th>Designs</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        @if ($projectQuery->count() == 0)
                        <tr>
                            <td colspan="5">No Projects to display.</td>
                        </tr>
                        @endif
                            @foreach($projectQuery as $data)
                            @foreach($data->designs as $design)
                            <tr>
                                <!-- <td class="center">
                                   <p><label>
                                        <input type="checkbox" class="filled-in checkboxAll" id="{{ $data->id }}" value="{{ $data->id }}"/>
                                        <span> </span>
                                    </label></p>
                                </td> -->
                                <td>{{ $data->name }}</td>
                                <td>{{ $design->type->name }}</td>
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
                                    <span class="label label-success capitalize"> {{ $design->status_customer }}</span>
                                </td>
                                <!-- <td class="capitalize">{{ $data->type->name }}</td> -->
                                <td class="center">
                                @if (Auth::user()->role === \App\Statics\Statics::USER_TYPE_CUSTOMER || Auth::user()->role === \App\Statics\Statics::USER_TYPE_ADMIN)
                                    <a class="btn prussian-blue" href="{{route('project.edit', '')}}/{{ $data->id }}">View / Edit</a>
                                    <a class="btn prussian-blue ml-xxxs" href="{{route('design.list')}}/{{ $data->id  }}">Designs</a>
                                @else
                                    <a class="btn prussian-blue" href="{{route('engineer.project.view', '')}}/{{ $data->id }}">View</a>
                                    <a class="btn prussian-blue ml-xxxs" href="{{route('engineer.design.list')}}/{{ $data->id  }}">Designs</a>
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
                    <div class="">
    {!! $projectQuery->links() !!}
  </div>

<p>
    Displaying {{$projectQuery->count()}} of {{ $projectQuery->total() }} Project(s).
</p>
</div>