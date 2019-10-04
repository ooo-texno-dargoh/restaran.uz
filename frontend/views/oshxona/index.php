<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
?>
<div class="container">
<!--    <p>-->
<!--        --><?php //echo '<pre>';
//    print_r($model);
//    echo '</pre>';
//    ?>
<!--    </p>-->
    <div>
        <table class="table">
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
                <tr <?php if($item->ch2==1)echo 'class="success"';?> data-toggle="modal" data-target="#myModal<?=$item->id?>">
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
                                        <button class="btn btn-danger">Rad qilish</button>
                                        <button class="btn btn-primary" style="margin-left: 5px; margin-right: 5px">Tayyor</button>
                                        <?php if($item->ch2==1):?>
                                        <button class="btn btn-success">Qabul qilish</button>
                                        <?php endif;?>
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
</div>
<script>
   function check() {
       $.ajax({
           type: "POST",
           url: '<?php echo Yii::$app->request->baseUrl. '/oshxona' ?>',
           data:{
             have:'bla'
           },
           // The key needs to match your method's input parameter (case-sensitive).
           contentType: "application/json; charset=utf-8",
           dataType: "json",
           success: function(data){
               console.log(data);
           },
           failure: function(errMsg) {
               alert(errMsg);
           }
       });
   }
   setInterval(check,2000);

</script>
