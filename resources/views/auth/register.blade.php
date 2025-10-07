<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Register - JILSorsogon MIS</title>
    <link rel="stylesheet" href="{{ asset('css/custom-login.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background-blur"></div>
    <header class="header-bar">
        <img src="{{ asset('unnamed.jpg') }}" alt="Logo" class="header-logo">
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
                    <p class="form-title">Create your account</p>
                    
                    @if ($errors->any())
                        <div class="error-message">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input id="name" type="text" name="name" placeholder="Enter your name..." value="{{ old('name') }}" required autofocus autocomplete="name">
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input id="email" type="email" name="email" placeholder="Enter your Active email..." value="{{ old('email') }}" required autocomplete="username">
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-phone"></i>
                            <input id="phone" type="tel" name="phone" placeholder="Enter your phone number..." value="{{ old('phone') }}" required maxlength="11" minlength="11" inputmode="numeric" pattern="\d{11}" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,11)">
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-calendar"></i>
                            <input id="birthday" type="date" name="birthday" value="{{ old('birthday') }}" required>
                        </div>
                        <div class="input-wrapper is-select">
                            <i class="fas fa-hashtag"></i>
                            <select id="age" name="age" required>
                                <option value="">Select your age</option>
                                @for ($i = 10; $i <= 100; $i++)
                                    <option value="{{ $i }}" {{ old('age') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-home"></i>
                            <input id="address" type="text" name="address" placeholder="Enter your address..." value="{{ old('address') }}" required>
                        </div>
                        <div class="input-wrapper is-select">
                            <i class="fas fa-venus-mars"></i>
                            <select id="gender" name="gender" required>
                                <option value="">Select your gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input id="password" type="password" name="password" placeholder="Enter your password..." required autocomplete="new-password">
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm your password..." required autocomplete="new-password">
                        </div>
                        <div class="input-wrapper is-select">
                            <i class="fas fa-church"></i>
                            <select id="preferred_chapter_id" name="preferred_chapter_id" required>
                                <option value="">Select your Sunday service chapter</option>
                                @if(isset($chapters))
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ old('preferred_chapter_id') == $chapter->id ? 'selected' : '' }}>
                                            {{ $chapter->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @if(!request()->has('invitation_code'))
                        <div class="input-wrapper">
                            <i class="fas fa-ticket-alt"></i>
                            <input id="invitation_code" 
                                   type="text" 
                                   name="invitation_code" 
                                   placeholder="Enter invitation code" 
                                   value="{{ request('invitation_code', old('invitation_code')) }}"
                                   {{ request()->has('invitation_code') ? 'readonly' : '' }}
                                   style="text-transform: uppercase">
                        </div>
                        @else
                            <input type="hidden" name="invitation_code" value="{{ request('invitation_code') }}">
                        @endif
                        <button type="submit" class="login-button">Register</button>
                    </form>
                    <div class="links">
                        <p>Already registered? <a href="{{ route('login') }}">Log In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>