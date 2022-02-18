@extends('layouts.app')

@section('title', "New $type->name design")

@section('content')
    <div class="container-fluid">
        <form id="electrical_form">
        @csrf
            <input type="hidden" value="{{ $project_id }}" name="project_id">
            <div class="row">
                <div class="col s12">
                    <h3 class="prussian-blue-text capitalize">{{$type->name}}</h3>
                    <h5>Design Request</h5>
                </div>
            </div>
            <div class="card card-content" style="padding-top:2%;padding-bottom:2%;">
            <section>
            <!-- <div class="row">
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
                                <h3 class="center-align">- OR - </h3><br> -->
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem1" multiple onchange="getSelectedValue('1')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Refrigerator_w_freezer">Refrigerator w/freezer</option>
                                                <option value="Freezer-Chest">Freezer - Chest</option>
                                                <option value="Freezer-Upright">Freezer - Upright</option>
                                                <option value="Dishwasher">Dishwasher</option>
                                                <option value="Range">Range</option>
                                                <option value="Oven">Oven</option>
                                                <option value="Microwave">Microwave</option>
                                                <option value="Toaster_oven">Toaster oven</option>
                                                <option value="Coffee_maker">Coffee maker</option>
                                                <option value="Garbage_disposal">Garbage disposal</option>
                                                <option value="Well_pump_1/2_HP">Well pump 1/2 HP</option>
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
                                                <option value="TV-small(up_to_19)">TV - small (up to 19)</option>
                                                <option value="TV-medium(up_to_27)">TV - medium (up to 27)</option>
                                                <option value="TV-large(greater_than_27)">TV - large (greater than 27)</option>
                                                <option value="TV-27_LCD_Flat_Screen">TV - 27 LCD Flat Screen</option>
                                                <option value="TV-42Plasma">TV - 42 Plasma</option>
                                                <option value="VCR/DVD">VCR/DVD</option>
                                                <option value="Cable_box">Cable box</option>
                                                <option value="Satellite_dish">Satellite dish</option>
                                                <option value="Computer_and_printer">Computer and printer</option>
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
                                                            Monthly KWh
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
                                                <option value="Lighting_of_rooms">Lighting # of rooms</option>
                                                <option value="Outdoor_lighting_175W">Outdoor lighting 175W</option>
                                                <option value="Outdoor_lighting_250W">Outdoor lighting 250W</option>
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
                                                            Monthly KWh
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
                                                <option value="Water_Heater">Water Heater (# of bedrooms)</option>
                                                <option value="Electric_Dryer_of_loads_per_week">Electric Dryer # of loads per week</option>
                                                <option value="Washing_of_loads">Washing # of loads</option>
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
                                                            Monthly KWh
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
                                                <option value="Hot_Tub">Hot Tub</option>
                                                <option value="Pool_filter_pump">Pool filter / pump</option>
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
                                                            Monthly KWh
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
                                                <option value="Air_Purifier">Air Purifier</option>
                                                <option value="Evaporative_Cooler">Evaporative Cooler</option>
                                                <option value="Window_Air_Conditioner">Window Air Conditioner</option>
                                                <option value="Ceiling_Fan">Ceiling Fan</option>
                                                <option value="Box_Fan">Box Fan</option>
                                                <option value="Electric_Blanket">Electric Blanket</option>
                                                <option value="Water_Bed_Heater">Water Bed Heater</option>
                                                <option value="Furnace_Fan">Furnace Fan</option>
                                                <option value="Furn_15KW_1100">Furn 15KW ~ 1100sq.ft</option>
                                                <option value="Furn_20KW_2000">Furn 20KW ~ 2000sq.ft</option>
                                                <option value="Furn_25KW_3000">Furn 25KW ~ 3000sq.ft</option>
                                                <option value="Bassboard_Lin_Feet">Bassboard Lin. Feet</option>
                                                <option value="Wall_Heaters_2000w">Wall Heaters @ 2000w</option>
                                                <option value="1500W_Portable">1500 W Portable</option>
                                                <option value="Heat_pump_fan">Heat pump fan</option>
                                                <option value="Heat_pump_800_1100">Heat pump 800 ~ 1100sq.ft</option>
                                                <option value="Heat_pump_1100_2000">Heat pump 1100 ~ 2000sq.ft</option>
                                                <option value="Heat_pump_2000_3000">Heat pump 2000 ~ 3000sq.ft</option>
                                                <option value="Air_Conditioner">Air Conditioner 1/2 ton</option>
                                                <option value="Air_Conditioner1.5_ton">Air Conditioner 1.5 ton</option>
                                                <option value="Air_Conditioner2_ton">Air Conditioner 2 ton</option>
                                                <option value="Air_Conditioner3_ton">Air Conditioner 3 ton</option>
                                                <option value="Air_Conditioner4_ton">Air Conditioner 4 ton</option>
                                                <option value="Air_Conditioner5_ton">Air Conditioner 5 ton</option>
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
                                                            Monthly KWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem6">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><br><br>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="average_bill1" name="average_bill1" type="text" placeholder=" ">
                                            <label for="average_bill1">Yearly usage: </label>
                                            <input type="button" class="btn btn-primary" onclick="getTotal()" value="Calculate">
                                        </div>
                                    </div><br>
                                </div>
                            </section>
                            <div class="row">
                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                                <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
                            @endif
                            </div>
                            <div class="row center">
                                <button class="btn green" type="button" onclick="insert(this)">Submit</button>
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
        </form>
    </div>
@endsection

@section('js')
<script>
        //Total Voltages
        
        
        function getTotal()
        {
            total=0;
            itemList=[];
            itemList.push($("#selectItem1").val());
            itemList.push($("#selectItem2").val());
            itemList.push($("#selectItem3").val());
            itemList.push($("#selectItem4").val());
            itemList.push($("#selectItem5").val());
            itemList.push($("#selectItem6").val());
            console.log(itemList);
            console.log(itemList[0][0]);
            
            for(let i=0;i<itemList.length;i++)
            {
                if(itemList[i].length>0)
                {
                    for(let j=0;j<itemList[i].length;j++)
                    {
                        total+=parseInt($("#item"+itemList[i][j]).val())*parseInt($("#vol"+itemList[i][j]).val())*12;
                    }
                }
            }
            console.log("Total : ",total);
            $("#average_bill1").val(total);

        }
        function getSelectedValue(id){
            //alert("hello");
            var items = $("#selectItem"+id).val();
            var tableRow = "";
            for(let i = 0; i < items.length; i++){
                tableRow += "<tr><td><input type='text' name='"+items[i]+"' value='"+items[i]+"' readonly></td></td><td><input type='text' id='item"+items[i]+"' name='quantity[]' value='1'></td><td><input type='text' id='vol"+items[i]+"' name='voltage[]' value=''></td></tr>";
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
                    toastr.success('Images uploaded!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
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
            let form = document.forms["electrical_form"].getElementsByTagName("input");
            let errors = 0;
            let jsonData = {};
           
            for (let item of form) {
                if (item.getAttribute("name"))
                    jsonData[item.getAttribute("name")] = item.value;
            }
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
                            fetch("{{route('design.electrical_load')}}", {
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
                                        toastr.success('Design inserted!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
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
@endsection