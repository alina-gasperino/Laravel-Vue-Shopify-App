@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')

<!-- Include Apex Charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.31.0/apexcharts.min.js" integrity="sha512-5nh/cCgHEr1ncUodSt5m0z5vOsT+iJlRN9fUtuyc1hC4KPB/bMozP2hsGiWg7DmC8/+96ZowqLKKt7yqmVNW9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Manual Campaigns</h4>
            </div>
        </div>
    </div>
    <!-- This is the new User interface  -->
    <div class="row">
        <div class="col-lg-6">
            <form action="{{ Route('manualCampaigns.startCampaign') }}" method="post" name="campaign">
                @csrf
                @include('manual-campaigns.parts._campaign-name')

                @include('manual-campaigns.parts._select-postcard')
            </form>
                @include('manual-campaigns.parts._criteria')

                @include('manual-campaigns.parts._chart')

                @include('manual-campaigns.parts._start')
            
        </div>
        <div class="col-lg-6">
            
            @include('manual-campaigns.parts._campaign-estimator')
        
        </div>

    </div>


    <!-- Campaign overview modal -->

    <!-- Modal -->
    @include('manual-campaigns.parts._modals._campaign-overview')

    <script>
        "use strict";

        //Campaign overview code starts
        function launchCampaignModal() {
            $('#campaignOverviewModal').modal({
                backdrop: false,
                show: true
            });
        }
        // Campaign overview modal ends

        function NumericInput(inp) {
            var numericKeys = '0123456789';

            // restricts input to numeric keys 0-9
            inp.addEventListener('keypress', function(e) {
                var event = e || window.event;
                var target = event.target;

                if (event.charCode == 0) {
                    return;
                }

                if (-1 == numericKeys.indexOf(event.key)) {
                    // Could notify the user that 0-9 is only acceptable input.
                    event.preventDefault();
                    return;
                }
            });

            // add the thousands separator when the user blurs
            inp.addEventListener('blur', function(e) {
                var event = e || window.event;
                var target = event.target;

                var tmp = target.value.replace(/,/g, '');
                var val = Number(tmp).toLocaleString('en-CA');

                if (tmp == '') {
                    target.value = '';
                } else {
                    target.value = val;
                }
            });

            // strip the thousands separator when the user puts the input in focus.
            inp.addEventListener('focus', function(e) {
                var event = e || window.event;
                var target = event.target;
                var val = target.value.replace(/[,.]/g, '');

                target.value = val;
            });
        }

        var Amount = new NumericInput(document.getElementById("avg_order_val"));
        var AudienceSize = new NumericInput(document.getElementById("audience_inp"));


        var audienceSizeEle = document.getElementById("audience_inp");
        var averageOrderSizeEle = document.getElementById("avg_order_val");
        var conversionRate_LabelEle = document.getElementById("conversionRate_Label");
        var audienceSize = parseInt(audienceSizeEle.value);
        var averageOrderSize = parseInt(averageOrderSizeEle.value);

        audienceSizeEle.addEventListener('change', function() {
            audienceSize = parseInt(audienceSizeEle.value)
            updateCalculator();

        });

        averageOrderSizeEle.addEventListener('change', function() {
            averageOrderSize = parseInt(averageOrderSizeEle.value)
            if (averageOrderSize > 0) {
                updateCalculator();
            }
        });

        // conversionRate_LabelEle.addEventListener('input', function() {
        //     updateCalculator();
        // });


        function updateCalculator() {

            let audienceSize = audienceSizeEle.value;
            audienceSize = audienceSize.replace(/\D/g, '');

            let estMatch = '50%';
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

            let averageOrderSize = document.getElementById('avg_order_val').value;
            averageOrderSize = averageOrderSize.replace(/\D/g, '');

            let additionalRevenue = document.getElementById('additional-revenue');
            additionalRevenue.innerText = '$' + (Math.ceil(averageOrderSize * incrementalOrders.innerText)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            let roas = document.getElementById('return-on-ad-spend');
            let roasVal = averageOrderSize / costPerAcqRounded;
            roas.innerText = roasVal.toFixed(1) + 'x';

        }

        // Script written for range sliders

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
            updateCalculator();
        }, false);

        document.getElementById("conversionRate_ranger").oninput = function() {
            var value = (this.value - this.min) / (this.max - this.min) * 100
            this.style.background = 'linear-gradient(to right, #007200 0%, #007200 ' + value + '%, #fff ' + value + '%, white 100%)'
        };

        // Checkbox show/hide toggle function
        $("#customCheck1").change(function() {
            if (this.checked) {
                $("#panel").slideDown();
            } else {
                $("#panel").slideUp();
            }
        });


        function drawChart(series){

            $('#audience_chart').empty();

            //Apex Donut Chart whole #80b880
            var options = {
                chart: {
                    height: 350,
                    type: 'donut'
                },
                fill: {
                    colors: ['#80b880', '#007200']
                },
                legend: {
                    show: true,
                    showForSingleSeries: false,
                    showForNullSeries: true,
                    showForZeroSeries: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    floating: false,
                    fontSize: '14px',
                    fontFamily: 'Helvetica Neue Normal',
                    fontWeight: 400
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '50%'
                        },
                        expandOnClick: false
                    }
                },
                colors: ['#80b880', '#007200'],
                labels: ['Whole Audience (# and $ revenue focused)', 'Selected audience size (# and order value)'],
                series
            };
            
            var chart = new ApexCharts(document.querySelector("#audience_chart"), options);
            chart.render();
        }

        const postcard_audience = document.getElementById('postcard_audience');
        const spend_operator = document.getElementById('spend_operator');
        const input_value = document.getElementById('input_value');
        
        function getAudienceChartData(){
            let postcardAudienceVal = postcard_audience.value;
            let spendOperatorVal = spend_operator.value;
            let inputValueVal = input_value.value;
            
            $.ajax({
                method: "post",
                url: "/manual-campaigns/draw-data",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    audience:postcardAudienceVal,
                    operator:spendOperatorVal,
                    value:inputValueVal
                },
                dataType:'json',
                success:function(serialData) {
                    drawChart(serialData);
                },
                error: function(error) {
                    console.log('error', error)                                
                    drawChart([]);
                }
            });

        }

        postcard_audience.addEventListener('change', getAudienceChartData);
        spend_operator.addEventListener('change', getAudienceChartData);
        input_value.addEventListener('change', getAudienceChartData);

        getAudienceChartData();

    </script>


</div>
@endsection

<!-- so we get historical order data from stores, what we'd like to be able to do is graphically display and sort that order data into meaning along these lines: top [x%] of customers (note: customers can have multiple orders), customers who have purchased something more than [x] times, customers who have spent more than [$x]
so we will need a new page that is able to read and sort through the historical order data -->

@push('js')
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}" defer></script>
    <script>
        $('#submitCampaign').on('click', function (result) {
            $('.ibox').children('.ibox-content').toggleClass('sk-loading');
            var shopId = $('select[name="shop_id"]').val();
            $.ajax({
                method: "post",
                url: $("form").attr('action'),
                data: $("form").serialize()
            }).done(function(result) {
                $('.ibox').children('.ibox-content').toggleClass('sk-loading');
                window.location.href = result.success['redirect'];
            }).fail(function(result) {
                // $('#paymentModal').show();
                let obj = result.responseJSON.errors;

                let arr = Object.keys(obj).map(function (key) { return obj[key]; });

                swal({
                    title: "Form input error",
                    text: arr,
                    icon: "error",
                    confirmButtonColor: "#DD6B55",
                });

                $('.ibox').children('.ibox-content').toggleClass('sk-loading');
            });
        })
    </script>
@endpush

@push('css')
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endpush