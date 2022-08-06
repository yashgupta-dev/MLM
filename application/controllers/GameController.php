<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GameController extends CI_Controller {
  public function __construct()
  {
      parent::__construct();
      
      $this->load->model('register');
      $this->load->helper('sms');
      $this->_loginIF();
      $this->anytimerun();
      date_default_timezone_set('Asia/Kolkata');

  }
  
  function getLastIdofData($id){
    return $this->db->where('userid',$id)->order_by('id','desc')->select('day')->get('daily_p')->row('day');
}


protected function _dailyPWallet($id){
    $level = $this->db->query('SELECT SUM(amount) as total FROM `daily_p` where userid="'.$id.'"')->row('total');    
    $direct = $this->db->where('user_id',$id)
                        ->select('income')
                        ->from('directincome')
                        ->get()->row('income');
                        
    $capping = $this->db->query('SELECT SUM(amount) as total FROM `userbinary` where userid="'.$id.'"')->row('total');         
    
    $app = $this->db->where('user',$id)
             ->select('wallet')
             ->from('gamelogin')
             ->get()->row('wallet');
             
    $winning = $this->_winningAMountCal($id);     
    
    return $direct + $level+ $capping + $winning + $app;
}

protected function _walletExistOrNot($id){
    return $this->db->where('user',$id)
                      ->select('amount')
                      ->from('wallet')
                      ->get()->row();
}

protected function _winningAMountCal($id){
    $data = $this->_countWinningBalance($id);
    $total = 0;
   foreach ($data as $key) {
     $total += $key->amount;
   }
   return $total;
}
protected function _countWinningBalance($id){
      return $this->db->where('user',$id)
                      ->select('amount')
                      ->from('winners')
                      ->get()->result();
}

protected function _fetchSingleLagData($id){
    return $this->db->where('id',$id)
                    ->where('isActive','0')
                    ->where('type','single')
                    ->select('rank,amount,days,direct,team')
                    ->from('single_leg')
                    ->get()->row();    
}

protected function _dailyPayoutFunctionUserExist($id,$date){
    return $this->db->where('userid',$id)
                    ->where('day',$date)    
                    ->select('userid')
                    ->from('daily_p')
                    ->get()->row();
}
protected function _directCheck($id,$time,$tab){
    if(date('Y-m-d h:i:s') <= $time){
        return 'ok';
    }

}
protected function _maketeamdirect($direct){
    
    return 1 + $this->_fetch_perviousTeam($direct);  
}

protected function _checkIUserHaveOrNot($id){
    return $this->db->where('userid',$id) 
                    ->select('userid')
                    ->from('daily_p')
                    ->get()->row();
}

protected function _fetch_perviousTeam($direct){
    return $this->db->where('userid',$direct)
                    ->select('team')
                    ->from('daily_p')
                    ->get()->row('daily_p');
}
protected function _fetchDataForDailyP($id,$get){
    
    return $this->db->where('id',$id)
            ->select('amount,team,rank')
            ->where('type','single')
            ->from('single_leg')
            ->get()->row($get);
}

protected function _timeDateCheck($id,$time,$tab){
    if(date('Y-m-d h:i:s') <= $time){
        $new =$this->db->where('id',$tab)->where('type','single')->select('amount')->from('single_leg')->get()->row('amount');

        $pre = $this->db->where('userid',$id)->select('amount')->from('daily_p')->get()->row('amount');
        $amt = $this->db->where('member_id',$id)->select('package_amt')->from('login')->get()->row('package_amt');

        //return $amt*$new/100 + $pre;
        return $amt*$new/100;
    }
    else{
        return $this->db->where('userid',$id)
                        ->select('amount')
                        ->from('daily_p')
                        ->get()->row('amount');
    }
}

public function anytimerun(){
     $sessid = $this->session->userdata('gameuser');// main sessid;
    $userData = $this->register->dailypayoutWhereUser($sessid);
    if(!empty($userData)){
        $daily_income = [];
        $wallet = [];
        $wallet[] =[
            'user'=>$userData->member_id,
            'amount'=>$this->_dailyPWallet($userData->member_id) 
        ];
        if($userData->daily_payout == '0'){
        $single = $this->_fetchSingleLagData($userData->tableId);
        if(!empty($single)){
            $directCheck = $this->_directCheck($userData->member_id,$userData->timePeriod,$userData->tableId);
            if($directCheck == 'ok'){
                $existDate = $this->getLastIdofData($userData->member_id);
                
                if(!empty($existDate)){
                    $getLastDate = strtotime($existDate);
                    $currentDate = strtotime(date('Y-m-d'));
                    $totalDiff = abs($currentDate - $getLastDate);
                    $years = floor($totalDiff / (365*60*60*24));  
                    $months = floor(($totalDiff - $years * 365*60*60*24) / (30*60*60*24));
                    $days = floor(($totalDiff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24)); 
                    for($i=1;$i<=$days;$i++){
                        $g = date('Y-m-d',strtotime("+$i day",strtotime($existDate)));
                        $daily_income[] = [
                            'userid'=>$userData->member_id,
                            'day'=>$g,
                            'amount'=>$this->_timeDateCheck($userData->member_id,$userData->timePeriod,$userData->tableId),
                            'rank'=>$this->_fetchDataForDailyP($userData->tableId,'rank'),
                            'team'=>$this->_maketeamdirect($userData->member_id),
                            'dm'=>$this->_fetchDataForDailyP($userData->tableId,'amount').'%'        
                        ];
                        
                    }    
                }else{
                    $daily_income[] = [
                        'userid'=>$userData->member_id,
                        'day'=>date('Y-m-d'),
                        'amount'=>$this->_timeDateCheck($userData->member_id,$userData->timePeriod,$userData->tableId),
                        'rank'=>$this->_fetchDataForDailyP($userData->tableId,'rank'),
                        'team'=>$this->_maketeamdirect($userData->member_id),
                        'dm'=>$this->_fetchDataForDailyP($userData->tableId,'amount').'%'
                    ];   
                }
            }
        }
        
    }
    }
    
    for($i=0; $i<count($daily_income); $i++){
        if(empty($this->_checkIUserHaveOrNot($daily_income[$i]['userid']))){
            $this->db->insert('daily_p',$daily_income[$i]);
            $table [] =[
                'msg'=>'daily inserted '.$daily_income[$i]['userid'].''
            ];
        }else{
            if(!empty($this->_dailyPayoutFunctionUserExist($daily_income[$i]['userid'],$daily_income[$i]['day']))){
                /*$this->db->where('userid',$data[$i]['userid'])
                    ->where('day',$data[$i]['day'])   
                    ->set('amount',$data[$i]['amount'])
                    ->set('rank',$data[$i]['rank'])
                    ->set('team',$data[$i]['team'])
                    ->set('dm',$data[$i]['dm'])
                    ->update('daily_p');*/
            }else{
                $this->db->insert('daily_p',$daily_income[$i]);
                $table [] =[
                    'msg'=>'daily inerted '.$daily_income[$i]['userid'].''
                ];
            }
        }
    }
    
    for($i=0; $i<count($wallet); $i++){
            if(!empty($this->_walletExistOrNot($wallet[$i]['user']))){
                $this->db->where('user',$wallet[$i]['user'])
                        ->set('amount',$wallet[$i]['amount'])
                        ->update('wallet');       
                $table [] =[
                    'msg'=>'wallet updated '.$wallet[$i]['user'].','
                ];                        
            }
            else{
                
                $this->db->insert('wallet',$wallet[$i]);
                $table [] =[
                    'msg'=>'wallet inserted '.$wallet[$i]['user'].','
                ];
              
            }
        }
    

}
  
  protected function _loginIF(){

      if($this->session->userdata('gameuser') != '')
      {    

      }
    else{
      redirect(base_url().'api/form');
    }
  }
  
 
    
    
  function change_passwordPageFunction(){
      $this->session->set_userdata('heading','CHANGE PASSWORD');
      $this->load->view('game/include/up');
      $this->load->view('game/updatepassword');
      $this->load->view('game/include/down');
  }
  
  function update_passwordFunctionPage()
    {
        $cur = str_replace(' ','',$this->input->post('crt_pass'));
        $cur_pas = $this->_protectedPasswordCur($cur);
        if(empty($cur)){
            echo json_encode(['cr'=>'current password required']);
        }else{
            if(empty($cur_pas))
            {
                echo json_encode(['cr'=>'current password not match']);
            }
            else{
                
                $pass = str_replace(' ','',$this->input->post('new_pass'));
                $cnf = str_replace(' ','',$this->input->post('cnf_pass'));
                if(empty($pass)){
                    echo json_encode(['cr'=>'password required']);
                }
                elseif(empty($cnf)){
                    echo json_encode(['cr'=>'confirm password required']);
                }
                else{
                    if($pass == $cnf)
                    {
                        $this->_gameChangePassword($cnf);
                        echo json_encode(['ok'=>'password changed successfully']);
                    } else{
                        echo json_encode(['cr'=>'confirm password not match']);
                    }    
                }
            }
        }
    }
    
    protected function _gameChangePassword($cnf){
        $this->db->where('user',$this->session->userdata('gameuser'));
        $this->db->set('pass',md5($cnf));
        $q = $this->db->update('gamelogin');
        return $q;
    }
    
    protected function _protectedPasswordCur($cur){
        return $this->db->where('user',$this->session->userdata('gameuser'))
                        ->where('pass',md5($cur))
                        ->select('pass')
                        ->get('gamelogin')->row('pass');
    }
  
  function notificationPage(){
      $this->_updateNotificationAll();
      $this->session->set_userdata('heading','MY NOTIFICATION');
      $this->load->view('game/include/up');
      $this->load->view('game/notfication');
      $this->load->view('game/include/down');
  }
  
  protected function _updateNotificationAll(){
      $data = $this->db->where('user',$this->session->userdata('gameuser'))->select('id')->get('notification')->result();
      
      foreach($data as $row){
          $this->db->where('id',$row->id)
                  ->set('st','0')
                  ->update('notification');
      }
      
  }
  
     //not logged in user showpanel
    
    
      
      protected function _getToken($appid,$secretkey,$mode){
           if($mode == 'PROD'){
               $url='https://payout-api.cashfree.com/';
           }else{
               $url='https://payout-gamma.cashfree.com/';    
           }
           
            $ch = curl_init($url.'payout/v1/authorize');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-Client-Id: '.$appid,
                'X-Client-Secret: '.$secretkey,
                'cache-control: no-cache',
                'Content-Type: application/json'
            ]);
        
            $result = curl_exec($ch);
            if(curl_errno($ch)){
                return ['post'=>'error in posting','mainerror'=>curl_error($ch)];
                die();
            }
            curl_close($ch);
            $rObj = json_decode($result,true);
            if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $rObj['message'];
            return $rObj['data']['token'];
      }
public function index(){
        
    $this->session->set_userdata('heading','');
    $this->load->view('game/include/up');
    $this->load->view('game/games');
    $this->load->view('game/include/down');
  }
  
  
  
    function gameStart(){
        $data =[
            'all'=>$this->_gameJson(),
            'live'=>$this->_liveGameJson()
            ];
        
        echo json_encode($data);
      
  }
  
  protected function _gameJson(){
      $game = $this->_protecetdGamesdata();
      $out='';
      if(!empty($game)){ foreach($game as $row){
                $out.='<div class="responsiveClassGame">';
                            $out.='<a href="'.base_url().'api/play-game?q='.$row->id.'" id="gameEnterToJoin">
                                <div class="card text-white mb-3" style="max-width: 10rem; background-image: linear-gradient(to right, rgba(33, 30, 30, 0.82),rgba(14, 14, 14, 0.56)),url('.base_url().'Games/game_images/'.$row->img.'); background-repeat: no-repeat; padding: 21px 0; background-size: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title text-white"><strong style="font-weight:800; line-break: anywhere;">'.$row->name.'</strong></h5>
                                        <p class="card-text"><strong>Tournament has been end.</strong></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                   ';
                   
                    if(date('Y-m-d h:i:s') > $row->end){
                            $this->db->where('id',$row->id)->set('del','0')->update('game');
                        }
                    }
                
                
                 }  else{ 
                    $out.='<div class="col-sm-12">
              <div class="text-center">
                  <p style="font-size:20px; color: #47474c;">No Games</p>
                  <div class="">
                      <i class="fa fa-trophy" style="font-size:200px;color: #eaeaea;"></i>
                  </div>
              </div>
          </div>';
                 } 
                 
        return $out;
  }
  
  protected function _protecetdGamesdata(){
      return $this->db->select('id,name,price,end,img,start,del')->get('game')->result();
  }
  
  protected function _liveGameJson(){
      $game = $this->_liveGameData();
      $out='';
      if(!empty($game)){ foreach($game as $row){
                
                    
                         $out.='<div class="responsiveClassGame">';
                            $out.='<a href="'.base_url().'api/play-game?q='.$row->id.'">
                                <div class="card text-white mb-3" style="max-width: 10rem; background-image: linear-gradient(to right, rgba(33, 30, 30, 0.82),rgba(14, 14, 14, 0.56)),url('.base_url().'Games/game_images/'.$row->img.'); background-repeat: no-repeat; background-size: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title text-white"><strong style="font-weight:800; line-break: anywhere;">'.$row->name.'</strong></h5>
                                        <p class="card-text"><strong>Tournament end in ';
                                
                                        $times = date('Y-m-d h:i:s');
                                      $date = $row->end;
                                        $time_ago = strtotime($times);
                                        $current_time = strtotime($date); 
                                        $time_diffrence = $current_time - $time_ago;
                                        $seconds = $time_diffrence;
                                        $minutes = round($seconds / 60); 
                                        $hours = round($seconds / 3600);
                                        $days = round($seconds / 86400);
                                        $weeks = round($seconds / 604800);
                                        $months = round($seconds / 2629440);
                                        $years = round($seconds / 31553280);
                                    
                                        if($seconds <= 60){     
                                            $out.=''.$seconds.' sec';
                                        }
                                    
                                        else if($minutes <=60)
                                        {
                                            if($minutes==1){
                                                $out.='1 mint';
                                            }
                                            else{
                                                $out.=''.$minutes.' mint';
                                            }
                                        }
                                    
                                        else if($hours <=24)
                                        {
                                            if($hours==1)
                                            {
                                                $out.='1 hrs';
                                            }
                                            else{
                                                $out.=''.$hours.' hrs';
                                            }
                                        }
                                    
                                        else if($days <=7)
                                        {
                                            if($days==1)
                                            {
                                                $out.='yesterday';
                                            }
                                            else{
                                                $out.=''.$days.' days ago';
                                            }
                                        }
                                        else if($weeks <=4.3)
                                        {
                                            if($weeks==1)
                                            {
                                                $out.='a week ago';
                                            }
                                            else{
                                                $out.=''.$weeks.' weeks ago';
                                            }
                                        }
                                        else if($months <=12)
                                        {
                                            if($months==1)
                                            {
                                                $out.='a month ago';
                                            }
                                            else
                                            {
                                                $out.=''.$months.' months ago';
                                            }
                                        }
                                        else
                                        {
                                            if($years==1)
                                            {
                                                $out.='one year ago';
                                            }
                                            else
                                            {
                                                $out.=''.$years.' years ago';
                                            }
                                        }
                                    
                                    
                                $out.='</strong></p>
                                <p class="border-top">joining fees</p><p> <i class="fa fa-rupee-sign"></i> '.$row->price.'</p>
                                    </div>
                                </div>
                            </a>
                        </div>';
                    if(date('Y-m-d h:i:s') > $row->end){
                            $this->db->where('id',$row->id)->set('del','0')->update('game');
                        }
                    }
                
                
                 }  else{ 
                    $out.='<div class="col-sm-12">
              <div class="text-center">
                  <p style="font-size:20px; color: #47474c;">No live matches</p>
                  <div class="">
                      <i class="fa fa-trophy" style="font-size:200px;color: #eaeaea;"></i>
                  </div>
              </div>
          </div>';
                 } 
                 
        return $out;
  }
  
  protected function _liveGameData(){
      return $this->db->select('id,name,price,end,img,start,del')->where('del','1')->get('game')->result();
  }
  
  function moreInfoFunction(){
      $this->session->set_userdata('heading','MORE');
      $this->load->view('game/include/up');
        $this->load->view('game/moreInfo');
        $this->load->view('game/include/down');
  }
  
    function accountHistory(){
        $this->session->set_userdata('heading','MY ACCOUNT');
        $this->load->view('game/include/up');
        $this->load->view('game/account');
        $this->load->view('game/include/down');
    }
    
  function matchesResultSHow(){
      $this->session->set_userdata('heading','MY MATCHES');
      $this->load->view('game/include/up');
      $this->load->view('game/matches');
      $this->load->view('game/include/down');    
  }
  
  function saveDataGame(){
      echo json_encode($this->_dataGame($_POST));
  }
  
  protected function _dataGame($req){
   $data = array(
       'game_id' => $req['gameid'],
       'user'    => $req['id'],
       'score'   => $req['score']
       );
    
   $exist = $this->_checkScoreExistOrNot($req['id'],$req['gameid']);
       if(!empty($exist)){
          $this->_gameStatusChangeMe();
          $checkTour = $this->_checkIDGame($req['tokenid']);
          
          if($checkTour->del == 0){
              $url = base_url().'api/game';
              return [
                  'url'=>$url
                  ];
          }else{
              $this->db->where('user',$req['id'])->where('game_id',$req['gameid'])->set($data)->update('scoreboard');
              return [
                  'ok'=>'updated'
                  ];
          }      
           
       }else{
          $this->db->insert('scoreboard',$data);  
          return [
              'ok'=>'inserted'
              ];
       }
  }
  
  protected function _gameStatusChangeMe(){
       $gameData = $this->_fetchGameDetails();
            
            if(!empty($gameData)){
                $game = [];
                foreach($gameData as $row){
                    $game[] = [
                        'end'=>$row->end,
                        'id'=>$row->id,
                        'del'=>$this->_checkProbalityGameOver($row->id,$row->end)
                        ];
                }
            }
            for($i=0; $i<count($game); $i++){
                $this->db->where('id',$game[$i]['id'])
                         ->update('game',$game[$i]);
                }
  }
  
  
    protected function _checkProbalityGameOver($id,$date){
    if(date('Y-m-d h:i:s') > $date){
        return 0;
    }else{
        return 1;
    }
}

    protected function _fetchGameDetails(){
    return $this->db->select('id,del,start,end')->get('game')->result();
}
  protected function _checkScoreExistOrNot($id,$g){
      
      return $this->db->where('user',$id)->where('game_id',$g)->select('game_id')->from('scoreboard')->get()->row();
  }
  
  function gameWorldFunction(){
      if(isset($_GET['q']) && $_GET['id']){
          switch ($_GET['q']) {
            case '1218':
                //echo 'jh';
                $this->load->view('game/Bubbles/index');
              break;
              case '1217':
                //echo 'jh';
                $this->load->view('game/Frog/index');
              break;
              case '1220':
                //echo 'jh';
                $this->load->view('game/Race/index');
              break;
            
            default:
                redirect(base_url().'api/game');
              break;
        
          }
      }else{
          redirect(base_url().'api/game');
      }
  }
  function joinGameFunctionUser(){
      if(isset($_GET['q'])){
          $sess = $this->_fetchuserDetailsSave($this->session->userdata('gameuser'));
        if(!empty($sess)){    
          $wxist = $this->_checkIDGame($_GET['q']);
            $game = $this->_tournamentJoinOrNot($wxist->tourid);
            if(empty($game)){
              if(!empty($wxist)){
                  
                  $txn = strtotime(date('Y-m-d h:i:s')).substr($this->session->userdata('gameuser'),2,6).'';
                  $data = [
                      'user' => $this->session->userdata('gameuser'),
                      'txn' =>  $txn,
                      't_amt' => $wxist->price,
                      'interest' => '0',
                      'order' =>'Game '.$wxist->name,
                      'status'=>'Done',
                      'method'=>'GAME_PLAY',
                      'gate'=>'app',
                      'timestamp'=>date('Y-m-d h:i:s A'),
                      'dr_cr'=>'Dr'
                    ];
                    $noti = [
                      'user' => $this->session->userdata('gameuser'),
                      'type' => 'Game '.$wxist->name,
                      'msg' => 'you have play '.$wxist->name.' game of '.$wxist->price.' Rs. tournament id: #'.$wxist->tourid.'',
                      'created_at' => date('Y-m-d h:i:s')
                    ];
                    
                $this->_updateNotify($noti);
                $this->_updatetxn($data);
              //$this->db->insert('transaction',$data);
              
                redirect(base_url().'api/gameWorld?q='.$wxist->id.'&id='.$wxist->tourid);
                
              }else{
                $this->session->set_flashdata('acc','
                      $.confirm({
                            title: "Tournament Join",
                            content: "Oops! we can not join tournament right now.",
                            type: "red",
                            keys: ["Enter"],
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: "Close",
                                    btnClass: "btn-red",
                                    close: function() {}
                                }
                            }
                        });');
                redirect(base_url().'api/game');       
              }

          }else{
                  redirect(base_url().'api/gameWorld?q='.$wxist->id.'&id='.$wxist->tourid);
              }
    }else{
         $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Tournament Join",
                          content: "Oops! we can not join tournament right now.",
                          type: "red",
                          keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
            redirect(base_url().'api/game');   
    }
          //session save;

      }else{
          $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Tournament Join",
                          content: "Oops! we can not join tournament right now.",
                          type: "red",
                          keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
            redirect(base_url().'api/game');   
      }
  }
  
  protected function _tournamentJoinOrNot($id){
      return $this->db->where('user',$this->session->userdata('gameuser'))
                      ->where('game_id',$id)
                      ->limit(1)
                      ->from('scoreboard')
                      ->get()
                      ->row();
  }
  
  function play_gameNow(){
        if(isset($_GET['q'])){
          $check = $this->_checkIDGame($_GET['q']);
          if(empty($check)){
              redirect(base_url().'api/game');
          }else{
              
            $this->load->view('game/include/up');
              $this->load->view('game/enterGame');
              $this->load->view('game/include/down');
              
          }
          
      }else{
          redirect(base_url().'api/game');
      }
    }
  protected function _checkIDGame($id){
      return $this->db->where('id',$id)->select('tourid,id,url,price,name,del')->get('game')->row();
  }
 function checkProbalityToGame(){
      
      echo json_encode($this->_gameFixing($_POST));
  }
  
  protected function _gameFixing($req){
      $out='';
      $getData = $this->_checkActiveOrNot($req['id']);
      if($getData->del == 1){
          $out.='<ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <small class="text-muted">NOTE: if you allready join tournament then you will not paid to play game again, before tournament end.</small>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Tournament Name</h6>
                    </div>
                        <span class="text-muted">'.$getData->name.'</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Entry Fees</h6>
                    </div>
                        <span class="text-muted">₹ '.$getData->price.'</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed'; if($this->_myBlanceLeft() >= 70){ $out.=' bg-danger text-white'; } $out.='">
                    <div>
                        <h6 class="my-0"><i class="fa fa-wallet"></i> Wallet Balance</h6>
                    </div>
                        <span class="'; if($this->_myBlanceLeft() >= 70){ $out.=' text-white'; } $out.='">₹ '.$this->_myBlanceLeft().'</span>
                </li>';
                if($this->_myBlanceLeft() >= $getData->price){
                    
                $out.='<li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0"></h6>
                    </div>
                        <span class="text-danger">- ₹ ';  $out.=$getData->price; $out.='</span>
                </li>';
                }
                if($this->_myBlanceLeft() >= $getData->price){
                    
                $out.='<li class="list-group-item d-flex justify-content-between lh-condensed bg-dark text-white">
                    <div>
                        <h6 class="my-0"><i class="fa fa-wallet"></i> Available Balance</h6>
                    </div>
                        <span class="text-white">₹ '; $out.=$this->_myBlanceLeft() - $getData->price; $out.='</span>
                </li>';
                    
                }
                
            $out.='</ul>
            <div class="form-row">
                <div class="col-md-6  p-2">
                    <a href="'.base_url().'api/game" class="closeModalNow btn btn-danger btn-block"><span style="vertical-align: middle;">Close</span> <i class="fa fa-times"></i></a>
                </div>
                <div class="col-md-6 p-2">';
                    if($this->_myBlanceLeft() >= $getData->price){
                        $out.='<a href="'.base_url().'api/join/tournament?q='.$getData->id.'" class="btn btn-success btn-block"  id="balanceAffOkagjhsg"><span style="vertical-align: middle;">Join</span> <i class="fa fa-angle-right"></i></a>';    
                    }
                    else{
                        $out.='<a href="'.base_url().'api/add-balance" class="btn btn-dark btn-block" id="balanceAffOkagjhsg"> <i class="fa fa-wallet"></i> <span style="vertical-align: middle;">ADD Balance</span></a>';    
                    }
                    
                $out.='</div>
            </div>';       
      }
      elseif($getData->del == 0){
          $out.='<ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div class="text-muted"><strong>Tournament has been closed.</strong></div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Tournament Name</h6>
                    </div>
                        <span class="text-muted">'.$getData->name.'</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Entry Fees</h6>
                    </div>
                        <span class="text-muted">₹ '.$getData->price.'</span>
                </li>';
                
            $out.='</ul>
            <div class="form-row">
                <div class="col-md-12  p-2">
                    <a href="'.base_url().'api/game" class="closeModalNow btn btn-danger btn-block"><span style="vertical-align: middle;">Close</span> <i class="fa fa-times"></i></a>
                </div>
            </div>';       
      }
      
      return $out;
  }
  
  protected function _checkActiveOrNot($id){
      return $this->db->where('id',$id)->select('price,name,del,id')->get('game')->row();
  }

  protected function _myBlanceLeft(){
      $main = $this->_mywallet();
        $trns = $this->_TransactionTableCount();

        return $main - $trns;
    }
    
    protected function _mywallet(){
        return $this->db->where('user',$this->session->userdata('gameuser'))->select('amount')->get('wallet')->row('amount');
    }
    
    protected function _TransactionTableCount()
    {
       /******** AUto TRANSFER*********/ 
       $sess = $this->_fetchuserDetailsSave($this->session->userdata('gameuser'));
        if(!empty($sess)){ 
            $data = $this->_countTransaction($this->session->userdata('gameuser'));
          
             $total = 0;
             foreach ($data as $key) {
               $total += $key->t_amt;
             }
             return $total;
        }else{
          return 0;
        }
       
        
    }
    
    protected function _countTransaction($id)
    {
      return $this->db->where('user',$id)
                      //->where('gate','app')    
                      ->where('dr_cr!=','Cr')
                      ->where('order !=','Join Megacontest')
                      ->where('order !=','Upgrade Account')
                      ->select('t_amt')
                      ->from('transaction')
                      ->get()->result();
    }

    
  /////////////////// ADD BALANCE ////////
  
  function balanceFunctionPhp(){
      $this->session->set_userdata('heading','ADD BALANCE');
      $this->load->view('game/include/up');
      $this->load->view('game/balance');
      $this->load->view('game/include/down');
  }
  
  
  function balance_addFunctionPhp(){
      $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('orderAmount','Amount','trim|required|numeric');
        if($this->form_validation->run()){
            
            $this->session->set_userdata('typepp','Balance added');
            echo $this->_protectedFunctionBlanceAMount($this->security->xss_clean($_POST));
            
      }else{
          
          redirect(base_url().'api/add-balance');
      }
  
  }
  
  protected function _protectedFunctionBlanceAMount($req){
          $data = $this->_getAllData($req);
          $createSign = [
              'appId'=>$data['appId'],
              'orderId'=>$data['orderId'],
              'orderAmount'=>$data['orderAmount'],
              'orderCurrency'=>$data['orderCurrency'],
              'orderNote'=>$data['orderNote'],
              'customerName'=>$data['customerName'],
              'customerEmail'=>$data['customerEmail'],
              'customerPhone'=>$data['customerPhone'],
              'returnUrl'=>$data['returnUrl'],
              'notifyUrl'=>$data['notifyUrl']
              ];
          $mode = $this->_fetchMode();      
          $sign = $this->_createSignature($createSign);      
          $this->_nowSendtoserver($createSign,$sign,$mode);
        
  }
  
  
  
  protected function _fetchwalletBalance(){
      $sess = $this->_fetchuserDetailsSave($this->session->userdata('gameuser'));
        if(!empty($sess)){ 
          return $this->db->where('user',$this->session->userdata('gameuser'))->select('wallet')->get('gamelogin')->row('wallet');
        }else{
          return 0;
        }
      
  }
  
  protected function  _updateWallet($wall){

      return $this->db->where('user',$this->session->userdata('gameuser'))
                      ->set($wall)
                      ->update('gamelogin');
  }
    protected function  _updatetxn($txn){
        return $this->db->insert('transaction',$txn);
    }
    protected function  _updateNotify($noti){
        return $this->db->insert('notification',$noti);
    }
  protected function _fetchSiteData(){
      return $this->db->select('title,logo,favicon,meta_author,meta_desc,meta_keyword,loader,default_user')
        ->from('site_setting')
        ->get()->row();
  }
  
  protected function _fetchAgainSign($req){
       $secretkey = $this->_fetchSign();
     $orderId = $req["orderId"];
     $orderAmount = $req["orderAmount"];
     $referenceId = $req["referenceId"];
     $txStatus = $req["txStatus"];
     $paymentMode = $req["paymentMode"];
     $txMsg = $req["txMsg"];
     $txTime = $req["txTime"];
     
     $data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
     
     $hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;
     $computedSignature = base64_encode($hash_hmac);
     return $computedSignature;
  }
  protected function _getAllData($req){
      return [
              'customerName'=>$this->session->userdata('name'),
              'customerPhone'=>$this->session->userdata('phone'),
              'customerEmail'=>$this->session->userdata('email'),
              'appId'=>$this->_getAppID(),
              'orderId'=>strtotime(date('Y-m-d h:i:s')).substr($this->session->userdata('phone'),0,4),
              'orderCurrency'=>$this->_getCurrency(),
              'orderNote'=>'',
              'returnUrl'=>base_url().'api/moneysuccess',
              'orderAmount'=>$req['orderAmount'],
              'notifyUrl'=>''
              
          ];
  }
  
  protected function _getCurrency(){
      return $this->db->select('currency')
                      ->where('type','cashfree')
                        ->from('credential')
                        ->get()->row('currency');
  }
  
  protected function _getAppID(){
      return $this->db->select('appid')
                      ->where('type','cashfree')
                        ->from('credential')
                        ->get()->row('appid');
  }
  
  protected function _createSignature($createSign){
      $secretKey = $this->_fetchSign();
      ksort($createSign);
      $signatureData = "";
        foreach ($createSign as $key => $value){
            $signatureData .= $key.$value;
        }
        
      $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
        $signature = base64_encode($signature);
        return $signature;
  }
  
  protected function _fetchSign(){
      return $this->db->select('secretkey')
                        ->from('credential')
                        ->get()->row('secretkey');
  }
  
  protected function _fetchMode(){
     return $this->db->select('mode')
                        ->from('credential')
                        ->get()->row('mode'); 
  }
  
  
    function showTransactionhistoryPage(){
        $this->session->set_userdata('heading','MY TRANSACTION');
        $this->load->view('game/include/up');
        $this->load->view('game/transaction_table');   
        $this->load->view('game/include/down');
        
    }
    function showWithdrawlPageFunction(){

        $this->session->set_userdata('heading','MY WITHDRAW');
        $this->load->view('game/include/up');
        $this->load->view('game/withdrawTable');
        $this->load->view('game/include/down');
    }
  
  function join_contestNowFunction(){
      $this->session->set_userdata('heading','JOIN CONTEST');
      $this->load->view('game/include/up');
      $this->load->view('game/join_contest');
      $this->load->view('game/include/down');
  }
  
  function finalPaymentOption(){
      $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pkg','pckage','trim|required|numeric');
        if($this->form_validation->run()){
            $this->session->set_userdata('typepp','Join Megacontest');
            $this->_sendToprivateFunctionproceed($this->security->xss_clean($_POST));
        }else{
            $this->session->set_flashdata('error',form_error('pkg'));
            redirect(base_url().'api/join-contest');   
        }
  }
  
  
  protected function _sendToprivateFunctionproceed($req){
      
      $createSign = [
              'customerName'=>$this->session->userdata('name'),
              'customerPhone'=>$this->session->userdata('phone'),
              'customerEmail'=>$this->session->userdata('email'),
              'appId'=>$this->_getAppID(),
              'orderId'=>strtotime(date('Y-m-d h:i:s')).$this->session->userdata('gameuser'),
              'orderCurrency'=>$this->_getCurrency(),
              'orderNote'=>'',
              'returnUrl'=>base_url().'api/moneysuccess',
              'orderAmount'=>$this->db->where('id',$req['pkg'])->select('price')->get('package')->row('price'),
              'notifyUrl'=>''
          ];
      
          $mode = $this->_fetchMode();      
          $sign = $this->_createSignature($createSign);
          $this->_nowSendtoserver($createSign,$sign,$mode);
          
  }
  
  function upgradefunctionpage(){
      $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pkg','pckage','trim|required|numeric');
        if($this->form_validation->run()){
            $this->session->set_userdata('typepp','Upgrade Account');
            $this->_upgradeCreateprivate($_POST);
        }else{
            $this->session->set_flashdata('error',form_error('pkg'));
            redirect(base_url().'api/upgrade');   
        }
  }
  
  
  protected function _upgradeCreateprivate($req){
      $createSign = [
              'customerName'=>$this->session->userdata('name'),
              'customerPhone'=>$this->session->userdata('phone'),
              'customerEmail'=>$this->session->userdata('email'),
              'appId'=>$this->_getAppID(),
              'orderId'=>strtotime(date('Y-m-d h:i:s')).$this->session->userdata('gameuser'),
              'orderCurrency'=>$this->_getCurrency(),
              'orderNote'=>'',
              'returnUrl'=>base_url().'api/moneysuccess',
              'orderAmount'=>$this->db->where('id',$req['pkg'])->select('price')->get('package')->row('price'),
              'notifyUrl'=>''
          ];
        
          $mode = $this->_fetchMode();      
          $sign = $this->_createSignature($createSign);
          $this->_nowSendtoserver($createSign,$sign,$mode);
          
  }
  
  protected function _nowSendtoserver($createSign,$sign,$mode){
      
      if ($mode == "PROD") {
          $url = "https://www.cashfree.com/checkout/post/submit";
          //$url = "https://test.cashfree.com/billpay/checkout/post/submit";
        } else {
          $url = "https://test.cashfree.com/billpay/checkout/post/submit";
        }
      echo '
          <html>
          <head>
              <link rel="stylesheet" href="http://yoyoplay.in/adminassets/css/all.min.css">
              <link rel="stylesheet" href="http://yoyoplay.in/adminassets/css/demo_1/style.css">
              <title>YOYO Play Game | redirecting to payment gateway</title>
          </head>
          <style>
          .request {
                font-size: 165px;
                position: fixed;
                top: 35%;
                right: 60px;
            }
            .textStyle {
                font-size: 60px;
                margin-top: 15px;
            }
            </style>
          <body>
          <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="request">
                            <i class="fa fa-spin fa-spinner"></i>
                            <div class="textStyle">
                                please wait..
                            </div>
                            <div class="textStyle">
                                redirecting to payment gateway
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          <form action="'.$url.'" name="frm1" method="post">
              <input type="hidden" name="signature" value="'.$sign.'"/>
              <input type="hidden" name="orderNote" value="'.$createSign['orderNote'].'"/>
              <input type="hidden" name="orderCurrency" value="'.$createSign['orderCurrency'].'"/>
              <input type="hidden" name="customerName" value="'.$createSign['customerName'].'"/>
              <input type="hidden" name="customerEmail" value="'.$createSign['customerEmail'].'"/>
              <input type="hidden" name="customerPhone" value="'.$createSign['customerPhone'].'"/>
              <input type="hidden" name="orderAmount" value="'.$createSign['orderAmount'].'"/>
              <input type="hidden" name="notifyUrl" value="'.$createSign['notifyUrl'].'"/>
              <input type="hidden" name="returnUrl" value="'.$createSign['returnUrl'].'"/>
              <input type="hidden" name="appId" value="'.$createSign['appId'].'"/>
              <input type="hidden" name="orderId" value="'.$createSign['orderId'].'"/>
          </form>
          <script language="javascript">document.frm1.submit();</script>
          </body>
      ';

  }
  
  function responseSuccessPage(){
      $this->_protectedFunctionResponse($_POST);
  }
  
  protected function _protectedFunctionResponse($req){
      $site = $this->_fetchSiteData();
       $computedSignature = $this->_fetchAgainSign($req);
       $signature = $req["signature"];
       $noti = $this->db->where('user',$this->session->userdata('gameuser'))->where('st','1')->from('notification')->get()->num_rows();
       echo '
          <!DOCTYPE html>
            <html lang="en">
            
            <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <meta http-equiv="X-UA-Compatible" content="ie=edge">
              <meta name="author" content="'.$site->meta_author.'">
              <meta name="description" content="'.$site->meta_desc.'">
              <meta name="keywords" content="'.$site->meta_desc.'">
              <meta name="msapplication-tap-highlight" content="no">
              <meta name="'.$this->security->get_csrf_token_name().'" content="'.$this->security->get_csrf_hash().'">
              <title>'.$site->title.'</title>
              <link rel="stylesheet" href="'.base_url().'adminassets/css/all.min.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/vendors/core/core.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/fonts/feather-font/css/iconfont.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/css/demo_1/style.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/css/custom.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/css/animate.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/css/dropzone.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/css/toastr.min.css">
              <link rel="stylesheet" href="'.base_url().'adminassets/css/demo_1/jquery-confirm.css">
              <link rel="shortcut icon" href="'.base_url().'adminassets/site/'.$site->favicon.'" />
            </head>
            <body>
            <!-- LOADER -->
            <div class="preloader">
                <div class="loader"></div> 
            </div>
            <div class="container-fluid">
                <div class="row sticky-top">
                    <div class="col-md-12 bg-dark text-white p-3">
                        <div class="d-flex justify-content-start">
                            <div class=""><a href="'.base_url().'api/account-profile" class="text-white"><i class="fa fa-angle-left"></i> BACK </a></div>
                        </div>
                    </div>
                </div>
            </div>
          <div class="mb-3 container-fluid " style="margin-top:30px;">
                <div class="card">
                    <div class="card-body" style="box-shadow:1px 1px 12px #dde;">
                        <div class="row" style="padding: 20px 0px;">
                              <div class="col-md-12 text-center">
       ';
     if ($signature == $computedSignature) {
        echo '
            <div class="successCheck">
                    <i class="fa '; if($req['txStatus'] == 'CANCELLED' || $req['txStatus'] == 'FAILED'){ echo ' fa-times-circle text-danger';} elseif($req['txStatus'] == 'PENDING'){echo ' fa-spin fa-spinner text-warning';} else{ echo ' fa-check-circle text-success';} echo '"></i>
                </div>
                <div class="mb-2 '; if($req['txStatus'] == 'CANCELLED' || $req['txStatus'] == 'FAILED'){ echo ' text-danger';}elseif($req['txStatus'] == 'PENDING'){echo ' text-warning';}else{ echo ' text-success';} echo '">'.$req['txStatus'].'</div>
                    <div class="dataTxn">
                        <table class="table table-borderd-less">
                            <tbody>
                              <tr>
                                <td>Order id</td>
                                <td>#'.$req['orderId'].'</td>
                            </tr>
                            <tr>
                                <td>Amount</td>
                              <td><i class="fa fa-rupee-sign"></i> '.$req['orderAmount'].'</td>
                            </tr>
                            <tr>
                                <td>Paid on</td>
                              <td>'.$req['txTime'].'</td>
                            </tr>
                            <tr>
                                <td>Refrence id</td>
                              <td>'.$req['referenceId'].'</td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                  <a href="'.base_url().'api/game" class="btn btn-block btn-success"><i class="fa fa-angle-left"></i> Back To Account</a>
                                </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                <style>
                    .successCheck {
                        font-size: 115px;
                    }
            </style>
            ';   
            
            $noti = array(
                'user'=>$this->session->userdata('gameuser'),
                'type'=>'PAYMENT '.$req['txStatus'].'',
                'msg'=> 'payment '.$req['orderAmount'].' Rs. at '.$req['txTime'].' your refrence id #'.$req['referenceId'].' ',
                'created_at'=>date('Y-m-d h:i:s')
                );
            
            if($this->session->userdata('typepp') == 'Join Megacontest'){
                $txn = array(
                'user'=>$this->session->userdata('gameuser'),
                'gate'=>'app',
                'txn'=>$req['orderId'],
                'refrence'=>$req['referenceId'],
                'status'=>$req['txStatus'],
                'method'=>$req['paymentMode'],
                'order'=>$this->session->userdata('typepp'),
                'dr_cr'=>'Dr',
                't_amt'=>$req['orderAmount'],
                'timestamp'=>date('Y-m-d h:i:s A'),
                'interest'=>'0'
                );        
                
            }elseif($this->session->userdata('typepp') == 'Upgrade Account'){
                $txn = array(
                'user'=>$this->session->userdata('gameuser'),
                'gate'=>'app',
                'txn'=>$req['orderId'],
                'refrence'=>$req['referenceId'],
                'status'=>$req['txStatus'],
                'method'=>$req['paymentMode'],
                'order'=>$this->session->userdata('typepp'),
                'dr_cr'=>'Dr',
                't_amt'=>$req['orderAmount'],
                'timestamp'=>date('Y-m-d h:i:s A'),
                'interest'=>'0'
                );        
                
            }else{
                $txn = array(
                'user'=>$this->session->userdata('gameuser'),
                'gate'=>'app',
                'txn'=>$req['orderId'],
                'refrence'=>$req['referenceId'],
                'status'=>$req['txStatus'],
                'method'=>$req['paymentMode'],
                'order'=>$this->session->userdata('typepp'),
                'dr_cr'=>'Cr',
                't_amt'=>$req['orderAmount'],
                'timestamp'=>date('Y-m-d h:i:s A'),
                'interest'=>'0'
                );        
            }
            
            
            if($req['txStatus'] == 'FAILED' || $req['txStatus'] == 'CANCELLED' || $req['txStatus'] == 'PENDING'){
                    
            }else{
                
                if($this->session->userdata('typepp') == 'Join Megacontest'){
                    $scr = [
                        'scracth'=>rand(00000000,99999999),
                        'pin'=>rand(00000,99999),
                        'status'=>'0',
                        'activation'=>date('Y-m-d'),
                        'package_name'=>'joining',
                        'package_amt'=>$req['orderAmount']
                    ];
                    
                    $inc = array(
                      'tableId'=>'1',
                      'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day"))
                    );
                    //$this->db->where('user_id',$this->session->userdata('gameuser'))->set(array('p_amt'=>$req['orderAmount'],'status'=>'0','activation'=>date('Y-m-d')))->update('leftbinary');       
                    $this->db->where('user_id',$this->session->userdata('gameuser'))->set(array('p_amt'=>$req['orderAmount'],'activation'=>date('Y-m-d'),'status'=>'0'))->update('makebinary');       
                    $this->db->where('member_id',$this->session->userdata('gameuser'))
                             ->set($inc)
                             ->update('login');
                    

                    $this->db->where('member_id',$this->session->userdata('gameuser'))->set($scr)->update('login');
                    $this->db->where('user',$this->session->userdata('gameuser'))->set('status','1')->update('gamelogin');
                    $this->db->where('userid',$this->session->userdata('gameuser'))->set(array('amount'=>'0','rank'=>'Null','team'=>'null','dm'=>'Null'))->update('daily_p');
                    $this->db->where('user_id',$this->session->userdata('gameuser'))->delete('directincome');
                    $this->db->where('userid',$this->session->userdata('gameuser'))->delete('binary');
                    $this->db->where('userid',$this->session->userdata('gameuser'))->delete('business');
                    $this->db->where('userid',$this->session->userdata('gameuser'))->delete('bv');
                    $this->db->where('user',$this->session->userdata('gameuser'))->delete('wallet');
                    $this->db->where('userid',$this->session->userdata('gameuser'))->delete('userbinary');
                    $this->db->where('user',$this->session->userdata('gameuser'))->delete('transaction');
                    
                }elseif($this->session->userdata('typepp') == 'Upgrade Account'){
                    $scr = [
                        'scracth'=>rand(00000000,99999999),
                        'pin'=>rand(00000,99999),
                        'status'=>'0',
                        'activation'=>date('Y-m-d'),
                        'package_name'=>'joining',
                        'package_amt'=>$req['orderAmount'],
                        'upgrade'=>'1'
                    ];
                    
                    $inc = array(
                      'tableId'=>'2',
                      'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day"))
                    );
                    
                    $this->db->where('member_id',$this->session->userdata('gameuser'))
                             ->set($inc)
                             ->update('login');
                             
                    $this->db->where('user_id',$this->session->userdata('gameuser'))->set(array('p_amt'=>$req['orderAmount'],'activation'=>date('Y-m-d'),'status'=>'0'))->update('makebinary');           
                    $this->db->where('member_id',$this->session->userdata('gameuser'))->set($scr)->update('login');
                    $this->db->where('user',$this->session->userdata('gameuser'))->set('status','1')->update('gamelogin');
                    
                }else{
                    $wall = array(
                        'wallet'=>$req['orderAmount']+$this->_fetchwalletBalance()
                    );    
                    $this->_updateWallet($wall);
                }
            }
            
            $this->_updatetxn($txn);
            $this->_updateNotify($noti);
                    
     }else{
         echo '
             <div class="successCheck">
                    <i class="fa '; if($req['txStatus'] == 'CANCELLED' || $req['txStatus'] == 'FAILED'){ echo ' fa-times-circle text-danger';} elseif($req['txStatus'] == 'PENDING'){echo ' fa-spin fa-spinner text-warning';} else{ echo ' fa-check-circle text-success';} echo '"></i>
                </div>
                <div class="mb-2 '; if($req['txStatus'] == 'CANCELLED' || $req['txStatus'] == 'FAILED'){ echo ' text-danger';}elseif($req['txStatus'] == 'PENDING'){echo ' text-warning';}else{ echo ' text-success';} echo '">'.$req['txStatus'].'</div>
                    <div class="dataTxn">
                        <table class="table table-borderd-less">
                            <tbody>
                              <tr>
                                <td>Order id</td>
                                <td>#'.$req['orderId'].'</td>
                            </tr>
                            <tr>
                                <td>Amount</td>
                              <td><i class="fa fa-rupee-sign"></i> '.$req['orderAmount'].'</td>
                            </tr>
                            <tr>
                                <td>Paid on</td>
                              <td>'.$req['txTime'].'</td>
                            </tr>
                            <tr>
                                <td>Refrence id</td>
                              <td>'.$req['referenceId'].'</td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                  <a href="'.base_url().'api/game" class="btn btn-block btn-success"><i class="fa fa-angle-left"></i> Back To Account</a>
                                </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                <style>
                    .successCheck {
                        font-size: 115px;
                    }
            </style>
            ';   
            if($this->session->userdata('typepp') == 'Join Megacontest'){
                $txn = array(
                'user'=>$this->session->userdata('gameuser'),
                'gate'=>'app',
                'txn'=>$req['orderId'],
                'refrence'=>$req['referenceId'],
                'status'=>$req['txStatus'],
                'method'=>$req['paymentMode'],
                'order'=>$this->session->userdata('typepp'),
                'dr_cr'=>'Dr',
                't_amt'=>$req['orderAmount'],
                'timestamp'=>date('Y-m-d h:i:s A'),
                'interest'=>'0'
                );        
            }else{
                $txn = array(
                'user'=>$this->session->userdata('gameuser'),
                'gate'=>'app',
                'txn'=>$req['orderId'],
                'refrence'=>$req['referenceId'],
                'status'=>$req['txStatus'],
                'method'=>$req['paymentMode'],
                'order'=>$this->session->userdata('typepp'),
                'dr_cr'=>'Cr',
                't_amt'=>$req['orderAmount'],
                'timestamp'=>date('Y-m-d h:i:s A'),
                'interest'=>'0'
                );        
            }
            
            $this->_updatetxn($txn);
            $this->_updateNotify($noti);
     }
     
     echo '
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <style>
    section#footer-menu {
        border-top: 1px solid #282f3a;
        box-shadow: 1px 1px 12px #282f3a;
        background: #282f3a;
    }
    section#footer-menu a i{
        color:#fff;
        font-size: 20px;
    }
    section#footer-menu a small{
        color:#fff;
    }
    span.notificationArea {
    color: #fff;
    background: #ff3366;
    padding: 3px 5px;
    border-radius: 35px;
    position: absolute;
    bottom: 25px;
    font-size: 10px;
}';

 $noti = $this->db->where('user',$this->session->userdata('gameuser'))->where('st','1')->from('notification')->get()->num_rows();
echo '</style>
<div class="container-fluid">
<section id="footer-menu" style=" width:100%;" class="fixed-bottom">
        <div class="d-flex justify-content-between p-2">
            
                <div class="text-center ">
                    <a href="'.base_url().'api/game" class="gameMenuAnchor">
                        <i class="fas fa-tachometer-alt"></i>
                        <div class="">
                            <small class="text-white">HOME</small>
                        </div>
                    </a>
                </div>
                
                <div class="text-center ">
                    <a href="'.base_url().'api/my-matches" class="gameMenuAnchor">
                        <i class="fa fa-gamepad"></i>
                        <div class="">
                            <small class="text-white">MATCHES</small>
                        </div>
                    </a>
                </div>
                
                <div class="text-center ">
                    <a href="'.base_url().'api/notification" class="gameMenuAnchor">
                        <i class="fa fa-bell"></i>';
                        if(!empty($noti)){
                        if($noti == 1 || $noti == 2 || $noti == 3 || $noti == 4 || $noti == 5 || $noti == 6 || $noti == 7 || $noti == 8 || $noti == 9){ 
                            echo '<span class="notificationArea">0'.$noti.'</span>';
                        } else{ 
                            echo '<span class="notificationArea">'.$noti.'</span>';
                            } 
                        } 
                        echo '<div class="">
                            <small class="text-white">NOTIFICTION</small>
                        </div>
                    </a>
                </div>    
                
                <div class="text-center ">
                    <a href="'.base_url().'api/account-profile" class="gameMenuAnchor">
                        <i class="fa fa-user-circle"></i>
                        <div class="">
                            <small class="text-white">ACCOUNT</small>
                        </div>
                    </a>
                </div>
                
                <div class="text-center ">
                    <a href="'.base_url().'api/more-info" class="gameMenuAnchor">
                        <i class="fa fa-ellipsis-h"></i>
                        <div class="">
                            <small class="text-white">MORE</small>
                        </div>
                    </a>    
                </div>
            
        </div>
    </section>
</div>

            
            
            <script src="'.base_url().'adminassets/vendors/core/core.js"></script>
            <script src="'.base_url().'adminassets/vendors/feather-icons/feather.min.js"></script>
            <script src="'.base_url().'adminassets/js/template.js"></script>
            <script src="'.base_url().'adminassets/js/dashboard.js"></script>
            <script src="'.base_url().'adminassets/js/toastr.min.js"></script>
            <script src="'.base_url().'adminassets/js/jquery-3.4.1.js"></script>
            <script src="'.base_url().'adminassets/js/dropzone.js"></script>
            <script src="'.base_url().'adminassets/js/api.js"></script>
            <script src="'.base_url().'adminassets/js/jquery-confirm.js"></script>
            <script src="'.base_url().'adminassets/js/holder.min.js"></script>
              <!-- end custom js for this page -->
              <script type="text/javascript">
                $(function(){
                  '.$this->session->flashdata('acc').'
                });
                
              </script>
            

     ';
  }
  
  function upgradeNowFunctionPage(){
      $this->session->set_userdata('heading','MY UPGRADE');
      $this->load->view('game/include/up');
      $this->load->view('game/upgradePage');
      $this->load->view('game/include/down');
  }
  
  function withdrawFnctionPage(){

      $this->session->set_userdata('heading','MY WITHDRAW');
      $this->load->view('game/include/up');
      $this->load->view('game/withdrow');
      $this->load->view('game/include/down');
  }
  
  function addBenificeryPageInserted(){
      $this->session->set_userdata('heading','ADD ACCOUNT');
      $this->load->view('game/include/up');
      $this->load->view('game/addBenificery');
      $this->load->view('game/include/down');
  }
  
  function makeValidationBeniicery(){
      $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('acco','Account number','trim|required|numeric|max_length[20]');//|is_unique[accounts.bankAccount]
        $this->form_validation->set_rules('cnfAcc','Confirm account number','trim|required|numeric|max_length[20]|matches[acco]');
        $this->form_validation->set_rules('baneId','Benificery','trim|required|is_unique[accounts.beneId]');
        $this->form_validation->set_rules('userid','userid','trim|required|is_unique[accounts.user]');
        $this->form_validation->set_rules('ifsc','Ifsc code','trim|required');
        $this->form_validation->set_rules('brnch','Branch name','trim|required');
        $this->form_validation->set_rules('phone','phone','trim|required|min_length[10]|min_length[10]|numeric');
        $this->form_validation->set_rules('email','email','trim|required|valid_email');
        if($this->form_validation->run()){
            
            echo json_encode($this->_protectedFunctionAddBenificery($this->security->xss_clean($_POST)));
            
        }else{
            $data = [
                'a'=>form_error('name'),
                'b'=>form_error('acco'),
                'c'=>form_error('cnfAcc'),
                'd'=>form_error('baneId'),
                'e'=>form_error('ifsc'),
                'f'=>form_error('phone'),
                'h'=>form_error('brnch'),
                'g'=>form_error('email'),
                'i'=>form_error('userid')
            ];
            echo json_encode($data);
            
            
        }
  }
  function createAccountAgain(){
    if($this->session->userdata('accountnumber') != ''){

        $data = $this->_protectedFetchBankData($this->session->userdata('accountnumber'));
        $array = [
          'beneId'=>$data->beneId,
          'name'=>$data->name,
          'email'=>$data->email,
          'phone'=>$data->phone,
          'bankAccount'=>$data->bankAccount,
          'ifsc'=>$data->ifsc,
          'address1'=>$data->address1,
          'status'=>$data->status,
          'user'=>$this->session->userdata('gameuser')
        ];

        $this->db->insert('accounts',$array);
        $this->session->unset_userdata('accountnumber');
        $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Account Added",
                          content: "Account Added successfully",
                          type: "green",
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-green",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'api/withdrow-amount');     

    }else{
      $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Server error ",
                          content: "Oops! we can not add account deatils.",
                          type: "red",
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'api/withdrow-amount');     
    }
    
  }

  protected function _protectedFetchBankData($account){
    return $this->db->where('bankAccount',$account)->select('beneId,name,email,phone,bankAccount,ifsc,address1,status')->get('accounts')->row();
  }

  protected function _protectedFunctionAddBenificery($req){
      //<p class="text-dark text-md">'; 
                        /*$out.=isset($finalBv)
                        ? '<a href="javascript:;" data="'.base_url().'my/rewards"><span class="badge badge-warning text-white text-md">'.$finalBv.' BV <i class="fa fa-angle-down"></i></span></a>'
                        : '<a href="javascript:;"><span class="badge badge-warning text-white text-md">'.$finalBv.' BV <i class="fa fa-angle-down"></i></span></a>';
            $out.='</p>*/
      $exist = $this->_protectedFunctionAccountExistInDB($this->session->userdata('gameuser'),$req['cnfAcc']);
      if($exist == 'ok'){
        $this->session->set_userdata('accountnumber',$req['cnfAcc']);
        $urladd = base_url().'api/account-again-exist';
        return [
            'kycTitle'=>'Account Exist?',
            'kycmsg'=>'This Account All Ready Exist!<br><strong>Do you really add one more same account?</strong>',
            'url'=>$urladd,
            'kyc'=>'ok'
          ];

      }elseif ($exist == 'no') {
        $urladd = base_url().'api/add-benificery';
        return [
            'kycTitle'=>'Account Add Limit Crosed?',
            'kycmsg'=>'Sorry! you can not more then 50 account.',
            'url'=>$urladd,
            'kyc'=>'ok'
          ];

      }elseif($exist == 'do'){
        $cred = $this->_getPayoutDetails();
        //$sign = $this->getSignature();
        $beneficiary = [
              'beneId' => $req['baneId'],
              'name' => $req['name'],
              'email' => $req['email'],
              'phone' => $req['phone'],
              'bankAccount' => $req['cnfAcc'],
              'ifsc' => $req['ifsc'],
              'address1' => $req['brnch'],
              'city' => $req['city'],
              'state' => $req['state'],
              'pincode' => $req['pincode'],
              'user'=>$this->session->userdata('gameuser')
          ];
        $token = $this->_getToken($cred->appid,$cred->secretkey,$cred->mode);
        $this->_fetchBeni($token,$beneficiary,$cred->mode);
        return $this->_addBeneficiary($token,$beneficiary,$cred->mode);  
      }else{
        $urladd = base_url().'api/add-benificery';
        return [
            'kycTitle'=>'Server Error',
            'kycmsg'=>'Sorry! something went wrong',
            'url'=>$urladd,
            'kyc'=>'ok'
          ];
      }
      
  }
  
  protected function _protectedFunctionAccountExistInDB($id,$account){
    $count = $this->db->where('bankAccount',$account)->select('bankAccount,user')->get('accounts')->result_array();
    if(!empty($count)){

      $i=1;

      foreach ($count as $row) {
        $i++;
      }
      if($i <= 50){

          return 'ok';
                      
      }else{
        return 'no';
      }
    }else{
      return 'do';
    }
    
  }
  public function withdrawPageFuntionTransaction(){
         echo json_encode($this->_transactionTransferAccount($_POST));
  }
  
  protected function _transactionTransferAccount($req){
    $st = $this->db->select('payment')->get('admin')->row('payment');
    if($st == '1'){
      $statusS = $this->_amountCheckDefaultStatus($req['amount']);
         if($statusS == 'ok'){
          $default = $this->db->select('default')->from('admin')->get()->row('default');
          if($req['amount'] < $default){
              return [
                  'a'=>'Amount Will be greater then Default Amount.'
                  ];
          }else{
              $limit = $this->db->select('limit')->limit('1')->get('admin')->row('limit');
                if($req['amount'] <= $limit){
                  $trs =  $this->db->where('user',$this->session->userdata('gameuser'))
                                          //->where('gate','app')    
                                          ->where('dr_cr=','Dr')
                                          ->where('order !=','Join Megacontest')
                                          ->where('order !=','Upgrade Account')
                                          ->select('t_amt')
                                          ->from('transaction')
                                          ->get()->result();  
                            $txn = 0;
                            
                            foreach ($trs as $key) {
                                $txn += $key->t_amt;
                            }
                        $wallet = $this->db->where('user',$this->session->userdata('gameuser'))->select('amount')->get('wallet')->row('amount');
                        if($trs > $wallet){
                          $left_bus = $wallet-$txn;
                        }else{
                            $left_bus = 0;        
                           
                        }  
                    if($req['amount'] <= $left_bus){

                        return $this->_againProtectedFunction($req['amount']);
                    }else{

                       
                    return [
                        'kycTitle'=>'Insufficient Balance',
                        'kycmsg'=>'You have no balance.',
                        'kyc'=>'ok'
                    ];
                    }

                }else{
                   return [
                            'kycTitle'=>'LIMIT EXIST',
                            'kycmsg'=>'You can not withdraw more then 50,000 Rs.',
                            'kyc'=>'ok'
                        ];
                }
          }
        }else{

          return [
                        'kycTitle'=>'Amount in multiple '.$statusS,
                        'kycmsg'=>'You can withdrow money in multiples of 100.',
                        'kyc'=>'ok'
                    ];
        }}elseif($st == '0'){
          return [
            'kycTitle'=>'Withdraw Closed',
            'kycmsg'=>'Withdraw has been closed Withdraw has been open in 10:00 AM & Close 6:00 PM. ',
            'kyc'=>'ok'
          ];
      }
  }
  
  protected function _amountCheckDefaultStatus($req){
      


        $multiple = 100;

      for($i=0; $i <= 50000; $i++){
          $total = $i *$multiple;
           if($total == $req){
            return  'ok';
            break;
           }
      }
  }

  protected function _againProtectedFunction($req){
         $beneId = $this->db->where('user',$this->session->userdata('gameuser'))->select('beneId,status')->from('accounts')->get()->row();
         $kyc = $this->db->where('userid',$this->session->userdata('gameuser'))->select('status')->from('kyccomplete')->get()->row('status');
      $intreset = $this->db->select('interest')->from('admin')->get()->row('interest');
      $cred = $this->_getPayoutDetails();
      if(empty($beneId)){
          return ['er'=>'Account Does not exist, please add account.'];
      }elseif($beneId->status != 'SUCCESS'){
          return ['er'=>'Account Not Added.'];
      }else{

          if($kyc == 2){
                $finalAmt = $req - $req * $intreset/100;
                $transfer = [
                    'beneId' => $beneId->beneId,
                    'amount' => $finalAmt,
                    'transferId' => strtotime(date('Y-m-d h:i:s')).''.substr($this->session->userdata('gameuser'),2,5)
                ];
                
                $token = $this->_getToken($cred->appid,$cred->secretkey,$cred->mode);
                return $this->_requestTransfer($token,$transfer,$cred->mode,$intreset,$req);
            }else{
                return [
                    'kycTitle'=>'KYC NOT COMPLETED',
                    'kycmsg'=>'please complete your kyc click to <a href="'.base_url().'api/complete-kyc">complete your kyc</a>',
                    'kyc'=>'ok'
                ];
            }
      }
      
  }
  
  protected function _requestTransfer($token,$transfer,$mode,$intreset,$req){
      if($mode == 'PROD'){
          $url='https://payout-api.cashfree.com/';
      }else{
          $url='https://payout-gamma.cashfree.com/';    
      }
      
        $ch = curl_init($url.'payout/v1/requestTransfer');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transfer));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$token.'',
            'Content-Type: application/json'
        ]);
    
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            return ['post'=>'error in posting','mainerror'=>curl_error($ch)];
            die();
        }
        curl_close($ch);
        $rObj = json_decode($result,true);
        $data = [
          'user' => $this->session->userdata('gameuser'),
          'txn' =>  $transfer['transferId'],
          't_amt' =>  $req,
          'interest' =>  $intreset,
          'order' =>  'Withdraw Amount',
          'gate'=>'app',
          'refrence'=>$rObj['data']['referenceId'],
          'dr_cr'=>'Dr',
          'timestamp'=>date('Y-m-d h:i:s A'),
          'method'=>'Bank',
          'status'=>$rObj['status']
        ];
        $noti = [
            'user'=>$this->session->userdata('gameuser'),
            'type'=>'PAYMENT '.$rObj['status'],
            'msg'=> ''.$rObj['message'].' '.$req.' Rs. your refrence id: #'.$rObj['data']['referenceId'].'',
            'created_at'=>date('Y-m-d h:i:s')
            ];
        $this->_updateNotify($noti);
        $field = array(
            "sender_id" => "SMSIND",
            "language" => "english",
            "route" => "qt",
            "numbers" => $this->session->userdata('phone'),
            "message" => "27375",
            "variables" => "{#BB#}",
            "variables_values" =>"$req"
        );
        forget($field);
        $this->db->insert('transaction',$data);
        return $data =['succ'=>$rObj['subCode'],'msg'=>$rObj['message'],'status'=>$rObj['status'],'refrence'=>$rObj['data']['referenceId']];
    }
  
  
  protected function _addBeneficiary($token,$beneficiary,$mode){
      if($mode == 'PROD'){
          $url='https://payout-api.cashfree.com/';
      }else{
          $url='https://payout-gamma.cashfree.com/';    
      }
      $data = [
            'beneId' => $beneficiary['beneId'],
            'name' => $beneficiary['name'],
            'email' => $beneficiary['email'],
            'phone' => $beneficiary['phone'],
            'bankAccount' => $beneficiary['bankAccount'],
            'ifsc' => $beneficiary['ifsc'],
            'address1' => $beneficiary['address1'],
            'city' => $beneficiary['city'],
            'state' => $beneficiary['state'],
            'pincode' => $beneficiary['pincode']
        ];
        $final  = $url.'payout/v1/addBeneficiary';
        $ch = curl_init($final);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$token.'',
            'Content-Type: application/json'
        ]);
    
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            return ['post'=>'error in posting','mainerror'=>curl_error($ch)];
            die();
        }
        curl_close($ch);
        $rObj = json_decode($result,true);
        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $data = ['errorcode'=>$rObj['subCode'],'msg'=>$rObj['message']];
        $this->db->insert('accounts',$beneficiary);
        $this->db->where('user',$this->session->userdata('gameuser'))
                 ->set('status',$rObj['status'])
                 ->update('accounts');
        $name = $this->session->userdata('name');         
        $field = array(
            "sender_id" => "SMSIND",
            "language" => "english",
            "route" => "qt",
            "numbers" => $this->session->userdata('phone'),
            "message" => "29329",
            "variables" => "{#DD#}",
            "variables_values" =>"$name"
        );
        forget($field);         
        return $data =['succ'=>$rObj['subCode'],'msg'=>$rObj['message'],'status'=>$rObj['status']];
        
    }
  
  protected function _fetchBeni($token,$beneficiary,$mode){
      
      if($mode == 'PROD'){
          $url='https://payout-api.cashfree.com/';
      }else{
          $url='https://payout-gamma.cashfree.com/';    
      }
        $final   = $url.'payout/v1/getBeneficiary/'.$beneficiary["beneId"];
        
        $ch = curl_init($final);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$token.'',
            'Postman-Token: 59c129af-c109-482a-bb6f-6fc18abde21d',
            'Content-Type: application/json'
        ]);
    
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            return ['post'=>'error in posting','mainerror'=>curl_error($ch)];
            die();
        }
        curl_close($ch);
        $rObj = json_decode($result,true);
        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $data = ['errorcode'=>$rObj['subCode'],'msg'=>$rObj['message']];
        return $data =['succ'=>$rObj['subCode'],'msg'=>$rObj['message'],'status'=>$rObj['status']];
    
    }
  
    
    
  protected function _getPayoutDetails(){
      return $this->db->select('appid,secretkey,mode')
                      ->where('type','payout')
                        ->from('credential')
                        ->get()->row();
  }
     
    function do_kycUpgradeFunction(){

        $this->session->set_userdata('heading','KYC');
      $this->load->view('game/include/up');
      $this->load->view('game/kycform');
      $this->load->view('game/include/down');
    }

    function completeKycFinal(){
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('dob','Date of birth','required');
        $this->form_validation->set_rules('pan','Pancard no','trim|required|max_length[12]');//|is_unique[kyccomplete.panno]
        $this->form_validation->set_rules('userid','userid no','trim|required|max_length[12]');//|is_unique[kyccomplete.userid]
        //$this->form_validation->set_rules('panp[]','Attached proof','required');
        //$this->form_validation->set_rules('addhrf','Aadhaarcard Front Side','required');
        //$this->form_validation->set_rules('addhrb','Aadhaarcard Back Side','required');
        if($this->form_validation->run()){
            $data = array();
            if (!empty($_FILES['panp']['name'])) {
                $filesCount = count($_FILES['panp']['name']);
            
                for ($i = 0; $i < $filesCount; $i++) {
                    
                    $_FILES['uploadFile']['name'] = str_replace(",","_",$_FILES['panp']['name'][$i]);
                    $_FILES['uploadFile']['type'] = $_FILES['panp']['type'][$i];
                    $_FILES['uploadFile']['tmp_name'] = $_FILES['panp']['tmp_name'][$i];
                    $_FILES['uploadFile']['error'] = $_FILES['panp']['error'][$i];
                    $_FILES['uploadFile']['size'] = $_FILES['panp']['size'][$i];
                    //Directory where files will be uploaded
                    if (!file_exists('uploads/'.$this->session->userdata('gameuser').'')) {
                        mkdir('uploads/'.$this->session->userdata('gameuser').'', 0755, true);
                        }   

                    $uploadPath = 'uploads/'.$this->session->userdata('gameuser').'/';
                    $config['upload_path'] = $uploadPath;
                    // Specifying the file formats that are supported.
                    $config['allowed_types'] = 'jpg|jpeg';
                    $config['max_size']      = '2048';
                    $config['detect_mime']   = TRUE;
                    $config['encrypt_name']  = TRUE;
                    $config['remove_spaces'] = TRUE;
                    $config['max_filename']  = 0;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('uploadFile')) {
                        $fileData = $this->upload->data();
                        $uploadData[$i]['file_name'] = $fileData['file_name'];
                    }
                }
                if (!empty($uploadData)) {
                    $list=array();
                    foreach ($uploadData as $value) {
                        array_push($list,$value['file_name']);
                    }
                    //$this->db->insert_batch('kyc',$list);
                    $fi = [
                        'userid'=>$_POST['userid'],
                        'panno'=>$_POST['pan'],
                        'panname'=>$_POST['name'],
                        'panimg'=>$list[0],
                        'dob'=>$_POST['dob'],
                        'created_at'=>date('Y-m-d h:i:s'),
                        'status'=>'1'
                    ];
                    $this->db->insert('kyccomplete',$fi);
                    $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "KYC PENDING TO APROVAL",
                          content: "Thank you your kyc send to admin for aproval.",
                          type: "red",
                          keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  action: function() {
                                      window.location.href="'.base_url().'api/account-profile";
                                  }
                              }
                          }
                      });');
                redirect(base_url().'api/complete-kyc'); 
                }else{
                    $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "KYC ERROR",
                          content: "Image type Jpg,Jpeg reuired.",
                          type: "red",
                          keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'api/complete-kyc'); 
                }
            

            }else{
                
                $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "KYC ERROR",
                          content: "No Files Selected",
                          type: "red",
                          keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'api/complete-kyc');   
            }
            
        }else{
            $data = [
                'a'=>form_error('name'),
                'p'=>form_error('pan'),
                'u'=>form_error('userid'),
                'c'=>form_error('dob'),
                //'f'=>form_error('addhrf'),
                //'b'=>form_error('addhrb')
            ];
            $this->session->set_flashdata($data);
            redirect(base_url().'api/complete-kyc');   
            
        }  
    }

    protected function _fetchuserDetailsSave($id){
        return $this->db->where('member_id',$id)
                  ->select('member_id,phone,email')
                  ->get('login')->row();
    }
    
    function viewALldataToMEFunction(){
        $this->session->set_userdata('heading','YOUR TEAM');
        $this->load->view('game/include/up');
        $this->load->view('game/joinalldata');
        $this->load->view('game/include/down');  
    }   
    
    function settingAPPFunction(){
        $this->session->set_userdata('heading','SETTING');
        $this->load->view('game/include/up');
        $this->load->view('game/setting');
        $this->load->view('game/include/down');  
    }
  
}
  ?>