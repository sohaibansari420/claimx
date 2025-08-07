@extends($activeTemplate . 'layouts.login_auth')

@section('content')
    <section class="tf-section project-info d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center">
                    <div class="glass-card p-4 text-center w-100" style="max-width: 420px;">

                        <form method="POST" action="{{ route('user.password.verify-code') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <h4 class="title mb-2">Verify Code</h4>
                            <p class="mb-4">Find email and verify code to reset password</p>

                            <div class="text-start">
                                <fieldset class="mb-3">
                                    <label for="code" class="form-label">Code</label>
                                    <input type="text" name="code" class="form-control" placeholder="@lang('Type Here...')" required>
                                    @error('code')
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

        .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
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
