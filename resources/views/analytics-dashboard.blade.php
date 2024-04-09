@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')

<!-- Include Apex Charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.31.0/apexcharts.min.js" integrity="sha512-5nh/cCgHEr1ncUodSt5m0z5vOsT+iJlRN9fUtuyc1hC4KPB/bMozP2hsGiWg7DmC8/+96ZowqLKKt7yqmVNW9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Include Select2 Library -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/6.5.95/css/materialdesignicons.min.css" integrity="sha512-Zw6ER2h5+Zjtrej6afEKgS8G5kehmDAHYp9M2xf38MPmpUWX39VrYmdGtCrDQbdLQrTnBVT8/gcNhgS4XPgvEg==" crossorigin="anonymous" referrerpolicy="no-referrer" />



<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Overview Dashboard</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-2">
            <div class="form-group mb-3">
                <select class="form-control custom-input" id="campaign-select" name="campaign_select" >
                    <option value="all">All Campaigns</option>
                    <!-- @foreach($campaigns as $index=>$campaign)
                        <option value="{{$campaign->id}}" {{ $selected_campaign_id == $campaign->id ? 'selected=""' : ''}}>Campaign-{{$campaign->discount_prefix}}-{{$index+1}}</option>
                    @endforeach -->

                    <option value="campaign_01">Campaign-01</option>
                    <option value="campaign_02">Campaign-02</option>
                    <option value="campaign_03">Campaign-03</option>
                </select>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="form-group mb-3">
                <select class="form-control custom-input select2-hidden-accessible" name="daterange" id="datepicker" data-select2-id="select2-data-datepicker" tabindex="-1" aria-hidden="true">
                    <option data-select2-id="select2-data-2-ms9n">Select Date</option>
                </select>
                <span class="select2 select2-container select2-container--default select2-container--below select2-container--focus" dir="ltr" data-select2-id="select2-data-1-9rxa" style="width: 247.156px;"><span class="selection">
                        <span id="datePicker-dropdown" class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-datepicker-container" aria-controls="select2-datepicker-container">
                            <i class="mdi mdi-calendar custom-calendar"></i>
                            <span class="select2-selection__rendered" id="select2-datepicker-container" role="textbox" aria-readonly="true" title="Select Date">Select Date</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
            </div>
            <!-- <div class="form-group">
                <div id="reportrange" class="form-control daterange" data-toggle="date-picker-range" data-target-display="#selectedValue" data-cancel-class="btn-light">
                    <i class="mdi mdi-calendar"></i>&nbsp;
                    <span id="selectedValue"></span> <i class="mdi mdi-menu-down"></i>
                </div>
            </div> -->
            <!-- <div class="form-group mb-3">
                <input type="text" class="custom-input daterange" />
            </div> -->
        </div>
    </div>

    <span class="select2-container select2-container--default  custom-period-box" style="z-index: 9999 !important; display: none;">
        <div class="card" style="min-height: 550px;">
            <div class="card-body">
                <div class="row align-items-center p-t-10">
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label for="example-select" class="inputs-label">Period</label>
                            <select class="form-control custom-input" id="period-select">
                                <option value="">Select Period</option>
                                <option value="Today">Today</option>
                                <option value="Yesterday">Yesterday</option>
                                <option value="Last 7 Days">Last 7 Days</option>
                                <option value="Last 30 Days">Last 30 Days</option>
                                <option value="This Month">This Month</option>
                                <option value="Last Month">Last Month</option>
                                <option value="Custom Range">Custom Range</option>
                            </select>
                        </div>
                    </div>
                </div> <!-- end row-->
                <div class="row align-items-center p-t-10">
                    <div class="col-xl-6">
                        <div class="form-group mb-3">
                            <label for="" class="inputs-label">Starting</label>
                            <input type="text" id="starting-date" class="form-control custom-input">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group mb-3">
                            <label for="" class="inputs-label">Ending</label>
                            <input type="text" id="ending-date" class="form-control custom-input">
                        </div>
                    </div>
                </div> <!-- end row-->
                <div class="row align-items-center p-t-10">
                    <div class="col-xl-12">
                        <input type="text" class="custom-input daterange focus-visible" style="opacity:0" />
                        <div id="render-picker"></div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </span>


    <div class="row">
        <div class="col-lg-4 col-xl-3 col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted font-weight-normal mt-0 text-truncate small-chart-text" title="Site Visitors">Site Visitors</h5>
                            <h3 class="my-2 py-1 small-chart-number" id="site_visitors_text"></h3>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <div id="site_visitors_chart" data-colors="#10c469"></div>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
        <div class="col-lg-4 col-xl-3 col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted font-weight-normal mt-0 text-truncate small-chart-text" title="Add to Carts">Add to Carts</h5>
                            <h3 class="my-2 py-1 small-chart-number" id="addtocart_text"></h3>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <div id="addtocart_chart" data-colors="#536de6"></div>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
        <div class="col-lg-4 col-xl-3 col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-muted font-weight-normal mt-0 text-truncate small-chart-text" title="Abandon Checkout">Abandon Checkout</h5>
                            <h3 class="my-2 py-1 small-chart-number" id="abandon_checkout_text"></h3>
                        </div>
                        <div class="col-6">
                            <div class="text-right">
                                <div id="abandon_checkout_chart" data-colors="#10c469"></div>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->

        <div class="col-lg-3 col-xl-3 col-md-6"></div>



    </div>

    <!-- end row -->




    <div class="row mt-20 p-r-15 p-l-15">
        <div class=" col-lg-12 card  growth-bg">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <h2 class="heading-dashboard text-center mt-0 p-t-10">With Simplepost you made:</h2>
                        <div class="row no-gutters">
                            <div class="col-xl-2 .d-none d-lg-block d-xl-block d-md-none d-sm-none d-xs-none"></div>
                            <div class="col-sm-6 col-xl-3 col-lg-4 col-md-12">
                                <div class=" shadow-none m-0">
                                    <div class="card-body text-center">
                                        <i class="dripicons-briefcase text-muted" style="font-size: 24px;"></i>
                                        <h3 class="green-figures"><span id="revenue_text_green"></span></h3>
                                        <p class="mb-0 text-figures">Revenue</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3 col-lg-4 col-md-12">
                                <div class=" shadow-none m-0 ">
                                    <div class="card-body text-center">
                                        <i class="dripicons-checklist text-muted" style="font-size: 24px;"></i>
                                        <h3 class="green-figures"><span id="new_orders_text_green"></span></h3>
                                        <p class="mb-0 text-figures">New Orders</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3 col-lg-4 col-md-12">
                                <div class=" shadow-none m-0 ">
                                    <div class="card-body text-center">
                                        <i class="dripicons-user-group text-muted" style="font-size: 24px;"></i>
                                        <h3 class="green-figures"><span id="returns_text_green"></span></h3>
                                        <p class="mb-0 text-figures">Return on Ad Spend</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-2 .d-none d-lg-block d-xl-block d-md-none d-sm-none d-xs-none"></div>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->

    </div>


    <div class="row mt-20">
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="table-content mb-4">Revenue</h4>
                    <div dir="ltr">
                        <div id="revenue_chart" class="apex-charts"></div>
                    </div>
                    <div class="text-center">
                        <p class="custom-text">
                        <div class="green-dot"></div> <span id="revenue_chart_dates"></span></p>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="table-content mb-4">New Orders</h4>
                    <div dir="ltr">
                        <div id="new_orders_chart" class="apex-charts" data-colors="#6c757d,#727cf5"></div>
                    </div>
                    <div class="text-center">
                        <p class="custom-text">
                        <div class="green-dot"></div> <span id="new_orders_dates"></span></p>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="table-content mb-4">Return on Ad Spend</h4>
                    <div dir="ltr">
                        <div id="returns_chart" class="apex-charts" data-colors="#fa5c7c"></div>
                    </div>
                    <div class="text-center">
                        <p class="custom-text">
                        <div class="green-dot"></div> <span id="returns_dates"></span></p>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->


    <div class="row mt-20">

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="table-content mb-4">Total Cost</h4>
                    <div dir="ltr">
                        <div id="total_cost_chart" class="apex-charts" data-colors="#39afd1"></div>
                    </div>
                    <div class="text-center">
                        <p class="custom-text">
                        <div class="green-dot"></div> <span id="total_cost_dates"></span></p>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="table-content mb-4">Customer Acquisition Cost</h4>
                    <div id="line-chart-syncing2" data-colors="#727cf5"></div>
                    <div dir="ltr">
                        <div id="customer_aquisition_chart" class="apex-charts" data-colors="#6c757d"></div>
                    </div>
                    <div class="text-center">
                        <p class="custom-text">
                        <div class="green-dot"></div> <span id="customer_aquisition_dates"></span></p>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="table-content mb-4">Conversion Rate</h4>
                    <div dir="ltr">
                        <div id="covertion_rates_chart" class="apex-charts"></div>
                    </div>
                    <div class="text-center">
                        <p class="custom-text">
                        <div class="green-dot"></div> <span id="covertion_rates_dates"></span></p>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->
    <br>


    <script src="../js/analytics.js"></script>

</div>
@endsection