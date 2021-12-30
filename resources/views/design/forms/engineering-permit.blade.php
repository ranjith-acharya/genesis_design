@extends('layouts.app')

@section('title', "New $type->name design")

@php
    $equipment = \App\Equipment::whereIn('type', [\App\Statics\Statics::EQUIPMENT_TYPE_INVERTER, \App\Statics\Statics::EQUIPMENT_TYPE_MODULE, \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR ])->get(['name', 'model', 'type']);
    $monitorSelect = [];
    $inverterSelect = [];
    $moduleSelect = [];
    foreach ($equipment as $item){
        if ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_INVERTER)
            $inverterSelect[$item->name . " | " . $item->model] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MODULE)
            $moduleSelect[$item->name . " | " . $item->model] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR)
            $monitorSelect[$item->name . " | " . $item->model] = null;
    }
@endphp

@section('content')
<div class="container-fluid black-text">
        <form id="structural_form"></form>
        <div class="row">
            <div class="col s12">
                <h3 class="imperial-red-text capitalize">{{$type->name}}</h3>
                <h5>Design Request</h5>
            </div>
            <div class="col s12">
                <div class="card" style="margin-top:-2%;">
                    <div class="wizard-content" style="padding-bottom:2%;">
                        <form action="#" class="validation-wizard wizard-circle m-t-40">
                            <h6>Step 1</h6>
                            <section>
                                <div class="row">
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <div class="switch center">
                                                <label>
                                                    Max System Size
                                                    <input type="checkbox" id="hoa" onclick="oggleSystemSize(this)">
                                                    <span class="lever"></span>
                                                    Limited System Size
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col m8">
                                        <div class="input-field col s12">
                                            <input id="system_size" name="system_size" type="text" class="required" value="maximum" placeholder=" ">
                                            <label for="system_size">System Size</label>
            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <div class="switch center">
                                                <label class="tooltipped" data-position="top" data-delay="10" data-tooltip="House Owner Association">                         
                                                    HOA? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
                                                    <input type="checkbox" onclick="oggleHOA(this)" checked>
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <select name="installation" id="installation" >
                                                <option value="none" selected>None</option>
                                                <option value="front roof">Front Roof</option>
                                                <option value="black roof">Back Roof</option>
                                                <option value="garage">Garage</option>
                                            </select>
                                            <label for="installation">Installation restrictions</label>
                                            <span class="helper-text red-text" data-error="This field is required" data-success="">Required</span>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <input id="remarks" name="remarks" type="text"  value="None">
                                            <label for="remarks">Remarks</label>
            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s4">
                                        @component('components.autocomplete', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                    </div>
                                    <div class="col s4">
                                        @component('components.autocomplete', ["name" => "monitor", "data" => $monitorSelect])@endcomponent
                                    </div>
                                    <div class="col s4">
                                        @component('components.autocomplete', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                    </div>
                                </div>
                                <div class="row">
                                    <br><br><br>
                                </div>
                            </section>
                            <h6>Step 2</h6>
                            <section>
                                <div class="row">
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Arrival</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Screen shot of Calendar invite</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Location of nearest Transformer</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Interior</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Ceilings-existing cracks</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Close up of breakers</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Attic</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Sheating size, stamp if possible</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Pitch on rafter of each roof plane</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Finished or Valued ceiling protocol</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Access from attic vent?</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Stud finder</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Roof</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>360&#176; skyline</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Pliability test</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row image-repeater">
                                    <div data-repeater-list="repeater-group">
                                        <div data-repeater-item class="row">
                                            <div class="input-field col s2">
                                                <img src="{{ asset('assets/images/big/auth-bg.jpg') }}" width="150px" height="150px" >
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="height" name="height" type="text" class="required" placeholder=" ">
                                                <label for="height">Height</label>
                
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="height1" name="height1" type="text" class="required" placeholder=" ">
                                                <label for="height1">Height1</label>
                
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="width" name="width" type="text" class="required" placeholder=" ">
                                                <label for="width">Width</label>
                
                                            </div>
                                            <div class="input-field col s1">
                                                <button data-repeater-delete="" class="btn btn-small red" type="button"><i class="material-icons">clear</i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create="" class="btn btn-small indigo m-l-10">Add Roof</button>
                                </div>
                                <div class="row"><br>
                                    <div class="left-align">Roof Decking/Layer:</div>
                                    <div class="row center-align">
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Plywood</span>
                                                </label>
                                            </p>
                                        </div>
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>OSB</span>
                                                </label>
                                            </p>
                                        </div>
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Skip Sheating</span>
                                                </label>
                                            </p>
                                        </div>
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Plank</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="center_spacing" name="center_spacing" type="text" class="required" placeholder=" ">
                                        <label for="center_spacing">On-Center Spacing: </label>
        
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="purlin" name="purlin" type="text" class="required" placeholder=" ">
                                        <label for="purlin">Purlin/Support Structure Sizes, Spacing, Span, Notes: </label>
        
                                    </div>
                                </div>
                                <div class="row"><br>
                                    <div class="left-align">Describe Access to Attic:</div><br>
                                    <div class="row">
                                        <div class="input-field col s3">
                                            <input id="pitch" name="pitch" type="text" class="required" placeholder=" ">
                                            <label for="pitch">Pitch: </label>
            
                                        </div>
                                        <div class="input-field col s3">
                                            <input id="azimuth" name="azimuth" type="text" class="required" placeholder=" ">
                                            <label for="azimuth">Azimuth: </label>
            
                                        </div>
                                        <div class="input-field col s3">
                                            <input id="rafter_size" name="rafter_size" type="text" class="required" placeholder=" ">
                                            <label for="rafter_size">Rafter size: </label>
            
                                        </div>
                                        <div class="input-field col s3">
                                            <input id="roof_material" name="roof_material" type="text" class="required" placeholder=" ">
                                            <label for="roof_material">Roof Material: </label>
            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s3">
                                            <input id="soft_spots" name="soft_spots" type="text" class="required" placeholder=" ">
                                            <label for="soft_spots">Soft Spots: </label>
            
                                        </div>
                                        <div class="input-field col s3">
                                            <input id="bouncy" name="bouncy" type="text" class="required" placeholder=" ">
                                            <label for="bouncy">Bouncy: </label>
            
                                        </div>
                                        <div class="input-field col s3">
                                            <input id="existing_leaks" name="existing_leaks" type="text" class="required" placeholder=" ">
                                            <label for="existing_leaks">Existing leaks: </label>
            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="comp_shingle_layers" name="comp_shingle_layers" type="text" class="required" placeholder=" ">
                                        <label for="comp_shingle_layers">If Comp Shingle how many layers: </label>
        
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="age_of_shingles" name="age_of_shingles" type="text" class="required" placeholder=" ">
                                        <label for="age_of_shingles">Age of Shingles: </label>
        
                                    </div>
                                    <div class="col s12">
                                        <label>Roof Condition :</label>
                                        <p>
                                            <label>
                                                <input name="group3" type="radio" />
                                                <span>Good</span>
                                            </label>
                                            <label>
                                                <input name="group3" type="radio" />
                                                <span>Bad</span>
                                            </label>
                                        </p>
                                    </div>
                                </div>
                            </section>
                            <h6>Step 3</h6>
                            <section>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input id="supply_side_voltage" name="supply_side_voltage" type="text" class="required" placeholder=" ">
                                        <label for="supply_side_voltage">Supply Side Voltage: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="manufacturer_model" name="manufacturer_model" type="text" class="required" placeholder=" ">
                                        <label for="manufacturer_model">Manufacturer and Model: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="main_breaker_rating" name="main_breaker_rating" type="text" class="required" placeholder=" ">
                                        <label for="main_breaker_rating">Main Breaker Rating: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="busbar_rating" name="busbar_rating" type="text" class="required" placeholder=" ">
                                        <label for="busbar_rating">Busbar Rating: </label>
        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input id="meter_reading" name="meter_reading" type="text" class="required" placeholder=" ">
                                        <label for="meter_reading">Meter Reading: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="proposed_point_connection" name="proposed_point_connection" type="text" class="required" placeholder=" ">
                                        <label for="proposed_point_connection">Proposed Point of Connection: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="meter_location" name="meter_location" type="text" class="required" placeholder=" ">
                                        <label for="meter_location">Meter Location: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="tap_possible" name="tap_possible" type="text" class="required" placeholder=" ">
                                        <label for="tap_possible">Tap Possible: </label>
        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input id="breaker_space" name="breaker_space" type="text" class="required" placeholder=" ">
                                        <label for="breaker_space">Breaker Space: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="grounding_method" name="grounding_method" type="text" class="required" placeholder=" ">
                                        <label for="grounding_method">Grounding Method: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="disconnect_type" name="disconnect_type" type="text" class="required" placeholder=" ">
                                        <label for="disconnect_type">Disconnect Type: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="panel_location" name="panel_location" type="text" class="required" placeholder=" ">
                                        <label for="panel_location">Panel Location: </label>
        
                                    </div>
                                </div>
                                <div class="row panel-repeater">
                                    <div data-repeater-list="repeater-group">
                                        <div data-repeater-item class="row">
                                            <div class="input-field col s2">
                                                <label>Sub Panel</label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="manufacturer_model1" type="text" placeholder=" ">
                                                <label for="manufacturer_model1">Manufacturer and Model: </label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="main_breaker_rating1" type="text" placeholder=" ">
                                                <label for="main_breaker_rating1">Main Breaker Rating</label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="busbar_rating1" type="text" placeholder=" ">
                                                <label for="busbar_rating1">Busbar Rating</label>
                                            </div>
                                            <div class="input-field col s1">
                                                <button data-repeater-delete="" class="btn btn-small" type="button"><i class="material-icons">clear</i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create="" class="btn btn-small m-l-10">Add Sub Panel</button>
                                </div>
                            </section>
                            <h6>Step 4</h6>
                            <section>
                                <div class="row">
                                    <div class="col m6">
                                        <div class="input-field col s12">
                                            <input id="beh2" type="text">
                                            <label for="beh2" class="">Behaviour :</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="participants2" type="text">
                                            <label for="participants2" class="">Confidance :</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <select id="typ6" name="city">
                                                <option value="" disabled selected>Select Result</option>
                                                <option value="s">Selected</option>
                                                <option value="r">Rejected</option>
                                                <option value="c">Call Second-time</option>
                                            </select>
                                            <label for="typ6">Result :</label>
                                        </div>
                                    </div>
                                    <div class="col m6">
                                        <div class="input-field col s12">
                                            <input id="decisions7" type="text">
                                            <label for="decisions7" class="">Comments :</label>
                                        </div>
                                        <div class="col s12">
                                            <label>Rate Interviwer :</label>
                                            <p>
                                                <label>
                                                    <input name="group5" type="radio" />
                                                    <span>1 star</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input name="group5" type="radio" />
                                                    <span>2 star</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input name="group5" type="radio" />
                                                    <span>3 star</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input name="group5" type="radio" />
                                                    <span>4 star</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input name="group5" type="radio" />
                                                    <span>5 star</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
            <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
        @endif
</div>
@endsection

@section('js')
    <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script>
     var form = $(".validation-wizard").show();

$(".validation-wizard").steps({
    headerTag: "h6",
    bodyTag: "section",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: "Submit"
    },
    onStepChanging: function(event, currentIndex, newIndex) {
        return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
    },
    onFinishing: function(event, currentIndex) {
        return form.validate().settings.ignore = ":disabled", form.valid()
    },
    onFinished: function(event, currentIndex) {
        swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
    }
}), $(".validation-wizard").validate({
    ignore: "input[type=hidden]",
    errorClass: "red-text",
    successClass: "green-text",
    highlight: function(element, errorClass) {
        $(element).removeClass(errorClass)
    },
    unhighlight: function(element, errorClass) {
        $(element).removeClass(errorClass)
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element)
    },
    rules: {
        email: {
            email: !0
        }
    }
});
function toggleSystemSize(elem) {
            if (elem.checked) {
                document.getElementById('system_size').disabled = false;
                document.getElementById('system_size').value = "";
            } else {
                document.getElementById('system_size').disabled = true;
                document.getElementById('system_size').value = "maximum";
            }
            M.updateTextFields();
        }

        function toggleHOA(elem) {
            if (elem.checked) {
                document.getElementById('installation').disabled = false;
                document.getElementById('installation').value = "none";

                document.getElementById('remarks').disabled = false;
                document.getElementById('remarks').value = "";
            } else {
                document.getElementById('installation').disabled = true;
                document.getElementById('installation').value = "none";

                document.getElementById('remarks').disabled = true;
                document.getElementById('remarks').value = "none";
            }
            M.FormSelect.init(document.querySelector("#installation"));
            M.updateTextFields();
        }
    </script>
@endsection