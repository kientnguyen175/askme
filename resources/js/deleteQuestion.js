$(document).ready(function () {
    var removeButtons = $('.remove-question-button');
    removeButtons.each(function () {
        var removeButton = $(this);
        removeButton.on('click', function (e) {
            cuteAlert({
                type: "question",
                title: "Delete Question",
                message: "Are you sure to delete this question?",
                confirmText: "Yes",
                cancelText: "Cancel"
            }).then((e) => {
                if (e == ("confirm")) {
                    $.ajax({
                        type: "GET",
                        url: 'http://localhost:8000/questions/' + removeButton.attr('id').replace('delete-question-', '') + '/delete',
                        success: function (data) {
                            removeButton.parent().remove();
                            if (parseInt($('#totalQuestions').html()) >= 1) {
                                $('#totalQuestions').html(parseInt($('#totalQuestions').html()) - 1)
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
