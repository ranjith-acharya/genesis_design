@extends('layouts.app')

@section('title', "New $type->name design")

@section('content')
    <div class="container-fluid">
        <form id="structural_form">
            <div class="row">
                <div class="col s12">
                    <h3 class="prussian-blue-text capitalize">{{$type->name}}</h3>
                    <h5>Design Request</h5>
                </div>
            </div>
            <div class="card card-content" style="padding-top:2%;padding-bottom:2%;">
            <section>
            <div class="row">
                                    <h4 class="mt-2" style="margin-left:10px;">Upload Bill</h4>
                                    <div class="col s12">
                                        <div class="mh-a" id="uppyBill"></div>
                                        <div class="center">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div><br>
                                <h3 class="center-align">- OR - </h3><br>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="average_bill" name="average_bill" type="text" placeholder=" ">
                                        <label for="average_bill">Yearly usage: </label>
                                    </div>
                                </div><br>
                                <h3 class="center-align">- OR - </h3><br>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem1" multiple onchange="getSelectedValue('1')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Refrigerator w/freezer">Refrigerator w/freezer</option>
                                                <option value="Freezer - Chest">Freezer - Chest</option>
                                                <option value="Freezer - Upright">Freezer - Upright</option>
                                                <option value="Dishwasher">Dishwasher</option>
                                                <option value="Range">Range</option>
                                                <option value="Oven">Oven</option>
                                                <option value="Microwave">Microwave</option>
                                                <option value="Toaster oven">Toaster oven</option>
                                                <option value="Coffee maker">Coffee maker</option>
                                                <option value="Garbage disposal">Garbage disposal</option>
                                                <option value="Well pump 1/2 HP">Well pump 1/2 HP</option>
                                            </select>
                                            <label>Kitchen</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly kWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem1">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem2" multiple onchange="getSelectedValue('2')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Stereo">Stereo</option>
                                                <option value="TV - small (up to 19)">TV - small (up to 19)</option>
                                                <option value="TV - medium (up to 27)">TV - medium (up to 27)</option>
                                                <option value="TV - large (greater than 27)">TV - large (greater than 27)</option>
                                                <option value="TV - 27 LCD Flat Screen">TV - 27 LCD Flat Screen</option>
                                                <option value="TV - 42 Plasma">TV - 42 Plasma</option>
                                                <option value="VCR/DVD">VCR/DVD</option>
                                                <option value="Cable box">Cable box</option>
                                                <option value="Satellite dish">Satellite dish</option>
                                                <option value="Computer and printer">Computer and printer</option>
                                            </select>
                                            <label>Entertainment</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly kWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem2">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem3" multiple onchange="getSelectedValue('3')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Lighting # of rooms">Lighting # of rooms</option>
                                                <option value="Outdoor lighting 175W">Outdoor lighting 175W</option>
                                                <option value="Outdoor lighting 250W">Outdoor lighting 250W</option>
                                            </select>
                                            <label>Lighting</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly kWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem3">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem4" multiple onchange="getSelectedValue('4')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Water Heater (# of bedrooms)">Water Heater (# of bedrooms)</option>
                                                <option value="Electric Dryer # of loads per week">Electric Dryer # of loads per week</option>
                                                <option value="Washing # of loads">Washing # of loads</option>
                                            </select>
                                            <label>Laundry</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly kWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem4">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem5" multiple onchange="getSelectedValue('5')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Hot Tub">Hot Tub</option>
                                                <option value="Pool filter / pump">Pool filter / pump</option>
                                            </select>
                                            <label>Outdoor Equipment</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly kWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem5">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem6" multiple onchange="getSelectedValue('6')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Dehumidifier">Dehumidifier</option>
                                                <option value="Humidifier">Humidifier</option>
                                                <option value="Air Purifier">Air Purifier</option>
                                                <option value="Evaporative Cooler">Evaporative Cooler</option>
                                                <option value="Window Air Conditioner">Window Air Conditioner</option>
                                                <option value="Ceiling Fan">Ceiling Fan</option>
                                                <option value="Box Fan">Box Fan</option>
                                                <option value="Electric Blanket">Electric Blanket</option>
                                                <option value="Water Bed Heater">Water Bed Heater</option>
                                                <option value="Furnace Fan">Furnace Fan</option>
                                                <option value="Furn 15KW ~ 1100sq.ft">Furn 15KW ~ 1100sq.ft</option>
                                                <option value="Furn 20KW ~ 2000sq.ft">Furn 20KW ~ 2000sq.ft</option>
                                                <option value="Furn 25KW ~ 3000sq.ft">Furn 25KW ~ 3000sq.ft</option>
                                                <option value="Bassboard Lin. Feet">Bassboard Lin. Feet</option>
                                                <option value="Wall Heaters @ 2000w">Wall Heaters @ 2000w</option>
                                                <option value="1500 W Portable">1500 W Portable</option>
                                                <option value="Heat pump fan">Heat pump fan</option>
                                                <option value="Heat pump 800 ~ 1100sq.ft">Heat pump 800 ~ 1100sq.ft</option>
                                                <option value="Heat pump 1100 ~ 2000sq.ft">Heat pump 1100 ~ 2000sq.ft</option>
                                                <option value="Heat pump 2000 ~ 3000sq.ft">Heat pump 2000 ~ 3000sq.ft</option>
                                                <option value="Air Conditioner 1/2 ton">Air Conditioner 1/2 ton</option>
                                                <option value="Air Conditioner 1.5 ton">Air Conditioner 1.5 ton</option>
                                                <option value="Air Conditioner 2 ton">Air Conditioner 2 ton</option>
                                                <option value="Air Conditioner 3 ton">Air Conditioner 3 ton</option>
                                                <option value="Air Conditioner 4 ton">Air Conditioner 4 ton</option>
                                                <option value="Air Conditioner 5 ton">Air Conditioner 5 ton</option>
                                            </select>
                                            <label>Comfort controls</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly kWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem6">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field">
                                            <input type="text" name="total_electrical_load" id="total_electrical_load" placeholder=" ">
                                            <label for="total_electrical_load">Total: </label>
                                        </div>
                                    </div>
                                </div>
                            </section>
            </div>
        </form>
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
            <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
        @endif
    </div>
@endsection

@section('js')
<script>
        function getSelectedValue(id){
            //alert("hello");
            var items = $("#selectItem"+id).val();
            var tableRow = "";
            for(let i = 0; i < items.length; i++){
                tableRow += "<tr><td><input type='text' name='"+items[i]+"' value='"+items[i]+"' readonly></td></td><td><input type='text' name='quantity[]' value='1'></td></tr>";
            }
            document.getElementById("selectedItem"+id).innerHTML=tableRow;
            //console.log($("#selectItem").val());
        }
    </script>
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
                target: `#uppyBill`,
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
                    M.toast({
                        html: "Images uploaded",
                        classes: "steel-blue"
                    });
                    filesUploaded++;
                    if (filesUploaded === fileCount)
                        window.location = "{{route('design.list', $project_id)}}";
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
            if (notes.value !== "")
                right(notes);
            else
                jsonData[notes.getAttribute("name")] = "No notes";


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
                            document.getElementById('stripe_card').style.display = 'block'
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
                                    M.toast({
                                        html: "Design inserted",
                                        classes: "steel-blue"
                                    });
                                } else {
                                    M.toast({
                                        html: "There was a error inserting the design. Please try again.",
                                        classes: "imperial-red"
                                    });
                                    console.error(response);
                                    elem.disabled = false;
                                }
                            }).catch(err => {
                                M.toast({
                                    html: "There was a network error. Please try again.",
                                    classes: "imperial-red"
                                });
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
                M.toast({
                    html: "There are some errors in your form, please fix them and try again",
                    classes: "imperial-red"
                });
                elem.disabled = false;
            }
        }
    </script>
@endsection