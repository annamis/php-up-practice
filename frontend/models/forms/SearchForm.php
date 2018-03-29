<?php

namespace frontend\models\forms;

use yii\base\Model;
use frontend\models\User;

class SearchForm extends Model
{

    public $keyword;

    public function rules()
    {
        return [
            ['keyword', 'trim'],
            ['keyword', 'required'],
            ['keyword', 'string', 'min' => 1],
        ];
    }

    public function search()
    {
        if ($this->validate()) {
            $model = new User();
            return $model->searchUser($this->keyword);
        }
    }

}
