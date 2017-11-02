<?php

namespace frontend\tests;

use Yii;
use frontend\tests\fixtures\UserFixture;

class UserTest extends \Codeception\Test\Unit
{

    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester; //в свойстве tester находится объект класса UnitTester, который помогает проводить тестирование

    //описывает фикстуры, которые должны быть в этом классе

    public function _fixtures()
    {
        return ['users' => UserFixture::className()];
    }

    public function _before()
    {
        Yii::$app->setComponents([
            'redis' => [
                'class' => 'yii\redis\Connection',
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
            ],
        ]);
    }

    public function testGetNicknameOnNicknameEmpty()
    {

        $user = $this->tester->grabFixture('users', 'user1'); //получаем из фикстуры по ключу юзерс юзера1
        expect($user->getNickname())->equals(1); //ожидаем, что значение, которое вернет этот метод будет равно 1
    }

    public function testGetNicknameOnNicknameNotEmpty()
    {

        $user = $this->tester->grabFixture('users', 'user2'); //получаем из фикстуры по ключу юзерс юзера1
        expect($user->getNickname())->equals('catelyn'); //ожидаем, что значение, которое вернет этот метод будет равно 1
    }

    public function testGetPostCount()
    {

        $user = $this->tester->grabFixture('users', 'user2'); //получаем из фикстуры по ключу юзерс юзера1
        expect($user->countPosts())->equals(2);
    }

    public function testFollowUser()
    {
        $user1 = $this->tester->grabFixture('users', 'user1');
        $user3 = $this->tester->grabFixture('users', 'user3');
        $user3->followUser($user1);

        //проверяем есть ли в множестве такой ключ
        $this->tester->seeRedisKeyContains('user:1:followers', 3);
        $this->tester->seeRedisKeyContains('user:3:subscriptions', 1);

        $this->tester->sendCommandToRedis('del', 'user:1:followers');
        $this->tester->sendCommandToRedis('del', 'user:3:subscriptions');
    }

    public function testUnfollowUser()
    {
        $this->tester->sendCommandToRedis('sadd', 'user:1:followers', '3');
        $this->tester->sendCommandToRedis('sadd', 'user:3:subscriptions', '1');

        //проверяем есть ли в множестве такой ключ
        $this->tester->seeRedisKeyContains('user:1:followers', 3);
        $this->tester->seeRedisKeyContains('user:3:subscriptions', 1);

        $user1 = $this->tester->grabFixture('users', 'user1');
        $user3 = $this->tester->grabFixture('users', 'user3');
        $user3->unfollowUser($user1);
        
        //проверяем удалился ли из множества такой ключ /или не существует такого значения у множества
        $this->tester->dontSeeInRedis('user:1:followers', 3);
        $this->tester->dontSeeInRedis('user:3:subscriptions', 1);
    }

}
