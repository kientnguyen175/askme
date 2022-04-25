$(document).ready(function () {
    window.questionEditor
    ClassicEditor
        .create(document.querySelector('#editor'), {
            initialData: content,
            licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    '|',
                    'undo',
                    'redo',
                    '|',
                    'blockquote',
                ]
            },
            link: {
                defaultProtocol: 'https://'
            }
        })
        .then(editor => {
            questionEditor = editor;
            editor.editing.view.change(writer => {
                writer.setStyle(
                    "height",
                    "321px",
                    editor.editing.view.document.getRoot()
                );
            });
        })
        .catch(error => console.error(error));

    // ...
    $('input[name="photos[]"]').attr("accept", "image/x-png,image/gif,image/jpeg,image/jpg")
    
    // ajax to send content to controller
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var form = $('#update-question');
    form.on('submit', function(e){
        e.preventDefault();
        imgs = document.querySelectorAll(".uploaded-image");
        var imgUrls = [];
        imgs.forEach(img => {
            imgUrls.push(img.getAttribute('data-alt'));
        });
        
        var formData = new FormData($("#update-question")[0]);
        formData.append('content', questionEditor.getData());
        formData.append('imgUrls', imgUrls);
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formData,
            processData: false, 
            contentType: false,
            success: function(data){
                if (data.response == 0) {
                    tata.error('Profile', 'Body of the question is required!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
                if (data.response == 1) {
                    window.location.href = 'http://localhost:8000/questions/' + data.questionId
                }
            },
            error: function(error){
                tata.error('Profile', 'Make sure you filled out the required fields!', {
                    duration: 5000,
                    animate: 'slide'
                });
            }
        });
    });
    
});
