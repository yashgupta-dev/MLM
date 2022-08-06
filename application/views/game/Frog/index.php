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
        <link rel="stylesheet" href="<?=base_url()?>api/css/reset.css" type="text/css">
        <link rel="stylesheet" href="<?=base_url()?>api/css/main.css" type="text/css">
        <link rel="stylesheet" href="<?=base_url()?>api/css/orientation_utils.css" type="text/css">
        <link rel="stylesheet" href="<?=base_url()?>api/css/ios_fullscreen.css" type="text/css">
        <link rel='shortcut icon' type='image/x-icon' href='<?=base_url()?>api/favicon.ico' />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
	<meta name="msapplication-tap-highlight" content="no"/>

        <script type="text/javascript" src="<?=base_url()?>api/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>api/js/createjs.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>api/js/howler.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>api/js/hammer.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>api/js/main.js"></script>
        
        
    </head>
    <body ondragstart="return false;" ondrop="return false;" >
	<div style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%"></div>
	
	<input type="hidden" value="<?php echo $this->session->userdata('gameuser');?>" name="usermname">
          <script>

            $(document).ready(function(){
                     var oMain = new CMain({
                                            
                                            lives: 5, //Number of starting lives
                                            crossing_time: 60000, // Maximum time available to get a frog into a cove (in ms)
                                            score_in_nest: 100,   //Points earned when a frog is in the cove
                                            score_with_fly: 500,  //Points earned when a frog eat a fly in the cove
                                            score_death: -200,    //Points losed when frog deads
            
                                            frog_speed : 100,     //Set the frog jump speed(in ms)
                                            
                                            sink_turtle_occurrency: 4,  //Number of standard turtle-group before a sink turtle-group
                                            num_level_increase_sink: 5, //Number of level before reduce by 1, the "sink_turtle_occurrency" parameter
                                            
                                            time_fly_to_spawn: 7000, //Time to spawn a fly (in ms)
                                            time_fly_to_disappear: 3000, //Time before a fly disappear (in ms)
                                                                                        
                                            //Time-Speed of a street lane (in ms). Each value will be randomly assigned to a lane every level
                                            street_lane_timespeed: [12000, 10000, 9000, 7000, 6000],
                                            //Decrease time-speed of a street lane (in ms) every level. Each value will be add to "street_lane_speed" parameters vector, respectively
                                            street_timespeed_decrease_per_level: [-150, -150, -250, -150, -250],
                                            //Occurrence of a street lane cars spawn (in ms). Each value will be randomly assigned to a lane every level, according with "street_lane_speed" parameters vector
                                            street_lane_occurrence: [3500, 3700, 3900, 4100, 4300],
                                            //Decrease occurrence of a street lane cars spawn(in ms). Each value will be subtract to "street_lane_occurrence" parameters vector, respectively
                                            street_occurrence_decrease_per_level: [-100, -100, -100, -100, -100],
                                            
                                            //Time-Speed of a water lane (in ms). From bottom to top:
                                            water_lane_timespeed: [ 13000,      //FIRST LANE  
                                                                    10000,      //SECOND LANE
                                                                    8000,       //THIRD LANE
                                                                    10000,      //FOURTH LANE
                                                                    10000 ],    //FIFTH LANE
                                            //Decrease time-speed of a water lane (in ms) every level. Each value will be add to "water_lane_speed" parameters vector, respectively
                                            water_timespeed_decrease_per_level: [   -150,    //FIRST LANE
                                                                                    -150,    //SECOND LANE
                                                                                    -250,    //THIRD LANE
                                                                                    -150,    //FOURTH LANE
                                                                                    -150 ],  //FIFTH LANE
                                            //Occurrence of a water lane trunk or turtle spawn (in ms). From bottom to top:
                                            water_lane_occurrence: [    5000,   //FIRST LANE
                                                                        4000,   //SECOND LANE
                                                                        3000,   //THIRD LANE
                                                                        3500,   //FOURTH LANE
                                                                        5000 ], //FIFTH LANE
                                            //Increase occurrence of a water lane trunk or turtle spawn (in ms). Each value will be add to "water_lane_occurrence" parameters vector, respectively
                                            water_occurrence_increase_per_level: [  50,   //FIRST LANE
                                                                                    50,   //SECOND LANE
                                                                                    50,   //THIRD LANE
                                                                                    50,   //FOURTH LANE
                                                                                    50 ],  //FIFTH LANE
                                            
                                            fullscreen:true,         //SET THIS TO FALSE IF YOU DON'T WANT TO SHOW FULLSCREEN BUTTON
                                            check_orientation:true,  //SET TO FALSE IF YOU DON'T WANT TO SHOW ORIENTATION ALERT ON MOBILE DEVICES
                                           });
                                           
                                           
                    $(oMain).on("start_session", function(evt) {
                            if(getParamValue('ctl-arcade') === "true"){
                                parent.__ctlArcadeStartSession();
                            }
                            //...ADD YOUR CODE HERE EVENTUALLY
                    });
                     
                    $(oMain).on("end_session", function(evt) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeEndSession();
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });

                    $(oMain).on("save_score", function(evt,iScore) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeSaveScore({score:iScore});
                           }
                            let gameid = <?=$_GET['id']?>    
                            let tokenid = <?=$_GET['q']?>    
                            let id = $("input[name='usermname']").val();
                            var score = iScore;
                            $.ajax({
                                url:'<?=base_url()?>api/game_data_save',
                                method:"post",
                                cache:false,
                                dataType:'json',
                                data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',score:score,id:id,gameid:gameid,tokenid:tokenid},
                                success:function(data)
                                {
                                    if(data.url){
                                        window.location.href=data.url;
                                        console.log(data.url);
                                    }
                                },
                                    error: function (jqXhr, textStatus, errorMessage) { // error callback 
                                        
                                        console.log(errorMessage);
                                    } 
                             });   
                            
                    });

                    $(oMain).on("start_level", function(evt, iLevel) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeStartLevel({level:iLevel});
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });

                    $(oMain).on("end_level", function(evt,iLevel) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeEndLevel({level:iLevel});
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });

                    $(oMain).on("show_interlevel_ad", function(evt) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeShowInterlevelAD();
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });
                    
                    $(oMain).on("share_event", function(evt, iScore) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeShareEvent({   img: TEXT_SHARE_IMAGE,
                                                                title: TEXT_SHARE_TITLE,
                                                                msg: TEXT_SHARE_MSG1 + iScore + TEXT_SHARE_MSG2,
                                                                msg_share: TEXT_SHARE_SHARE1 + iScore + TEXT_SHARE_SHARE1});
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });
                     
                     if(isIOS()){ 
                        setTimeout(function(){sizeHandler();},200); 
                    }else{
                        sizeHandler(); 
                    }
                    
           });

        </script>

        <canvas id="canvas" class='ani_hack' width="1080" height="1136"> </canvas>
        <div data-orientation="portrait" class="orientation-msg-container">
        	<p class="orientation-msg-text">Please rotate your device</p>
        </div>
        <div id="block_game" style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%; display:none"></div>
        
    </body>
</html>
