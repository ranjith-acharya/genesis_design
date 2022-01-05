@extends('layouts.app')

@section('css')
    <link href="{{asset('uppy/uppy.min.css')}}" rel="stylesheet">
@endsection

@section('title', "New $type->name design")

@php
    $equipment = \App\Equipment::whereIn('type', [\App\Statics\Statics::EQUIPMENT_TYPE_INVERTER, \App\Statics\Statics::EQUIPMENT_TYPE_MODULE, \App\Statics\Statics::EQUIPMENT_TYPE_RACKING, \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR ])->get(['name', 'model', 'type']);
    $monitorSelect = [];
    $inverterSelect = [];
    $moduleSelect = [];
    $rackingSelect = [];
    foreach ($equipment as $item){
        if ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_INVERTER)
            $inverterSelect[$item->name . " | " . $item->model] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MODULE)
            $moduleSelect[$item->name . " | " . $item->model] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR)
            $monitorSelect[$item->name . " | " . $item->model] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_RACKING)
            $rackingSelect[$item->name . " | " . $item->model] = null;
    }
@endphp

@section('content')
<style>
.uppy-Dashboard-inner{
    margin-right: auto!important;
    margin-left: auto!important;
    height: 450px !important;
    width: 700px !important;
}    
</style>
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
                            <h6>Basic Information</h6>
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
                                            <input id="remarks" name="remarks" type="text"  value="" placeholder="If any">
                                            <label for="remarks">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        @component('components.autocomplete', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                            <label for="inverterType">Inverter Type</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        @component('components.autocomplete', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                            <label for="rackingType">Racking Type</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6 input-field">
                                        @component('components.autocomplete', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                            <label for="moduleType">Module Type</label>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- <div class="col s6">
                                        @component('components.autocomplete', ["name" => "monitor", "data" => $monitorSelect])@endcomponent
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="monitorType" name="monitorType" type="text"  value="" placeholder="Mention">
                                            <label for="monitorType">Monitor Type</label>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <br><br>
                                </div>
                            </section>
                            <h6>Roof Information</h6>
                            <section>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="utility" name="utility" type="text"  value="" placeholder=" ">
                                        <label for="utility">Utility</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="tree_cutting" name="tree_cutting" type="text"  value="" placeholder=" ">
                                            <label for="tree_cutting">Tree Cutting</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="re_roofing" name="re_roofing" type="text"  value="" placeholder=" ">
                                            <label for="re_roofing">Re-Roofing</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <div class="switch center">
                                                <label>
                                                    Service Upgrade&emsp;&emsp;No
                                                    <input type="checkbox">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="others" name="others" type="text"  value="" placeholder=" ">
                                            <label for="others">Others</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
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
                                </div> -->
                                <div class="row image-repeater">
                                    <div data-repeater-list="repeater-group">
                                        <div data-repeater-item class="row">
                                            <div class="input-field col s4">
                                                <img src="{{ asset('assets/images/big/roof.jpg') }}" width="320px" height="150px" >
                                            </div>
                                            <div class="input-field col s2" style="margin-top:5%;">
                                                <input id="height" name="height" type="text" class="required" placeholder="(feet)">
                                                <label for="height">Height</label>
                                            </div>
                                            <div class="input-field col s2" style="margin-top:5%;">
                                                <input id="height1" name="height1" type="text" class="required" placeholder="(feet)">
                                                <label for="height1">Height1</label>
                                            </div>
                                            <div class="input-field col s2" style="margin-top:5%;">
                                                <input id="width" name="width" type="text" class="required" placeholder="(feet)">
                                                <label for="width">Width</label>
                                            </div>
                                            <div class="input-field col s1" style="margin-top:5%;">
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
                                    <div class="input-field col s12">
                                        <input id="roofDecking_LayerThickness" name="roofDecking_LayerThickness" type="text"  value="" placeholder=" ">
                                        <label for="roofDecking_LayerThickness">Roof Decking / Layer Thickess</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="center_spacing" name="center_spacing" type="text" class="required" placeholder="(inches)">
                                        <label for="center_spacing">On-Center Spacing: </label>
        
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="purlin" name="purlin" type="text" class="required" placeholder="(inches)">
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
                                            <select>
                                                <option value="" disabled selected>Choose your option</option>
                                                <option value="Asphault">Asphault</option>
                                                <option value="Metal">Metal</option>
                                                <option value="Shingle">Shingle</option>
                                                <option value="Tile">Tile</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <label for="roof_material">Roof Material: </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Soft Spots&emsp;&emsp;No
                                                    <input type="checkbox">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Bouncy&emsp;&emsp;No
                                                    <input type="checkbox">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Existing Leaks&emsp;&emsp;No
                                                    <input type="checkbox">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Vaulted Ceiling&emsp;&emsp;No
                                                    <input type="checkbox">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <select>
                                            <option value="" disabled selected>Choose your option</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="more than 3">More than 3</option>
                                        </select>
                                        <label for="comp_shingle_layers">If Comp Shingle how many layers: </label>
        
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="age_of_shingles" name="age_of_shingles" type="text" class="required" placeholder="(years)">
                                        <label for="age_of_shingles">Age of Shingles: </label>
        
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field">
                                            <div class="switch center">
                                                <label>
                                                    Roof Condition&emsp;&emsp;Bad
                                                    <input type="checkbox">
                                                    <span class="lever"></span>
                                                    Good
                                                </label>
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
                                    <!-- <div class="col m4">
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
                                    </div> -->
                                    </div>
                                </div>
                            </section>
                            <h6>Electrical Information</h6>
                            <section>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input id="supply_side_voltage" name="supply_side_voltage" type="text" placeholder=" ">
                                        <label for="supply_side_voltage">Supply Side Voltage: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="manufacturer_model" name="manufacturer_model" type="text" placeholder=" ">
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
                                        <input id="meter_reading" name="meter_reading" type="text" placeholder=" ">
                                        <label for="meter_reading">Meter Reading: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="proposed_point_connection" name="proposed_point_connection" type="text" placeholder=" ">
                                        <label for="proposed_point_connection">Proposed Point of Connection: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="meter_location" name="meter_location" type="text" class="required" placeholder=" ">
                                        <label for="meter_location">Meter Location: </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <div class="switch center">
                                            <label>
                                                Tap Possible&emsp;&emsp;No
                                                <input type="checkbox">
                                                <span class="lever"></span>
                                                Yes
                                            </label>
                                        </div>
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
                                                <input id="manufacturer_model1" type="text" placeholder="">
                                                <label for="manufacturer_model1">Manufacturer and Model: </label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="main_breaker_rating1" type="text" placeholder="">
                                                <label for="main_breaker_rating1">Main Breaker Rating</label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="busbar_rating1" type="text" placeholder="">
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
                            <h6>Utility Bills/Electrical Load</h6>
                            <section>
                                <div class="row">
                                    <h4 class="mt-2">Upload Bill</h4>
                                    <div class="col s12">
                                        <div class="mh-a" id="uppyBill"></div>
                                        <div class="center">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div><br>
                                <h3 class="center-align">- OR - </h3><br>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="average_bill" name="average_bill" type="text" placeholder=" ">
                                        <label for="average_bill">Yearly usage: </label>
                                    </div>
                                </div><br>
                                <h3 class="center-align">- OR - </h3><br>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem1" multiple onchange="getSelectedValue('1')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Refrigerator w/freezer">Refrigerator w/freezer</option>
                                                <option value="Freezer - Chest">Freezer - Chest</option>
                                                <option value="Freezer - Upright">Freezer - Upright</option>
                                                <option value="Dishwasher">Dishwasher</option>
                                                <option value="Range">Range</option>
                                                <option value="Oven">Oven</option>
                                                <option value="Microwave">Microwave</option>
                                                <option value="Toaster oven">Toaster oven</option>
                                                <option value="Coffee maker">Coffee maker</option>
                                                <option value="Garbage disposal">Garbage disposal</option>
                                                <option value="Well pump 1/2 HP">Well pump 1/2 HP</option>
                                            </select>
                                            <label>Kitchen</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem1">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem2" multiple onchange="getSelectedValue('2')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Stereo">Stereo</option>
                                                <option value="TV - small (up to 19)">TV - small (up to 19)</option>
                                                <option value="TV - medium (up to 27)">TV - medium (up to 27)</option>
                                                <option value="TV - large (greater than 27)">TV - large (greater than 27)</option>
                                                <option value="TV - 27 LCD Flat Screen">TV - 27 LCD Flat Screen</option>
                                                <option value="TV - 42 Plasma">TV - 42 Plasma</option>
                                                <option value="VCR/DVD">VCR/DVD</option>
                                                <option value="Cable box">Cable box</option>
                                                <option value="Satellite dish">Satellite dish</option>
                                                <option value="Computer and printer">Computer and printer</option>
                                            </select>
                                            <label>Entertainment</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem2">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem3" multiple onchange="getSelectedValue('3')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Lighting # of rooms">Lighting # of rooms</option>
                                                <option value="Outdoor lighting 175W">Outdoor lighting 175W</option>
                                                <option value="Outdoor lighting 250W">Outdoor lighting 250W</option>
                                            </select>
                                            <label>Lighting</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem3">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem4" multiple onchange="getSelectedValue('4')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Water Heater (# of bedrooms)">Water Heater (# of bedrooms)</option>
                                                <option value="Electric Dryer # of loads per week">Electric Dryer # of loads per week</option>
                                                <option value="Washing # of loads">Washing # of loads</option>
                                            </select>
                                            <label>Laundry</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem4">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem5" multiple onchange="getSelectedValue('5')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Hot Tub">Hot Tub</option>
                                                <option value="Pool filter / pump">Pool filter / pump</option>
                                            </select>
                                            <label>Outdoor Equipment</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem5">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem6" multiple onchange="getSelectedValue('6')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Dehumidifier">Dehumidifier</option>
                                                <option value="Humidifier">Humidifier</option>
                                                <option value="Air Purifier">Air Purifier</option>
                                                <option value="Evaporative Cooler">Evaporative Cooler</option>
                                                <option value="Window Air Conditioner">Window Air Conditioner</option>
                                                <option value="Ceiling Fan">Ceiling Fan</option>
                                                <option value="Box Fan">Box Fan</option>
                                                <option value="Electric Blanket">Electric Blanket</option>
                                                <option value="Water Bed Heater">Water Bed Heater</option>
                                                <option value="Furnace Fan">Furnace Fan</option>
                                                <option value="Furn 15KW ~ 1100sq.ft">Furn 15KW ~ 1100sq.ft</option>
                                                <option value="Furn 20KW ~ 2000sq.ft">Furn 20KW ~ 2000sq.ft</option>
                                                <option value="Furn 25KW ~ 3000sq.ft">Furn 25KW ~ 3000sq.ft</option>
                                                <option value="Bassboard Lin. Feet">Bassboard Lin. Feet</option>
                                                <option value="Wall Heaters @ 2000w">Wall Heaters @ 2000w</option>
                                                <option value="1500 W Portable">1500 W Portable</option>
                                                <option value="Heat pump fan">Heat pump fan</option>
                                                <option value="Heat pump 800 ~ 1100sq.ft">Heat pump 800 ~ 1100sq.ft</option>
                                                <option value="Heat pump 1100 ~ 2000sq.ft">Heat pump 1100 ~ 2000sq.ft</option>
                                                <option value="Heat pump 2000 ~ 3000sq.ft">Heat pump 2000 ~ 3000sq.ft</option>
                                                <option value="Air Conditioner 1/2 ton">Air Conditioner 1/2 ton</option>
                                                <option value="Air Conditioner 1.5 ton">Air Conditioner 1.5 ton</option>
                                                <option value="Air Conditioner 2 ton">Air Conditioner 2 ton</option>
                                                <option value="Air Conditioner 3 ton">Air Conditioner 3 ton</option>
                                                <option value="Air Conditioner 4 ton">Air Conditioner 4 ton</option>
                                                <option value="Air Conditioner 5 ton">Air Conditioner 5 ton</option>
                                            </select>
                                            <label>Comfort controls</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem6">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <h6>Upload Supporting Documents</h6>
                            <section>
                            <h4 class="mt-2">Supporting Documents</h4><br>
                                <div class="row">
                                    <div class="col s10">
                                        <div class="mh-a" id="uppy"></div>
                                        <div class="center">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                    <div class="col s2">
                                        <a href="{{ asset('assets/document/Site_Survey_Document.pdf') }}" download>
                                            <button type="button" class="btn btn-small indigo"><i class="ti-cloud-down left"></i>Download</button>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                                            <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
                                        @endif
                                    </div>
                                </div>
                            </section>
                            <br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
    <script>
        function getSelectedValue(id){
            //alert("hello");
            var items = $("#selectItem"+id).val();
            var tableRow = "";
            for(let i = 0; i < items.length; i++){
                tableRow += "<tr><td><input type='text' name='"+items[i]+"' value='"+items[i]+"' readonly></td></td><td><input type='text' name='quantity[]' value='1'></td></tr>";
            }
            document.getElementById("selectedItem"+id).innerHTML=tableRow;
            //console.log($("#selectItem").val());
        }
    </script>
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script src="{{asset('js/validate/validate.min.js')}}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{asset('js/designs/payment.js')}}"></script>

    <script type="text/javascript">
        const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';
        let uppy = null;
        let fileCount = 0;
        let filesUploaded = 0;

        // Payment stuff
        const paymentHoldUrl = '{{route('payment.hold')}}';
        const stripePublicKey = '{{env('STRIPE_KEY')}}';


        document.addEventListener("DOMContentLoaded", function () {
            uppy = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppy`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MBs each"
            }).use(Uppy.XHRUpload, {
                endpoint: '{{ env('SUN_STORAGE') }}/file',
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy.on('upload-success', sendFileToDb);

            uppy.on('file-added', (file) => {
                fileCount++;
            });

            uppy.on('file-removed', (file) => {
                fileCount--;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            uppy = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppyBill`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MBs each"
            }).use(Uppy.XHRUpload, {
                endpoint: '{{ env('SUN_STORAGE') }}/file',
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy.on('upload-success', sendFileToDb);

            uppy.on('file-added', (file) => {
                fileCount++;
            });

            uppy.on('file-removed', (file) => {
                fileCount--;
            });
        });

        const sendFileToDb = function (file, response) {

            fetch("{{route('design.file.attach')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                },
                body: JSON.stringify({
                    path: response.body.name,
                    system_design_id: file.meta.system_design_id,
                    content_type: file.meta.type
                })
            }).then(async response => {
                return {db_response: await response.json(), "status": response.status};
            }).then(response => {
                if (response.status === 200 || response.status === 201) {
                    console.log(response.db_response);
                    M.toast({
                        html: "Images uploaded",
                        classes: "steel-blue"
                    });
                    filesUploaded++;
                    if (filesUploaded === fileCount)
                        window.location = '{{route('design.list', $project_id)}}';
                } else {
                    M.toast({
                        html: "There was a error uploading images. Please try again.",
                        classes: "imperial-red"
                    });
                    console.error(response);
                }
            }).catch(err => {
                M.toast({
                    html: "There was a network error uploading images. Please try again.",
                    classes: "imperial-red"
                });
                console.error(err);
            });

        };

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

        function validateFields() {

            M.FormSelect.init(document.querySelector("#installation"));
            let form = document.forms["aurora_form"].getElementsByTagName("input");
            let errors = 0;
            let jsonData = {};

            //Make the thing green
            function right(item) {
                item.classList.remove("invalid");
                item.classList.add("valid");

                if (item.getAttribute("name"))
                    jsonData[item.getAttribute("name")] = item.value;
            }

            //Make the thing red
            function wrong(item) {
                item.classList.remove("valid");
                item.classList.add("invalid");
                errors++;
            }

            // All the non-select inputs
            for (let item of form) {
                if (item.getAttribute('validate') === 'offset') {
                    if (item.value >= 1 && item.value <= 200)
                        right(item)
                    else
                        wrong(item)
                } else if (item.getAttribute('validate') === 'annual_usage') {
                    if (item.value >= 1)
                        right(item)
                    else
                        wrong(item)
                } else {
                    if (!validate.single(item.value, {presence: {allowEmpty: false}}))
                        right(item);
                    else
                        wrong(item);
                }
            }

            const notes = document.getElementById('notes');
            if (notes.value !== "")
                right(notes);
            else
                jsonData[notes.getAttribute("name")] = "No notes";


            const installation = M.FormSelect.getInstance(document.querySelector("#installation"));
            if (installation.getSelectedValues()[0] === "") wrong(installation.wrapper);
            else {
                right(installation.wrapper);
                jsonData["installation"] = installation.getSelectedValues()[0];
            }

            jsonData["hoa"] = document.getElementById('hoa').checked;
            jsonData["project_id"] = "{{$project_id}}";

            return {
                errors: errors,
                columns: jsonData
            };
        }

        // insert files and project
        function insert(elem) {

            elem.disabled = true;
            const validationResult = validateFields();
            document.getElementById('stripe_card').style.display = 'none'

            function uploadFiles(system_design_id) {
                uppy.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy.upload();
            }

            if (validationResult.errors === 0) {

                holdPayment('{{$type->name}}').then(resp => {
                    console.log(resp)
                    if (resp) {
                        if (resp.error) {
                            document.getElementById('stripe_error').innerText = resp.error.message;
                            elem.disabled = false;
                            document.getElementById('stripe_card').style.display = 'block'
                        } else {

                            validationResult.columns['stripe_payment_code'] = resp.paymentIntent.id;
                            fetch("{{route('design.aurora')}}", {
                                method: 'post',
                                body: JSON.stringify(validationResult.columns),
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                                }
                            }).then(async response => {
                                return {db_response: await response.json(), "status": response.status};
                            }).then(response => {
                                if (response.status === 200 || response.status === 201) {
                                    console.log(response.db_response)
                                    uploadFiles(response.db_response.id);
                                    if (fileCount === 0)
                                        window.location = '{{route('design.list', $project_id)}}';
                                    M.toast({
                                        html: "Design inserted",
                                        classes: "steel-blue"
                                    });
                                } else {
                                    M.toast({
                                        html: "There was a error inserting the design. Please try again.",
                                        classes: "imperial-red"
                                    });
                                    console.error(response);
                                    elem.disabled = false;
                                }
                            }).catch(err => {
                                M.toast({
                                    html: "There was a network error. Please try again.",
                                    classes: "imperial-red"
                                });
                                console.error(err);
                                elem.disabled = false;
                            });
                        }
                    } else {
                        console.log("error")
                        elem.disabled = false;
                    }
                })

            } else {
                M.toast({
                    html: "There are some errors in your form, please fix them and try again",
                    classes: "imperial-red"
                });
                elem.disabled = false;
            }
        }
    </script>
@endsection