<!-- <div class="row">
    <div class="col s12">
        <span class="prussian-blue-text"><b>Note</b></span>
        <blockquote class="mt-xxs">
            {{$design}}
        </blockquote>
    </div>
</div> -->
<div class="row">
    <div class="col s12"><h4 class="prussian-blue-text">Basic Information</h4></div>
    <div class="col s4">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>System Size: </b></span>
            {{$design->fields['system_size']}}
        </div>
    </div>
    <div class="col s4">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Installation Restricitons: </b></span>
            {{$design->fields['installation']}}
        </div>
    </div>
    <div class="col s4">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Remarks?: </b></span>
            {{$design->fields['remarks']}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Inverter: </b></span>
            {{ (isset($design->fields['inverter']))?$design->fields['inverter']=="Others"?$design->fields['inverterOther']:$design->fields['inverter']:"-" }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Monitor: </b></span>
            {{ (isset($design->fields['monitor']))?$design->fields['monitor']=="Others"?$design->fields['monitorOther']:$design->fields['monitor']:"-" }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Module: </b></span>
            {{(isset($design->fields['module']))?$design->fields['module']=="Others"?$design->fields['moduleOther']:$design->fields['module']:"-" }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Racking: </b></span>
            {{(isset($design->fields['racking']))?$design->fields['racking']=="Others"?$design->fields['rackingOther']:$design->fields['racking']:"-" }}
        </div>
    </div>
</div><hr>
<div class="row">
    <div class="col s12"><h4 class="prussian-blue-text">Roof Information</h4></div>
    @if($design->fields['utility'] == "")
        
    @else
    <div class="col s12">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Utility: </b></span>
            {{ $design->fields['utility'] }}
        </div>
    </div>
    @endif
    
    @if($design->fields['tree_cutting'] == "")
        
    @else
    <div class="col s3">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Tree Cutting: </b></span>
            {{ $design->fields['tree_cutting'] }}
        </div>
    </div>
    @endif

    @if($design->fields['re_roofing'] == "")
        
    @else
    <div class="col s3">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Re-Roofing: </b></span>
            {{ $design->fields['re_roofing'] }}
        </div>
    </div>
    @endif

    @if($design->fields['service_upgrade'] == "")
        
    @else
    <div class="col s3">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Surface Upgrade: </b></span>
            {{ $design->fields['service_upgrade']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['others'] == "")
        
    @else
    <div class="col s3">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Others: </b></span>
            {{ $design->fields['others'] }}
        </div>
    </div>
    @endif
    <div class="row center">
    @if($design->fields['array']['overhang'] && $design->fields['array']['width'] && $design->fields['array']['height'] == "")

    @else
    <div class="col s12">
        <table class="striped">
            <thead>
                <tr>
                    <td class="center"><b>Overhang (feet)</b></td>
                    <td class="center"><b>Width (feet)</b></td>
                    <td class="center"><b>Height (feet)</b></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center">{{ $design->fields['array']['overhang'] }}</td>
                    <td class="center">{{ $design->fields['array']['width'] }}</td>
                    <td class="center">{{ $design->fields['array']['height'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    </div><br>
    <div class="col s12"><h5 class="card-title">Roof Decking/Layer </h5></div>
    @if($design->fields['plywood'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Plywood: </b></span>
            {{ $design->fields['plywood']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['osb'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>OSB: </b></span>
            {{ $design->fields['osb']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['skip_sheating'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Skip Sheating: </b></span>
            {{ $design->fields['skip_sheating']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['plank'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Plank: </b></span>
            {{ $design->fields['plank']?"Yes":"No" }}
        </div>
    </div>
    @endif
    
    @if($design->fields['roofDecking_LayerThickness'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Roof Decking / Layer Thickess: </b></span>
            {{ $design->fields['roofDecking_LayerThickness'] }}
        </div>
    </div>
    @endif

    @if($design->fields['center_spacing'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Center Spacing: </b></span>
            {{ $design->fields['center_spacing']}}
        </div>
    </div>
    @endif
    
    @if($design->fields['purlin'] == "")
        
    @else
    <div class="col s12">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Purlin/Support Structure Sizes, Spacing, Span, Notes: </b></span>
            {{ $design->fields['purlin'] }}
        </div>
    </div>
    @endif
    <div class="col s12"><br><h5 class="card-title">Describe Access to Attic </h5></div>
    @if($design->fields['pitch'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Pitch: </b></span>
            {{ $design->fields['pitch'] }}
        </div>
    </div>
    @endif

    @if($design->fields['azimuth'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Azimuth: </b></span>
            {{ $design->fields['azimuth'] }}
        </div>
    </div>
    @endif

    @if($design->fields['rafter_size'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Rafter Size: </b></span>
            {{ $design->fields['rafter_size'] }}
        </div>
    </div>
    @endif

    @if($design->fields['roofMaterialOption'] == "")
        
    @elseif($design->fields['roofMaterialOption'] == "Others")
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Roof Material: </b></span>
            {{ $design->fields['other_roof_material'] }}
        </div>
    </div>
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Roof Material: </b></span>
            {{ $design->fields['roofMaterialOption'] }}
        </div>
    </div>
    @endif

    @if($design->fields['soft_spots'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Soft Spots: </b></span>
            {{ $design->fields['soft_spots']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['bouncy'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Bouncy: </b></span>
            {{ $design->fields['bouncy']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['existing_leaks'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Existing Leaks: </b></span>
            {{ $design->fields['existing_leaks']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['vaulted_ceiling'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Vaulted Ceiling: </b></span>
            {{ $design->fields['vaulted_ceiling']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['comp_shingle_layers'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Comp Shingle Layers: </b></span>
            {{ $design->fields['comp_shingle_layers'] }}
        </div>
    </div>
    @endif
    
    @if($design->fields['age_of_shingles'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Age of Shingles: </b></span>
            {{ $design->fields['age_of_shingles'] }}
        </div>
    </div>
    @endif

    @if($design->fields['roof_condition'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Roof Condition: </b></span>
            {{ $design->fields['roof_condition']?"Good":"Bad" }}
        </div>
    </div>
    @endif
    <div class="col s12"><br><h5 class="card-title">Finished / Vaulted Ceiling Protocol</h5></div>
    @if($design->fields['access_attic_vent'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Access from Attic vent?: </b></span>
            {{ $design->fields['access_attic_vent']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['stud_finder'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Stud Finder: </b></span>
            {{ $design->fields['stud_finder']?"Yes":"No" }}
        </div>
    </div>
    @endif
</div><hr>
<div class="row">
    <div class="col s12"><h4 class="prussian-blue-text">Electrical Information</h4></div>
    @if($design->fields['supply_side_voltage'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Supply Side Voltage: </b></span>
            {{ $design->fields['supply_side_voltage'] }}
        </div>
    </div>
    @endif

    @if($design->fields['manufacturer_model'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Manufacturer Model: </b></span>
            {{ $design->fields['manufacturer_model'] }}
        </div>
    </div>
    @endif

    @if($design->fields['main_breaker_rating'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Main Breaker Rating: </b></span>
            {{ $design->fields['main_breaker_rating'] }}
        </div>
    </div>
    @endif

    @if($design->fields['busbar_rating'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Busbar Rating: </b></span>
            {{ $design->fields['busbar_rating'] }}
        </div>
    </div>
    @endif

    @if($design->fields['meter_no'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Meter No: </b></span>
            {{ $design->fields['meter_no'] }}
        </div>
    </div>
    @endif

    @if($design->fields['proposed_point_connection'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Proposed Point Connection: </b></span>
            {{ $design->fields['proposed_point_connection'] }}
        </div>
    </div>
    @endif

    @if($design->fields['meter_location'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Meter Location: </b></span>
            {{ $design->fields['meter_location'] }}
        </div>
    </div>
    @endif

    @if($design->fields['tap_possible'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Tap Possible: </b></span>
            {{ $design->fields['tap_possible']?"Yes":"No" }}
        </div>
    </div>
    @endif

    @if($design->fields['breaker_space'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Breaker Space: </b></span>
            {{ $design->fields['breaker_space'] }}
        </div>
    </div>
    @endif

    @if($design->fields['grounding_method'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Grounding Method: </b></span>
            {{ $design->fields['grounding_method'] }}
        </div>
    </div>
    @endif

    @if($design->fields['disconnect_type'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Disconnect Type: </b></span>
            {{ $design->fields['disconnect_type'] }}
        </div>
    </div>
    @endif

    @if($design->fields['panel_location'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Panel Location: </b></span>
            {{ $design->fields['panel_location'] }}
        </div>
    </div>
    @endif
</div><hr>
<div class="row">
    <div class="col s12"><h4 class="prussian-blue-text">Utility Bills / Electric Load</h4></div>
    @if($design->fields['average_bill'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Yearly Usage: </b></span>
            {{ $design->fields['average_bill'] }}
        </div>
    </div>
    @endif

    @if($design->fields['average_bill1'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Calculated Usage: </b></span>
            {{ $design->fields['average_bill1'] }}&nbsp;kWH
        </div>
    </div>
    @endif
</div><hr>