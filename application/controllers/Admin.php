<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
  public function __construct()
  {
      parent::__construct();
      
        $this->load->model('register');
        $this->_loginIf();    
        date_default_timezone_set('Asia/Kolkata');

  }
  
  function test(){
      
  }
  
  function admin_bank_deleteme(){
      
      if($_GET['q'] && $_GET['c']){
          
          
            $cred = $this->_getPayoutDetails();
            $token = $this->_getToken($cred->appid,$cred->secretkey,$cred->mode);  
            $data['data'] = $this->_removeBene($token,$_GET['q'],$cred->mode);
            $data['db'] = $this->db->where('beneId',$_GET['q'])->where('user',$_GET['c'])->delete('accounts');   
            
            $this->load->view('super_admin/include/header');
                $this->load->view('super_admin/statusDel',$data);
                $this->load->view('super_admin/include/footer'); # code...
            
          } else{
          
                redirect(base_url().'admin/manage-account?q='.$_GET['c'].'');  
            }
      
  }
  
  protected function _ftechBeneid($bene,$user){
    return $this->db->where('beneId',$bene)->where('user',$user)->select('*')->get('accounts')->result();   
  }
  
  
  protected function _removeBene($token,$bene,$mode){
           if($mode == 'PROD'){
               $url='https://payout-api.cashfree.com/';
           }else{
               $url='https://payout-gamma.cashfree.com/';    
           }
           
           $data = [
              'beneId'=>$bene
              ];
              
              
            $ch = curl_init($url.'payout/v1/removeBeneficiary');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$token,
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
            return $rObj;
            die;
            if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') return $rObj['message'];
            return $data = ['code'=>$rObj['data']['subCode'],'message'=>$rObj['data']['availableBalance']];
      }

  function openbanknDetailsfunction(){
        if($_GET['q']){
            
            $bene = $this->_ftechBene($_GET['q']);
            if(!empty($bene)){
                $data['bene'] = $this->_ftechBene($_GET['q']);
                $this->load->view('super_admin/include/header');
                $this->load->view('super_admin/showBank',$data);
                $this->load->view('super_admin/include/footer'); # code...
            }else{
                
            $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Benefecery",
                          content: "May be benefiecery has been deleted ",
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
                redirect(base_url().'admin/all-bank-accounts');   
            }
        } else{
            
                redirect(base_url().'admin/all-bank-accounts');   
        } 
  }
  
  protected function _ftechBene($req){
      return $this->db->where('beneId',$req)->select('*')->get('accounts')->result();
  }

  function fetchBankFunctoion(){
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/accounts');
        $this->load->view('super_admin/include/footer'); # code...
  }
  
  function admin_debit_creditFunction(){
  if($_GET['q']){
    $exist = $this->db->where('member_id',$_GET['q'])->select('email')->get('login')->row();
    if(!empty($exist)){
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/debit_credit_page');
        $this->load->view('super_admin/include/footer'); # code...
    }else{
      redirect(base_url().'admin/all-user');
    }
  }else{
    redirect(base_url().'admin/all-user');
  }
   
  }

  function admin_add_debit_creditFunction(){
    $this->load->helper('security');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('userid','user id','trim|required');
    $this->form_validation->set_rules('wallet','wallet','required');
    $this->form_validation->set_rules('crDr','Amount','required');
    $this->form_validation->set_rules('remarks','Remarks','required');
    $this->form_validation->set_rules('drOpt','select Option','required');
    if($this->form_validation->run()){
        echo json_encode($this->_detailsMatchNow($_POST));
    
    }else{
      $array = array(
          'a' => form_error('userid'),
          'b' => form_error('wallet'),
          'c' => form_error('crDr'),
          'd' => form_error('remarks'),
          'e'=>form_error('drOpt')
        );
        echo json_encode($array);
    } 
  }


  protected function _previousDataBalance($req){
    return $this->db->where('user',$req)->select('wallet')->get('gamelogin')->row('wallet');
  }

  protected function _detailsMatchNow($req){
    $rWallet = $this->_protechedfetchGameWallet($req['userid']);
    //if($req['crDr'] <= $rWallet){
      $txn = strtotime(date('Y-m-d h:i:s')).substr($req['userid'],2,4);
      if($req['drOpt'] == 'Cr'){

              $data = [
                  'user' => $req['userid'],
                  'txn' =>  $txn,
                  't_amt' => $req['crDr'],
                  'interest' => '0',
                  'order' =>$req['remarks'],
                  'status'=>'SUCCESS',
                  'method'=>'Admin',
                  'gate'=>'Site',
                  'timestamp'=>date('Y-m-d h:i:s A'),
                  'dr_cr'=>$req['drOpt']
                ];

          $updateGamlogin = $req['crDr'] + $this->_previousDataBalance($req['userid']);        

          $this->db->insert('transaction',$data);
          $this->db->where('user',$req['userid'])
                   ->set('wallet',$updateGamlogin)
                   ->update('gamelogin');
          return [
                    'ok'=>'ok',
                    'msg'=>'Your Transaction has been saved'
                ];         

      }elseif ($req['drOpt'] == 'Dr') {
              $data = [
                  'user' => $req['userid'],
                  'txn' =>  $txn,
                  't_amt' => $req['crDr'],
                  'interest' => '0',
                  'order' =>$req['remarks'],
                  'status'=>'SUCCESS',
                  'method'=>'Admin',
                  'timestamp'=>date('Y-m-d h:i:s A'),
                  'gate'=>'Site',
                  'dr_cr'=>$req['drOpt']
                ];

                $this->db->insert('transaction',$data);
                return [
                    'ok'=>'ok',
                    'msg'=>'Your Transaction has been saved'
                ];
          }
        
      
    /*}else{
      return [
        'er'=>'ok',
        'title'=>'Amount error',
        'msg'=>'Sorry! Amount Balance high more then Wallet Balance'
      ];
    }*/
  }

  protected function _protechedfetchGameWallet($id){
    $trs =  $this->db->where('user',$id)
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
                                        $wallet = $this->db->where('user',$id)->select('amount')->get('wallet')->row('amount');
                                        if($trs > $wallet){
                                          $left_bus = $wallet-$txn;
                                        }else{
                                            $left_bus = 0;        
                                           
                                        }

                                        return $left_bus;
  }
  function winners_functionTo_GetData(){
      $id=1216;
      $winnerlist=1;
      $wl='Desc';
      $data['re'] = $this->_fetchAllResults($id,$winnerlist,$wl);
      $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/winnersAnnouce',$data);
        $this->load->view('super_admin/include/footer'); # code...
  }
  
  
  function SendTo_WinnersFunction(){
      echo json_encode($this->_creatWinnerIncome($_POST['variant_attribute'],$_POST['user'],$_POST['id']));
  }
  protected function _creatWinnerIncome($req,$user,$id){
      $data = [];
      for($i=0;$i<count($req); $i++){
          for($i=0;$i<count($user); $i++){
              for($i=0;$i<count($id); $i++){
                  $data[] =[
                      'user'=>$user[$i],
                      'amount'=>$req[$i],
                      'tournament'=>$id[$i]
                      ];
                }     
          }
      }
      
      for($i=0; $i<count($data);$i++){
          
          $this->db->insert('winners',$data[$i]);
                        
         $this->db->where('user',$data[$i]['user'])
                        ->set('status','1')
                        ->update('scoreboard');       
          
          $txn = [
                  'user' => $data[$i]['user'],
                  'txn' => strtotime(date('Y-m-d h:i:s')),
                  't_amt' => $data[$i]['amount'],
                  'interest' => '0',
                  'order' =>'Winnings',
                  'status'=>'Done',
                  'method'=>'Account',
                  'gate'=>'app',
                  'dr_cr'=>'Cr'
                ];
                
            $noti = [
                  'user' => $data[$i]['user'],
                  'type' => 'TOURNAMENT WIN',
                  'msg' => 'hurry! you win tournament, you winning '.$data[$i]['amount'].' Rs. from this tournment id: '.$data[$i]['tournament'].'',
                  'created_at' => date('Y-m-d h:i:s')
                ];
            
            $this->db->insert('notification',$noti);
            
          $this->db->insert('transaction',$txn);                     
          
      }
      
      
      return [
          'ok'=>'winners announced successfully and price will be sent to user account.'
          ];   
  }
  
  protected function _fetchPerivousWalletAmount($id){
      return $this->db->where('user',$id)->select('amount')->from('wallet')->get()->row('amount');
  }
  function secondWinnerdata(){
      echo json_encode($this->_protectedFunctionGETWinner($_POST));
  }
  
  protected function _protectedFunctionGETWinner($req){
      $out='';
          $re = $this->_fetchAllResults($req['gn'],$req['rank'],$req['wl']);
          $out.='<table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tournament ID</th>
                                <th>Game ID</th>
                                <th>User ID</th>
                                <th>Score</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>';
                            if(!empty($re)){ $i=0; foreach($re as $row){ ++$i;
                            $out.='<tr>
                                <td>'.$i.'</td>
                                <td><input type="text" readonly value="'.$row->game_id.'" name="id[]" class="form-control" placeholder="Amount" style="border-radius:35px 35px 35px 35px; border:1px solid #000;"></td>
                                <td>'.$this->db->where('id',$row->gameRealId)->select('name')->get('game')->row('name').'</td>
                                <td><input type="text" readonly value="'.$row->user.'" name="user[]" class="form-control" placeholder="userid" style="border-radius:35px 35px 35px 35px; border:1px solid #000;"></td>
                                <td><span class="badge badge-success">'.$row->score.'</span></td>
                                <td><input type="text" name="priceUpdate[]" class="form-control" placeholder="Amount" style="border-radius:35px 35px 35px 35px; border:1px solid #000;"></td>
                            </tr>';
                             } } else{ 
                            $out.='<tr>
                                <td colspan="5" class="text-center">No data Found</td>
                            </tr>';
                             } 
                        $out.='</tbody>
                    </table>';
      
      return $out;
  }
  
  protected function _fetchAllResults($id,$winnerlist,$wl){
      return $this->db->query("SELECT * FROM scoreboard where status = '0' ORDER BY score $wl LIMIT $winnerlist")->result();  
  }
  function tournamentFunction(){
      if($_GET['q']){
          switch ($_GET['q']) {
          
            case 'add':
                $this->load->view('super_admin/include/header');
                $this->load->view('super_admin/add-game');
                $this->load->view('super_admin/include/footer'); # code...
              break;
            case 'list':
                $data['game'] = $this->_protecetdGamesdata();
                $this->load->view('super_admin/include/header');
                $this->load->view('super_admin/list',$data);
                $this->load->view('super_admin/include/footer'); # code...
              break;
            
            default:
                redirect(base_url().'admin/dashboard');
              break;
          }
        }else{
          redirect(base_url().'admin/dashboard');
        }
  }
  
  protected function _protecetdGamesdata(){
      return $this->db->select('id,name,price,end,img,start,del')->get('game')->result();
  }
  
  function start_gameToFunction(){
      $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('game_id','Game ID','required|trim');
        $this->form_validation->set_rules('game_fees','Tournament price','trim|required|numeric');
        $this->form_validation->set_rules('game_s_date','Tournament start date','required');
        $this->form_validation->set_rules('game_s_time','Tournament start time','required');
        $this->form_validation->set_rules('s_sec','Tournament start seconds','required|trim|numeric');
        $this->form_validation->set_rules('game_date','Tournament end date','required');
        $this->form_validation->set_rules('game_time','Tournament start time','required');
        $this->form_validation->set_rules('sec','Tournament start seconds','required|trim|numeric');
        if($this->form_validation->run()){
            echo json_encode($this->_protecetedDataMangae($_POST));
        }else{
            $array =array(
                'error'   => true,
                'a'=>form_error('game_id'),
                'b'=>form_error('game_fees'),
                'c'=>form_error('game_s_date'),
                'd'=>form_error('game_s_time'),
                'e'=>form_error('s_sec'),
                'f'=>form_error('game_date'),
                'g'=>form_error('game_time'),
                'h'=>form_error('sec')
            );
            
            echo json_encode($array);
        }
  }
  
  protected function _protecetedDataMangae($req){
      $startTime = $req['game_s_date'].' '.$req['game_s_time'].':'.$req['s_sec'];
      $endTime = $req['game_date'].' '.$req['game_time'].':'.$req['sec'];
      $up = [
          'price'=>$req['game_fees'],
          'start'=>$startTime,
          'tourid'=>strtotime(date('Y-m-d h:i:s')),
          'end'=>$endTime,
          'del'=>'1'
          ];
          
      try{
          
          $this->db->where('id',$req['game_id'])
                  ->set($up)->update('game');
          return [
              'ok'=>'Tournament has been started.'
          ];        
      }catch(Exception $e){
            return [
              'er'=>$e->getmessage()
          ];          
      }

  }
  function add_gameFunctionData(){
      $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('g_name','Game name','required|max_length[20]');
        //$this->form_validation->set_rules('g_price','Game price','required|numeric');
        //$this->form_validation->set_rules('hours','hours date','required');
        //$this->form_validation->set_rules('g_start','Start date','required');
        if($this->form_validation->run()){
            try{
              
              $config['upload_path']          = 'Games/game_images/';
                $config['allowed_types']        = 'jpg|png|jpeg';
                //$config['max_size']             = 100;
                //$config['max_width']            = 1024;
                //$config['max_height']           = 768;
                $config['detect_mime']          = TRUE;
                $config['encrypt_name']        = TRUE;
                $config['remove_spaces']        = TRUE;
                $config['max_filename']       = 0;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('g_file'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        $array = array(
                            'er'=>$error
                           );
                           
                        $this->session->set_flashdata($array);
                        redirect(base_url().'admin/tournament?q=add');
                        //$this->load->view('upload_form', $error);
                }
                else
                {       $fileData = $this->upload->data();
                        $uploadData = $fileData['file_name'];
                        $data = array(
                          'name'=>$this->security->xss_clean($this->input->post('g_name')),
                          'img'=>$uploadData
                        );
                        $this->db->insert('game',$data);
                        $game_id = $this->db->insert_id();
                      $this->session->set_flashdata('acc','
                        $.confirm({
                            title: "Tournament Added",
                            content: "You have added tournament successfully<br> Your Game ID: '.$game_id.'",
                            type: "blue",
                            typeAnimated: true,
                                buttons: {
                                    tryAgain: {
                                        text: "Close",
                                        btnClass: "btn-blue",
                                        close: function() {}
                                    }
                                }
                        });');
                    redirect(base_url().'admin/tournament?q=add');
                }
                
              
            
            }catch(Exception $e){
                
            $this->session->set_flashdata('acc','
                $.confirm({
                    title: "DB Error",
                    content: "'.$e->getMessage().'",
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
            redirect(base_url().'admin/tournament?q=add');
            
                
            }
            
        }else{
            $array = array(
                'a' => form_error('g_name'),
                //'b' => form_error('g_price'),
                //'c' => form_error('hours'),
                //'d' => form_error('g_start')
               );
               
            $this->session->set_flashdata($array);
            redirect(base_url().'admin/tournament?q=add');
        }
  }
  
  function rewardsFunction(){
      $data['reward'] = $this->_fetchRewards();
      $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/reward',$data);
        $this->load->view('super_admin/include/footer');
  }
  
  protected function _fetchRewards(){
      return $this->db->select('id,point,name,opt')
                      ->from('reward')
                      ->get()->result();
  }
  
  protected function _loginIf()
  {
    if($this->session->userdata('admin') != '')
      {    

      }
    else{
        $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Login Error",
                          content: "Oops! we can not redirect to dashboard",
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
      redirect(base_url().'admin');
    } 
  }

  function withdraw_onOffOptionFunctionPage(){
      $st = $this->_protectedOnOffPaymentOption($_POST);
      
      if($st == '0'){
           $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Something happen",
                          content: "we can not chnage settings",
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
                redirect(base_url().'admin/dashboard');             
      }elseif($st == '1'){
           $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Changes Saved",
                          content: "your settings has been saved",
                          type: "blue",
                          keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-blue",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'admin/dashboard');             
      }
  }

  protected function _protectedOnOffPaymentOption($req){
      $status = $this->_fetchStatusDefault();
      if ($status == 0) {
        $this->db->where('email',$this->session->userdata('admin_email'))->set('payment',$req['withdrawOnOff'])->update('admin');
        return '1';
      }elseif ($status == 1) {
        $this->db->where('email',$this->session->userdata('admin_email'))->set('payment','0')->update('admin');
        return '1';
      }else{
         return '0';
      }

  }

  protected function _fetchStatusDefault(){
    return $this->db->select('payment')->get('admin')->row('payment');
  }

  function incomeAdminPage(){

    if($_GET['q']){
      switch ($_GET['q']) {
      
        case 'referral':
            $this->load->view('super_admin/include/header');
            $this->load->view('super_admin/direct');
            $this->load->view('super_admin/include/footer'); # code...
          break;
        default:
            redirect(base_url().'admin/dashboard');
          break;
      }
    }else{
      redirect(base_url().'admin/dashboard');
    }
  }

  function direct_income_function(){
      $data = array();
        if(!empty($this->register->directincome()))
        {
        //total rows count
        $totalRec = count($this->register->directincome());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#directData';
        $config['base_url']    = base_url().'directAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'directF';
        $this->ajax_pagination->initialize($config);
        
        $posts = $this->register->directincome(array('limit'=>10));
          echo '
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Amount</th>
                        <th>Referral</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                          echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['user_id'].'</td>
                        <td>₹ '.$row['income'].'</td>
                        <td>'.$row['direct'].'</td>
                        <td>'.$row['date'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="5">
                                <div class="text-center">No Data Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();

  }

  function directAjax(){
      $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->directincome($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->directincome($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#directData';
        $config['base_url']    = base_url().'directAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'directF';
        $this->ajax_pagination->initialize($config);
        //get the posts data
        $posts = $this->register->directincome(array('limit'=>10));
         echo '
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Amount</th>
                        <th>Referral</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                          echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['user_id'].'</td>
                        <td>₹ '.$row['income'].'</td>
                        <td>'.$row['direct'].'</td>
                        <td>'.$row['date'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="5">
                                <div class="text-center">No Data Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
  }


  public function rateFunction(){
      $this->load->view('super_admin/include/header');
      $this->load->view('super_admin/rateview');
      $this->load->view('super_admin/include/footer'); 
  }

  function rateFuntionNew(){
    echo json_encode($this->rateChange($_POST));
  }

  protected function rateChange($data){
      return $this->db->where('email',$this->session->userdata('admin_email'))
                      ->set('interest',$data['rate'])
                      ->set('default',$data['dm'])
                      ->update('admin');


  }
  public function verifyKycFormDone(){
    
    echo json_encode($this->_fetchVerify_now($_POST));
  }

  protected function _fetchVerify_now($data){
    $kyc = $this->_fetchrecord($data['id']);
    $out='';
    $out.='
      <div class="verifyPage">
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="d-flex justify-content-between">
              <div class="close">KYC ID: '.$data['id'].'</div>
                <div class="close"><a href="/admin/user-kyc"><i class="fa fa-window-close"></i></a></div>
              </div>
            </div>
          </div>
          <div class="border-bottom"></div>
          <div class="row mt-2">';
          //foreach ($kyc as $row) {
              $out.='
              <div class="col-md-6">
                  <div class="imgFrame p-2">
                    <div class="verifyNote mt-1">
                        <div class="form-group">
                            <label>PANCARD NO</label>
                            <input type="text" class="form-control" readonly value="'.$kyc->panno.'">
                        </div>
                        <div class="form-group">
                            <label>NAME</label>
                            <input type="text" class="form-control" readonly value="'.$kyc->panname.'">
                        </div>
                        <div class="form-group">
                            <label>Date Of Birth</label>
                            <input type="text" class="form-control" readonly value="'.$kyc->dob.'">
                        </div>
                    </div>
                  </div>
              </div>

              <div class="col-md-6">
                  <div class="imgFrame p-2">
                    <div class="proof">';
                      if($kyc->status == '2')$out.='<div class="verify"><i class="fa fa-shield-alt" id="iconSetStyle"></i><p class="text-center verifyText">Verifyed</p></div>';
                      if($kyc->status == '0')$out.='<div class="reject"><i class="fa fa-shield-alt" id="iconSetStyle"></i><p class="text-center rejectText">Rejected</p></div>';

                      $out.='<img src="/uploads/'.$kyc->userid.'/'.$kyc->panimg.'" class="img-fluid img-responsive" style="width: 100%;height: auto;">
                    </div>
                  </div>
                </div>
              ';
            //}  
    $out.='</div>
          <div class="row mt-3">
            <div class="offset-md-3 col-md-6">
                  <select class="form-control" id="kycerror"  name="kycerror" data-m="'.$kyc->userid.'" data-id="'.$kyc->id.'">
                    <option value="0">Rejected</option>
                    <option value="2">Verifyed</option>
                  </select>
            </div>
          </div>
      </div>';

      return $out;
  }
  protected function _fetchrecord($id){
     return $this->db->where('userid',$id)
                    ->select('id,panname,panno,panimg,dob,status,userid')
                    ->from('kyccomplete')
                    ->get()->row();
   
  }
  public function kyc_page(){
      $this->load->view('super_admin/include/header');
      $this->load->view('super_admin/kyc_page');
      $this->load->view('super_admin/include/footer'); 
  }

  public function verifyKycObject(){
    
    //echo json_encode($this->_getKycAprove($_POST));
    
    print_r($this->_getKycAprove($_POST));
  }

  protected function _getKycAprove($data){

       $this->db->where('id',$data['id'])
                ->where('userid',$data['m'])
              ->set('status',$data['val'])
              ->update('kyccomplete');
       
       return 'You '.$data['val'].' this proof.';
  }

  function kycfunction(){
      $data = array();
        if(!empty($this->register->kyc()))
        {
        //total rows count
        $totalRec = count($this->register->kyc());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#kycUser';
        $config['base_url']    = base_url().'kycAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'checkKyc';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->kyc(array('limit'=>10));    
              echo '
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Kyc Date</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                          echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['userid'].'</td>
                        <td>'.$row['created_at'].'</td>
                        <td>'.$this->_checkKyc($row['userid']).'</td>
                        <td>';
                        if($row['status'] == 2){
                          echo '<a href="javascript:;" data-id="'.$row['userid'].'" id="verifydocs">KYC DONE</a>';
                        }
                        elseif($row['status'] == 1){
                          echo '<a href="javascript:;" data-id="'.$row['userid'].'" id="verifydocs">KYC PENDING</a>';
                        }
                        if($row['status'] == 0){
                          echo '<a href="javascript:;" data-id="'.$row['userid'].'" id="verifydocs">KYC REJECTED</a>';
                        }
                        echo '</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="4">
                                <div class="text-center">No Data Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
        
  }
  function kycAJaxFunction(){
    $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->kyc($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->kyc($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#kycUser';
        $config['base_url']    = base_url().'kycAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'checkKyc';
        $this->ajax_pagination->initialize($config);
           
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->kyc($conditions);
        echo '
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Kyc Date</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                        echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['userid'].'</td>
                        <td>'.$row['created_at'].'</td>
                        <td>'.$this->_checkKyc($row['userid']).'</td>
                        <td>';
                        if($row['status'] == 2){
                          echo '<a href="javascript:;" data-id="'.$row['userid'].'" id="verifydocs">KYC DONE</a>';
                        }
                        elseif($row['status'] == 1){
                          echo '<a href="javascript:;" data-id="'.$row['userid'].'" id="verifydocs">KYC PENDING</a>';
                        }
                        if($row['status'] == 0){
                          echo '<a href="javascript:;" data-id="'.$row['userid'].'" id="verifydocs">KYC REJECTED</a>';
                        }
                        echo '</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="4">
                                <div class="text-center">No Data Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
  }

  protected function _checkKyc($id){
    $data =  $this->db->where('userid',$id)
                    ->select('status')
                    ->from('kyccomplete')
                    ->get()->row('status');  
      
      if($data == 0 ){
        return '<span class="badge badge-warning">Rejected</span>';
      }elseif($data == 1 ){
        return '<span class="badge badge-warning">Pending</span>';
      }if($data == 2 ){
        return '<span class="badge badge-success">Aproved</span>';
      }
}
    
  function index()
  { 
    $cred = $this->_getPayoutDetails();
    $token = $this->_getToken($cred->appid,$cred->secretkey,$cred->mode);  
    $data['bal'] = [];
    if(!isset($token['mainerror'])) {
      $data['bal'] = $this->_getBalance($token,$cred->mode);
    }
    
    $data['sms'] = $this->publicFetchSmsBalance();    
    $this->load->view('super_admin/include/header');
    $this->load->view('super_admin/dashboard',$data);
    $this->load->view('super_admin/include/footer'); 
    
  }
  
  function publicFetchSmsBalance(){
            
            
            $ch = curl_init('https://www.fast2sms.com/dev/wallet');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'authorization: ZjIpM4RgbYwVSluqfGTWi1y5avU3ELNc8hsO6drDteoFzBCAkXu0hrt7PmXjywQeaBcUNM5bWKV3E96l',
                'cache-control: no-cache',
                'Content-Type: application/json'
            ]);
        
            $result = curl_exec($ch);
            return $result;
            if(curl_errno($ch)){
                return ['post'=>'error in posting','mainerror'=>curl_error($ch)];
                die();
            }
            curl_close($ch);
            $rObj = json_decode($result,true);
            return $rObj['wallet'];
            
  }
  protected function _getPayoutDetails(){
      return $this->db->select('appid,secretkey,mode')
                      ->where('type','payout_free')
                        ->from('credential')
                        ->get()->row();
  }
  
  protected function _getBalance($token,$mode){
           if($mode == 'PROD'){
               $url='https://payout-api.cashfree.com/';
           }else{
               $url='https://payout-gamma.cashfree.com/';    
           }
           
            $ch = curl_init($url.'payout/v1/getBalance');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$token,
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
            return $data = ['balance'=>$rObj['data']['balance'],'availableBalance'=>$rObj['data']['availableBalance']];
      }
  
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
      
  function showme()
  {
      
      $now_final_verify  = $this->register->login_verify2($this->uri->segment(3));
      //$this->load->model('main_modal');
      if(!empty($now_final_verify))
      {
        
        $session_data = array(
            'username' =>$this->uri->segment(3),
            'name'=>$now_final_verify->name,
            'user_id'=>$now_final_verify->member_id,
            'email'=>$now_final_verify->email,
            'usrf'=>$now_final_verify->id
            );
        $this->session->set_userdata($session_data);
        redirect(base_url().'my/dashboard');
      }

      else{
        $this->session->set_flashdata('loginerror','<script>
                  Swal.fire({
                      position: "top-end",
                      type: "error",
                      title: "Warning! unauthorized login",
                      showConfirmButton: false,
                      timer: 2000
                    });
                </script>');
        redirect(base_url().'admin/all-user');
      }
  }

  function reject_idFunction(){
    $id = str_replace(' ','',$this->input->post('id')); 
    if(empty($id))
      {
          echo json_encode(['er'=>'Rejeted id required']);
      }
      else{
        $this->_rejectIdFunction($id,$this->input->post($_POST));
        echo json_encode(['ok'=>'Request rejected']);
      }
  }

  protected function _rejectIdFunction($id,$data){
    return $this->db->where('id',$id)->set('status','REJECT')->update('reuest_scratch');
           $this->db->where('txn',$data['txn'])->set('order','Refunded')->update('transaction');                    
  }
  function accept_id()
  {
      $id2 = str_replace(' ','',$this->input->post('id'));
      if(empty($id2))
      {
          echo '12';
      }
      else{
          $rec = $this->register->fetch_request($id2);   
        
          $i=0;
          $st=$rec->number;
          while($i != $st)
          {
          $id = 1234567890;
          $store = substr(str_shuffle($id),0,9);
          $unikq = $this->register->fetch_unik($store);
          if(empty($unikq))
          {
            $data = array(
                  'scratch'=>$store,
                  'package_name'=>$rec->s_name,
                  'price'=>$rec->s_price,
                  'date'=>date('d M, Y h:i:h A'),
                  'usedORnot'=>'unused',
                  'user_id'=>$rec->s_id
                  );
                  $this->db->insert('scratch',$data);
                  
          }
          else{
              $id = 1234567890;
              $store2 = substr(str_shuffle($id),0,10);
              $data = array(
                  'scratch'=>$store2,
                  'package_name'=>$rec->s_name,
                  'price'=>$rec->s_price,
                  'date'=>date('d M, Y h:i:h A'),
                  'usedORnot'=>'unused',
                  'user_id'=>$rec->s_id
                  );
                  $this->db->insert('scratch',$data);
              
          }
                  
                  $i++;
            }
            if($i == $rec->number)
            {
                
                    echo $this->register->update_request($id2);
            }
            else{
                echo '2';
            }
            
      }
  }
  function requestToIdFinal()
  {
      $pack = str_replace(' ','',$this->input->post('pack'));
      $id = str_replace(' ','',$this->input->post('id'));
      $user_id = str_replace(' ','',$this->input->post('user_id'));
      $user = $this->register->fetch_user_scarth($user_id);
      if(empty($user))
      {
          echo '4';
      }
      else{
          if(empty($pack))
      {
          echo '2';
      }
      elseif($id >500 )
      {
          echo '3';
      }
      else{
       $price = $this->register->fetch_package_price($pack);
          
        $total = $price->price * $id;
       $data = array(
           's_id'=>$user_id,
           's_name'=>$pack,
           's_price'=>$price->price,
           's_total_price'=>$total,
           'date'=>date('d-M-Y h:i:s A'),
           'number'=>$id
           );
           echo $this->register->insertId($data);
       
       
      }   
  }
  }
  
  function requestToId()
  {
      $pack = str_replace(' ','',$this->input->post('pack'));
      $id = str_replace(' ','',$this->input->post('id'));
      $user_id = str_replace(' ','',$this->input->post('user_id'));
      $user = $this->register->fetch_user_scarth($user_id);
      if(empty($user))
      {
          echo '4';
      }
      else{
          if(empty($pack))
      {
          echo '2';
      }
      elseif($id >500 )
      {
          echo '3';
      }
      else{
          
          $price = $this->register->fetch_package_price($pack);
          
         echo $price->price * $id;
          
      }    
      }
      
  }


function update_password()
{
    $cur = str_replace(' ','',$this->input->post('cur'));
    $cur_pas = $this->register->fetch_curent_pass($cur);
    if(empty($cur_pas))
    {
        echo '7';
    }
    else{
        $pass = str_replace(' ','',$this->input->post('new_pass'));
        $cnf = str_replace(' ','',$this->input->post('cnf_pass'));
        if($pass == $cnf)
        {
            echo $this->register->update_pass($cnf);
        }
        else{
            echo '9';
        }
        
        
    }
}



// site setting

  function site_setting()
  {
      $this->load->view('super_admin/include/header');
      $this->load->view('super_admin/setting');
      $this->load->view('super_admin/include/footer'); 
  }

  public function favicon_img()
  {
      $f = $this->_avl_favicon();
      $data = array(
          'favicon'=>'<img src="'.base_url().'adminassets/site/'.$f->favicon.'" class="img-fluid">',
          'logo'=>'<img src="'.base_url().'adminassets/site/'.$f->logo.'" class="img-fluid">',
          'loader'=>'<img src="'.base_url().'adminassets/images/loader/'.$f->loader.'" class="img-fluid" width="50%">',
          'default_user'=>'<img src="'.base_url().'adminassets/images/faces/'.$f->default_user.'" class="img-fluid" width="50%">'
          );
          echo json_encode($data);
  }
  private function _avl_favicon()
  {
      return $this->db->select('favicon,logo,loader,default_user')->get('site_setting')->row();
  }
   
  public function site_default_user()
  {
      
              $config['upload_path']          = 'adminassets/images/faces/';
                $config['allowed_types']        = 'png|jpg|jpeg';
                //$config['max_size']             = 1024;
                //$config['min_width']            = 450;
                //$config['min_height']           = 388;
                //$config['max_width']            = 800;
                //$config['max_height']           = 800;
                $config['detect_mime']          = TRUE;
                $config['encrypt_name']        = TRUE;
                $config['remove_spaces']        = TRUE;
                $config['max_filename']       = 0;
                $config['overwrite']            = TRUE;
                $this->load->library('upload', $config);
                
                if ( ! $this->upload->do_upload('default'))
                {
                  if(isset($_FILES['default'])) {
                      echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> '.$this->upload->display_errorrs().'
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                       
                       
                        }
                    }
             else
                {
                        $p_img = $this->_fetch_previous_user_img();
                          if($p_img->default_user && file_exists('adminassets/images/faces/'.$p_img->default_user)){
                              unlink('adminassets/images/faces/'.$p_img->default_user);
                          }
                        
                        $uploadimage = $this->upload->data();
                        $favicon = array(
                            'default_user'=>$uploadimage['file_name']
                            );
                        if($this->_upload_user_default($favicon))
                        {
                          echo '
                              <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <strong>Success!</strong> Default user has been saved successfully.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                      else{
                          echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> Oops! we can not able this time.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                        
                }
  
  }
  private function _upload_user_default($favicon)
  {
      return $this->register->upload_logo($favicon);
  }
  
  public function upload_logo_img()
  {
      
              $config['upload_path']          = 'adminassets/site/';
                $config['allowed_types']        = 'png|jpg|jpeg';
                //$config['max_size']             = 1024;
                //$config['min_width']            = 450;
                //$config['min_height']           = 388;
                //$config['max_width']            = 300;
                //$config['max_height']           = 300;
                $config['detect_mime']          = TRUE;
                //$config['encrypt_name']        = TRUE;
                $config['remove_spaces']        = TRUE;
                $config['max_filename']       = 0;
                $config['overwrite']            = TRUE;
                $this->load->library('upload', $config);
                
                if ( ! $this->upload->do_upload('logo'))
                {
                  if(isset($_FILES['logo'])) {
                      echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> '.$this->upload->display_errorrs().'
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                       
                       
                        }
                    }
             else
                {
                        $p_img = $this->_fetch_previous_logo();
                      if($p_img->logo && file_exists('adminassets/site/'.$p_img->logo))
                          {
                              unlink('adminassets/site/'.$p_img->logo);
                            }
                        
                        $uploadimage = $this->upload->data();
                        $favicon = array(
                            'logo'=>$uploadimage['file_name']
                            );
                        if($this->_upload_logo($favicon))
                        {
                          echo '
                              <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <strong>Success!</strong> Logo has been saved successfully.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                      else{
                          echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> Oops! we can not able this time.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                        
                }
  
  }
  private function _upload_logo($favicon)
  {
      return $this->register->upload_logo($favicon);
  }
  
  public function loader_update()
  {
                $config['upload_path']          = 'adminassets/images/loader/';
                $config['allowed_types']        = 'gif';
                //$config['max_size']             = 2024;
                //$config['min_width']            = 450;
                //$config['min_height']           = 388;
                //$config['max_width']            = 800;
                //$config['max_height']           = 800;
                $config['detect_mime']          = TRUE;
                $config['encrypt_name']        = TRUE;
                $config['remove_spaces']        = TRUE;
                $config['max_filename']       = 0;
                $config['overwrite']            = TRUE;
                $this->load->library('upload', $config);
                
                if ( ! $this->upload->do_upload('loaderUpdate'))
                {
                  if(isset($_FILES['loaderUpdate'])) {
                      echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> '.$this->upload->display_errorrs().'
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                       
                       
                        }
                    }
             else
                {
                        $p_img = $this->_fetch_previous_loader();
                      if($p_img->loader && file_exists('adminassets/images/loader/'.$p_img->loader))
                          {
                              unlink('adminassets/images/loader/'.$p_img->loader);
                            }
                        
                        $uploadimage = $this->upload->data();
                        $favicon = array(
                            'loader'=>$uploadimage['file_name']
                            );
                        if($this->_loader_update($favicon))
                        {
                          echo '
                              <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <strong>Success!</strong> Loader has been saved successfully.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                      else{
                          echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> Oops! we can not able this time.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                        
                }
  }
  
  private function _loader_update($favicon)
  {
      return $this->register->upload_favicon($favicon);
  }
  
  public function upload_favicon_img()
  {

                $config['upload_path']          = 'adminassets/site/';
                $config['allowed_types']        = 'png';
                //$config['max_size']             = 1024;
                //$config['min_width']            = 450;
                //$config['min_height']           = 388;
                //$config['max_width']            = 300;
                //$config['max_height']           = 300;
                $config['detect_mime']          = TRUE;
                $config['encrypt_name']        = TRUE;
                $config['remove_spaces']        = TRUE;
                $config['overwrite']            = TRUE;
                $config['max_filename']       = 0;
                $this->load->library('upload', $config);

                
                if ( ! $this->upload->do_upload('favicon'))
                {
                  if(isset($_FILES['favicon'])) {
                      echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> '.$this->upload->display_errorrs().'
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                       
                       
                        }
                    }
             else
                {
                        $p_img = $this->_fetch_previous_img();
                      if($p_img->favicon && file_exists('adminassets/site/'.$p_img->favicon))
                          {
                              unlink('adminassets/site/'.$p_img->favicon);
                            }
                        
                        $uploadimage = $this->upload->data();
                        $favicon = array(
                            'favicon'=>$uploadimage['file_name']
                            );
                        if($this->_upload_favicon($favicon))
                        {
                          echo '
                              <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <strong>Success!</strong> Favicon has been saved successfully.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                      else{
                          echo '
                              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                  <strong>Error!</strong> Oops! we can not able this time.
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                          ';
                      }
                        
                }
  }
  
  private function _upload_favicon($favicon)
  {
      return $this->register->upload_favicon($favicon);
  }
  private function _fetch_previous_logo()
  {
      return $this->db->select('logo')->get('site_setting')->row();
  }
  private function _fetch_previous_img()
  {
      return $this->db->select('favicon')->get('site_setting')->row();
  }
  private function _fetch_previous_user_img()
  {
      return $this->db->select('default_user')->get('site_setting')->row();
  }
  private function _fetch_previous_loader()
  {
      return $this->db->select('loader')->get('site_setting')->row();
  }
  public function update_title()
  {
      $title = $this->security->xss_clean($this->input->post('title'));
      if(empty($title))
      {
           echo json_encode('
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Error!</strong> Site title required.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
          ');
      }
      else{
          $title = array(
              'title'=>$this->security->xss_clean($this->input->post('title'))
          );
          
          if($this->_update_title($title))
      {
          echo json_encode('
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success!</strong> Title has been saved successfully.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
          ');
      }
      else{
          echo json_encode('
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Error!</strong> Oops! we can not able this time.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
          ');
      }
          
      }
  }
  private function _update_title($title)
  {
      return $this->register->update_title($title);
  }
  public function fetch_title()
  {
      $data = $this->_fetch_title();
      $title = array(
          'title'=>$data->title
          );
    echo json_encode($title);     
  }
  private function _fetch_title()
  {
      return $this->db->select('title')->get('site_setting')->row();
  }
  public function fetch_seo()
  {
      $data = $this->_fetch_seo();
      $seo = array(
          'author'=>$data->meta_author,
          'desc'=>$data->meta_desc,
          'key'=>$data->meta_keyword
          );
    echo json_encode($seo);     
  }
  private function _fetch_seo()
  {
      return $this->db->select('meta_author,meta_desc,meta_keyword')->get('site_setting')->row();
  }
  public function seo_done()
  {
      $seo = array(
      'meta_author'=> $this->security->xss_clean($this->input->post('author')),
      'meta_desc' => $this->security->xss_clean($this->input->post('m_desc')),
      'meta_keyword' => $this->security->xss_clean($this->input->post('keywords'))
      );
      if($this->_update_seo($seo))
      {
          echo json_encode('
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success!</strong> Seo has been saved successfully.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
          ');
      }
      else{
          echo json_encode('
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>Error!</strong> Oops! we can not able this time.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
          ');
      }
      
  }
  private function _update_seo($seo)
  {
      return $this->register->update_seo($seo);
  }

    
  function deactiveFunctionPage(){
      echo json_encode($this->_protectedFunctionDeactiveUser($_POST));
  }    
  
  protected function _protectedFunctionDeactiveUser($req){
      if(empty($req['id'])){
          return ['er'=>'id required to deactive member'];
      }else{
        $this->db->where('member_id',$req['id'])
                   ->set('status','2')
                   ->update('login');
        $this->db->where('user_id',$req['id'])->set('status','2')->update('makebinary');       
        $this->db->where('user',$req['id'])->set('status','2')->update('gamelogin');         
        return ['ok'=>'Memeber has been De-active'];           
      }
  }
  
  function activePageFunction(){
      echo json_encode($this->_protectedFunctionactiveUser($_POST));
  }    
  
  protected function _protectedFunctionactiveUser($req){
      if(empty($req['id'])){
          return ['er'=>'id required to active member'];
      }else{
          $this->db->where('member_id',$req['id'])
                   ->set('status','0')
                   ->update('login');
            $this->db->where('user_id',$req['id'])->set('status','0')->update('makebinary');              
          $this->db->where('user',$req['id'])->set('status','1')->update('gamelogin');                  
        return ['ok'=>'Mmeber has been Active'];           
      }
  }
  

  function all_user_show()
  {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/all_userShow');
        $this->load->view('super_admin/include/footer');    
      
  }

  function blockFunctionPage(){
    echo json_encode($this->_blockUserFunction($_POST));
  }

  function unblockPageFunction(){
    echo json_encode($this->_UnblockUserFunction($_POST));
  }

  protected function _UnblockUserFunction($id){
    if(empty($id['id'])){
      return [
        'er'=>'block id required'
      ];
  }else{
      if(!empty($this->_fetchExistUserBlick($id['id']))){
          $this->db->where('member_id',$id['id'])->set('status','0')->update('login');
          $this->db->where('user_id',$id['id'])->set('status','0')->update('makebinary');
          $this->db->where('user',$id['id'])->set('status','1')->update('gamelogin');
          return [
            'ok'=>'user has been Unblocked'
          ];
      }else{
        return [
          'er'=>'user not exist'
        ];
      }
    }
  }

  protected function _blockUserFunction($id){
      if(empty($id['id'])){
          return [
            'er'=>'block id required'
          ];
      }else{
          if(!empty($this->_fetchExistUserBlick($id['id']))){
              $this->db->where('member_id',$id['id'])->set('status','3')->update('login');
              $this->db->where('user_id',$id['id'])->set('status','3')->update('makebinary');
              $this->db->where('user',$id['id'])->set('status','3')->update('gamelogin');
              return [
                'ok'=>'user has been blocked'
              ];
          }else{
            return [
              'er'=>'user not exist'
            ];
          }
      }
  }
  protected function _fetchExistUserBlick($id){
    return $this->db->where('member_id',$id)->select('member_id')->get('login')->row();
  }

  function all_userDataPagination()
  {
    $data = array();
        if(!empty($this->register->fetch_all_member_records()))
        {
        //total rows count
        $totalRec = count($this->register->fetch_all_member_records());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#allUserHtmlData';
        $config['base_url']    = base_url().'allUserPagnet/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'allUserPag';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->fetch_all_member_records(array('limit'=>10));
        echo '

          <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member Id</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Package</th>
                        <th>Through</th>
                        <th>Activation</th>
                        
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>';
                        if(!empty($posts)){$i=1; foreach($posts as $row){
                            echo '<tr>
                                <td>'.$i.'</td>
                        <td><span class="badge bg-dark text-white"><i class="fa fa-user-alt"></i> '.$row['member_id'].'</span></td>
                        <td>'.$row['name'].'</td>
                        <td><span class="badge bg-dark text-white"><i class="fa fa-phone"></i> '.$row['phone'].'</span></td>

                        <td>'.$row['package_name'].' (&#8377; '.$row['package_amt'].'-/)</td>

                        <td><span class="badge bg-info">'; $s = $this->db->where('user',$row['member_id'])->where('order','Join Megacontest')->select('method')->get('transaction')->row('method'); if(!empty($s)){ echo $s;}else{echo 'admin added';} echo '</span></td>
                        
                        <td><span class="badge bg-dark text-white">'.$row['activation'].'</span></td>

                        <td>
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="d-flex justify-content-between  bg-light p-2">
                                    
                                    <div>
                                      <a data-toggle="tooltip" data-placement="bottom" title="Daily Payout On/Off" class="text-dark" href="'.base_url().'admin/daily-payout?q='.$row['member_id'].'&c='; if($row['daily_payout'] == 1) {echo '0';} elseif($row['daily_payout'] == 0){echo '1';} echo '" target="_blank">';
                                      if($row['daily_payout'] == 0){
                                      echo '<i class="fas fa-sync-alt text-success"></i>';
                                      }elseif($row['daily_payout'] == 1){
                                          echo '<i class="fas fa-sync-alt text-danger"></i>';
                                      }
                                      
                                echo '</a>
                                    </div>
                                    
                                    <!--div>
                                      <a data-toggle="tooltip" data-placement="bottom" title="make binary" class="text-dark" href="'.base_url().'admin/make-binary-user?q='.$row['member_id'].'" target="_blank"><i class="fas fa-sync-alt"></i></a>
                                    </div-->
                                    
                                    <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="left" title="User Edit" class="text-dark" href="'.base_url().'admin/edit?q='.$row['member_id'].'" target="_blank"><i class="fa fa-user-edit"></i></a>
                                    </div>
                                    
                                    <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="bottom" title="Activate & Upgrade" class="text-dark" href="'.base_url().'admin/change?q='.$row['member_id'].'" target="_blank"><i class="fa fa-edit"></i></a>
                                    </div>
                                    
                                    <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="top" title="Open Dashboard" class="text-dark" href="'.base_url().'admin/admin-check/'.$row['member_id'].'" target="_blank"><i class="fa fa-arrow-right"></i></a>
                                    </div>
                                    
                                    <div class="ml-2">';
                                      if($row['status'] == 0){
                                        echo '<a data-toggle="tooltip" data-placement="right" title="Block User" data-id="'.$row['member_id'].'"  id="block" href="javascript:;" class="text-dark"> <i class="fas fa-ban text-danger"></i></a>';
                                        }elseif($row['status'] == 3){
                                            echo '<a data-toggle="tooltip" data-placement="right" title="Unblock User" data-id="'.$row['member_id'].'"  id="unblock" href="javascript:;" class="text-dark"><i class="far fa-circle text-success"></i></a>';
                                        }elseif($row['status'] == 1){
                                          echo '<a data-toggle="tooltip" data-placement="right" title="Block User" data-id="'.$row['member_id'].'"  id="block" href="javascript:;" class="text-dark"> <i class="fas fa-ban text-danger"></i></a>';
                                        }
                                        elseif($row['status'] == 2){
                                          echo '<a data-toggle="tooltip" data-placement="right" title="Block User" data-id="'.$row['member_id'].'"  id="block" href="javascript:;" class="text-dark"> <i class="fas fa-ban text-danger"></i></a>';
                                        }
                              echo '</div>
                              
                                    <div class="ml-2">';
                                      if($row['status'] == 2){
                                        echo '<a data-toggle="tooltip" data-placement="left" title="Activate User" data-id="'.$row['member_id'].'"  id="activeNow" href="javascript:;" class="text-dark"><i class="fas fa-user-check text-success"></i></a>';
                                      }elseif($row['status'] == 0){
                                          echo '<a data-toggle="tooltip" data-placement="left" title="Deactivate User" data-id="'.$row['member_id'].'"  id="deactiveNow" href="javascript:;" class="text-dark"><i class="fas fa-user-slash text-warning"></i></a>';
                                      }elseif($row['status'] == 1){
                                          echo '<a data-toggle="tooltip" data-placement="top" title="User Inactive" href="javascript:;" class="text-dark"><i class="fas fa-user-times text-danger"></i></a>';
                                      }
                              echo '</div>
                              
                                    <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="left" title="Debit & Credit" class="text-dark" href="'.base_url().'admin/debit-and-credit-transaction?q='.$row['member_id'].'" target="_blank"><i class="fa fa-money-check-alt"></i></a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </td>
                    </tr>';
                        $i++;}
                                                }
                         else{
                          echo '
                            <tr>
                              <td colspan="6">
                                <div class="text-center">No User Found</div>
                              </td>
                            </tr>
                          ';
                         }
                    echo '</tbody>
                  </table>';
                echo $this->ajax_pagination->create_links();

        
  }
  
  
  function alllPaginationSecond()
  {
    $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        $rank = $this->security->xss_clean($this->input->post('rank'));
        $statuswise = $this->security->xss_clean($this->input->post('statusWise'));
        $pkgwise = $this->security->xss_clean($this->input->post('pkgwise'));
        $datefilter = $this->security->xss_clean($this->input->post('datefilter'));
        
        if(!empty($datefilter)){
          $conditions['search']['datefilter'] = $datefilter;
        }
        
        if(!empty($pkgwise)){
          $conditions['search']['pkgwise'] = $pkgwise;
        }
        if(!empty($statuswise)){
          $conditions['search']['statuswise'] = $statuswise;
        }
        if(!empty($rank)){
            $conditions['search']['rank'] = $rank;
        }

        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->fetch_all_member_records($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->fetch_all_member_records($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#allUserHtmlData';
        $config['base_url']    = base_url().'allUserPagnet/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'allUserPag';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->fetch_all_member_records($conditions);
        echo '

          <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member Id</th>
                        <th>Name</th>
                        <th>Phone</th>
                        
                        <th>Package</th>
                        <th>Through</th>
                        <th>Activation</th>
                        
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>';
                        if(!empty($posts)){$i=1; foreach($posts as $row){
                            echo '<tr>
                                <td>'.$i.'</td>
                        <td><span class="badge bg-dark text-white"><i class="fa fa-user-alt"></i> '.$row['member_id'].'</span></td>
                        <td>'.$row['name'].'</td>
                        <td><span class="badge bg-dark text-white"><i class="fa fa-phone"></i> '.$row['phone'].'</span></td>

                        <td>'.$row['package_name'].' (&#8377; '.$row['package_amt'].'-/)</td>

                        <td><span class="badge bg-info">'; $s = $this->db->where('user',$row['member_id'])->where('order','Join Megacontest')->select('method')->get('transaction')->row('method'); if(!empty($s)){ echo $s;}else{echo 'admin added';} echo '</span></td>
                        
                        <td><span class="badge bg-dark text-white">'.$row['activation'].'</span></td>


                        
                        <td>
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="d-flex justify-content-between  bg-light p-2">
                                    
                                    <div>
                                      <a data-toggle="tooltip" data-placement="bottom" title="Daily Payout On/Off" class="text-dark" href="'.base_url().'admin/daily-payout?q='.$row['member_id'].'&c='; if($row['daily_payout'] == 1) {echo '0';} elseif($row['daily_payout'] == 0){echo '1';} echo '" target="_blank">';
                                      if($row['daily_payout'] == 0){
                                      echo '<i class="fas fa-sync-alt text-success"></i>';
                                      }elseif($row['daily_payout'] == 1){
                                          echo '<i class="fas fa-sync-alt text-danger"></i>';
                                      }
                                      
                                echo '</a>
                                    </div>
                                    
                                    <!--div>
                                      <a data-toggle="tooltip" data-placement="bottom" title="make binary" class="text-dark" href="'.base_url().'admin/make-binary-user?q='.$row['member_id'].'" target="_blank"><i class="fas fa-sync-alt"></i></a>
                                    </div-->
                                    
                                      <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="left" title="User Edit" class="text-dark" href="'.base_url().'admin/edit?q='.$row['member_id'].'" target="_blank"><i class="fa fa-user-edit"></i></a>
                                      </div>
                                      <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="bottom" title="Activate & Upgrade" class="text-dark" href="'.base_url().'admin/change?q='.$row['member_id'].'" target="_blank"><i class="fa fa-edit"></i></a>
                                      </div>
                                      <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="top" title="Open Dashboard" class="text-dark" href="'.base_url().'admin/admin-check/'.$row['member_id'].'" target="_blank"><i class="fa fa-arrow-right"></i></a>
                                      </div>
                                      <div class="ml-2">';
                                      if($row['status'] == 0){
                                        echo '<a data-toggle="tooltip" data-placement="right" title="Block User" data-id="'.$row['member_id'].'"  id="block" href="javascript:;" class="text-dark"> <i class="fas fa-ban text-danger"></i></a>';
                                        }elseif($row['status'] == 3){
                                            echo '<a data-toggle="tooltip" data-placement="right" title="Unblock User" data-id="'.$row['member_id'].'"  id="unblock" href="javascript:;" class="text-dark"><i class="far fa-circle text-success"></i></a>';
                                        }elseif($row['status'] == 1){
                                          echo '<a data-toggle="tooltip" data-placement="right" title="Block User" data-id="'.$row['member_id'].'"  id="block" href="javascript:;" class="text-dark"> <i class="fas fa-ban text-danger"></i></a>';
                                        }
                                        elseif($row['status'] == 2){
                                          echo '<a data-toggle="tooltip" data-placement="right" title="Block User" data-id="'.$row['member_id'].'"  id="block" href="javascript:;" class="text-dark"> <i class="fas fa-ban text-danger"></i></a>';
                                        }
                                      echo '</div>
                                      <div class="ml-2">';
                                      if($row['status'] == 2){
                                        echo '<a data-toggle="tooltip" data-placement="left" title="Activate User" data-id="'.$row['member_id'].'"  id="activeNow" href="javascript:;" class="text-dark"><i class="fas fa-user-check text-success"></i></a>';
                                      }elseif($row['status'] == 0){
                                          echo '<a data-toggle="tooltip" data-placement="left" title="Deactivate User" data-id="'.$row['member_id'].'"  id="deactiveNow" href="javascript:;" class="text-dark"><i class="fas fa-user-slash text-warning"></i></a>';
                                      }elseif($row['status'] == 1){
                                          echo '<a data-toggle="tooltip" data-placement="top" title="User Inactive" href="javascript:;" class="text-dark"><i class="fas fa-user-times text-danger"></i></a>';
                                      }
                                      echo '</div>
                                      <div class="ml-2">
                                      <a data-toggle="tooltip" data-placement="left" title="Debit & Credit" class="text-dark" href="'.base_url().'admin/debit-and-credit-transaction?q='.$row['member_id'].'" target="_blank"><i class="fa fa-money-check-alt"></i></a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        </td>
                    </tr>';
                        $i++;}
                                                }
                         else{
                          echo '
                            <tr>
                              <td colspan="6">
                                <div class="text-center">No User Found</div>
                              </td>
                            </tr>
                          ';
                         }
                    echo '</tbody>
                  </table>';
                echo $this->ajax_pagination->create_links();
      }


function pckage_changeFunction(){
  $exist = $this->db->where('member_id',$_GET['q'])->select('email')->get('login')->row();
  if(!empty($exist)){
    $this->load->view('super_admin/include/header');
    $this->load->view('super_admin/pckage-change');  
    $this->load->view('super_admin/include/footer');
  }else{
    redirect(base_url().'admin/all-user');
  }
}

function editChangeFunction(){
  $exist = $this->db->where('member_id',$_GET['q'])->select('email')->get('login')->row();
  if(!empty($exist)){
    $this->load->view('super_admin/include/header');
    $this->load->view('super_admin/editPackageChange');  
    $this->load->view('super_admin/include/footer');
  }else{
    redirect(base_url().'admin/all-user');
  }
}

function usereditFunctionPageControl(){
    $this->load->helper('security');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('userid','user id','trim|required');
    $this->form_validation->set_rules('name','Name','required');
    $this->form_validation->set_rules('email','Email','required');
    $this->form_validation->set_rules('phone','Phone','required');
    if($this->form_validation->run()){
        echo json_encode($this->_eitUserByAdmin($_POST));
    
    }else{
      $array = array(
          'userid' => form_error('userid'),
          'a' => form_error('name'),
          'b' => form_error('email'),
          'c' => form_error('phone')
        );
        echo json_encode($array);
    }
}

function _eitUserByAdmin($req){

  $data = [
    'name'=>$req['name'],
    'email'=>$req['email'],
    'phone'=>$req['phone']
  ];

  $this->db->where('member_id',$req['userid'])
           ->set($data)
           ->update('login');
   $this->db->where('user_id',$req['userid'])->set('name',$req['name'])->update('makebinary');       
  $this->db->where('user',$req['userid'])
           ->set($data)
           ->update('gamelogin');
           
  return [
    'ok'=>'ok',
    'msg'=>'Your Changes has been saved.'
  ];


}
function changePkgFunction(){
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('userid','user id','trim|required');
        $this->form_validation->set_rules('pkg','Package','required');
        $this->form_validation->set_rules('upg','Upgrade','required');
        $this->form_validation->set_rules('acu','Choose','required');
        if($this->form_validation->run()){
            echo json_encode($this->_packageChaneh($_POST));
        
        }else{
          $array = array(
              'userid' => form_error('userid'),
              'pkg' => form_error('pkg'),
              'upg' => form_error('upg'),
              'acu' => form_error('acu')
            );
            echo json_encode($array);
        }
}

protected function _packageChaneh($req){

  $pac = $this->db->where('id',$req['pkg'])
                  ->select('name,price')
                  ->get('package')->row();
  if($req['acu']  == '1'){
      if($req['upg'] == 2){
    $inc = array(
      'package_amt'=>$pac->price,
      'package_name'=>$pac->name,
      'status'=>'0',
      'activation'=>date('Y-m-d'),
      'tableId'=>'2',
      'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day"))
    );
    
    $this->db->where('user_id',$req['userid'])->set(array('p_amt'=>$pac->price,'activation'=>date('Y-m-d'),'status'=>'0'))->update('makebinary');       
    
    $txn = array(
      'user'=>$req['userid'],
      'gate'=>'site',
      'txn'=>'Admin',
      'refrence'=>'Admin',
      'status'=>'SUCCESS',
      'method'=>'Admin',
      'order'=>'Upgrade Account',
      'dr_cr'=>'Dr',
      't_amt'=>$pac->price,
      'timestamp'=>date('Y-m-d h:i:s A'),
      'interest'=>'0'
    );


  } elseif($req['upg'] == 1){
    $inc = array(
      'package_amt'=>$pac->price,
      'package_name'=>$pac->name,
      'tableId'=>$req['upg'],
      'status'=>'0',
      'activation'=>date('Y-m-d'),
      'tableId'=>'1',
      'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day"))
    );
    
    $this->db->where('user_id',$req['userid'])->set(array('p_amt'=>$pac->price,'activation'=>date('Y-m-d'),'status'=>'0'))->update('makebinary');       
    
    $txn = array(
      'user'=>$req['userid'],
      'gate'=>'site',
      'txn'=>'Admin',
      'refrence'=>'Admin',
      'status'=>'SUCCESS',
      'method'=>'Admin',
      'order'=>'Join Megacontest',
      'dr_cr'=>'Dr',
      't_amt'=>$pac->price,
      'timestamp'=>date('Y-m-d h:i:s A'),
      'interest'=>'0'
    );


  }else{
      return [
        'upg'=>'error'
      ];
  }


    $this->db->where('member_id',$req['userid'])->set($inc)->update('login');
    $this->db->insert('transaction',$txn);
    return [
      'ok'=>'ok',
      'msg'=>'Your Changes has been saved.'
    ];

  }elseif ($req['acu'] == '2') {
    
    if($req['upg'] == 2){
    $inc = array(
      'package_amt'=>$pac->price,
      'package_name'=>$pac->name,
      'status'=>'0',
      'activation'=>date('Y-m-d'),
      'tableId'=>$req['upg'],
      'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day"))
    );
    $this->db->where('user_id',$req['userid'])->set(array('p_amt'=>$pac->price,'activation'=>date('Y-m-d'),'status'=>'0'))->update('makebinary');             
    $txn = array(
      'user'=>$req['userid'],
      'gate'=>'site',
      'txn'=>'Admin',
      'refrence'=>'Admin',
      'status'=>'SUCCESS',
      'method'=>'Admin',
      'order'=>'Upgrade Account',
      'dr_cr'=>'Dr',
      't_amt'=>$pac->price,
      'timestamp'=>date('Y-m-d h:i:s A'),
      'interest'=>'0'
    );


  } elseif($req['upg'] == 1){
    $inc = array(
      'package_amt'=>$pac->price,
      'package_name'=>$pac->name,
      'tableId'=>$req['upg'],
      'status'=>'0',
      'activation'=>date('Y-m-d'),
      'upgrade' => $req['upg'],
      'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day"))
    );
    $this->db->where('user_id',$req['userid'])->set(array('p_amt'=>$pac->price,'activation'=>date('Y-m-d'),'status'=>'0'))->update('makebinary');       
    $txn = array(
      'user'=>$req['userid'],
      'gate'=>'site',
      'txn'=>'Admin',
      'refrence'=>'Admin',
      'status'=>'SUCCESS',
      'method'=>'Admin',
      'order'=>'Join Megacontest',
      'dr_cr'=>'Dr',
      't_amt'=>$pac->price,
      'timestamp'=>date('Y-m-d h:i:s A'),
      'interest'=>'0'
    );


  }else{
      return [
        'upg'=>'error'
      ];
  }


    $this->db->where('member_id',$req['userid'])->set($inc)->update('login');
    $this->db->where('userid',$req['userid'])->delete('daily_p');
    $this->db->where('user_id',$req['userid'])->delete('directincome');
    $this->db->where('userid',$req['userid'])->delete('binary');
    // $this->db->where('userid',$req['userid'])->delete('business');
    $this->db->where('userid',$req['userid'])->delete('bv');
    $this->db->where('user',$req['userid'])->delete('wallet');
    $this->db->where('userid',$req['userid'])->delete('userbinary');
    $this->db->where('user',$req['userid'])->delete('transaction');
    $this->db->insert('transaction',$txn);

    return [
      'ok'=>'ok',
      'msg'=>'Your Changes has been saved.'
    ];
  }

}
public function scratch_id()
{
  $this->load->view('super_admin/include/header');
  $this->load->view('super_admin/scratch_id');  
  $this->load->view('super_admin/include/footer');

}

function scratchAllDataRequest()
  {
    $data = array();
        if(!empty($this->register->fetch_scratch_request()))
        {
        //total rows count
        $totalRec = count($this->register->fetch_scratch_request());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#scarcthAddData';
        $config['base_url']    = base_url().'scratchDefaultAjaxRequest/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'scratchAddAjax';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->fetch_scratch_request(array('limit'=>10));
        echo '
            <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Txn</th>
                        <th>Request User</th>
                        <th>Package Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(! empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                      echo '<tr>
                        <td>'.$num.'</td>
                        <td><a href="'.base_url().'admin/txn?q='.$row['txn'].'">#'.$row['txn'].'</a></td>
                        <td>'.$row['s_id'].'</td>
                        <td>'.$row['s_name'].' (&#8377; '.$row['s_price'].'-/)</td>
                        <td>'.$row['date'].'</td>
                        <td>';
                        if($row['status'] =='NOT DONE'){
                          echo '<span class="badge badge-danger">'.$row['status'].'</span>';}
                          elseif($row['status'] =='REJECT'){
                            echo '<span class="badge badge-danger">'.$row['status'].'</span>';}else{
                              echo '<span class="badge badge-success">'.$row['status'].'</span>';} echo '</td>';
                        
                        
                        if($row['status'] =='NOT DONE'){
                        echo '<td>
                              <div class="d-flex">
                                <div class="yes">
                                  <button data-id="'.$row['id'].'" id="accept_id_scratch" class="btn btn-success text-white">Yes</button>
                                </div>
                                <div class="yes ml-2">
                                  <button data-id="'.$row['id'].'" data-txn="'.$row['txn'].'" id="reject_id_scratch" class="btn btn-danger text-white">No</button>
                                </div>
                              </div>
                              
                            </td>';
                        }
                      echo '</tr>';

                         $num++; } 
                         }
                         else{
                          echo '
                            <tr>
                              <td colspan="8">
                                <div class="text-center">No Scratch Request Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                      
                    echo '</tbody>
                  </table>';
                echo $this->ajax_pagination->create_links();

      }


  function scarcthDataAjaxPaginateRequest()
  {
    $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->fetch_scratch_request($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->fetch_scratch_request($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#scarcthAddData';
        $config['base_url']    = base_url().'scratchDefaultAjaxRequest/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'scratchAddAjax';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->fetch_scratch_request($conditions);
       echo '
            <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Txn</th>
                        <th>Request User</th>
                        <th>Package Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(! empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                      echo '<tr>
                        <td>'.$num.'</td>
                        <td><a href="'.base_url().'admin/txn?q='.$row['txn'].'">#'.$row['txn'].'</a></td>
                        <td>'.$row['s_id'].'</td>
                        <td>'.$row['s_name'].' (&#8377; '.$row['s_price'].'-/)</td>
                        <td>'.$row['date'].'</td>
                        <td>';
                        if($row['status'] =='NOT DONE'){
                          echo '<span class="badge badge-danger">'.$row['status'].'</span>';}
                          elseif($row['status'] =='REJECT'){
                            echo '<span class="badge badge-danger">'.$row['status'].'</span>';}else{
                              echo '<span class="badge badge-success">'.$row['status'].'</span>';} echo '</td>';
                        
                        
                        if($row['status'] =='NOT DONE'){
                        echo '<td>
                              <div class="d-flex">
                                <div class="yes">
                                  <button data-id="'.$row['id'].'" id="accept_id_scratch" class="btn btn-success text-white">Yes</button>
                                </div>
                                <div class="yes ml-2">
                                  <button data-id="'.$row['id'].'" data-txn="'.$row['txn'].'" id="reject_id_scratch" class="btn btn-danger text-white">No</button>
                                </div>
                              </div>
                              
                            </td>';
                        }
                      echo '</tr>';

                         $num++; } 
                         }
                         else{
                          echo '
                            <tr>
                              <td colspan="8">
                                <div class="text-center">No Scratch Request Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                      
                    echo '</tbody>
                  </table>';
                echo $this->ajax_pagination->create_links();
      }

protected function _protectedFetchPaymentStatus($id,$name){
    return $this->db->where('order',$name)->where('user',$id)->select('status')->from('transaction')->get()->row('status');
}
function fetchNotification()
{
    $data = array(
       'req'=>$this->_protectedFunctionNotification(),
       'users'=>$this->_protectedUsers(),
       'inuser'=>$this->_protectedUsersInactive(),
       'daily'=>$this->_protectedUsersDailyPayout(),
       'direct_income' => $this->_IncomeDirectTotal(),
       'wallet' => $this->_totalWallet(),
       'binary_income' => $this->_binary_income(),
       'total_member' => $this->_total_member(),
       'withdrawBalance'=>$this->_AdminwithdrawBalance(),
       'PayableBalance'=>$this->_PayableBalanceAllUser(),
       'joiningUpgrade'=>$this->_joiningUpgradeShow(),
       'binary_incomeSecond'=>$this->_binary_incomeSecond(),
       'rightBusinessToday'=>$this->_todayRightBusiness(),
       'blocked'=>$this->_blockedCount(),
       'deactivemember' => $this->_deactivememberFunction(),
       'lossprofit'  => $this->_lossProfit()
    );
    echo json_encode($data);
}

protected function _lossProfit(){
    $trns = $this->db->where('dr_cr','Dr')->where('order','Withdraw Amount')->where('status','SUCCESS')->select('t_amt')->from('transaction')->get()->result();  
    $withd = 0;
    foreach ($trns as $key) {
        $withd += $key->t_amt;
    }
                
    $data =  $this->db->where('dr_cr','Dr')->where('status','SUCCESS')->where('order','Join Megacontest')->or_where('order','Upgrade Account')->where('method !=','Admin')->select('t_amt')->from('transaction')->get()->result();
    $left_bus = 0;
    foreach ($data as $key) {
        $left_bus += $key->t_amt;
    }
    $msg = '';            
    if($left_bus > $withd){
        //return 'l';
        $s = $left_bus - $withd;
        $n = $s/100;
        $f = $n/100; 
        $msg.='<span class="text-success"><i class="fa fa-angle-up "></i> '.$f.'%</span>';
    }elseif($left_bus < $withd){
        $s = $withd - $left_bus;
        $n = $s/100;
        $f = $n/100; 
        $msg.='<span class="text-danger"><i class="fa fa-angle-down "></i> '.$f.'%</span>';
        
    }else{
        $msg.='';
    }
    
    return $msg;
}

protected function _blockedCount(){
    return $this->db->query("SELECT COUNT(id) as total FROM `login` where status = '3'")->row('total');
}

protected function _deactivememberFunction(){
    return $this->db->query("SELECT COUNT(id) as total FROM `login` where status = '2'")->row('total');
}

protected function _todayRightBusiness(){
    $date = date('Y-m-d');
    $n = $this->db->query("SELECT SUM(package_amt) as total FROM `login` where activation = '$date'")->row('total');
    if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }
 
        return number_format($n);
}

protected function _binary_incomeSecond(){
    $n = $this->db->query('SELECT SUM(amount) as total FROM `binary`')->row('total');
    if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }
    
}

protected function _joiningUpgradeShow(){
    $data =  $this->db->where('dr_cr','Dr')    
                        ->where('status','SUCCESS')
                        ->where('order','Join Megacontest')
                        ->or_where('order','Upgrade Account')
                        ->where('method !=','Admin')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();  
            $n = 0;
                foreach ($data as $key) {
                    $n += $key->t_amt;
                }
    
   if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }
}

protected function _AdminwithdrawBalance(){
    $data =  $this->db->where('dr_cr','Dr')    
                        ->where('order','Withdraw Amount')
                        ->where('status','SUCCESS')
                        //->where('order !=','Upgrade Account')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();  
            $n = 0;
                foreach ($data as $key) {
                    $n += $key->t_amt;
                }

   if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }         
            
}
protected function _TransactionTableCount()
{

   $data = $this->_countTransaction();

   $total = 0;
   foreach ($data as $key) {
     $total += $key->t_amt;
   }
   return $total;
    
}

protected function _countTransaction()
  {
       return $this->db->where('dr_cr','Dr')    
                        ->where('order','Withdraw Amount')
                        ->where('status','SUCCESS')
                        // ->where('order !=','Join Megacontest')
                        // ->or_where('order !=','Upgrade Account')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();  
  }
protected function _PayableBalanceAllUser(){
  $n = $this->db->query('select SUM(amount) as total from wallet')->row('total');
  if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }
}

protected function _total_member(){
  $n = $this->db->query('select count(id) as total from login')->row('total');
  if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }
}

protected function _binary_income(){
  $n = $this->db->query('SELECT SUM(amount) as total FROM `userbinary`')->row('total');
  if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }
}


protected function _totalWallet(){
  $sum = $this->db->query('select SUM(amount) as total from wallet')->row('total');
  $transa = $this->_TransactionTableCount();
  $n = $sum - $transa;
  if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }                      
  
}


protected function _IncomeDirectTotal(){
  $data = $this->db->query('select SUM(income) as total from directincome')->row();
  $n = $data->total;
  if(!empty($n)){
    $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)){
          return false;
        }
        if ($n > 1000000000000) {
          return round(($n/1000000000000), 2).'T';

        }elseif ($n > 1000000000) {
          return round(($n/1000000000), 2).'B';

        }elseif ($n > 1000000) {
          return round(($n/1000000), 2).'M';

        }elseif ($n > 1000) {
          return round(($n/1000), 2).'K';

        }else{
          return $n;
        }
      }else{
        return 0;
      }
  
}


protected function _protectedUsersDailyPayout()
{
  $data = $this->db->query('select SUM(amount) as total from daily_p')->row();
  return $data->total;  
}

protected function _protectedUsersInactive()
{
  $data = $this->db->query('select count(id) as total from login where status="1"')->row();
  return $data->total;  
}

protected function _protectedUsers()
{
  $data = $this->db->query('select count(id) as total from login where status="0"')->row();
  return $data->total;  
}

protected function _protectedFunctionNotification()
{

  $data = $this->db->query('select count(id) as total from reuest_scratch where status="NOT DONE"')->row();
  return $data->total;
}

function payoutFunction()
  {
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/dailypayout');
        $this->load->view('super_admin/include/footer');    
      
  }

  function AllPayDataPagination()
  {
    $data = array();
        if(!empty($this->register->dailypayoutTable()))
        {
        //total rows count
        $totalRec = count($this->register->dailypayoutTable());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#allpayout';
        $config['base_url']    = base_url().'allpayoutData/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'allDp';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->dailypayoutTable(array('limit'=>10));
        echo '

          <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Amount</th>
                        <th>Rank</th>
                        <th>Updated Date</th>
                      </tr>
                    </thead>
                    <tbody>';
                        if(!empty($posts)){$i=1; foreach($posts as $row){
                            echo '
                             <tr>
                                <td>'.$i.'</td>
                                <td>'.$row['userid'].'</td>
                                <td>₹ '; echo isset($row['amount']) ? $row['amount'] : 'Null'; echo '</td>
                                <td><span class="badge badge-success">'; echo isset($row['rank']) ? $row['rank'] : 'Null';echo '</span></td>
                                <td>'.$row['day'].'</td>
                              </tr>
                            ';   
                          $i++;}
                      }
                         else{
                          echo '
                            <tr>
                              <td colspan="8">
                                <div class="text-center">No Payout Found</div>
                              </td>
                            </tr>
                          ';
                         }
                    echo '</tbody>
                  </table>';
                echo $this->ajax_pagination->create_links();

        
  }
    function payoutAjaxdata()
  {
    $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        $rank = $this->security->xss_clean($this->input->post('rank'));
        if(!empty($rank)){
            $conditions['search']['rank'] = $rank;
        }

        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->dailypayoutTable($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->dailypayoutTable($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#allpayout';
        $config['base_url']    = base_url().'allpayoutData/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'allDp';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->dailypayoutTable($conditions);
        echo '

          <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Amount</th>
                        <th>Rank</th>
                        <th>Updated Date</th>
                      </tr>
                    </thead>
                    <tbody>';
                        if(!empty($posts)){$i=1; foreach($posts as $row){
                            echo '
                             <tr>
                                <td>'.$i.'</td>
                                <td>'.$row['userid'].'</td>
                                <td>₹ '; echo isset($row['amount']) ? $row['amount'] : 'Null'; echo '</td>
                                <td><span class="badge badge-success">'; echo isset($row['rank']) ? $row['rank'] : 'Null';echo '</span></td>
                                <td>'.$row['day'].'</td>
                              </tr>
                            ';   
                          $i++;}
                      }
                         else{
                          echo '
                            <tr>
                              <td colspan="8">
                                <div class="text-center">No Payout Found</div>
                              </td>
                            </tr>
                          ';
                         }
                    echo '</tbody>
                  </table>';
                echo $this->ajax_pagination->create_links();
      }


  function single_legFunction(){
    $this->load->view('super_admin/include/header');
    $this->load->view('super_admin/single-leg'); 
    $this->load->view('super_admin/include/footer');
  }

  function allSIngleLeg()
  {
    $data = array();
        if(!empty($this->register->feth_single_leg()))
        {
        //total rows count
        $totalRec = count($this->register->feth_single_leg());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#singleLeg';
        $config['base_url']    = base_url().'singleLefAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'singlelegC';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->feth_single_leg(array('limit'=>10));
                
        echo '
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Income Type</th>
                        <th>Required</th>
                        <th>Referral</th>
                        <th>percent %</th>
                        <th>Days</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                echo '<tr class="">
                        <td>'.$num.'</td>
                        <td><strong class="p-2 bg-info">'; echo $row['rank']; if($row['rank'] == 'BV'){ echo '_(in amount)';} else{ echo '_(in %)';} echo '</strong></td>
                        <td>'.$row['team'].'</td>
                        <td>'.$row['direct'].'</td>
                        <td contenteditable="True" id="paymentControl" data-id="'.$row['amount'].'&'.$row['id'].'&amount">'.$row['amount'].'</td>
                        <td>'.$row['days'].'</td>
                        <td>'; if($row['isActive'] == '0'){echo '<span class="badge badge-success">Active</span>';}else{echo '<span class="badge badge-danger">Inactive</span>';} echo'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="6">
                                <div class="text-center">No Data Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
        
      }

  function singleAjaxDataPaginate()
  {
      $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->feth_single_leg($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->feth_single_leg($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#singleLeg';
        $config['base_url']    = base_url().'singleLefAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'singlelegC';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->feth_single_leg($conditions);
                        echo '<table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Income Type</th>
                        <th>Required</th>
                        <th>Referral</th>
                        <th>Amount</th>
                        <th>Days</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                        echo '<tr class="">
                        <td>'.$num.'</td>
                        <td><strong class="p-2 bg-info">'; echo $row['rank']; if($row['rank'] == 'BV'){ echo '_(in amount)';} else{ echo '_(in %)';} echo '</strong></td>
                        <td>'.$row['team'].'</td>
                        <td>'.$row['direct'].'</td>
                        <td contenteditable="True" id="paymentControl" data-id="'.$row['amount'].'&'.$row['id'].'&amount">'.$row['amount'].'</td>
                        <td>'.$row['days'].'</td>
                        <td>'; if($row['isActive'] == '0'){echo '<span class="badge badge-success">Active</span>';}else{echo '<span class="badge badge-danger">Inactive</span>';} echo'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="6">
                                <div class="text-center">No Data Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
        
       }    

  public function transactionPage(){
      $this->load->view('super_admin/include/header');
      $this->load->view('super_admin/trans');
      $this->load->view('super_admin/include/footer'); 
  }

  function transationOageFuncgugihjknj(){
    $data = array();
        if(!empty($this->register->transactionDB()))
        {
        //total rows count
        $totalRec = count($this->register->transactionDB());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#trans';
        $config['base_url']    = base_url().'transactionAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'trsnf';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->transactionDB(array('limit'=>10));
          echo '
                  <table id="dataTableExample" class="table table-hover table-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Txn</th>
                        <th>Member Id</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>For</th>
                        <th>Interest</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Time</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                  echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>#'.$row['txn'].'</td>
                        <td>'.$row['user'].'</td>
                        <td>'.$this->db->where('member_id',$row['user'])->select('phone')->get('login')->row('phone').'</td>
                        <td>'.$this->db->where('member_id',$row['user'])->select('email')->get('login')->row('email').'</td>
                        <td><span class="badge badge-secondary">'.$row['order'].'<span></td>
                        <td  class="text-center">'; echo isset($row['interest'])? $row['interest'].' %' : '0 %'; echo '</td>
                        <td>'; 
                          if(!empty($row['interest'])){
                              if($row['dr_cr'] == 'Dr'){
                                  echo ' <span class="text-danger small mr-1"> '.$row['dr_cr'].'</span>';
                              }elseif($row['dr_cr'] == 'Cr'){
                                  echo ' <span class="text-success small mr-1"> '.$row['dr_cr'].'</span>';
                              }
                           echo '₹';echo $row['t_amt'] - $row['t_amt'] * $row['interest'] / 100;
                           
                            } else{
                              if($row['dr_cr'] == 'Dr'){
                                  echo ' <span class="text-danger small  mr-1"> '.$row['dr_cr'].'</span>';
                              }elseif($row['dr_cr'] == 'Cr'){
                                  echo ' <span class="text-success small  mr-1"> '.$row['dr_cr'].'</span>';
                              }
                              echo '₹';echo $row['t_amt'];
                              
                            }
                  echo '</td>
                        <td>'.$row['method'].'</td>
                        <td><span class="badge badge-info">'.$row['status'].'</span></td>
                        <td>'.$row['timestamp'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="10">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();   
  }

  function transactionAJaxRequest(){
      $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->transactionDB($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->transactionDB($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#trans';
        $config['base_url']    = base_url().'transactionAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'trsnf';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->transactionDB($conditions);
         echo '
                  <table id="dataTableExample" class="table table-hover table-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Txn</th>
                        <th>Member Id</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>For</th>
                        <th>Interest</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Time</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                  echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>#'.$row['txn'].'</td>
                        <td>'.$row['user'].'</td>
                        <td>'.$this->db->where('member_id',$row['user'])->select('phone')->get('login')->row('phone').'</td>
                        <td>'.$this->db->where('member_id',$row['user'])->select('email')->get('login')->row('email').'</td>
                        <td><span class="badge badge-secondary">'.$row['order'].'<span></td>
                        <td  class="text-center">'; echo isset($row['interest'])? $row['interest'].' %' : '0 %'; echo '</td>
                        <td>'; 
                          if(!empty($row['interest'])){
                              if($row['dr_cr'] == 'Dr'){
                                  echo ' <span class="text-danger small mr-1"> '.$row['dr_cr'].'</span>';
                              }elseif($row['dr_cr'] == 'Cr'){
                                  echo ' <span class="text-success small mr-1"> '.$row['dr_cr'].'</span>';
                              }
                           echo '₹';echo $row['t_amt'] - $row['t_amt'] * $row['interest'] / 100;
                           
                            } else{
                              if($row['dr_cr'] == 'Dr'){
                                  echo ' <span class="text-danger small  mr-1"> '.$row['dr_cr'].'</span>';
                              }elseif($row['dr_cr'] == 'Cr'){
                                  echo ' <span class="text-success small  mr-1"> '.$row['dr_cr'].'</span>';
                              }
                              echo '₹';echo $row['t_amt'];
                              
                            }
                  echo '</td>
                        <td>'.$row['method'].'</td>
                        <td><span class="badge badge-info">'.$row['status'].'</span></td>
                        <td>'.$row['timestamp'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="10">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();   
  }
  
  function binaryIncomeTableView(){
      $this->load->view('super_admin/include/header');
      $this->load->view('super_admin/binaryTable');
      $this->load->view('super_admin/include/footer');    
  }
  
  function binaryTableCreateFunction(){
      $data = array();
        if(!empty($this->register->binaryAdmin()))
        {
        //total rows count
        $totalRec = count($this->register->binaryAdmin());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#binaryTable';
        $config['base_url']    = base_url().'admin/binaryAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'binary';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->binaryAdmin(array('limit'=>10));
          echo '
                  <table id="dataTableExample" class="table table-hover table-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Amount</th>
                        <th>Carry</th>
                        <th>Carry Side</th>
                        <th>Next Right ID</th>
                        <th>Next Left ID</th>
                        <th>Created</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                  echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['userid'].'</td>
                        <td>₹ '.$row['amount'].'</td>
                        <td>'.$row['carry'].'</td>
                        <td>'.$row['side'].'</td>
                        <td>'.$row['right_id'].'</td>
                        <td>'.$row['left_id'].'</td>
                        <td>'.$row['date'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="9">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();   
  }
  
  function binaryTableNewCreateAjax(){
    $conditions = array();
        
        //calc offset number
        $page = $this->security->xss_clean($this->input->post('page'));
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->security->xss_clean($this->input->post('keywords'));
        $sortBy = $this->security->xss_clean($this->input->post('sortBy'));
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->binaryAdmin($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->binaryAdmin($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#binaryTable';
        $config['base_url']    = base_url().'admin/binaryAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'binary';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->binaryAdmin($conditions);  
        echo '
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Amount</th>
                        <th>Carry</th>
                        <th>Carry Side</th>
                        <th>Next Right ID</th>
                        <th>Next Left ID</th>
                        <th>Created</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                  echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['userid'].'</td>
                        <td>₹ '.$row['amount'].'</td>
                        <td>'.$row['carry'].'</td>
                        <td>'.$row['side'].'</td>
                        <td>'.$row['right_id'].'</td>
                        <td>'.$row['left_id'].'</td>
                        <td>'.$row['date'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="9">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();   
        }
  
  function bulkUserCreateFunction(){
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/bulkUserCreate');
        $this->load->view('super_admin/include/footer');    
  }
  
  function approveFunctionCreate(){
      if($_GET['q'] && $_GET['u']){
        $data['kyc'] = $this->_protectedKyc($_GET['q']);  
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/kycAllApprove',$data);
        $this->load->view('super_admin/include/footer');          
      }else{
          redirect(base_url().'admin/user-kyc');
      }
        
  }
  
  protected function _protectedKyc($id){
      return $this->db->where('status',$id)->select('id,userid,panno,panname,panimg')->get('kyccomplete')->result();
  }
  
  
  function approveKycNowFunctionCreate(){
      
      $number = count($this->input->post('checkAllData'));
      $data = [];
      if($number > 0 ){
        for($i=0; $i<$number;$i++){
            $data[] = ['id'=>$this->input->post('checkAllData')[$i],'status'=>'2'];
        }
        //echo json_encode($data);
        $this->db->update_batch('kyccomplete',$data, 'id'); 
        echo json_encode(['ok'=>'ok','title'=>'KYC SUCCESS','msg'=>'Kyc Has been Approved']);
      }else{
          echo json_encode(['er'=>'er','title'=>'KYC ERROR','msg'=>'please select atleas one KYC!']);
      }
      
  }
  
  
  function rejectKycNowFunctionCreate(){
      
      $number = count($this->input->post('checkAllData'));
      $data = [];
      if($number > 0 ){
        for($i=0; $i<$number;$i++){
            $data[] = ['id'=>$this->input->post('checkAllData')[$i],'status'=>'1'];
        }
        //echo json_encode($data);
        $this->db->update_batch('kyccomplete',$data, 'id'); 
        echo json_encode(['ok'=>'ok','title'=>'KYC SUCCESS','msg'=>'Kyc Has been Rejected']);
      }else{
          echo json_encode(['er'=>'er','title'=>'KYC ERROR','msg'=>'please select atleas one KYC!']);
      }
      
  }
  
  function manageDatabaseNow(){
        
        $this->load->view('super_admin/include/header');
        $this->load->view('super_admin/managDB');
        $this->load->view('super_admin/include/footer');   
  }
  
  function downloadTableFunction(){
      if($_GET['q']){
        $result = $this->_fetchExist($_GET['q']);      
        if(!empty($result)){
            $field = $this->db->list_fields($_GET['q']);
            
            $file_name = $_GET['q'].date('Ymdhis').'.csv'; 
            header("Content-Description: File Transfer"); 
            header("Content-Disposition: attachment; filename=$file_name"); 
            header("Content-Type: application/csv;");
           
            // get data 
            $student_data = $result;
        
            // file creation 
            $file = fopen('php://output', 'w');
         
            $header = $field; 
            fputcsv($file, $header);
            foreach ($student_data->result_array() as $key => $value)
            { 
               fputcsv($file, $value); 
            }
            fclose($file); 
            exit; 
        }else{
            
        }
      }else{
          redirect(base_url().'admin.manage/database');
      }
  }
  
    protected function _fetchExist($req){
        return $this->db->select('*')->get($req);
    }
  
    function rewardEditFunctionCreate(){
        
        if($_POST['text'] == '0' || $_POST['text'] == '1'){
            $data = explode('&',$_POST['data']);    
                $per = $data[0];
                $id =$data[1];
                $f  = $data[2];
            echo json_encode($this->_functionEditYes($per,$_POST['text'],$id,$f));
            
            
        }else{
            if(!empty($_POST['text'])){
                $data = explode('&',$_POST['data']);    
                    //$per = $data[0];
                    $id =$data[1];
                    $f  = $data[2];
                echo json_encode($this->_functionEditYes($_POST['text'],$id,$f));
            }else{
                echo json_encode([
                    'er'=>'Field required!'
                ]);    
            }
        }
            
            
    }
        
  
  protected function _functionEditYes($p,$id,$f){
      
      $this->db->where('id',$id)->set($f,$p)->update('reward');
      return [
            'ok'=>'changes saved'
          ];
  }
  
  function paymentControlEditFunctionCreate(){
      if(!empty(is_numeric($_POST['text']))){
                $data = explode('&',$_POST['data']);    
                    //$per = $data[0];
                    $id =$data[1];
                    $f  = $data[2];
                echo json_encode($this->_functionEditYespayment($_POST['text'],$id,$f));
            }else{
                echo json_encode([
                    'er'=>'Field required only numeric value!'
                ]);    
            }
  }
  
  protected function _functionEditYespayment($p,$id,$f){
      $this->db->where('id',$id)->set($f,$p)->update('single_leg');
      return [
            'ok'=>'changes saved'
          ];
  }
  
  function dailyPayoutOnOffFunctionCreate(){
      if($_GET['q']){
          $exist = $this->db->where('member_id',$_GET['q'])->select('member_id')->get('login')->row();
          if(!empty($exist)){
            $this->load->view('super_admin/dailyPayoutOnOff');
            $this->db->where('member_id',$exist->member_id)->set('daily_payout',$_GET['c'])->update('login');
            
          }else{
            echo 'no';
              redirect(base_url().'admin/all-user');
          }
          
      }else{
          //echo $_GET['c'];
          redirect(base_url().'admin/all-user');
      }
  }
  
  function changePackageFunctionAndEdit(){
    $data['package'] = $this->_packageChangeFunction();
    $this->load->view('super_admin/include/header');
    $this->load->view('super_admin/packageedit',$data); 
    $this->load->view('super_admin/include/footer');
  }
  
  protected function _packageChangeFunction(){
      return $this->db->select('name,id,price,isActive,forAdmin')->get('package')->result();
  }
  
  function changepackageinformation(){
      
      if(!empty($_POST['text'])){
                $data = explode('&',$_POST['data']);    
                    //$per = $data[0];
                    $id =$data[0];
                    $f  = $data[1];
                echo json_encode($this->_functionEditYespaymentpackage($_POST['text'],$id,$f));
            }else{
                if($_POST['text'] == 0 || $_POST['text'] == 1){
                    $data = explode('&',$_POST['data']);    
                    $id =$data[0];
                    $f  = $data[1];
                echo json_encode($this->_functionEditYespaymentpackage($_POST['text'],$id,$f));
                }else{
                    echo json_encode([
                        'er'=>'Field required'
                    ]);        
                }
                
            }
  }
  
  protected function _functionEditYespaymentpackage($p,$id,$f){
      $this->db->where('id',$id)->set($f,$p)->update('package');
      return [
            'ok'=>'changes saved'
          ];
  }
  
  function addNewRowFunctionCreate(){
      echo json_encode($this->_protectedAddNewRow($_POST));
  }
  
  protected function _protectedAddNewRow($req){
      $data = [
            'name'=>'joining',
            'price'=>'0.00',
            'isActive'=>'1',
            'forAdmin'=>'0',
            'check'=>$req['count']
          ];
        $exist = $this->db->where('id',$req['count'])->select('id')->get('package')->row('id');  
        if(empty($exist)){
            $this->db->insert('package',$data);      
            return [
                'ok'=>'Row added'
            ];
        }else{
            return [
                'er'=>'Row allready added refresh page now!'
            ];
        }
  }
  
  function removeNewRowFunction(){
      echo json_encode($this->_protectedAddNewRowRemove($_POST));
  }
  
  protected function _protectedAddNewRowRemove($req){
      $this->db->where('check',$req['id'])->delete('package');
      return [
                'ok'=>'Row removed'
            ];
  }
  
  function set_primary(){
      echo json_encode($this->_protectedAddSetPrimary($_POST));
  }
  
  protected function _protectedAddSetPrimary($req){
       $d = explode('&', $req['id']);
       if($this->_changeSprimary($d[1])){
           
            $this->db->where('id',$d[0])->set($d[1],'1')->update('package');     
            return ['ok'=>'Set to primary'];
            
       } else{
           return ['er'=>'Not set to primary'];
       }
      
          
          
  }
  
  private function _changeSprimary($name){
      return $this->db->where($name,'1')->set($name,'0')->update('package');
  }

}
  ?>