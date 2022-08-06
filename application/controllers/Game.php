<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Game extends CI_Controller {
	public function __construct()
 	{
  		parent::__construct();
  		
  		$this->load->model('register');
  		$this->load->helper('sms');
  		date_default_timezone_set('Asia/Kolkata');

 	}
    
    protected function _checkLogin(){
        
        if($this->session->userdata('gameuser') != '')
        {
          redirect(base_url().'api/game');
        }
        else{
        
            if (get_cookie('appuser') && get_cookie('apppass')) {
                $block = $this->_blockOrnotcheck(get_cookie('appuser'));
                if($block == 3){    
                
                }else{
                    if(!empty($this->_protectedLoginData(get_cookie('appuser')))){
                    $now_final_verify  = $this->_protectedDataLogin(get_cookie('appuser'));
                if($now_final_verify->pass == md5(get_cookie('apppass')))
                {
                    $session_data = array(
                      'gameuser' =>get_cookie('appuser'),
                      'name'=>$now_final_verify->name,
                      'phone'=>$now_final_verify->phone,
                      'email'=>$now_final_verify->email,
                    );
                    $this->session->set_userdata($session_data);
                    if($this->session->userdata('gameuser') != ''){
                        
                        redirect(base_url().'api/game');
                    }else{
                        
                    }
                }
            }
                }
            }
        }
    }
      
      
    
    function index(){
        $this->_checkLogin();
        $this->load->view('game/login');
    }
    
    

    function create_new_account(){
        $this->_checkLogin();
        $this->load->view('game/register');
    }

    function createNew_accountImmediate(){
      $this->load->library('form_validation');
      $this->form_validation->set_rules('phone','Phone','trim|required|min_length[10]|max_length[10]|numeric|is_unique[gamelogin.phone]');
      if($this->form_validation->run())
      {

            echo json_encode($this->_createNewAccount($this->security->xss_clean($_POST)));

      }else{
        $array = array(
          'error'   => true,
          'phone' => form_error('phone'),
          );
        echo json_encode($array);
      }
    }

    protected function _createNewAccount($req){
      //$otp = rand(00000,99999);
      //$otp = '0123456789';
	  $otp = str_shuffle(substr('0123456789',0,5));
      $sess = [
          'apiOtp'=>$otp,
          'phoneapi'=>$req['phone']
        ];
        $this->session->set_userdata($sess);  
        $field = array(
          "sender_id" => "SMSIND",
          "language" => "english",
          "route" => "qt",
          "numbers" => $req['phone'],
          "message" => "31991",
          "variables" => "{#AA#}",
          "variables_values" => "$otp"
      );
      $res = forget($field);
      if($res == 0){
          return ['ok'=>'we send Otp your register mobile'];    
      }else{
          return ['er'=>'something went wrong'];    
      }
      
      if($this->session->userdata('apiOtp' != '')){
        return [
            'er'=>'something went wrong.'
        ];
      }else{
        return [
            'msg'=>$req['phone'],
            'ok'=>'WE SEND OTP IN YOUR MOBILE.',
            'title'=>'REGISTER NEW USER'
          ];
      }

    }
    
    function otp_verifyCheck(){
      $this->load->helper('security');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('Otpveri','Otp','trim|required|min_length[5]|max_length[5]|numeric');
      if($this->form_validation->run())
      {

          //echo json_encode($_POST);
          echo json_encode($this->_verifyOtpfunction($this->security->xss_clean($_POST)));

      }else{
        $array = array(
          'error'   => true,
          'er' => form_error('Otpveri'),
          );
        echo json_encode($array);
      } 
    }

    protected function _verifyOtpfunction($req){
      if($this->session->userdata('apiOtp') == $req['Otpveri']){
            return $this->_addDataInDb($req);
      }else{
        return [
          'er'=>'OTP not matched.'
        ];
      }
    }

    protected function _addDataInDb($req){
      $user = substr(str_shuffle('ABCDEFHJIKLMNOPQRSTUVWXYZ1234567890'),0,6);
      $pass = rand(00000000,99999999);
      
      $dat = [
          'phone'=>$this->session->userdata('phoneapi')
      ];

      $this->db->insert('gamelogin',$dat);
      $this->session->set_userdata($dat);
      $this->session->unset_userdata('apiOtp');
      $link = base_url().'api/compelet-registration';
      return [
          'link'=>$link,
          'ok'=>'ok',
          'title'=>'Create account',
          'btn'=>'Next Step >',
          'msg'=>'Please complete your Registeration.'
      ];

    }

    function complete_registrationForm(){

      if($this->session->userdata('phone') !=''){
        $this->load->view('game/complete-create');
      }else{
        redirect(base_url().'api/form');
      }

    }

    function checkToFinsihAccount(){
      $this->load->helper('security');
      $this->load->library('form_validation');
      $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[gamelogin.email]');
      $this->form_validation->set_rules('user','Username','trim|required|max_length[12]|min_length[8]|is_unique[gamelogin.user]');
      $this->form_validation->set_rules('pass','Password','trim|required|max_length[12]|min_length[8]');
      $this->form_validation->set_rules('name','Full Name','trim|required');
      if($this->form_validation->run())
      {
          echo json_encode($this->_completeRegisterationForm($this->security->xss_clean($_POST)));

      }else{
        $array = array(
          'error'   => true,
          'a' => form_error('name'),
          'b' => form_error('email'),
          'c' => form_error('user'),
          'd' => form_error('pass')
          );
        echo json_encode($array);
      } 
    }

    protected function _completeRegisterationForm($req){
      $in = [
        'user'=>$req['user'],
        'name'=>$req['name'],
        'email'=>$req['email'],
        'pass'=>md5($req['pass'])
      ];
      $this->db->where('phone',$this->session->userdata('phone'))
               ->set($in)
               ->update('gamelogin');

      $session_data = array(
            'gameuser' =>$req['user'],
            'name'=>$req['name'],
            'phone'=>$this->session->userdata('phone'),
            'email'=>$req['email'],
            );
        $name = $req['name'];
        $fa = $req['user'];
        $mspa = $req['pass'];
        $this->session->set_userdata($session_data);
          $url = base_url().'api/game';
          $field = array(
            "sender_id" => "SMSIND",
            "language" => "english",
            "route" => "qt",
            "numbers" => $this->session->userdata('phone'),
            "message" => "31910",
            "variables" => "{#BB#}|{#CC#}",
            "variables_values" =>"$fa|$mspa"
        );
        forget($field);
          return [
              'ok'=>'ok',
              'title'=>'Registeration completed',
              'msg'=>'Thank you for Registeration your credentials<br>Your Username:'.$this->session->userdata('user').' <br> Your Password: '.$this->session->userdata('passsave').'',
              'btn'=>'Save',
              'link'=>$url
            ];         
          $this->session->unset_userdata('phone');
    }

    function forgetpasswordPageFunction(){
      $this->load->view('game/api_forget');
    }

     public function foregtFunction(){
        if(!empty($_POST['member'])){
            $credential = $this->_fetchCredentials();
            $exist = $this->_protectedCeheckUser($_POST);

            if(!empty($exist)){
                $rand = rand(11111,99999);
                $this->session->set_userdata('mil',$exist);
                $this->session->set_userdata('otp',$rand);
                $field = array(
                  "sender_id" => "SMSIND",
                  "language" => "english",
                  "route" => "qt",
                  "numbers" => $this->_fetchPhone($_POST['member']),
                  "message" => "27378",
                  "variables" => "{#AA#}",
                  "variables_values" => "$rand"
              );
              $res = forget($field);
              if($res == 0){
                  echo json_encode(['ok'=>'we send Otp your register mobile']);    
              }else{
                  echo json_encode(['er'=>'something went wrong']);    
              }
            }else{
                echo json_encode(['er'=>'inavlid user id.']);
                
            }
        }else{
            echo json_encode(['er'=>'user id required']);
            
        }
    }

    protected function _fetchEMail($id){
      return $this->db->where('user',$id)
                  ->select('email')
                  ->get('gamelogin')->row('email');
    }
    
    protected function _fetchPhone($data){
        return $this->db->where('user',$_POST['member'])
                        ->select('phone')
                        ->from('gamelogin')
                        ->get()->row('phone');
    }
    
    protected function _protectedCeheckUser($data){
        return $this->db->where('user',$_POST['member'])
                        ->select('user')
                        ->from('gamelogin')
                        ->get()->row('user');
    }


    function chnagePasswordFunctionTo(){
      $this->load->library('form_validation');
        $this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[12]');
        $this->form_validation->set_rules('cnfpassword','Confirm password','trim|required|min_length[6]|max_length[12]|matches[password]');
        if($this->form_validation->run())
        {
            $dat = $this->_protectedFunctionChangePassword($_POST['cnfpassword']);
            if($dat == 3){
                
                $data =array(
                  'from'=>'programmingfinger@gmail.com',
                  'title'=>'yoyo play',
                  'to'=>$this->_fetchEMail($this->session->userdata('chng')),
                  'sub'=>'change password',
                  'text'=>'your password has been changed successfully,if you not changed your password then complain to admin?',
                  'heading'=>'Password Changed',
                  'pre'=>'pass word has been changed successfully.',
                  'btn'=>'Sign in'
                );
                $this->_protectedFunctiontest($data);
                $this->session->sess_destroy();
                $url = base_url().'api/form';
                echo json_encode(['ok'=>$url]);   

            }
        }else{
            $array = array(
                'error'   => true,
                'cnf' => form_error('cnfpassword'),
                'new' => form_error('password')
            );
                          
            echo json_encode($array);
        }
    }

    protected function _protectedFunctionChangePassword($data){
        $this->db->where('user',$this->session->userdata('chng'))
                  ->set('pass',md5($data))
                  ->update('gamelogin');
        
        $this->db->where('member_id',$this->session->userdata('chng'))
                  ->set('pass',md5($data))
                  ->update('login');
                  
        return 3;
        
    }

    public function loginTOAccount(){
    $this->load->library('form_validation');
    $this->form_validation->set_rules('username','Username','trim|required|max_length[50]');
    $this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[12]');
    if($this->form_validation->run()){
      
      $username = $this->security->xss_clean($this->input->post("username"));
      $password = $this->security->xss_clean($this->input->post("password"));
      $passwordx = md5($password);
      $block = $this->_blockOrnotcheck($username);
        if($block == 3){  
          echo json_encode(['notfound'=>'Your account has been BLOCKED']);
        }else{
            
      if(!empty($this->_protectedLoginData($username))){
      $now_final_verify  = $this->_protectedDataLogin($username);
      if (ctype_upper(substr($username,'0','2'))) {       
        if($now_final_verify->pass == $passwordx){
          
          $session_data = array(
              'gameuser' =>$username,
              'name'=>$now_final_verify->name,
              'phone'=>$now_final_verify->phone,
              'email'=>$now_final_verify->email,
              );
            $this->session->set_userdata($session_data);
            if($this->session->userdata('gameuser') != ''){
                $this->input->set_cookie('appuser', $username, 31556926); /* Create cookie for store emailid */
                $this->input->set_cookie('apppass', $password, 31556926); /* Create cookie for password */
                $url = base_url().'api/game';
                echo json_encode(['link'=>$url]);    
                
            } else{
                echo json_encode(['notfound'=>'Oops! something went wrong']);
            }
            
        } else{

          echo json_encode(['notfound'=>'username and password is incorrect']);

        }
        
      } else{

          echo json_encode(['notfound'=>'username and password is incorrect']);

        }    


    }
    else{

      echo json_encode(['notfound'=>'username and password is incorrect']);

    }
  
  }

    } 
    else{
    //false
      $array = array(
        'error'   => true,
        'user' => form_error('username'),
        'pass' => form_error('password')
       );
    
      echo json_encode($array);
    }
  }
    protected function _blockOrnotcheck($username){
        return $this->db->where('user',$username)->select('status')->get('gamelogin')->row('status');
    }  
    protected function _protectedLoginData($username)
    {
        return $this->_fetchRecords($username);
    }
    protected function _protectedDataLogin($username)
    {
        return $this->_fetchRecords($username);
    }
    
    protected function _fetchRecords($username){
        $this->db->where('user',$username);
    	  //$this->db->where('status','1');
    	  $verfify = $this->db->get('gamelogin');
    	  return $verfify->row();
    }
    
    
    
    function logoutFunction(){
        delete_cookie('appuser'); /* Delete email cookie */
        delete_cookie('apppass'); /* Delete password cookie */
        $this->session->sess_destroy();
        redirect(base_url().'api/form');
    }
    
   protected function _fetchCredentials(){
      return $this->db->select('smtp_user,smpt_pass,smpt_port,smtp_host,protocol,website_name')
                      ->from('admin')
                      ->get()->row();
  }  

   protected function _protectedFunctiontest($data)
    {
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
 	
    
}
	?>