@extends('layouts.app', ['activePage' => 'design', 'titlePage' => __('Select A Postcard')])

@section('content')


    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Campaign Overview</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    @include('campaign-overview.parts._table',['campaigns' => $campaigns, 'table_title' => 'Active Campaigns'])

                    @include('campaign-overview.parts._table',['campaigns' => $archivedCampaigns, 'table_title' => 'Past Campaigns'])
                    
                </div>
            </div>
        </div>
    </div>
@endsection
