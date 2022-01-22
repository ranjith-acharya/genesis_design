@extends('layouts.app')

@section('title', "New $type->name design")

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
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR)
            $monitorSelect[$item->name] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_RACKING)
            $rackingSelect[$item->name] = null;
    }
@endphp

@section('css')
    <link href="{{asset('uppy/uppy.min.css')}}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script src="{{asset('js/validate/validate.min.js')}}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{asset('js/designs/payment.js')}}"></script>
    <script type="text/javascript">
        // Payment stuff
        const paymentHoldUrl = "{{route('payment.hold')}}";
        const stripePublicKey = "{{env('STRIPE_KEY')}}";

        //local vars
        let arrays = {};
        const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';
        let uppy = null;
        let fileCount = 0;
        let filesUploaded = 0;

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
                endpoint: "{{ env('SUN_STORAGE') }}/file",
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
                    toastr.success('Images uploaded!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    // M.toast({
                    //     html: "Images uploaded",
                    //     classes: "green"
                    // });
                    filesUploaded++;
                    if (filesUploaded === fileCount)
                        window.location = "{{route('design.list', $project_id)}}";
                } else {
                    toastr.error('There was a error uploading images. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    console.error(response);
                }
            }).catch(err => {
                toastr.error('There was a network error uploading images. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                console.error(err);
            });

        };

        function validateFields() {

            M.FormSelect.init(document.querySelector("#installation"));
            let form = document.forms["array_form"].getElementsByTagName("input");
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
                } else {
                    if (!validate.single(item.value, {presence: {allowEmpty: false}}))
                        right(item);
                    else
                        wrong(item);
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

            return {
                errors: errors,
                columns: jsonData
            };
        }

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

        function insert(elem) {

            const roofType = M.FormSelect.getInstance(document.querySelector("#roof_type"));
            const fields = {arrays: [],roofType: roofType.getSelectedValues()[0]};
            for (const key in arrays) {
                fields.arrays.push(arrays[key]);
            }

            elem.disabled = true;
            document.getElementById('stripe_card').style.display = 'none'

            function uploadFiles(system_design_id) {
                uppy.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy.upload();
            }

            if (fields.arrays.length > 0) {
                holdPayment('{{$type->name}}').then(resp => {
                    console.log(resp)
                    if (resp) {
                        if (resp.error) {
                            document.getElementById('stripe_error').innerText = resp.error.message;
                            elem.disabled = false;
                            document.getElementById('stripe_card').style.display = 'block'
                        } else {

                            axios("{{route('design.structural_load')}}", {
                                method: 'post',
                                data: {
                                    project_id: "{{$project_id}}",
                                    fields: fields,
                                    stripe_payment_code: resp.paymentIntent.id
                                },
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                                }
                            }).then(response => {
                                if (response.status === 200 || response.status === 201) {
                                    console.log(response.data)
                                    uploadFiles(response.data.id);
                                    if (fileCount === 0)
                                        window.location = "{{route('design.list', $project_id)}}";
                                        toastr.success('Design inserted!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                                        // M.toast({
                                        //     html: "Design inserted",
                                        //     classes: "green"
                                        // });
                                } else {
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
                    } else {
                        console.log("error")
                        elem.disabled = false;
                    }
                })
            } else {
                toastr.error('Please insert at least one array before submitting the design!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                elem.disabled = false;
            }
        }
    </script>
    <script>
    function equipment(val, name){
        //alert(val);
        //console.log(name.id);
        var name = name.id;
            if(name == 'inverter'){
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
</script>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col s12">
                <h3 class="prussian-blue-text capitalize">{{$type->name}}</h3>
                <h5>Design Request</h5>
            </div>
        </div>
        <form id="array_form" class="card card-content" style="padding-top:2%;padding-bottom:2%;">
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
                                @component('components.autocomplete', ["name" => "module", "data" => $moduleSelect])@endcomponent
                            </div>
                            <div class="col s4">
                                <div class="input-field col s12">
                                    <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                    <label for="moduleType">Module Type</label>                                            
                                </div>
                                <div class="col s4">
                                    <div class="input-field col s12" id="moduleOther_input" style="display:none;">
                                        <input type="text" name="moduleOther" id="moduleOther" value="moduleOther">
                                        <label for="moduleOther">Other: </label>
                                    </div>
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
                        <div class="col s12 m4 input-field">
                            <input id="panels" name="panels" validate="panels" type="number" value="1">
                            <label for="panels"># of Panels</label>
                            <span class="helper-text">Required. at least 1</span>
                        </div>
                        <div class="col s12 m4 input-field">
                            <input id="tilt" name="tilt" validate="tilt" type="number" value="0">
                            <label for="tilt">Tilt</label>
                            <span class="helper-text" data-error="Enter a value greater than equal to 0 and less than equal to 90">Required 0-90</span>
                        </div>
                        <div class="col s12 m4 input-field">
                            <input id="azimuth" name="azimuth" validate="azimuth" type="number" value="0">
                            <label for="azimuth">Azimuth</label>
                            <span class="helper-text" data-error="Enter a value greater than equal to 0 and less than equal to 360">Required 0-360</span>
                        </div>
                        <div class="col s12 center center-align">
                            <button type="button" class="btn imperial-red-outline-button" id="add_array">Add Array</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
        <div class="row">
            <div class="col s12"><br>
                <h4 class="mt-2">Supporting Documents</h4>
                <div class="mh-a" id="uppy"></div>
                <div class="center">
                    <span class="helper-text imperial-red-text" id="files_error"></span>
                </div>
            </div>
        </div>
        <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
        <div class="row">
            <div class="col s12 center">
                <p class="imperial-red-text">We will initiate a hold of ${{$type->latestPrice->price}} when you save this request. The funds will only be captured when we send a proposal</p>
                <button type="button" class="btn imperial-red-outline-button" onclick="insert(this)">Request&nbsp;Design</button>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m4 offset-m4" id="stripe_card" style="display: none">
                <div class="card-panel center imperial-red honeydew-text">
                    <h5 id="stripe_error"></h5>
                    <h6>Try again later or add / change your default payment method</h6>
                </div>
            </div>
        </div>
    </div>
@endsection