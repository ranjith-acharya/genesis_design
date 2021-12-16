@extends('layouts.app')

@section('title', "New $type->name design")

@section('content')
    <div class="container">
        <form id="structural_form">
            <div class="row">
                <div class="col s12">
                    <h3 class="imperial-red-text capitalize">{{$type->name}}</h3>
                    <h5>Design Request</h5>
                </div>
            </div>
        </form>
        
        <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
    </div>
@endsection
