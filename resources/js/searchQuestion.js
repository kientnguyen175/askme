$(document).ready(function () {
    const items = document.querySelectorAll('.current_page_item');
    items.forEach((item) => {
        item.classList.remove("current_page_item");
    });
       
    $('#question-search').on('keyup', function (e) {
        e.preventDefault()
        if ((e.key === 'Enter' || e.keyCode === 13)) {
            var searchText = $('#question-search').val().trim();
            if (searchText[0] == '[' && searchText[String(searchText).length - 1] == ']') {
                window.location.href = 'http://localhost:8000/questions/view/' + searchText + '/newest#tab-top';
            } else {
                if (searchText) {
                    window.location.href = 'http://localhost:8000/questions/view/' + searchText + '/relevance#tab-top';
                } else {
                    window.location.href = 'http://localhost:8000/questions/view#tab-top'
                }
            }
        }
    });
});
