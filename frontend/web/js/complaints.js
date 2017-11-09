$(document).ready(function () {
    $('a.button-complain').click(function () { 
        var button = $(this); //сохраняем элемент по которому было нажатие в переменную button
        var preloader = $(this).find('i.icon-preloader'); //в этой же ссылке находим иконку, которую сохраняем в переменную preloader
        var params = {
            'id': $(this).attr('data-id') //читаем идентификатор поста как атрибут data-id
        };
        preloader.show(); // показываем прелоадер в случае если есть клик, до тех пор, пока не придет ответ от сервера
        $.post('/post/default/complain', params, function(data) { //запрос приходит на экшн /post/default/complain
            preloader.hide(); //в случае ответа от сервера прячем прелоадер
            button.addClass('disabled'); //делаем кнопку неактивной
            button.html(data.text); // и добавляем в нее текст в зависимости от того как на ответит сервер
        });
        return false;
    });
});
//итого: скрипт по нажатию на кнопку "пожаловаться" умеет отпралять запрос на экшн /post/default/complain с идентификатором поста