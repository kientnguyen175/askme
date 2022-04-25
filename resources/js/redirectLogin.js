$(document).ready(function(){
    $('.icon-heart').on('click', function(e){
        if (!checkLogin) window.location.href = "http://localhost:8000/login";
    })
});
