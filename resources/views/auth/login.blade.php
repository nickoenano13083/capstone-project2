<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Login - JILSorsogon MIS</title>
    <link rel="stylesheet" href="{{ asset('css/custom-login.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background-blur"></div>
    <header class="header-bar">
        <img src="{{ asset(path: 'unnamed.jpg') }}" alt="Logo" class="header-logo">
        <div class="header-title">
            <span>JESUS IS LORD</span>
            <span>SORSOGON</span>
           
        </div>
        <div class="header-subtitle">
            CHURCH MANAGEMENT SYSTEM
        </div>
    </header>

    <main class="main-container">
        <div class="login-container">
            <div class="login-left">
                <img src="{{ asset('jil-sorsogon-white.png') }}" alt="Church Logo">
            </div>
            <div class="login-right">
                <div class="login-form-container">
                    <img src="{{ asset('unnamed.jpg') }}" alt="Logo" class="form-logo">
                    <p class="form-title">Sign in your valid credentials to continue</p>

                    @if (session('status'))
                        <div class="status-message">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="error-message">
                            @foreach ($errors->all() as $error)
                                <div>{{$error}}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Enter your email..." required value="{{ old('email') }}">
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Enter your password..." required>
                        </div>
                        <button type="submit" class="login-button">Log In</button>
                    </form>

                    <div class="links">
                        <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
                        @if (Route::has('password.request'))
                        <p><a href="{{ route('password.request') }}">Forgot password? <i class="fas fa-question-circle"></i></a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
