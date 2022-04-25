$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#follow-user').on('click', function(e){
        $.ajax({
            type: "POST",
            url: `http://localhost:8000/user/followUser/${questionUserId}`,
            success: function(data){
                $('#follow-user').addClass('hidden');
                $('#unfollow-user').removeClass('hidden');
            },
            error: function(error){
                
            }
        });
    });
    $('#unfollow-user').on('click', function(e){
        $.ajax({
            type: "POST",
            url: `http://localhost:8000/user/unfollowUser/${questionUserId}`,
            success: function(data){
                $('#unfollow-user').addClass('hidden');
                $('#follow-user').removeClass('hidden');
            },
            error: function(error){
                
            }
        });
    });
});
