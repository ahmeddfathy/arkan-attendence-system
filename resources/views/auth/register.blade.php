<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background styling */
        body {
            background: white;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        /* Form card styling */
        .auth-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Title styling */
        .auth-card h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333333;
            text-align: center;
            font-weight: bold;
        }

        /* Input fields */
        .auth-card .form-control {
            border-radius: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            font-size: 14px;
            padding: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .auth-card .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.2);
        }

        /* Button styling */
        .auth-card .btn {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            border: none;
            padding: 12px 20px;
            color: #fff;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .auth-card .btn:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            transform: translateY(-3px);
        }

        /* Link styling */
        .auth-card a {
            color: #6a11cb;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .auth-card a:hover {
            color: #2575fc;
        }

        /* Additional spacing */
        .auth-card .mt-4 {
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .auth-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-card">
    
        <h1>{{ __('Create an Account') }}</h1>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <x-label for="terms">
                    <div class="d-flex align-items-center">
                        <x-checkbox name="terms" id="terms" required />
                        <span class="ms-2 text-muted">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">'.__('Terms of Service').'</a>',
                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </span>
                    </div>
                </x-label>
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('login') }}">{{ __('Already registered? Login here') }}</a>
                <x-button class="btn">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>

    </div>

</body>
