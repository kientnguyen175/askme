$(document).ready(function(){
    // add current page
    const items = document.querySelectorAll('.current_page_item');
    items.forEach((item) => {
        item.classList.remove("current_page_item");
    });
    document.getElementById("explore").classList.add('current_page_item');
    document.getElementById("questions").classList.add('current_page_item');

    // delete question
    const deleteButtons = $('#delete-question');
    deleteButtons.on('click', function (e) {
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
                    url: 'http://localhost:8000/questions/' + questionId + '/delete',
                    success: function (data) {
                        window.location.href = 'http://localhost:8000/user/newsfeed'
                    },
                    error: function (error) {

                    }
                });
            } else {
            }
        })
    });

    // copy question content
    $('.copy-question-content').on('click', function () {
        const editorContainer = document.getElementById('editor').nextSibling.lastChild.lastChild;
        const answerContainer = document.getElementById('answer-editor').nextSibling.lastChild.lastChild;
        const editorData = Array.from(editorContainer.childNodes);
        editorData.forEach(data => {
            const clone = data.cloneNode(true)
            answerContainer.appendChild(clone)
        });

        // document.execCommand('copy');
        
        // answerEditor.model.change( writer => {
        //     const insertPosition = answerEditor.model.document.selection.getFirstPosition();
        //     writer.insertText(content, { bold: true }, insertPosition);
        // } );
    });

    $('.bxslider-title').attr('href', 'javascript:void(0)')

    // delete answer
    const deleteAns = $('.delete-ans');
    deleteAns.on('click', function (e) {
        var thisAns = $(this);
        var answerId = $(this).attr('data-ans')
        e.preventDefault();
        cuteAlert({
            type: "question",
            title: "Delete Anser",
            message: "Are you sure to delete this answer?",
            confirmText: "Yes",
            cancelText: "Cancel"
        }).then((e) => {
            if (e == ("confirm")) {
                $.ajax({ 
                    type: "POST",
                    url: 'http://localhost:8000/answers/' + answerId + '/destroy',
                    success: function (data) {
                        $(`#answer-${answerId}`).remove();
                        $('#answer-number').html(parseInt($('#answer-number').html()) - 1);
                        if ($('.comment').length == 0) {
                            window.location.href = `http://localhost:8000/questions/${questionId}`
                        }
                        if (!data.best_answer_id) {
                            $('.go-to-best-ans').addClass('hidden');
                            prev = $('.solved').prev();
                            $('.solved').remove();
                            $("<span class='question-answered question-answered-done solved hidden'><i class='icon-ok'></i>solved</span><span class='question-answered progress'><i class='icon-ok'></i>in progress</span>").insertAfter(prev)
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
