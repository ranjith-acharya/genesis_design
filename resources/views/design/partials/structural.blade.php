<div class="row">
    <div class="col s12">
        <div class="mb-xxxs">
            <span class="prussian-blue-text"><b>Roof Type: </b></span>
            {{$design->fields['roofType']}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h5>Arrays</h5>
        <table class="striped">
            <thead>
            <tr>
                <th class="center">Inverter</th>
                <th class="center">Module</th>
                @if($design->project->type->name == 'commercial')
                    <th class="center">Racking</th>    
                    <th class="center">Monitor</th>
                @else
                    <th class="center">Racking</th>
                @endif
                <th class="center"># Panels</th>
                <th class="center">Tilt</th>
                <th class="center">Azimuth</th>
            </tr>
            </thead>
            <tbody id="array_table">
            @foreach($design->fields['arrays'] as $array)
                <tr>
                    <td class="center" data-name="inverter">{{$array['inverter']}}</td>
                    <td class="center" data-name="module">  {{$array['module']}}</td>
                    @if($design->project->type->name == 'commercial')
                        <td class="center" data-name="racking">{{$array['racking']}}</td>
                        <td class="center" data-name="monitor">{{ $array['monitor'] }}</td>
                    @else
                        <td class="center" data-name="racking">{{$array['racking']}}</td>
                    @endif
                    <td class="center" data-name="panels">  {{$array['panels']}}</td>
                    <td class="center" data-name="tilt">    {{$array['tilt']}}</td>
                    <td class="center" data-name="azimuth"> {{$array['azimuth']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
