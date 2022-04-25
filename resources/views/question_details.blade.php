@extends('layouts.master')

@section('style')
	@parent
    {{-- <link rel="stylesheet" href="{{ asset('css/editor.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/ipa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recorder.css') }}">
    <link rel="stylesheet" href="{{ asset('css/questionDetailsPage.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/cute-alert/style.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/jquery-modal/jquery.modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/collection.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/avatar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> 
    <link rel="stylesheet" href="{{ asset('bower_components/image-uploader/css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/image-uploader/css/image-uploader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/image-uploader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/audioUploader.css') }}">   
    <script>
        if (!document.addEventListener) {
            parent.location.href = 'ie8/type.html';
        }
        //--------------------------------------------------------------------
        if (parent.location.href != window.location.href) {
            parent.location.href = window.location.href;
        }
        //--------------------------------------------------------------------
        var f = 0;
        var left = "pete";
        var right = "e-lang.co.uk";
        //--------------------------------------------------------------------
        var report = 'typefb.php';
        var about = 'typehelp.html';
        var home = 'http://mackichan.e-lang.co.uk/javascript-call-activities/';
        var again = 'type.html';
        var selectedString = "";
        var ipaCode = new Array();
        ipaCode[0] = new Array("", "105,720", "618", "650", "117,720", "618,601", "101,618", "712", "716");
        ipaCode[1] = new Array("", "101", "601", "604,720", "596,720", "650,601", "596,618", "601,650");
        ipaCode[2] = new Array("", "230", "652", "593,720", "594", "101,601", "097,618", "097,650");
        ipaCode[3] = new Array("", "112", "98", "116", "100", "679", "676", "107", "103");
        ipaCode[4] = new Array("", "102", "118", "952", "240", "115", "122", "643", "658");
        ipaCode[5] = new Array("", "109", "110", "331", "104", "108", "114", "119", "106");
        //I changed this to specify a zero array length to overcome problems in IE 5.5
        var myAnswer = new Array(0);
        var help = '';
        var webNav = new Array("webIE", "webNot");
        var wordNav = new Array("wordIE", "wordNot");
        //--------------------------------------------------------------------
        browser = navigator.appName;
        var IE = false;
        if (browser == "Microsoft Internet Explorer") {
            IE = true;
        }
        //--------------------------------------------------------------------
        function doAnswer() {
            var answerString = "";
            var oldString = "";
            var oldStringLong = "";
            var partOne = "";
            var partTwo = "";
            var finalAnswer = "";
            var finalAnswerWord = "";
            var finalAnswerWeb = "";
            for (i = 0; i < myAnswer.length; i++) {
                oldString = myAnswer[i];
                if (oldString.length > 3) {
                    partOne = oldString.substr(0, 3)
                    partTwo = oldString.substr(4, 7)
                    answerString = answerString + String.fromCharCode(partOne);
                    answerString = answerString + String.fromCharCode(partTwo);
                    oldStringLong = oldStringLong + '&#' + partOne + ';';
                    oldStringLong = oldStringLong + '&#' + partTwo + ';';
                    partOne = partOne.replace('058', '720')
                    partTwo = partTwo.replace('058', '720')
                    finalAnswerWord = finalAnswerWord + String.fromCharCode(partOne);
                    finalAnswerWord = finalAnswerWord + String.fromCharCode(partTwo);
                } else {
                    answerString = answerString + String.fromCharCode(myAnswer[i]);
                    oldStringLong = oldStringLong + '&#' + oldString + ';';
                    tempString = myAnswer[i];
                    tempString = tempString.replace('058', '720');
                    finalAnswerWord = finalAnswerWord + String.fromCharCode(tempString);
                }
            }
            if (answerString.length > 0) {
                finalAnswer = "/" + answerString + "/";
            } else {
                finalAnswer = answerString;
            }
            if (!'{{ Auth::check() }}') {
                document.forms[1].resultview.value = finalAnswer;
                document.forms[1].result.value = '/' + finalAnswerWord + '/';
                finalAnswerWeb = oldStringLong;
                document.forms[1].resultweb.value = '/' + finalAnswerWeb + '/';
            } else {
                document.forms[3].resultview.value = finalAnswer;
                document.forms[3].result.value = '/' + finalAnswerWord + '/';
                finalAnswerWeb = oldStringLong;
                document.forms[3].resultweb.value = '/' + finalAnswerWeb + '/';
            }
            
        }
        //--------------------------------------------------------------------
        function chooseMe(foo) {
            cx = foo % 10;
            rx = Math.floor(foo / 10);
            b = myAnswer.length;
            myAnswer[b] = ipaCode[rx][cx];
            doAnswer();
        }
        //--------------------------------------------------------------------
        function addSpace() {
            b = myAnswer.length;
            myAnswer[b] = "032";
            doAnswer();
        }
        //--------------------------------------------------------------------
        function clearOne() {
            if (myAnswer.length > 0) {
                myAnswer.length = myAnswer.length - 1;
            }
            doAnswer();
        }
        //--------------------------------------------------------------------
        function clearAll() {
            myAnswer.length = 0;
            //there is a problem here with some IE5.5 - not an object. Why?
            doAnswer();
        }
        //--------------------------------------------------------------------
        function buttonCaption(foo) {
            var keyString = "";
            if (foo.length > 3) {
                keyString = keyString + '&#' + foo.substr(0, 3) + ';' + '&#' + foo.substr(4, 7) + ';';
            } else {
                keyString = keyString + '&#' + foo + ';';
            }
            return (keyString);
        }
    </script>
   
@endsection

@section('scripts')
	@parent
    <script>
        var checkLogin = '{{ Auth::check() }}';
        var content = '{!! $question->content->content !!}';
        // var editorNumber = '{{ $question->answers->count() }}';
        var currentUserId = "{{ Auth::id() ?? 0 }}";
        var currentUserName = "{{ Auth::user()->name ?? '' }}";
        var currentUserAvatar = '{{ Auth::user()->avatar ?? ''  }}'.replace('amp;', '');
        var questionUserId = "{{ $question->user->id }}"; 
        var questionUserAvatar = "{{ $question->user->avatar }}".replace('amp;', ''); 
        var questionId = "{{ $question->id }}";
        var questionUserName = '{{ $question->user->name }}';
        var answerUserIds = @json($answerUserIds);
        var answerUserNames = @json($answerUserNames);
        var answerUserAvatars = @json($answerUserAvatars);
        var answerContents = @json($answerContents);
        var answerConversations = @json($answerConversations);
        var answerIds = @json($answerIds);
    </script>
    <script src="{{ asset('js/recorder.js') }}"></script>
    {{-- <script src="{{ asset('js/postQuestion.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/askQuestionPage.js') }}"></script> --}}
    <script src="{{ asset('js/questionDetailsPage.js') }}"></script>
    <script src="{{ asset('js/voteQuestion.js') }}"></script>
    <script src="{{ asset('js/postAnswer.js') }}"></script>
    <script src="{{ asset('js/voteAnswer.js') }}"></script>
    <script src="{{ asset('js/redirectLogin.js') }}"></script>
    <script src="{{ asset('js/bestAnswer.js') }}"></script>
    <script src="{{ asset('bower_components/cute-alert/cute-alert.js') }}"></script>
    <script src="{{ asset('js/addComment.js') }}"></script>
    <script src="{{ asset('bower_components/jquery-modal/jquery.modal.min.js') }}"></script>
    <script src="{{ asset('js/collection.js') }}"></script>
    <script src="{{ asset('js/avatar.js') }}"></script>
    <script src="{{ asset('js/saveQuestion.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/followUser.js') }}"></script>
    <script src="{{ asset('js/followQuestion.js') }}"></script>
    <script src="{{ asset('js/goToBestAns.js') }}"></script>
    <script src="{{ asset('js/audioUploader.js') }}"></script>
    <script src="{{ asset('bower_components/image-uploader/js/image-uploader.js') }}"></script>
@endsection

@section('content')
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v11.0&appId=4159924400713205&autoLogAppEvents=1" nonce="CkitCLg0"></script>
    <div class="breadcrumbs">
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Questions</h1>
                </div>
            </div>
        </section>
    </div>
    {{-- <section class="container main-content"> --}}
        {{-- <div class="row"> --}}
            <div class="col-md-8 left-block">
                <div class="about-author clearfix">
                    <div class="author-image">
                        <a href="#" original-title="author" class="tooltip-n"><img alt="" src="{{ $question->user->avatar ? $question->user->avatar : asset('images/default_avatar.png') }}"></a>
                    </div>
                    <div class="author-bio">
                        <div style="width: 30%">
                            <a href="{{ route('user.show', $question->user->id) }}">
                                <h3 style="word-break: break-word;">{{ $question->user->name }}</h3>
                            </a>
                        </div>
                        @if (Auth::check() && !$checkFollowAuthor && Auth::id() != $question->user->id)
                            <button id="follow-user" class="follow-user">Follow This User</button>
                            <button id="unfollow-user" class="follow-user hidden">Unfollow This User</button>
                        @endif
                        @if (Auth::check() && $checkFollowAuthor)
                            <button id="follow-user" class="follow-user hidden">Follow This User</button>
                            <button id="unfollow-user" class="follow-user">Unfollow This User</button>
                        @endif
                        <span>{{ $question->user->username ? '@' . $question->user->username : ''}}</span>
                    </div>
                </div>
                <article class="question single-question question-type-normal">
                    <h2 class="question-title">
                        <a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a>
                    </h2>
                    @if ($question->best_answer_id)
                        <span class="question-answered question-answered-done solved"><i class="icon-ok"></i>solved</span>
                    @else     
                        <span class="question-answered question-answered-done solved hidden"><i class="icon-ok"></i>solved</span>
                        <span class="question-answered progress"><i class="icon-ok"></i>in progress</span>
                    @endif
                    {{-- <div class="question-type-main"><i class="icon-question-sign"></i>Question</div> --}}
                    <div class="question-inner">
                        <div class="clearfix"></div>
                        <div class="question-desc">
                            <div id="ckeditor-container">
                                <div id="editor"></div>
                                <div id="sidebar" class="ckeditor-sidebar"></div>
                            </div> 
                            <br>
                            @if ($question->images->count()) 
                                <div class="bxslider" sty>
                                    @php 
                                        $imageNumber = $question->images->count()
                                    @endphp
                                    @foreach ($question->images as $key => $image)
                                        <div class="slide" >
                                            <div class="grid-bxslider">
                                                <div class="bxslider-overlay t_center">
                                                    <a href="#" class="bxslider-title">
                                                        <br>
                                                        <h4>{{ ++$key }}/{{ $imageNumber }}</h4>
                                                    </a>
                                                    <a href="{{ $image->url }}" class="prettyPhoto" rel="prettyPhoto">
                                                        <span class="overlay-lightbox">
                                                            <i class="icon-search"></i>
                                                        </span>
                                                    </a>
                                                </div>  
                                                <div style="width:170.25px; height:106.11112px; margin:auto"><img style="height: 100%; width: 100%; object-fit: contain" src="{{ $image->url }}" alt=""></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if ($question->medias->count())
                                <div>
                                    @foreach ($question->medias as $key => $media)
                                        <audio id="audio-{{ $key }}" controls controlsList="nodownload" preload="auto">
                                            <source src="{{ $media->url }}" type="audio/ogg">
                                            <source src="{{ $media->url }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                        <span>&nbsp;</span>
                                    @endforeach
                                </div>
                                <br>
                            @endif
                            <div class="tags-block">
                                @foreach ($question->tags as $tag)
									<button class="tags">{{ $tag->tag }}</button>
								@endforeach
                            </div>
                            <br>
                        </div>
                        @if ($question->status == 1)
                            <div class="question-details">
                                <span class="question-answered question-answered-done vote-number">
                                    <a class="vote-question" href="{{ route('questions.vote', $question->id) }}">
                                        @if (!$votedCheck)
                                            <i class="icon-heart heart-icon-unvote"></i>
                                        @else
                                            <i class="icon-heart heart-icon-vote"></i>
                                        @endif
                                    </a>
                                    <span id="vote-number">{{ $question->vote_number }}</span> votes
                                </span>
                                {{-- <span class="question-favorite"><i class="icon-star"></i>5</span> --}}
                            </div>
                        @endif
                        {{-- <span class="question-category"><a href="#"><i class="icon-folder-close"></i>wordpress</a></span> --}}
                        <span class="question-date"><i class="icon-time"></i>{{ Carbon\Carbon::parse($question->created_at)->diffForHumans() }}</span>
                        <span class="question-comment"><i class="icon-comments"></i>{{ $question->answers->count() }} answers</span>
                        <span class="question-view"><i class="icon-eye-open"></i>{{ $question->view_number }} views</span>
                        @if ($question->updated)
                            <span class="question-view question-edited"><i class="icon-pencil"></i>edited {{ Carbon\Carbon::parse($question->updated_at)->diffForHumans() }}</span>
                        @endif
                        @if (Auth::id() == $question->user->id)
                            <span class="single-question-vote-result"><button class="question-options" id="delete-question">Delete</button></span>
                            <a href="{{ route('questions.edit', $question->id) }}"><span class="single-question-vote-result"><button class="question-options" id="edit-question">Edit</button></span></a>
                        @endif
                        {{-- <ul class="single-question-vote">
                            <li><a href="#" class="single-question-vote-down" title="Dislike"><i class="icon-thumbs-down"></i></a></li>
                            <li><a class="question-report single-question-vote-up" href="#">Report</a></li>
                        </ul> --}}
                        <div class="clearfix"></div>
                    </div>
                </article>
                @if ($question->status == 1)
                    @auth
                        <div class="share-tags page-content">
                            <div class="question-tags">
                                @if (Auth::id() != $question->user->id && !$checkFollowQuestion)
                                    <button id="follow-this-question">Follow This Question</button>
                                    <button id="unfollow-this-question" class="hidden">Unfollow This Question</button>
                                @endif
                                @if (Auth::id() != $question->user->id && $checkFollowQuestion)
                                    <button id="follow-this-question" class="hidden">Follow This Question</button>
                                    <button id="unfollow-this-question">Unfollow This Question</button>
                                @endif
                                <form action="{{ route('users.saveToCollection', $question->id) }}" id="login-form" class="modal" method="POST">
                                    @csrf
                                    <div class="wrapper">
                                        {{-- <div class="ways">
                                            <ul>
                                                <li class="active">
                                                    Saved
                                                </li>
                                            </ul>
                                        </div>
                                        <br> --}}
                                        {{-- <div>
                                            <section>
                                            <option value="">hello</option>
                                        </section>
                                        </div> --}}
                                        <div class="sections option-block">
                                            <div style="display: flex">
                                                <input value="old" class="save-options" type="radio" name="saveOptions">
                                                <label class="label-options" for="">Save to old collection</label>
                                            </div>
                                            <br>
                                            <section class="active">
                                                {{-- <select id="choose-collection" style="color: black" name="chooseCollection" multiple>
                                                    <option value=""><b>Choose collection...</b></option>
                                                    @foreach ($collections as $collection)
                                                        <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                                    @endforeach
                                                </select> --}}
                                                <select class="js-example-basic-multiple" name="chooseCollection[]" multiple="multiple">
                                                    @foreach ($collections as $collection)
                                                        <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                                    @endforeach
                                                </select>
                                                <script>
                                                    $(document).ready(function() {
                                                        $('.js-example-basic-multiple').select2();
                                                    });
                                                </script>
                                                {{-- <div class="select-option">
                                                    <div class="head">Choose a collection...</div>
                                                    <div class="option"></div>
                                                </div> --}}
                                            </section>
                                        </div>
                                        <br>
                                        <div class="sections option-block">
                                            <div style="display: flex">
                                                <input value="new" class="save-options" type="radio" name="saveOptions">
                                                <label class="label-options" for="">Create new collection</label>
                                            </div>
                                            <br>
                                            <section class="active">
                                                
                                                <input name="title" type="text" placeholder="Title (required)" id="title"/> 
                                                {{-- <div class="images">
                                                    <div class="pic">
                                                        add
                                                    </div>
                                                </div> --}}
                                                <div class="avatar-wrapper">
                                                    <img src="{{ asset('images/default_collection.jpg') }}" alt="" class="profile-pic">
                                                    <div class="upload-button">
                                                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                                    </div>
                                                    <input class="file-upload" id="avatar" name="image" type="file" accept="image/*"/>
                                                </div>
                                            </section>
                                            <section>
                                                <input type="text" placeholder="Topic" id="topic"/>
                                                <textarea placeholder="something..." id="msg"></textarea>
                                            </section>
                                        </div>
                                        <footer class="collection-footer">
                                            <a href="#" rel="modal:close"><button id="just-save">Not save to any collection</button></a>
                                            <input id="send1" type="submit" value="Save to collection">
                                        </footer> 
                                    </div>
                                </form>
                                @if (isset($saveQuestion) && $saveQuestion == 0)
                                    <a href="#login-form" rel="modal:open"><button id="save-this-question">Save</button></a>
                                    <button class="hidden" id="unsave-this-question">Unsave</button>
                                @endif
                                @if (isset($saveQuestion) && $saveQuestion == 1)
                                    <a class="hidden" href="#login-form" rel="modal:open"><button id="save-this-question">Save</button></a>
                                    <button id="unsave-this-question">Unsave</button>
                                @endif
                            </div>
                            <div class="fb-share-button" data-href="http://127.0.0.1:8000/questions/55/" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Chia sẻ</a></div>
                            <div class="clearfix"></div>
                        </div>
                    @endauth
                @endif
                @if ($question->status == 1)
                    <div id="commentlist" class="page-content">
                        <div class="boxedtitle page-title"  id="tab-top">
                            <h2>
                                Answers ( <span class="color" id="answer-number">{{ $question->answers->count() }}</span> )  
                                <div id="sort-answers">
                                    @if ($sortBy == 'oldest')
                                        <a href="{{ route('questions.showBy', ['questionId' => $question->id, 'sortBy' => 'vote_number']) }}#tab-top">
                                            <button class="sort-answers-button sort-answers-first-button">
                                                Votes
                                            </button> 
                                        </a>
                                        <button class="sorted-by sort-answers-end-button">Oldest</button> 
                                    @elseif ($sortBy == 'vote_number')
                                        <button class="sorted-by sort-answers-first-button">
                                            Votes
                                        </button> 
                                        <a href="{{ route('questions.show', $question->id) }}#tab-top">
                                            <button class="sort-answers-button sort-answers-end-button">Oldest</button> 
                                        </a>
                                    @endif
                                    @if ($question->best_answer_id)
                                        <button class="go-to-best-ans" data-best-id="">Go To Best Answer</button>
                                    @else 
                                        <button class="go-to-best-ans hidden" data-best-id="">Go To Best Answer</button>
                                    @endif
                                </div>
                            </h2>
                        </div>
                        <div>
                            {{ $answers->links() }}
                        </div>
                        <ol class="commentlist clearfix">
                            <script>
                                class CommentsIntegrationFactory {
                                    constructor(appData) {
                                        this.appData = appData
                                    }
                                    genCommentsIntegration() {
                                        const self = this;
                                        return class CommentsIntegration {
                                            constructor(editor) {
                                                this.editor = editor;
                                            }
                                            init() {
                                                const usersPlugin = this.editor.plugins.get('Users');
                                                const commentsRepositoryPlugin = this.editor.plugins.get('CommentsRepository');
                                                // Load the users data.
                                                for (const user of self.appData.users) {
                                                    usersPlugin.addUser(user);
                                                }
                                                // Set the current user.
                                                usersPlugin.defineMe(self.appData.userId);
                                                // Load the comment threads data.
                                                for (const commentThread of self.appData.commentThreads) {
                                                    commentsRepositoryPlugin.addCommentThread(commentThread);
                                                }
                                                let oldConversation = self.appData.commentThreads;
                                                commentsRepositoryPlugin.adapter = {
                                                    addComment(data) {
                                                        const commentThreadsData = commentsRepositoryPlugin.getCommentThreads( {
                                                            skipNotAttached: true,
                                                            skipEmpty: true,
                                                            toJSON: true
                                                        } );
                                                        
                                                        $.ajaxSetup({
                                                            headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                            }
                                                        });
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'http://localhost:8000/questions/answer/' + self.appData.answerId + '/updateConversation',
                                                            data: {
                                                                conversation: JSON.stringify(commentThreadsData),
                                                                oldConversation: JSON.stringify(oldConversation),
                                                                addComment: 1
                                                            },
                                                            success: function(data){
                                                                oldConversation = commentThreadsData;

                                                                if (!data.response) {
                                                                    tata.error('Add Comment', 'Failed!', {
                                                                        duration: 5000,
                                                                        animate: 'slide'
                                                                    });
                                                                }
                                                            },
                                                            error: function(error){
                                                                
                                                            }
                                                        });
                                                        // Write a request to your database here. The returned `Promise`
                                                        // should be resolved when the request has finished.
                                                        // When the promise resolves with the comment data object, it
                                                        // will update the editor comment using the provided data.
                                                        return Promise.resolve({
                                                            createdAt: new Date() // Should be set on the server side.
                                                        })
                                                    },
                                                    updateComment(data) {
                                                        console.log('Comment updated', data)
                                                        const commentThreadsData = commentsRepositoryPlugin.getCommentThreads( {
                                                            skipNotAttached: true,
                                                            skipEmpty: true,
                                                            toJSON: true
                                                        } );
                                                        $.ajaxSetup({
                                                            headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                            }
                                                        });
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'http://localhost:8000/questions/answer/' + self.appData.answerId + '/updateConversation',
                                                            data: {
                                                                conversation: JSON.stringify(commentThreadsData),
                                                                oldConversation: JSON.stringify(oldConversation)
                                                            },
                                                            success: function(data){
                                                                oldConversation = commentThreadsData;
                                                                if (!data.response) {
                                                                    tata.error('Update Comment', 'Failed!', {
                                                                        duration: 5000,
                                                                        animate: 'slide'
                                                                    });
                                                                }
                                                            },
                                                            error: function(error){
                                                            
                                                            }
                                                        });
                                                        // Write a request to your database here. The returned `Promise`
                                                        // should be resolved when the request has finished.
                                                        return Promise.resolve()
                                                    },
                                                    removeComment( data ) {
                                                        console.log( 'Comment removed', data );
                                                        const commentThreadsData = commentsRepositoryPlugin.getCommentThreads( {
                                                            skipNotAttached: true,
                                                            skipEmpty: true,
                                                            toJSON: true
                                                        } );
                                                        $.ajaxSetup({
                                                            headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                            }
                                                        });
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'http://localhost:8000/questions/answer/' + self.appData.answerId + '/updateConversation',
                                                            data: {
                                                                conversation: JSON.stringify(commentThreadsData),
                                                                oldConversation: JSON.stringify(oldConversation)
                                                            },
                                                            success: function(data){
                                                                oldConversation = commentThreadsData;
                                                                if (!data.response) {
                                                                    tata.error('Remove Comment', 'Failed!', {
                                                                        duration: 5000,
                                                                        animate: 'slide'
                                                                    });
                                                                }
                                                            },
                                                            error: function(error){

                                                            }
                                                        });
                                                        // Write a request to your database here. The returned `Promise`
                                                        // should be resolved when the request has finished.
                                                        return Promise.resolve();
                                                    },
                                                    
                                                }
                                            }
                                        }
                                    }
                                }
                            </script>
                            <div class="infinite-scroll">
                                @foreach ($answers as $key => $answer)
                                    <script>
                                        var i = '{{ $answers->firstItem() + $key - 1 }}';
                                    </script>
                                    <li class="comment" id="answer-{{ $answer->id }}">
                                        <div class="comment-body comment-body-answered clearfix"> 
                                            <div class="avatar">
                                                <img alt="" src="{{ $answer->user->avatar ? $answer->user->avatar : asset('images/default_avatar.png') }}">
                                                @if ($answer->user->id == Auth::id() || $question->user->id == Auth::id())
                                                    <div class="delete-ans tooltip-wrap" data-ans="{{ $answer->id }}">
                                                        <a href="javascript:void(0)"><i class="icon-remove-sign"></i></a>
                                                        <div class="tooltip-content-2" style="display: none">Delete</div>
                                                    </div>
                                                @endif
                                                @if ($answer->user->id == Auth::id())
                                                    <div class="edit-ans tooltip-wrap">
                                                        <a href="{{ route('answers.edit', $answer->id) }}"><i class="icon-edit-sign"></i></a>
                                                        <div class="tooltip-content-1" style="display: none">Edit</div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="comment-text">
                                                <div class="author clearfix">
                                                    <div class="comment-author"><a href="{{ route('user.show', $answer->user_id) }}">{{ $answer->user->name }}</a></div>
                                                    {{-- <div class="comment-vote"> --}}
                                                        {{-- <ul class="question-vote"> --}}
                                                            {{-- <li><a href="#" class="question-vote-up" title="Like"></a></li>
                                                            <li><a href="#" class="question-vote-down" title="Dislike"></a></li> --}}
                                                            {{-- <div class="ul_list ul_list-icon-ok ul_list_circle" list_background="#3498db" list_background_hover="#2f3239" list_color="#FFF">
                                                                <ul>
                                                                    <a href="javacript:void(0)"><li><i l_background="#e74c3c" l_background_hover="red" class="icon-heart ul_l_circle"></i></li></a>
                                                                </ul>
                                                            </div> --}}
                                                        {{-- </ul> --}}
                                                    {{-- </div> --}}
                                                    {{-- <span class="question-vote-result">1 votes</span> --}}
                                                    <div class="comment-meta">
                                                        <div class="date"><i class="icon-time"></i>{{ Carbon\Carbon::parse($answer->created_at)->diffForHumans() }}</div> 
                                                    </div>
                                                    @if ($question->best_answer_id == $answer->id)
                                                        <div class="question-answered question-answered-done" id="best-answer">
                                                            <i id="best-{{ $answer->id }}" class="icon-ok"></i>Best Answer
                                                        </div>
                                                        <button id="best-answer-{{ $answer->id }}" class="best-answer hidden">Best Answer</button> 
                                                    @elseif (Auth::id() == $question->user->id)
                                                        <div class="question-answered question-answered-done hidden">
                                                            <i id="best-{{ $answer->id }}" class="icon-ok"></i>Best Answer
                                                        </div>
                                                        <button id="best-answer-{{ $answer->id }}" class="best-answer">Best Answer</button> 
                                                    @endif
                                                </div>
                                                <div class="text">
                                                    <div class="ckeditor-container">
                                                        <div id="editor{{ $answer->id }}"></div>
                                                        <div id="sidebar{{ $answer->id }}" class="ckeditor-sidebar"></div>
                                                    </div> 
                                                    <div style="width: 630px">
                                                        <br>
                                                        @if ($answer->images->count()) 
                                                            <div class="bxslider">
                                                                @php 
                                                                    $imageAnswerNumber = $answer->images->count()
                                                                @endphp
                                                                @foreach ($answer->images as $answerImageKey => $answerImage)
                                                                    <div class="slide">
                                                                        <div class="grid-bxslider">
                                                                            <div class="bxslider-overlay t_center">
                                                                                <a href="#" class="bxslider-title">
                                                                                    <br>
                                                                                    <h4 style="margin-top: -3px">{{ ++$answerImageKey }}/{{ $imageAnswerNumber }}</h4>
                                                                                </a>
                                                                                <a href="{{ $answerImage->url }}" class="prettyPhoto" rel="prettyPhoto"><span class="overlay-lightbox overlay-lightbox-for-answer"><i class="icon-search"></i></span></a>
                                                                            </div>
                                                                            <div style="width:135px; height:84.14px; margin: auto">
                                                                                <img style="width: 100%; height: 100%; object-fit: contain;" src="{{ $answerImage->url }}" alt="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        @if ($answer->medias->count())
                                                            <div>
                                                                @foreach ($answer->medias as $answerMediaKey => $media)
                                                                    <audio controls controlsList="nodownload" style="width: 240px;" preload="auto">
                                                                        <source src="{{ $media->url }}" type="audio/ogg">
                                                                        <source src="{{ $media->url }}" type="audio/mpeg">
                                                                        Your browser does not support the audio element.
                                                                    </audio>
                                                                    <span>&nbsp;</span>
                                                                @endforeach
                                                            </div>
                                                            <br>
                                                        @endif
                                                        
                                                    </div>
                                                </div>
                                                {{-- <i class="vote-answer" l_background="#e74c3c" l_background_hover="#2f3239" class="icon-star ul_l_circle"></i> --}}
                                                {{-- <a class="comment-reply" href="#"><i class="icon-reply"></i>Reply</a>  --}}
                                                <a class="vote-answer" href="{{ route('answers.vote', $answer->id) }}">
                                                    @if ($answerVotedCheck[$answers->firstItem() + $key - 1] == 0) 
                                                        <i class="icon-heart heart-icon-answer-unvote" id="vote-answer-{{$answer->id}}"></i>
                                                    @else
                                                        <i class="icon-heart heart-icon-answer-vote answer-vote" id="vote-answer-{{$answer->id}}"></i>
                                                    @endif
                                                </a>   
                                                <span class="answer-vote-number" id="answer-{{ $answer->id }}-vote-number">{{ $answer->vote_number }}</span> <b style="font-size: 13px">votes</b> 
                                                {{-- @if ($question->best_answer_id != $answer->id)
                                                    <button class="best-answer">Edit</button>
                                                @endif  --}}
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-11" style="float: right; margin-right: -18px;">
                                            <div class="accordion toggle-accordion">
                                                <h4 class="accordion-title active"><a href="#">Comments</a></h4>
                                                <div class="accordion-inner" style="display: block">
                                                    
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="clearfix"></div>
                                        <ul class="children" id="real-comments">
                                            @foreach ($answer->comments as $comment)
                                                <li class="comment" id="comment-{{ $comment->id }}">
                                                    <div class="comment-body clearfix"> 
                                                        <div class="comment-avatar avatar">
                                                            <img alt="" src="{{ $comment->user->avatar ?? asset('images/default_avatar.png') }}">
                                                            @if ($comment->user->id == Auth::id() || $question->user->id == Auth::id())
                                                                <div class="delete-ans-comment tooltip-wrap-comment" data-comment="{{ $comment->id }}">
                                                                    <a href="javascript:void(0)"><i class="icon-remove-sign"></i></a>
                                                                    <div class="tooltip-content-comment-2" style="display: none">Delete</div>
                                                                </div>
                                                            @endif
                                                            @if ($comment->user->id == Auth::id())
                                                                <div class="edit-ans-comment tooltip-wrap-comment" data-comment="{{ $comment->id }}">
                                                                    <a href="javascript:void(0)"><i class="icon-edit-sign"></i></a>
                                                                    <div class="tooltip-content-comment-1" style="display: none">Edit</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="comment-text">
                                                            <div class="author comment-info clearfix">
                                                                <div class="comment-author comment-font-size"><a href="#">{{ $comment->user->name }}</a></div>
                                                                {{-- <div class="comment-vote">
                                                                    <ul class="question-vote">
                                                                        <li><a href="#" class="question-vote-up" title="Like"></a></li>
                                                                        <li><a href="#" class="question-vote-down" title="Dislike"></a></li>
                                                                    </ul>
                                                                </div> --}}
                                                                {{-- <span class="question-vote-result">+1</span> --}}
                                                                <div class="comment-meta" style="display: flex">
                                                                    <div class="date comment-date"><i class="icon-time"></i>{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</div> 
                                                                </div>
                                                                {{-- <a class="comment-reply" href="#"><i class="icon-reply"></i>Reply</a>  --}}
                                                            </div>
                                                            <div class="text"><div class="comment-content">
                                                                <input id="edit-comment-{{ $comment->id }}" type="text" class="hidden update-comment" value="" data-comment="{{ $comment->id }}">
                                                                <div id="content-comment-{{ $comment->id }}">{{ $comment->comment }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                            <li class="comment" id="comment-for-answer-{{ $answer->id }}">
                                                <div class="comment-body clearfix"> 
                                                    <div>
                                                        <div class="avatar comment-avatar">
                                                            <img alt="" src="{{ Auth::user()->avatar ?? asset('images/default_avatar.png') }}"> 
                                                        </div>
                                                        <input id="add-comment-answer-{{ $answer->id }}" class="comments" type="text" name="comment" placeholder="Add a comment...">
                                                    </div> 
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <script>
                                        if (currentUserId != questionUserId && currentUserId != answerUserIds[i]) {
                                            if (questionUserId != answerUserIds[i]) {
                                                console.log(currentUserId, questionUserId,answerUserIds[i]);
                                                var appData = {
                                                    answerId: answerIds[i],
                                                    users: [
                                                        {
                                                            id: 'user-' + questionUserId,
                                                            name: questionUserName,
                                                            avatar: questionUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                                                        },
                                                        {
                                                            id: 'user-' + answerUserIds[i],
                                                            name: answerUserNames[i],
                                                            avatar: answerUserAvatars[i] || 'http://localhost:8000/images/default_avatar.png'
                                                        },
                                                        {
                                                            id: 'user-' + currentUserId,
                                                            name: currentUserName,
                                                            avatar: currentUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                                                        },
                                                    ],
                                                    // The ID of the current user.
                                                    userId: 'user-' + currentUserId,
                                                    // CommentThreads
                                                    commentThreads: JSON.parse(answerConversations[i]),
                                                    // Editor initial data.
                                                    initialData: answerContents[i]
                                                }
                                                if (answerConversations[i] != '[]') {
                                                    ClassicEditor
                                                        .create(document.querySelector('#editor' + answerIds[i]), {
                                                            initialData: appData.initialData,
                                                            extraPlugins: [new CommentsIntegrationFactory(appData).genCommentsIntegration()],
                                                            licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
                                                            sidebar: {
                                                                container: document.querySelector('#sidebar' + answerIds[i])
                                                            },
                                                            link: {
                                                                addTargetToExternalLinks: true
                                                            },
                                                        })
                                                        .then(editor => {
                                                            editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
                                                            editor.isReadOnly = true
                                                            
                                                            
                                                            
                                                        })
                                                        .catch(error => console.error(error));
                                                } else {
                                                    ClassicEditor
                                                        .create(document.querySelector('#editor' + answerIds[i]), {
                                                            initialData: appData.initialData,
                                                            licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
                                                            sidebar: {
                                                                container: document.querySelector('#sidebar' + answerIds[i])
                                                            },
                                                            link: {
                                                                addTargetToExternalLinks: true
                                                            },
                                                        })
                                                        .then(editor => {
                                                            editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
                                                            editor.isReadOnly = true
                                                            
                                                            
                                                            
                                                        })
                                                        .catch(error => console.error(error));
                                                }
                                                
                                            } 
                                            if (questionUserId == answerUserIds[i]) {
                                                var appData = {
                                                    answerId: answerIds[i],
                                                    users: [
                                                        // {
                                                        //     id: 'user-' + questionUserId,
                                                        //     name: questionUserName,
                                                        //     avatar: questionUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                                                        // },
                                                        {
                                                            id: 'user-' + currentUserId,
                                                            name: currentUserName,
                                                            avatar: currentUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                                                        },
                                                        {
                                                            id: 'user-' + answerUserIds[i],
                                                            name: answerUserNames[i],
                                                            avatar: answerUserAvatars[i] || 'http://localhost:8000/images/default_avatar.png'
                                                        },
                                                        // {
                                                        //     id: 'user-' + currentUserId,
                                                        //     name: currentUserName,
                                                        //     avatar: currentUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                                                        // }
                                                    ],
                                                    // The ID of the current user.
                                                    userId: 'user-' + currentUserId,
                                                    // CommentThreads
                                                    commentThreads: JSON.parse(answerConversations[i]),
                                                    // Editor initial data.
                                                    initialData: answerContents[i]
                                                }
                                                if (answerConversations[i] != '[]') {
                                                    ClassicEditor
                                                        .create(document.querySelector('#editor' + answerIds[i]), {
                                                            initialData: appData.initialData,
                                                            extraPlugins: [new CommentsIntegrationFactory(appData).genCommentsIntegration()],
                                                            licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
                                                            sidebar: {
                                                                container: document.querySelector('#sidebar' + answerIds[i])
                                                            },
                                                            link: {
                                                                addTargetToExternalLinks: true
                                                            },
                                                        })
                                                        .then(editor => {
                                                            editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
                                                            editor.isReadOnly = true
                                                            
                                                            
                                                            
                                                        })
                                                        .catch(error => console.error(error)); 
                                                } else {
                                                    ClassicEditor
                                                        .create(document.querySelector('#editor' + answerIds[i]), {
                                                            initialData: appData.initialData,
                                                            licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
                                                            sidebar: {
                                                                container: document.querySelector('#sidebar' + answerIds[i])
                                                            },
                                                            link: {
                                                                addTargetToExternalLinks: true
                                                            },
                                                        })
                                                        .then(editor => {
                                                            editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
                                                            editor.isReadOnly = true
                                                            
                                                            
                                                            
                                                        })
                                                        .catch(error => console.error(error)); 
                                                }
                                            }
                                        } else if (currentUserId == questionUserId && currentUserId == answerUserIds[i]) {
                                            var appData = {
                                                answerId: answerIds[i],
                                                // Users data.
                                                users: [
                                                    // {
                                                    //     id: 'user-' + questionUserId,
                                                    //     name: questionUserName,
                                                    //     avatar: questionUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                                                    // },
                                                    {
                                                        id: 'user-' + answerUserIds[i],
                                                        name: answerUserNames[i],
                                                        avatar: answerUserAvatars[i] || 'http://localhost:8000/images/default_avatar.png'
                                                    }
                                                ],
                                                // The ID of the current user.
                                                userId: 'user-' + currentUserId,
                                                // CommentThreads
                                                commentThreads: JSON.parse(answerConversations[i]),
                                                // Editor initial data.
                                                initialData: answerContents[i]
                                            }
                                            ClassicEditor
                                                .create(document.querySelector('#editor' + answerIds[i]), {
                                                    
                                                    initialData: appData.initialData,
                                                    extraPlugins: [new CommentsIntegrationFactory(appData).genCommentsIntegration()],
                                                    licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
                                                    sidebar: {
                                                        container: document.querySelector('#sidebar' + answerIds[i])
                                                    },
                                                    link: {
                                                        addTargetToExternalLinks: true
                                                    },
                                                    commentsOnly: true,
                                                })
                                                .then(editor => {
                                                    editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
                                                    // if (currentUserId == answerUserIds[i]) {
                                                    //     editor.isReadOnly = true
                                                    // }
                                                    // if (currentUserId == questionUserId) {
                                                    //     editor.plugins.get('CommentsOnly').isEnabled = true;
                                                    // }
                                                    editor.model.markers.on( 'update:comment', ( evt, marker, oldRange, newRange ) => {
                                                        if ( !newRange ) {
                                                            const threadId = marker.name.split( ':' ).pop();
                                                            const editorData = editor.data.get();
                                                            const commentsRepository = editor.plugins.get('CommentsRepository');
                                                            const commentThreadsData = commentsRepository.getCommentThreads( {
                                                                skipNotAttached: true,
                                                                skipEmpty: true,
                                                                toJSON: true
                                                            } );
                                                            $.ajaxSetup({
                                                                headers: {
                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                }
                                                            });
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: 'http://localhost:8000/questions/answer/' + editor.sourceElement.id.substr(6) + '/deleteConversationThread',
                                                                data: {
                                                                    conversation: JSON.stringify(commentThreadsData),
                                                                    oldConversation: JSON.stringify(appData.commentThreads)
                                                                },
                                                                success: function(data){
                                                                    if (data.response == 0) {
                                                                    tata.error('Delete Conversation', 'Failed!', {
                                                                        duration: 5000,
                                                                        animate: 'slide'
                                                                    });
                                                                }
                                                                },
                                                                error: function(error){

                                                                }
                                                            });
                                                            console.log( `The comment thread with ID ${ threadId } has been removed.` );
                                                        }
                                                    } );
                                                    
                                                })
                                                .catch(error => console.error(error));
                                        } else {
                                            var appData = {
                                                answerId: answerIds[i],
                                                // Users data.
                                                users: [
                                                    {
                                                        id: 'user-' + questionUserId,
                                                        name: questionUserName,
                                                        avatar: questionUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                                                    },
                                                    {
                                                        id: 'user-' + answerUserIds[i],
                                                        name: answerUserNames[i],
                                                        avatar: answerUserAvatars[i] || 'http://localhost:8000/images/default_avatar.png'
                                                    }
                                                ],
                                                // The ID of the current user.
                                                userId: 'user-' + currentUserId,
                                                // CommentThreads
                                                commentThreads: JSON.parse(answerConversations[i]),
                                                // Editor initial data.
                                                initialData: answerContents[i]
                                            }
                                            ClassicEditor
                                                .create(document.querySelector('#editor' + answerIds[i]), {
                                                    commentsOnly: true,
                                                    initialData: appData.initialData,
                                                    extraPlugins: [new CommentsIntegrationFactory(appData).genCommentsIntegration()],
                                                    licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
                                                    sidebar: {
                                                        container: document.querySelector('#sidebar' + answerIds[i])
                                                    },
                                                    link: {
                                                        addTargetToExternalLinks: true
                                                    },
                                                })
                                                .then(editor => {
                                                    editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
                                                    // if (currentUserId == answerUserIds[i]) {
                                                    //     editor.isReadOnly = true
                                                    // }
                                                    // if (currentUserId == questionUserId) {
                                                    //     editor.plugins.get('CommentsOnly').isEnabled = true;
                                                    // }
                                                    // const commentsRepository = editor.plugins.get('CommentsRepository');
                                                    // Get the data on demand.
                                                    editor.model.markers.on( 'update:comment', ( evt, marker, oldRange, newRange ) => {
                                                        const commentsRepository = editor.plugins.get('CommentsRepository');
                                                        const commentThreadsData = commentsRepository.getCommentThreads( {
                                                                skipNotAttached: true,
                                                                skipEmpty: true,
                                                                toJSON: true
                                                            } );
                                                        if ( !newRange ) {
                                                            const threadId = marker.name.split( ':' ).pop();
                                                            const editorData = editor.data.get();
                                                            const commentsRepository = editor.plugins.get('CommentsRepository');
                                                            const commentThreadsData = commentsRepository.getCommentThreads( {
                                                                skipNotAttached: true,
                                                                skipEmpty: true,
                                                                toJSON: true
                                                            } );
                                                            $.ajaxSetup({
                                                                headers: {
                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                }
                                                            });
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: 'http://localhost:8000/questions/answer/' + editor.sourceElement.id.substr(6) + '/deleteConversationThread',
                                                                data: {
                                                                    conversation: JSON.stringify(commentThreadsData),
                                                                    answerContent: editorData,
                                                                    oldConversation: JSON.stringify(appData.commentThreads)
                                                                },
                                                                success: function(data){
                                                                    if (data.response == 0) {
                                                                        tata.error('Delete Conversation', 'Failed!', {
                                                                            duration: 5000,
                                                                            animate: 'slide'
                                                                        });
                                                                    }
                                                                },
                                                                error: function(error){
                                                                    //
                                                                }
                                                            });
                                                            console.log( `The comment thread with ID ${ threadId } has been removed.` );
                                                        }
                                                    } );
                                                })
                                                .catch(error => console.error(error));
                                        }
                                    </script>
                                @endforeach
                            </div>
                        </ol>
                        {{ $answers->links() }}   
                    </div>
                @endif
                @if ($relatedQuestions->count() >= 1 && $question->status == 1)
                    <div id="related-posts">
                        <h2>Suggestions</h2>
                        <div class="carousel-all testimonial-carousel testimonial-warp-2" carousel_responsive="false" carousel_auto="false" carousel_effect="scroll">
                            <div class="slides">
                                @foreach ($relatedQuestions as $relatedQuestion)
                                    <div class="testimonial-warp">
                                        <div class="testimonial">
                                            <div style="font-weight: bolder; font-size: 14px; margin-bottom: -8px">
                                                {{ $relatedQuestion->title }}
                                            </div>
                                            <br>
                                            <div style="border: 1px solid #C4C4C4; border-radius: 5px; padding: 10px">{{ mb_substr(html_entity_decode(strip_tags($relatedQuestion->content->content)),0,255) . '...' }}</div>
                                            <span class="testimonial-f-arrow"></span>
                                            <span class="testimonial-l-arrow"></span>
                                        </div>
                                        <br>
                                        <div class="testimonial-client">
                                            <img src="{{ $relatedQuestion->user->avatar ?? asset('images/default_avatar.png') }}" alt="">
                                            <span>{{ $relatedQuestion->user->name }}</span>
                                            <div style="font-size: 12px">{{ $relatedQuestion->user->points }} points</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="carousel-pagination"></div>
                        </div>
                    </div>
                @endif
                @if ($question->status == 1)
                    <div id="respond" class="comment-respond page-content clearfix">
                        <div class="boxedtitle page-title"><h2>Leave an answer</h2></div>
                        <form action="{{ route('answers.store', $question->id) }}" method="post" id="post-answer" class="comment-form" enctype="multipart/form-data">
                            @csrf
                            <div id="respond-textarea">
                                <p>
                                    <label class="required" for="comment"><i class="icon-double-angle-right"></i> Body<span>*</span></label>
                                    <div class="ckeditor-container">
                                        <div id="answer-editor"></div>
                                        <div id="answer-sidebar" class="ckeditor-sidebar"></div>
                                        <span id="paste-question-content" class="copy-question-content"><i class="icon-copy"></i></span>
                                        {{-- <span><button>Paste Question Content</button></span> --}}
                                    </div> 
                                </p>
                                <br>
                                <div>
                                    <p>
                                        <label class="required"><i class="icon-double-angle-right"></i> Images</label>
                                        <br>
                                        
                                        <input type="file" name="images[]" accept="image/x-png,image/gif,image/jpeg,image/jpg" multiple>
                                    </p>
                                    <p>
                                        <label class="required"><i class="icon-double-angle-right"></i> Audios</label>
                                        <br>
                                        <input type="file" name="medias[]" accept="audio/mp3,audio/ogg,audio/wav" multiple>
                                    </p>
                                </div>
                            </div>
                            <br>
                            <p class="form-submit">
                                @if (Auth::check())
                                    <input id="post-answer-button" type="submit" value="Post your answer" class="button small color">
                                @else
                                    <input type="submit" value="Post your answer" class="button small color" onclick="event.preventDefault(); window.location.href='http://localhost:8000/login'">
                                @endif   
                            </p>
                        </form>
                    </div>
                @endif
            </div>
            
        {{-- </div> --}}
    {{-- </section> --}}
    <aside class="col-md-3 sidebar" style="position: sticky; top: 0; margin-bottom: 86px;"> 
        <div class="widget widget_tag_cloud">
            <h3 class="widget_title">Hottest Tags</h3>
            @foreach ($topTags as $tag)
				<a style="color: #2c5777 !important" class="home-tag" href="javascript:void(0)">{{ $tag->tag }}</a>
			@endforeach
        </div>
        <div class="widget" style="height: 500px">
            <h3 class="widget_title">IPA Tool</h3>
            <form method="post" enctype="application/x-www-form-urlencoded" action="#">
                <input id="resultview" type="text" name="resultview" class="resvew" style="font-size: 16px">
                <div id="formcontent">
                    <input type="text" id="result" class="res"/>
                    <input type="text" id="resultweb" class="res"/>
                </div>
                <div class="typewriter">
                    <script type="text/javascript">
                        for(j = 0, z = 1; j < 1; j++, z = z + 10) {
                            for (i = 1, k = z; i < 5; i++, k++) {
                                p = k % 10;
                                document.write('<input type=\"button\" class=\"vowel\" onclick=\"javascript:chooseMe(' + k + ');\" value=\"' + buttonCaption(ipaCode[j][p]) + '\"/> ');
                            }
                            for (i = 5; i < 7; i++, k++) {
                                p = k % 10;
                                document.write('<input type=\"button\" class=\"dipth\" onclick=\"javascript:chooseMe(' + k + ');\" value=\"' + buttonCaption(ipaCode[j][p]) + '\"/> ');
                            }
                            for (i = 7; i < 9; i++, k++) {
                                p = k % 10;
                                document.write('<input type=\"button\" class=\"stress\" onclick=\"javascript:chooseMe(' + k + ');\" value=\"' + buttonCaption(ipaCode[j][p]) + '\"/> ');
                            }
                            document.write('<br/>');
                        }
                        for (j = 1, z = 11; j < 3; j++, z = z + 10) {
                            for (i = 1, k = z; i < 5; i++, k++) {
                                p = k % 10;
                                document.write('<input type=\"button\" class=\"vowel\" onclick=\"javascript:chooseMe(' + k + ');\" value=\"' + buttonCaption(ipaCode[j][p]) + '\"/> ');
                            }
                            for (i = 5; i < 8; i++, k++) {
                                p = k % 10;
                                document.write('<input type=\"button\" class=\"dipth\" onclick=\"javascript:chooseMe(' + k + ');\" value=\"' + buttonCaption(ipaCode[j][p]) + '\"/> ');
                            }
                            document.write('<br/>');
                        }
                        for (j = 3, z = 31; j < 6; j++, z = z + 10) {
                            for (i = 1, k = z; i < 9; i++, k++) {
                                p = k % 10;
                                document.write('<input type=\"button\" class=\"vowel\" onclick=\"javascript:chooseMe(' + k + ');\" value=\"' + buttonCaption(ipaCode[j][p]) + '\"/> ');
                            }
                            document.write('<br/>');
                        }
                    </script>
                    <br/>
                    <input type="button" onclick="clearOne();" class="buact" value="Delete"/>
                    <input type="button" onclick="addSpace();" class="buact" value="Space"/>
                    <input type="button" onclick="clearAll();;" class="buact" value="Reset"/>
                    <div style="margin-top: 5px"></div>
                    <input id="copy-ipa" type="button" class="buact" value="Copy"/>
                    <div style="margin-bottom: 20px"></div>
                    <br/><br/>
                </div>
            </form>
        </div>
        <div class="widget">
            <h3 class="widget_title">Voice Recorder Tool</h3>
            <div id='gUMArea'>
                <button class="btn btn-default" id='gUMbtn'>Request Voice Recorder</button>
            </div>
            <div id="record">
                <div id='btns'>
                    <button class="btn btn-default" id='start'>Start</button>
                    <button class="btn btn-default" id='stop'>Stop</button>
                </div>
                <div id="img-block">
                    <img id="gif" src="{{ asset('images/recording.gif') }}" alt="">
                </div>
            </div>
            <div id="save">
                <ul class="list-unstyled" id='ul'></ul>
            </div>
        </div>
    </aside>
    <script src="{{ asset('js/editorInQuestionDetailsPage.js') }}"></script>
    <script>
        document.querySelectorAll('.ck-label').forEach(function (a) {
            a.remove();
        })
    </script>
    <script>
        $('#paste-question-content').on('click', function () {
            const content = theEditor.getData();
            const viewFragment = answerEditor.data.processor.toView( content );
            const modelFragment = answerEditor.data.toModel( viewFragment );
            answerEditor.model.insertContent( modelFragment );
        });
    </script>
@endsection
