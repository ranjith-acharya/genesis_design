@extends('layouts.app')

@section('title')
Create Bulk Project
@endsection

@section('css')
    <link href="{{asset('uppy/uppy.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/project/project.css')}}" rel="stylesheet">
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUREr9JDgVEAJu_yv-LQFvWjfNDMY2NIU&libraries=places"></script>
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script src="{{asset('js/validate/validate.min.js')}}"></script>
    <script type="text/javascript">
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
                target: `#uppyBulk`,
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
    </script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-content">
            <form class="black-text" action="">
                @csrf
                <div class="bulk-repeater">
                    <div class="col s10">
                        <div data-repeater-list="repeater-group">
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="input-field col s5">
                                        <input id="customer_name" type="text" name="customer_name[]" placeholder="" value="" class="required">
                                        <label for="customer_name">Customer Name: </label>
                                    </div>
                                    <div class="input-field col s5">
                                        <select name="customer_project_type[]" class="required">
                                            <option value="" disabled selected>Choose your option</option>
                                            <option value="residential">Residential</option>
                                            <option value="commercial">Commercial</option>
                                        </select>
                                        <label>Type</label>
                                    </div>
                                    <div class="input-field col s1">
                                        <button data-repeater-delete="" class="btn btn-small red tooltipped" data-tooltip="Remove Project" data-position="bottom" type="button"><i class="material-icons">clear</i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s10">
                                        <textarea id="customer_address" name="customer_address[]" class="materialize-textarea" placeholder=" " class="required"></textarea>
                                        <label for="customer_address">Address: </label>
                                    </div>
                                    <!-- <div class="input-field col s3">
                                        <select name="customer_project_design">
                                            <option value="" disabled selected>Choose your option</option>
                                            <option value="aurora design">Aurora Design</option>
                                            <option value="structural load letter and calculations">Structural Load Letter and Calculations</option>
                                            <option value="PE stamping">PE Stamping</option>
                                            <option value="electrical load calculations">Electrical Load Calculations</option>
                                            <option value="site survey">Site Survey</option>
                                            <option value="engineering permit package">Engineering Permit Package</option>
                                        </select>
                                        <label>Design</label>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col s2">
                                        <label for="uppyBulk">Upload Documents</label>
                                    </div>
                                    <div class="col s9">
                                        <div class="mh-a" id="uppyBulk"></div>
                                        <div class="">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="col s2 center">
                        <button type="button" data-repeater-create="" class="btn btn-small steel-blue"><i class="material-icons left">add</i>CUSTOMER</button>
                        <button type="submit" class="btn btn-small green" ><i class="material-icons left">add</i>ADD PROJECT</button>
                    </div>
                </div>
               <br>
            </form>
        </div>
    </div>
</div>
@endsection