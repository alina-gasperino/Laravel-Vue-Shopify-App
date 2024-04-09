<div class="modal inmodal fade" id="paymentModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Add a Subscription</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Campaign Overview</h5>
                    </div>
                    <div class="ibox-content">
                        $1.50 per postcard sent.
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ Route('startSubscription') }}" method="post">
            @csrf
            <input hidden name="payment_shop_id" value=""/>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div id="card-element"></div>
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Add a Payment Method</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div id="card-element"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Name on card</label>
                                        <input type="text" class="form-control" name="fullname"
                                               placeholder="Full name" value="{{old('fullname')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Credit Card Number</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="number"
                                                   placeholder="Card Number"
                                                   required="required"
                                                   value="{{old('number')}}">
                                            <span class="input-group-addon"><i
                                                    class="fa fa-credit-card"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7 col-md-7">
                                    <div class="form-group">
                                        <label>Expiration Date</label>
                                        <input type="text" class="form-control" name="expiration"
                                               placeholder="MM / YY" required="required"
                                               value="{{old('expiration')}}">
                                    </div>
                                </div>
                                <div class="col-5 col-md-5 float-right">
                                    <div class="form-group">
                                        <label>CVV</label>
                                        <input type="text" class="form-control" name="cvv"
                                               placeholder="CVV" required="required"
                                               value="{{old('cvv')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Billing Address</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Street Address</label>
                                        <input type="text" class="form-control" name="address1"
                                               placeholder="Street address"
                                               value="{{old('address1')}}">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="address2"
                                               placeholder="Apt, suite, building (optional)"
                                               value="{{old('address2')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7 col-md-7">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="city"
                                               placeholder="e.g Boston" required="required"
                                               value="{{old('city')}} ">
                                    </div>
                                </div>
                                <div class="col-5 col-md-5 float-right">
                                    <div class="form-group">
                                        <label>State/Providence</label>
                                        <input type="text" class="form-control" name="state"
                                               placeholder="e.g. MA" required="required"
                                               value="{{old('state')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7 col-md-7">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control" name="country"
                                               required="required" value="{{old('country')}}">
                                    </div>
                                </div>
                                <div class="col-5 col-md-5 float-right">
                                    <div class="form-group">
                                        <label>Postal / Zip Code</label>
                                        <input type="text" class="form-control" name="zip"
                                               placeholder="e.g. 01234" required="required"
                                               value="{{old('zip')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-primary btn-lg" name="submit" value="start"
                                            type="submit">
                                        Start Campaign
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
