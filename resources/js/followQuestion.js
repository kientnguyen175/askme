$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#follow-this-question').on('click', function(e){
        $.ajax({
            type: "POST",
            url: `http://localhost:8000/user/followQuestion/${questionId}`,
            success: function(data){
                $('#follow-this-question').addClass('hidden');
                $('#unfollow-this-question').removeClass('hidden');
            },
            error: function(error){
                
            }
        });
    });
    $('#unfollow-this-question').on('click', function(e){
        $.ajax({
            type: "POST",
            url: `http://localhost:8000/user/unfollowQuestion/${questionId}`,
            success: function(data){
                $('#unfollow-this-question').addClass('hidden');
                $('#follow-this-question').removeClass('hidden');
            },
            error: function(error){
                
            }
        });
    });
});
