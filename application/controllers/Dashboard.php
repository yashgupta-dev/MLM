<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
  public function __construct()
  {
      parent::__construct();
      
      $this->load->model('register');
      $this->load->helper('sms');
      $this->_loginIf();    
      $this->anytimerun();
      date_default_timezone_set('Asia/Kolkata');

  }
  
  
  protected function _panelstatus(){
      return $this->register->panelstatus();
  }

protected function _fetchCDate($day,$user){
      return $this->db->where('day',$day)
                      ->where('userid',$user)
                      ->from('daily_p')
                      ->get()->row();
}
  function rewardsFunction(){
      $data['reward'] = $this->_fetchRewards();
      $this->load->view('admin/include/header');
        $this->load->view('admin/reward',$data);
        $this->load->view('admin/include/footer');
  }
  
  protected function _myBlanceLeft(){
    $main = $this->_mywallet();
    $trns = $this->_TransactionTableCount();

    return $main - $trns;
    }
    protected function _mywallet(){
        return $this->db->where('user',$this->session->userdata('username'))
                        ->select('amount')
                        ->from('wallet')
                        ->get()->row('amount');
    }
  protected function _fetchRewards(){
      return $this->db->where('opt','1')
                      ->select('point,name')
                      ->from('reward')
                      ->get()->result();
  }
  function upgradeFunction(){
      if($_GET['q']){
            if(!empty($this->_checkUrlq($_GET['q']))){
                $this->load->view('admin/include/header');
                $this->load->view('admin/upgrade');
                $this->load->view('admin/include/footer');
            }else{
                redirect(base_url());    
            }
      }else{
          redirect(base_url());       
      }
  }
  
  
  
  public function userGenealogy()
    {   
        if($_GET['q']){
            if(!empty($this->_checkUrlq($_GET['q']))){
                
                $tree = $this->register->tree_fetch($_GET['q']);
                $treeRight = $this->register->tree_fetch_right($_GET['q']);
                $data['tree'] = $this->register->tree_fetch($_GET['q']);
              $data['dataUser'] = $this->register->tree_data_fetch($_GET['q']);
              //echo '<pre>';
              //print_r($data);
                foreach($tree as $c)
                {
                    $rightSecond = $c->member_id;
                      if(isset($c->sub_name))
                      {
                        foreach($c->sub_name as $row)
                        {
                          if($row->side == 'Left')
                          {
                            $left = $row->member_id;
                          }else{
                              }
                        }
                      }
                }
              foreach($treeRight as $c)
              {
                if(isset($c->sub_name))
                {
                  foreach($c->sub_name as $row)
                  {
                    if($row->side == 'Right')
                    {
                      $right = $row->member_id;
                    }else{
                     }
                  }
                }
              }
                //echo $id;
               //echo '<pre>';
              if(!empty($rightSecond)){ 
                $data['rightsecond'] = $this->register->fetch_child_second($rightSecond);
              }
              if(!empty($left))
              {
                $data['thirdleft_child']  = $this->register->fetch_child_more($left);
                $data['thirdright_child']  = $this->register->fetch_child_more_right_third($left);
                //echo '<pre>';
                //print_r($datal);
              }
              if(!empty($right))
              {
                $data['thirdleft2_child']  = $this->register->fetch_child_more2($right);
                $data['thirdright2_child']  = $this->register->fetch_child_more_right_third2($right);
                
              }
                // echo '<pre>';
                //print_r($data);
              if(!empty($right)){
                $data['right_child']  = $this->register->fetch_child_more_right($right);
              }
               
              $this->load->view('admin/include/header');
              $this->load->view('admin/user-geneology',$data);  
              $this->load->view('admin/include/footer');
            }else{
                redirect(base_url());
            }
        }else{
            redirect(base_url());
        }
        
    } 
    
    protected function _checkUrlq($id){
        return $this->db->where('member_id',$id)
                        ->select('member_id')
                        ->from('login')
                        ->get()->row();
    }
    function activateFunction(){
        if($_GET['q']){
            if(!empty($this->_checkUser($_GET['q']))){
                $this->load->view('admin/include/header');
                $this->load->view('admin/activate');
                $this->load->view('admin/include/footer');          
            }
            else{
                redirect(base_url().'my/team/Right-team');   
            }
            
        }
        else{
            redirect(base_url().'my/team/Right-team');   
        }
    }
    
    protected function _checkUser($id){
        return $this->db->where('member_id',$id)
                        ->select('member_id')
                        ->get('login')->row('member_id');
    }
  
  function widthdrowFunction(){
      $this->load->view('admin/include/header');
      $this->load->view('admin/withdrow');
      $this->load->view('admin/include/footer');
  }

  function trsnferMoneyFunction(){
    
    echo json_encode($this->_GetFormData($_POST));
  }

  protected function _GetFormData($data){
    $def = $this->db->select('default')->from('admin')->get()->row('default');
    if($data['trans'] < $def){ 
      return $msg =[
          'error'=>'Minimum amount should be greater then <i class="fa fa-rupee-sign text-muted" style="font-size:11px;"></i> '.$def.''
        ];
    
    }
    else{
      
        $his = $this->_cehckPreviousTransaction($this->session->userdata('username'));
        $wallet = $this->db->where('user',$this->session->userdata('username'))->get('wallet')->row('amount');
        $bal = $wallet - $his;
          if($this->_checkBal()){
              //no get make interest;
              if($this->_checkKyc($this->session->userdata('username'))){
                $trsnsfer = $this->_makeIntersetFinal($data['trans']); 
                //sub to bal to trsnsfer and update and save;
                return $this->_GetFinalIncome($trsnsfer,$bal,$data['trans']);
              }else{
                return $msg =[
                  'error'=>'Your KYC not completed, please complete your kyc now <a href="/my/profile/update-kyc">Complete KYC</a>.</p>'
                ];    
              }
          }
          else{
            return $msg =[
              'error'=>'Account details not found! please add account <p><a href="/my/profile/update-profile">click to add account.</a></p>'
            ];
          }
        }
  }

protected function _checkKyc($id){
    $count = $this->db->query("SELECT count(id) as total FROM kyc where userid='$id'")->row('total');  
    $data =  $this->db->where('userid',$id)
                    ->where('status','verify')
                    ->select('proof')
                    ->from('kyc')
                    ->get()->result();  
      
      if($count == count($data)){
        return 'ok';
      }
}

  protected function _GetFinalIncome($trsnsfer,$bal,$main){
    
    if($bal <= $main){

      return $msg =[
        'error' => 'Insufficiant balance in your account.'
      ];

    }else{
        $credential = $this->_fetchCredentials();
        $fin = $bal - $main;
        $data = [
          'fin' =>$fin,
          'trnsfer'=>$trsnsfer,    
          'user' => $this->session->userdata('username'),
          'txn' => strtotime(date('Y-m-d h:i:s')).substr($this->session->userdata('username'),2,6).'',
          'for' => 'Withdraw',
          't_amt' => $main,
          'interest' => $this->db->select('interest')->from('admin')->get()->row('interest')
        ];
        $this->session->set_userdata($data);
        $rand = rand(1111,9999);
        $this->session->set_userdata('otp',$rand);
        /*$field = array(
            "sender_id" => "WINMND",
            "language" => "english",
            "route" => "qt",
            "numbers" => $this->_protectedFetchPhone($this->session->userdata('username')),
            "message" => "27378",
            "variables" => "{#AA#}",
            "variables_values" => "$rand"
        );
        $res = forget($field);*/
        $data =array(
            'from'=>$credential->smtp_user,
            'title'=>$credential->website_name,
            'to'=>$this->_fetchEMail($this->session->userdata('username')),
            'sub'=>''.$credential->website_name.' (Money Withdrawl.)',
            'text'=>'Dear '.$this->session->userdata('name').', <br><br> we accept your transaction request Your Txn '.$data['txn'].', Your Transaction OTP '.$rand.' plaese do not share your otp to any one please secure your OTP.',
            'heading'=>'Windhdrawl Alert',
            'pre'=>'Your transaction request otp '.$rand.' please do not share your password to any one, please keep secure',
            'btn'=>'More Query'
        );
        $this->_protectedFunctiontest($data);
        /*if($res == 0){
            return [
                'ok'=>'we send Otp your register mobile'
            ];
            }else{
                return [
                    'otper'=>'something went wrong'
                ];
            }*/
            return [
                'ok'=>'we send Otp your register email'
            ];
        
        
      }
  }
  function otpVerify_function_check(){
      echo json_encode($this->_checkOtp($_POST));
  }
  
  protected function _checkOtp($data){
      if(empty($data['otpName'])){
          return [
            'er'=>'Otp required'
            ];
      }else{
          return $this->_checkOtoFinal($data['otpName']);
      }
  }
  
  protected function _checkOtoFinal($otp){
      if($otp == $this->session->userdata('otp')){
          $trsnsfer = $this->session->userdata('trnsfer');
          $fin = $this->session->userdata('fin');
          $data = [
          'user' => $this->session->userdata('user'),
          'txn' =>  $this->session->userdata('txn'),
          't_amt' =>  $this->session->userdata('t_amt'),
          'interest' =>  $this->session->userdata('interest'),
          'order' =>  $this->session->userdata('for'),
          'gate'=>'site',
          'dr_cr'=>'Dr'
        ];
          return $this->_otpVerifyAfter($trsnsfer,$data,$fin);
      }else{
          return [
              'er'=>'invalid otp'
              ];
      }
  }
  protected function _otpVerifyAfter($trsnsfer,$data,$fin){
      
      $this->db->insert('transaction',$data);
      
        /*$field = array(
            "sender_id" => "WINMND",
            "language" => "english",
            "route" => "qt",
            "numbers" => $this->_protectedFetchPhone($this->session->userdata('username')),
            "message" => "27375",
            "variables" => "{#BB#}|{#CC#}",
            "variables_values" =>"$trsnsfer|winnersmind.in"
        );
        forget($field);*/
        $credential = $this->_fetchCredentials();
        $data =array(
            'from'=>$credential->smtp_user,
            'title'=>$credential->website_name,
            'to'=>$this->_fetchEMail($this->session->userdata('username')),
            'sub'=>''.$credential->website_name.' (Amount Transfer)',
            'text'=>'Dear '.$this->session->userdata('name').', <br><br> we deposit amount in your account
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <th>Txn</th>
                  <th>Txn</th>
                </tr>
                <tr>
                  <td>#'.$this->session->userdata('txn').'</td>
                  <td>Rs. '.$this->session->userdata('t_amt').'</td>
                </tr>
                <tr>
                  <td>interest: </td>
                  <td>
                    '.$this->session->userdata('interest').'%
                  </td>
                </tr>
                <tr style="background-color:#dde; padding:10px 0px;">
                  <td>Total Amount:</td>
                  <td>
                     Rs. '.$this->session->userdata('trnsfer').'
                  </td>
                </tr>

              </table>
            ',
            'heading'=>'Transaction Alert',
            'pre'=>'Amount will be transferes to your account. Thank you. ',
            'btn'=>'More Query'
        );
        $this->_protectedFunctiontest($data);
        $this->session->unset_userdata('otp');
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('txn');
        $this->session->unset_userdata('t_amt');
        $this->session->unset_userdata('interest');
        $this->session->unset_userdata('fin');
        $this->session->unset_userdata('trnsfer');
        return $msg= [
          'url'=>base_url().'my/widthdrow',
          'msg' =>'<p>Your amount will be credit to your account in 2-3 working days. <span class="text-muted"> Thank you.</span></p>'
        ];
        
  }
  
  protected function _protectedFetchPhone($id){
      return $this->db->where('member_id',$id)
                      ->select('phone')
                      ->get('login')->row('phone');
  }

  protected function _cehckPreviousTransaction($id){
    $data =  $this->db->where('user',$this->session->userdata('username'))
                        ->where('dr_cr','Dr')    
                        ->where('order !=','Join Megacontest')
                        ->where('order !=','Upgrade Account')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();  
            $left_bus = 0;
                foreach ($data as $key) {
                    $left_bus += $key->t_amt;
                }
            return $left_bus;
  }

  protected function _makeIntersetFinal($trnsfer){
    $interest = $this->db->select('interest')->from('admin')->get()->row('interest');    
    $get = $trnsfer * $interest/100;
    $final = $trnsfer - $get;
    return $final;
  }

  protected function _checkBal(){

    return $this->db->where('user_id',$this->session->userdata('username'))
                    ->from('account')
                    ->get()->row();
  }

  function interestFunction(){
    $bal= $this->security->xss_clean($this->input->post('bal')); 

    echo json_encode($this->_makeInterset($bal));
  }

  protected function _makeInterset($bal){

    $interest = $this->db->select('interest')->from('admin')->get()->row('interest');
    $get = $bal * $interest/100;
    $final = $bal - $get;
    $out='';
    $out.='widthrow amount: '.$final.' <i class="fa fa-rupee-sign" style="font-size:12px"></i>. (';$out.=isset($interest)? $interest: '0'; $out.='% interest)';

    return $out;
  }

  function incomeFunctionBig(){
    $this->load->view('admin/include/header');
      if($_GET['q']){
        
          switch ($_GET['q']) {
            case 'referral':
          $table ='directincome';
              $time = 'date';
              $am ='income';
              $type='user_id';
              $data['data'] = $this->_FetchTableAndCreate($_GET['q'],$table,$time,$am,$type);
              $this->load->view('admin/income',$data);
              break;
            case 'binary':
              $data['data'] = $this->_binaryCreateTable();
              $this->load->view('admin/income',$data);
              break;
            case 'daily':
              
              $data['data'] = $this->_createDailyPayoutTable();
              $this->load->view('admin/income',$data);
              break;
            
            default:
                redirect(base_url().'my/dashboard');      
              break;
        
          }
      }else{
        redirect(base_url().'my/dashboard');
      }
      $this->load->view('admin/include/footer');  
  }

  protected function _createDailyPayoutTable(){
       $capping = $this->db->where('userid',$this->session->userdata('username'))
                    ->select('*')
                    ->from('daily_p')
                    ->get()->result_array();
  $out='';
        $out.='<table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Memer Id</th>
            <th>Daily Income</th>
            <th>Created</th>
          </tr>
        </thead>
          <tbody>';
          $i=1;
          if(!empty($capping)){}
          foreach ($capping as $row) {
            $out.='<tr>
                <td>'.$i.'</td>
                <td>'.$row['userid'].'</td>
                <td><i class="fa fa-rupee-sign"></i> '.$row['amount'].'</td>';
                $out.='
                <td>'.$row['day'].'</td>
                </tr>';
              $out.='</tbody>';  
          $i++;}
          
        return $out;
  }
  protected function _binaryCreateTable(){
    $capping = $this->db->where('userid',$this->session->userdata('username'))
                    ->select('*')
                    ->from('userbinary')
                    ->get()->result_array();
  $out='';
        $out.='<table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Memeber Id</th>
            <th>Amount</th>
            <th>Created</th>
          </tr>
        </thead>
          <tbody>';
          $i=1;
          if(!empty($capping)){}
          foreach ($capping as $row) {
            $out.='<tr>
                <td>'.$i.'</td>
                <td>'.$row['userid'].'</td>
                <td><i class="fa fa-rupee-sign"></i> '.$row['amount'].'</td>';
                $out.='
                <td>'.$row['created_at'].'</td>
                </tr>';
              $out.='</tbody>';  
          $i++;}
          
        return $out;


               
  }
  

  protected function _FetchTableAndCreate($id,$table,$time,$am,$type){
    $data = $this->db->where($type,$this->session->userdata('username'))
                     ->from($table)
                     ->get()->row();
    
    $out='';
    $out.='<table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Your id</th>
            <th>Amount</th>';
        $out.= isset($data->total_fund)? '<th>Total Fund</th>' : '' ;
            $out.='<th>Last Updated</th>
          </tr>
        </thead>
        <tbody>';
        if(!empty($data)){
          $out.='<tr>
            <td>1</td>
            <td>'.$this->session->userdata('username').'</td>
            <td><i class="fa fa-rupee-sign"></i> '.$data->$am.'</td>';
            $out.= isset($data->total_fund)? '<td><i class="fa fa-rupee-sign"></i> '.$data->total_fund.'</td>' : '' ;
            $out.='
            <td>'.$data->$time.'</td>
          </tr>';
        }
        else{
          $out.='
            <tr>
              <td colspan="4"><div class="text-center">No Result Found.</div></td>
            </tr>
          ';
        }
        $out.='</tbody>
    ';
    return $out;
}

  function update_kyc_form(){
      
      $this->load->view('admin/include/header');
      $this->load->view('admin/kyc');
      $this->load->view('admin/include/footer');

  }

  function kyc_data(){
    $data = array();
        if (!empty($_FILES['file']['name'])) {
            $filesCount = count($_FILES['file']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['uploadFile']['name'] = str_replace(",","_",$_FILES['file']['name'][$i]);
                $_FILES['uploadFile']['type'] = $_FILES['file']['type'][$i];
                $_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                $_FILES['uploadFile']['error'] = $_FILES['file']['error'][$i];
                $_FILES['uploadFile']['size'] = $_FILES['file']['size'][$i];
                //Directory where files will be uploaded
                if (!file_exists('uploads/'.$this->session->userdata('username').'')) {
                      mkdir('uploads/'.$this->session->userdata('username').'', 0755, true);
                    }   

                $uploadPath = 'uploads/'.$this->session->userdata('username').'/';
                $config['upload_path'] = $uploadPath;
                // Specifying the file formats that are supported.
                $config['allowed_types'] = 'jpg|jpeg';
                $config['detect_mime']          = TRUE;
                $config['encrypt_name']        = TRUE;
                $config['remove_spaces']        = TRUE;
                $config['max_filename']       = 0;
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
                    $list[] = array(
                      'userid'=>$this->session->userdata('username'),
                      'proof'=>$value['file_name']
                    );
                }

                $this->db->insert_batch('kyc',$list);
                echo json_encode('your proof sended to admin for verification.');  
          }
      }
  }

  function removeImageProodyFunction(){
      $id = $this->input->post('id');
      echo json_encode($this->_RemoveProofBack($id));
  }

  protected function _RemoveProofBack($id){
    $name = $this->db->where('id',$id)
                    ->select('proof')
                    ->from('kyc')
                    ->get()->row('proof');
    if (file_exists('uploads/'.$this->session->userdata('username').'/'.$name.'')) {
          unlink('uploads/'.$this->session->userdata('username').'/'.$name.'');
          return $this->db->where('id',$id)
                          ->delete('kyc');  
    }   
    else{
      $data = array(
          'er'=>'Sorry! file not found.'
      );
      return $data;
      
    }

  }

  

  protected function _loginIf()
  {
    if($this->session->userdata('username') != '')
      {    

      }
    else{
      redirect(base_url());
    } 
  }

  function direct_sponser_table()
  {
      $data['sp'] = $this->register->fetch_sposer($this->session->userdata('username'));
      $this->load->view('admin/include/header');
      $this->load->view('admin/direct_sponser',$data);
      $this->load->view('admin/include/footer');
  }
  
function requestToId()
{
    $this->load->helper('security');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('username','username','trim|required|max_length[20]');
    $this->form_validation->set_rules('package_name','package','required|numeric');
    $this->form_validation->set_rules('number','Id','trim|required|numeric|is_natural_no_zero');
    $this->form_validation->set_rules('email','email','trim|required|valid_email');
    $this->form_validation->set_rules('phone','phone','trim|required|numeric|max_length[10]|min_length[10]');
    $this->form_validation->set_rules('paymentMethod','Payemnt Method','required|numeric');
    if($this->form_validation->run()){
        $choose = $this->security->xss_clean($this->input->post('paymentMethod'));
        if($choose == 1){
            echo json_encode($this->_makePayemntFormData($this->security->xss_clean($_POST)));
        }elseif($choose == 0){
            echo json_encode($this->_protectedWalletAccount($this->security->xss_clean($_POST)));
            
        }else{
            echo json_encode(['title'=>'Credentials','er'=>'Invalid Creadentials.']);
        }
    }else{
        $array = array(
        'error'   => true,
        'a' => form_error('username'),
        'b' => form_error('package_name'),
        'c' => form_error('number'),
        'e' => form_error('email'),
        'f' => form_error('phone'),
        'd' => form_error('paymentMethod')
       );
    
      echo json_encode($array);
}

}

protected function _makePayemntFormData($req){
    
}

function cancelFunction(){
    $this->session->unset_userdata('set');
    $this->session->unset_userdata('pp');
    $this->session->unset_userdata('pn');
    $this->session->unset_userdata('req');
    $this->session->unset_userdata('wallet');
    redirect(base_url().'my/scratch?q=raise-request');
}

function bookOrderFunctionCheck(){
    if(!empty($this->session->userdata('pp'))
        && !empty($this->session->userdata('set'))
        && !empty($this->session->userdata('pn'))
        && !empty($this->session->userdata('req'))
        && !empty($this->session->userdata('wallet'))
    ){
        $txn = strtotime(date('Y-m-d h:i:s')).substr($this->session->userdata('username'),2,6).'';
       $data = array(
           'txn'=>$txn,
           's_id'=>$this->session->userdata('username'),
           's_name'=>$this->session->userdata('pn'),
           's_price'=>$this->session->userdata('pp'),
           's_total_price'=>$this->session->userdata('pp') * $this->session->userdata('req'),
           'date'=>date('d-M-Y h:i:s'),
           'number'=>$this->session->userdata('req')
      );
      
      $txn = array(
          'txn'=>$txn,
          'method'=>'wallet',
          'status'=>'Done',
          't_amt'=>$this->session->userdata('pp') * $this->session->userdata('req'),
          'user'=>$this->session->userdata('username'),
          'order'=>'Pin request',
          'gate'=>'site',
            'dr_cr'=>'Dr'
      );
          
      $this->_protecedtDataInsert($txn);
        $this->_dbInsertRequest($data);
        
            $credential = $this->_fetchCredentials();
            $email =array(
                'from'=>$credential->smtp_user,
                'title'=>$credential->website_name,
                'to'=>$this->_fetchEMail($this->session->userdata('username')),
                'sub'=>''.$credential->website_name.' (Pin request)',
                'text'=>'Dear Member '.$this->session->userdata('username').'<br> your request has been send to admin aproval. when admin aprove your pin request that will automatic added in your panel <br> Thank you<br>Regards<br>admin.',
                'heading'=>'Pin Request Approval',
                'pre'=>'',
                'btn'=>'login to account'
            );
            $this->_protectedFunctiontest($email);    
            $this->session->unset_userdata('set');
            $this->session->unset_userdata('pp');
            $this->session->unset_userdata('pn');
            $this->session->unset_userdata('req');
            $this->session->unset_userdata('wallet');
            $this->session->set_flashdata('acc','
            $.confirm({
                title: "Success",
                content: "Dear '.$this->session->userdata('name').' <br> your request has been send to aproval to admin, your reqest has been aproved 1-2 working days, Thank you. ",
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
        redirect(base_url().'my/scratch?q=raise-request');    
    }else{
          
      $this->session->set_flashdata('acc','
            $.confirm({
                title: "Transaction Alert",
                content: "Sorry! '.$this->session->userdata('name').' we can not accept your request at a time try again latter, Thank you",
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
            
        redirect(base_url().'my/scratch?q=raise-request');    
    }
       
}

protected function _protecedtDataInsert($txn){
    return $this->db->insert('transaction',$txn);
    
}
protected function _dbInsertRequest($data){
    return $this->db->insert('reuest_scratch',$data);
}
protected function _protectedWalletAccount($data){
    $price = $this->register->fetch_package_price($data['package_name']);
    if(!empty($price)){
        $stat = $this->_checkBalanceWalletId($data);
        if($stat == 202){
            $ses = [
            'set'=>'set',
            'wallet'=>$this->_fetchWallet($this->session->userdata('username')),
            'req'=>$data['number'],
            'pp'=>$price->price,
            'pn'=>$price->name
            ];
            
            $this->session->set_userdata($ses);
            return [
            'title'=>'Placeorder',
            'ok'=>'ok',
            'msg'=>'Are you sure to pay Amount using your wallet'
            ];
            
            
        }else{
            return [
                'title'=>$stat.' Wallet Balance',
                'er'=>'Insuffcient Wallet Balance'
            ];
        }
    }else{
        return [
            'title'=>'Package',
            'er'=>'we not have any package at a time?'
            ];
    }
  //echo $price->price * $id;
}



protected function _checkBalanceWalletId($data){
    $wallet = $this->_fetchWallet($this->session->userdata('username'));
    $price = $this->register->fetch_package_price($data['package_name']);
    $countAmt = $data['number'] * $price->price;
    if($wallet >= $countAmt){
        return 202;
    }else{
        return '404';
    }
}

protected function _fetchWallet($id){
    $wal = $this->db->where('user',$id)->get('wallet')->row('amount');
    
    $left_bus = $this->_TransactionTableCount();
    
    return $wal - $left_bus;
}

function update_password()
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
                    $this->register->update_pass($cnf);
                    echo json_encode(['ok'=>'password changed successfully']);
                } else{
                    echo json_encode(['cr'=>'confirm password not match']);
                }    
            }
        }
    }
}

protected function _protectedPasswordCur($cur){
    return $this->db->where('member_id',$this->session->userdata('username'))
                    ->where('pass',md5($cur))
                    ->select('pass')
                    ->get('login')->row('pass');
}

function ROIIncrease()
{

    echo json_encode($this->register->ROISTATUS($this->session->userdata('username')));
    
}


//=====================================================================================================
     //Dashboard Count System;
//===================================================================================================    



public function countRightTeam()
{
  $data = array(
    'rightTotal'=>$this->_protectedTeamRightCount(),
    'LeftTeam' =>$this->_protectedTeamLeftCount(),
    'trns'=>$this->_TransactionTableCount(),
    'direct'=>$this->_mydirectTeam($this->session->userdata('username')),
    'direct_income'=>$this->_directIncome($this->session->userdata('username')),
    'dailyp'=>$this->_TotalDailyPayoutCount(),
    'balance' =>$this->_fetchWalletBalcance(),
    'binary' =>$this->_countBalance(),
    'leftBusiness'=>$this->_leftBusiness($this->session->userdata('usrf')),
    'rightBusiness'=>$this->_rightBusiness($this->session->userdata('usrf')),
    'todayleftBusiness'=>$this->_todayleftBusiness(),
    'todayrightBusiness'=>$this->_todayrightBusiness(),
    'panelstatus' => $this->_panelstatus()
  );
  echo json_encode($data);  
  
}

protected function _todayleftBusiness(){
  $id = $this->session->userdata('usrf');
  $right = $this->db->where('parent',$id)->where('activation',date('Y-m-d'))->where('teamside','Left')->select('*')->get('makebinary')->result();
  $total = 0;
  if(!empty($right)){
      foreach ($right as $row) {
            # code...
            $total += $row->p_amt;
          }    
  
   }else{
       
       $total=0;
   }
   return $total;

}

protected function _todayrightBusiness(){
  $id = $this->session->userdata('usrf');
   $right = $this->db->where('parent',$id)->where('activation',date('Y-m-d'))->where('teamside','Right')->select('*')->get('makebinary')->result();
  $total = 0;
  if(!empty($right)){
      foreach ($right as $row) {
            # code...
            $total += $row->p_amt;
          }    
  
   }else{
       
       $total=0;
   }
   return $total;
}

protected function _rightBusiness($id){
  $right = $this->db->where('parent',$id)->where('teamside','Right')->select('*')->get('makebinary')->result();
  $total = 0;
  if(!empty($right)){
      foreach ($right as $row) {
            # code...
            $total += $row->p_amt;
          }    
  
   }else{
       
       $total=0;
   }
   return $total;
   /*$precision = 2;
  if($left_bus < 900) {
    $n_format = number_format($left_bus, $precision);
      $n_format.''.$suffix = '';
        return floatval($n_format);
  } else if ($left_bus < 900000) {
    // 0.9k-850k
    $n_format = number_format($left_bus / 1000, $precision);
      return $n_format.''. $suffix = 'K';
  } else if ($left_bus < 900000000) {
    // 0.9m-850m
    $n_format = number_format($left_bus / 1000000, $precision);
      return $n_format.''.$suffix = 'M';
  } else if ($left_bus < 900000000000) {
    // 0.9b-850b
    $n_format = number_format($left_bus / 1000000000, $precision);
      return $n_format.''.$suffix = 'B';
  } else {
    // 0.9t+
    $n_format = number_format($left_bus / 1000000000000, $precision);
      return $n_format.''.$suffix = 'T';
  }
    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
      $dotzero = '.' . str_repeat( '0', $precision );
      $n_format = str_replace( $dotzero, '', $n_format );
    }  */
}

protected function _leftBusiness($id){

  $right = $this->db->where('parent',$id)->where('teamside','Left')->select('*')->get('makebinary')->result();
  $total = 0;
  if(!empty($right)){
      foreach ($right as $row) {
            # code...
            $total += $row->p_amt;
          }    
  
   }else{
       
       $total=0;
   }
   return $total;
   /*$precision = 2;
  if($left_bus < 900) {
    $n_format = number_format($left_bus, $precision);
      $n_format.''.$suffix = '';
        return floatval($n_format);
  } else if ($left_bus < 900000) {
    // 0.9k-850k
    $n_format = number_format($left_bus / 1000, $precision);
      return $n_format.''. $suffix = 'K';
  } else if ($left_bus < 900000000) {
    // 0.9m-850m
    $n_format = number_format($left_bus / 1000000, $precision);
      return $n_format.''.$suffix = 'M';
  } else if ($left_bus < 900000000000) {
    // 0.9b-850b
    $n_format = number_format($left_bus / 1000000000, $precision);
      return $n_format.''.$suffix = 'B';
  } else {
    // 0.9t+
    $n_format = number_format($left_bus / 1000000000000, $precision);
      return $n_format.''.$suffix = 'T';
  }
    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
      $dotzero = '.' . str_repeat( '0', $precision );
      $n_format = str_replace( $dotzero, '', $n_format );
    } */
}
protected function _countBalance(){
    return $this->db->where('userid',$this->session->userdata('username'))->where('created_at',date('Y-m-d'))->select('amount')->get('userbinary')->row('amount');
}

protected function _fetchWalletBalcance(){
    $txn =  $this->db->where('user',$this->session->userdata('username'))
                        //->where('gate','app')    
                        ->where('dr_cr','Dr')    
                        ->where('order !=','Join Megacontest')
                        ->where('order !=','Upgrade Account')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();    
                            $total = 0;
                            
                            foreach ($txn as $key) {
                                $total += $key->t_amt;
                            }
                        $wallet = $this->db->where('user',$this->session->userdata('username'))->select('amount')->from('wallet')->get()->row('amount');
                        if($total > $wallet){
                             $left_bus = 0;        
                        }else{
                        $left_bus = $wallet-$total;
                         
                           
                        }
                        
                        return $left_bus;
                        die;
    
    // die;
    // return $this->db->where('user',$this->session->userdata('username'))
    //                 ->select('amount')
    //                 ->from('wallet')
    //                 ->get()->row('amount');
    // die;
    //                 $total = $this->_TransactionTableCount();
    //                 $wallet = $this->db->where('user',$this->session->userdata('username'))->select('amount')->from('wallet')->get()->row('amount');
    //                 //if($total < $wallet){
                        
    //                     return $left_bus = $wallet - $total;
    //                 //}else{
    //                   //  $left_bus = $wallet-$total;
    //                     //}
    //                     die;
    //      /*$left_bus = $this->db->where('user',$this->session->userdata('username'))
    //                 ->select('amount')
    //                 ->from('wallet')
    //                 ->get()->row('amount');*/
                    $precision = 2;
                        if ($left_bus < 1000) {
                            $n_format = number_format($left_bus, $precision);
                            $n_format.''.$suffix = '';
                            return floatval($n_format);
                        } else if ($left_bus < 99999) {
                            // 0.9k-850k
                            $n_format = number_format($left_bus / 1000, $precision);
                            return $n_format.''. $suffix = 'K';
                        } else if ($left_bus < 900000000) {
                            // 0.9m-850m
                            $n_format = number_format($left_bus / 1000000, $precision);
                            return $n_format.''.$suffix = 'M';
                        } else if ($left_bus < 900000000000) {
                            // 0.9b-850b
                            $n_format = number_format($left_bus / 1000000000, $precision);
                            return $n_format.''.$suffix = 'B';
                        } else {
                            // 0.9t+
                            $n_format = number_format($left_bus / 1000000000000, $precision);
                            return $n_format.''.$suffix = 'T';
                        }
                        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
                        // Intentionally does not affect partials, eg "1.50" -> "1.50"
                        if ( $precision > 0 ) {
                            $dotzero = '.' . str_repeat( '0', $precision );
                            $n_format = str_replace( $dotzero, '', $n_format );
                        }
    /*return $this->db->where('user',$this->session->userdata('username'))
                    ->select('amount')
                    ->from('wallet')
                    ->get()->row('amount');*/
}

protected function _directIncome($member){
  return $this->db->where('user_id',$member)
                  ->select('income')
                  ->from('directincome')
                  ->get()->row('income');
}


protected function _TransactionTableCount()
{
   /******** AUto TRANSFER*********/ 
   $data = $this->_countTransaction($this->session->userdata('username'));

   $total = 0;
   foreach ($data as $key) {
     $total += $key->t_amt;
   }
   return $total;
    
}

protected function _countTransaction($id)
  {
       return $this->db->where('user',$this->session->userdata('username'))
                        //->where('gate','app')    
                        ->where('dr_cr','Dr')    
                        //->where('order !=','Join Megacontest')
                        //->where('order !=','Upgrade Account')
                        ->where('order ','Withdraw Amount')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();  
  }

protected function _TotalDailyPayoutCount(){        
   return $this->register->daily_paise_widBal($this->session->userdata('username'));
}

protected function _mydirectTeam($member)
{
  return $this->db->query("SELECT count(id) as total FROM login where direct_sp='$member'")->row('total');

}

protected function _protectedTeamRightCount()
{

    return $this->_protectedFtechRightTotal($this->session->userdata('usrf'));
   

}

protected function _protectedFtechRightTotal($id){
    $data = $this->db->where('parent',$id)->where('teamside','Right')->select('*')->get('makebinary')->result();
    $i = 0;
    foreach ($data as $row) {
      
      $i++;

    }

    return $i;
}

protected function _protectedTeamLeftCount()
{

   $data = $this->db->where('parent',$this->session->userdata('usrf'))->where('teamside','Left')->select('*')->get('makebinary')->result();
    $i = 0;
    foreach ($data as $row) {
      
      $i++;

    }

    return $i;
}

//// Dashboard Count System end...=============================================

protected function _makeDirectIncomeUsingReferral($id){
  $data = $this->db->where('direct_sp',$id)
                   ->where('status !=','1')
                   ->select('member_id,side,package_amt')
                   ->from('login')
                   ->get()->result();
  $ref = $this->db->where('type','referral')
                  ->select('direct,amount')                     
                  ->get('single_leg')->row();
  
  $d = [];
  $loop =0;
  foreach($data as $row){
      $d[]= [
          'user'=>$loop,
          'bal'=>$row->package_amt*$ref->amount/100
          ];
  ++$loop;}

  $total = 0;
  for($c = 0; $c < count($d); $c++){
      $total += $d[$c]['bal'];
  }
  return [
        'user_id'=>$id,
        'income'=>$total,
        'direct'=>$loop
    ];  
  
}


protected function _directIncomeExist($id){
  return $this->db->where('user_id',$id)
                  ->select('user_id')
                  ->from('directincome')
                  ->get()->row();
}


public function makeLeftFirstTreedata($req,$sesId){
  $data = $this->db->select('member_id,activation,package_amt,status')
                   ->where('side','Left')
                   ->where('sponser',$req)
                   ->from('login')->get()->row();
    if(!empty($data)){               
    $employe = [];
        $emp = [
        'parent' =>$sesId,  
        'user_id'=> $data->member_id,
        'activation'=> $data->activation,
        'p_amt'=> $data->package_amt,
        'status'=> $data->status,
        'teamside'=>'Left'
    ];
        $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
        if(empty($empty)){
            $this->db->insert('makebinary',$emp);
        }
        $emp['child'] = $this->left_countApi($emp,$sesId);
        array_push($employe,$emp);
    return $employe;
  }

}

function left_countApi($emp,$sesId){
    $tree = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$emp)->from('login')->get()->result();  
    $employe = [];
    foreach($tree as $data){
        $emp = [
            'parent' =>$sesId,  
            'user_id'=> $data->member_id,
            'activation'=> $data->activation,
            'p_amt'=> $data->package_amt,
            'status'=> $data->status,
            'teamside'=>'Left'
        ];
        $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
        if(empty($empty)){
            $this->db->insert('makebinary',$emp);
            
        }
        $emp['child'] = $this->left_countApi($emp,$sesId);
        array_push($employe,$emp);
    }
    return $employe;
    
}//right inary start ;

public function makeRightFirstTreedata($req,$sesId){
        
    $data = $this->db->select('member_id,activation,package_amt,status')->where('side','Right')->where(array('sponser'=>$req))->from('login')->get()->row();
    if(!empty($data)){
      $employe = [];
        $emp = [
            'parent' =>$sesId,  
            'user_id'=> $data->member_id,
            'activation'=> $data->activation,
            'p_amt'=> $data->package_amt,
            'status'=> $data->status,
            'teamside'=>'Right'
        ];
        $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
        if(empty($empty)){
            $this->db->insert('makebinary',$emp);
        }
        $emp['child'] = $this->right_countApi($emp,$sesId);
        array_push($employe,$emp);
    
    return $employe;
    }
}

function right_countApi($emp,$sesId){
    $tree = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$emp)->from('login')->get()->result();
    $employe = [];
        foreach($tree as $data){
            $emp = [
                'parent' =>$sesId,  
                'user_id'=> $data->member_id,
                'activation'=> $data->activation,
                'p_amt'=> $data->package_amt,
                'status'=> $data->status,
                'teamside'=>'Right'
            ];
            $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
            if(empty($empty)){
                $this->db->insert('makebinary',$emp);
            }
            $emp['child'] = $this->right_countApi($emp,$sesId);
            array_push($employe,$emp);
        }
    return $employe;
}


public function requestExistOrnot($id,$req){
    return $this->db->where('user_id',$req)->where('parent',$id)->select('user_id')->get('makebinary')->row();
}

protected function _fetchLastRightId($id){
    return $this->db->where('parent',$id)->where('teamside','Right')->select('user_id')->get('makebinary')->result_array();   
}

// protected function _fetchMemberId($id,$rightFetch){
//     return $this->db->where('parent',$id)->where('teamside','Right')->limit($rightFetch)->select('user_id')->get('makebinary')->result_array();   
// }

// function getFirst($id,$sesId){
//     $children = array();
//     $data = $this->db->select('member_id,activation,package_amt,status')->where('side','Left')->where('sponser',$id)->from('login')->get()->result();
//     foreach($data as $row) {
//         $child_id = $row->member_id;
//         $children[$child_id] = array(
//                 'parent' =>$sesId,  
//                 'user_id'=> $row->member_id,
//                 'activation'=> $row->activation,
//                 'p_amt'=> $row->package_amt,
//                 'status'=> $row->status,
//                 'teamside'=>'Left'
//             );
//         $empty =  $this->db->where('user_id',$children[$child_id]['user_id'])->where('parent',$children[$child_id]['parent'])->select('user_id')->get('makebinary')->row();
//         if(empty($empty)){
//             $this->db->insert('makebinary',$children[$child_id]);
//         }
//         $children = array_merge($children, $this->getAllDownlines($child_id,$sesId));
//     }
//      return $children;
// }

// function getAllDownlines($child_id,$sesId) {
//     $data = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$child_id)->from('login')->get()->result();  
//     $new_father_ids = array();
//     $children = array();
//     foreach ($data as $child) {
//         $children[$child->member_id] = array(
//                 'parent' =>$sesId,  
//                 'user_id'=> $child->member_id,
//                 'activation'=> $child->activation,
//                 'p_amt'=> $child->package_amt,
//                 'status'=> $child->status,
//                 'teamside'=>'Left'
//             ); // etc

//         // $empty =  $this->db->where('user_id',$children[$child->member_id]['user_id'])->where('parent',$children[$child->member_id]['parent'])->select('user_id')->get('makebinary')->row();
//         // if(empty($empty)){
//         //     $this->db->insert('makebinary',$children[$child->member_id]);
            
//         // }
        
//         $new_father_ids[] = $child->member_id;
//         $children = array_merge($children, $this->getAllDownlines($new_father_ids,$sesId));    
//     }
    
//     return $children;
// }
public function user()
{   
    $sesId = $this->session->userdata('usrf');
    $ses = $this->session->userdata('username');
    // echo '<pre>';
    // print_r($this->getFirst($ses,$sesId));
    // echo '<pre>';
    $this->makeLeftFirstTreedata($ses,$sesId);
    $this->makeRightFirstTreedata($ses,$sesId);
    // die;
    $this->load->view('admin/include/header');
    $this->load->view('admin/dashboard');
    $this->load->view('admin/include/footer');

}

public function anytimerun(){
    $sessid = $this->session->userdata('username');// main sessid;
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
    
            
    $right = $this->_protectedFetchRightTeam();
    $left = $this->_protectedFetchLeftTeam();
    $rightD = $this->_directRightUser();
    $leftD = $this->_directLeftUser();
    
    $binary =[];
    if(!empty($rightD) && !empty($leftD)) {
    //$binary = [];
    if(!empty($right) && !empty($left)){
      
      $rightTotal = 0;
          foreach ($right as $key) {
            $rightTotal += $key['p_amt'];
          }
          $leftTotal = 0;
          foreach ($left as $key) {
            $leftTotal += $key['p_amt'];
          }

      $exist = $this->_fetchLastBinary($this->session->userdata('username'));
        if(!empty($exist)){

            $rightE = $this->_protectedFetchRightTeam();
            $leftE = $this->_protectedFetchLeftTeam();
            $rightETotal = 0;
            foreach ($rightE as $key) {
              $rightETotal += $key['p_amt'];
            }
            $leftETotal = 0;
            foreach ($leftE as $key) {
              $leftETotal += $key['p_amt'];
            } 
                  $pre = $this->_previousBinaryRecord($this->session->userdata('username'));
                  if($pre->side == 'Right'){
                      $finalRight = $rightETotal - $exist->right_id;
                      $finalLeft = $leftETotal - $exist->left_id;

                      if($finalLeft != 0 && $finalRight !=0){
                        if($finalRight > $finalLeft){
                             
                              $binary[] = [ 
                                'userid'=>$this->session->userdata('username'),
                                'amount'=>$finalLeft * $this->_fetchPercent()/100,
                                'carry'=>$finalRight - $finalLeft,
                                'side'=>'Right',
                                'right_id'=>$finalLeft + $exist->right_id,
                                'left_id'=>$finalLeft + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        } elseif ($finalRight < $finalLeft) {
                          
                          $binary[] = [ 
                                'userid'=>$this->session->userdata('username'),
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Left',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        }else{
                          
                          $binary[] = [ 
                                'userid'=>$this->session->userdata('username'),
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Null',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];
                        }  
                      }
                      
                  } elseif ($pre->side == 'Left') {

                      $finalLeft = $leftETotal - $exist->left_id;
                      $finalRight = $rightETotal - $exist->right_id;

                      if($finalLeft != 0 && $finalRight !=0){
                        
                        if($finalRight > $finalLeft){
                             
                              $binary[] = [ 
                                'userid'=>$this->session->userdata('username'),
                                'amount'=>$finalLeft * $this->_fetchPercent()/100,
                                'carry'=>$finalRight - $finalLeft,
                                'side'=>'Right',
                                'right_id'=>$finalLeft + $exist->right_id,
                                'left_id'=>$finalLeft + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        } elseif ($finalRight < $finalLeft) {
                          
                          $binary[] = [ 
                                'userid'=>$this->session->userdata('username'),
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Left',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        }else{
                          
                          $binary[] = [ 
                                'userid'=>$this->session->userdata('username'),
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Null',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];
                          }  

                      } 

        }else{
      
                $finalLeft = $leftETotal - $exist->left_id;
                $finalRight = $rightETotal - $exist->right_id;

                  if($finalLeft != 0 && $finalRight !=0){
                    
                    if($finalRight > $finalLeft){
                         
                          $binary[] = [ 
                            'userid'=>$this->session->userdata('username'),
                            'amount'=>$finalLeft * $this->_fetchPercent()/100,
                            'carry'=>$finalRight - $finalLeft,
                            'side'=>'Right',
                            'right_id'=>$finalLeft + $exist->right_id,
                            'left_id'=>$finalLeft + $exist->left_id,
                            'created_at'=>date('Y-m-d'),
                          ];

                    } elseif ($finalRight < $finalLeft) {
                      
                      $binary[] = [ 
                            'userid'=>$this->session->userdata('username'),
                            'amount'=>$finalRight * $this->_fetchPercent()/100,
                            'carry'=>$finalLeft - $finalRight,
                            'side'=>'Left',
                            'right_id'=>$finalRight + $exist->right_id,
                            'left_id'=>$finalRight + $exist->left_id,
                            'created_at'=>date('Y-m-d'),
                          ];

                    }else{
                      
                      $binary[] = [ 
                            'userid'=>$this->session->userdata('username'),
                            'amount'=>$finalRight * $this->_fetchPercent()/100,
                            'carry'=>$finalLeft - $finalRight,
                            'side'=>'Null',
                            'right_id'=>$finalRight + $exist->right_id,
                            'left_id'=>$finalRight + $exist->left_id,
                            'created_at'=>date('Y-m-d'),
                          ];
                    }  
              }
      }
          

           

        }else{// if condition end else start;            
        /// count b usineess notghing else;

        if( $rightTotal > $leftTotal){
                  $binary[] = [ 
                  'userid'=>$this->session->userdata('username'),
                  'amount'=>$leftTotal * $this->_fetchPercent()/100,
                  'carry'=>$rightTotal - $leftTotal,
                  'side'=>'Right',
                  'right_id'=>$leftTotal,
                  'left_id'=>$leftTotal,
                  'created_at'=>date('Y-m-d')
                ];
                  
          


        }elseif ( $rightTotal < $leftTotal) {

          
                  $binary[] = [ 
                  'userid'=>$this->session->userdata('username'),
                  'amount'=>$rightTotal * $this->_fetchPercent()/100,
                  'carry'=>$leftTotal - $rightTotal,
                  'side'=>'Left',
                  'right_id'=>$rightTotal,
                  'left_id'=>$rightTotal,
                  'created_at'=>date('Y-m-d')
                ];
                  
          
        }else{

          
                  $binary[] = [ 
                  'userid'=>$this->session->userdata('username'),
                  'amount'=>$rightTotal * $this->_fetchPercent()/100,
                  'carry'=>$rightTotal - $leftTotal,
                  'side'=>'Null',
                  'right_id'=>$rightTotal,
                  'left_id'=>$rightTotal,
                  'created_at'=>date('Y-m-d')
                ];
                  
          
          

        }//insert condition end;
      }
    }
    
    for ($i=0; $i < count($binary) ; $i++) { 
        if(!empty($binary[$i])){
          if(!empty($this->_protectedFunctiontestfetchToday($this->session->userdata('username'),date('Y-m-d')))){
              $total = $binary[$i]['amount'] + $this->fetchPerviousAmount($this->session->userdata('username'),date('Y-m-d'));
               $this->db->where('userid',$binary[$i]['userid'])
                         ->where('created_at',$binary[$i]['created_at'])
                         ->set('amount',$total)
                         ->set('right_id',$binary[$i]['right_id'])
                         ->set('left_id',$binary[$i]['left_id']) 
                         ->set('carry',$binary[$i]['carry']) 
                         ->set('side',$binary[$i]['side']) 
                         ->update('binary');      

          }else{
            $this->db->insert('binary',$binary[$i]);
          
          }
            
        }
    }

  }
    //bv caluclations
    $bvMatching = $this->_fetchMatchingBusinessBinary($this->session->userdata('username'));
    $bvArray = [
          'userid' => $this->session->userdata('username'),
          'RightBv' => $bvMatching/$this->_bvMakeFunctionAmount(),
          'created' => date('Y-m-d')
      ];
    if(!empty($bvArray)){
          if(!empty($this->_protectedFetchBvExistOrnot($this->session->userdata('username')))){
              $this->db->where('userid',$bvArray['userid'])
                       ->set('RightBv',$bvArray['RightBv'])
                       ->set('created',$bvArray['created'])
                       ->update('bv');
          }else{

              $this->db->insert('bv',$bvArray);
          }
        }
    //end;
    //direct spopnser 
    $referral[] = $this->_makeDirectIncomeUsingReferral($this->session->userdata('username'));
    for($i=0; $i<count($referral); $i++){
      if(!empty($referral[$i])){   
          //echo '<pre>';
          //print_r($referral[$i]);
          if(!empty($this->_directIncomeExist($referral[$i]['user_id']))){
              $this->db->where('user_id',$referral[$i]['user_id'])
                      ->set('income',$referral[$i]['income'])
                      ->set('direct',$referral[$i]['direct'])
                      ->update('directincome');       
                //  echo 'Referral update '.$referral[$i]['user_id'].',';
          }
          else{
              $this->db->insert('directincome',$referral[$i]);
              //echo 'Referral insert '.$referral[$i]['user_id'].',';
          }
      }else{
          
      }
  }
    //end
    $fetchCapping = $this->_protectedMakeCappingdata($this->session->userdata('username'),date('Y-m-d'));
    $cappingRecord =[];
    foreach ($fetchCapping as $key) {
      $cappingRecord[] =[
          'userid'=>$key['userid'],
          'amount'=>$this->_cappingPriceGenerate($key['userid'],$key['created_at']),
          'created_at'=>$key['created_at']
      ];
    }
    for ($c=0; $c < count($cappingRecord) ; $c++) { 

        if(!empty($cappingRecord[$c])){
            if(!empty($this->_cappingExist($this->session->userdata('username'),date('Y-m-d')))){
                  $this->db->where('userid',$cappingRecord[$c]['userid'])
                           ->where('created_at',$cappingRecord[$c]['created_at'])
                           ->set('amount',$cappingRecord[$c]['amount']) 
                           ->update('userbinary');

            }else{
                $this->db->insert('userbinary',$cappingRecord[$c]);
              
            }
        }
      
    }
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

protected function _protectedFetchBvExistOrnot($id){
  return $this->db->where('userid',$id)->select('userid')->get('bv')->row();
}

protected function _bvMakeFunctionAmount(){
  return $this->db->where('type','BV')->select('amount')->get('single_leg')->row('amount');
}

protected function _fetchMatchingBusinessBinary($id){
  $id =  $this->db->where('userid',$id)->order_by('id','desc')->limit('1')->select('right_id')->get('binary')->row();
  if(!empty($id)){
      return $id->right_id;
  }else{
      return 0;
  }
}

protected function _directRightUser(){
  return $this->db->where('direct_sp',$this->session->userdata('username'))->where('status !=','1')->where('side','Right')->select('member_id')->get('login')->row('member_id');
}

protected function _directLeftUser(){
  return $this->db->where('direct_sp',$this->session->userdata('username'))->where('status !=','1')->where('side','Left')->select('member_id')->get('login')->row('member_id');
}
protected function _cappingPriceGenerate($id,$today){
    $cappingRatio =  $this->_fetchcapping();
    $binaryToday = $this->db->where('userid',$id)->where('created_at',$today)->select('amount')->get('binary')->row('amount'); 
    $package = $this->_fetchUIserPackageDetails($id);
    $cappincome = $package * $cappingRatio;

    if($binaryToday > $cappincome){
        return $cappincome;
    }elseif($binaryToday < $cappincome){
        return $binaryToday;
    }else{
      return $binaryToday;
    }
}

protected function _fetchUIserPackageDetails($id){
  return $this->db->where('member_id',$id)->select('package_amt')->get('login')->row('package_amt');
}
protected function _cappingExist($id,$today){
    return $this->db->where('userid',$id)->where('created_at',$today)->select('userid')->get('userbinary')->row();
}

protected function _fetchcapping(){
  return $this->db->where('type','capping')
                  ->where('isActive','0')
                  ->select('amount')
                  ->get('single_leg')->row('amount');
}

protected function _protectedMakeCappingdata($id,$today){
   return $this->db->where('userid',$id)->where('created_at',$today)->select('*')->get('binary')->result_array(); 
}

protected function fetchPerviousAmount($id,$today){
  return $this->db->where('userid',$id)->where('created_at',$today)->select('amount')->get('binary')->row('amount'); 
}

protected function _protectedFunctiontestfetchToday($id,$today){
    return $this->db->where('userid',$id)->where('created_at',$today)->select('userid')->get('binary')->row();
}

protected function _previousBinaryRecord($id){
  return $this->db->where('userid',$id)
                  ->order_by('id','desc')
                  ->select('userid,amount,side,carry,right_id,left_id')
                  ->from('binary')
                  ->get()->row();
}

protected function _fetchPercent(){
  return $this->db->where('type','binary')
                  ->where('isActive','0')
                  ->select('amount')
                  ->get('single_leg')->row('amount');
}




protected function _fetchLastBinary($id){
  return $this->db->where('userid',$id)->select('right_id,left_id,carry')->order_by('id','desc')->get('binary')->row();
}

protected function _protectedFetchRightTeam(){
  return $this->db->where('parent',$this->session->userdata('usrf'))
                  ->where('teamside','Right')
                  ->select('p_amt,id,user_id')
                  ->order_by('id','asc')
                  ->where('status !=','1')
                  ->get('makebinary')->result_array();
}

protected function _protectedFetchLeftTeam(){
  return $this->db->where('parent',$this->session->userdata('usrf'))
                  ->where('teamside','Left')
                  ->select('p_amt,id,user_id')
                  ->where('status !=','1')
                  ->order_by('id','asc')
                  ->get('makebinary')->result_array();
}

public function password_update()
{
    $this->load->view('admin/include/header');
    $this->load->view('admin/updatepassword');
    $this->load->view('admin/include/footer');
}


public function profile_update()
{
    //$data['account'] = $this->register->Accountinfo();
    $data['profile'] = $this->register->profileinfo();
    $data['contact'] = $this->register->contacteinfo();
    $data['nomiee'] = $this->register->nomineeinfo();
    $this->load->view('admin/include/header');
    $this->load->view('admin/updateprofile',$data);
    $this->load->view('admin/include/footer');
}

function upadate_nominee()
{
    $this->load->library('form_validation');
    $this->form_validation->set_rules('nominee_name','Nominee Name','required');
    $this->form_validation->set_rules('relation','Nominee Relation.','required');
    
    if($this->form_validation->run())
    {
        $data = array(
        'nominee_name'=>$this->input->post('nominee_name'),
            'nominee_reltion'=>$this->input->post('relation'),
            
            'modify_date'=>date('d M, Y h:i A')
        );
        
        $ok = $this->register->nominee_update($data);
            if($ok == '1')
            {
            
              $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Account Details updated successfully",
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
                redirect(base_url().'my/profile/update-profile');   
            }
            else{
                  $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Oops! We can not update this time",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'my/profile/update-profile'); 
            }
    }
    else{
        
        $this->profile_update();
    }
    
}
public function upadate_account()
{
    
        $this->load->library('form_validation');
    $this->form_validation->set_rules('acno','account no','trim|required|min_length[10]|max_length[16]');
    
    $this->form_validation->set_rules('b_name','Bank Name.','required');
    $this->form_validation->set_rules('branch_name','Branch Name.','required');
    $this->form_validation->set_rules('ifsc','IFSC Code.','trim|required');
    
    $this->form_validation->set_rules('pan','Pan Number','trim|required');
    if($this->form_validation->run())
    {
        $data = array(
        'ac'=>$this->input->post('acno'),
            'bank_name'=>$this->input->post('b_name'),
            'branch_name'=>$this->input->post('branch_name'),
            'ifsc_code'=>$this->input->post('ifsc'),
            'pan_no'=>$this->input->post('pan'),
            'modify_date'=>date('d M, Y h:i A')
        );
        
        $ok = $this->register->account_update($data);
            if($ok == '1')
            {
                
              $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Account Details updated successfully",
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
                redirect(base_url().'my/profile/update-profile');   
            }
            else{
                  $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Oops! We can not update this time",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'my/profile/update-profile'); 
            }
    }
    else{
        
        $this->profile_update();
    }
    
    
}
public function add_account()
{
    
        $this->load->library('form_validation');
    $this->form_validation->set_rules('acno','account no','trim|required|min_length[10]|max_length[16]');
    
    $this->form_validation->set_rules('b_name','Bank Name.','required');
    $this->form_validation->set_rules('branch_name','Branch Name.','required');
    $this->form_validation->set_rules('ifsc','IFSC Code.','trim|required');
    
    $this->form_validation->set_rules('pan','Pan Number','trim|required');
    if($this->form_validation->run())
    {
        $data = array(
        'ac'=>$this->input->post('acno'),
            'bank_name'=>$this->input->post('b_name'),
            'branch_name'=>$this->input->post('branch_name'),
            'ifsc_code'=>$this->input->post('ifsc'),
            'pan_no'=>$this->input->post('pan'),
            'user_id'=>$this->session->userdata('username'),
            'modify_date'=>date('d M, Y h:i A')
        );
        
        $ok = $this->register->account_add($data);
            if($ok == '1')
            {
             
               $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Account Details updated successfully",
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
                redirect(base_url().'my/profile/update-profile');   
            }
            else{
                  $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Oops! We can not update this time",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'my/profile/update-profile'); 
            }
    }
    else{
        
        $this->profile_update();
    }
    
    
}
public function upadate_contact()
{
        $data = array(
        'address'=>$this->input->post('address'),
            'city'=>$this->input->post('City'),
            'state'=>$this->input->post('State'),
            'district'=>$this->input->post('District'),
            'postal'=>$this->input->post('postalcode'),
            'email'=>$this->input->post('email'),
            'phone'=>$this->input->post('phone'),
            'modify_date'=>date('d M, Y h:i A')
        );
        
        $app = array(
        'email'=>$this->input->post('email'),
            'phone'=>$this->input->post('phone')
        );
        
        $this->db->where('user',$this->session->userdata('username'))->set($app)->update('gamelogin');
        $ok = $this->register->profile_update($data);
            if($ok == '1')
            {
                
              
              $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Contact Details updated successfully",
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
                redirect(base_url().'my/profile/update-profile');   
            }
            else{
                  
                  $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Oops! We can not update this time",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'my/profile/update-profile'); 
            }
    
    
    
}
public function upadate_profile()
{
        $data = array(
        'name'=>$this->input->post('name'),
            'father_name'=>$this->input->post('father_name'),
            'dob'=>$this->input->post('dob'),
            'gender'=>$this->input->post('gender'),
            'modify_date'=>date('d M, Y h:i A')
        );
        
        $app = array(
        'name'=>$this->input->post('name'),
        );
        
        $this->db->where('user',$this->session->userdata('username'))->set($app)->update('gamelogin');
        
        $ok = $this->register->profile_update($data);
            if($ok == '1')
            {
              
                        $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Profile Details updated successfully",
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
                redirect(base_url().'my/profile/update-profile');   
            }
            else{
                  $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Pofile updation",
                          content: "Oops! We can not update this time",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                  
                redirect(base_url().'my/profile/update-profile'); 
            }

          
    
    
    
}



public function left_team_count()
{   
    $this->load->view('admin/include/header');
    $this->load->view('admin/left_team'); 
    $this->load->view('admin/include/footer');   
}

function member_activate()
{
    $id =$this->input->post('member_id');
    $scratch = $this->input->post('scratch');
    $pinm = $this->input->post('Pin');
    if($scratch =="")
    {
      
      $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation error",
                          content: "Scarcth id required.",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'member-activate?q='.$id.'');   
    }
    elseif($id == "")
    {
          $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation error",
                          content: "Member id required.",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'member-activate?q='.$id.'');   
    }
    elseif($pinm == "")
    {
            $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation error",
                          content: "Pin required",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
          
                redirect(base_url().'member-activate?q='.$id.'');   
    }
    else{

        $pinExist = $this->_PinExistFunction($pinm,$id);
        if(!empty($pinExist)){
          $this->session->set_flashdata('acc','
              $.confirm({
                    title: "Activation error",
                    content: "You allready used this pin.",
                    type: "red",keys: ["Enter"],
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: "Close",
                            btnClass: "btn-red",
                            close: function() {}
                        }
                    }
                });');
           
                redirect(base_url().'member-activate?q='.$id.'');   
        }
        else{
            /*21875912 */
        $pin = $this->register->fetch_pin($pinm);
        
        if(empty($pin))
        {
            
            $this->session->set_flashdata('acc','
              $.confirm({
                    title: "Activation error",
                    content: "Your pin is invalid try again.",
                    type: "red",keys: ["Enter"],
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: "Close",
                            btnClass: "btn-red",
                            close: function() {}
                        }
                    }
                });');
          //redirect(base_url().'my/team/Genealogy/'.$this->input->post('member_id')); 
            redirect(base_url().'member-activate?q='.$id.'');   
        }
        elseif($pin->scratch != $scratch)
        {
            //echo 'not matched scratch';
          $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation error",
                          content: "Scratch not matched",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
            redirect(base_url().'member-activate?q='.$id.'');   
        }
        elseif($pin->user_id != $this->session->userdata('username'))
        {
            //echo 'you do not have scarcth ids';
          $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation error",
                          content: "You don\' have any pin.",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
          redirect(base_url().'member-activate?q='.$id.'');   
        }
        elseif($pin->usedORnot != 'unused')
        {
            //echo 'this id has been used';
          $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation error",
                          content: "Pin allready used.",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
            redirect(base_url().'member-activate?q='.$id.'');   
        }
        else{
            $this->_protectedFunctionMakeRoyaluser($id,$pin->price);
            //$this->_directMember($id);
            if($this->register->infoUpdate($id,$pinm,$scratch,$pin->price,$pin->package_name))
            {
              
              $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation success",
                          content: "Memeber has been activated.",
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
              redirect(base_url().'member-activate?q='.$id.'');   
            }
            else{
              $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "Activation error",
                          content: "Memeber not activated.",
                          type: "red",keys: ["Enter"],
                          typeAnimated: true,
                          buttons: {
                              tryAgain: {
                                  text: "Close",
                                  btnClass: "btn-red",
                                  close: function() {}
                              }
                          }
                      });');
                redirect(base_url().'member-activate?q='.$id.'');   
            }

            
        }
    
        }

      }
    
}

protected function _protectedFunctionMakeRoyaluser($id,$amt){
  
    $idi = $this->db->where('rank','Single Joining')->select('id')->from('single_leg')->get()->row('id');
    $day = $this->db->where('rank','Single Joining')->select('days')->from('single_leg')->get()->row('days');
    $inc = array(
      'tableId'=>$idi,
      'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +".$day." day"))
    );
    $this->db->where('user_id',$id)->set(array('p_amt'=>$amt,'activation'=>date('Y-m-d'),'status'=>'0'))->update('makebinary');       
    
  
    $this->db->where('member_id',$id)
             ->set($inc)
             ->update('login');
}

protected function _protectedFecthGetUserInfomation($id){
    return $this->db->where('member_id',$id)
                    ->select('name,email,member_id,pass,phone')
                    ->from('login')
                    ->get()->row();
}

  
protected function _PinExistFunction($pinm,$id){
  return $this->db->where('member_id',$id)
                  ->where('pin',$pinm)
                  ->get('login')->row();
}

// site setting

public function scratch_card_details()
{
  if($_GET['q']){
    switch ($_GET['q']) {
        case 'my-pin':
            $this->load->view('admin/include/header');
            $this->load->view('admin/scratch'); 
            $this->load->view('admin/include/footer');           
            break;
        case 'my-request':
            $this->load->view('admin/include/header');
            $this->load->view('admin/e-pin_request'); 
            $this->load->view('admin/include/footer');          
            break;
        case 'raise-request':
            $this->load->view('admin/include/header');
            $this->load->view('admin/e-request');
            $this->load->view('admin/include/footer');  
            break;
        default:
              
            break;  
    }
  }else{
      redirect(base_url());
  }

}


  function scratchAllData()
  {
    $data = array();
        if(!empty($this->register->fetch_scratch()))
        {
        //total rows count
        $totalRec = count($this->register->fetch_scratch());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#scarcthData';
        $config['base_url']    = base_url().'scratchDefaultAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'scratchAjax';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->fetch_scratch(array('limit'=>10));
                
        echo '<div class="table-responsive">
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Pin Number</th>
                        <th>Scratch Number</th>
                        <th>Amount</th>
                        <th>Package Name</th>
                        <th>Date/Time</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                          echo '<tr class="';
                              if($row['usedORnot'] == 'used'){echo 'bg-danger text-white';}else{echo '';} echo '">
                        <td>'.$num.'</td>
                        <td>'.$row['pin'].'</td>
                        <td>'.$row['scratch'].'</td>
                        <td>'.$row['price'].'</td>
                        <td>'.$row['package_name'].' ('.$row['price'].'/-);</td>
                        <td>'.$row['date'].'</td>
                        <td>'.$row['usedORnot'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="7">
                                <div class="text-center">No Scratch Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
        echo '</div>
    </div>
</div>';

      }

 function scarcthDataAjaxPaginate()
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
        $type = $this->security->xss_clean($this->input->post('type'));
        if(!empty($type)){
            $conditions['search']['type'] = $type;
        }
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->fetch_scratch($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->fetch_scratch($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#scarcthData';
        $config['base_url']    = base_url().'scratchDefaultAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'scratchAjax';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->fetch_scratch($conditions);
                
        echo '<div class="table-responsive">
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Pin Number</th>
                        <th>Scratch Number</th>
                        <th>Amount</th>
                        <th>Package Name</th>
                        <th>Date/Time</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                          echo '<tr class="';
                              if($row['usedORnot'] == 'used'){echo 'bg-danger text-white';}else{echo '';} echo '">
                        <td>'.$num.'</td>
                        <td>'.$row['pin'].'</td>
                        <td>'.$row['scratch'].'</td>
                        <td>'.$row['price'].'</td>
                        <td>'.$row['package_name'].' ('.$row['price'].'/-);</td>
                        <td>'.$row['date'].'</td>
                        <td>'.$row['usedORnot'].'</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="7">
                                <div class="text-center">No Scratch Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
        echo '</div>
    </div>
</div>';
       }    


function all_requestUser(){
     $data = array();
        if(!empty($this->register->my_request()))
        {
        //total rows count
        $totalRec = count($this->register->my_request());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#e_pin_request';
        $config['base_url']    = base_url().'e_requestUserAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'e_request';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->my_request(array('limit'=>10));
         echo '<div class="table-responsive">
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Memeber Id</th>
                        <th>Pin Name</th>
                        <th>Price/Id\'s</th>
                        <th>Total Amount</th>
                        <th>Date/Time</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                  echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['s_id'].'</td>
                        <td>'.$row['s_name'].'</td>
                        <td>Rs. '.$row['s_price'].' x '.$row['number'].'</td>
                        <td>Rs. '.$row['s_total_price'].'/-</td>
                        <td>'.$row['date'].'</td>
                        <td>'; if($row['status']=='NOT DONE') echo 'Pending'; else echo 'DONE';echo '</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="7">
                                <div class="text-center">No Request Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
  
}

function e_request_ajax_show(){
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
        
        if(!empty($this->register->my_request($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->my_request($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#e_pin_request';
        $config['base_url']    = base_url().'e_requestUserAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'e_request';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->my_request($conditions);
        echo '<div class="table-responsive">
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Memeber Id</th>
                        <th>Pin Name</th>
                        <th>Price/Id\'s</th>
                        <th>Total Amount</th>
                        <th>Date/Time</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>';
                      if(!empty($posts)) {$num=1; foreach ($posts as $row) {
                          
                  echo '<tr class="">
                        <td>'.$num.'</td>
                        <td>'.$row['s_id'].'</td>
                        <td>'.$row['s_name'].'</td>
                        <td>Rs. '.$row['s_price'].' x '.$row['number'].'</td>
                        <td>Rs. '.$row['s_total_price'].'/-</td>
                        <td>'.$row['date'].'</td>
                        <td>'; if($row['status']=='NOT DONE') echo 'Pending'; else echo 'DONE';echo '</td>
                      </tr>';

                         $num++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="7">
                                <div class="text-center">No Request Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();
        }
    
    function transactionRequestDetails(){
        $this->load->view('admin/include/header');
            $this->load->view('admin/transaction'); 
            $this->load->view('admin/include/footer');
    }
    
    function transactionPagedata(){
        $data = array();
        if(!empty($this->register->transactionUser()))
        {
        //total rows count
        $totalRec = count($this->register->transactionUser());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#transactionRequest';
        $config['base_url']    = base_url().'transactionAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'transactionPage';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->transactionUser(array('limit'=>10));
         echo '
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Txn</th>
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
                              <td colspan="8">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();   
    }
    
    function transactionAjaxFunction(){
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
        
        if(!empty($this->register->transactionUser($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->transactionUser($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#transactionRequest';
        $config['base_url']    = base_url().'transactionAjax/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'transactionPage';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->transactionUser($conditions);
          echo '
                  <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Txn</th>
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
                              <td colspan="8">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';
            echo $this->ajax_pagination->create_links();   
    }
    
      protected function _fetchCredentials(){
          return $this->db->select('smtp_user,smpt_pass,smpt_port,smtp_host,protocol,website_name')
                          ->from('admin')
                          ->get()->row();
      }  
      
    protected function _fetchEMail($id){
      return $this->db->where('member_id',$id)
                  ->select('email')
                  ->get('login')->row('email');
    }
    

    protected function _protectedFunctiontest($data){
      $credential = $this->_fetchCredentials();
      $config['protocol'] = $credential->protocol;
      $config['smtp_host'] = $credential->smtp_host;
      $config['smtp_port'] = $credential->smpt_port;
      $config['smtp_user'] = $credential->smtp_user;
      $config['smtp_pass'] = $credential->smpt_pass;
      $config['mailpath'] = '/usr/sbin/sendmail';
      $config['charset'] = 'iso-8859-1';
      $config['wordwrap'] = TRUE;
      $config['mailtype'] = 'html';
      $config['newline']    = "\r\n";
      $this->email->initialize($config);
      $this->email->from($data['from'],$data['title']);
      $this->email->to($data['to']);
      $this->email->subject($data['sub']);
      $this->email->message('<!DOCTYPE html>
                  <html>
                    <head>

                      <meta charset="utf-8">
                      <meta http-equiv="x-ua-compatible" content="ie=edge">
                      <title>'.$data['sub'].'</title>
                      <meta name="viewport" content="width=device-width, initial-scale=1">
                      <style type="text/css">
                      @media screen {
                        @font-face {
                          font-family: "Source Sans Pro";
                          font-style: normal;
                          font-weight: 400;
                          src: local("Source Sans Pro Regular"), local("SourceSansPro-Regular"), url('.base_url().'adminassets/fonts/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format("woff");
                        }

                        @font-face {
                          font-family: "Source Sans Pro";
                          font-style: normal;
                          font-weight: 700;
                          src: local("Source Sans Pro Bold"), local("SourceSansPro-Bold"), url('.base_url().'adminassets/fonts/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format("woff");
                        }
                      }

                      /**
                       * Avoid browser level font resizing.
                       * 1. Windows Mobile
                       * 2. iOS / OSX
                       */
                      body,
                      table,
                      td,
                      a {
                        -ms-text-size-adjust: 100%; /* 1 */
                        -webkit-text-size-adjust: 100%; /* 2 */
                      }

                      /**
                       * Remove extra space added to tables and cells in Outlook.
                       */
                      table,
                      td {
                        mso-table-rspace: 0pt;
                        mso-table-lspace: 0pt;
                      }

                      /**
                       * Better fluid images in Internet Explorer.
                       */
                      img {
                        -ms-interpolation-mode: bicubic;
                      }

                      /**
                       * Remove blue links for iOS devices.
                       */
                      a[x-apple-data-detectors] {
                        font-family: inherit !important;
                        font-size: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                        color: inherit !important;
                        text-decoration: none !important;
                      }

                      /**
                       * Fix centering issues in Android 4.4.
                       */
                      div[style*="margin: 16px 0;"] {
                        margin: 0 !important;
                      }

                      body {
                        width: 100% !important;
                        height: 100% !important;
                        padding: 0 !important;
                        margin: 0 !important;
                      }

                      /**
                       * Collapse table borders to avoid space between cells.
                       */
                      table {
                        border-collapse: collapse !important;
                      }

                      a {
                        color: #ffffff;
                        text-decoration: none;
                        font-family: monospace;
                      }

                      img {
                        height: auto;
                        line-height: 100%;
                        text-decoration: none;
                        border: 0;
                        outline: none;
                      }
                      </style>

                    </head>
                    <body style="background-color: #e9ecef;">

                      <!-- start preheader -->
                      <div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
                        '.$data['pre'].'
                      </div>
                      <!-- end preheader -->

                      <!-- start body -->
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">

                        <!-- start logo -->
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <!--[if (gte mso 9)|(IE)]>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                            <tr>
                            <td align="center" valign="top" width="600">
                            <![endif]-->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="center" valign="top" style="padding: 10px 0px;">
                                  <a href="'.base_url().'/api/form" target="_blank" style="display: inline-block;">
                                    <img src="'.base_url().'adminassets/site/yoyo_logo2.png" border="0" width="48" style="display: block; width: 100%; max-width: 50%; min-width: 50%;">
                                  </a>
                                </td>
                              </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                            </td>
                            </tr>
                            </table>
                            <![endif]-->
                          </td>
                        </tr>
                        <!-- end logo -->

                        <!-- start hero -->
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <!--[if (gte mso 9)|(IE)]>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                            <tr>
                            <td align="center" valign="top" width="600">
                            <![endif]-->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: "Source Sans Pro", Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                                  <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">'.$data['heading'].'</h1>
                                </td>
                              </tr>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                            </td>
                            </tr>
                            </table>
                            <![endif]-->
                          </td>
                        </tr>
                        <!-- end hero -->

                        <!-- start copy block -->
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <!--[if (gte mso 9)|(IE)]>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                            <tr>
                            <td align="center" valign="top" width="600">
                            <![endif]-->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">

                              <!-- start copy -->
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: "Source Sans Pro", Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                                  <p style="margin: 0;">
                                    '.$data['text'].'
                                  </p>
                                </td>
                              </tr>
                              <!-- end copy -->

                              <!-- start button -->
                              <tr>
                                <td align="left" bgcolor="#ffffff">
                                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                      <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td align="center" bgcolor="#1a82e2" style="border-radius: 6px;">
                                              <a href="'.base_url().'api/form" target="_blank" style="display: inline-block; padding: 25px 35px; font-family: "Source Sans Pro", Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">'.$data['btn'].'</a>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <!-- end button -->

                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                            </td>
                            </tr>
                            </table>
                            <![endif]-->
                          </td>
                        </tr>
                        <!-- end copy block -->
                      </table>
                      <!-- end body -->

                    </body>
                    </html>');
      $this->email->send();
      return $this->email->print_debugger();
    }

    function appaccessswitchcombination(){
        $st = $this->_protectedOnOffPaymentOption($_POST);
      
      if($st == '0'){
           $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "app access",
                          content: "you can not close app access",
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
                redirect(base_url().'my/dashboard');             
      }elseif($st == '1'){
          $all = $this->_protectedFecthGetUserInfomationlogin($this->session->userdata('username'));
          $data = [
              'name'=>$all->name,
              'email'=>$all->email,
              'user'=>$all->member_id,
              'pass'=>$all->pass,
              'phone'=>$all->phone,
              'status'=>'1'
              ];
    
              $this->db->insert('gamelogin',$data);      
          
           $this->session->set_flashdata('acc','
                    $.confirm({
                          title: "app access",
                          content: "now you can use our app to withdrow wallet balance.",
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
                redirect(base_url().'my/dashboard');             
      }
    }

    protected function _protectedFecthGetUserInfomationlogin($id){
    return $this->db->where('member_id',$id)
                    ->select('name,email,member_id,pass,phone')
                    ->from('login')
                    ->get()->row();
}

    protected function _protectedOnOffPaymentOption($req){
      $status = $this->_fetchStatusDefault();
      if ($status == 0) {
        $this->db->where('member_id',$this->session->userdata('username'))->set('appswitch',$req['appswitch'])->update('login');
        return '1';
      }elseif ($status == 1) {
        return '0';
      }else{
         return '0';
      }

  }

    protected function _fetchStatusDefault(){
    return $this->db->where('member_id',$this->session->userdata('username'))->select('appswitch')->get('login')->row('appswitch');
  }
  
  
    public function right_team_count()
    {
        if($_GET['q']){
            switch ($_GET['q']) {
                	case 'Right':
                		$this->load->view('admin/include/header');
                        $this->load->view('admin/right_team');  
                        $this->load->view('admin/include/footer'); 
                		break;
                		
                	case 'Left':
                		$this->load->view('admin/include/header');
                        $this->load->view('admin/right_team');  
                        $this->load->view('admin/include/footer'); 
                		break;	
                	
                	default:
                		# code...
                		break;
                }
        }else{
            
        }
        //$data['right'] = $this->register->right_team();   
          
    }
    
    
    function fetchTeamRecrdsods(){
        $data = array();
        if(!empty($this->register->teamFetchRecordSide()))
        {
        //total rows count
        $totalRec = count($this->register->teamFetchRecordSide());
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#myteamdata';
        $config['base_url']    = base_url().'myteamFetch/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'teambtn';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $posts = $this->register->teamFetchRecordSide(array('limit'=>10,'side'=>$this->input->post('side')));
        echo '
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member Id</th>
                    <th>Name</th>
                    <th>Package Name</th>
                    <th>Joining Date</th>
                    <th>Activation Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>';
            if(!empty($posts)) {$i=1; foreach ($posts as $row) {
        echo '
                <tr>
                    <td>'.$i.'</td>
                    <td>'.$row['user_id'].'</td>
                    <td>'.$this->db->where('member_id',$row['user_id'])->select('name')->get('login')->row('name').'</td>
                    <td>joining (Rs. '.$row['p_amt'].'-/)</td>
                    <td>'.$this->db->where('member_id',$row['user_id'])->select('time')->get('login')->row('time').'</td>
                    <td>'.$row['activation'].'</td>
                    <td>'; if($row['status'] == 1)
                                {echo '<i class="fa fa-circle text-warning"></i> In-active';} 
                        elseif($row['status'] == 2){
                            echo '<i class="fa fa-circle text-danger"></i> De-active';
                        } elseif($row['status'] == 3){
                            echo '<i class="fa fa-bane text-danger"></i> BLOCKED';
                        }elseif($row['status'] == 0){
                            echo '<i class="fa fa-check-circle text-success"></i> Active';} echo '</td>
                    <td>';
                     if($row['status'] =='1'){
                        echo "<a href='".base_url()."member-activate?q=".$row['user_id']."'><i class='fa fa-play-circle text-danger' style='font-size: 25px;'></i></a>";
                    } echo '</td>
                </tr>';
                    $i++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="7">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();   
    }
    
    
    function fetchUsingAjaxRecord(){
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
        $side = $this->security->xss_clean($this->input->post('side'));
        //echo $side;
        if(!empty($side)){
            $conditions['side'] = $side;
        }
        
        
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        if(!empty($this->register->teamFetchRecordSide($conditions)))
        {
        //total rows count
        $totalRec = count($this->register->teamFetchRecordSide($conditions));
        }
        else{
            $totalRec=0;
        }
        //pagination configuration
        $config['target']      = '#myteamdata';
        $config['base_url']    = base_url().'myteamFetch/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = 10;
        $config['link_func']   = 'teambtn';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->security->xss_clean($this->input->post('pageby'));
        
        //get posts data
        $posts = $this->register->teamFetchRecordSide($conditions);
        echo '
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member Id</th>
                    <th>Name</th>
                    <th>Package Name</th>
                    <th>Joining Date</th>
                    <th>Activation Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>';
            if(!empty($posts)) {$i=1; foreach ($posts as $row) {
        echo '
                <tr>
                    <td>'.$i.'</td>
                    <td>'.$row['user_id'].'</td>
                    <td>'.$this->db->where('member_id',$row['user_id'])->select('name')->get('login')->row('name').'</td>
                    <td>joining (Rs. '.$row['p_amt'].'-/)</td>
                    <td>'.$this->db->where('member_id',$row['user_id'])->select('time')->get('login')->row('time').'</td>
                    <td>'.$row['activation'].'</td>
                    <td>'; if($row['status'] == 1)
                                {echo '<i class="fa fa-circle text-warning"></i> In-active';} 
                        elseif($row['status'] == 2){
                            echo '<i class="fa fa-circle text-danger"></i> De-active';
                        } elseif($row['status'] == 3){
                            echo '<i class="fa fa-bane text-danger"></i> BLOCKED';
                        }elseif($row['status'] == 0){
                            echo '<i class="fa fa-check-circle text-success"></i> Active';} echo '</td>
                    <td>';
                     if($row['status'] =='1'){
                        echo "<a href='".base_url()."member-activate?q=".$row['user_id']."'><i class='fa fa-play-circle text-danger' style='font-size: 25px;'></i></a>";
                    } echo '</td>
                </tr>';
                    $i++; } }
                         else{
                          echo '
                            <tr>
                              <td colspan="7">
                                <div class="text-center">No Transaction Found</div>
                              </td>
                            </tr>
                          ';
                         }
                      
                      
                    echo '</tbody>
                  </table>';

                  echo $this->ajax_pagination->create_links();   
    }

}
  ?>