<div class="row mt-20">
    <div class="col-lg-8"><small class="custom-text">Do you want to limit the number of postcards sent on a monthly basis?</small></div>
    <div class="col-lg-4">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="customCheck1">
            <label class="custom-control-label custom-text" for="customCheck1">Yes</label>
        </div>
    </div>

</div>
<div class="row mt-20" id="panel" style="display: none;">
    <div class="col-lg-6 mt-10"><small class="custom-text">Please limit the number of postcards sent per month to: </small></div>
    <div class="col-lg-6">
        <input type="text"  name="max_sends" id="input_max_sends" value="{{ old('max_sends') }}" class="form-control custom-input">
    </div>
</div>
@push('js')
    <script>
        $("#customCheck1").change(function() {
            if (this.checked) {
                $("#panel").slideDown();
            } else {
                $("#panel").slideUp();
            }
        });
    </script>
@endpush
