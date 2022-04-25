$(document).ready(function () {
    const items = document.querySelectorAll('.current_page_item');
    items.forEach((item) => {
        item.classList.remove("current_page_item");
    });
    
    $('#user-search').on('keyup', function (e) {
        e.preventDefault()
        if ((e.key === 'Enter' || e.keyCode === 13)) {
            if ($('#user-search').val().trim()) {
                window.location.href = 'http://localhost:8000/users/search/' + $('#user-search').val().trim() + '/points#tab-top';
            } else {
                window.location.href = 'http://localhost:8000/users/view/points'
            } 
        }
    });
});
