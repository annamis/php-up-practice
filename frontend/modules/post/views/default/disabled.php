<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\web\JqueryAsset;
?>

<div class="container full">
    <div class="page-posts no-padding">
        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">
                <div class="blog-posts blog-posts-large">
                    <div class="row">

                        <!-- feed item -->
                        <article class="post col-sm-12 col-xs-12">                                            
                            <p>This post was deleted, or it never existed at all.</p>
                        </article>
                        <!-- feed item -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(), //зависимость подключаемого js-файла от библиотеки Jquery
]);

