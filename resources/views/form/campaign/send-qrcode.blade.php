<div class="row mt-20">
    <div class="col-lg-8"><small class="custom-text">Do you want to utilize a QR Code?</small></div>
    <div class="col-lg-4">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="qrCheck">
            <label class="custom-control-label custom-text" for="qrCheck">Yes</label>
        </div>
    </div>

</div>
<div class="row mt-20" id="qrPanel" style="display: none;">
    <div class="col-xl-12">
        <input type="text"  placeholder="Please enter QR Code link e.g. https://mystore.com/sale" name="qr_code_url" id="qrCode_url" value="{{ old('qr_code_url') }}" class="form-control custom-input">
    </div>
</div>
@push('js')
    <script>
        $("#qrCheck").change(function() {
            if (this.checked) {
                $("#qrPanel").slideDown();
            } else {
                $("#qrPanel").slideUp();
            }
        });
    </script>
@endpush
