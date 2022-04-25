$(document).ready(function () {
    $('#search').on('click', function () {
        var element = document.getElementById("suggestion");
        element.classList.remove('hidden')
    })
    window.onclick = function(event) {
        if (event.target != document.getElementById("search") && event.target != document.getElementById("suggestion")) {
            document.getElementById("suggestion").classList.add('hidden')
        }
    }

    $('#search').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            var searchText = $('#search').val().trim();
            if (searchText[0] == '[' && searchText[String(searchText).length - 1] == ']') {
                window.location.href = 'http://localhost:8000/questions/view/' + searchText + '/newest#tab-top';
            } else {
                if (searchText) {
                    window.location.href = 'http://localhost:8000/questions/view/' + searchText + '/relevance#tab-top';
                }
            }
        }
    });
});
