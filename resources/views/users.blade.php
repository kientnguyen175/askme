@extends('layouts.master')

@section('style')
	@parent
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endsection

@section('title')
	<title>Users | ASK Me</title>
@endsection

@section('content')
    <div id="tab-top" class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Users</h1>
                </div>
            </div>
        </section>
    </div>
	<div class="col-md-8" style="margin-left: 53px">
		<input id="user-search" value="{{ $searchText ?? '' }}" name="searchText" type="text" placeholder="Search users by username or name...">
		<div class="question-tab users-block">
			@if ($tab == 'points')
				<button class="view-tab clicked">Points</button>
			@elseif (!isset($searchText)) 
				<a href="{{ route('users.view', 'points') }}#tab-top"><button class="view-tab">Points</button></a>
			@else
				<a href="{{ route('users.search', [$searchText, 'points']) }}#tab-top"><button class="view-tab">Points</button></a>
			@endif

			@if ($tab == 'name')
				<button class="view-tab clicked">Name</button>
			@elseif (!isset($searchText))
				<a href="{{ route('users.view', 'name') }}#tab-top"><button class="view-tab">Name</button></a>
			@else
				<a href="{{ route('users.search', [$searchText, 'name']) }}#tab-top"><button class="view-tab">Name</button></a>
			@endif

			@if ($tab == 'newest')
				<button class="view-tab clicked">Newest</button>
			@elseif (!isset($searchText))
				<a href="{{ route('users.view', 'newest') }}#tab-top"><button class="view-tab">Newest</button></a>
			@else 
				<a href="{{ route('users.search', [$searchText, 'newest']) }}#tab-top"><button class="view-tab">Newest</button></a>
			@endif
			<br><br>
			<div class="tags-block">
				<div class="">
					<div class="clearfix pagination-user">
						{{ $users->links() }}
						<br>
					</div>
					<br><br>
					@foreach ($users as $user)
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
								@if ($tab == 'points')
									<span class="points"><b>{{ $user->points }} points</b></span>
								@else
									<span class="points">{{ $user->points }} points</span>
								@endif
								<div></div>
								@if ($tab == 'newest')
									<span class="created-at"><b>{{ 'joined at ' . $user->created_at->format('d/m/Y') }}</b></span>
								@else 
									<span class="created-at">{{ 'joined at ' . $user->created_at->format('d/m/Y') }}</span>
								@endif
							</div>
						</li>
					@endforeach
				</div>
			</div>
			{{-- <div class="clearfix" style="margin-bottom: 30px"></div> --}}
			<div class="clearfix">
				{{ $users->links() }}
			</div>
			{{-- <div class="clearfix" style="margin-bottom: 30px"></div> --}}
		</div>
	</div>
	<aside class="col-md-3 sidebar">
		<div class="widget widget_stats">
			<h3 class="widget_title">Stats</h3>
			<div class="ul_list ul_list-icon-ok">
				<ul>
					<li><i class="icon-question-sign"></i>Users ( <span>{{ $totalUsers }}</span> )</li>
				</ul>
			</div>
		</div>
		<div class="widget widget_highest_points">
			<h3 class="widget_title">Top Users</h3>
			<ul>
				@foreach ($topUsers as $user)
					<li>
						<div class="author-img">
							<a href="{{ route('user.show', $user->id) }}">
								<img width="60" height="60" src="{{ $user->avatar ?? asset('images/default_avatar.png') }}" alt="">
							</a>
						</div>
						<h6><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></h6>
						<span class="comment">{{ $user->points }} points</span>
					</li>
				@endforeach
			</ul>
		</div>
		<div class="widget widget_tag_cloud">
			<h3 class="widget_title">Hottest Tags</h3>
			@foreach ($topTags as $tag)
				<div>
					<a style="color: #2c5777 !important" class="home-tag" href="http://localhost:8000/questions/view/[{{ $tag->tag }}]/newest#tab-top">{{ $tag->tag }}</a>
				</div>
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
@endsection

@section('scripts')
	@parent
	<script src="{{ asset('js/searchUser.js') }}"></script>
@endsection
