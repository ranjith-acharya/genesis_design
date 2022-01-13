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
        let messagesJson = '{!! json_encode($messages) !!}';

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
                endpoint: "{{ env('SUN_STORAGE') }}/file",
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

            let messages_div = document.getElementById("messages_div");
            messages_div.scrollTop = messages_div.scrollHeight;

            messagesJson = JSON.parse(messagesJson);
            for (let button of document.getElementsByClassName("view_files")) {
                button.addEventListener('click', viewFiles);
            }
            markMessagesRead();
        });

        function markMessagesRead() {
            axios("{{route('messages.mark.read', "")}}/{{$id}}", {
                method: 'post',
                data: {
                    design: '{{$id}}'
                },
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                }
            }).then(response => {
                if (response.status === 200 || response.status === 201) {
                    console.log("messages marked as read");
                    loadNotifications();
                } else {
                    console.error("Error marking messages as read");
                }
            });
        }

        const viewFiles = function (event) {
            const button = event.target;

            let message = messagesJson.find(message => message.id === parseInt(button.getAttribute('data-id')));
            let modal = M.Modal.getInstance(document.getElementById('view_files_modal'));
            document.getElementById('view_files_modal_message').innerText = message.message;

            let template = Handlebars.compile(document.getElementById('file_li').innerText);
            document.getElementById('view_files_modal_files').innerHTML = template({data: message.files, messageID: message.id});

            modal.open();
        };

        async function sendMessage(input_id) {
            document.getElementById('message_loader').style.display = "block";

            let message = document.getElementById(input_id)
            if (message.value)
                return axios("{{route('messages.insert')}}", {
                    method: 'post',
                    data: {
                        message: message.value,
                        design: '{{$id}}'
                    },
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                    }
                }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        M.toast({
                            html: "Message sent!",
                            classes: "steel-blue"
                        });
                        document.getElementById('message_loader').style.display = "none";
                    } else {
                        M.toast({
                            html: "There was a error sending the message. Please try again.",
                            classes: "imperial-red"
                        });
                        document.getElementById('message_loader').style.display = "none";
                    }
                    return response;
                }).catch(err => {
                    M.toast({
                        html: "There was a network error. Please try again.",
                        classes: "imperial-red"
                    });
                    document.getElementById('message_loader').style.display = "none";
                    return err;
                });
            else
                return {status: 403}
        }

        document.getElementById('send').addEventListener('click', function () {
            sendMessage("message").then(response => (response.status === 200 || response.status === 201) ? window.location.reload() : "")
        });

        document.getElementById('attach-file').addEventListener('click', function () {
            let modal = M.Modal.getInstance(document.getElementById('message_modal'));
            document.getElementById('modal_message').value = document.getElementById('message').value;
            M.updateTextFields();
            M.textareaAutoResize(document.getElementById('modal_message'));
            modal.open();
        });

        const sendFileToDb = function (file, response) {

            axios("{{route('messages.file.attach')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                },
                data: {
                    path: response.body.name,
                    system_design_message_id: file.meta.system_design_message_id,
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

        document.getElementById('send-with-file').addEventListener('click', function () {
            function uploadFiles(message_id) {
                uppy.setMeta({system_design_message_id: message_id, path: `genesis/${company}/users/{{Auth::id()}}/messages/${message_id}`})
                uppy.upload();
            }

            sendMessage("modal_message").then(response => {
                if (response.status === 200 || response.status === 201) {
                    uploadFiles(response.data.id);
                }
            })
        });
    </script>
    <script type="text/html" id="file_li">
        @{{#each data}}
        <li class="collection-item avatar valign-wrapper">
            <i class="fal fa-file circle prussian-blue"></i>
            <p class="title prussian-blue-text">@{{ this.path }}</p>
            <a class="secondary-content steel-blue-text tooltipped mt-xs" href="{{route('messages.file.get')}}?design={{$id}}&message=@{{ ../messageID }}&file=@{{this.id}}" data-position="left"
               data-tooltip="Open in a new tab"
               target="_blank">View</a>
        </li>
        @{{/each}}
    </script>
@endpush

<div class="row m-0" style="overflow: auto;max-height: 70vh" id="messages_div">
    <div class="col s12">
        @if(sizeof($messages) > 0)
            @foreach($messages as $message)
                @component('partials.message', ["message" => $message,"design" => $id, "files" => (sizeof($message->files) > 0)])@endcomponent
            @endforeach
        @else
            <div class="card-panel">
                No messages yet
            </div>
        @endif
    </div>
</div>
@unless($readOnly)
    <div class="row m-0">
        <div class="valign-wrapper">
            <div class="input-field col s9">
                <textarea id="message" class="materialize-textarea" placeholder="Type your message"></textarea>
                <label for="message">Message</label>
            </div>
            <div class="col s3 center">
                <a class="btn btn-large btn-floating pulse cyan tooltipped" data-position="top" data-tooltip="Send Message" id="send"><i class="fal fa-paper-plane" ></i></a>
                <a class="btn btn-large btn-floating prussian-blue tooltipped  ml-s" id="attach-file" data-position="top" data-tooltip="Attach File"><i class="fal fa-paperclip"></i></a>
            </div>
        </div>
        <div id="message_loader" style="display: none">
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        </div>
    </div>

    <div id="message_modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Send message with attachments</h4>
            <div class="row">
                <div class="input-field col s12">
                    <textarea id="modal_message" class="materialize-textarea"></textarea>
                    <label for="modal_message">Message</label>
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
@endunless
<div id="view_files_modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Message attachments</h4>
        <div class="row">
            <p class="col s12" id="view_files_modal_message"></p>
        </div>
        <div class="row">
            <div class="col s12">
                <ul class="collection" id="view_files_modal_files"></ul>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="modal-close waves-effect waves-green btn-flat">Cancel</a>
    </div>
</div>
