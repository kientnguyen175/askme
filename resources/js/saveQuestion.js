$(document).ready(function () {
    $('#save-this-question').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: `http://localhost:8000/user/saveQuestion/${questionId}`,
            data: {
                questionId: questionId
            },
            success: function(data){
                $('#save-this-question').parent().addClass('hidden');
                $('#unsave-this-question').removeClass('hidden')
            },
            error: function(error){

            }
        });
    });
    $('#unsave-this-question').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: `http://localhost:8000/user/unsaveQuestion/${questionId}`,
            data: {
                questionId: questionId
            },
            success: function(data){
                $('#unsave-this-question').addClass('hidden');
                $('#save-this-question').parent().removeClass('hidden');
            },
            error: function(error){

            }
        });
    });
    $('#just-save').on('click', function () {
        tata.success('Save', 'Saved', {
            duration: 5000,
            animate: 'slide'
        });
    })

    var collectionForm = $('#login-form');
    collectionForm.on('submit', function(e){
        e.preventDefault();
        var collectionFormData = new FormData($("#login-form")[0]);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: collectionForm.attr('action'),
            data: collectionFormData,
            processData: false, // for multipart/form-data
            contentType: false, // for multipart/form-data
            success: function(data){
                if (data.response == -1) {
                    tata.error('Save to old collection', 'You dont have any collections. Please create new collection!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
                if (data.response == 0) {
                    tata.error('Save to old collection', 'Need to choose a collection!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
                if (data.response == 1) {
                    tata.error('Save to collection', 'Need to choose saving to old collection or create new collection!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
                if (data.response == 2) {
                    tata.error('Save to collection', 'Title is require when create new collection!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
                if (data.response == 3) {
                    $('#just-save').click();
                    // $('.js-example-basic-multiple').append(`<option value=${data.newCollectionId}>${data.newCollectionName}</option>`);
                }
                if (data.response == 4) {
                    $('#just-save').click();
                }
            },
            error: function(error){
                
            }
        });
    });
});