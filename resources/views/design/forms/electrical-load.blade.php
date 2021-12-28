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
                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12">
                            <select>
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Option 1">Option 1</option>
                            </select>
                            <label>Kitchen</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field">
                            <input id="kitchenQuantity" type="text" class="validate">
                            <label for="kitchenQuantity">Quantity</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <br>
                        <button class="btn btn-small" type="button" name="" id="addMoreKitchen">Add more</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12">
                            <select>
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Option 1">Option 1</option>
                            </select>
                            <label>Entertainment</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field">
                            <input id="entertainmentQuantity" type="text" class="validate">
                            <label for="entertainmentQuantity">Quantity</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <br>
                        <button class="btn btn-small" type="button" name="" id="addMoreKitchen">Add more</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12">
                            <select>
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Option 1">Option 1</option>
                            </select>
                            <label>Lighting</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field">
                            <input id="lightingQuantity" type="text" class="validate">
                            <label for="lightingQuantity">Quantity</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <br>
                        <button class="btn btn-small" type="button" name="" id="addMoreKitchen">Add more</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12">
                            <select>
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Option 1">Option 1</option>
                            </select>
                            <label>Laundry</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field">
                            <input id="laundryQuantity" type="text" class="validate">
                            <label for="laundryQuantity">Quantity</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <br>
                        <button class="btn btn-small" type="button" name="" id="addMoreKitchen">Add more</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12">
                            <select>
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Option 1">Option 1</option>
                            </select>
                            <label>Outdoor Equipment</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field">
                            <input id="outdoorQuantity" type="text" class="validate">
                            <label for="outdoorQuantity">Quantity</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <br>
                        <button class="btn btn-small" type="button" name="" id="addMoreKitchen">Add more</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col s6">
                        <div class="input-field col s12">
                            <select>
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Option 1">Option 1</option>
                            </select>
                            <label>Comfort Controls</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <div class="input-field">
                            <input id="comfortQuantity" type="text" class="validate">
                            <label for="comfortQuantity">Quantity</label>
                        </div>
                    </div>
                    <div class="col s3">
                        <br>
                        <button class="btn btn-small" type="button" name="" id="addMoreKitchen">Add more</button>
                    </div>
                </div>
            </div>
        </form>
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
            <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
        @endif
    </div>
@endsection