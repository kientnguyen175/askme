$(document).ready(function(){
    jQuery(".share-inside").click(function () {
		if (jQuery(".share-inside-warp").hasClass("share-inside-show")) {
			jQuery(".share-inside-warp").slideUp("500");
			jQuery(".share-inside-warp").removeClass("share-inside-show");
		}else {
			jQuery(".share-inside-warp").slideDown("500");
			jQuery(".share-inside-warp").addClass("share-inside-show");
		}
	});

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
});
