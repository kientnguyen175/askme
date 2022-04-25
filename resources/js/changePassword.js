$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var form = $('#change-password');
    form.on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: "PATCH",
            url: form.attr('action'),
            data: form.serialize(),
            success: function(data){
                console.log(data.response)
                if (data.response == 1) {
                    tata.success('Password', 'Change password successfully!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                } else {
                    tata.error('Password', 'The data is not correct!', {
                        duration: 5000,
                        animate: 'slide'
                    });
                }
            },
            error: function(error){
                tata.error('Password', 'Failed to change password!', {
                    duration: 5000,
                    animate: 'slide'
                });
            }
        });
    });
});
