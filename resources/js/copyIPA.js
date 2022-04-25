$(document).ready(function () {
    $('#copy-ipa').on('click', function () {
        var copyIPA = document.getElementById("resultview");
        /* Select the text field */
        copyIPA.select();
        copyIPA.setSelectionRange(0, 99999); /* For mobile devices */
        /* Copy the text inside the text field */
        document.execCommand("copy");
    })
});
