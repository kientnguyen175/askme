$(document).ready(function () {
    // update collection
    $('#edit-collection').on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = new FormData($("#edit-collection")[0]);
        $.ajax({
            type: "POST",
            url: $('#edit-collection').attr('action'),
            data: formData,
            processData: false, // for multipart/form-data
            contentType: false, // for multipart/form-data
            success: function(data){
                $('#collection-name').html(`${data.name}`)
                $('#cancel-updating').click();
                tata.success('Collection', 'Update collection successfully!', {
                    duration: 5000,
                    animate: 'slide'
                });
            },
            error: function(error){
                tata.error('Collection', 'Failed to update collection!', {
                    duration: 5000,
                    animate: 'slide'
                });
            }
        });
    })

    // delete collection
    $('#delete-collection').on('click', function () {
        cuteAlert({
            type: "question",
            title: "Delete Collection",
            message: "Are you sure to delete this collection?",
            confirmText: "Yes",
            cancelText: "Cancel"
        }).then((e) => {
            if (e == ("confirm")) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: `http://localhost:8000/user/collection/${$('#delete-collection').attr('data-collection')}/destroy`,
                    success: function(data){
                        window.location.href = `http://localhost:8000/user/savedQuestions`
                    },
                    error: function(error){
                        
                    }
                });
            };
        });
    });
});
