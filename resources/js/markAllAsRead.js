$(document).ready(function () {
    $('#read-all').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $('#read-all').attr('href'),
            success: function(data){
                $('.not-read').each( function () {
                    $(this).removeClass('not-read');
                })
                $('#noti-count').html('0');
            },
            error: function(error){
               
            }
        });
    })
});
