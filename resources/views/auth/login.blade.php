<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* خلفية مريحة */
        body {
            background:white;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden;
        }

        /* تصميم الفورم */
        .login-form {
            background: #ffffff;
            border-radius: 15px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            animation: fadeIn 1s ease-in-out;
            position: relative;
        }

        /* تأثيرات الحركية عند تحميل الفورم */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* عنوان */
        .login-form h1 {
            font-size: 26px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #34495e;
            animation: slideDown 1s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* تصميم الحقول */
        .login-form .form-control {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            padding: 12px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-form .form-control:focus {
            border-color: #3498db;
            box-shadow: 0px 6px 10px rgba(52, 152, 219, 0.3);
            transform: scale(1.02);
        }

        /* الزر */
        .login-form .btn {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            border: none;
            padding: 12px 20px;
            color: #fff;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .login-form .btn:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            transform: translateY(-3px);
        }

        /* الروابط */
        .login-form .form-actions a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .login-form .form-actions a:hover {
            color: #2c3e50;
        }

        /* التأثير على أجهزة صغيرة */
        @media (max-width: 768px) {
            .login-form {
                padding: 30px;
            }

            .login-form h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf
        <h1>  welcom again!</h1>

        <div class="form-group">
            <input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autofocus placeholder="email">
        </div>

        <div class="form-group">
            <input id="password" class="form-control form-control-lg" type="password" name="password" required placeholder="password">
        </div>

        <div class="form-check d-flex align-items-center mb-3">
            <input class="form-check-input me-2" type="checkbox" id="remember_me" name="remember">
            <label for="remember_me" class="form-check-label text-muted">
                تذكرني
            </label>
        </div>

        <div class="form-actions mb-3 d-flex justify-content-between">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">  forget password?</a>
            @endif
            <a href="{{ route('register') }}">register new account</a>
        </div>

        <button type="submit" class="btn">{{ __('login') }}</button>
    </form>
</body>
