@extends('layouts.app', ['activePage' => 'design', 'titlePage' => __('Select A Postcard')])

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Automated Retargeting</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                @include('form.campaign')
            </div>
            <div class="col-lg-6 col-md-6">
                @include('campaign.calculator')
            </div>
        </div>
    </div>
    </div>
@endsection
