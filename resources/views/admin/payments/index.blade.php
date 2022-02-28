@extends('layouts.app')

@section('title')
Project Index - Genesis Design
@endsection
@push('style')
	<style type="text/css">
        ul{
            white-space:nowrap !important;
        }
		.my-active span{
			background-color: #5cb85c !important;
			color: white !important;
			border-color: #5cb85c !important;
		}
	</style>
@endpush
@section('js')
<script src="https://js.stripe.com/v3/"></script>

<script>
const stripePublicKey = "{{env('STRIPE_KEY')}}";
const paymentCancelUrl="{{route('payment.cancel')}}";
async function cancelProjectPayment(stripe_payment_code,design_id)
{
   console.log(stripe_payment_code);
   console.log("<-------ID --->: ",design_id);
   let stripe = Stripe(stripePublicKey);

    return await axios(paymentCancelUrl, {
        method: 'post',
        data: {code: stripe_payment_code},
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
        }
    }).then(response => {
        console.log(response);
        if (response.status === 200 || response.status === 201) 
        {


            fetch("{{ route('admin.payment.status') }}", {
                                method: 'post',
                                data: {design_id:design_id,payment_status:'cancel'},
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                                }
                            
                            }).then(response => {
                                if (response.status === 200 || response.status === 201)
                                {
                                    console.log(response);
                                    
                                    toastr.success('Design Payment Status Updated !', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                                } 
                                else 
                                {
                                    toastr.error('There was a error inserting the design. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                                    console.error(response);
                                    elem.disabled = false;
                                }
                            }).catch(err => {
                                toastr.error('There was a network error. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                                console.error(err);
                                elem.disabled = false;
                            });
        }
        else{
            M.toast({
                html: "There was a error processing payment. Please try again.",
                classes: "imperial-red"
            });
            return false;
        }
    }).catch(err => {
        M.toast({
            html: "There was a network error. Please try again.",
            classes: "imperial-red"
        });
        console.error(err);
        return false;
    });
}


function filter() {
            getMoreUsers(1);
        }
        $('#project_search').on('keyup', function() {
         
          getMoreUsers(1);
        });
function getMoreUsers(page) {
    M.FormSelect.init(document.querySelectorAll('select'));
    var filters = [
                {field: "project_type_id", value: M.FormSelect.getInstance(document.getElementById('project_type_select')).getSelectedValues()[0]},
                {field: "project_status", value: M.FormSelect.getInstance(document.getElementById('project_status_select')).getSelectedValues()[0]},
                {field: "status", value: M.FormSelect.getInstance(document.getElementById('status_select')).getSelectedValues()[0]}
            ]
    var search=$('#project_search').val();
    //console.log(search+" "+project_type_id+" "+project_status+" "+status);
       $.ajax({
        type: "GET",
        data: {
          'search':search,
          'filters': JSON.stringify(filters)
        },
        url: "@if(Auth::user()->role == 'admin'){{route('admin.projects.getPayments')}}@else{{route('manager.projects.getPayments')}}@endif" + "?page=" + page,
        success:function(data) {
           
          $('#projectData').html(data);
        }
      });
    }
</script>
<script>

$(document).ready(function() {
        $(document).on('click', '.pager a', function(event) {
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
         
         getMoreUsers(page);
        });

       

       
    });
function setProjectID(name,id,design_id){
    //alert(name);
    if(design_id=="A123")
    {
        alert("Currently No Design In this Project We Can't Assign to Anyone ");
    }
    else{

    
    $('#project_id').val(id);
    $("#design_id").val(design_id);
    $("#assign_form").attr('action',"@if(Auth::user()->role == 'admin'){{ route('admin.assign') }}@else{{ route('manager.assign') }}@endif");
    $("#project_name").text(name);
    //alert(id)
    var modelid=id;
    $.ajax({
        url:"@if(Auth::user()->role == 'admin'){{url('admin/projects')}}@else{{url('manager/projects')}}@endif"+"/"+id+"/assign",
        method:"POST",
        datatype:"JSON",
        success:function(data)
        {
            //alert(data);
            console.log(data);
            //$("#engineer_select").val(data['engineer_id']).attr("selected", "selected");
            $('#engineer_select option[value="'+data['engineer_id']+'"]').attr("selected", "selected");
            // $('#updateForm').attr('action',"{{url('fuel_details')}}"+"/"+id); 
            // $("#method").val("PATCH");        
        }
    });
    }
}
    function archiveProject(id){
        $("#archiveForm"+id).submit();
    }
    
</script>
@endsection
@section('content')
<div class="container-fluid black-text">
    <div class="row">
        <div class="col s12">
            <div class="card">
                @if ($message = Session::get('success'))
                <script>
                    toastr.success('{{$message}}', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                </script>
	            @endif
                <div class="card-content">
                    <div class="row">
                        <div class="col s3">
                            <h3>Project Payments</h3>
                        </div>
                    </div>
<div class="row mb-0">
                <div class="col s12 m9 center-on-small-only">
                    <div class="col s3">
                        <div class="input-field inline">
                            <input id="project_search" type="text" data-type="projects">
                            <label for="project_search">Search for project(s)...</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field inline">
                        <select id="project_type_select" onchange="filter()">
                                <option value="all">All</option>
                                @foreach($projectTypes as $projectType)
                                    <option value="{{$projectType->id}}">{{Str::ucfirst($projectType->name)}}</option>
                                @endforeach
                            </select>
                            <label for="project_type_select">Project Type</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field inline">
                            <select id="status_select" onchange="filter()">
                                <option value="all">All</option>
                                    @foreach(\App\Statics\Statics::STATUSES as $Status)
                                        <option value="{{$Status}}">{{Str::ucfirst($Status)}}</option>
                                    @endforeach
                            </select>
                            <label for="status_select"> State</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field inline">
                            <select id="project_status_select" onchange="filter()">
                                <option value="all">All</option>
                                    @foreach(\App\Statics\Statics::DESIGN_STATUS_ENGINEER as $projectStatus)
                                        <option value="{{$projectStatus}}">{{Str::ucfirst($projectStatus)}}</option>
                                    @endforeach
                            </select>  
                            <label for="project_status_select">Project Status</label>
                        </div>
                    </div>
                </div>
               
            </div>
           
       
                    <div id="projectData">
                        @include('admin.reports.payments')
                        
                    </div>
                  

            </div>
        </div>
    </div>
</div>
@endsection

