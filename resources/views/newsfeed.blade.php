@extends('layouts.master')

@section('title')
	<title>Personal Questions | ASK Me</title>
@endsection

@section('style')
	@parent
	<link rel="stylesheet" href="{{ asset('css/newsfeedPage.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/cute-alert/style.css') }}">
@endsection

@section('scripts')
	@parent
    <script src="{{ asset('js/newsfeedPage.js') }}"></script>
	<script src="{{ asset('bower_components/cute-alert/cute-alert.js') }}"></script>
	<script src="{{ asset('js/deleteQuestion.js') }}"></script>
@endsection

@section('content')
    <div class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Personal Questions</h1>
                </div>
            </div>
        </section>
    </div>
    <section class="container main-content">
		<div class="row">
			<div class="col-md-9">
				<div class="infinite-scroll">
					{{ $questions->links() }}
					@foreach ($questions as $question)
						<script>
							var buttonNumber = '{{ $questions->count() }}'
						</script>
						<article class="question question-type-normal">
							<h2 class="question-title">
								<a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a>
							</h2>
							@if (Auth::id() == $user->id)
								<i class="icon-remove remove-question-button" id="delete-question-{{ $question->id }}"></i>
							@endif
							<div class="question-author">
								<a href="{{ route('user.show', $user->id) }}" original-title="ahmed" class=""><span></span><img alt="" src="{{ $user->avatar ?? asset('images/default_avatar.png') }}"></a>
							</div>
							<div class="question-inner">
								<div class="clearfix"></div>
								<p class="question-desc">
									<span class="character-limitation">{{ mb_substr(html_entity_decode(strip_tags($question->content->content)),0,255) . '...' }}</span>
									<br><br>
									@foreach ($question->tags as $tag)
										<a href="http://localhost:8000/questions/view/[{{ $tag->tag }}]/newest#tab-top">
											<button class="tags">{{ $tag->tag }}</button>
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
								<span class="question-date"><i class="icon-time"></i>{{ $question->created_at->diffForHumans(Carbon\Carbon::now()) }}</span>
								<span class="question-category"><i class="icon-heart"></i>{{ $question->vote_number }} votes</span>
								<span class="question-comment"><i class="icon-comments"></i>{{ $question->answers->count() }} answers</span>
								<span class="question-view"><i class="icon-eye-open"></i>{{ $question->view_number }} views</span>
								<div class="clearfix"></div>
							</div>
						</article>
					@endforeach
					{{ $questions->links() }}
				</div>
			</div>
			<aside class="col-md-3 sidebar">
				@if (isset($totalQuestions))
					<div class="widget widget_stats">
						<h3 class="widget_title">Stats</h3>
						<div class="ul_list ul_list-icon-ok">
							<ul>
								<li><i class="icon-question-sign"></i>Questions ( <span id="totalQuestions">{{ $totalQuestions }}</span> )</li>
							</ul>
						</div>
					</div>
				@endif
				@if (isset($topTags))
					<div class="widget widget_tag_cloud">
						<h3 class="widget_title">Hottest Tags</h3>
						@foreach ($topTags as $tag)
							<div>
								<a style="color: #2c5777 !important" class="home-tag" href="http://localhost:8000/questions/view/[{{ $tag->tag }}]/newest#tab-top">{{ $tag->tag }}</a>
							</div>
						@endforeach
					</div>
				@endif
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
