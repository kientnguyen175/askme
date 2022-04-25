@extends('layouts.master')

@section('title')
	<title>Home | ASK Me</title>
@endsection

@section('style')
	@parent
@endsection

@section('content')
	<div class="section-warp ask-me">
		<div class="container clearfix">
			<div class="box_icon box_warp box_no_border box_no_background" box_border="transparent"
				box_background="transparent" box_color="#FFF">
				<div class="row">
					<div class="col-md-3">
						<h2>ASK me English!</h2>
						<p style="font-size: 15px !important; font-style: italic;">Find the best answer to your question about English, help others answer theirs</p>
						<div class="clearfix"></div>
						<a class="color button dark_button medium" href="javascript:void(0)">About Us</a>
						<a class="color button dark_button medium" href="javascript:void(0)">Join Now</a>
					</div>
					<div class="col-md-9">
						<form class="form-style form-style-2">
							<p>
								<textarea rows="4" id="question_title"
									onfocus="if(this.value=='Ask any question about English and you be sure find your answer?')this.value='';"
									onblur="if(this.value=='')this.value='Ask any question about English and you be sure find your answer?';">Ask any question about English and you be sure find your answer?</textarea>
								<i class="icon-pencil"></i>
								<span class="color button small publish-question">Ask Now</span>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8" style="margin-left: 53px">
		<div class="tabs-warp question-tab">
			<ul class="tabs">
				<li class="tab"><a href="javascript:void(0)" class="current">Newest</a></li>
				<li class="tab"><a href="javascript:void(0)">Unsolved</a></li>
				<li class="tab"><a href="javascript:void(0)">Votes</a></li>
			</ul>
			<div class="tab-inner-warp">
				<div class="tab-inner">
					@foreach ($newestQuestions as $question)
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
									<i class="icon-comments-alt"></i>{{ $question->answers->count() }} Answers
								</span>
								<span class="question-view"><i class="icon-eye-open"></i>{{ $question->view_number }}</span>
								<div class="clearfix"></div>
							</div>
						</article>
					@endforeach
					<div style="margin-bottom: 30px"></div>
					<a href="{{ route('questions.view') }}" class="load-questions"><i class="icon-globe"></i>Explore More Questions</a>
				</div>
			</div>
			<div class="tab-inner-warp">
				<div class="tab-inner">
					@foreach ($unansweredQuestions as $unansweredQuestion)
						<article class="question question-type-normal" style="margin-bottom: 5px;">
							<h2>
								<a href="{{ route('questions.show', $unansweredQuestion->id) }}">{{ $unansweredQuestion->title }}</a>
							</h2>
							<div class="question-author">
								<a href="{{ route('user.show', $unansweredQuestion->user->id) }}" original-title="" class="question-author-img tooltip-n">
									<span></span>
									<img alt="" src="{{ $unansweredQuestion->user->avatar }}">
								</a>
							</div>
							<div class="question-inner">
								<div class="clearfix"></div>
								<p class="question-desc">{{ mb_substr(html_entity_decode(strip_tags($unansweredQuestion->content->content)),0,255) . '...' }}</p>
								<div class="question-details">
									@if ($unansweredQuestion->best_answer_id)
										<span class="question-answered question-answered-done"><i class="icon-ok"></i>solved</span>
									@else 
										<span class="question-answered"><i class="icon-ok"></i>in progress</span>
									@endif
								</div>
								@if ($unansweredQuestion->vote_number && $unansweredQuestion->vote_number >=2)
									<span class="question-category" style="color: black"><i class="icon-heart-empty"></i>{{ $unansweredQuestion->vote_number }} votes</span>
								@else 	
									<span class="question-category" style="color: black"><i class="icon-heart-empty"></i>{{ $unansweredQuestion->vote_number }} vote</span>
								@endif
								<span class="question-date"><i class="icon-time"></i>{{ $unansweredQuestion->created_at->diffForHumans(Carbon\Carbon::now()) }}</span>
								<span class="question-comment">
									<i class="icon-comments-alt"></i>{{ $unansweredQuestion->answers->count() }} Answers
								</span>
								<span class="question-view"><i class="icon-eye-open"></i>{{ $unansweredQuestion->view_number }}</span>
								<div class="clearfix"></div>
							</div>
						</article>
					@endforeach
					<div style="margin-bottom: 30px"></div>
					<a href="{{ route('questions.view') }}" class="load-questions"><i class="icon-globe"></i>Explore More Questions</a>
				</div>
			</div>
			<div class="tab-inner-warp">
				<div class="tab-inner">
					@foreach ($votesQuestions as $votesQuestion)
						<article class="question question-type-normal" style="margin-bottom: 5px;">
							<h2>
								<a href="{{ route('questions.show', $votesQuestion->id) }}">{{ $votesQuestion->title }}</a>
							</h2>
							<div class="question-author">
								<a href="{{ route('user.show', $votesQuestion->user->id) }}" original-title="" class="question-author-img tooltip-n">
									<span></span>
									<img alt="" src="{{ $votesQuestion->user->avatar }}">
								</a>
							</div>
							<div class="question-inner">
								<div class="clearfix"></div>
								<p class="question-desc">{{ mb_substr(html_entity_decode(strip_tags($votesQuestion->content->content)),0,255) . '...' }}</p>
								<div class="question-details">
									@if ($votesQuestion->best_answer_id)
										<span class="question-answered question-answered-done"><i class="icon-ok"></i>solved</span>
									@else 
										<span class="question-answered"><i class="icon-ok"></i>in progress</span>
									@endif
								</div>
								@if ($votesQuestion->vote_number && $votesQuestion->vote_number >=2)
									<span class="question-category" style="color: black"><i class="icon-heart-empty"></i>{{ $votesQuestion->vote_number }} votes</span>
								@else 	
									<span class="question-category" style="color: black"><i class="icon-heart-empty"></i>{{ $votesQuestion->vote_number }} vote</span>
								@endif
								<span class="question-date"><i class="icon-time"></i>{{ $votesQuestion->created_at->diffForHumans(Carbon\Carbon::now()) }}</span>
								<span class="question-comment">
									<i class="icon-comments-alt"></i>{{ $votesQuestion->answers->count() }} Answers
								</span>
								<span class="question-view"><i class="icon-eye-open"></i>{{ $votesQuestion->view_number }}</span>
								<div class="clearfix"></div>
							</div>
						</article>
					@endforeach
					<div style="margin-bottom: 30px"></div>
					<a href="{{ route('questions.view') }}" class="load-questions"><i class="icon-globe"></i>Explore More Questions</a>
				</div>
			</div>
		</div>
	</div>
	<aside class="col-md-3 sidebar">
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
@endsection
