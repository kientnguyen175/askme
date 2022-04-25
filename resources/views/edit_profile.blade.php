@extends('layouts.master')

@section('style')
    @parent
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/avatar.css') }}">
@endsection

@section('content')
    <div class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Edit Profile</h1>
                </div>
            </div>
        </section>
    </div>
    <section class="container main-content">
        <div class="row">
            <div class="col-md-9">
                <div class="page-content">
                    <div class="boxedtitle page-title">
                        <h2>Edit Profile</h2>
                    </div>
                    <div class="form-style form-style-4">
                        <form action="{{ route('user.update') }}" method="post" id="update-profile" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="col-md-4">
                                <div class="avatar-wrapper">
                                    <img src="{{ $user->avatar ? $user->avatar : asset('images/default_avatar.png') }}" alt="" class="profile-pic">
                                    <div class="upload-button">
                                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                    </div>
                                    <input class="file-upload" id="avatar" name="avatar" type="file" accept="image/*"/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    <label>Bio</label>
                                    <textarea cols="58" rows="8" name="bio" id="bio">{{ $user->bio }}</textarea> 
                                </p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-inputs clearfix">
                                <p>
                                    <label class="required">Full Name<span>*</span></label>
                                    <input type="text" required name="name" value="{{ $user->name }}">
                                    @error('avatar')
                                        <label>{{ $message }}</label>
                                    @enderror
                                    @error('name')
                                        <label>{{ $message }}</label>
                                    @enderror
                                </p>
                                <p>
                                    <label>Website Link</label>
                                    <input type="text" name="website_link" id="website-link" value="{{ $user->website_link }}">
                                </p>
                                @if (Auth::user()->username)
                                    <p>
                                        <label for="">Username</label>
                                        <input type="text" name="username" value="{{ Auth::user()->username }}" disabled>
                                    </p>
                                @else 
                                    <p>
                                        <label for="">Username</label>
                                        <input id="username" type="text" name="username">
                                        <small style="color: #3498db"><i><b>Username can only be set up once!</b></i></small>
                                    </p>
                                @endif
                                <p></p>
                            </div>
                            <p class="form-submit">
                                <input type="submit" value="Update Profile" class="button color small login-submit submit">
                            </p>
                        </form>
                    </div>
                </div>
                <div class="page-content">
                    <div class="boxedtitle page-title">
                        <h2>Change Password</h2>
                    </div>
                    <div class="form-style form-style-4">
                        <form action="{{ route('user.changePassword') }}" method="post" id="change-password">
                            @csrf
                            @method('PATCH')
                            <div class="clearfix"></div>
                            <div class="form-inputs clearfix">
                                <p>
                                    <label class="required">Old Password<span>*</span></label>
                                    <input type="password" name="old_password" required>
                                </p>
                                <p>
                                    <label class="required">New password<span>*</span></label>
                                    <input type="password" name="password" required>
                                </p>
                                <p>
                                    <label class="required">Confirm New Password<span>*</span></label>
                                    <input type="password" name="password_confirmation" required>
                                </p>
                            </div>
                            <p class="form-submit">
                                <input type="submit" value="Update Password" class="button color small login-submit submit">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <aside class="col-md-3 sidebar">
                <div class="widget widget_tag_cloud">
                    <h3 class="widget_title">Hottest Tags</h3>
                    @foreach ($topTags as $tag)
                        <div><a style="color: #2c5777 !important" class="home-tag" href="javascript:void(0)">{{ $tag->tag }}</a></div>
                    @endforeach
                </div>
                <div class="widget widget_social">
                    <h3 class="widget_title">Find Us</h3>
                    <ul>
                        <li class="rss-subscribers">
                            <a href="javascript:void(0)" target="">
                                <strong>
                                    <i class="icon-rss"></i>
                                    <span>Subscribe</span><br>
                                    <small>To RSS Feed</small>
                                </strong>
                            </a>
                        </li>
                        <li class="facebook-fans">
                            <a href="javascript:void(0)" target="">
                                <strong>
                                    <i class="social_icon-facebook"></i>
                                    <span>5,000</span><br>
                                    <small>People like it</small>
                                </strong>
                            </a>
                        </li>
                        <li class="twitter-followers">
                            <a href="javascript:void(0)" target="">
                                <strong>
                                    <i class="social_icon-twitter"></i>
                                    <span>3,000</span><br>
                                    <small>Followers</small>
                                </strong>
                            </a>
                        </li>
                        <li class="youtube-subs">
                            <a href="javascript:void(0)" target="">
                                <strong>
                                    <i class="icon-play"></i>
                                    <span>1,000</span><br>
                                    <small>Subscribers</small>
                                </strong>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </section>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('js/avatar.js') }}"></script>
    <script src="{{ asset('js/updateProfile.js') }}"></script>
    <script src="{{ asset('js/changePassword.js') }}"></script>
@endsection
