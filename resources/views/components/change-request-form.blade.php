@push('stylesheets')
    <link href="{{asset('uppy/uppy.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script type="text/javascript">
        let uppy = null;
        let fileCount = 0;
        let filesUploaded = 0;
        const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';

        document.addEventListener('DOMContentLoaded', function () {
            M.Modal.init(document.querySelectorAll('.modal'));
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
                height: 380,
                target: `#uppy`,
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

            uppy.on('file-added', (file) => {
                fileCount++;
            });

            uppy.on('file-removed', (file) => {
                fileCount--;
            });

            uppy.on('upload-success', sendFileToDb);
        });

        document.getElementById('start_cr').addEventListener('click', function () {
            let modal = M.Modal.getInstance(document.getElementById('cr_modal'));
            modal.open();
        });

        const sendFileToDb = function (file, response) {

            axios('{{route('change_requests.file.attach')}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                },
                data: {
                    path: response.body.name,
                    change_request_id: file.meta.change_request_id,
                    content_type: file.meta.type
                }
            }).then(response => {
                if (response.status === 200 || response.status === 201) {
                    console.log(response.data);
                    M.toast({
                        html: "Image uploaded",
                        classes: "steel-blue"
                    });
                    filesUploaded++;
                    if (filesUploaded === fileCount)
                        window.location.reload();

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

        async function sendCr() {
            let description = document.getElementById('modal_description')
            if (description.value)
                return axios("{{route('change_requests.new')}}", {
                    method: 'post',
                    data: {
                        description: description.value,
                        design: '{{$design->id}}',
                        proposal: '{{$design->proposals[0]->id}}'
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                    }
                }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        M.toast({
                            html: "Change request sent!",
                            classes: "steel-blue"
                        });
                    } else {
                        M.toast({
                            html: "There was a error sending the message. Please try again.",
                            classes: "imperial-red"
                        });
                    }
                    return response;
                }).catch(err => {
                    M.toast({
                        html: "There was a network error. Please try again.",
                        classes: "imperial-red"
                    });
                    return err;
                });
            else
                return {status: 403}
        }

        document.getElementById('send-with-file').addEventListener('click', function () {
            function uploadFiles(cr_id) {
                uppy.setMeta({change_request_id: cr_id, path: `genesis/${company}/design_requests/{{$design->id}}/change_requests/${cr_id}`})
                uppy.upload();
            }

            sendCr().then(response => {
                if (response.status === 200 || response.status === 201) {
                    if (fileCount === 0)
                        window.location.reload();
                    else
                        uploadFiles(response.data.id);
                }
            })
        });
    </script>
@endpush

<div id="cr_modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Make A Change Request</h4>
        <div class="row">
            <div class="col s12">
                <h5>How change requests work</h5>
                <p>You write down the changes you need to be made in the proposal, our experts then evaluate the changes requested and provide you with a quote of how much it will cost. You'll then have to either accept
                    thr quote for the work to being on the change request.</p>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <textarea id="modal_description" class="materialize-textarea"></textarea>
                <label for="modal_description">Description</label>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="mh-a" id="uppy"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="modal-close waves-effect waves-green btn-flat">Cancel</a>
        <a class="waves-effect waves-green btn-flat" id="send-with-file">Send</a>
    </div>
</div>
