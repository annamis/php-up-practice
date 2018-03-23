<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

$this->title = Html::encode($user->username);
?>

<div class="container full">
    <div class="page-posts no-padding">
        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">
                <div class="blog-posts blog-posts-large">
                    <div class="row">

                        <!-- profile -->
                        <article class="profile col-sm-12 col-xs-12">                                            
                            <div class="profile-title">
                                <img src="<?php echo $user->getPicture(); ?>" id="profile-picture" class="author-image" />
                                <div class="author-name"><?php echo Html::encode($user->username); ?></div>
                            </div>
                            <div class="profile-description">
                                <p>This page was deleted. Information is unavaliable.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
