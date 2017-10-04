$(document).ready(function () {
    $('a.button-like').click(function () { //отлавливает нажатие кнопки btn-like
        var button = $(this);
        var params = {
            'id': $(this).attr('data-id') //читаем идентификатор поста как атрибут data-id
        };
        $.post('/post/default/like', params, function (data) { //отправка ajax запроса на action like с параметром id, полученным из атрибута data-id у кнопки (можно увидить в network->headers)
            if (data.success) { //проверяем содержимое переменной success, если она true, то
                button.hide(); //кнопка лайк прячется
                button.siblings('.button-unlike').show(); //появляется кнопка анлайк
                button.siblings('.likes-count').html(data.likesCount);//обновляем содержимое блока likes-count во вьюшке и записываем туда данные из ответа
            }
        });
        return false;
    });

    $('a.button-unlike').click(function () {
        var button = $(this);
        var params = {
            'id': $(this).attr('data-id')
        };
        $.post('/post/default/unlike', params, function (data) {
            if (data.success) {
                button.hide(); 
                button.siblings('.button-like').show();
                button.siblings('.likes-count').html(data.likesCount);
            }
        });
        return false;
    });
});
