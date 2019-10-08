<?php

namespace frontend\controllers;

use frontend\models\AsosSlave;
use frontend\models\STovar;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OshxonaController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        $this->layout='@frontend/views/layouts/client';
        $model=AsosSlave::find()->where(['<=','ch2',2])->all();

        if(\Yii::$app->request->isAjax){
//            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data=\Yii::$app->request->post('cnt');
            return Json::encode($this->havenewdata($data));
        }
        return $this->render('index',[
            'model'=>$model,
        ]);
    }
    public function havenewdata($cnt)
    {
        $model = AsosSlave::find()->where(['<=', 'ch2', 2])->all();
        $i = 0;
        foreach ($model as $item) {

            $tovar = \frontend\models\STovar::find()->where(['id' => $item->tovar_id])->one();
            if (Yii::$app->user->identity->int2 == $tovar->brend) {
                $i++;
                if($cnt<$i)
                {
                    $cnt++;
                    return ArrayHelper::toArray($item);
                }
            }
        }
        return $i != $cnt;
    }
    public function actionUpdate($id,$ch2){
        $model = AsosSlave::find()->where(['id'=>$id])->one();
        $model->ch2=$ch2;
        $model->save();
        return $this->redirect(['/oshxona']);
    }

}
