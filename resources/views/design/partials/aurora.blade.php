<div class="row">
    <div class="col s12">
        <span class="prussian-blue-text"><b>Note</b></span>
        <blockquote class="mt-xxs capitalize">
            {{$design->fields['notes']}}
        </blockquote>
    </div>
    <div class="col s12 m6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Annual Usage: </b></span>
            {{ $design->fields['annual_usage'] }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Max Offset: </b></span>
            {{ $design->fields['max_offset'] }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Installation restrictions: </b></span>
            <span class="capitalize">{{($design->fields['installation']?$design->fields['installation']:"-")}}</span>
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Created On: </b></span>
            {{ $design->created_at->format('F dS Y - h:i A')}} (UTC)
        </div>
    </div>
    <div class="col s12 m6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>System Size: </b></span>
            <span class="capitalize">{{ $design->fields['system_size'] }}</span>
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>HOA?: </b></span>
            {{$design->fields['hoa']?"Yes":"No" }}
        </div>
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Remarks: </b></span>
            <span class="capitalize">{{ $design->fields['remarks'] }}</span>
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
</div>
