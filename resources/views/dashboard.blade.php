@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <button type="button" class="btn btn-default btn-lg">Trailing 7 Days</button>
                <button type="button" class="btn btn-default btn-lg">Trailing 30 Days</button>
                <button type="button" class="btn btn-default btn-lg">Trailing 12 Months</button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        Revenue from Mailings
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <canvas id="lineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Last 5 Campaigns</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Thumbnail</th>
                                <th>Revenue</th>
                                <th>$ / Recipient</th>
                                <th>% of Mail Revenue</th>
                                <th>Return on Spend</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->project_id }}</td>
                                    <td><img width=99 height= 64 src="{{ $campaign->thumbnail_url }}"></td>
                                    <td>${{ rand(0,5000) }}</td>
                                    <td>${{ rand(0,5000) }}</td>
                                    <td>{{ rand(0,100) }}%</td>
                                    <td>{{ rand(0,10) }}.0<small>x</small></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Recent Activity</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>Campaign #c5g9595wy77g028ds5q0 batch #4 sent for processing</td>
                                <td>2 Hours Ago</td>
                            </tr>
                            <tr>
                                <td>Campaign #c5g9595wy77g028ds5q0 batch #3 mailed</td>
                                <td>1 Day Ago</td>
                            </tr>
                            <tr>
                                <td>Campaign #c5g9595wy77g028ds5q0 batch #3 sent for processing</td>
                                <td>1 Day Ago</td>
                            </tr>
                            <tr>
                                <td>Campaign #c5g9595wy77g028ds5q0 batch #2 mailed</td>
                                <td>2 Days Ago</td>
                            </tr>
                            <tr>
                                <td>Campaign #c5g9595wy77g028ds5q0 batch #2 sent for processing</td>
                                <td>2 Days Ago</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css')

@endpush
@push('onload-js')
    <script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}" defer></script>
@endpush
@push('js')
    <script>
        $(document).ready(function () {
            var lineData = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [

                    {
                        label: "Cost of Mailer",
                        backgroundColor: 'rgba(220, 220, 220, 0.5)',
                        pointBorderColor: "#fff",
                        data: [28, 48, 40, 19, 86, 27, 90]
                    }, {
                        label: "Revenue From Mailer",
                        backgroundColor: 'rgba(26,179,148,0.5)',
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 80, 81, 110, 55, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true,
                aspectRatio:5
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            new Chart(ctx, {type: 'line', data: lineData, options: lineOptions});

        });
    </script>
@endpush
