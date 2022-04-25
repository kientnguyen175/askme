$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var voteIcon = $('.vote-answer');
    voteIcon.each(function() {
        var currentElement = $(this);
        currentElement.on('click', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: currentElement.attr('href'),
                success: function(data){
                    console.log(data.response)
                    if (data.response == 1) {
                        currentElement.children().removeClass('heart-icon-answer-unvote').addClass('heart-icon-answer-vote');
                        var newVote = parseInt(currentElement.next().html()) + 1;
                        currentElement.next().html(newVote);
                    }
                    if (data.response == 0) {
                        currentElement.children().removeClass('heart-icon-answer-vote').addClass('heart-icon-answer-unvote');
                        var newVote = parseInt(currentElement.next().html()) - 1;
                        currentElement.next().html(newVote);
                    }
                },
                error: function(error){
                   
                }
            });
        });
    });
    
});
