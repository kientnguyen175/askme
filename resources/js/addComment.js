$(document).ready(function () {
    const comments = $('.comments');
    comments.each(function () {
        var comment = $(this);
        comment.keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                if (currentUserId == 0) {
                    window.location.href = 'http://localhost:8000/login';
                } else {
                    if (comment.val().trim()) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: 'http://localhost:8000/questions/answer/' + comment.attr('id').replace('add-comment-answer-', '') + '/addComment',
                            data: {
                                comment: comment.val().trim(),
                                commentUserName: currentUserName,
                                commentUserAvatar: currentUserAvatar || 'http://localhost:8000/images/default_avatar.png',
                                questionId: questionId,
                            },
                            success: function (data) {
                                var newComment = 
                                `<li class="comment" id="comment-${data.comment_id}">
                                    <div class="comment-body clearfix"> 
                                        <div class="comment-avatar avatar">
                                            <img alt="" src="${currentUserAvatar || 'http://localhost:8000/images/default_avatar.png'}">
                                            <div class="delete-ans-comment tooltip-wrap-comment" data-comment="${data.comment_id}">
                                                <a href="javascript:void(0)"><i class="icon-remove-sign"></i></a>
                                                <div class="tooltip-content-comment-2" style="display: none">Delete</div>
                                            </div>
                                            <div class="edit-ans-comment tooltip-wrap-comment" data-comment="${data.comment_id}">
                                                <a href="javascript:void(0)"><i class="icon-edit-sign"></i></a>
                                                <div class="tooltip-content-comment-1" style="display: none">Edit</div>
                                            </div>
                                        </div>
                                        <div class="comment-text">
                                            <div class="author comment-info clearfix">
                                                <div class="comment-author comment-font-size"><a href="#">${currentUserName}</a></div>
                                                <div class="comment-meta" style="display: flex">
                                                    <div class="date comment-date"><i class="icon-time"></i>Just now</div> 
                                                </div>
                                            </div>
                                            <div class="text"><div class="comment-content">
                                                <input id="edit-comment-${data.comment_id}" type="text" class="hidden update-comment" value="" data-comment="${data.comment_id}">
                                                <div id="content-comment-${data.comment_id}">${comment.val().trim()}</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>`
                                
                                $('#comment-for-answer-' + comment.attr('id').replace('add-comment-answer-', '')).before(newComment);
                                comment.val('');
                                // delete comment
                                const deleteComment = $('.delete-ans-comment');
                                deleteComment.on('click', function (e) {
                                    var thisComment = $(this);
                                    var commentId = $(this).attr('data-comment')
                                    e.preventDefault();
                                    cuteAlert({
                                        type: "question",
                                        title: "Delete Comment",
                                        message: "Are you sure to delete this comment?",
                                        confirmText: "Yes",
                                        cancelText: "Cancel"
                                    }).then((e) => {
                                        if (e == ("confirm")) {
                                            $.ajax({ 
                                                type: "POST",
                                                url: 'http://localhost:8000/comments/' + commentId + '/destroy',
                                                success: function (data) {
                                                    $(`#comment-${commentId}`).remove()
                                                },
                                                error: function (error) {

                                                }
                                            });
                                        }
                                    })
                                });

                                // edit comment 
                                const editComment = $('.edit-ans-comment');
                                editComment.on('click', function (e) {
                                    e.preventDefault();
                                    var commentId = $(this).attr('data-comment');
                                    var commentContent = $(`#content-comment-${commentId}`).html();
                                    $(`#content-comment-${commentId}`).addClass('hidden');
                                    $(`#edit-comment-${commentId}`).val(commentContent).removeClass('hidden');
                                });
                                
                                // update comment
                                const updates = $('.update-comment');
                                updates.each(function () {
                                    var update = $(this);
                                    update.keypress(function (event) {
                                        var keycode = (event.keyCode ? event.keyCode : event.which);
                                        if (keycode == '13') {
                                            if (update.val().trim()) {
                                                $.ajaxSetup({
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    }
                                                });
                                                $.ajax({
                                                    type: "POST",
                                                    url: 'http://localhost:8000/comments/' + update.attr('data-comment') + '/update',
                                                    data: {
                                                        comment: update.val().trim(),
                                                    },
                                                    success: function (data) {
                                                        $(`#edit-comment-${data.commentId}`).addClass('hidden');
                                                        $(`#content-comment-${data.commentId}`).html(data.comment).removeClass('hidden')
                                                    },
                                                    error: function (error) {

                                                    }
                                                });
                                            }
                                            else {
                                                tata.error('Update Comment', 'The comment is empty!', {
                                                    duration: 5000,
                                                    animate: 'slide'
                                                });
                                            }
                                        }
                                    });
                                });
                            },
                            error: function (error) {
    
                            }
                        });
                    }
                    else {
                        tata.error('Add Comment', 'The comment is empty!', {
                            duration: 5000,
                            animate: 'slide'
                        });
                    }
                }
            }
        });
    });
});
