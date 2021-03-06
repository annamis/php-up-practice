<?php

namespace common\components;

use yii\web\UploadedFile;

/**
 * File storage interface
 *
 * @author anna
 */
interface StorageInterface
{

    public function saveUploadedFile(UploadedFile $file);

    public function getFile(string $filename);
}
