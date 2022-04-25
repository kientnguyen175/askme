$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const buttons = $('.best-answer');
    buttons.each(function() {
        var button = $(this);
        button.on('click', function(e){
            cuteAlert({
                type: "question",
                title: "Best Answer",
                message: "Choose this for the best answer?",
                confirmText: "Yes",
                cancelText: "Cancel"
            }).then((e)=>{
                if ( e == ("confirm")) {
                    $.ajax({
                        type: "PATCH",
                        url: 'http://localhost:8000/questions/' + questionId + '/bestAnswer',
                        data: {
                            answerId: button.attr('id').replace('best-answer-', '')
                        },
                        success: function(data){
                            // old best answer
                            var bestAns = $('#best-answer');
                            bestAns.addClass('hidden');
                            bestAns.next().removeClass('hidden');
                            bestAns.attr('id', '')
                            // new best answer
                            var newBestAns = $(`#best-answer-${data.answerId}`);
                            newBestAns.addClass('hidden');
                            newBestAns.prev().removeClass('hidden').attr('id', 'best-answer');
                            // delele in progress
                            var inProgress = $('.progress');
                            inProgress.prev().removeClass('hidden');
                            inProgress.remove();
                            // go to best ans
                            $('.go-to-best-ans').removeClass('hidden')
                        },
                        error: function(error){
                            
                        }
                    });
                } else {
                }
            })
        });
    });
});
