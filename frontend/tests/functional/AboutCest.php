<?php

namespace frontend\tests;

use frontend\tests\FunctionalTester;

class AboutCest
{

    public function checkAbout(FunctionalTester $I) //на вход получает объект класса FunctionalTester, который умеет выполнять проверки
    {
        $I->amOnRoute('site/about'); //открыть страницу
        $I->see('About Images project', 'h1'); //проверить наличие элемента с текстом
    }

}
