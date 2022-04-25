@extends('layouts.master')

@section('title')
	<title>Collection</title>
@endsection

@section('style')
	@parent
    <link rel="stylesheet" href="{{ asset('css/saved-question-page.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/jquery-modal/jquery.modal.css') }}">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/avatar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/collectionDetail.css') }}">
	<link rel="stylesheet" href="{{ asset('bower_components/cute-alert/style.css') }}">
@endsection

@section('scripts')
	@parent
	<script src="{{ asset('bower_components/jquery-modal/jquery.modal.min.js') }}"></script>
	<script src="{{ asset('js/avatar.js') }}"></script>
	<script src="{{ asset('js/collection-options.js') }}"></script>
	<script src="{{ asset('bower_components/cute-alert/cute-alert.js') }}"></script>
@endsection

@section('content')
    <div class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Collection "<span id="collection-name">{{ $collection->name }}</span>"</h1>
                </div>
            </div>
        </section>
    </div>
    <section class="container main-content">
		<div class="row">
			<div class="col-md-9">
				
				<a href="#edit-collection" rel="modal:open"><button class="collection-tag">Edit</button></a>
				<form action="{{ route('collections.update', $collection->id) }}" id="edit-collection" class="modal" method="post">
					@csrf 
					<div class="avatar-wrapper">
						<img src="{{ $collection->image }}" alt="" class="profile-pic">
						<div class="upload-button">
							<i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
						</div>
						<input class="file-upload" id="avatar" name="image" type="file" accept="image/*"/>
					</div>
					<div style="text-align: center;">
						<input name="name" style="text-align: center; margin: auto" type="text" value="{{ $collection->name }}">
						<br>
						<input class="update-collection" type="submit" value="Update">
						<a href="javascript:void(0)" rel="modal:close" id="cancel-updating" class="hidden">Close</a>
					</div>
				</form>
				<button id="delete-collection" data-collection="{{ $collection->id }}" class="collection-tag" style="margin-left: 5px">Delete</button>
				<br><br>
				<div class="infinite-scroll">
					@foreach ($collection->questions as $question)
						<article class="question question-type-normal">
							<h2 class="question-title">
								<a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a>
							</h2>
							{{-- <i class="icon-remove remove-question-button" id="delete-question-{{ $question->id }}"></i> --}}
							{{-- <a class="question-report" href="#">Report</a> --}}
							<div class="question-author">
								<a href="{{ route('user.show', $question->user->id) }}" original-title="ahmed" class=""><span></span><img alt="" src="{{ $question->user->avatar ?? asset('images/default_avatar.png') }}"></a>
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
									@if ($question->best_answer_id)
										<span class="question-answered question-answered-done"><i class="icon-ok"></i>solved</span>
									@else 
										<span class="question-answered"><i class="icon-ok"></i>in progress</span>
									@endif
									{{-- <span class="question-favorite"><i class="icon-star"></i>5</span> --}}
								</div>
								<span class="question-date"><i class="icon-time"></i>{{ $question->created_at->diffForHumans(Carbon\Carbon::now()) }}</span>
								<span class="question-category"><i class="icon-heart"></i>{{ $question->vote_number }} votes</span>
								<span class="question-comment"><i class="icon-comments"></i>{{ $question->answers->count() }} answers</span>
								<span class="question-view"><i class="icon-eye-open"></i>{{ $question->view_number }} views</span>
								<div class="clearfix"></div>
							</div>
						</article>
					@endforeach
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

