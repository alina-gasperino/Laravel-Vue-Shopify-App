@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Campaign Overview')])

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha512-oBTprMeNEKCnqfuqKd6sbvFzmFQtlXS3e0C/RGFV0hD6QzhHV+ODfaQbAlmY6/q0ubbwlAM/nCJjkrgA3waLzg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="wrapper wrapper-content animated fadeInRight">

    <!-- This is the new User interface  -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <!-- <div class="ibox-title heading-dashboard">
                            Account
                        </div> -->
                        <div class="ibox-content responsive-ibox">
                            <div class="tabbable-panel">
                                <div class="tabbable-line">
                                    <div class="heading-dashboard p-l-10">
                                        Account
                                    </div>
                                    <ul class="nav nav-tabs ">
                                        <li class="active">
                                            <a href="#overview_tab" data-toggle="tab">
                                                Overview </a>
                                        </li>
                                        <li>
                                            <a href="#billing_history_tab" data-toggle="tab">
                                                Billing History </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane p-t-25 active" id="overview_tab">

                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="header-title mb-3">Billing</h4>
                                                    <div class="table-responsive">
                                                        <table class="table table-centered table-nowrap table-hover mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <h5 class="font-14 my-1"><a href="javascript:void(0);" class="inputs-label">Billing Cycle</a></h5>

                                                                    </td>
                                                                    <td></td>
                                                                    <td>
                                                                        <h5 class="custom-text">Dec, 01 2021 to Dec, 31 2021</h5>
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <h5 class="font-14 my-1"><a href="javascript:void(0);" class="inputs-label">Billing Information</a></h5>

                                                                    </td>
                                                                    <td></td>
                                                                    <td>
                                                                        <h5 class="custom-text"><a href="#">Update Card</a></h5>
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <h5 class="font-14 my-1"><a href="javascript:void(0);" class="inputs-label">Monthly Total</a></h5>

                                                                    </td>
                                                                    <td></td>
                                                                    <td>
                                                                        <select class="form-control custom-text" id="monthly_total">
                                                                            <option>$0 USD</option>
                                                                            <option>$5 USD</option>
                                                                            <option>$10 USD</option>
                                                                        </select>

                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>




                                                            </tbody>
                                                        </table>
                                                    </div> <!-- end table-responsive-->

                                                </div> <!-- end card body-->
                                            </div> <!-- end card -->

                                        </div>
                                        <div class="tab-pane" id="billing_history_tab">

                                            <div class="card-body">
                                                <!-- <h4 class="header-title mb-3">Billing History</h4> -->
                                                <ul class="nav nav-tabs mb-3 no_border" id="sub_nav">
                                                    <li>
                                                        <a href="#email_plans" data-toggle="tab" aria-expanded="false" class="custom_nav_link nav-link no_border">
                                                            <span class="d-md-block">Email Plans</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#sms_plans" data-toggle="tab" aria-expanded="false" class="custom_nav_link nav-link no_border">
                                                            <span class="d-md-block">SMS Plans</span>
                                                        </a>
                                                    </li>
                                                    <li class="active">
                                                        <a id="default_tab" href="#payment_history" data-toggle="tab" aria-expanded="true" class="custom_nav_link nav-link no_border">
                                                            <span class="d-md-block">Payment History</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#billing_pref" data-toggle="tab" aria-expanded="false" class="custom_nav_link nav-link no_border">
                                                            <span class="d-md-block">Billing Preferences</span>
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content">

                                                    <div class="tab-pane" id="email_plans">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="header-title mb-3">email_plans</h4>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="sms_plans">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="header-title mb-3">sms_plans</h4>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane show active" id="payment_history">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="header-title mb-3">Billing History</h4>
                                                                <div class="row mb-2">
                                                                    <div class="col-lg-12">
                                                                        <form class="form-inline">

                                                                            <div class="form-group mx-sm-1 mb-2">

                                                                                <select class="custom-input table_select">
                                                                                    <option selected="">Date Range</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mx-sm-1 mb-2">

                                                                                <select class="custom-input table_select">
                                                                                    <option selected="">Invoice Status</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mx-sm-1 mb-2">

                                                                                <select class="custom-input table_select">
                                                                                    <option selected="">Rows Per Page</option>
                                                                                </select>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- end col-->
                                                                </div>

                                                                <div class="table-responsive">
                                                                    <table class="table table-centered mb-0">
                                                                        <thead class="thead-light">
                                                                            <tr>
                                                                                <th style="width: 20px;">
                                                                                    <div class="custom-control custom-checkbox">
                                                                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                                                        <label class="custom-control-label" for="customCheck1">&nbsp;</label>
                                                                                    </div>
                                                                                </th>
                                                                                <th>Invoice</th>
                                                                                <th>Date</th>
                                                                                <th>Amount</th>
                                                                                <th>Payment Method</th>
                                                                                <th>Payment Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="custom-control custom-checkbox">
                                                                                        <input type="checkbox" class="custom-control-input" id="customCheck11">
                                                                                        <label class="custom-control-label" for="customCheck11">&nbsp;</label>
                                                                                    </div>
                                                                                </td>
                                                                                <td><a href="#" class="text-body font-weight-bold">#BM9699</a> </td>
                                                                                <td>January 17 2018</td>
                                                                                <td>
                                                                                    $5,177.68
                                                                                </td>
                                                                                <td>
                                                                                    Mastercard
                                                                                </td>

                                                                                <td>
                                                                                    <h5><span class="badge badge-success-lighten"><i class="mdi mdi-coin"></i> Paid</span></h5>
                                                                                </td>


                                                                            </tr>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="billing_pref">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="header-title mb-3">billing_pref</h4>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div> <!-- end card body-->


                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>

    <!-- New interface ends here -->
</div>
@endsection