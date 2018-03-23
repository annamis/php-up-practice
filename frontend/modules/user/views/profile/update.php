<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\User */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>
<?php echo $form->field($model, 'username'); ?>
<?php echo $form->field($model, 'nickname'); ?>
<?php echo $form->field($model, 'email'); ?>
<?php echo $form->field($model, 'about'); ?>
<?php
echo Html::submitButton('Save', [
    'class' => 'btn btn-primary',
]);
?>
<?php ActiveForm::end(); ?>
<br>
<?=
Html::a('Delete', ['/user/profile/disable', 'nickname' => $model->getNickname()], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Are you sure you want to delete your profile?',
        'method' => 'post',
    ],
]);
?>
<br><br>
