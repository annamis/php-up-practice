$(document).ready(function () {
    $('a.button-comment').click(function () {
        $('textarea').focus();
    });
    $('a.update-comment').click(function () { //отлавливает нажатие кнопки btn-like
        alert('ok');
        return false;
    });
});