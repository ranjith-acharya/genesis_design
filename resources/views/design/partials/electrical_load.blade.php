<div class="row">    
    {{-- @if($design->fields['average_bill'] == "")
        
    @else
    <div class="col s6">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Yearly Usage: </b></span>
            {{ $design->fields['average_bill'] }}&nbsp;kWH
        </div>
    </div>
    @endif --}}

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