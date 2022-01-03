@extends('layouts.app')

@section('title', "New $type->name design")

@section('content')
    <div class="container-fluid">
        <form id="structural_form">
            <div class="row">
                <div class="col s12">
                    <h3 class="imperial-red-text capitalize">{{$type->name}}</h3>
                    <h5>Design Request</h5>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <div class="col s12">
                                <h4>Supporting Documents</h4>
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
                                <h4>Permit Change</h4>
                                <div class="mh-a" id="uppyPermitChange"></div>
                                    <div class="">
                                        <span class="helper-text imperial-red-text" id="files_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s4">
                            <div class="input-field">
                                <p>
                                    <label>
                                        <input type="checkbox"/>
                                        <span>Structural Letter</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                        <div class="col s4">
                            <div class="input-field">
                                <p>
                                    <label>
                                        <input type="checkbox"/>
                                        <span>Electrical Letter</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                        <div class="col s4">
                            <div class="input-field">
                                <input placeholder=" " type="text" class="validate" readonly>
                                <label for="total_price">Price: </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
    </div>
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
                target: `#uppySupportingDocuments`,
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
                target: `#uppyPermitChange`,
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
    </script>
@endsection