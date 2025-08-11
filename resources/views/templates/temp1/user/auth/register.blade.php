@extends($activeTemplate . 'layouts.login_auth')

@section('content')
<section class="tf-section project-info d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div class="glass-card p-4 w-100" style="max-width: 700px;">

                    <form method="post" action="{{ route('user.register') }}" onsubmit="return submitUserForm();">
                        @csrf

                        <div class="text-center mb-4">
                            <h4 class="text-white mb-2">Register</h4>
                            <p class="text-light small">Welcome to Claim X, please enter your details</p>
                            <a href="{{ route('user.login') }}" class="text-white-50">Already have an account? Login</a>
                        </div>

                        <div class="row g-3">
                            @if ($ref_user == null)
                                <div class="col-md-6">
                                    <label class="form-label">Referral Username *</label>
                                    <input type="text" name="referral" class="form-control" placeholder="@lang('Enter referral username')" required autofocus>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label class="form-label">Referral Username</label>
                                    <input type="text" name="referral" class="form-control" value="{{ $ref_user->username }}" readonly>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="firstname" class="form-control" pattern="[A-Za-z]+" value="{{ old('firstname') }}" placeholder="Enter first name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="lastname" class="form-control" pattern="[A-Za-z]+" value="{{ old('lastname') }}" placeholder="Enter last name" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Mobile Number *</label>
                                <div class="input-group">
                                    <select name="country_code" class="form-select select2" id="select2" style="max-width: 100px;">
                                        @include('partials.country_code')
                                    </select>
                                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="Your mobile number" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Country *</label>
                                <input type="text" name="country" class="form-control" placeholder="Country" readonly>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Username *</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Enter username" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password *</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            </div>

                            @if (reCaptcha())
                                <div class="col-md-12">
                                    @php echo reCaptcha(); @endphp
                                    <div id="g-recaptcha-error"></div>
                                </div>
                            @endif

                            @include($activeTemplate . 'partials.custom-captcha')

                            <div class="col-md-12 form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox" name="checkbox">
                                <label class="form-check-label text-white-50" for="checkbox">
                                    I accept the Terms & Privacy Policy
                                </label>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .form-control, .form-select {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        border: none;
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    .form-control:focus, .form-select:focus {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        box-shadow: none;
    }
    .form-check-label {
        font-size: 0.9rem;
    }
</style>
@endpush

@push('script')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    (function ($) {
        "use strict";

        @if (@$country_code)
        $(`option[data-code={{ $country_code }}]`).attr('selected', '');
        @endif

        $('select[name=country_code]').change(function () {
            $('input[name=country]').val($('select[name=country_code] :selected').data('country'));
        }).change();

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang('Captcha field is required.')</span>';
                return false;
            }
            return true;
        }

        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
        $('#select2').select2({
            placeholder: "Search country code",
            allowClear: false,
            minimumResultsForSearch: 0
        });
    })(jQuery);
</script>
@endpush
