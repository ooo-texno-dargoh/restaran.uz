/**
 *
 * Created by Lord on 06.10.2019.
 */
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

        });
    }
}
var viewed=false;
var mydata;
function check() {

    playMusic();
    if(viewed){
        window.location.href=window.location+'/update?id='+mydata['id']+'&ch2=0';
    }
    var cnt='$i';
    $.ajax({
        type: "POST",
        url: window.location,
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
    window.location.href=window.location+'/update?id='+mydata['id']+'&ch2=3';
});
$('#tayyor').bind('click', function(e){
    e.preventDefault();
    window.location.href=window.location+'/update?id='+mydata['id']+'&ch2=4';
});
$('#qabulqilingan').bind('click', function(e){
    e.preventDefault();
    window.location.href=window.location+'/update?id='+mydata['id']+'&ch2=2';
});

