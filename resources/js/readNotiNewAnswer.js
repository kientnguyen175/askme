$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.new-answer-noti').on('click', function(e){
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function(data){
                window.location.href = `http://localhost:8000/questions/${data.questionId}?page=${data.newAnswerPage}&_reload${Date.now()}#answer-${data.newAnswerId}`
            },
            error: function(error){
               
            }
        });
    });
});
