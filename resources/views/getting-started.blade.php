@extends('layouts.app', ['activePage' => 'design', 'titlePage' => __('Select A Postcard')])

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12">
                <div class="card growth-bg">
                    <div class="card-body">
                        <h4 class="heading-dashboard mb-20 mb-3">With SimplePost, access the power of direct mail!</h4>
                        <p class="custom-text" style="color: #387f37;font-family: 'Helvetica Neue Bold';line-height: 1.5em;">Direct mail is a great way to both keep in touch with existing customers and expand your customer base through automated retargeting.</p>
                        <p>Our research shows that direct mail has a significantly higher both open and conversion rate than traditional email marketing campaigns.</p>
                        <p>Additionally, our research shows that customers prefer having physical contact with brands and that physical advertising is an important part of any brands marketing mix.</p>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <iframe style="width:100%;" width="420" height="415" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="ibox">
                                    <div class="ibox-content">
                                        <p class="heading-dashboard">
                                            <i class="fa fa-bullhorn"></i> Automated Retargeting:
                                        </p>
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="custom-text">Send website vistors direct mail discounts through our proprietary IP -> physical address process! You can set rules for specific actions (such as just visitors or add to carts or abandon checkouts) and send only those higher potential converting customers specific discounts for them.</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">

                                                <a href="/automated-retargeting" type="button" class="btn custom-button btn" style="    float: right;    width: 100%;    max-width: 250px;">Automated Retargeting </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="ibox">
                                    <div class="ibox-content">
                                        <p class="heading-dashboard">
                                            <i class="fa fa-bullseye"></i> Manual Retargeting:
                                        </p>
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="custom-text">Send direct mail discounts to specific segments of your customer base with a few clicks of a button! you’ve spent money acquiring these customers, remind them how much they enjoy the brand with unique coupons for their behavior.</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">

                                                <a href="/manual-campaigns" type="button" class="btn custom-button btn" style="    float: right;    width: 100%;    max-width: 250px;">Manual Retargeting</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
</div>
@endsection