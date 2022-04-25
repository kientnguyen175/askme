@extends('layouts.master')

@section('style')
	@parent
    <link rel="stylesheet" href="{{ asset('css/questions.css') }}">
@endsection

@section('title')
	<title>Questions | ASK Me</title>
@endsection

@section('content')
    <div id="tab-top" class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Questions</h1>
                </div>
            </div>
        </section>
    </div>
	<div class="col-md-8" style="margin-left: 53px">
		<input id="question-search" value="{{ $searchText ?? '' }}" name="searchText" type="text" placeholder="Search for questions...">
		<br>
		<div class="question-tab">
			@if (isset($tab) && $tab == 'relevance')
				<button class="view-tab clicked">Relevance</button>
			@elseif (isset($searchText) && $searchText[0] != '[' && $searchText[-1] != ']')
				<a href="{{ route('questions.viewByTab', ['searchText' => $searchText ?? 'noSearching', 'tab' => 'relevance']) . '#tab-top'}}"><button class="view-tab">Relevance</button></a>
			@endif

			@if (!isset($tab) || (isset($tab) && $tab == 'newest'))
				<button class="view-tab clicked">Newest</button>
			@else 
				<a href="{{ route('questions.viewByTab', ['searchText' => $searchText ?? 'noSearching', 'tab' => 'newest']) . '#tab-top'}}"><button class="view-tab">Newest</button></a>
			@endif

			@if (isset($tab) && $tab == 'unsolved')
				<button class="view-tab clicked">Unsolved</button>
			@else 
				<a href="{{ route('questions.viewByTab', ['searchText' => $searchText ?? 'noSearching', 'tab' => 'unsolved']) . '#tab-top' }}"><button class="view-tab">Unsolved</button></a>
			@endif

			@if (isset($tab) && $tab == 'votes')
				<button class="view-tab clicked">Votes</button>
			@else 
				<a href="{{ route('questions.viewByTab', ['searchText' => $searchText ?? 'noSearching', 'tab' => 'votes']) . '#tab-top' }}"><button class="view-tab">Votes</button></a>
			@endif
			
			@if ($questions->links())
				<div class="link-top">{{ $questions->links() }}</div>
			@endif
			<br><br><br>
			<div class="">
				<div class="">
					@foreach ($questions as $question)
						<article class="question question-type-normal" style="margin-bottom: 5px;">
							<h2>
								<a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a>
							</h2>
							<div class="question-author">
								<a href="{{ route('user.show', $question->user->id) }}" original-title="" class="question-author-img tooltip-n">
									<span></span>
									<img alt="" src="{{ $question->user->avatar ?? asset('images/default_avatar.png') }}">
								</a>
							</div>
							<div class="question-inner">
								<div class="clearfix"></div>
								<p class="question-desc">
									<span class="character-limitation">{{ mb_substr(html_entity_decode(strip_tags($question->content->content)),0,255) . '...' }}</span>
									<br><br>
									@foreach ($question->tags as $questionTag)
										<a href="http://localhost:8000/questions/view/[{{ $questionTag->tag }}]/newest#tab-top">
											<button class="home-tag">{{ $questionTag->tag }}</button>
										</a>
									@endforeach
								</p>
								<div class="question-details">
									@if ($question->best_answer_id)
										<span class="question-answered question-answered-done"><i class="icon-ok"></i>solved</span>
									@else 
										<span class="question-answered"><i class="icon-ok"></i>in progress</span>
									@endif
								</div>
								@if ($question->vote_number && $question->vote_number >=2)
									<span class="question-category" style="color: black"><i class="icon-heart-empty"></i>{{ $question->vote_number }} votes</span>
								@else 	
									<span class="question-category" style="color: black"><i class="icon-heart-empty"></i>{{ $question->vote_number }} vote</span>
								@endif
								<span class="question-date"><i class="icon-time"></i>{{ $question->created_at->diffForHumans(Carbon\Carbon::now()) }}</span>
								<span class="question-comment">
									<a href="javascript:void(0)"><i class="icon-comments-alt"></i>{{ $question->answers->count() }} Answers</a>
								</span>
								<span class="question-view"><i class="icon-eye-open"></i>70 views</span>
								<div class="clearfix"></div>
							</div>
						</article>
					@endforeach
					<br>
					{{ $questions->links() }}
					<div class="clearfix" style="margin-bottom: 30px"></div>
				</div>
			</div>
		</div>
	</div>
	<aside class="col-md-3 sidebar">
		<div class="widget widget_stats">
			<h3 class="widget_title">Stats</h3>
			<div class="ul_list ul_list-icon-ok">
				<ul>
					<li><i class="icon-question-sign"></i>Questions ( <span>{{ $totalQuestions }}</span> )</li>
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
				<div><a style="color: #2c5777 !important" class="home-tag" href="http://localhost:8000/questions/view/[{{ $tag->tag }}]/newest#tab-top">{{ $tag->tag }}</a></div>
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
@endsection

@section('scripts')
	@parent
	<script src="{{ asset('js/searchQuestion.js') }}"></script>
@endsection
