<div class="ibox">
    <div class="ibox-title heading-dashboard">
        Step 2: Set Your Rules
    </div>
    <div class="ibox-content">
        <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
        </div>
        <table class="table table-responsiveness">
            <thead>
            <tr>
                <th scope="col" class="inputs-label">Target Audience</th>
                <th scope="col" class="inputs-label">Audience Size</th>
                <th scope="col" class="inputs-label">Est. Address Match (30%)</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($audienceSizes as $audienceSize)
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="audience" id="{{ $audienceSize->name }}" value="{{ $audienceSize->id }}"
                            @if($audienceSize->id == 10) checked="checked" @endif
                            >
                            <label class="form-check-label table-content" for="{{ $audienceSize->name }}">
                                {{ $audienceSize->name }}
                            </label>
                        </div>
                    </td>
                    <td class="table-content">50,000</td>
                    <td class="table-content">15,000</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    $('input[type=radio][name=audience]').change(function() {
 console.log("this.value", this.value);
});
</script>