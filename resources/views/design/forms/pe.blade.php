@extends('layouts.app')

@section('title', "New $type->name design")

@section('content')
    <div class="container-fluid">
        <form id="pe_stamping">
            @csrf
            <input type="hidden" value="{{ $project_id }}" name="project_id">
            <div class="row">
                <div class="col s12">
                    <h3 class="prussian-blue-text capitalize">{{$type->name}}</h3>
                    <h5>Design Request</h5>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
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
                    <div class="row">
                        <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
                    </div>
                    <div class="row center">
                        <p class="imperial-red-text">We will initiate a hold of ${{$type->latestPrice->price}} when you save this request. The entire amount will only be captured once we send you the design.</p>
                        <button class="btn green" type="button" onclick="insert(this)">Request Design</button>
                        <div class="row">
                            <div class="col s12 m4 offset-m4" id="stripe_card" style="display: none">
                                <div class="card-panel center imperial-red honeydew-text">
                                    <h5 id="stripe_error"></h5>
                                    <h6>Try again later or add / change your default payment method</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
<script src="{{asset('uppy/uppy.min.js')}}"></script>
<script src="{{asset('js/validate/validate.min.js')}}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="{{asset('js/designs/payment.js')}}"></script>
<script type="text/javascript">
    const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';
    let uppy1 = null;
    let uppy2 = null;
    let fileCount = 0;
    let filesUploaded = 0;
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
            uppy2.on('upload-success', sendFileToDb);

            uppy2.on('file-added', (file) => {
                fileCount++;
            });

            uppy2.on('file-removed', (file) => {
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
            let form = document.forms["pe_stamping"].getElementsByTagName("input");
            let errors = 0;
            let jsonData = {};

            for (let item of form) {
                if (item.getAttribute("name"))
                    jsonData[item.getAttribute("name")] = item.value;
            }
            jsonData["structural_letter"] = document.getElementById('structural_letter').checked;
            jsonData["electrical_stamps"] = document.getElementById('electrical_stamps').checked;
            jsonData["project_id"] = "{{$project_id}}";
            return {
                errors: errors,
                columns: jsonData
            };
        }

        function insert(elem) {

            elem.disabled = true;
            const validationResult = validateFields();
            document.getElementById('stripe_card').style.display = 'none'

            function uploadFiles(system_design_id) {
                uppy1.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy1.upload();
                uppy2.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy2.upload();
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
                            fetch("{{ route('design.pe_stamping') }}", {
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
                toastr.error('There are some error in your form, please fix them and try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                elem.disabled = false;
            }
        }
</script>
@endsection