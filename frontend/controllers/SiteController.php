<?php
namespace frontend\controllers;
use frontend\models\SMobil;
use frontend\models\Asos;
use frontend\models\SPapka;
use frontend\models\AsosSlave;
use frontend\models\Pl;
use frontend\models\SClient;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

   public function actionTovaradd()
    {
        $kns=Yii::$app->request->post('kns');
        $sot=Yii::$app->request->post('sot');
        $kol=Yii::$app->request->post('kol');
        $esk=Yii::$app->request->post('idsup');
        $tek = AsosSlave::find()->where(['id'=>$esk])->andWhere(['del_flag'=>1])->one();
        if($tek['kol_ost']<$kol || $kol==0){

            return 0;
        }
        AsosSlave::updateAll(['kol_ost'=> $tek['kol_ost'] - $kol],['id'=>$esk]);

        $sotish=$kol*$sot;
        $mod = new AsosSlave();
        $mod->tovar_nom = Yii::$app->request->post('nom');
        $mod->tovar_id = Yii::$app->request->post('tid');
        $mod->asos_id =Yii::$app->request->post('asosid');
        $mod->kol = Yii::$app->request->post('kol');
        $mod->kol_in = 0;
        $mod->summa = $sotish;
        $mod->summa_in = 0;
        $mod->summa_all = $sotish;
        $mod->sotish = $sot;
        $mod->sotish_in = 0;
        $mod->kol_ost = Yii::$app->request->post('idsup');$mod->kol_in_ost = Yii::$app->request->post('idsup');
        $mod->user_id=Yii::$app->getUser()->id;
        $mod->save();
        
        return   Yii::$app->request->post('asosid');
    }
    public function actionTovaredit()
    {
        $kol_new=Yii::$app->request->post('kol_new');
        $kol_old=Yii::$app->request->post('kol_old');
        $s_id_old=Yii::$app->request->post('s_id_old');
        $s_id_new=Yii::$app->request->post('s_id_new');
        $slave_old = AsosSlave::find()->where(['id' => $s_id_old]) -> one();
        $kol_ost1=$slave_old['kol_ost'] + $kol_old - $kol_new;
        if($slave_old['kol_ost'] + $kol_old-$kol_new < 1 || $kol_new == 0 ){
            return $kol_ost1;}

        AsosSlave::updateAll(['kol_ost'=> $kol_ost1],['id'=>$s_id_old]);
        AsosSlave::updateAll(['kol'=> $kol_new,'summa'=> 'sotish*'.$kol_new,'summa_all'=> 'sotish*'.$kol_new],['id'=>$s_id_new]);
        return $kol_ost1;
    }
public function actionTxfdf()
    {
        $kol_new=Yii::$app->request->post('kol_new');
        $kol_old=Yii::$app->request->post('kol_old');
        $s_id_old=Yii::$app->request->post('s_id_old');
        $s_id_new=Yii::$app->request->post('s_id_new');
        $slave_old = AsosSlave::find()->where(['id' => $s_id_old]) -> one();
        $kol_ost1=$slave_old['kol_ost'] + $kol_old - $kol_new;
        if($slave_old['kol_ost'] + $kol_old-$kol_new < 1 || $kol_new == 0 ){
            return $kol_ost1;}

        AsosSlave::updateAll(['kol_ost'=> $kol_ost1],['id'=>$s_id_old]);
        AsosSlave::updateAll(['kol'=> $kol_new,'summa'=> 'sotish*'.$kol_new,'summa_all'=> 'sotish*'.$kol_new],['id'=>$s_id_new]);
        return $kol_ost1;
    }    
    public function actionIndex()
    {
        $query = AsosSlave::find()->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom_sh,s_tovar.nom, s_tovar.id as idt, s_tovar.kol_in as tkol_in')
            ->from('asos_slave,asos,s_tovar')
            ->where('asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 || asos_slave.kol_in_ost>0 ) and s_tovar.id = asos_slave.tovar_id and asos.client_id = ' .Yii::$app->getUser()->identity->client_id )->orderBy('s_tovar.kat,s_tovar.papka,s_tovar.nom')
            ->all();



        $date = date("Y-m-d");
        $s = Asos::find()->where(['sana'=>$date])->andWhere(['print_flag'=>0])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->andWhere(['mobil'=>Yii::$app->request->post('stolid')])->andWhere(['user_id'=>Yii::$app->getUser()->id])->one();
        $asos = Asos::find()->where(['sana'=>$date])->andWhere(['print_flag'=>0])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->andWhere(['user_id'=>Yii::$app->getUser()->id])->andWhere(['mobil'=>Yii::$app->request->post('stolid')])->one();

        if(Yii::$app->request->post('stolid')){


            return $this->render('index',[

            ]);

        }
        else {
            $this->redirect(['/site/kabina']);
        }
        if($asos['mobil']==Yii::$app->request->post('stolid')){

            $sotil = AsosSlave::find()->where(['asos_id'=>$asos['id']])->andWhere(['del_flag'=>1])->andWhere(['user_id'=>Yii::$app->getUser()->id])->all();


            return $this->render('index',[

                'sotil'=>$sotil,
                'query'=>$query
            ]);
        }
        else{
            $summa = Yii::$app->request->post('summa');
            $model = new Asos();
            $model->client_id = Yii::$app->getUser()->identity->client_id;
            $model->user_id = Yii::$app->getUser()->id;
            $model->xodim_id = Yii::$app->getUser()->id;
            $model->sana = $date;
            $model->diler_id= "1";
            $model->tur_oper = 2;
            $model->mobil = Yii::$app->request->post('stolid');
            $model->summa= $summa;
            $model->save();
            $this->redirect(['/site/kabina']);
            $sotil = AsosSlave::find()->where(['asos_id'=>$s['id']])->andWhere(['del_flag'=>1])->andWhere(['user_id'=>Yii::$app->getUser()->id])->all();

            return $this->render('index',[

                'sotil'=>$sotil,
                'query'=>$query
            ]);
        }

    }

    public function actionKabina(){

    Asos::deleteAll(['id'=>Yii::$app->request->post('delqil')]);
    
    $model = \frontend\models\SMobil::find()->orderBy('qavat,nn')->all();

        return $this->render('kabina',[

            'model'=>$model
        ]);
    }

    public function actionCart(){

        $session = Yii::$app->session;

        if(isset($_POST['pid'])){
            $pid =$_POST['pid'];
            $i=0;
//            if($_SESSION['cart2']){
//                foreach ($_SESSION['cart2'] as $item){
//                    $i++;
//                }
//                $_SESSION['cart2'][$i]=$pid;
//            }else{
            $_SESSION['new'][]=$pid;
//            }

        }

        $carts = AsosSlave::find()->where(['tovar_id'=>$session['new']])->andWhere(['del_flag'=>1])->all();
        return $this->render('cart',[
            'carts'=>$carts,
        ]);
    }

    public function actionAdd()
    {


        $asos = Yii::$app->request->post('asos_id');
        $idin = Yii::$app->request->post('id');
        $idsup = Yii::$app->request->post('idsup');
        $asosiy = Yii::$app->request->post('asosiy');
        $ichki = Yii::$app->request->post('ichki');
        $nom = Yii::$app->request->post('nom');
        $data = Yii::$app->request->post('data');


        $sotil = AsosSlave::find()->where(['asos_id'=>$asos])->andWhere(['del_flag'=>1])->andWhere(['user_id'=>Yii::$app->getUser()->id])->orderBy(['id'=>SORT_DESC])->all();

        if($data){
        $query = AsosSlave::find()->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom as nom , s_tovar.id as idt, s_tovar.kol_in as tkol_in')
            ->from('asos_slave,asos,s_tovar')
            ->where('asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper = 1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 or asos_slave.kol_in_ost>0) and s_tovar.id = asos_slave.tovar_id and s_tovar.id = '.Yii::$app->request->post('data').' and asos.client_id = '.Yii::$app->getUser()->identity->client_id)
            ->all();

        if(Yii::$app->request->post('ichki') || Yii::$app->request->post('asosiy')) {


            $sotil = AsosSlave::find()->where(['asos_id'=>$asos])->andWhere(['del_flag'=>1])->andWhere(['user_id'=>Yii::$app->getUser()->id])->all();
            return $this->render('view',[

                'asos'=>$asos,
                'sotil'=>$sotil,
                'query'=>$query
            ]);
        }

            return $this->render('view',[

                'asos'=>$asos,
                'sotil'=>$sotil,
                'query'=>$query
            ]);
        }
        else{
            $query = AsosSlave::find()->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom as nom , s_tovar.id as idt, s_tovar.kol_in as tkol_in')
                ->from('asos_slave,asos,s_tovar')
                ->where('asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 || asos_slave.kol_in_ost>0 ) and s_tovar.id = asos_slave.tovar_id and asos.client_id = ' .Yii::$app->getUser()->identity->client_id )
                ->limit(1000)->all();

            if(Yii::$app->request->post('ichki') || Yii::$app->request->post('asosiy')) {

                $mod = new AsosSlave();
                $mod->tovar_nom = $nom;
                $mod->tovar_id = $idin;
                $mod->asos_id = $asos;
                $mod->kol_in_ost = $idsup;
                $mod->kol_ost = $idsup;
                $mod->kol_in = $ichki;
                $mod->kol = $asosiy;
                $mod->user_id=Yii::$app->getUser()->id;

                $mod->save();

                $sotil = AsosSlave::find()->where(['asos_id'=>$asos])->andWhere(['del_flag'=>1])->andWhere(['user_id'=>Yii::$app->getUser()->id])->all();
                return $this->render('view',[

                    'asos'=>$asos,
                    'sotil'=>$sotil,
                    'query'=>$query
                ]);
            }

            return $this->render('view',[

                'asos'=>$asos,
                'sotil'=>$sotil,
                'query'=>$query
            ]);
        }
        }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
     public function actionUpdate(){

           
            $asos =Yii::$app->request->post('assosiy');
     		$ichki =Yii::$app->request->post('ichki');
     		 $sotil = AsosSlave::find()->where(['id'=>Yii::$app->request->post('idup')])->andWhere(['del_flag'=>1])->one();

     		 $ap= AsosSlave::find()->where(['id'=>$sotil['kol_ost']])->andWhere(['del_flag'=>1])->one();

     		 AsosSlave::updateAll(['kol_ost'=>$ap['kol_ost']-$sotil['kol']+$asos,'kol_in_ost'=>$ap['kol_in_ost']-$sotil['kol_in']+$ichki],['id'=>$sotil['kol_ost']]);

     	 return $this->render('update',[

                'sotil'=>$sotil,
            ]);
		}




    public function actionKarzina(){
         
        $kat=Yii::$app->request->post('kat');


        $date = date("Y-m-d");

        $stol = SMobil::find()->where(['id'=>Yii::$app->request->post('stolid')])->one();

        $asos = Asos::find()->where(['sana'=>$date])->andWhere(['print_flag'=>0])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->andWhere(['user_id'=>Yii::$app->getUser()->id])->andWhere(['mobil'=>Yii::$app->request->post('stolid')])->one();

 		if($asos['mobil']!=Yii::$app->request->post('stolid')){
            $model = new Asos();
            $model->client_id = Yii::$app->getUser()->identity->client_id;
            $model->user_id = Yii::$app->getUser()->id;
            $model->xodim_id = Yii::$app->getUser()->id;
            $model->sana = $date;
            $model->diler_id= "1";
            $model->tur_oper = 2;
            $model->mobil = Yii::$app->request->post('stolid');
            $model->xizmat_foiz = $stol['foiz'];
            $model->save();

        }

        $tkolinost=Yii::$app->request->post('tkolinost');

        $ichki = Yii::$app->request->post('ichkison');

        $kololdin = Yii::$app->request->post('kolinjoriy');

        $ostatid    = Yii::$app->request->post('idsup');

        $koljo    = Yii::$app->request->post('koljoriy');

        $tayyor = Yii::$app->request->post('tayyor');

        AsosSlave::updateAll(['zakaz_see'=>2],['asos_id'=>$tayyor,'zakaz_see'=>1]);

        if($koljo){
        if($koljo < 1){

            $query = AsosSlave::find()->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom as nom , s_tovar.id as idt, s_tovar.kol_in as tkol_in')
                ->from('asos_slave,asos,s_tovar')
                ->where('asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 || asos_slave.kol_in_ost>0 ) and s_tovar.id = asos_slave.tovar_id and asos.client_id = ' .Yii::$app->getUser()->identity->client_id )
                ->limit(1000)->all();
            Yii::$app->session->setFlash('success', "Taqsimlab bulmaydi asosiy 1 dan kam!");
            return $this->render('view',[

                'query'=>$query
            ]);
        }
        }
        if($kololdin < $ichki and $koljo > 0){

            $sleve= AsosSlave::find()->where(['id'=>$ostatid])->andWhere(['del_flag'=>1])->one();
            $kol =$sleve['kol_ost']-1;
            $kolin =$sleve['kol_in_ost']+1000;

            AsosSlave::updateAll(['kol_in_ost'=>$kolin,'kol_ost'=>$kol],['id'=>$ostatid]);

            $query = AsosSlave::find()->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom as nom , s_tovar.id as idt, s_tovar.kol_in as tkol_in')
                ->from('asos_slave,asos,s_tovar')
                ->where('asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 || asos_slave.kol_in_ost>0 ) and s_tovar.id = asos_slave.tovar_id and asos.client_id = ' .Yii::$app->getUser()->identity->client_id )
                ->orderBy('s_tovar.kat,s_tovar.nom')->all();
            Yii::$app->session->setFlash('success', "Ichki son taqsimlandi!");
            return $this->render('view',[

                'query'=>$query
            ]);
        }
         $query = AsosSlave::find()->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom as nom , s_tovar.id as idt, s_tovar.kol_in as tkol_in')
                ->from('asos_slave,asos,s_tovar')
                ->where('asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 or asos_slave.kol_in_ost>0 ) and s_tovar.id = asos_slave.tovar_id ')
                ->orderBy('s_tovar.kat,s_tovar.papka,s_tovar.nom')->all();
 		$tkolin=Yii::$app->request->post('tkolin');


 		$ichki = Yii::$app->request->post('ichkison');

        $ap= AsosSlave::find()->where(['id'=>$tkolinost])->andWhere(['del_flag'=>1])->one();

        $ides = Yii::$app->request->post('ides');
        $koles = Yii::$app->request->post('koles');
        $kolines = Yii::$app->request->post('kolines');
        $idjo = Yii::$app->request->post('idjo');
        $koljo = Yii::$app->request->post('kol');
        $kolinjo = Yii::$app->request->post('kolin');

        $eskikolkolin =AsosSlave::find()->where(['id'=>$ides])->andWhere(['del_flag'=>1])->one();
        $anc=$eskikolkolin['kol_ost']+$koles-$koljo;
        $ancin=$eskikolkolin['kol_in_ost']+$kolines-$kolinjo;

        AsosSlave::updateAll(['kol_ost'=>$anc],['id'=>$ides]);
        AsosSlave::updateAll(['kol'=>$koljo],['id'=>$idjo]);

        if($kolines > 0){

            AsosSlave::updateAll(['kol_in_ost'=>$ancin],['id'=>$ides]);
            AsosSlave::updateAll(['kol_in'=>$kolinjo],['id'=>$idjo]);
        }

        if($kat){

           $query = AsosSlave::find()->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom as nom,s_tovar.nom_sh as nom_sh, s_tovar.id as idt, s_tovar.kol_in as tkol_in,s_tovar.kat')
                ->from('asos_slave,asos,s_tovar')
                ->where('s_tovar.kat = '.$kat.' and asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 or asos_slave.kol_in_ost>0) and s_tovar.id = asos_slave.tovar_id')->orderBy('s_tovar.papka,s_tovar.nom')->all();
                   
       


       }
       else {

        if(Yii::$app->getUser()->identity->ustama==1) 	
        	{$ustama='(asos_slave.sotish+s_tovar.ustama)';}	else {$ustama='asos_slave.sotish';}
             $query = AsosSlave::find()->select('s_papka.nom as papkanom,s_tovar.papka as papka,polka,asos_slave.id as ids,'.$ustama.'  as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom_sh as nom_sh,s_tovar.nom as nom, s_tovar.id as idt, s_tovar.kol_in as tkol_in,s_tovar.kat')
                ->from('asos_slave,asos,s_tovar')
                ->leftJoin('s_papka','s_papka.id=s_tovar.papka')
                ->where('s_tovar.kat > 0 and asos.id = asos_slave.asos_id and asos_slave.del_flag = 1 and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5) and (asos_slave.kol_ost>0 or asos_slave.kol_in_ost>0) and s_tovar.id = asos_slave.tovar_id ')->orderBy('s_tovar.kat,s_tovar.papka,s_tovar.nom')->all();


		}
         $asosiy = Yii::$app->request->post('asosiyson');

         $ichki = Yii::$app->request->post('ichkison');

        if(Yii::$app->request->post('tkol')){

	    if(Yii::$app->request->post('tkol') < Yii::$app->request->post('asosiyson')){
            Yii::$app->session->setFlash('success', "Tovar soni noto'g'ri ");
            return $this->render('view',['query'=>$query,]);
    		}

    	}

//        $sotil = AsosSlave::find()->where(['asos_id'=>$s['id']])->andWhere(['del_flag'=>1])->andWhere(['user_id'=>Yii::$app->getUser()->id])->all();

    		if(Yii::$app->request->post('tkol') and Yii::$app->request->post('tkolin')){
    			if(Yii::$app->request->post('tkol')< Yii::$app->request->post('asosiyson') and Yii::$app->request->post('tkolin')< $ichki){

					$this->redirect('/site/add');

    			}

    		}

        $idjo = Yii::$app->request->post('iddel');
        $koldel = Yii::$app->request->post('koldel');
        $kolindel = Yii::$app->request->post('kolindel');
        $kolinjoriy = Yii::$app->request->post('kolinjoriy');
        $koljoriy   = Yii::$app->request->post('koljoriy');
        $ostatid    = Yii::$app->request->post('idsup');
        $summa = Yii::$app->request->post('summa');
        $kols = Yii::$app->request->post('kols');
        $idupd=Yii::$app->request->post('andnac');
        $idav=Yii::$app->request->post('idav');

        $ap= AsosSlave::find()->where(['id'=>$idav])->andWhere(['del_flag'=>1])->one();


        AsosSlave::updateAll(['kol_ost'=>$ap['kol_ost']+$koldel,'kol_in_ost'=>$ap['kol_in_ost']+$kolindel],['id'=>$idav]);
        Asos::updateAll(['summa'=>$summa,'kol'=>$kols],['id'=>$idupd]);
        $date = date("Y-m-d");

        $idup = Yii::$app->request->post('idup');
        $kol = Yii::$app->request->post('kol');
        $kolin = Yii::$app->request->post('kolin');

        if(Yii::$app->request->post('andnac')) {
            $andnac = Yii::$app->request->post('andnac');
            AsosSlave::updateAll(['zakaz'=>date('Y-m-d H:i:i'),'ch2'=>0],['asos_id'=>$andnac,'zakaz'=> Null]);
        }
        if(Yii::$app->request->post('andchek')) {
            $andnac = Yii::$app->request->post('andchek');
            $itog = AsosSlave::find()->where(['del_flag'=>1])->andwhere(['asos_id'=>$andnac])->all();
            $i = 0;$n = 0; foreach ($itog as $asitem){ $i = $i + 1; $n = $n + $asitem['summa_all'];}
            $ap = SClient::find()->where(['id'=>Yii::$app->getUser()->identity->client_id])->one();
            $asos = Asos::find()->where(['id'=>$andnac])->one();
            $xizmat = $n * $asos['xizmat_foiz'] / 100;
            $sum_naqd = $n + $xizmat;
            $sum_naqd_ch = round($sum_naqd,0);
            $cheg_n = round($sum_naqd-$sum_naqd_ch,-2);
            $summa_ch = $sum_naqd_ch;
            date_default_timezone_set('Asia/Tashkent');
            
            SClient::updateAll(['garaj' => $ap['garaj']+1], ['id'=>Yii::$app->getUser()->identity->client_id]);
            Asos::updateAll(['changedate' => date("Y-m-d H:i:s"),'sum_plast_ch' => 0,'sum_epos_ch' => 0,'sum_naqd_ch' => 0,'sum_epos' => 0,'sum_plastik' => 0,'sum_naqd' => $sum_naqd,'sum_naqd_ch' => $sum_naqd_ch,'cheg_n' => $cheg_n,'summa_ch' => $sum_naqd_ch,'xizmat' => $xizmat,'diler_id' => $ap['garaj']+1,'print_flag' => 1,'kol' => $i,'summa' => $n], ['id' => $andnac]);
            AsosSlave::updateAll(['zakaz_see'=>3],['asos_id'=>$andnac,'zakaz_see'=>1]);
            $this->redirect(['/site/index']);
        }
        if(Yii::$app->request->post('andfaktur')) {
            $garaj = Yii::$app->request->post('garaj');
            $andnac = Yii::$app->request->post('andfaktur');
            SClient::updateAll(['garaj' => $garaj], ['id'=>Yii::$app->getUser()->identity->client_id]);
            Asos::updateAll(['diler_id' => 1,'print_flag' => 3], ['id' => $andnac,'user_id'=>Yii::$app->getUser()->id]);
            $this->redirect(['/site/index']);
        }
        AsosSlave::deleteAll(['id'=>$idjo]);
        AsosSlave::updateAll(['kol'=>$kol,'kol_in'=>$kolin],['id'=>$idup]);
        $date = date("Y-m-d");

        $id = Yii::$app->request->post('tavarid');

        $asosid = Yii::$app->request->post('asosid');

        $asosiy = Yii::$app->request->post('asosiyson');

        $ichki = Yii::$app->request->post('ichkison');

        $kololdin = Yii::$app->request->post('kolinjoriy');


        $kolost = $koljoriy-$asosiy;

        $kolinost = $kolinjoriy-$ichki;

        AsosSlave::updateAll(['kol_ost'=>$kolost,'kol_in_ost'=>$kolinost],['id'=>$ostatid]);

        $nom = Yii::$app->request->post('tavarnomi');
        if(Yii::$app->request->post('asosiyson') || Yii::$app->request->post('ichkison')) {

            $model = new AsosSlave();
            $model->tovar_nom = $nom;
            $model->tovar_id = $id;
            $model->asos_id = $asosid;
            $model->kol_ost = $ostatid;
            $model->kol_in_ost = -1;


            if($asosiy==null){
                $model->kol = 0;
            }
            else{
                $model->kol = $asosiy;
            }
            if($ichki==null){
                $model->kol_in = 0;
            }
            else{
                $model->kol_in = $ichki;
            }
            if($asosiy==null){
                $model->summa = 0;
            }
            else{
                $model->summa = $asosiy*Yii::$app->request->post('sot');
            }
            if($ichki==null){
                $model->summa_in = 0;
            }
            else{
                $model->summa_in = Yii::$app->request->post('sotin')*$ichki;
            }
            if($asosiy==null){
                $model->sotish = 0;
            }
            else{
                $model->sotish = Yii::$app->request->post('sot');
            }
            if($ichki==null){
                $model->sotish_in = 0;
            }
            else{
                $model->sotish_in = Yii::$app->request->post('sotin');
            }

            $model->summa_all = Yii::$app->request->post('sotin')*$ichki + Yii::$app->request->post('sot')*$asosiy;
            $model->user_id=Yii::$app->getUser()->id;
            $model->save();

            $s = Asos::find()->where(['sana'=>$date])->andWhere(['del_flag'=>1])->andWhere(['print_flag'=>0])->andWhere(['mobil'=>Yii::$app->request->post('stolid')])->andWhere(['user_id'=>Yii::$app->getUser()->id])->andWhere(['>','diler_id',0])->one();

            $sotil = AsosSlave::find()->where(['asos_id'=>$s['id']])->andWhere(['del_flag'=>1])->andWhere(['user_id'=>Yii::$app->getUser()->id])->orderBy(['id'=>SORT_DESC])->all();

            return $this->render('index',[

                'sotil'=>$sotil,
                'asosid'=>$asosid,
                'ostatid'=>$ostatid,
                'query'=>$query
            ]);
        }
        $s = Asos::find()->where(['sana'=>$date])->andWhere(['del_flag'=>1])->andWhere(['print_flag'=>0])->andWhere(['mobil'=>Yii::$app->request->post('stolid')])->andWhere(['user_id'=>Yii::$app->getUser()->id])->andWhere(['>','diler_id',0])->one();
        //$sotil = AsosSlave::find()->andWhere(['del_flag'=>1])->where(['asos_id'=>$s['id']])->andWhere(['user_id'=>Yii::$app->getUser()->id])->orderBy(['id'=>SORT_DESC])->all();
        $sotil = AsosSlave::find()->andWhere(['del_flag'=>1])->where(['asos_id'=>$s['id']])->andWhere(['user_id'=>Yii::$app->getUser()->id])->orderBy(['id'=>SORT_DESC])->all();


        return $this->render('index_papka',[

            'sotil'=>$sotil,
            'asosid'=>$asosid,
            'ostatid'=> $ostatid,
            'query'=>$query
        ]);
    }
    public function actionOmbor()
    {

        /*        $query = (new Query())
            ->select('z.nom as znom,d.nom as dnom,s.kol_ost*s.sena as q,s.kol_in_ost*s.sena_in as q_in, s.kol_ost*s.sena+s.kol_in_ost*s.sena_in as q_all,s.kol*s.sotish+s.kol_in*s.sotish_in as sotiladi,s.*,t.nom as s_tovar,z.nom as s_zavod,d.nom as s_diler')
            ->from('asos a,asos_slave s,s_tovar t,s_zavod z,s_diler d')
            ->where(['or', ['a.tur_oper' => 1], ['a.tur_oper' => 4], ['a.tur_oper' => 5]])
            ->andWhere(['or', ['>', 's.kol_ost', 0], ['>', 's.kol_in_ost', 0]])
            ->andWhere(['d.id' => 'a.diler_id'])
            ->andWhere(['z.id' => 't.zavod_id'])
            ->andWhere(['t.id' => 's.tovar_id'])
            ->andWhere(['a.id' => 's.asos_id'])
            ->andWhere(['s.del_flag' => 1])
            ->orderBy('t.nom', 's.srok');*/

//            'sql' => $query->createCommand()->sql,

        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT s.kol_ost as kolOost, z.nom as znom,d.nom as dnom,s.kol_ost*s.sena as q,s.kol_in_ost*s.sena_in as q_in, s.kol_ost*s.sena+s.kol_in_ost*s.sena_in as q_all,s.kol*s.sotish+s.kol_in*s.sotish_in as sotiladi,s.*,t.nom as s_tovar,z.nom as s_zavod,d.nom as s_diler
            FROM asos a,asos_slave s,s_tovar t,s_zavod z,s_diler d 
            where (a.tur_oper=1 or a.tur_oper=4 or a.tur_oper=5)
            
            and d.id=a.diler_id and z.id=t.zavod_id and t.id=s.tovar_id
            and a.id=s.asos_id and s.del_flag=1
            
            order by t.nom,s.srok',
            'sort' => [
                'attributes' => [],
            ],
            'pagination' => false,
        ]);
        $models = $dataProvider->getModels();

        $dataProvider2 = new SqlDataProvider([
            'sql' => 'SELECT nom FROM s_diler',
            'pagination' => false,
        ]);
        $models2 = $dataProvider2->getModels();

        return $this->render('ombor', [
            'dataProvider' => $dataProvider,
            'models' => $models,
            'models2' => $models2,
        ]);
    }

    /**
     * Displays haridor qarz haqida.
     */
    public function actionClientQarz()
    {
        /*
        $haridor = Yii::$app->request->post('haridor');
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$haridor) $haridor='';
        if(!$date1) $date1 = date('Y-m-01');
        if($date2) $date2 = date('Y-m-d');
//        echo Yii::$app->request->post('date1').' ----- '.Yii::$app->request->post('date2').' '.Yii::$app->request->post('haridor');
        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT sum(qarz_summa) as summa,sum(sum_epos_ch) as epos FROM asos WHERE del_flag=1 AND tur_oper=2 AND h_id="'.$haridor.'" AND sana<"'.$date1.'"',
            'sort' => [
                'attributes' => [],
            ],
            'pagination' => false,
        ]);

        $models = $dataProvider->getModels();


        $dataProvider2 = new SqlDataProvider([
            'sql' => 'SELECT id, nom FROM s_haridor WHERE id>1',
            'sort' => [
                'attributes' => [],
            ],
            'pagination' => false,
        ]);
        $models2 = ArrayHelper::map($dataProvider2->getModels(), 'id', 'nom');


        return $this->render('client-qarz', [
            'dataProvider' => $dataProvider,
            'models' => $models,
            'models2' => $models2,
        ]);
        */

        $haridor = Yii::$app->request->post('haridor');
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$haridor) $haridor='';
        if(!$date1) $date1 = date('Y-m-01');
        if(!$date2) $date2 = date('Y-m-d');

        $b = Yii::$app->db->createCommand('SELECT IFNULL(SUM(qarz_summa),0)+IFNULL(SUM(sum_epos_ch),0) as summa FROM asos WHERE del_flag=1 AND tur_oper=2 AND h_id="'.$haridor.'" AND sana<"'.$date1.'"')->queryOne();
        $b2=0;
        $e = Yii::$app->db->createCommand('SELECT sena_pl, vid FROM pl WHERE del_flag=1 AND h_id="'.$haridor.'" AND d_pl<"'.$date1.'"')->queryAll();
        foreach ($e as $item) {
            if($item['vid']==7 || $item['vid']==17 || $item['vid']==20) $b2+=$item['sena_pl'];
            else if($item['vid']==8 || $item['vid']==18) $b['summa']+=$item['sena_pl'];
        }
        $danq = $b['summa'] - $b2;

        $b = Yii::$app->db->createCommand('SELECT IFNULL(SUM(qarz_summa),0)+IFNULL(SUM(sum_epos_ch),0) as summa FROM asos WHERE del_flag=1 AND tur_oper=2 AND h_id="'.$haridor.'" AND sana BETWEEN "'.$date1.'" AND "'.$date2.'"')->queryOne();
        $b2=0;
        $e = Yii::$app->db->createCommand('SELECT sena_pl, vid FROM pl WHERE del_flag=1 AND h_id="'.$haridor.'" AND d_pl BETWEEN "'.$date1.'" AND "'.$date2.'" ')->queryAll();
        foreach ($e as $item) {
            if($item['vid']==7 || $item['vid']==17 || $item['vid']==20) $b2+=$item['sena_pl'];
            else if($item['vid']==8 || $item['vid']==18) $b['summa']+=$item['sena_pl'];
        }
        $gachaq = $b['summa'] - $b2;

        $haridorlar = ArrayHelper::map(Yii::$app->db->createCommand('SELECT id, nom FROM s_haridor WHERE id>1')->queryAll(), 'id', 'nom');

        $query = Asos::find()->where('del_flag=1')->andWhere('tur_oper=2')->andWhere(['h_id' => $haridor])->andWhere(['between', 'sana', $date1, $date2]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query2 = Pl::find()->where(['between', 'd_pl', $date1, $date2])->andWhere('del_flag=1')->andWhere(['h_id' => $haridor]);
        $dataProvider2 = new ActiveDataProvider([
            'query' => $query2,
        ]);

        return $this->render('client-qarz', [
            'danq' => $danq,
            'chiqim' => (int)$b['summa'],
            'kirim' => $b2,
            'gachaq' => $gachaq,
            'haridorlar' => $haridorlar,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
        ]);
    }



    public function actionTayyor(){

        $model = AsosSlave::find()->where(['zakaz_see'=>1])->andWhere(['del_flag'=>1])->groupBy(['asos_id'])->all();

        return $this->render('tayyor',[

            'model'=>$model
        ]);


    }
    public function actionNokladnoy()
    {
        $date = date("Y-m-d");
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$date1) $date1 = date('Y-m-d');
        if(!$date2) $date2 = date('Y-m-d');

        $s = Asos::find()->where(['sana'=>$date1])->andWhere(['del_flag'=>1])->andwhere(['>','print_flag',0])->andWhere(['user_id'=>Yii::$app->getUser()->id])->andWhere(['>','diler_id',0])->all();

        return $this->render('nok',[

            's'=>$s
        ]);
    }
    public function actionNokboss()
    {
        $date = date("Y-m-d");
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$date1) $date1 = date('Y-m-d');
        if(!$date2) $date2 = date('Y-m-d');

        //$query = Asos::find()->where('del_flag=1')->andWhere('tur_oper=2')->andWhere(['h_id' => $haridor])->andWhere(['between', 'sana', $date1, $date2]);
        $s = Asos::find()->where(['>','print_flag',0])->andwhere(['sana'=>$date1])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->orderBy('user_id')->all();
        return $this->render('nokboss',[
            's'=>$s
        ]);
    }  
    public function actionNokkassir()
    {
        $date = date("Y-m-d");
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$date1) $date1 = date('Y-m-d');
        if(!$date2) $date2 = date('Y-m-d');

        //$query = Asos::find()->where('del_flag=1')->andWhere('tur_oper=2')->andWhere(['h_id' => $haridor])->andWhere(['between', 'sana', $date1, $date2]);
        $s = Asos::find()->where(['>','print_flag',0])->andwhere(['sana'=>$date1])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->orderBy('user_id')->all();
        return $this->render('nokkassir',[
            's'=>$s
        ]);
    }  
    public function actionTovarkun()
    {
        $date = date("Y-m-d");
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$date1) $date1 = date('Y-m-d');
        if(!$date2) $date2 = date('Y-m-d');

        $s = AsosSlave::find()->select('asos_slave.tovar_nom,sum(asos_slave.kol) as kol,sum(asos_slave.summa_all) as summa_all')
            ->from('asos_slave,asos,s_tovar')
            ->where('diler_id > 0 and asos_slave.del_flag = 1 and asos.tur_oper=2 and  asos.id=asos_slave.asos_id and asos_slave.tovar_id=s_tovar.id and asos.sana = "'.$date1.'"')->groupBy('asos_slave.tovar_id')->orderBy('s_tovar.kat,s_tovar.papka,s_tovar.nom')->all();
        //$s = Asos::find()->where(['sana'=>$date1])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->orderBy('user_id')->all();
        return $this->render('tovarkun',[
            's'=>$s
        ]);
    }
    public function actionTovarkunuser()
    {
        $date = date("Y-m-d");
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$date1) $date1 = date('Y-m-d');
        if(!$date2) $date2 = date('Y-m-d');

        $s = AsosSlave::find()->select('asos_slave.tovar_nom,sum(asos_slave.kol) as kol,sum(asos_slave.summa_all) as summa_all')
            ->from('asos_slave,asos,s_tovar')
            ->where('diler_id > 0 and asos_slave.del_flag = 1 and asos.tur_oper=2 and  asos.id=asos_slave.asos_id and s_tovar.id=asos_slave.tovar_id and asos.sana = "'.$date1.'" and asos_slave.user_id = '.Yii::$app->getUser()->id)->groupBy('asos_slave.tovar_id')->orderBy('s_tovar.kat,s_tovar.papka,s_tovar.nom')->all();
        //$s = Asos::find()->where(['sana'=>$date1])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->orderBy('user_id')->all();
        return $this->render('tovarkunuser',[
            's'=>$s
        ]);
    }     
    public function actionOfisiant()
    {
        $date = date("Y-m-d");
        $date1 = Yii::$app->request->post('date1');
        $date2 = Yii::$app->request->post('date2');
        if(!$date1) $date1 = date('Y-m-d');
        if(!$date2) $date2 = date('Y-m-d');
        
        $s = Asos::find()->select('user_id,count(id) as kol,sum(summa_ch) as summa_ch')->where(['sana'=>$date1])->andWhere(['del_flag'=>1])->andWhere(['>','diler_id',0])->groupBy('user_id')->all();
        return $this->render('ofisiant',[
            's'=>$s
        ]);
    }    
    public function actionKun()
    {
        $date = date("Y-m-d");
        $asos_id =Yii::$app->request->post('asosid');
        $a = Asos::find()->where(['id'=>$asos_id])->one();

        $s = \frontend\models\AsosSlave::find()->andWhere(['del_flag'=>1])->andWhere(['asos_id'=>$asos_id])->all();
        return $this->render('kun',[
            'a'=>$a,
            's'=>$s
        ]);
    }
    public function actionKunboss()
    {
        $date = date("Y-m-d");
        $asos_id =Yii::$app->request->post('asosid');
        $a = Asos::find()->where(['id'=>$asos_id])->one();

        $s = \frontend\models\AsosSlave::find()->andWhere(['del_flag'=>1])->andWhere(['asos_id'=>$asos_id])->all();
        return $this->render('kun',[
            'a'=>$a,
            's'=>$s
        ]);
    }

    public function actionView($id){


        $query = AsosSlave::find()->where(['asos_slave.del_flag'=>1])
            ->select('asos_slave.id as ids,asos_slave.sotish as sot,asos_slave.sotish_in as sotin,asos_slave.kol_ost as kns,asos_slave.kol_in_ost as kolin,s_tovar.nom as nom , s_tovar.id as idt')
            ->innerJoin('asos','asos.id = asos_slave.asos_id and (asos.tur_oper=1 or asos.tur_oper=4 or asos.tur_oper=5)')
            ->innerJoin('s_tovar','s_tovar.id = asos_slave.tovar_id and (s_tovar.shtrix='.$id.' or s_tovar.shtrix1='.$id.' or s_tovar.shtrix2='.$id.' or s_tovar.shtrix_full='.$id.' )')
            ->all();

        return $this->render('view',[

            'query'=>$query

        ]);
    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
			$model->login();
			if(Yii::$app->user->identity->int1==1)
			    return $this->redirect(['/oshxona']);
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
