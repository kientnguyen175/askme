@extends('layouts.master')

@section('style')
	@parent
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endsection

@section('title')
	<title>Followers | ASK Me</title>
@endsection

@section('content')
    <div id="tab-top" class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Followers</h1>
                </div>
            </div>
        </section>
    </div>
	<div class="col-md-8" style="margin-left: 53px">
		<div class="question-tab users-block">
			<br>
			<div class="tags-block">
				<div class="">
					<div class="clearfix pagination-user">
						{{ $followers->links() }}
						<br>
					</div>
					<br>
					@foreach ($followers as $follower)
                        @php 
                            $user = App\Models\User::find($follower->model_id)
                        @endphp
						<li class="col-md-4 user-block">
							<div class="author-img">
								<a href="{{ route('user.show', $user->id) }}">
									<img width="60" height="60" src="{{ $user->avatar ?? asset('images/default_avatar.png') }}" alt="">
								</a>
							</div>
							<div class="user-info">
								<h6 class="name"><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></h6>
								<span class="username"><i>{{ $user->username ? '@' . $user->username : '' }}</i></span>
								<div class="after-username"></div>
								
									<span class="points">{{ $user->points }} points</span>
								<div></div>
									<span class="created-at"><b>{{ 'joined at ' . $user->created_at->format('d/m/Y') }}</b></span>
								
							</div>
						</li>
					@endforeach
				</div>
			</div>
            <br>
			<div class="clearfix">
				{{ $followers->links() }}
			</div>
			{{-- <div class="clearfix" style="margin-bottom: 30px"></div> --}}
		</div>
	</div>
	<aside class="col-md-3 sidebar">
		<div class="widget widget_stats">
			<h3 class="widget_title">Stats</h3>
			<div class="ul_list ul_list-icon-ok">
				<ul>
					<li><i class="icon-question-sign"></i>Followers ( <span>{{ $totalFollowers }}</span> )</li>
				</ul>
			</div>
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
@endsection

@section('scripts')
	@parent
	<script src="{{ asset('js/searchUser.js') }}"></script>
@endsection
