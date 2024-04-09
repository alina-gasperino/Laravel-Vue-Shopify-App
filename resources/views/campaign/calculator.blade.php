<div class="row">
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title heading-dashboard">
                Campaign Estimator
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="audience-size" class="inputs-label">Audience Size</label>
                            <input type="text" id="audience-size" class="form-control custom-input" value="1500">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="inputs-label">Estimated Address Match</label>
                            <input type="text" id="eAddressMatch_Label" class="form-control custom-input">
                            <div id="eAddressMatch">
                                <input id="eAddressMatch_ranger" value="30" type="range" min="15" max="50" step="5">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-b-lg">
                    <div class="col-lg-12">
                        <div id="drag-fixed" class="slider_red"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="inputs-label">Conversion Rate</label>
                            <input type="text" id="conversionRate_Label" class="form-control custom-input">
                            <div id="conversionRate">
                                <input id="conversionRate_ranger" value="5" type="range" min="1" max="10" step="1">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="average-order-size" class="inputs-label">Average Order Value</label>

                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                    <span class="input-group-text">$</span></span>
                                <input data-toggle="touchspin" data-bts-prefix="$" type="text" id="average-order-size" class="form-control custom-input" value="150">
                            </div>


                        </div>
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
    </div>
</div>


@push('css')
<link href="{{ asset('css/plugins/nouislider/jquery.nouislider.css') }}" rel="stylesheet">
@endpush
@push('onload-js')
<script src="{{ asset('js/plugins/nouislider/jquery.nouislider.min.js') }}" defer></script>
<script src="{{ asset('js/plugins/wnumb/wnumb.min.js') }}" defer></script>
@endpush
@push('js')
<script>
    $(document).ready(function() {

        document.getElementById("eAddressMatch_ranger").oninput = function() {
            var value = (this.value - this.min) / (this.max - this.min) * 100
            this.style.background = 'linear-gradient(to right, #007200 0%, #007200 ' + value + '%, #fff ' + value + '%, white 100%)'
        };

        const comission = document.querySelector('#eAddressMatch input');
        const comissionLabel = document.getElementById('eAddressMatch_Label');
        const comissionLabelPrefix = comissionLabel.innerHTML;
        const comissionRange = document.createElement('label');

        comissionRange.id = 'rangeLimits';
        comissionRange.className = 'row';
        comissionRange.innerHTML = `<span class="col-6 text-left faded-range">${comission.getAttribute('min')}%</span><span class="col-6 text-right faded-range">${comission.getAttribute('max')}%</span>`;
        document.querySelector('#eAddressMatch').appendChild(comissionRange);

        comissionLabel.value = `${comission.value}%`;

        comission.addEventListener('input', function() {
            comissionLabel.value = `${Number(comission.value)}%`;
            updateCalculator()
        }, false);


        const conversionRate = document.querySelector('#conversionRate input');
        const conversionRateLabel = document.getElementById('conversionRate_Label');
        const conversionRateLabelPrefix = conversionRateLabel.innerHTML;
        const conversionRateRange = document.createElement('label');

        conversionRateRange.id = 'rangeLimits_conversionRate';
        conversionRateRange.className = 'row';
        conversionRateRange.innerHTML = `<span class="col-6 text-left faded-range">${conversionRate.getAttribute('min')}%</span><span class="col-6 text-right faded-range">${conversionRate.getAttribute('max')}%</span>`;
        document.querySelector('#conversionRate').appendChild(conversionRateRange);

        conversionRateLabel.value = `${conversionRate.value}%`;

        conversionRate.addEventListener('input', function() {
            conversionRateLabel.value = `${Number(conversionRate.value)}%`;
            updateCalculator()
        }, false);

        document.getElementById("conversionRate_ranger").oninput = function() {
            var value = (this.value - this.min) / (this.max - this.min) * 100
            this.style.background = 'linear-gradient(to right, #007200 0%, #007200 ' + value + '%, #fff ' + value + '%, white 100%)'
        };

        updateCalculator();
        //var conversionRate = document.getElementById('conversion-rate');
        var audienceSize = document.getElementById('audience-size');
        var averageOrderSize = document.getElementById('average-order-size');


        audienceSize.addEventListener('change', function() {
            audienceSizeVal = parseInt(audienceSize.value)
            updateCalculator();

        });

        averageOrderSize.addEventListener('change', function() {
            averageOrderSizeVal = parseInt(averageOrderSize.value)
            if (averageOrderSizeVal > 0) {
                updateCalculator();
            }
        });


        function updateCalculator() {

            let audienceSize = document.getElementById('audience-size').value;
            audienceSize = audienceSize.replace(/\D/g, '');

            let estMatch = document.getElementById('eAddressMatch_Label').value;
            estMatch = estMatch.replace(/\D/g, '');

            let postCardsToSend = document.getElementById('postcards-to-send');
            postCardsToSend.innerText = Math.ceil(estMatch / 100 * audienceSize);

            let conversionRate = document.getElementById('conversionRate_Label').value
            conversionRate = conversionRate.replace(/\D/g, '');

            let incrementalOrders = document.getElementById('incremental-orders');
            incrementalOrders.innerText = Math.ceil(conversionRate / 100 * postCardsToSend.innerText);
            let totalPostCardCost = document.getElementById('total-post-card-cost');
            let totalPostCardCostVal = Math.round(1.5 * postCardsToSend.innerText);
            totalPostCardCost.innerText = '$' + totalPostCardCostVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            let costPerAcquisition = document.getElementById('cost-per-acquisition');
            let costPerAcqRounded = totalPostCardCost.innerText.substring(1, totalPostCardCost.innerText.length).replace(',', '') / incrementalOrders.innerText.replace(',', '');
           // costPerAcquisition.innerText = '$' + costPerAcqRounded.toFixed(2)
            costPerAcquisition.innerText = '$' + Math.round(costPerAcqRounded);

            let averageOrderSize = document.getElementById('average-order-size').value;
            averageOrderSize = averageOrderSize.replace(/\D/g, '');

            let additionalRevenue = document.getElementById('additional-revenue');
            additionalRevenue.innerText = '$' + (Math.ceil(averageOrderSize * incrementalOrders.innerText)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            let roas = document.getElementById('return-on-ad-spend');
            roasVal = averageOrderSize / costPerAcqRounded;
            roas.innerText = roasVal.toFixed(1) + 'x';

        }


        // var audience_size_slider = document.getElementById('audience-size-slider');
        //
        // noUiSlider.create(audience_size_slider, {
        //     start: 10000,
        //     behaviour: 'tap-drag',
        //     connect: 'upper',
        //     range: {
        //         'min':  600,
        //         'max':  15000
        //     }
        // });
        //
        // var inputFormat = document.getElementById('audience-size');
        //
        // audience_size_slider.noUiSlider.on('update', function (values, handle) {
        //     inputFormat.innerText = Math.round(values[handle]);
        // });
        //
        // inputFormat.addEventListener('change', function () {
        //     audience_size_slider.noUiSlider.set(this.value);
        // });
        //
        //
        // var conversion_rate_slider = document.getElementById('conversion-rate-slider');
        //
        // noUiSlider.create(conversion_rate_slider, {
        //     start: 2,
        //     behaviour: 'tap-drag',
        //     connect: 'upper',
        //     range: {
        //         'min':  1,
        //         'max':  5
        //     }
        // });
        //
        // var conversionRate = document.getElementById('conversion-rate');
        //
        // conversion_rate_slider.noUiSlider.on('update', function (values, handle) {
        //     conversionRate.innerText = Math.round(values[handle]) + '%';
        // });
    });
</script>
@endpush