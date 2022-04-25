$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var form = $('#post-question');
    form.on('submit', function (e) {
        e.preventDefault();
        imgs = document.querySelectorAll(".uploaded-image");
        var imgUrls = [];
        imgs.forEach(img => {
            if (img.firstChild.getAttribute('src').indexOf('blob') == 0) {
                imgUrls.push(img.firstChild.getAttribute('src'));
            }
        });
        var formData = new FormData($("#post-question")[0]);
        console.log(theEditor.getData())
        formData.append('content', theEditor.getData());
        formData.append('imgUrls', imgUrls);
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formData,
            processData: false, // for multipart/form-data
            contentType: false, // for multipart/form-data
            success: function (data) {
                if (data.response == 1) {
                    if (data.schedule == 0) {
                        window.location.href = `http://localhost:8000/questions/${data.question_id}`
                    }
                    if (data.schedule == 1) {
                        window.location.href = "http://localhost:8000/user/pendingQuestions"
                    }
                };
                if (data.response == 0) {
                    tata.error('Ask Question', 'Please fill out the required fields!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                };
                if (data.response == 2) {
                    tata.error('Ask Question', 'The schedule time needs to be longer than the current time!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }

            },
            error: function (error) {
                tata.error('Ask Question', 'Failed to post the question!', {
                    duration: 5000,
                    animate: 'slide'
                });
            }
        });
    });
});
