<?php

namespace frontend\tests;

use frontend\tests\FunctionalTester;
use frontend\tests\fixtures\UserFixture;

class LoginCest
{

    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
            ],
        ]);
    }

    public function checkLoginWorking(FunctionalTester $I)
    {
        $I->amOnRoute('user/default/login');

        $formParams = [
            'LoginForm[email]' => '1@got.com', //заполняем форму параметрами
            'LoginForm[password]' => '111111',
        ];

        $I->submitForm('#login-form', $formParams); //отправляем форму

        $I->see('Eddard "Ned" Stark', 'form button[type=submit]'); //проверяем наличие такого слова в таком селекторе
    }

    public function checkLoginWrongPassword(FunctionalTester $I)
    {
        $I->amOnRoute('user/default/login');

        $formParams = [
            'LoginForm[email]' => '1@got.com',
            'LoginForm[password]' => 'wrong',
        ];

        $I->submitForm('#login-form', $formParams);

        $I->seeValidationError('Incorrect email or password');
    }

}
