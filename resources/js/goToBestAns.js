$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.go-to-best-ans').on('click', function(e){
        $.ajax({
            type: "GET",
            url: `http://localhost:8000/questions/${questionId}/goToBestAnswer`,
            success: function(data){
                window.location.href = `http://localhost:8000/questions/${questionId}?page=${data.page}#answer-${data.bestAnsId}`
            },
            error: function(error){
                
            }
        });
    });
});
