@extends($activeTemplate . 'layouts.login_auth')

@section('content')

    <div class="glass-card text-center">
        <!-- Logo and Heading -->
        <h2 class="mb-1">
            <img class="brand-logo" src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" style="height: 50px;" alt="@lang('Logo')">
        </h2>
        <h6 class="mb-4">Enter your credentials to access your account</h6>

        <!-- Login Form -->
        <form method="POST" action="{{ route('user.login') }}">
            @csrf

            <div class="mb-3 text-start">
                <label for="username" class="form-label">User Name </label>
                <input type="text" name="username" class="form-control" placeholder="username" required>
            </div>

            <div class="mb-2 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            
            @if (reCaptcha())
                <div class="form-group mb-3">
                    @php echo reCaptcha(); @endphp
                </div>
            @endif
            @include($activeTemplate . 'partials.custom-captcha')

            <div class="mb-3 text-end">
                <a href="{{ route('user.password.request') }}">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Sign in</button>
        </form>

        <!-- Social Login -->
        

        <!-- Register -->
        <p>Don't have an account? <a href="{{ route('user.register') }}">Register for free</a></p>
    </div>
@endsection
@push('script')
    <script>
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
    </script>
@endpush

