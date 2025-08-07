@if(\App\Models\Extension::where('act', 'custom-captcha')->where('status', 1)->first())
    <div class="mb-2 text-start">
        <div class="input-group">
            @php echo  getCustomCaptcha($height = 46, $width = '100%', $bgcolor = '#171F29', $textcolor = '#798DA3') @endphp
        </div>
    </div>
    <div class="mb-2 text-start">
        <label>
            @lang('Captcha Code') *
        </label>
        <input type="text" name="captcha" class="form-control" maxlength="6" placeholder="@lang('Enter Code')" required>
    </div>
@endif
