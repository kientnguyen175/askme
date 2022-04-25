$(document).ready(function(){
    window.newAnswerId;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var form = $('#post-answer');
    form.on('submit', function(e){
        e.preventDefault();
        var formData = new FormData($("#post-answer")[0]);
        formData.append('content', answerEditor.getData());
        formData.append('conversation', conversation);
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formData,
            processData: false, // for multipart/form-data
            contentType: false, // for multipart/form-data
            success: function(data){
                if (data.response == -1) {
                    window.location.href = "http://localhost:8000/login";
                } 
                if (data.response == 0) {
                    tata.error('Post Answer', 'Please fill out the required fields!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
                if (data.response == 1) {
                    window.location.href = `http://localhost:8000/questions/${data.questionId}?page=${data.newAnswerPage}&_reload${Date.now()}#answer-${data.newAnswerId}`
                }
                // if (data.response == 1) {
                //     // images block
                //     var imageBlock = ``;
                //     if (data.imageURLs != '[]') {
                //         imageBlock += `<div class="bxslider" sty>`;
                //         for (let i = 0; i < data.imageURLs.length; i++) {
                //             imageBlock += `<div class="slide"><div class="grid-bxslider"><div class="bxslider-overlay t_center"><a href="#" class="bxslider-title"><br><h4 style="margin-top: -3px">`
                //                 + (i + 1) 
                //                 + `/`
                //                 + data.imageURLs.length
                //                 + `</h4></a><a href="`
                //                 + data.imageURLs[i]
                //                 + `" class="prettyPhoto" rel="prettyPhoto"><span class="overlay-lightbox overlay-lightbox-for-answer"><i class="icon-search"></i></span></a></div><div style="width:119.25px; height:74.325px"><img style="max-width: 100%; max-height: 100%;" src="`
                //                 + data.imageURLs[i]
                //                 + `" alt=""></div></div></div>`
                //             ;
                //         }
                //         imageBlock += `</div>`;
                //     }
                //     console.log(imageBlock)   

                //     // medias block
                //     var mediaBlock = ``;
                //     if (data.imageURLs != '[]') {
                //         mediaBlock += `<div>`;
                //         for (let i = 0; i < data.mediaURLs.length; i++) {
                //             mediaBlock += `<audio controls controlsList="nodownload" style="width: 240px;"><source src="`
                //                 + data.mediaURLs[i]
                //                 + `" type="audio/ogg"><source src="`
                //                 + data.mediaURLs[i]
                //                 + `" type="audio/mpeg">Your browser does not support the audio element.</audio><span>&nbsp;</span>`
                //             ;
                //         }
                //         mediaBlock += `</div><br>`;
                //     }
                //     console.log(mediaBlock)   

                //     $('.infinite-scroll').prepend(
                //         `<li class="comment" id="new-answer"><div class="comment-body comment-body-answered clearfix"><div class="avatar"><img alt="" src=" ${data.answerUserAvatar || 'http://localhost:8000/images/default_avatar.png'}`
                //         + `"></div><div class="comment-text"><div class="author clearfix"><div class="comment-author"><a href="#">`
                //         + data.answerUserName
                //         + `</a></div><div class="comment-meta"><div class="date"><i class="icon-time"></i>`
                //         + data.time
                //         + `</div></div></div><div class="text">`
                //         + `<div class="ckeditor-container"><div id="editor`
                //         + data.keyCkeditor
                //         + `"></div><div id="sidebar`
                //         + data.keyCkeditor
                //         + `" class="ckeditor-sidebar"></div></div>`
                //         + `<div style="width: 567px"><br>`
                //         + imageBlock
                //         + mediaBlock
                //         + `</div>`
                //         + `</div><a id="new-answer-vote" class="vote-answer" href="http://localhost:8000/questions/voteAnswer/`
                //         + data.newAnswerId
                //         + `"><i class="icon-heart heart-icon-answer-unvote" id="vote-answer-`
                //         + data.newAnswerId
                //         + `"></i></a><span class="answer-vote-number" id="answer-`
                //         + data.newAnswerId
                //         + `-vote-number">0</span> <b style="font-size: 13px">votes</b></div></div><ul class="children"></ul></li>`
                //         + `<script src="http://localhost:8000/bower_components/askme-style/js/jquery.carouFredSel-6.2.1-packed.js"></script>`
                //         + `<script src="http://localhost:8000/bower_components/askme-style/js/jquery.bxslider.min.js"></script>`
                //         + `<script src="http://localhost:8000/bower_components/askme-style/js/custom.js"></script>`
                //     )
                //     console.log(data.imagesCount, data.mediasCount);
                //     class CommentsIntegrationFactory {
                //         constructor(appData) {
                //             this.appData = appData
                //         }
                //         genCommentsIntegration() {
                //             const self = this;
                //             return class CommentsIntegration {
                //                 constructor(editor) {
                //                     this.editor = editor;
                //                 }
                //                 init() {
                //                     const usersPlugin = this.editor.plugins.get('Users');
                //                     const commentsRepositoryPlugin = this.editor.plugins.get('CommentsRepository');
                //                     // Load the users data.
                //                     for (const user of self.appData.users) {
                //                         usersPlugin.addUser(user);
                //                     }
                //                     // Set the current user.
                //                     usersPlugin.defineMe(self.appData.userId);
                //                     // Load the comment threads data.
                //                     for (const commentThread of self.appData.commentThreads) {
                //                         commentsRepositoryPlugin.addCommentThread(commentThread);
                //                     }
                //                     commentsRepositoryPlugin.adapter = {
                //                         addComment(data) {
                //                             const commentThreadsData = commentsRepositoryPlugin.getCommentThreads( {
                //                                 skipNotAttached: true,
                //                                 skipEmpty: true,
                //                                 toJSON: true
                //                             } );
                //                             $.ajaxSetup({
                //                                 headers: {
                //                                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //                                 }
                //                             });
                //                             $.ajax({
                //                                 type: 'POST',
                //                                 url: 'http://localhost:8000/questions/answer/' + self.appData.answerId + '/updateConversation',
                //                                 data: {
                //                                     conversation: JSON.stringify(commentThreadsData) 
                //                                 },
                //                                 success: function(data){
                                                    
                //                                 },
                //                                 error: function(error){
                                                   
                //                                 }
                //                             });
                //                             return Promise.resolve({
                //                                 createdAt: new Date() // Should be set on the server side.
                //                             })
                //                         },
                //                         updateComment(data) {
                //                             console.log('Comment updated', data)
                //                             const commentThreadsData = commentsRepositoryPlugin.getCommentThreads( {
                //                                 skipNotAttached: true,
                //                                 skipEmpty: true,
                //                                 toJSON: true
                //                             } );
                //                             $.ajaxSetup({
                //                                 headers: {
                //                                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //                                 }
                //                             });
                //                             $.ajax({
                //                                 type: 'POST',
                //                                 url: 'http://localhost:8000/questions/answer/' + self.appData.answerId + '/updateConversation',
                //                                 data: {
                //                                     conversation: JSON.stringify(commentThreadsData) 
                //                                 },
                //                                 success: function(data){
                                                    
                //                                 },
                //                                 error: function(error){
                                                
                //                                 }
                //                             });
                //                             // Write a request to your database here. The returned `Promise`
                //                             // should be resolved when the request has finished.
                //                             return Promise.resolve()
                //                         },
                //                         removeComment( data ) {
                //                             console.log( 'Comment removed', data );
                //                             const commentThreadsData = commentsRepositoryPlugin.getCommentThreads( {
                //                                 skipNotAttached: true,
                //                                 skipEmpty: true,
                //                                 toJSON: true
                //                             } );
                //                             $.ajaxSetup({
                //                                 headers: {
                //                                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //                                 }
                //                             });
                //                             $.ajax({
                //                                 type: 'POST',
                //                                 url: 'http://localhost:8000/questions/answer/' + self.appData.answerId + '/updateConversation',
                //                                 data: {
                //                                     conversation: JSON.stringify(commentThreadsData) 
                //                                 },
                //                                 success: function(data){
                                                    
                //                                 },
                //                                 error: function(error){

                //                                 }
                //                             });
                //                             // Write a request to your database here. The returned `Promise`
                //                             // should be resolved when the request has finished.
                //                             return Promise.resolve();
                //                         },
                //                     }
                //                 }
                //             }
                //         }
                //     }
                //     var appData = {
                //         answerId: data.newAnswerId,
                //         users: [
                //             {
                //                 id: 'user-' + data.answerUserId,
                //                 name: data.answerUserName,
                //                 avatar: data.answerUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                //             }
                //         ],
                //         userId: 'user-' + data.answerUserId,
                //         commentThreads: JSON.parse(data.answerConversation),
                //         initialData: data.answerContent
                //     }
                //     ClassicEditor
                //         .create(document.querySelector('#editor' + data.keyCkeditor), {
                //             initialData: appData.initialData,
                //             licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
                //             extraPlugins: [new CommentsIntegrationFactory(appData).genCommentsIntegration()],
                //             sidebar: {
                //                 container: document.querySelector('#sidebar' + data.keyCkeditor)
                //             },
                //             link: {
                //                 addTargetToExternalLinks: true
                //             },
                //             commentsOnly: true,
                //         })
                //         .then(editor => {
                //             editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
                //             editor.model.markers.on( 'update:comment', ( evt, marker, oldRange, newRange ) => {
                //                 if ( !newRange ) {
                //                     const threadId = marker.name.split( ':' ).pop();
                //                     const editorData = editor.data.get();
                //                     const commentsRepository = editor.plugins.get('CommentsRepository');
                //                     const commentThreadsData = commentsRepository.getCommentThreads( {
                //                         skipNotAttached: true,
                //                         skipEmpty: true,
                //                         toJSON: true
                //                     } );
                //                     $.ajaxSetup({
                //                         headers: {
                //                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //                         }
                //                     });
                //                     $.ajax({
                //                         type: 'POST',
                //                         url: 'http://localhost:8000/questions/answer/' + appData.answerId + '/deleteConversationThread',
                //                         data: {
                //                             conversation: JSON.stringify(commentThreadsData),
                //                             answerContent: editorData
                //                         },
                //                         success: function(data){
                                            
                //                         },
                //                         error: function(error){

                //                         }
                //                     });

                //                     console.log( `The comment thread with ID ${ threadId } has been removed.` );
                //                 }
                //             } );
                //         })
                //         .catch(error => console.error(error));
                //     $('#answer-number').html(
                //         parseInt($('#answer-number').html()) + 1
                //     )

                //     answerEditor.setData('');
                //     // window.location.href = 'http://localhost:8000/questions/' + data.questionId + '#tab-top';
                //     $(document).ready(function(){
                //         $.ajaxSetup({
                //             headers: {
                //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //             }
                //         });
                //         var voteNewAnswer = $('#new-answer-vote');
                //         voteNewAnswer.on('click', function(e) {
                //             e.preventDefault();
                //             $.ajax({
                //                 type: 'POST',
                //                 url: voteNewAnswer.attr('href'),
                //                 success: function(data){
                //                     console.log('hello')
                //                     if (data.response == 1) {
                //                         voteNewAnswer.children().removeClass('heart-icon-answer-unvote').addClass('heart-icon-answer-vote');
                //                         var newVote = parseInt(voteNewAnswer.next().html()) + 1;
                //                         voteNewAnswer.next().html(newVote);
                //                     }
                //                     if (data.response == 0) {
                //                         console.log('hi');
                //                         voteNewAnswer.children().removeClass('heart-icon-answer-vote').addClass('heart-icon-answer-unvote');
                //                         var newVote = parseInt(voteNewAnswer.next().html()) - 1;
                //                         voteNewAnswer.next().html(newVote);
                //                     }
                //                 },
                //                 error: function(error){
                //                     console.log('faild')
                //                 }
                //             });
                //         });
                //     });
                //     document.getElementById("tab-top").scrollIntoView()
                // }
            },
            error: function(error){
                tata.error('Post Answer', 'Something wrong. Please try again!', {
                    duration: 5000,
                    animate: 'slide'
                });
            }
        });
    });
});
