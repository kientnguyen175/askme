@extends('layouts.master')

@section('style')
	@parent
	<link rel="stylesheet" href="{{ asset('css/user-profile.css') }}">
@endsection

@section('title')
	<title>User Profile | ASK Me</title>
@endsection

@section('scripts')
	@parent
	<script src="{{ asset('js/userProfile.js') }}"></script>
	<script>
		var userId = '{{ $user->id }}'
 		$(document).ready(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$('#follow-user').on('click', function(e){
				$.ajax({
					type: "POST",
					url: `http://localhost:8000/user/followUser/${userId}`,
					success: function(data){
						$('#follow-user').addClass('hidden');
						$('#unfollow-user').removeClass('hidden');
					},
					error: function(error){
						
					}
				});
			});
			$('#unfollow-user').on('click', function(e){
				$.ajax({
					type: "POST",
					url: `http://localhost:8000/user/unfollowUser/${userId}`,
					success: function(data){
						$('#unfollow-user').addClass('hidden');
						$('#follow-user').removeClass('hidden');
					},
					error: function(error){
		
					}
				});
			});
		});
	</script>
@endsection

@section('content')
	<div class="breadcrumbs">
		<section class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>User Profile</h1>
				</div>
			</div>
		</section>
	</div>
	<section class="container main-content">
		<div class="row">
			<div class="col-md-9">
				<div class="row">
					<div class="user-profile">
						<div class="col-md-12">
							<div class="page-content user-profile">
								@if ($user->username)
									<h2 class="name">{{ $user->name }}</h2>
								@else
									<h2>{{ $user->name }}</h2>
								@endif
								
								@if (Auth::check() && !$checkFollowUser && Auth::id() != $user->id)
									<button id="follow-user">Follow This User</button>
									<button id="unfollow-user" class="hidden">UnFollow This User</button>
								@endif

								@if (Auth::check() && $checkFollowUser)
									<button id="follow-user" class="hidden">Follow This User</button>
									<button id="unfollow-user">UnFollow This User</button>
								@endif
								<span class="user-name">{{ $user->username ? '@' . $user->username : ''}}</span>
								<b>{{ $user->bio }}</b>
								<p></p>
								<div class="user-profile-img"><img src="{{ $user->avatar ? $user->avatar : asset('images/default_avatar.png') }}" alt="admin"></div>
								<div class="ul_list ul_list-icon-ok about-user">
									<ul>
										<li><i class="icon-heart"></i>Points: {{ $user->points }}</li>
										<li><i class="icon-group"></i>Followers: <span>{{ $totalFollowers }}</span></li>
										@if ($user->website_link)
											<li><i class="icon-globe"></i>Website: <a target="_blank" href="{{ 'https://' . $user->website_link }}">{{ $user->website_link }}</a></li>
										@endif
										<li><i class="icon-plus"></i>Registerd: <span>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span></li>
									</ul>
								</div>
								<p></p>
								@if (Auth::id() == $user->id)
									<a href="{{ route('user.edit') }}" class="button small blue-button">Edit</a>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="page-content page-content-user-profile">
								<div class="user-profile-widget">
									<h2>User Stats</h2>
									<div class="ul_list ul_list-icon-ok">
										<ul>
											<li><i class="icon-question-sign"></i><a href="{{ route('user.newsfeedBy', $user->id) }}">Questions<span> ( <span>{{ $user->questions->where('status', 1)->count() }}</span> ) </span></a></li>
											<li><i class="icon-comment"></i><a href="{{ route('user.answers', $user->id) }}">Answers<span> ( <span>{{ $user->answers->count() }}</span> ) </span></a></li>
											<li><i class="icon-heart"></i>Points<span> ( <span>{{ $user->points }}</span> ) </span></li>
											<li><i class="icon-group"></i><a href="{{ route('user.followers', $user->id) }}">Followers<span> ( <span>{{ $totalFollowers }}</span> ) </span></a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<aside class="col-md-3 sidebar">
				<div class="widget widget_tag_cloud">
					<h3 class="widget_title">Hottest Tags</h3>
					@foreach ($topTags as $tag)
						<a style="color: #2c5777 !important" class="home-tag" href="http://localhost:8000/questions/view/[{{ $tag->tag }}]/newest#tab-top">{{ $tag->tag }}</a>
					@endforeach
				</div>
				<div class="widget widget_social">
					<h3 class="widget_title">Find Us</h3>
					<ul>
						<li class="rss-subscribers">
							<a href="javascript:void(0)">
								<strong>
									<i class="icon-rss"></i>
									<span>Subscribe</span><br>
									<small>To RSS Feed</small>
								</strong>
							</a>
						</li>
						<li class="facebook-fans">
							<a href="javascript:void(0)">
								<strong>
									<i class="social_icon-facebook"></i>
									<span>5,000</span><br>
									<small>People like it</small>
								</strong>
							</a>
						</li>
						<li class="twitter-followers">
							<a href="javascript:void(0)">
								<strong>
									<i class="social_icon-twitter"></i>
									<span>3,000</span><br>
									<small>Followers</small>
								</strong>
							</a>
						</li>
						<li class="youtube-subs">
							<a href="javascript:void(0)">
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
