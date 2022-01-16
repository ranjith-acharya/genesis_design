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
            <form class="black-text" id="bulk_projects" name="bulk_projects" action="">
                @csrf
                <div class="">
                    <div class="col s10">
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="input-field col s5">
                                        <input id="customer_name" type="text" name="customer_name[]" placeholder="" value="" class="required">
                                        <label for="customer_name">Customer Name: </label>
                                    </div>
                                    <div class="input-field col s5">
                                        <select  id="project_type" name="customer_project_type[]" class='browser-default'>
                                            <option value=""  selected>Choose your option</option>
                                            <option value="residential">Residential</option>
                                            <option value="commercial">Commercial</option>
                                        </select>
                                    </div>
                                    <div class="input-field col s1">
                                        <button data-repeater-delete="" class="btn btn-small red tooltipped" data-tooltip="Remove Project" data-position="bottom" type="button"><i class="material-icons">clear</i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s10">
                                        <input type="text" id="customer_address" name="customer_address[]"  placeholder=" " class="required">
                                        <label for="customer_address">Address: </label>
                                    </div>
                                   
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
                    <div id="formRepetation">
                    </div>
                    <div class="col s2 center">
                        <button type="button" onclick="repeatForm()"  class="btn btn-small steel-blue"><i class="material-icons left">add</i>CUSTOMER</button>
                        <button type="button" onclick="insert()" class="btn btn-small green" ><i class="material-icons left">add</i>ADD PROJECT</button>
                    </div>
                </div>
               <br>
            </form>
        </div>
    </div>
</div>
<script>
    count=0;
    uppiesArray=[];
    var uppies=[];
    let whichUppyIsRequired = [];
let redirectEnabled = true;
let fileCount = 0;
let filesUploaded = 0;
const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';
    const post = '{{route('project.insert')}}';
    const postUpdate = '';
    const fileInsert = '{{route('project.file.attach')}}';
const sendFileToDb = function (file, response) {

axios(fileInsert, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
    },
    data: {
        path: response.body.name,
        file_type_id: file.meta.file_type_id,
        project_id: file.meta.project_id,
        content_type: file.meta.type
    }
}).then(response => {
    if (response.status === 200 || response.status === 201) {
        console.log(response.data);
        M.toast({
            html: "Files uploaded",
            classes: "steel-blue"
        });
    } else {
        M.toast({
            html: "There was a error uploading images. Please try again.",
            classes: "imperial-red"
        });
        console.error(response);
    }
    filesUploaded++;
    if ((filesUploaded === fileCount) && redirectEnabled)
        window.location = redirect;
}).catch(err => {
    M.toast({
        html: "There was a network error uploading images. Please try again.",
        classes: "imperial-red"
    });
    console.error(err);
});
}
    function repeatForm()
    {
       
        uppiesArray.push(count);
        var div="<div id='repeat"+count+"'><div class='col s10'><div ><div data-repeater-item><div class='row'><div class='input-field col s5'><input id='customer_name"+count+"' type='text' name='customer_name[]' placeholder=' value=' class='required'><label for='customer_name'>Customer Name: </label></div>";
        div+="<div class='input-field col s5'><select class='browser-default' name='customer_project_type[]'><option value=''  selected>Choose your option</option><option value='residential'>Residential</option><option value='commercial'>Commercial</option></select></div>";
        div+="<div class='input-field col s1'><button data-repeater-delete=' class='btn btn-small red tooltipped' data-tooltip='Remove Project' data-position='bottom' onclick='closeDiv("+count+")' type='button'><i class='material-icons'>clear</i></button></div></div>";
        div+="<div class='row'><div class='input-field col s10'><input type='text' id='customer_address"+count+"' name='customer_address[]'  placeholder=' ' class='required'><label for='customer_address'>Address: </label></div></div>";
        div+="<div class='row'><div class='col s2'><label for='uppyBulk"+count+"'>Upload Documents</label></div><div class='col s9'><div class='mh-a' id='uppyBulk"+count+"'></div><div class='><span class='helper-text imperial-red-text' id='files_error"+count+"'></span></div></div></div><br></div></div></div>";
        document.getElementById('formRepetation').innerHTML+=div;
        loadUppies(count);
        count++;
       
    }
  
    function loadUppies(count)
    { 
        uppies[count] = Uppy.Core({
                id: "files"+uppiesArray[count],
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
                target: '#uppyBulk'+uppiesArray[count],
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
            uppies[count].on('upload-success', sendFileToDb);

            uppies[count].on('file-added', (file) => {
                fileCount++;
            });

            uppies[count].on('file-removed', (file) => {
                fileCount--;
            });

    }

//Validate Form Fields
function validateFields(skipUppies = false) {
    let form = document.forms["bulk_projects"].getElementsByTagName("input");
   
   let errors = 0;
    let jsonData = {};
    console.log("Forms Data : ",form);
   
    //Make the thing green
    function right(item) {
        item.classList.remove("invalid");
        item.classList.add("valid");

        if (item.getAttribute("name"))
            jsonData[item.getAttribute("name")] = [item.value];
    }

    //Make the thing red
    function wrong(item) {
        item.classList.remove("valid");
        item.classList.add("invalid");
        errors++;
    }

    // Strip all the FUNKY chars from the string
    function stripSpecials(string) {
        //remove special chars
        string = string.replace(/[^\w\s.]/gi, "");
        //remove spaces
        string = string.replace(/\s+/g, "").trim();
        return string.toLowerCase();
    }
    console.log("Json Data1 :",jsonData);
    // All the non-select inputs
    for (let item of form) {

        if (item.getAttribute("name"))
        {
            if(item.getAttribute("name") in jsonData)
                    jsonData[item.getAttribute("name")].push(item.value);
            else
            {
                if(item.getAttribute("name")==="_token")
                {
                    jsonData[item.getAttribute("name")]=item.value;
                }
                else{
                    jsonData[item.getAttribute("name")]=[item.value];
                }
                
            }
        }
    }
    console.log("Json Data2 :",jsonData);
    const project_types=document.getElementsByName("customer_project_type[]");
    for(let i=0;i<project_types.length;i++)
    {
        if(project_types[i].value=="")
        {
            wrong(project_types[i])
        }
        else
        {

            if("customer_project_type[]" in jsonData)
            {
               
            if(project_types[i].value==="residential")
                {
                    jsonData["customer_project_type[]"].push('residential');
                }
            else if(project_types[i].value==="commercial")
                {
                    jsonData["customer_project_type[]"].push('commercial');
                }
            }
            else
            {
                if(project_types[i].value==="residential")
                {
                    jsonData["customer_project_type[]"]=['residential'];
                }
                else if(project_types[i].value==="commercial")
                {
                    jsonData["customer_project_type[]"]=['commercial'];
                }
            }
        }
        
    }
    // const project_type = document.querySelector("#project_type");

    // if (project_type.selectedIndex === 0) wrong(project_type);
    // else {
    //     right(project_type);
    //     if(project_type.selectedIndex===1)
    //     jsonData["customer_project_type[]"] = ['residential'];
    //     else if(project_type.selectedIndex===2)
    //     jsonData["customer_project_type[]"] = ['commercial'];
       
    // }

   

    // validate uppies
    if (!skipUppies)
        whichUppyIsRequired.forEach((is_required, index) => {
            if (is_required) {
                if (uppies[index].getFiles().length === 0) {
                    errors++;
                    document.getElementById(uppies[index].getID() + "_error").innerText = "Please attach at least one file";
                } else
                    document.getElementById(uppies[index].getID() + "_error").innerText = "";
            }
        });

    return {
        errors: errors,
        columns: jsonData
    };
}

//insert Bulk Project
function insert() {


const validationResult = validateFields();
console.log("Rules ---> ",validationResult);
function uploadFiles(project_id) {
        uppies.forEach(uppy => {
            uppy.setMeta({project_id: project_id, path: `genesis/${company}/projects/${project_id}/${uppy.getID()}`})
            uppy.upload();
        })
    }

    if (validationResult.errors === 0) {
        axios(post, {
            method: 'post',
            data: validationResult.columns,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
            }
        }).then(response => {
            if (response.status === 200 || response.status === 201) {
                console.log("Data Resposne -----> ",response)
                uploadFiles(response.data.id);
            } else {
                M.toast({
                    html: "There was a error inserting the project. Please try again.",
                    classes: "imperial-red"
                });
                console.error(response);
            }
        }).catch(err => {
            M.toast({
                html: "There was a network error. Please try again.",
                classes: "imperial-red"
            });
            console.error(err);
        });
    } else {
        M.toast({
            html: "There are some errors in your form, please fix them and try again",
            classes: "imperial-red"
        });
    }
}



    </script>
@endsection
@section('js')

@endsection