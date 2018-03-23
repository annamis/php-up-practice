<?php

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Post;
use Intervention\Image\ImageManager;
use frontend\models\events\PostCreatedEvent;

class PostForm extends Model
{

    const MAX_DESCRIPTION_LENGHT = 1000;
    const EVENT_POST_CREATED = "post_created";

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
            ['status', 'default', 'value' => Post::STATUS_ACTIVE],
            ['status', 'in', 'range' => [Post::STATUS_ACTIVE, Post::STATUS_DELETED, Post::STATUS_DISABLED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'description' => Yii::t('postForm', 'Description'),
            'picture' => Yii::t('postForm', 'Picture'),
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
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']); // навешиваем обработчик на событие AFTER_VALIDATE,которе я определяю как метод текущего класса resizePicture()
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeeds']);
    }

    /**
     * Resize image if needed
     */
    public function resizePicture()
    {
        $width = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($this->picture->tempName);     //    /tmp/11ro51

        $image->resize($width, $height, function ($constraint) { //3 агрумент - анонимная функция с настройками
            $constraint->aspectRatio(); //сохранение пропорций изображения
            $constraint->upsize(); //если размер изображения меньше, чем указано в params, то не изменять размер
        })->save();        //    /tmp/11ro51
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) { // вызов события EVENT_AFTER_VALIDATE автоматически
            //в текущем объекте $this хранится вся информация о картинке 
            $post = new Post();
            //берем значения из текущей формы и назначаем их экземляру post
            $post->description = $this->description;
            $post->created_at = time();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            // в случае успешного сохранения поста и если событие EVENT_POST_CREATED брошено
            if ($post->save(false)) {
                //создаем DTO
                $event = new PostCreatedEvent;
                //прикрепляем данные,необходимые для создания ленты новостей
                $event->user = $this->user;
                $event->post = $post;
                $this->trigger(self::EVENT_POST_CREATED, $event);
                return true;
            }
            return false;
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
