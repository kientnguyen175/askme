<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password | ASK Me</title>
    <link rel="shortcut icon" href="{{ asset('bower_components/askme-style/images/favicon.png') }}">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>
    <div class="form-gap" style="padding-top: 70px"></div>
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
                            <h4><i class="fa fa-unlock-alt fa-4x"></i></h4>
                            <h2 class="text-center">New Password</h2>
                            <p>You can create new password here.</p>
                            @error('password')
                                <br>
                                <span class="invalid-feedback" role="alert" style="color: red">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="panel-body">
                                <form action="{{ route('resetPassword', $userId) }}" id="register-form" role="form" autocomplete="off" class="form" method="post">
                                    @csrf  
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-key" aria-hidden="true"></i>
                                            </span>
                                            <input required id="email" name="password" placeholder="New password" class="form-control" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-key" aria-hidden="true"></i>
                                            </span>
                                            <input required name="password_confirmation" placeholder="Confirm new password" class="form-control" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Create New Password" type="submit">
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
