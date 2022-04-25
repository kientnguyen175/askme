$(document).ready(function(){
    const items = document.querySelectorAll('.current_page_item');
    items.forEach((item) => {
        item.classList.remove("current_page_item");
    });
    document.getElementById("personal").classList.add('current_page_item');
    document.getElementById("my-profile").classList.add('current_page_item');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var form = $('#update-profile');
    form.on('submit', function(e){
        e.preventDefault();
        var formData = new FormData($("#update-profile")[0]);
        formData.append('_method', 'PATCH'),
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formData,
            processData: false, // for multipart/form-data
            contentType: false, // for multipart/form-data
            success: function(data){
                $('#website-link').val(data.website_link);
                $(".name").html(data.name);
                $("#username").val(data.username);
                tata.success('Profile', 'Update profile successfully!', {
                    duration: 5000,
                    animate: 'slide'
                });
            },
            error: function(error){
                tata.error('Profile', 'Failed to update profile!', {
                    duration: 5000,
                    animate: 'slide'
                });
            }
        });
    });
});
