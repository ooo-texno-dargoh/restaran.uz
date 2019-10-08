<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        
        backend\assets\AppAsset::register($this);
    } else {
        frontend\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?php
        if (!Yii::$app->user->isGuest){
        echo $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ); } ?>

        <?php
        if (!Yii::$app->user->isGuest){
        echo $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        );}
        ?>

        <?php
        if (!Yii::$app->user->isGuest){
        echo  $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ); }
        else{

            echo "
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h1 style=\"text-align: center; color: white\">PAGE NOTE FOUND (#404)</h1>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
";}
        ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
