@extends('layouts.app')

@section('title', "New Proposal")

@section('css')
    <link href="{{asset('uppy/uppy.min.css')}}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script type="text/javascript">
        let uppyFull = null;
        let uppyPartial = null;
        let fileCount = 0;
        let filesUploaded = 0;
        const redirect = "{{route('engineer.design.view', $design->id)}}";
        const company = '{{($design->project->customer->company)?$design->project->customer->company:"no-company"}}';

        document.addEventListener('DOMContentLoaded', function () {
            uppyFull = Uppy.Core({
                id: "fullFiles",
                debug: true,
                meta: {
                    save_as: ''
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
                height: 380,
                target: `#uppy_full`,
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

            {{--uppyPartial = Uppy.Core({--}}
            {{--    id: "partialFiles",--}}
            {{--    debug: true,--}}
            {{--    meta: {--}}
            {{--        save_as: ''--}}
            {{--    },--}}
            {{--    onBeforeUpload: (files) => {--}}
            {{--        const updatedFiles = {}--}}
            {{--        Object.keys(files).forEach(fileID => {--}}
            {{--            updatedFiles[fileID] = files[fileID];--}}
            {{--            updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;--}}
            {{--        })--}}
            {{--        return updatedFiles--}}
            {{--    }--}}
            {{--}).use(Uppy.Dashboard, {--}}
            {{--    height: 380,--}}
            {{--    target: `#uppy_partial`,--}}
            {{--    inline: true,--}}
            {{--    hideUploadButton: true,--}}
            {{--    note: "Upto 20 files of 20 MBs each"--}}
            {{--}).use(Uppy.XHRUpload, {--}}
            {{--    endpoint: "{{ env('SUN_STORAGE') }}/file",--}}
            {{--    headers: {--}}
            {{--        'api-key': "{{env('SUN_STORAGE_KEY')}}"--}}
            {{--    },--}}
            {{--    fieldName: "file"--}}
            {{--});--}}

            uppyFull.on('file-added', (file) => {
                fileCount++;
            });
            // uppyPartial.on('file-added', (file) => {
            //     fileCount++;
            // });

            uppyFull.on('file-removed', (file) => {
                fileCount--;
            });
            // uppyPartial.on('file-removed', (file) => {
            //     fileCount--;
            // });

            uppyFull.on('upload-success', sendFileToDb);
            // uppyPartial.on('upload-success', sendFileToDb);
        });

        const sendFileToDb = function (file, response) {
            console.log(file);
            axios("{{route('engineer.proposal.file.attach')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                },
                data: {
                    path: response.body.name,
                    type: 'full',
                    proposal_id: file.meta.proposal_id,
                    content_type: file.meta.type
                }
            }).then(response => {
                if (response.status === 200 || response.status === 201) {
                    console.log(response.data);
                    toastr.success('Files Uploaded!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    // M.toast({
                    //     html: "Files uploaded",
                    //     classes: "steel-blue"
                    // });
                    filesUploaded++;
                    if (filesUploaded === fileCount)
                        window.location = redirect;

                } else {
                    toastr.error('There was a error uploading images. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    console.error(response);
                }
            }).catch(err => {
                toastr.error('There was a network error uploading files. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                console.error(err);
            });
        };

        async function sendProposalObj() {
            let note = document.getElementById('note')
            if (note.value && uppyFull.getFiles().length > 0)
                return axios("{{route('engineer.proposal.insert')}}", {
                    method: 'post',
                    data: {
                        note: note.value,
                        design: '{{$design->id}}',
                        change_request: '{{(sizeof($design->changeRequests)>0)?$design->changeRequests[0]->id:""}}'
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                    }
                }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        toastr.success('Proposal Uploaded!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                        // M.toast({
                        //     html: "Proposal Iploaded",
                        //     classes: "steel-blue"
                        // });
                    } else {
                        toastr.error('There was a error sending the message. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    }
                    return response;
                }).catch(err => {
                    toastr.error('There was a network error. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    return err;
                });
            else
                return {status: 403}
        }

        function send(elem) {
            elem.disabled = true;

            function uploadFiles(proposal_id) {
                uppyFull.setMeta({proposal_id: proposal_id, path: `genesis/${company}/proposals/project-{{$design->id}}/full`})
                uppyFull.upload();

                {{--uppyPartial.setMeta({proposal_id: proposal_id, path: `genesis/${company}/proposals/project-{{$design->id}}/partial`, type: "partial"})--}}
                // uppyPartial.upload();
            }

            sendProposalObj().then(response => {
                if (response.status === 200 || response.status === 201) {
                    uploadFiles(response.data.id);
                } else if (response.status === 403) {
                    toastr.error('Make sure there is a note and at least one file is added!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                    // M.toast({
                    //     html: "Make sure there is a note and at least one file is added",
                    //     classes: "imperial-red"
                    // });
                }
                elem.disabled = false;
            })
        }
    </script>
@endsection

@section('content')
    <div class="container-fluid">
        @if((sizeof($design->changeRequests)>0))
            {{ Breadcrumbs::render('change_request_proposal_new', $design) }}
        @else
            {{ Breadcrumbs::render('proposal_new', $design) }}
        @endif
        <div class="card card-content">
            <div class="container-fluid">
        <div class="row">
            <div class="col s12 m10">
                <h3>New Proposal</h3>
                <h6 class="capitalize">For <span class="bold blue-text">{{$design->type->name}}</span> Project <span class="bold blue-text">{{$design->project->name}}</span></h6>
            </div>
        </div>
        @if((sizeof($design->changeRequests)>0))
            <div class="row">
                <div class="col s12">
                    <h4 class="capitalize">Change Request</h4>
                    <span class="prussian-blue-text"><b>Description</b></span>
                    <blockquote>{{$design->proposals[0]->changeRequest->description}}</blockquote>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col s12">
                <h4 class="capitalize">Proposal Information</h4>
                <div class="input-field col s12">
                    <textarea id="note" class="materialize-textarea"></textarea>
                    <label for="note">Note</label>
                </div>
            </div>
        </div>
        {{--        <div class="row">--}}
        {{--            <div class="col s12">--}}
        {{--                <h4>Partial Files</h4>--}}
        {{--                <h6>To be displayed to the customer <span class="imperial-red-text">BEFORE</span> they approve the design</h6>--}}
        {{--                <div class="mh-a" id="uppy_partial"></div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="row">
            <div class="col s12">
                <h4>Files</h4>
                <div class="mh-a" id="uppy_full"></div>
                <div class="imperial-red-text" id="uppy_errors"></div>
            </div>
        </div>
        <div class="row"><br>
            <div class="col s12 center">
                <button class="btn prussian-blue" onclick="send(this)">Send Proposal</button>
            </div>
        </div>
            </div>
        </div>
    </div>
@endsection
