<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{

    public $sourcePath = '@bower/font-awesome'; //папка с источниками, в которой нужно искать файлы
    public $css = [
        'css/font-awesome.css',
    ];

}
