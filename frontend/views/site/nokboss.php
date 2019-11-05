<?php
use yii\bootstrap\ActiveForm;
use frontend\models\Asos;
use frontend\models\AsosSlave;
use frontend\models\User;
use kartik\date\DatePicker;
$this->title="";
$date = date('Y-m-d');
?>
<div class="client-qarz">
    <div class="row">
        <?php ActiveForm::begin()?>
        <div class="col-md-3 client-qarz__date">
            <?php
            echo DatePicker::widget([
                'name' => 'date1',
                'value' => Yii::$app->request->post('date1')?Yii::$app->request->post('date1'):date('Y-m-d'),
                'options' => ['placeholder' => 'Sanani tanlang...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>

        <div class="col-md-1 client-qarz__button">
            <button type="submit" class="btn btn-primary">Qidirish</button>
        </div>
        <?php ActiveForm::end()?>
    </div>


</div>

<style>
    .info-qarz{
        margin: 20px 0;
    }
    .info-qarz__item{
        display: inline-block;
    }
    .info-qarz__item label {
        text-align: right;
        width: 130px;
        margin-right: 12px;
    }
    .info-qarz__item span {
        display: inline-block;
        width: 120px;
        border: 1px solid #ccc;
        padding: 1px 4px;
        border-radius: 4px;
    }
    @media only screen and (max-width: 1280px) {
        .info-qarz__item{
            width: 49%;
        }
    }
    @media only screen and (max-width: 1086px) {
        .client-qarz__date, .client-qarz__select2, .client-qarz__button{
            padding-top: 2px;
            padding-bottom:2px;
        }
        .client-qarz__button{
            text-align: right;
        }
    }
    @media only screen and (max-width: 564px) {
        .info-qarz__item label {
            text-align: left;
            margin-right: 0;
        }
    }
</style>

<div  style="text-align: center;margin: 2px; background-color: rgba(43,106,246,0.77);">
<table class="table table-bordered" id="users">
<tr><th>Стол</th><th>Чек №</th><th>Сана</th><th>Қат-ор</th><th>Жами</th><th>%</th></tr>
<?php
$i = 0;$summa = 0;$summaJami = 0;
foreach ($s as $item):
?>
<tr style="background-color:#B6E8F8;" id="<?=$item['id'];?>" class="chekqator-<?=$item['id'];?>">
  <td>
    <?php \yii\widgets\ActiveForm::begin(['action'=>['/site/kun']])?>
        <input type="text" value="<?=$item->id?>" name="asosid" hidden>
        <input type="text" value="<?=$item->diler_id?>" name="dilerid" hidden>
        <button class="btn btn-info form-control"  style="color: white">
           <?php
           $i++;$summa = $summa + $item['summa'];
           //$summaJami = $summaJami + $item['summa_ch'];
           $nom = \frontend\models\SMobil::find()->where(['id'=>$item->mobil])->one();

           $user = user::find()->where(['id'=>$item->user_id])->one();?>

           <?=$nom['nom']?></button><?=$user['username']?>
           <?php
           echo "<img id = 'img".$item['id']."' src=\"/images/down.png\" border=\"0\" style=\"cursor:pointer\" onclick=\"do_ajax_fnc(this,$item[id],$item[id],'divlk$item[id]')\"/>";
           ?>
  </td>
  <td>
    <input type="text" value="<?=$nom->nom?>" name="stol" hidden>
     <input type="text" value="<?=$item->summa_ch?>" name="summa_ch" hidden>
    <?php \yii\widgets\ActiveForm::end()?>
    <?=$item['diler_id']?>
  </td>
  <td>
    <?=$item['changedate']?>
  </td>
	<?php
  	$ss = AsosSlave::find()->where(['del_flag'=>1])->where('asos_id='.$item['id'])->all();
	$kol=0;$summa_all=0;
	foreach ($ss as $tem) {
		$kol=$kol+$tem['kol'];$summa_all=$summa_all+$tem['summa_all'];
	}
	$summa_all = $summa_all	+ $summa_all*$item['xizmat_foiz']/100;
    $summa_all = round($summa_all,-2);
	$summaJami = $summaJami + $summa_all;
  ?>
  <td><?=$kol?></td>
  <td><?=$summa_all?></td>
  <td><?=$item['xizmat_foiz']?></td>
</tr>
<tr><td colspan=11>
<div id="divlk<?=$item[id]?>" style="visibility:hidden;display:none;background-color:#B6E8F8 ;">
<table class="mb-0 table table-hover">
	<tr>
        <td><a href="Naqd">Naqd</a></td><td><?=$item['sum_naqd_ch']?></td>
        <td><input  type="text" name="hn" value=""></td>
    </tr>
    <tr>
        <td>Plastik</td><td><?=$item['sum_plast_ch']?></td>
        <td><input type="text" name="hp" value=""></td>
    </tr>
    <tr>
        <td>Bank</td><td><?=$item['sum_epos_ch']?></td>
        <td><input type="text" name="he" value=""></td>
    </tr>
    <tr>
        <td>
            <button class="btn btn-success">Saqlash</button>
            <input type="text" hidden name="asosid" value="<?=$s['id']?>">
        </td>
    </tr>
</table>
<table class="table table-bordered">
	<tr><td>Nomi</td><td>Soni</td><td>Narxi</td><td>Summasi</td><tr>
<?php foreach ($ss as $so){
?>
	<tr>
	<td><?= $so['tovar_nom']?></td>
	<td><?= $so['kol']?></td>
	<td><?= $so['sotish']?></td>
	<td><?= $so['summa_all']?></td>
	</tr>

<?php }?>
</table>
</div>
</td></tr>

<?php //echo "<tr><td colspan=\"11\"><div id=\"divlk$item[id]\" style=\"visibility:hidden;display:none;background-color:#B6E8F8 ;\"></div></td></tr>"; ?>

<?php endforeach;?>
<th>Жами</th>
<th><?=$i?></th>
<th></th>
<th></th>
<th><?=$summaJami?></th>
</table>
</div>
<script type="text/javascript">
    $('#naqd').on(click, function(e){
        e.preventDefault();alert('jid');
        var data = $(this).serialize();jid=$(this).attr('id');
        
        document.getElementById("divtovar").style.display='none';
        document.getElementById("katalog" + jid).style.display='block';
        $('.lastpapka').val(jid);
    })
	function do_ajax_fnc(img,mid,flag,divname,och){
	  	divel = document.getElementById(divname);
		//if (!divel){return;}
		if (divel.style.visibility == 'visible') {
		    divel.style.visibility = 'hidden';
		    divel.style.display = 'none';
		    img.src = '/images/down.png';
		} else {
		 	divel.style.visibility = 'visible';
			divel.style.display = 'block';
			img.src = '/images/up.png';
		}
		if (och == 'N') {
		    divel.style.visibility = 'hidden';
		    divel.style.display = 'none';
		    img.src = '/images/down.png';
		}
        if (och == 'Y') {
		 	divel.style.visibility = 'visible';
			divel.style.display = 'block';
			img.src = '/images/up.png';
		}
		//if (divel.innerHTML == '') {
			url = "/site/divlistlk";
            doajaxpost(divname,'Iltimos, bir oz kuting ...',mid,flag,url);
		//}
    }
</script>