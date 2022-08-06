<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$site = $this->db->select('title,logo,favicon,meta_author,meta_desc,meta_keyword,loader,default_user')
        ->from('site_setting')
        ->get()->row();
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="author" content="<?=$site->meta_author?>">
  <meta name="description" content="<?=$site->meta_desc?>">
  <meta name="keywords" content="<?=$site->meta_desc?>">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="<?= $this->security->get_csrf_token_name()?>" content="<?= $this->security->get_csrf_hash(); ?>">
  <title><?=$site->title?></title>
	<script src="<?=base_url()?>adminassets/bubble/libraries/p5.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/bubble/libraries/p5.dom.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/bubble/libraries/p5.sound.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/bubble/game.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/bubble/scoreboard.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/bubble/ship.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/bubble/bubble.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/bubble/sketch.js" type="text/javascript"></script>
	<script src="<?=base_url()?>adminassets/js/jquery-3.4.1.js"></script>
	<style>
		body {
			padding: 0;
			margin: 0;
		}
		canvas {
			vertical-align: top;
			
		}
	</style>
</head>


<body style="background-color:blue;">

<input type="hidden" value="<?php echo $this->session->userdata('gameuser');?>" name="usermname">
<script>

function csrfdata() {
    let token = $("meta[name='csrf_test_name']").attr("content");
    return token;
}

setInterval(function(){
    gameFunctionScore();
    
},2000);
function gameFunctionScore()
{
    let gameid = <?=$_GET['id']?>    
    let tokenid = <?=$_GET['q']?>    
    let id = $("input[name='usermname']").val();
    let token = csrfdata();
    let score = game.scoreBoard.score;
     $.ajax({
        url:'<?=base_url()?>api/game_data_save',
        method:"post",
        cache:false,
        dataType:'json',
        data:{'csrf_test_name':token,score:score,id:id,gameid:gameid,tokenid:tokenid},
        success:function(data)
        {
            if(data.url){
                window.location.href=data.url;
            }
        },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                
                console.log(errorMessage);
            } 
     });   
}


</script>
</body>

</html>
