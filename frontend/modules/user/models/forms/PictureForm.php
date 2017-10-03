<?php

namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;

class PictureForm extends Model 
{
    public $picture;
    
    public function rules()
    {
        return [
            [['picture'], 'file',
                'extensions' => ['jpg'],
                'checkExtensionByMimeType' => true,
                'maxSize' => Yii::$app->params['maxFileSize'], 
                'tooBig' => 'Limit is 2MB'
            ],
        ];
    }
    
    public function save()
    {
        return 1;
    }
}
