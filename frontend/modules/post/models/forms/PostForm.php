<?php

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Post;

class PostForm extends Model
{

    const MAX_DESCRIPTION_LENGHT = 1000;

    public $picture; // поля, которые загружает пользователь
    public $description;
    
    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => false, //пропускать поле, если оно пустое
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize()],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
        ];
    }

    /**
     * @param User $user
     */
    //используется для передачи данных из контроллера в модель (в примере в методе save() 
    //мы не должны в модели напрямую получать данные из сессии, поэтому в actionCreate() в модель PostForm мы передаем 
    //текущего аутентифицированного пользователя Yii::$app->user->identity, после чего он поступит на вход в конструктор;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            //в текущем объекте $this хранится вся информация о картинке 
            $post = new Post();
            //берем значения из текущей формы и назначаем их экземляру post
            $post->description = $this->description;
            $post->created_at = time();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            return $post->save(false);
        }
    }

    /**
     * Maximum size of the uploaded file
     * @return integer
     */
    private function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }

}
