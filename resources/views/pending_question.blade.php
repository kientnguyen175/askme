@extends('layouts.master')

@section('title')
	<title>Pending Question</title>
@endsection

@section('style')
	@parent
    <link rel="stylesheet" href="{{ asset('css/newsfeedPage.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/cute-alert/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pending-question.css') }}">
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
                    <h1>Pending Question</h1>
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
						<article class="question question-type-normal pending">
							<h2 class="question-title">
								<a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a>
							</h2>
							<i class="icon-remove remove-question-button" id="delete-question-{{ $question->id }}"></i>
							{{-- <a class="question-report" href="#">Report</a> --}}
							<div class="question-author">
								<a href="{{ route('user.show', $user->id) }}" original-title="ahmed" class=""><span></span><img alt="" src="{{ $user->avatar }}"></a>
							</div>
							<div class="question-inner">
								<div class="clearfix"></div>
								<p class="question-desc">
									<span class="character-limitation">{{ mb_substr(html_entity_decode(strip_tags($question->content->content)),0,255) . '...' }}</span>
									<br><br>
									@foreach ($question->tags as $tag)
										<button class="tags">{{ $tag->tag }}</button>
									@endforeach
								</p>
								<div class="question-details">
									{{-- @if ($question->best_answer_id)
										<span class="question-answered question-answered-done"><i class="icon-ok"></i>solved</span>
									@else 
										<span class="question-answered"><i class="icon-ok"></i>in progress</span>
									@endif --}}
									{{-- <span class="question-favorite"><i class="icon-star"></i>5</span> --}}
								</div>
								<span class="question-date"><i class="icon-time"></i>will be published at  {{ Carbon\Carbon::parse($question->schedule_time)->addHours(7)->format('H:i d/m/Y') }}</span>
								{{-- <span class="question-category"><i class="icon-heart"></i>{{ $question->vote_number }} votes</span>
								<span class="question-comment"><i class="icon-comments"></i>{{ $question->answers->count() }} answers</span>
								<span class="question-view"><i class="icon-eye-open"></i>{{ $question->view_number }} views</span> --}}
								<div class="clearfix"></div>
							</div>
						</article>
					@endforeach
					{{ $questions->links() }}
				</div>
			</div>
			<aside class="col-md-3 sidebar">
				<div class="widget widget_stats">
					<h3 class="widget_title">Stats</h3>
					<div class="ul_list ul_list-icon-ok">
						<ul>
							<li><i class="icon-question-sign"></i>Questions ( <span>20</span> )</li>
							<li><i class="icon-comment"></i>Answers ( <span>50</span> )</li>
						</ul>
					</div>
				</div>
				<div class="widget widget_social">
					<h3 class="widget_title">Find Us</h3>
					<ul>
						<li class="rss-subscribers">
							<a href="#" target="_blank">
							<strong>
								<i class="icon-rss"></i>
								<span>Subscribe</span><br>
								<small>To RSS Feed</small>
							</strong>
							</a>
						</li>
						<li class="facebook-fans">
							<a href="#" target="_blank">
							<strong>
								<i class="social_icon-facebook"></i>
								<span>5,000</span><br>
								<small>People like it</small>
							</strong>
							</a>
						</li>
						<li class="twitter-followers">
							<a href="#" target="_blank">
							<strong>
								<i class="social_icon-twitter"></i>
								<span>3,000</span><br>
								<small>Followers</small>
							</strong>
							</a>
						</li>
						<li class="youtube-subs">
							<a href="#" target="_blank">
							<strong>
								<i class="icon-play"></i>
								<span>1,000</span><br>
								<small>Subscribers</small>
							</strong>
							</a>
						</li>
					</ul>
				</div>
				<div class="widget widget_login">
					<h3 class="widget_title">Login</h3>
					<div class="form-style form-style-2">
						<form>
							<div class="form-inputs clearfix">
								<p class="login-text">
									<input type="text" value="Username" onfocus="if (this.value == 'Username') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Username';}">
									<i class="icon-user"></i>
								</p>
								<p class="login-password">
									<input type="password" value="Password" onfocus="if (this.value == 'Password') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Password';}">
									<i class="icon-lock"></i>
									<a href="#">Forget</a>
								</p>
							</div>
							<p class="form-submit login-submit">
								<input type="submit" value="Log in" class="button color small login-submit submit">
							</p>
							<div class="rememberme">
								<label><input type="checkbox" checked="checked"> Remember Me</label>
							</div>
						</form>
						<ul class="login-links login-links-r">
							<li><a href="#">Register</a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="widget widget_highest_points">
					<h3 class="widget_title">Highest points</h3>
					<ul>
						<li>
							<div class="author-img">
								<a href="#"><img width="60" height="60" src="https://2code.info/demo/html/ask-me/images/demo/admin.jpeg" alt=""></a>
							</div> 
							<h6><a href="#">admin</a></h6>
							<span class="comment">12 Points</span>
						</li>
						<li>
							<div class="author-img">
								<a href="#"><img width="60" height="60" src="https://2code.info/demo/html/ask-me/images/demo/avatar.png" alt=""></a>
							</div> 
							<h6><a href="#">vbegy</a></h6>
							<span class="comment">10 Points</span>
						</li>
						<li>
							<div class="author-img">
								<a href="#"><img width="60" height="60" src="https://2code.info/demo/html/ask-me/images/demo/avatar.png" alt=""></a>
							</div> 
							<h6><a href="#">ahmed</a></h6>
							<span class="comment">5 Points</span>
						</li>
					</ul>
				</div>
				<div class="widget widget_tag_cloud">
					<h3 class="widget_title">Tags</h3>
					<a href="#">projects</a>
					<a href="#">Portfolio</a>
					<a href="#">Wordpress</a>
					<a href="#">Html</a>
					<a href="#">Css</a>
					<a href="#">jQuery</a>
					<a href="#">2code</a>
					<a href="#">vbegy</a>
				</div>
				<div class="widget">
					<h3 class="widget_title">Recent Questions</h3>
					<ul class="related-posts">
						<li class="related-item">
							<h3><a href="#">This is my first Question</a></h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
							<div class="clear"></div><span>Feb 22, 2014</span>
						</li>
						<li class="related-item">
							<h3><a href="#">This Is My Second Poll Question</a></h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
							<div class="clear"></div><span>Feb 22, 2014</span>
						</li>
					</ul>
				</div>
			</aside>
		</div>
	</section>
@endsection
