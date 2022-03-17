@extends('layouts.app')

@section('title', "New $type->name design")

@section('css')
    <link href="{{asset('uppy/uppy.min.css')}}" rel="stylesheet">
@endsection

@section('js')
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
        const paymentHoldUrl = "{{route('payment.hold')}}";
        const stripePublicKey = "{{env('STRIPE_KEY')}}";


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
                    toastr.success('Images Uploaded!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
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
                console.log("item", item);
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
            const inverterOther = document.getElementById('inverterOther');
            // const monitorOther = document.getElementById('monitorOther');
            const moduleOther = document.getElementById('moduleOther');
            const rackingOther = document.getElementById('rackingOther');
            if (notes.value !== ""){
                //alert("notes");
                right(notes);
            }
            else
                jsonData[notes.getAttribute("name")] = "No notes";

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
                            document.getElementById('stripe_card').style.display = 'block';
                            toastr.error('Make your Payment Method Default in Profile!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
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
                                        window.location = "{{route('design.list', $project_id)}}";
                                        toastr.success('Design Submitted!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
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
                toastr.error('There are some errors in your form, please fix them and try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
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
    <div class="container-fluid">
        <form id="aurora_form">
            <div class="row">
                <div class="col s12">
                    <h3 class="prussian-blue-text capitalize">{{$type->name}}</h3>
                    <h5>Design Request</h5>
                </div>
            </div>
        <div class="card card-content" style="padding-top:2%;padding-bottom:2%;">
            <div class="row">
                <div class="col s12">
                    <h6 class="red-text capitalize" style="padding-bottom:2%;">* Fields are Required</h6>
                </div>
            </div>
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
                    <input id="annual_usage" name="annual_usage" type="number" validate="annual_usage">
                    <label for="annual_usage">Annual Usage <span class="red-text lead">*</span></label>
                    <span class="helper-text" data-error="Enter a value greater than 1">Required</span>
                </div>
                <div class="input-field col s6">
                    <input id="max_offset" name="max_offset" type="number" validate="offset">
                    <label for="max_offset">Max Offset % <span class="red-text lead">*</span></label>
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
                                                <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="moduleOther_input" style="display:none;">
                                                <input type="text" name="moduleOther" id="moduleOther" value="moduleOther">
                                                <label for="moduleOther">Other:  <span class="red-text lead">*</span></label>
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
                                                <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="inverterOther_input" style="display:none;">
                                                <input type="text" name="inverterOther" id="inverterOther" value="inverterOther">
                                                <label for="inverterOther">Other:  <span class="red-text lead">*</span></label>
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
                                                <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="rackingOther_input" style="display:none;">
                                                <input type="text" name="rackingOther" id="rackingOther" value="rackingOther">
                                                <label for="rackingOther">Other:  <span class="red-text lead">*</span></label>
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
                                                <label for="monitorType">Monitor Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="monitorOther_input" style="display:none;" >
                                                <input type="text" name="monitorOther" id="monitorOther" value="monitorOther">
                                                <label for="monitorOther">Other:  <span class="red-text lead">*</span></label>
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
                                                <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="moduleOther_input" style="display:none;">
                                                <input type="text" name="moduleOther" id="moduleOther" value="moduleOther">
                                                <label for="moduleOther">Other:  <span class="red-text lead">*</span></label>
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
                                                <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="inverterOther_input" style="display:none;">
                                                <input type="text" name="inverterOther" id="inverterOther" value="inverterOther">
                                                <label for="inverterOther">Other:  <span class="red-text lead">*</span></label>
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
                                                <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="rackingOther_input" style="display:none;">
                                                <input type="text" name="rackingOther" id="rackingOther" value="rackingOther">
                                                <label for="rackingOther">Other:  <span class="red-text lead">*</span></label>
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
        </div>
        </form>
        <div class="row">
            <div class="col s12">
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
                <p class="imperial-red-text">We will initiate a hold of ${{$type->latestPrice->price}} when you save this request. The entire amount will only be captured once we send you the design.</p>
                <button type="button" class="btn imperial-red-outline-button" onclick="insert(this)">Request&nbsp;Design</button>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m4 offset-m4" id="stripe_card" style="display: none">
                <div class="card-panel center prussian-blue" style="color:#fff;">
                    <h5 id="stripe_error" class="white-text"></h5>
                    <h6  class="white-text">Try again later or add / change your default payment method</h6>
                </div>
            </div>
        </div>
    </div>
@endsection
