<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "feed".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $author_id
 * @property string $author_name
 * @property integer $author_nickname
 * @property string $author_picture
 * @property integer $post_id
 * @property string $post_filename
 * @property string $post_description
 * @property integer $post_created_at
 */
class Feed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feed';
    }
}
