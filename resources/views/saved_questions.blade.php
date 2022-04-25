@extends('layouts.master')

@section('title')
	<title>Saved Questions</title>
@endsection

@section('style')
	@parent
	<link rel="stylesheet" href="{{ asset('css/avatar.css') }}">
	<link rel="stylesheet" href="{{ asset('css/saved-question-page.css') }}">
@endsection

@section('scripts')
	@parent
@endsection

@section('content')
    <div class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Saved Questions</h1>
                </div>
            </div>
        </section>
    </div>
    <section class="container main-content">
		<div class="row">
			<div class="col-md-9">
				<div class="tabs-warp question-tab">
		            <ul class="tabs">
		                <li class="tab"><a href="#" class="current">Collections</a></li>
		                <li class="tab"><a href="#">General</a></li>
		            </ul>
					<div class="tab-inner-warp">
						<div class="tab-inner">
							@foreach ($collections as $collection)
								<div style="padding-left:5px; padding-right:5px;" class="col-md-4">
									<div class="page-content page-shortcode">
										<div class="box_icon">
											<div class="t_center">
												<a href="{{ route('collections.show', $collection->id) }}">
													<div class="avatar-wrapper">
														<img src="{{ $collection->image }}" name="image" alt="" class="profile-pic-{{ $collection->id }} profile-pic">
														<div class="upload-button-{{ $collection->id }} upload-button">
															<i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
														</div>
														<input class="file-upload-{{ $collection->id }} file-upload" id="avatar" name="avatar" type="file" accept="image/*"/>
													</div>
												</a>
												<br>
												<p>
													<b>{{ $collection->name }}</b>
												</p>
												{{-- <p style="margin-top: -40px;">
													<a href="javascript:void(0)"><i class="icon-ellipsis-vertical" style="margin-right: -200px;"></i></a>
												</p> --}}
											</div>
										</div>
									</div>
								</div>
							@endforeach
		                </div>
		            </div>
		            <div class="tab-inner-warp">
						<div class="tab-inner">
							<div style="padding-left: 5px; padding-right: 5px">
								@if (isset($singleQuestions))
									@foreach ($singleQuestions as $question)
										<article class="question question-type-normal">
											<h2 class="question-title">
												<a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a>
											</h2>
											{{-- <i class="icon-remove remove-question-button" id="delete-question-{{ $question->id }}"></i> --}}
											<div class="question-author">
												<a href="{{ route('user.show', $question->user->id) }}" original-title="" class=""><span></span><img alt="" src="{{ $question->user->avatar ?? asset('images/default_avatar.png') }}"></a>
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
								@endif
							</div>
		                </div>
		            </div>
				</div>
			</div>
			<aside class="col-md-3 sidebar">
				{{-- <div class="widget widget_stats">
					<h3 class="widget_title">Stats</h3>
					<div class="ul_list ul_list-icon-ok">
						<ul>
							<li><i class="icon-question-sign"></i>Questions ( <span>20</span> )</li>
							<li><i class="icon-comment"></i>Answers ( <span>50</span> )</li>
						</ul>
					</div>
				</div> --}}
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
				{{-- <div class="widget widget_highest_points">
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
				</div> --}}
			</aside>
		</div>
	</section>
@endsection
