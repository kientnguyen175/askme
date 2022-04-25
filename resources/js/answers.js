$(document).ready(function () {
    var removeButtons = $('.remove-question-button');
    removeButtons.each(function () {
        var removeButton = $(this);
        removeButton.on('click', function (e) {
            cuteAlert({
                type: "question",
                title: "Delete Answer",
                message: "Are you sure to delete this answer?",
                confirmText: "Yes",
                cancelText: "Cancel"
            }).then((e) => {
                if (e == ("confirm")) {
                    $.ajax({
                        type: "POST",
                        url: 'http://localhost:8000/answers/' + removeButton.attr('id').replace('delete-question-', '') + '/destroy',
                        success: function (data) {
                            removeButton.parent().remove();
                            if (parseInt($('#totalAnswers').html()) >= 1) {
                                $('#totalAnswers').html(parseInt($('#totalAnswers').html()) - 1)
                            } 
                        },
                        error: function (error) {

                        }
                    });
                } else {
                }
            })
        });
    });
});
