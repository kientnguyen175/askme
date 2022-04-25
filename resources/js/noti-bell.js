$(document).ready(function(){
    $('#notification-bell').on('click', function () {
        var element = document.getElementById("noti-list");
        element.classList.toggle("hidden");
    })
});
