@extends('layouts.profile')

@section('profile_body')
    <div class="card-panel">
        <div class="row">
            <div class="col s12 m6">
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>First Name: </b></span>
                    {{ Auth::user()->first_name }}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Email: </b></span>
                    {{ Auth::user()->email }}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Company: </b></span>
                    {{ Auth::user()->company }}
                </div>
            </div>
            <div class="col s12 m6">
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Last Name: </b></span>
                    {{ Auth::user()->last_name }}
                </div>
                <div class="mb-xxxs">
                    <span class="prussian-blue-text"><b>Phone: </b></span>
                    {{ Auth::user()->phone }}
                </div>
            </div>
        </div>
    </div>
@endsection
