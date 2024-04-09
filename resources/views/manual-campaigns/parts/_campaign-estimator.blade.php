<div class="ibox">
    <div class="ibox-title heading-dashboard">
        Campaign Estimator
    </div>
    <div class="ibox-content">

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="audience_inp" class="inputs-label">Audience Size</label>
                    <input type="text" id="audience_inp" class="form-control custom-input" value="1500">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="" class="inputs-label">Conversion Rate</label>
                    <input type="text" id="conversionRate_Label" class="form-control custom-input">
                    <div id="conversionRate">
                        <input id="conversionRate_ranger" value="5" type="range" min="1" max="10" step="1">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="avg_order_val" class="inputs-label">Average Order Value</label>
                    <input type="text" id="avg_order_val" class="form-control custom-input" value="150">
                </div>
            </div>
            <div class="col-lg-6">
                <button type="button" class="btn custom_launch_button" onclick="launchCampaignModal()">Launch Campaign</button>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-lg-12">
                <h2 class="heading-dashboard text-center p-t-25">What you’ll get</h2>
                <br>

                <div class="row no-gutters">
                    <div class="col-xl-4">
                        <div class=" shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-briefcase text-muted" style="font-size: 24px;"></i>
                                <h3 class="green-figures"><span id="postcards-to-send">750</span></h3>
                                <p class="mb-0 text-figures">Post Cards Sent</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class=" shadow-none m-0 ">
                            <div class="card-body text-center">
                                <i class="dripicons-checklist text-muted" style="font-size: 24px;"></i>
                                <h3 class="green-figures"><span id="total-post-card-cost">$1,125</span></h3>
                                <p class="mb-0 text-figures">Total Cost</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class=" shadow-none m-0 ">
                            <div class="card-body text-center">
                                <i class="dripicons-user-group text-muted" style="font-size: 24px;"></i>
                                <h3 class="green-figures"><span id="incremental-orders">38</span></h3>
                                <p class="mb-0 text-figures">Number of New Orders</p>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row no-gutters">
                    <div class="col-xl-4">
                        <div class=" shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-briefcase text-muted" style="font-size: 24px;"></i>
                                <h3 class="green-figures"><span id="additional-revenue">$3,800</span></h3>
                                <p class="mb-0 text-figures">New Revenue</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class=" shadow-none m-0 ">
                            <div class="card-body text-center">
                                <i class="dripicons-checklist text-muted" style="font-size: 24px;"></i>
                                <h3 class="green-figures"><span id="cost-per-acquisition">$30</span></h3>
                                <p class="mb-0 text-figures">Customer Acquisition Cost</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class=" shadow-none m-0 ">
                            <div class="card-body text-center">
                                <i class="dripicons-user-group text-muted" style="font-size: 24px;"></i>
                                <h3 class="green-figures"><span id="return-on-ad-spend">3.4x</span></h3>
                                <p class="mb-0 text-figures">Return on Ad Spend</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>