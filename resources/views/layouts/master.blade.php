<!DOCTYPE html>
<html lang="en">
<head class="head">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('bower_components/askme-style/images/favicon.png') }}">
    
    @yield('title')

    @section('style')
        <link rel="stylesheet" href="{{ asset('bower_components/askme-style/style.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/askme-style/css/skins/blue.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/askme-style/css/responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @show
    
    @section('scripts')
        <script src="{{ asset('bower_components/askme-style/js/jquery.min.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.easing.1.3.min.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/html5.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/twitter/jquery.tweet.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jflickrfeed.min.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.inview.min.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.tipsy.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/tabs.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.flexslider.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.prettyPhoto.js') }}"></script>  
        <script src="{{ asset('bower_components/askme-style/js/jquery.carouFredSel-6.2.1-packed.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.scrollTo.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.nav.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/tags.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/jquery.bxslider.min.js') }}"></script>
        <script src="{{ asset('bower_components/askme-style/js/custom.js') }}"></script>
        <script src="{{ asset('bower_components/tata-js/dist/tata.js') }}"></script>
        <script src="{{ asset('ckeditor5-comment/build/ckeditor.js') }}"></script>
        <script src="{{ asset('js/search.js') }}"></script>
        <script src="{{ asset('js/noti-bell.js') }}"></script>
        <script src="{{ asset('js/readNotiNewAnswer.js') }}"></script>
        <script src="{{ asset('js/playAudios.js') }}"></script>
        <script src="{{ asset('js/copyIPA.js') }}"></script>
        <script src="{{ asset('js/markAllAsRead.js') }}"></script>
        <script src="{{ asset('js/options.js') }}"></script>
    @show
</head>
<body>
    <div class="loader">
        <div class="loader_html"></div>
    </div>
    <header id="header">
        <section class="container clearfix">
            <div class="logo"><a href="{{ route('home') }}"><img alt="" src="{{ asset('bower_components/askme-style/images/logo.png') }}"></a></div>
            <nav class="navigation">
                <ul>
                    <li id="explore" class=""><a href="javascript:void(0)">Explore</a>
                        <ul>
                            <li id="questions" class=""><a href="{{ route('questions.view') }}">Questions</a></li>
                            <li id="tags"><a href="{{ route('tags.view', 'popular') }}">Tags</a></li>
                            <li id="users"><a href="{{ route('users.view', 'points') }}">Users</a></li>
                        </ul>
                    </li>
                    <li id="ask_question"><a href="{{ route('user.showAskForm') }}">Ask Question</a></li>
                    <li id="personal" class="newsfeed">
                        @guest
                            <a href="{{ route('login') }}">Log In</a>
                        @else
                            <a href="javascript:void(0)">
                                <span class="name">{{ Auth::user()->name }}</span>
                            </a>
                            <ul>
                                <li class="newsfeed"><a href="{{ route('user.newsfeed') }}">My Questions</a></li>
                                <li><a href="{{ route('user.answers', Auth::id()) }}">My Answers</a></li>
                                <li class="saved-questions"><a href="{{ route('users.savedQuestions') }}">My Saved Questions</a></li>
                                <li><a href="{{ route('user.pending') }}">My Pending Question</a></li>
                                <li id="my-profile"><a href="{{ route('user.show', Auth::id()) }}">My Profile</a></li>
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        @endguest
                    </li>
                    <li>
                        <input name="search" id="search" class="search-input" type="text" placeholder="Search for questions...">
                        <div id="suggestion" class="hidden">
                            <br>
                            <div><b>1.</b> Search normally. <i>Ex: Thì hiện tại hoàn thành sử dụng khi nào?</i></div>
                            <br>
                            <div><b>2.</b> Search within tags. <i>Ex: [grammar] or [vocab,speaking]</i></div>
                            <br>
                        </div>
                    </li>
                    @auth
                        <li>
                            <div class="dropdown1">
                                <i class="icon-bell-alt" id="notification-bell"></i>
                                <div id="noti-block">
                                    <span id="noti-count">{{ Auth::user()->unreadNotifications->count() }}</span>
                                    <div id="noti-list" class="hidden">
                                        <div class="noti-list">
                                            @foreach (Auth::user()->notifications as $notification)
                                                @if ($notification->type == 'App\Notifications\PublishQuestion')
                                                    <a href="{{ route('user.readNotiPublishQuestion', ['notiId' => $notification->id, 'questionId' => $notification->data['question_id']]) }}">
                                                        @if ($notification->read_at == null) 
                                                            <div class="noti not-read" style="display: flex">
                                                                <div style="width: 51.25px; height: 50px;">
                                                                    <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                    src="{{ Auth::user($notification->notifiable_id)->avatar ?? asset('images/default_avatar.png') }}" alt="">
                                                                </div>
                                                                <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                    Question <b>{{ App\Models\Question::find($notification->data['question_id'])->title }}</b> is published.
                                                                    <div>
                                                                        <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else 
                                                            <div class="noti" style="display: flex">
                                                                <div style="width: 51.25px; height: 50px;">
                                                                    <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                    src="{{ Auth::user($notification->notifiable_id)->avatar }}" alt="">
                                                                </div>
                                                                <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                    Question <b>{{ App\Models\Question::find($notification->data['question_id'])->title }}</b> is published.
                                                                    <div>
                                                                        <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </a>
                                                @endif
                                                @if ($notification->type == 'App\Notifications\NewQuestionToFollowers')
                                                    <a href="{{ route('user.readNotiPublishQuestion', ['notiId' => $notification->id, 'questionId' => $notification->data['question_id']]) }}">
                                                        @if ($notification->read_at == null) 
                                                            <div class="noti not-read" style="display: flex">
                                                                <div style="width: 51.25px; height: 50px;">
                                                                    <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                    src="{{ $notification->data['user_avatar'] }}" alt="">
                                                                </div>
                                                                <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                    <b>{{ $notification->data['user_name'] }}</b> posted a new question.
                                                                    <div>
                                                                        <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else 
                                                            <div class="noti" style="display: flex">
                                                                <div style="width: 51.25px; height: 50px;">
                                                                    <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                    src="{{ $notification->data['user_avatar'] }}" alt="">
                                                                </div>
                                                                <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                    </b>{{ $notification->data['user_name'] }}</b> posted a new question.
                                                                    <div>
                                                                        <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </a>
                                                @endif
                                                @if ($notification->type == 'App\Notifications\NewAnswerToFollowers')
                                                    <a class="new-answer-noti" href="{{ route('user.readNotiNewAnswer', ['notiId' => $notification->id, 'questionId' => $notification->data['question_id'], 'newAnswerId' => $notification->data['answer_id']]) }}">
                                                        @if (Auth::id() != $notification->notifiable_id) 
                                                            {{-- follower  --}}
                                                            @if ($notification->read_at == null) 
                                                                <div class="noti not-read" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> posted an answer to the <b>{{ $notification->data['question_title'] }}</b> question you followed.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else 
                                                                <div class="noti" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> posted a new answer to the <b>{{ $notification->data['question_title'] }}</b> question you followed.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else 
                                                            {{-- author --}}
                                                            @if ($notification->read_at == null) 
                                                                <div class="noti not-read" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> posted an answer to your question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else 
                                                                <div class="noti" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> posted an answer to your question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </a>
                                                @endif
                                                @if ($notification->type == 'App\Notifications\NewCommentNoti')
                                                    <a href="{{ route('user.readNewCommentNoti', ['notiId' => $notification->data['noti_id'], 'questionId' => $notification->data['question_id'], 'commentId' => $notification->data['comment_id'], 'page' => $notification->data['page']])}}">
                                                        @if ($notification->read_at == null) 
                                                            <div class="noti not-read" style="display: flex">
                                                                <div style="width: 51.25px; height: 50px;">
                                                                    <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                    src="{{ $notification->data['comment_user_avatar'] }}" alt="">
                                                                </div>
                                                                <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                    <b>{{ $notification->data['comment_user_name'] }}</b> posted a comment to your answer in the question <b>{{ $notification->data['question_title'] }}</b>
                                                                    <div>
                                                                        <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="noti" style="display: flex">
                                                                <div style="width: 51.25px; height: 50px;">
                                                                    <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                    src="{{ $notification->data['comment_user_avatar'] }}" alt="">
                                                                </div>
                                                                <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                    <b>{{ $notification->data['comment_user_name'] }}</b> posted a comment to your answer in the question <b>{{ $notification->data['question_title'] }}</b>
                                                                    <div>
                                                                        <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </a>
                                                @endif
                                                @if ($notification->type == 'App\Notifications\UpdateAnswerNoti')
                                                    <a href="{{ route('answers.readUpdateAnswerNoti', ['notiId' => $notification->data['noti_id'], 'questionId' => $notification->data['question_id'], 'answerId' => $notification->data['answer_id']])}}">
                                                        @if (isset($notification->data['follower_id'])) 
                                                            {{-- follower  --}}
                                                            @if ($notification->read_at == null) 
                                                                <div class="noti not-read" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> updated an answer in the <b>{{ $notification->data['question_title'] }}</b> question you followed.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else 
                                                                <div class="noti" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> updated a new answer in the <b>{{ $notification->data['question_title'] }}</b> question you followed.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else 
                                                            {{-- author --}}
                                                            @if ($notification->read_at == null) 
                                                                <div class="noti not-read" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> updated an answer in your question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else 
                                                                <div class="noti" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> updated an answer in your question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </a>
                                                @endif
                                                @if ($notification->type == 'App\Notifications\NewPrivateCommentNoti')
                                                    <a href="{{ route('answers.readUpdateAnswerNoti', ['notiId' => $notification->data['noti_id'], 'questionId' => $notification->data['question_id'], 'answerId' => $notification->data['answer_id']])}}">
                                                        @if (isset($notification->data['question_user_name'])) 
                                                            {{-- thong bao toi chu cau tra loi --}}
                                                            @if ($notification->read_at == null) 
                                                                <div class="noti not-read" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['question_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['question_user_name'] }}</b> added a new private comment to your answer in question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else 
                                                                <div class="noti" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['question_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['question_user_name'] }}</b> added a new private comment to your answer in question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else 
                                                            {{-- thong bao toi chu cau hoi --}}
                                                            @if ($notification->read_at == null) 
                                                                <div class="noti not-read" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> added a new private comment to an answer in your question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else 
                                                                <div class="noti" style="display: flex">
                                                                    <div style="width: 51.25px; height: 50px;">
                                                                        <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                                                        src="{{ $notification->data['answer_user_avatar'] }}" alt="">
                                                                    </div>
                                                                    <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                                                        <b>{{ $notification->data['answer_user_name'] }}</b> added a new private comment to an answer in your question <b>{{ $notification->data['question_title'] }}</b>.
                                                                        <div>
                                                                            <small>{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="read-all">
                                            <span style="position: absolute;">
                                                <a id="read-all" href="{{ route('user.markAllAsRead') }}">Mark All As Read</a>
                                            </span> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>  
                    @endauth 
                </ul>
            </nav>
        </section>
    </header>

    @yield('content')
    
    <div class="clearfix"></div>
    <footer id="footer">
        <section class="container">
            <div class="copyrights f_left">&copy; 2021 ASK me</div>
            <div class="social_icons f_right">
                <ul>
                    <li class="twitter"><a original-title="Twitter" class="tooltip-n" href="javascript:void(0)"><i class="social_icon-twitter font17"></i></a></li>
                    <li class="facebook"><a original-title="Facebook" class="tooltip-n" href="javascript:void(0)"><i class="social_icon-facebook font17"></i></a></li>
                    <li class="gplus"><a original-title="Google plus" class="tooltip-n" href="javascript:void(0)"><i class="social_icon-gplus font17"></i></a></li>
                    <li class="youtube"><a original-title="Youtube" class="tooltip-n" href="javascript:void(0)"><i class="social_icon-youtube font17"></i></a></li>
                    <li class="skype"><a original-title="Skype" class="tooltip-n" href="javascript:void(0)"><i class="social_icon-skype font17"></i></a></li>
                    <li class="flickr"><a original-title="Flickr" class="tooltip-n" href="javascript:void(0)"><i class="social_icon-flickr font17"></i></a></li>
                    <li class="rss"><a original-title="Rss" class="tooltip-n" href="javascript:void(0)"><i class="social_icon-rss font17"></i></a></li>
                </ul>
            </div>
        </section>
    </footer>
    <div class="go-up">
        <i class="icon-chevron-up"></i>
    </div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            encrypted: true,
            cluster: 'ap1'
        });
        var channel = pusher.subscribe('PublishQuestionNotiEvent');
        channel.bind('publish-question', function(data) {
            if (data.user_id == '{{ Auth::id() }}') {
                var newNoti =
                    `<a href="http://localhost:8000/user/readNotiPublishQuestion/${data.noti_id}/${data.question_id}">
                        <div class="noti not-read" style="display: flex">
                            <div style="width: 51.25px; height: 50px;">
                                <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                src="${data.user_avatar}" alt="">
                            </div>
                            <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                Question <b>${data.question_title}</b> is published.
                                <div>
                                    <small>Just now</small>
                                </div>
                            </div>
                        </div>
                    </a>`
                $('.noti-list').prepend(newNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
                console.log(data);
            }
            if (data.follower_id == '{{ Auth::id() }}') {
                var newNoti =
                    `<a href="http://localhost:8000/user/readNotiPublishQuestion/${data.noti_id}/${data.question_id}">
                        <div class="noti not-read" style="display: flex">
                            <div style="width: 51.25px; height: 50px;">
                                <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                                src="${data.user_avatar}" alt="">
                            </div>
                            <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                                <b>${data.user_name}</b> posted a new question.
                                <div>
                                    <small>Just now</small>
                                </div>
                            </div>
                        </div>
                    </a>`
                $('.noti-list').prepend(newNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
                console.log(data);
            }
        });
        var channel2 = pusher.subscribe('NewAnswerNotiEvent');
        channel2.bind('new-answer', function(data) {
            // notify to author
            if (data.author_id == '{{ Auth::id() }}') {
                var newAnswerNoti =
                `<a class="new-answer-noti-${data.answer_id}" href="http://localhost:8000/user/readNotiNewAnswer/${data.noti_id}/${data.question_id}/${data.answer_id}">
                    <div class="noti not-read" style="display: flex">
                        <div style="width: 51.25px; height: 50px;">
                            <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                            src="${data.answer_user_avatar}" alt="">
                        </div>
                        <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                            <b>${data.answer_user_name}</b> posted an answer to your question <b>${data.question_title}</b>.
                            <div>
                                <small>Just now</small>
                            </div>
                        </div>
                    </div>
                </a>`;
                $('.noti-list').prepend(newAnswerNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
                console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $(`.new-answer-noti-${data.answer_id}`).on('click', function(e){
                    e.preventDefault();
                    $.ajax({
                        type: "GET",
                        url: $(this).attr('href'),
                        success: function(dataAjax){
                            window.location.href = `http://localhost:8000/questions/${dataAjax.questionId}?page=${dataAjax.newAnswerPage}&_reload${Date.now()}#answer-${dataAjax.newAnswerId}`
                        },
                        error: function(error){
                        
                        }
                    });
                });
            }
            // notify to followers
            if (data.follower_id == '{{ Auth::id() }}') {
                var newAnswerNoti =
                `<a class="new-answer-noti-${data.answer_id}" href="http://localhost:8000/user/readNotiNewAnswer/${data.noti_id}/${data.question_id}/${data.answer_id}">
                    <div class="noti not-read" style="display: flex">
                        <div style="width: 51.25px; height: 50px;">
                            <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                            src="${data.answer_user_avatar}" alt="">
                        </div>
                        <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                            <b>${data.answer_user_name}</b> posted an answer to the <b>${data.question_title}</b> question you followed.
                            <div>
                                <small>Just now</small>
                            </div>
                        </div>
                    </div>
                </a>`;
                $('.noti-list').prepend(newAnswerNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
                console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $(`.new-answer-noti-${data.answer_id}`).on('click', function(e){
                    e.preventDefault();
                    $.ajax({
                        type: "GET",
                        url: $(this).attr('href'),
                        success: function(dataAjax){
                            window.location.href = `http://localhost:8000/questions/${dataAjax.questionId}?page=${dataAjax.newAnswerPage}&_reload${Date.now()}#answer-${dataAjax.newAnswerId}`
                        },
                        error: function(error){
                        
                        }
                    });
                });
            }
        });
        var channel3 = pusher.subscribe('NewCommentNotiEvent');
        channel3.bind('new-comment', function(data) {
            if (data.answer_author_id == '{{ Auth::id() }}') {
                var newCommentNoti =
                `<a href="http://localhost:8000/user/readNewCommentNoti/${data.noti_id}/${data.question_id}/${data.comment_id}/${data.page}">
                    <div class="noti not-read" style="display: flex">
                        <div style="width: 51.25px; height: 50px;">
                            <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                            src="${data.comment_user_avatar}" alt="">
                        </div>
                        <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                            <b>${data.comment_user_name}</b> posted a comment to your answer in the question <b>${data.question_title}</b>
                            <div>
                                <small>Just now</small>
                            </div>
                        </div>
                    </div>
                </a>`;
                $('.noti-list').prepend(newCommentNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
            }
        });
        var channel4 = pusher.subscribe('UpdateAnswerEvent');
        channel4.bind('update-answer', function(data) {
            // notify to author
            if (data.author_id == '{{ Auth::id() }}') {
                var newAnswerNoti =
                `<a class="new-answer-noti-${data.answer_id}" href="http://localhost:8000/answers/readUpdateAnswerNoti/${data.noti_id}/${data.question_id}/${data.answer_id}">
                    <div class="noti not-read" style="display: flex">
                        <div style="width: 51.25px; height: 50px;">
                            <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                            src="${data.answer_user_avatar}" alt="">
                        </div>
                        <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                            <b>${data.answer_user_name}</b> updated an answer in your question <b>${data.question_title}</b>.
                            <div>
                                <small>Just now</small>
                            </div>
                        </div>
                    </div>
                </a>`;
                $('.noti-list').prepend(newAnswerNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
            }
            // notify to followers
            if (data.follower_id == '{{ Auth::id() }}') {
                var newAnswerNoti =
                `<a class="new-answer-noti-${data.answer_id}" href="http://localhost:8000/answers/readUpdateAnswerNoti/${data.noti_id}/${data.question_id}/${data.answer_id}">
                    <div class="noti not-read" style="display: flex">
                        <div style="width: 51.25px; height: 50px;">
                            <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                            src="${data.answer_user_avatar}" alt="">
                        </div>
                        <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                            <b>${data.answer_user_name}</b> updated an answer in the <b>${data.question_title}</b> question you followed.
                            <div>
                                <small>Just now</small>
                            </div>
                        </div>
                    </div>
                </a>`;
                $('.noti-list').prepend(newAnswerNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
            }
        });
        var channel5 = pusher.subscribe('NewPrivateCommentNotiEvent');
        channel5.bind('new-private-comment', function(data) {
            // notify to chu cau tra loi
            if (data.answer_user_id == '{{ Auth::id() }}') {
                var newAnswerNoti =
                `<a href="http://localhost:8000/answers/readUpdateAnswerNoti/${data.noti_id}/${data.question_id}/${data.answer_id}">
                    <div class="noti not-read" style="display: flex">
                        <div style="width: 51.25px; height: 50px;">
                            <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                            src="${data.question_user_avatar}" alt="">
                        </div>
                        <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                            <b>${data.question_user_name}</b> added a new private comment to your answer in question <b>${data.question_title}</b>.
                            <div>
                                <small>Just now</small>
                            </div>
                        </div>
                    </div>
                </a>`
                $('.noti-list').prepend(newAnswerNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
            }
            // notify to chu cau hoi
            if (data.question_user_id == '{{ Auth::id() }}') {
                var newAnswerNoti =
                `<a href="http://localhost:8000/answers/readUpdateAnswerNoti/${data.noti_id}/${data.question_id}/${data.answer_id}">
                    <div class="noti not-read" style="display: flex">
                        <div style="width: 51.25px; height: 50px;">
                            <img style=" background:white; border: 1px solid #D1E5F1; border-radius:50%; height: 100%; width: 100%; object-fit: contain" 
                            src="${data.answer_user_avatar}" alt="">
                        </div>
                        <div style="width: 290px; margin-left: 10px; margin-top: 5px">
                            <b>${data.answer_user_name}</b> added a new private comment to an answer in your question <b>${data.question_title}</b>.
                            <div>
                                <small>Just now</small>
                            </div>
                        </div>
                    </div>
                </a>`;
                $('.noti-list').prepend(newAnswerNoti)
                $('#noti-count').html(parseInt($('#noti-count').html()) + 1);
            }
        });
    </script>
</body>
</html>
