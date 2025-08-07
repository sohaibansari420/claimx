@extends($activeTemplate . 'layouts.login_auth')

@section('content')
    <section class="tf-section project-info d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center">
                    <div class="glass-card p-4 text-center w-100" style="max-width: 420px;">
                        
                        <form method="post" action="{{ route('user.password.email') }}">
                            @csrf

                            <h4 class="title mb-2">Forget Password</h4> 
                            <p class="mb-4 small">Enter your email address or username below and we’ll send you reset instructions.</p>

                            <div class="text-start">
                                <!-- Select Type -->
                                <fieldset class="mb-3">
                                    <label for="type" class="form-label">Select Type</label>
                                    <select name="type" class="form-select">
                                        <option value="email">@lang('E-Mail Address')</option>
                                        <option value="username">@lang('Username')</option>
                                    </select>
                                </fieldset>

                                <!-- Email / Username -->
                                <fieldset class="mb-3">
                                    <label for="value" class="form-label">Email / Username</label>
                                    <input type="text" name="value" class="form-control" value="{{ old('value') }}" placeholder="@lang('Type Here...')" required>
                                    @error('value')
                                        <span class="text-danger small d-block mt-1">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </fieldset>
                            </div>

                            <div class="text-start mb-3">
                                Nevermind? <a href="{{ route('user.login') }}" class="text-white-50">Sign in</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Reset Password
                            </button>
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
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: white;
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
        }

        .form-control::placeholder, .form-select {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
        }

        a {
            color: #cbd5e0;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@push('script')
    <script type="text/javascript">
        $('select[name=type]').change(function() {
            $('.my_value').text($('select[name=type] :selected').text());
        }).change();
    </script>
@endpush
