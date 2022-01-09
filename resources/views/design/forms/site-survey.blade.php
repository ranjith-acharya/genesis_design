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
        </form>
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
            <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
        @endif
    </div>
@endsection
