<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In | ASK Me</title>
	<link rel="shortcut icon" href="{{ asset('bower_components/askme-style/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/login-signup-form-style/fonts/material-icon/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/login-signup-form-style/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="main">
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="{{ asset('bower_components/login-signup-form-style/images/signin-image.jpg') }}" alt="sing up image"></figure>
                        <a href="{{ route('register') }}" class="signup-image-link">Create an account</a>
                    </div>
                    <div class="signin-form">
                        <h2 class="form-title">Log in to <a href="{{ route('home') }}" class="home">ASKme</a></h2>
                        <form method="POST" action="{{ route('login') }}" class="register-form" id="login-form">
                            @csrf 
                            @error('email')
                                <div class="form-group"> 
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                </div>
                            @enderror
                            <div class="form-group">
                                <label for="your_name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input id="email" type="email" placeholder="E-mail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            </div> 
                            <div class="form-group">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember-me" class="label-agree-term"><span></span>Remember me</label>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                            </div>
                           <div class="form-group">
                                <a href="{{ route('password.request') }}" class="forgot-password">Forgot your password</a>
                            </div>
                        </form>
                        <div class="social-login">
                            <span class="social-label">Or login with</span>
                            <ul class="socials">
                                <li><a href="{{ route('login.social.redirect', 'facebook') }}"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                                <li><a href="{{ route('login.social.redirect', 'google') }}"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('bower_components/login-signup-form-style/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bower_components/login-signup-form-style/js/main.js') }}"></script>
    <script src="{{ asset('bower_components/tata-js/dist/tata.js') }}"></script>
    @if (session('mail-successfully') == true)
        <script>
            tata.success('Reset password', 'Please check your e-mail to reset password!', {
                duration: 5000,
                animate: 'slide'
            });
        </script>
    @endif
    @if (session('new-password'))
        <script>
            tata.success('Reset password', 'Reset password successfully!', {
                duration: 5000,
                animate: 'slide'
            });
        </script>
    @endif
</body>
</html>
