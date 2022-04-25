$(document).ready(function () {
    window.answerEditor
    if (questionUserId != currentUserId) {
        var appData = {
            users: [
                {
                    id: 'user-' + questionUserId,
                    name: questionUserName,
                    avatar: questionUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                },
                {
                    id: 'user-' + currentUserId,
                    name: currentUserName,
                    avatar: currentUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                },
            ],
            userId: 'user-' + currentUserId,
            commentThreads: conversation ? JSON.parse(conversation) : '',
            initialData: content
        }
    } else {
        var appData = {
            users: [
                {
                    id: 'user-' + questionUserId,
                    name: questionUserName,
                    avatar: questionUserAvatar || 'http://localhost:8000/images/default_avatar.png'
                },
            ],
            userId: 'user-' + currentUserId,
            commentThreads: conversation ? JSON.parse(conversation) : '',
            initialData: content
        }
    }
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
                    commentsRepositoryPlugin.adapter = {
                        addComment(data) {
                            const commentThreadsData = commentsRepositoryPlugin.getCommentThreads( {
                                skipNotAttached: true,
                                skipEmpty: true,
                                toJSON: true
                            } );
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
                            // Write a request to your database here. The returned `Promise`
                            // should be resolved when the request has finished.
                            return Promise.resolve();
                        },
                        
                    }
                }
            }
        }
    }
    
    ClassicEditor
        .create(document.querySelector('#editor'), {
            initialData: appData.initialData,
            licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
            extraPlugins: [new CommentsIntegrationFactory(appData).genCommentsIntegration()],
            sidebar: {
                container: document.querySelector('#sidebar')
            },
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    '|',
                    'undo',
                    'redo',
                    '|',
                    'blockquote',
                    'comment'
                ]
            },
            link: {
                defaultProtocol: 'https://'
            }
        })
        .then(editor => {
            editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
            
            answerEditor = editor;
            editor.editing.view.change(writer => {
                writer.setStyle(
                    "height",
                    "321px",
                    editor.editing.view.document.getRoot()
                );
            });
        })
        .catch(error => console.error(error));

    // ...
    $('input[name="photos[]"]').attr("accept", "image/x-png,image/gif,image/jpeg,image/jpg")
    
    // ajax to send content to controller
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var form = $('#update-answer');
    form.on('submit', function(e){
        e.preventDefault();
        imgs = document.querySelectorAll(".uploaded-image");
        var imgUrls = [];
        imgs.forEach(img => {
            imgUrls.push(img.getAttribute('data-alt'));
        });
        
        var formData = new FormData($("#update-answer")[0]);
        formData.append('content', answerEditor.getData());
        formData.append('imgUrls', imgUrls);
        const commentsRepository = answerEditor.plugins.get('CommentsRepository');
        const commentThreadsData = commentsRepository.getCommentThreads( {
            skipNotAttached: true,
            skipEmpty: true,
            toJSON: true
        } );
        formData.append('conversation', JSON.stringify(commentThreadsData));
        formData.append('questionId', questionId);
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formData,
            processData: false, 
            contentType: false,
            success: function(data){
                if (data.response == 0) {
                    tata.error('Profile', 'Body of the answer is required!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
                if (data.response == 1) {
                    window.location.href = `http://localhost:8000/questions/${questionId}?page=${data.page}#answer-${data.answerId}`
                }
            },
            error: function(error){
                tata.error('Profile', 'Make sure you filled out the required fields!', {
                    duration: 5000,
                    animate: 'slide'
                });
            }
        });
    });
});
