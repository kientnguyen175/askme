<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | ASK Me</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        .form-gap {
            padding-top: 70px;
        }
    </style>
</head>
<body>
    <div class="form-gap" style></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('bower_components/askme-style/images/logo_light.png') }}" alt="">
                            </a>
                            <br><br>
                            <h4><i class="fa fa-lock fa-4x"></i></h4>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">
                                <form action="{{ route('resetPasswordLink') }}" id="register-form" role="form" autocomplete="off" class="form" method="post">
                                    @csrf  
                                    @if (session('email-error'))
                                        <div class="form-group">
                                            <b style="color: red">{{ session('email-error') }}</b>  
                                        </div>
                                    @endif   
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-envelope color-blue"></i>
                                            </span>
                                            <input required id="email" name="email" placeholder="Your e-mail address" class="form-control" type="email" autocomplete="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</body>
</html>
