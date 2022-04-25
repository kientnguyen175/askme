$(document).ready(function () {
    $('.audio').each(function () {
        let audio = $(this);
        audio.on('click', function (e) {
            e.preventDefault();
            audio.remove();
        })
    })
});
