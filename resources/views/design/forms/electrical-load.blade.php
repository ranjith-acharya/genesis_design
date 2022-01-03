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
            <div class="card card-content" style="padding-top:2%;padding-bottom:2%;">
            <section>
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
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem6">                                                
                                                </tbody>
                                            </table>
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
@endsection