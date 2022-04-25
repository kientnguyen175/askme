$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var voteIcon = $('.vote-question');
    voteIcon.on('click', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: voteIcon.attr('href'),
            success: function(data){
                console.log(data.response)
                if (data.response == 1) {
                    voteIcon.children().removeClass('heart-icon-unvote').addClass('heart-icon-vote');
                    var newVote = parseInt($('#vote-number').html()) + 1;
                    $('#vote-number').html(newVote);
                }
                if (data.response == 0) {
                    voteIcon.children().removeClass('heart-icon-vote').addClass('heart-icon-unvote');
                    var newVote = parseInt($('#vote-number').html()) - 1;
                    $('#vote-number').html(newVote);
                }
            },
            error: function(error){
               
            }
        });
    });
});
