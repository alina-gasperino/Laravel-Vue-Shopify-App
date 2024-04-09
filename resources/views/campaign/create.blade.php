@extends('layouts.app', ['activePage' => 'design', 'titlePage' => __('Select your audience')])

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6 col-md-8">
                @include('form.campaign')
            </div>
            <div class="col-lg-6 col-md-4">
                @include('campaign.calculator')
            </div>
        </div>
    </div>
@endsection
