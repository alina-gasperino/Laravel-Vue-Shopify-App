<div class="ibox">
    <div class="ibox-title heading-dashboard">
        Campaign Criteria
    </div>
    <div class="ibox-content">
        <p class="custom-text">
            I want to send this postcard with this offer to this audience:
        </p>
        <div class="row">
            <div class="col-12">
                <select class="form-control custom-input" id="postcard_audience" name="postcard_audience">
                    @foreach(HelperService::postcardAudienceOptions() as $option)
                    <option>{{$option}}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="row mt-20">
            <div class="col-3 pr-1">
                <input type="text" id="customer_spend" class="form-control custom-input" value="Customer Spend">
            </div>
            <div class="col-3 pl-1 pr-1">
                <select class="form-control custom-input" id="spend_operator" name="spend_operator">
                    <option>equals</option>
                    <option>greator to</option>
                    <option>less than</option>
                </select>
            </div>
            <div class="col-6 pl-1"><input type="text" id="input_value" name="input_value" class="form-control custom-input" value="100"></div>
        </div>
    </div>
</div>