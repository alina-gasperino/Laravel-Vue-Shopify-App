<div class="ibox">
    <div class="ibox-title heading-dashboard">
        Step 4: Create Your Offer
    </div>
    <div class="ibox-content">
        <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
        </div>
        <p class="custom-text">Statistics show that customers are more likely to convert when presented an offer.
            Each customer’s
            post card will come with a custom printed code they can use at checkout</p>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="inputs-label" for="input_discount_type">Offer Type</label>
                    <select class="form-control custom-input" name="discount_type" id="input_discount_type">
                        <option value="1">% Off</option>
                        <option value="2">Flat Discount</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="input_discount_amount" class="inputs-label">Amount</label>
                    <input type="text" class="form-control custom-input" name="discount_amount" id="input_discount_amount" value="10">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <label for="input_discount_prefix" class="inputs-label">Discount Prefix</label>
                <input class="form-control" name="discount_prefix" id="input_discount_prefix" value="{{ preg_replace("/[^A-Z0-9 ]/", '', strtoupper(substr($userShop, 0,6))) }}">
            </div>
            <div class="col-lg-6">
                <p id="discount-code-sample" class="faded-text"></p>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-lg-8"><small class="custom-text">Do you want to utilize a QR Code?</small></div>
            <div class="col-lg-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="hasQR">
                    <label class="custom-control-label custom-text" for="hasQR">Yes</label>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="linkContainer" style="display:none;">
                <input type="text" class="form-control" id="storeLink" />
            </div>
            <div class="col-lg-12" style="padding-top: 20px">
                <a class="btn custom-button" id="submitCampaign" value="start" type="submit" href="javascript:void(0)">Start
                    Campaign</a>
            </div>           
        </div>
        @include('form.campaign.limit-send')
    </div>
</div>


@push('js')
<script>
    $(document).ready(function() {
        let shopName = $('#input_shop_id option:selected').text();
        let storeLink = document.getElementById('storeLink');
        let discountPrefix = document.getElementById('input_discount_prefix');

        function getStoreLink(value) {
            return "https://" + shopName + "/discount/" + value + "?redirect=/collections/all"
        }

        storeLink.value = getStoreLink(discountPrefix.value)

        $("#hasQR").change(function() {
            if (this.checked) {
                $("#linkContainer").slideDown();
            } else {
                $("#linkContainer").slideUp();
            }
        });

        let discountType = document.getElementById('input_discount_type');
        let discountAmount = document.getElementById('input_discount_amount');
        updateOfferAmount(discountType.value)
        updateOfferDescription(discountPrefix.value, discountAmount.value)


        discountType.addEventListener('change', function() {
            updateOfferAmount(this.value)
            updateOfferDescription(discountPrefix.value, discountAmount.value)
        });

        discountAmount.addEventListener('change', function() {
            updateOfferAmount(discountType.value)
            updateOfferDescription(discountPrefix.value, discountAmount.value)
        });

        discountPrefix.addEventListener('change', function() {
            updateOfferDescription(discountPrefix.value, discountAmount.value)
        });
        $(discountPrefix).on('keyup', function() {
            storeLink.value = getStoreLink(discountPrefix.value)
        })

    });

    function updateOfferAmount($offerType) {
        let discountAmount = document.getElementById('input_discount_amount');
        let discountAmountVal = discountAmount.value
        discountAmountVal = discountAmountVal.replace(/\D/g, '');
        if ($offerType == 1) {
            discountAmount.value = discountAmountVal + '%';
        } else if ($offerType == 2) {
            discountAmount.value = '$' + discountAmountVal;
        }
    }

    function updateOfferDescription($discountPrefix, $discountAmount) {
        let discountDescription = document.getElementById('discount-code-sample');
        discountDescription.innerText = 'Ex. Code: `' + $discountPrefix + '-ABC123` grants ' + $discountAmount + ' off purchases'

    }
</script>
@endpush