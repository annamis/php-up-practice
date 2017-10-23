<?php
/* @var $this yii\web\View */
/* @var $currentUser frontend\models\User */
/* @var $feedItems[] frontend\models\Feed */

use yii\web\JqueryAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = 'Feed';
?>
<div class="site-index">

    <?php if ($feedItems): ?>
        <?php foreach ($feedItems as $feedItem): ?>
            <?php /* @var $feedItem Feed */ ?>

                <div class="col-md-12">
                    <img src="<?php echo $feedItem->author_picture; ?>" width="30" height="30" />
                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($feedItem->author_nickname) ? $feedItem->author_nickname : $feedItem->author_id]); ?>">
                        <?php echo Html::encode($feedItem->author_name); ?>
                    </a>
                </div>

                <img src="<?php echo Yii::$app->storage->getFile($feedItem->post_filename); ?>" />

                <!--Like Block-->
                <div class="col-md-12">
                    Likes: <span class="likes-count"><?php echo $feedItem->countLikes(); ?></span>
                    <a href="#" class="button-like <?php echo ($currentUser && $currentUser->likesPost($feedItem->post_id)) ? "display-none" : ""; ?>" data-id="<?php echo $feedItem->post_id; ?>">
                        <span class="glyphicon glyphicon-heart-empty gi-2x grey"></span>
                    </a>
                    <a href="#" class="button-unlike <?php echo ($currentUser && $currentUser->likesPost($feedItem->post_id)) ? "" : "display-none"; ?>" data-id="<?php echo $feedItem->post_id; ?>">
                        <span class="glyphicon glyphicon-heart gi-2x red"></span>
                    </a>
                </div> 
                <!--End Like Block-->

                <div class="col-md-12">
                    <?php echo HtmlPurifier::process($feedItem->post_description); ?>
                </div>                

                <div class="col-md-12">
                    <?php echo Yii::$app->formatter->asDatetime($feedItem->post_created_at); ?>
                </div>

                <div class="col-md-12"><hr/></div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="col-md-12">
                Nobody posted yet!
            </div>
        <?php endif; ?>

    </div>

    <?php
    $this->registerJsFile('@web/js/likes.js', [
        'depends' => JqueryAsset::className(), //зависимость подключаемого js-файла от библиотеки Jquery
    ]);
    