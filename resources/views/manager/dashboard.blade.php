@extends('layouts.app')

@section('title')
Manager Home - Genesis Design
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col l3 m6 s12">
            <div class="card danger-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{ $customerCount }}</h2>
                            <h6 class="white-text op-5 light-blue-text">Customers</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="ti-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l3 m6 s12">
            <div class="card info-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{ $engineerCount }}</h2>
                            <h6 class="white-text op-5">Engineers</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="fal fa-user-hard-hat"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l3 m6 s12">
            <div class="card success-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{$projectsActive }}</h2>
                            <h6 class="white-text op-5 text-darken-2">Active Projects</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="material-icons">equalizer</i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l3 m6 s12">
            <div class="card warning-gradient card-hover">
                <div class="card-content">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2 class="white-text m-b-5">{{ $projectsPending }}</h2>
                            <h6 class="white-text op-5">Pending Projects</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="white-text display-6"><i class="ti-info-alt"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 l8">
            <div class="card">
                <div class="card-content">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="card-title">Yearly Sales</h5>
                        </div>
                        <div class="ml-auto">
                            <ul class="list-inline font-12 dl m-r-10">
                                <li class="cyan-text"><i class="fa fa-circle"></i> Earnings</li>
                                <li class="blue-text text-accent-4"><i class="fa fa-circle"></i> Sales</li>
                            </ul>
                        </div>
                    </div>
                    <div class="p-t-20">
                        <div class="row">
                            <div class="col s12">
                                <div id="sales" style="height: 332px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 l4">
            <div class="card card-hover">
                <div class="card-content">
                    <div class="d-flex align-items-center">
                        <div class="m-r-20">
                            <h1 class=""><i class="ti-light-bulb"></i></h1></div>
                        <div>
                            <h3 class="card-title">Sales Analytics</h3>
                            <h6 class="card-subtitle">March  2017</h6> </div>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col s6">
                            <h3 class="font-light m-t-10"><sup><small><i class="ti-arrow-up"></i></small></sup>35487</h3>
                        </div>
                        <div class="col s6 right-align">
                            <div class="p-t-10 p-b-10">
                                <div class="spark-count" style="height:65px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-hover">
                <div class="card-content">
                    <div class="d-flex align-items-center">
                        <div class="m-r-20">
                            <h1 class=""><i class="ti-pie-chart"></i></h1></div>
                        <div>
                            <h3 class="card-title">Bandwidth usage</h3>
                            <h6 class="card-subtitle">March  2017</h6> 
                        </div>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col s6">
                            <h3 class="font-light m-t-10">50 GB</h3>
                        </div>
                        <div class="col s6 p-t-10 p-b-20 right-align">
                            <div class="p-t-10 p-b-10 m-r-20">
                                <div class="spark-count2" style="height:65px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection