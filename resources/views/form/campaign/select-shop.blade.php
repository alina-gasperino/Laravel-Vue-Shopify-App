<div class="ibox">
    <div class="ibox-title heading-dashboard">
        Step 1: Select Your Shop
    </div>
    <div class="ibox-content">
        <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
        </div>
        <select class="form-control m-b" name="shop_id" id="input_shop_id">
            @foreach($availableShops as $shop)
            <option value="{{$shop->id}}" @if(count($availableShops)=='1' ) selected @endif @if(old('shop_id')=='{{$shop->id}}' ) selected @endif>{{$shop->shop_name}}</option>
            @endforeach
        </select>
        <div class="form-group mb-3">
            <label for="campaign_name" class="inputs-label">Name Your Campaign</label>
            <input type="text" class="form-control custom-input" name="campaign_name" id="campaign_name">
        </div>
    </div>
</div>