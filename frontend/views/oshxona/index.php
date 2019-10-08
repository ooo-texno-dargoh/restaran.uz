<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$neworder=new \frontend\models\AsosSlave();
?>

<div class="container bg-white text-white">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No</th>
                <th>Nomi</th>
                <th>Soni</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i=0;
            foreach ($model as $item) :

                $tovar=\frontend\models\STovar::find()->where(['id'=>$item->tovar_id])->one();
                if(Yii::$app->user->identity->int2==$tovar->brend):
                $i++;?>
                <tr <?php
                if ($item->ch2==2)
                    echo 'style="background-color: green; color: white"';
                ?>  data-toggle="modal" data-target="#myModal<?=$item->id?>">
                    <td style="width: 5px"><?=$i?></td>
                    <td><?=$item->tovar_nom?></td>
                    <td><?=$item->kol?></td>
                </tr>

                <div class="modal fade" id="myModal<?=$item->id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog center-block" role="document">
                        <div class="modal-content modal-info">
                            <div class="modal-header">
                                Amalni tasdiqlash
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body modal-spa">
                                <div class="col-md-7 span-1 ">
                                    <h3><?=$item->tovar_nom?></h3>

                                    <div class="price_single">
                                        <span class="reducedfrom "><?=$item->kol.' ta'?></span>

                                        <div class="clearfix"></div>
                                    </div>
                                    <br>
                                    <div style="display: flex; justify-content: center;">
                                        <a href="<?=Yii::$app->urlManager->createUrl(['oshxona/update','id'=>$item->id,'ch2'=>3])?>" class="btn btn-danger">Rad qilish</a>
                                        <a href="<?=Yii::$app->urlManager->createUrl(['oshxona/update','id'=>$item->id,'ch2'=>4])?>" class="btn btn-primary" style="margin-left: 5px; margin-right: 5px">Tayyor</a>

                                        <a href="<?=Yii::$app->urlManager->createUrl(['oshxona/update','id'=>$item->id,'ch2'=>2])?>" class="btn btn-success">Qabul qilish</a>

                                    </div>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            endforeach; ?>
            </tbody>
        </table>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="neworder">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Yangi Buyurtma</h4>
            </div>
            <div class="modal-body">
                <p id="namanew"></p>
                <p id="kolnew"></p>
                <p id="idnew"></p>
            </div>
            <div class="modal-footer">
                <div style="display: flex; justify-content: center;">
                    <button id="radqilish" class="btn btn-danger">Rad qilish</button>
                    <button id="tayyor" class="btn btn-primary" style="margin-left: 5px; margin-right: 5px">Tayyor</button>
                    <button  id="qabulqilingan" class="btn btn-success">Qabul qilish</button>

                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<audio id="audio" src="<?=Yii::$app->homeUrl?>alert.mp3"></audio>
<?php
$url=Yii::$app->request->baseUrl.'/oshxona';
$script= <<<JS
var audio = document.getElementById('audio');
function playMusic(){
    var promise = document.querySelector('audio').play();
    if (promise !== undefined) {
        if (!(navigator.vibrate === undefined)) {
                    navigator.vibrate(1000);
                    }
        promise.then(_ => {
            // Autoplay started!
        }).catch(error => {
            audio.play();
        });
    }
}
        var viewed=false;
        var mydata;
function check() {
       if(viewed){
           window.location.href='$url'+'/update?id='+mydata['id']+'&ch2=0';
       }
       var cnt='$i';
       $.ajax({
           type: "POST",
           url: '$url',
           data: {
               cnt: cnt
           },
           success: function(data) {
               
               if (data == 'false'){
                   console.log('pustoy');
               }
               else {
                    var newOrder = JSON.parse(data);
                    console.log(newOrder['tovar_nom']);
                    document.getElementById('namanew').innerHTML= newOrder['tovar_nom'];
                    document.getElementById('kolnew').innerHTML=newOrder['kol'];
                     
                    $('#neworder').modal('show');
                    viewed=true;
                    mydata=newOrder;
                    playMusic();
               }
           },
           failure: function(errMsg) {
               alert(errMsg);
           }
       });
   }
   $(document).ready(function() { 
      
       setInterval(check,3000); });

    window.navigator = window.navigator || {};

  

   $('#radqilish').bind('click', function(e){
     e.preventDefault();
        window.location.href='$url'+'/update?id='+mydata['id']+'&ch2=3';     
});
   $('#tayyor').bind('click', function(e){
     e.preventDefault();
        window.location.href='$url'+'/update?id='+mydata['id']+'&ch2=4';     
});
   $('#qabulqilingan').bind('click', function(e){
     e.preventDefault();
        window.location.href='$url'+'/update?id='+mydata['id']+'&ch2=2';     
});

JS;
$this->registerJs($script);
?>

