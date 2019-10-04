<?php

namespace frontend\controllers;

use frontend\models\AsosSlave;
use frontend\models\STovar;

class OshxonaController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=AsosSlave::find()->where(['<=','ch2',2])->all();

        if(\Yii::$app->request->isAjax){
            $data=\Yii::$app->request->post();
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            exit();
        }

        return $this->render('index',[
            'model'=>$model,
        ]);
    }

}
