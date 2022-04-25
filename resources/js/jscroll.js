$(document).ready(function(){
    // ẩn thanh phân trang của Laravel
    $('ul.pagination').hide();
    $(function () {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<a href="javascript:void(0)" class="load-questions"><i class="icon-refresh"></i>Loading...</a>',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function () {
                // xóa thanh phân trang ra khỏi html mỗi khi load xong nội dung
                $('ul.pagination').remove();
            }
        });
    });
});
