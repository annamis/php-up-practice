<?php

namespace frontend\components;

use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{

    public $supportedLanguages = ['en-US', 'ru-RU'];

    /**
     * Load current language based on diven cookie if any
     * @param yii\base\Application $app
     */
    public function bootstrap($app) //метод будет запущен перед запуском приложения. на вход принимает экземпляр текущего приложения
    {
        $cookieLanguage = $app->request->cookies['language']; //получаем язык из куки
        if (isset($cookieLanguage) && in_array($cookieLanguage, $this->supportedLanguages)) {//проверяем соответствует ли он тому языку, который поддерживается приложением
            $app->language = $app->request->cookies['language']; //устанавливаем язык приложения
        }
    }

}

// Вопросы:
// Как это - на вход принимает экземпляр текущего приложения?
// Зачем 2 раза проверять язык: при сохранении языка в куки и перед считыванием языка