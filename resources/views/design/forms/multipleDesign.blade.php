@extends('layouts.app')

@php
    $equipment = \App\Equipment::whereIn('type', [\App\Statics\Statics::EQUIPMENT_TYPE_INVERTER, \App\Statics\Statics::EQUIPMENT_TYPE_MODULE, \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR, \App\Statics\Statics::EQUIPMENT_TYPE_RACKING ])->get(['name', 'model', 'type']);
    $monitorSelect = [];
    $inverterSelect = [];
    $moduleSelect = [];
    $rackingSelect = [];
    foreach ($equipment as $item){
        if ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_INVERTER)
            $inverterSelect[$item->name] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MODULE)
            $moduleSelect[$item->name] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_RACKING)
            $rackingSelect[$item->name] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR)
            $monitorSelect[$item->name] = null;
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
<div class="container-fluid">
    <div class="row">
        <div class="col s12">
            <h3 class="prussian-blue-text capitalize">Multiple</h3>
            <h5>Design Request</h5>
        </div>
        <div class="col s12">
            <div class="card" style="margin-top:-2%;">
                <div class="wizard-content" style="padding-bottom:2%;">
                    <form id="array_form" enctype="multipart/form-data" class="validation-wizard wizard-circle m-t-40">
                    @csrf
                    <h6>Aurora Design</h6>
                    <section>
                        <div class="row valign-wrapper">
                            <div class="input-field col s6">
                                <div class="switch center">
                                    <label>
                                        Max System Size
                                        <input type="checkbox" id="hoa" onclick="toggleSystemSize(this)">
                                        <span class="lever"></span>
                                        Limited System Size
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col s6">
                                <input id="system_size" name="system_size" type="text" disabled value="maximum">
                                <label for="system_size">System Size</label>
                                <span class="helper-text">Required</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="annual_usage" name="annual_usage" type="number" validate="annual_usage" class="required">
                                <label for="annual_usage">Annual Usage</label>
                                <span class="helper-text" data-error="Enter a value greater than 1">Required</span>
                            </div>
                            <div class="input-field col s6">
                                <input id="max_offset" name="max_offset" type="number" validate="offset" class="required">
                                <label for="max_offset">Max Offset %</label>
                                <span class="helper-text" data-error="Enter a value between 1 and 200">Required. Max 200%</span>
                            </div>
                        </div>
                        <div class="row valign-wrapper">
                            <div class="input-field col s4">
                                <div class="switch center">
                                <label class="tooltipped" data-position="top" data-delay="10" data-tooltip="House Owner Association"> 
                                        HOA? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
                                        <input type="checkbox" onclick="toggleHOA(this)">
                                        <span class="lever"></span>
                                        Yes
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col s4">
                                <select name="installation" id="installation" disabled>
                                    <option value="none" selected>None</option>
                                    <option value="front roof">Front Roof</option>
                                    <option value="black roof">Back Roof</option>
                                    <option value="garage">Garage</option>
                                </select>
                                <label for="installation">Installation restrictions</label>
                                <span class="helper-text" data-error="This field is required" data-success="">Required</span>
                            </div>
                            <div class="input-field col s4">
                                <input id="remarks" name="remarks" type="text" disabled value="None">
                                <label for="remarks">Remarks</label>
                            </div>
                        </div>
                        <div class="row">
                                            @if($project_type == 'commercial')
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                            <label for="moduleType">Module Type</label>                                            
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="moduleOther_input" style="display:none;">
                                                            <input type="text" name="moduleOther" id="moduleOther" value="moduleOther">
                                                            <label for="moduleOther">Other: </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                            <label for="inverterType">Inverter Type</label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="inverterOther_input" style="display:none;">
                                                            <input type="text" name="inverterOther" id="inverterOther" value="inverterOther">
                                                            <label for="inverterOther">Other: </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                            <label for="rackingType">Racking Type</label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="rackingOther_input" style="display:none;">
                                                            <input type="text" name="rackingOther" id="rackingOther" value="rackingOther">
                                                            <label for="rackingOther">Other: </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "monitor", "data" => $monitorSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="monitorType" name="monitorType" type="text"  value="" placeholder="Mention">
                                                            <label for="monitorType">Monitor Type</label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="monitorOther_input" style="display:none;" >
                                                            <input type="text" name="monitorOther" id="monitorOther" value="monitorOther">
                                                            <label for="monitorOther">Other: </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($project_type == 'residential')
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                            <label for="moduleType">Module Type</label>                                            
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="moduleOther_input" style="display:none;">
                                                            <input type="text" name="moduleOther" id="moduleOther" value="moduleOther">
                                                            <label for="moduleOther">Other: </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                            <label for="inverterType">Inverter Type</label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="inverterOther_input" style="display:none;">
                                                            <input type="text" name="inverterOther" id="inverterOther" value="inverterOther">
                                                            <label for="inverterOther">Other: </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                            <label for="rackingType">Racking Type</label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="rackingOther_input" style="display:none;">
                                                            <input type="text" name="rackingOther" id="rackingOther" value="rackingOther">
                                                            <label for="rackingOther">Other: </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <blockquote style="padding-left: 0.5em">Anything else we should know? Drop it here:</blockquote>
                                <div class="input-field">
                                    <textarea id="notes" name="notes" class="materialize-textarea"></textarea>
                                    <label for="notes">Notes</label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:2%;">
                            <div class="col s12">
                                <h4 class="mt-2">Supporting Documents</h4>
                                <div class="mh-a" id="uppyAurora"></div>
                                <div class="center">
                                    <span class="helper-text imperial-red-text" id="files_error"></span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <h6>Structural Load Letter and Calculation</h6>
                    <section>
                        <div class="row">
                            <div class="col s12 input-field">
                                <select id="roof_type">
                                    <option value="Asphalt">Asphalt</option>
                                    <option value="Cedar Shake">Cedar Shake</option>
                                    <option value="Clay">Clay</option>
                                    <option value="Flat Rolled">Flat Rolled</option>
                                    <option value="Metal - Shingle">Metal - Shingle</option>
                                    <option value="Metal - Standing Seam">Metal - Standing Seam</option>
                                    <option value="Shingles">Shingles</option>
                                </select>
                                <label for="roof_type">Roof Type</label>
                            </div>
                            <div class="col s12">
                                <h5>Arrays</h5>
                                <div class="row col s12">
                                @if($project_type == 'commercial')
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete1', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                <label for="moduleType">Module Type</label>                                            
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="moduleOther_input1" style="display:none;">
                                                    <input type="text" name="moduleOther" id="moduleOther1" value="moduleOther">
                                                    <label for="moduleOther">Other: </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                    <label for="inverterType">Inverter Type</label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="inverterOther_input1" style="display:none;">
                                                    <input type="text" name="inverterOther" id="inverterOther1" value="inverterOther">
                                                    <label for="inverterOther">Other: </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                    <label for="rackingType">Racking Type</label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="rackingOther_input1" style="display:none;">
                                                    <input type="text" name="rackingOther" id="rackingOther1" value="rackingOther">
                                                    <label for="rackingOther">Other: </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "monitor", "data" => $monitorSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="monitorType" name="monitorType" type="text"  value="" placeholder="Mention">
                                                    <label for="monitorType">Monitor Type</label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="monitorOther_input1" style="display:none;" >
                                                    <input type="text" name="monitorOther" id="monitorOther1" value="monitorOther">
                                                    <label for="monitorOther">Other: </label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($project_type == 'residential')
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                    <label for="moduleType">Module Type</label>                                            
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="moduleOther_input1" style="display:none;">
                                                    <input type="text" name="moduleOther" id="moduleOther1" value="moduleOther">
                                                    <label for="moduleOther">Other: </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                    <label for="inverterType">Inverter Type</label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="inverterOther_input1" style="display:none;">
                                                    <input type="text" name="inverterOther" id="inverterOther1" value="inverterOther">
                                                    <label for="inverterOther">Other: </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                    <label for="rackingType">Racking Type</label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="rackingOther_input1" style="display:none;">
                                                    <input type="text" name="rackingOther" id="rackingOther1" value="rackingOther">
                                                    <label for="rackingOther">Other: </label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                <div class="row">
                                    <div class="col s12 m4 input-field">
                                        <input id="panels" name="panels" validate="panels" type="number" value="1" class="required">
                                        <label for="panels"># of Panels</label>
                                        <span class="helper-text">Required. at least 1</span>
                                    </div>
                                    <div class="col s12 m4 input-field">
                                        <input id="tilt" name="tilt" validate="tilt" type="number" value="0" class="required">
                                        <label for="tilt">Tilt</label>
                                        <span class="helper-text" data-error="Enter a value greater than equal to 0 and less than equal to 90">Required 0-90</span>
                                    </div>
                                    <div class="col s12 m4 input-field">
                                        <input id="azimuth" name="azimuth" validate="azimuth" type="number" value="0" class="required">
                                        <label for="azimuth">Azimuth</label>
                                        <span class="helper-text" data-error="Enter a value greater than equal to 0 and less than equal to 360">Required 0-360</span>
                                    </div>
                                    <div class="col s12 center center-align">
                                        <button type="button" class="btn imperial-red-outline-button" id="add_array">Add Array</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <table class="striped table responsive-table white black-text">
                                    <thead>
                                    <tr>
                                        <th class="center">Module</th>
                                        <th class="center">Inverter</th>
                                    @if($project_type == 'commercial')
                                        <th class="center">Racking</th>
                                        <th class="center">Monitor</th>
                                    @else
                                        <th class="center">Racking</th>
                                    @endif
                                        <th class="center"># Panels</th>
                                        <th class="center">Tilt</th>
                                        <th class="center">Azimuth</th>
                                        <th class="center"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="array_table">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:2%;">
                            <div class="col s12"><br>
                                <h4 class="mt-2">Supporting Documents</h4>
                                <div class="mh-a" id="uppyStructural"></div>
                                <div class="center">
                                    <span class="helper-text imperial-red-text" id="files_error"></span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <h6>PE Stamping</h6>
                    <section>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field">
                                    <div class="col s12">
                                    <h4>Supporting Documents(Site Survey Pictures)</h4>
                                    <div class="mh-a" id="uppySupportingDocuments"></div>
                                        <div class="">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field">
                                    <div class="col s12">
                                    <h4>Engineering Plan Set</h4>
                                    <div class="mh-a" id="uppyPermitChange"></div>
                                        <div class="">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field">
                                    <p>
                                        <label>
                                            <input type="checkbox" name="structural_letter" id="structural_letter"/>
                                            <span>Structural Letter</span>
                                        </label>
                                    </p>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field">
                                    <p>
                                        <label>
                                            <input type="checkbox" name="electrical_stamps" id="electrical_stamps"/>
                                            <span>Electrical Stamps</span>
                                        </label>
                                    </p>
                                </div>
                            </div>
                            <div class="col s4">
                                <!-- <div class="input-field">
                                    <input placeholder=" " type="text" class="validate" readonly>
                                    <label for="total_price">Price: </label>
                                </div> -->
                            </div>
                        </div>
                    </section>

                    <h6>Electrical Load Calculations</h6>
                    <section>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <select id="selectItem1" multiple onchange="getSelectedValue('1')">
                                        <option value="" disabled>Choose your option</option>
                                        <option value="Refrigerator_w_freezer">Refrigerator w/freezer</option>
                                        <option value="Freezer-Chest">Freezer - Chest</option>
                                        <option value="Freezer-Upright">Freezer - Upright</option>
                                        <option value="Dishwasher">Dishwasher</option>
                                        <option value="Range">Range</option>
                                        <option value="Oven">Oven</option>
                                        <option value="Microwave">Microwave</option>
                                        <option value="Toaster_oven">Toaster oven</option>
                                        <option value="Coffee_maker">Coffee maker</option>
                                        <option value="Garbage_disposal">Garbage disposal</option>
                                        <option value="Well_pump_1/2_HP">Well pump 1/2 HP</option>
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
                                                <td>
                                                    Monthly kWh
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
                                        <option value="TV-small(up_to_19)">TV - small (up to 19)</option>
                                        <option value="TV-medium(up_to_27)">TV - medium (up to 27)</option>
                                        <option value="TV-large(greater_than_27)">TV - large (greater than 27)</option>
                                        <option value="TV-27_LCD_Flat_Screen">TV - 27 LCD Flat Screen</option>
                                        <option value="TV-42Plasma">TV - 42 Plasma</option>
                                        <option value="VCR/DVD">VCR/DVD</option>
                                        <option value="Cable_box">Cable box</option>
                                        <option value="Satellite_dish">Satellite dish</option>
                                        <option value="Computer_and_printer">Computer and printer</option>
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
                                                <td>
                                                    Monthly KWh
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
                                        <option value="Lighting_of_rooms">Lighting # of rooms</option>
                                        <option value="Outdoor_lighting_175W">Outdoor lighting 175W</option>
                                        <option value="Outdoor_lighting_250W">Outdoor lighting 250W</option>
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
                                                <td>
                                                    Monthly KWh
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
                                        <option value="Water_Heater">Water Heater (# of bedrooms)</option>
                                        <option value="Electric_Dryer_of_loads_per_week">Electric Dryer # of loads per week</option>
                                        <option value="Washing_of_loads">Washing # of loads</option>
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
                                                <td>
                                                    Monthly KWh
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
                                        <option value="Hot_Tub">Hot Tub</option>
                                        <option value="Pool_filter_pump">Pool filter / pump</option>
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
                                                <td>
                                                    Monthly KWh
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
                                        <option value="Air_Purifier">Air Purifier</option>
                                        <option value="Evaporative_Cooler">Evaporative Cooler</option>
                                        <option value="Window_Air_Conditioner">Window Air Conditioner</option>
                                        <option value="Ceiling_Fan">Ceiling Fan</option>
                                        <option value="Box_Fan">Box Fan</option>
                                        <option value="Electric_Blanket">Electric Blanket</option>
                                        <option value="Water_Bed_Heater">Water Bed Heater</option>
                                        <option value="Furnace_Fan">Furnace Fan</option>
                                        <option value="Furn_15KW_1100">Furn 15KW ~ 1100sq.ft</option>
                                        <option value="Furn_20KW_2000">Furn 20KW ~ 2000sq.ft</option>
                                        <option value="Furn_25KW_3000">Furn 25KW ~ 3000sq.ft</option>
                                        <option value="Bassboard_Lin_Feet">Bassboard Lin. Feet</option>
                                        <option value="Wall_Heaters_2000w">Wall Heaters @ 2000w</option>
                                        <option value="1500W_Portable">1500 W Portable</option>
                                        <option value="Heat_pump_fan">Heat pump fan</option>
                                        <option value="Heat_pump_800_1100">Heat pump 800 ~ 1100sq.ft</option>
                                        <option value="Heat_pump_1100_2000">Heat pump 1100 ~ 2000sq.ft</option>
                                        <option value="Heat_pump_2000_3000">Heat pump 2000 ~ 3000sq.ft</option>
                                        <option value="Air_Conditioner">Air Conditioner 1/2 ton</option>
                                        <option value="Air_Conditioner1.5_ton">Air Conditioner 1.5 ton</option>
                                        <option value="Air_Conditioner2_ton">Air Conditioner 2 ton</option>
                                        <option value="Air_Conditioner3_ton">Air Conditioner 3 ton</option>
                                        <option value="Air_Conditioner4_ton">Air Conditioner 4 ton</option>
                                        <option value="Air_Conditioner5_ton">Air Conditioner 5 ton</option>
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
                                                <td>
                                                    Monthly KWh
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItem6">                                                
                                        </tbody>
                                    </table>
                                </div>
                            </div><br><br>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="average_bill1" name="average_bill1" type="text" placeholder=" " value="0">
                                    <label for="average_bill1">Yearly usage: </label>
                                    <input type="button" class="btn btn-primary" onclick="getTotal()" value="Calculate">
                                </div>
                            </div><br>
                        </div>
                        <div class="row">
                            <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
                        </div>
                    </div>
                    </section>
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
            insert(event);
            
        },
        onFinished: function(event, currentIndex) {
        
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

        function validateFields() {

            M.FormSelect.init(document.querySelector("#installation"));
            let form = document.forms["array_form"].getElementsByTagName("input");
            let errors =  0;
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
                if (item.getAttribute('validate') === 'tilt') {
                    if (item.value >= 0 && item.value <= 90)
                        right(item)
                    else
                        wrong(item)
                } else if (item.getAttribute('validate') === 'azimuth') {
                    if (item.value >= 0 && item.value <= 360)
                        right(item)
                    else
                        wrong(item)
                } else if (item.getAttribute('validate') === 'panels') {
                    if (item.value >= 1)
                        right(item)
                    else
                        wrong(item)
                } 
                else {
                    if (!validate.single(item.value, {presence: {allowEmpty: false}}))
                        right(item);
                //     else
                //         wrong(item);
                }
            }

            const inverterOther = document.getElementById('inverterOther');
            // const monitorOther = document.getElementById('monitorOther');
            const moduleOther = document.getElementById('moduleOther');
            const rackingOther = document.getElementById('rackingOther');

            if(inverterOther.value !== "")
                right(inverterOther);
            else{
                inverterOther.classList.value = "valid";
                jsonData[inverterOther.getAttribute("name")] = "No inverter";
            }

            // if(monitorOther.value !== "")
            //     right(monitorOther);
            // else
            //     jsonData[monitorOther.getAttribute("name")] = "No monitor";

            if(moduleOther.value !== ""){
                //alert("Module");
                right(moduleOther);
            }else{
                moduleOther.classList.value = "valid";
                jsonData[moduleOther.getAttribute("name")] = "No module";
            }

            if(rackingOther.value !== "")
                right(rackingOther);
            else{
                rackingOther.classList.value = "valid";
                jsonData[rackingOther.getAttribute("name")] = "No racking";
            }

            const inverterOther1 = document.getElementById('inverterOther1');
            // const monitorOther = document.getElementById('monitorOther');
            const moduleOther1 = document.getElementById('moduleOther1');
            const rackingOther1 = document.getElementById('rackingOther1');

            if(inverterOther1.value !== "")
                right(inverterOther1);
            else{
                inverterOther1.classList.value = "valid";
                jsonData[inverterOther1.getAttribute("name")] = "No inverter";
            }

            // if(monitorOther.value !== "")
            //     right(monitorOther);
            // else
            //     jsonData[monitorOther.getAttribute("name")] = "No monitor";

            if(moduleOther1.value !== ""){
                //alert("Module");
                right(moduleOther1);
            }else{
                moduleOther1.classList.value = "valid";
                jsonData[moduleOther1.getAttribute("name")] = "No module";
            }

            if(rackingOther1.value !== "")
                right(rackingOther1);
            else{
                rackingOther1.classList.value = "valid";
                jsonData[rackingOther1.getAttribute("name")] = "No racking";
            }

            return {
                errors: errors,
                columns: jsonData
            };
            }
            let arrays = {};
            document.getElementById('add_array').addEventListener('click', function () {
            const result = validateFields()
            if (!result.errors) {
                let row = document.createElement('tr');
                row.id = Date.now();
                arrays[row.id] = result.columns;
                row.innerHTML = `<td class="center" data-name="module">${result.columns.module}</td>
                                <td class="center" data-name="inverter">${result.columns.inverter}</td> 
                            @if($project_type == 'commercial')
                                <td class="center" data-name="racking">${result.columns.racking}</td>
                                <td class="center" data-name="monitor">${result.columns.monitor}</td>
                            @else
                                <td class="center" data-name="racking">${result.columns.racking}</td>
                            @endif
                                <td class="center" data-name="panels">${result.columns.panels}</td>
                                <td class="center" data-name="tilt">${result.columns.tilt}</td>
                                <td class="center" data-name="azimuth">${result.columns.azimuth}</td>
                                <td class="center"><button type="button" class="btn imperial-red-outline-button remove" data-id="${row.id}" onclick="remove(this)">Remove</button></td>`;
                document.getElementById('array_table').append(row);
            }
        });

            function remove(elem) {
                const id = elem.getAttribute('data-id')
                document.getElementById(id).remove();
                delete arrays[id];
            }

        function getTotal(){
            total=0;
            itemList=[];
            itemList.push($("#selectItem1").val());
            itemList.push($("#selectItem2").val());
            itemList.push($("#selectItem3").val());
            itemList.push($("#selectItem4").val());
            itemList.push($("#selectItem5").val());
            itemList.push($("#selectItem6").val());
            console.log(itemList);
            console.log(itemList[0][0]);
            
            for(let i=0;i<itemList.length;i++)
            {
                if(itemList[i].length>0)
                {
                    for(let j=0;j<itemList[i].length;j++)
                    {
                        total+=parseInt($("#item"+itemList[i][j]).val())*parseInt($("#vol"+itemList[i][j]).val())*12;
                    }
                }
            }
            console.log("Total : ",total);
            $("#average_bill1").val(total);

        }
        function getSelectedValue(id){
            //alert("hello");
            var items = $("#selectItem"+id).val();
            var tableRow = "";
            for(let i = 0; i < items.length; i++){
                tableRow += "<tr><td><input type='text' name='"+items[i]+"' value='"+items[i]+"' readonly></td></td><td><input type='text' id='item"+items[i]+"' name='quantity[]' value='1'></td><td><input type='text' id='vol"+items[i]+"' name='voltage[]' value=''></td></tr>";
            }
            document.getElementById("selectedItem"+id).innerHTML=tableRow;
            //console.log($("#selectItem").val());
        }
    </script>
    <script>
        function equipment(val, name){
            // alert(val);
            console.log(name[0].id);

            var name = name[0].id;
                if(name == 'inverter' ){
                    if(val == 'Others'){
                        document.getElementById('inverterOther_input').style.display = "block";
                        document.getElementById('inverterOther').value = "";
                    }else{
                        document.getElementById('inverterOther_input').style.display = "none";
                    }
                }else if(name == 'monitor'){
                    if(val == 'Others'){
                        document.getElementById('monitorOther_input').style.display = "block";
                        document.getElementById('monitorOther').value = "";
                    }else{
                        document.getElementById('monitorOther_input').style.display = "none";
                    }
                }else if(name == 'racking'){
                    if(val == 'Others'){
                        document.getElementById('rackingOther_input').style.display = "block";
                        document.getElementById('rackingOther').value = "";
                    }else{
                        document.getElementById('rackingOther_input').style.display = "none";
                    }
                }else if(name == 'module'){
                    if(val == 'Others'){
                        document.getElementById('moduleOther_input').style.display = "block";
                        document.getElementById('moduleOther').value = "";
                    }else{
                        document.getElementById('moduleOther_input').style.display = "none";
                }
            }
        }
            function equipment1(val, name){
            // alert(val);
            console.log(name[1].id);

            var name = name[1].id;
            if(name == 'inverter1'){
                    if(val == 'Others'){
                        document.getElementById('inverterOther_input1').style.display = "block";
                        document.getElementById('inverterOther1').value = "";
                    }else{
                        document.getElementById('inverterOther_input1').style.display = "none";
                    }
                }else if(name == 'monitor1'){
                    if(val == 'Others'){
                        document.getElementById('monitorOther_input1').style.display = "block";
                        document.getElementById('monitorOther1').value = "";
                    }else{
                        document.getElementById('monitorOther_input1').style.display = "none";
                    }
                }else if(name == 'racking1'){
                    if(val == 'Others'){
                        document.getElementById('rackingOther_input1').style.display = "block";
                        document.getElementById('rackingOther1').value = "";
                    }else{
                        document.getElementById('rackingOther_input1').style.display = "none";
                    }
                }else if(name == 'module1'){
                    if(val == 'Others'){
                        document.getElementById('moduleOther_input1').style.display = "block";
                        document.getElementById('moduleOther1').value = "";
                    }else{
                        document.getElementById('moduleOther_input1').style.display = "none";
                }
            }
        }
    </script>
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script src="{{asset('js/validate/validate.min.js')}}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{asset('js/designs/payment.js')}}"></script>

    <script type="text/javascript">
        const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';
        let uppy1 = null;
        let uppy2 = null;
        let uppy3 = null;
        let uppy4 = null;
        let fileCount = 0;
        let filesUploaded = 0;

        // Payment stuff
        const paymentHoldUrl = "{{route('payment.hold')}}";
        const stripePublicKey = "{{env('STRIPE_KEY')}}";


        document.addEventListener("DOMContentLoaded", function () {
            uppy1 = Uppy.Core({
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
                target: `#uppyAurora`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MBs each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy1.on('upload-success', sendFileToDb);

            uppy1.on('file-added', (file) => {
                fileCount++;
            });

            uppy1.on('file-removed', (file) => {
                fileCount--;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            uppy2 = Uppy.Core({
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
                target: `#uppyStructural`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MBs each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy2.on('upload-success', sendFileToDb);

            uppy2.on('file-added', (file) => {
                fileCount++;
            });

            uppy2.on('file-removed', (file) => {
                fileCount--;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            uppy3 = Uppy.Core({
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
                target: `#uppySupportingDocuments`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MBs each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy3.on('upload-success', sendFileToDb);

            uppy3.on('file-added', (file) => {
                fileCount++;
            });

            uppy3.on('file-removed', (file) => {
                fileCount--;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            uppy4 = Uppy.Core({
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
                target: `#uppyPermitChange`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MBs each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy4.on('upload-success', sendFileToDb);

            uppy4.on('file-added', (file) => {
                fileCount++;
            });

            uppy4.on('file-removed', (file) => {
                fileCount--;
            });
        });
    </script>
@endsection