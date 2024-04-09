@extends('layouts.app', ['activePage' => 'design', 'titlePage' => __('Select A Postcard')])

@section('content')
    <div style="height: 30px; margin: 18px 25px">
        <a class="btn btn-lg btn-primary pull-right" id="savePostCard"
           href="{{ Route('createCampaign', $projectId) }}">Save & Continue</a>
    </div>
    <div></div>

    <iframe frameborder="0" src=
    "https://simplepost.designhuddle.com/editor?token={{ $userToken }}&project_id={{ $projectId }}"
            style="overflow: hidden; height: 90%; width:90%; position: absolute"
    ></iframe>
@endsection


@push('js')

@endpush
