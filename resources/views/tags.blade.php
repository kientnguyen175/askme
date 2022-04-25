@extends('layouts.master')

@section('style')
	@parent
	<link rel="stylesheet" href="{{ asset('css/tags.css') }}">
@endsection

@section('title')
	<title>Tags | ASK Me</title>
@endsection

@section('content')
    <div id="tab-top" class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Tags</h1>
                </div>
            </div>
        </section>
    </div>
	<div class="col-md-8" style="margin-left: 53px">
		<input id="tag-search" value="{{ $searchText ?? '' }}" name="searchText" type="text" placeholder="Search for tags...">
		<div class="question-tab tag-block">
			@if ($tab == 'popular')
				<button class="view-tab clicked">Popular</button>
			@elseif (!isset($searchText)) 
				<a href="{{ route('tags.view', 'popular') }}#tab-top"><button class="view-tab">Popular</button></a>
			@else
				<a href="{{ route('tags.search', [$searchText, 'popular']) }}#tab-top"><button class="view-tab">Popular</button></a>
			@endif

			@if ($tab == 'name')
				<button class="view-tab clicked">Name</button>
			@elseif (!isset($searchText))
				<a href="{{ route('tags.view', 'name') }}#tab-top"><button class="view-tab">Name</button></a>
			@else
				<a href="{{ route('tags.search', [$searchText, 'name']) }}#tab-top"><button class="view-tab">Name</button></a>
			@endif

			@if ($tab == 'newest')
				<button class="view-tab clicked">Newest</button>
			@elseif (!isset($searchText))
				<a href="{{ route('tags.view', 'newest') }}#tab-top"><button class="view-tab">Newest</button></a>
			@else 
				<a href="{{ route('tags.search', [$searchText, 'newest']) }}#tab-top"><button class="view-tab">Newest</button></a>
			@endif
			<br><br>
			<div class="tags-block">
				<div class="">
					<div class="tag-link clearfix">
						{{ $tags->links() }}
						<br>
					</div>
					<br>
					@foreach ($tags as $tag)
						<div class="col-md-3 tag">
							<a href="{{ route('questions.viewByTab', ['searchText' => '['.$tag->tag.']', 'tab' => 'newest']) }}#tab-top"><button class="tag-button">{{ $tag->tag }}</button></a>
							@if ($tag->questions_count > 1)
								<p class="question-count">{{ $tag->questions_count }} questions</p>
							@else 
								<p class="question-count">{{ $tag->questions_count }} question</p>
							@endif 
						</div>
					@endforeach
				</div>
			</div>
			<div class="clearfix" style="margin-bottom: 30px"></div>
			<br><br><br>
			<div class="tag-link clearfix">
				{{ $tags->links() }}
			</div>
			{{-- <div class="clearfix" style="margin-bottom: 30px"></div> --}}
		</div>
	</div>
	<aside class="col-md-3 sidebar">
		<div class="widget widget_stats">
			<h3 class="widget_title">Stats</h3>
			<div class="ul_list ul_list-icon-ok">
				<ul>
					<li><i class="icon-tags"></i>Tags ( <span>{{ $totalTags }}</span> )</li>
				</ul>
			</div>
		</div>
		<div class="widget widget_tag_cloud">
			<h3 class="widget_title">Hottest Tags</h3>
			@foreach ($topTags as $tag)
				<div>
					<a style="color: #2c5777 !important" class="home-tag" href="http://localhost:8000/questions/view/[{{ $tag->tag }}]/newest#tab-top">{{ $tag->tag }}</a>
				</div>
			@endforeach
		</div>
		{{-- <div class="widget widget_highest_points">
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
		</div> --}}
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
@endsection

@section('scripts')
	@parent
	<script src="{{ asset('js/searchTag.js') }}"></script>
@endsection
