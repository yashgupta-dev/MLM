<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {      
        parent::__construct();
        $this->load->library('encryption');
        $this->load->model('register');
        $this->load->helper('sms');
        date_default_timezone_set('Asia/Kolkata');
        
    }
    
    protected function _checkLogin()
      {
        if($this->session->userdata('username') != '')
        {
          redirect(base_url().'my/dashboard');
        }else{}
      }
      
      public function accountLogin()
    {
        $this->_checkLogin();
        $this->load->view('login');
    }
   
    function checkLoginadmin()
    {
        if($this->session->userdata('admin') != '')
        {
          redirect(base_url().'admin/dashboard');
        }
        else{
          
        }
    }
    
    public function adminLogin()
    {
        $this->checkLoginadmin();
        $this->load->view('admin-login');
    }
    
    public function accountCreate()
    { 
      $this->load->view('register');
    }
    public function logintome(){
    $this->load->library('form_validation');
    $this->form_validation->set_rules('username','Username','trim|required|max_length[50]');
    $this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[20]');
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
              
            if ($this->security->xss_clean($this->input->post("remember_me")))
              {
                $this->input->set_cookie('username', $username, 31556926); /* Create cookie for store emailid */
                $this->input->set_cookie('password', $password, 31556926); /* Create cookie for password */
              }
              else{
                delete_cookie('username'); /* Delete email cookie */
                delete_cookie('password'); /* Delete password cookie */
              }
            $session_data = array(
                'username' =>$username,
                'name'=>$now_final_verify->name,
                'user_id'=>$now_final_verify->member_id,
                'email'=>$now_final_verify->email,
                'usrf'=>$now_final_verify->id,
                );
              $this->session->set_userdata($session_data);
              $url = base_url().'my/dashboard';
              echo json_encode(['link'=>$url]);
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

    protected function _protectedLoginData($username)
    {
        return $this->register->login_verify($username); 
    }
    protected function _protectedDataLogin($username)
    {
        return $this->register->login_verify($username);
    }
    
    // admin login start --------
    
    
    function admin_sign_in()  {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username','Username','trim|required|max_length[50]');
        $this->form_validation->set_rules('password','Password','trim|required|min_length[8]|max_length[20]');
        if($this->form_validation->run()){
          
          $username = $this->security->xss_clean($this->input->post("username"));
          $password = $this->security->xss_clean($this->input->post("password"));
          $passwordx = md5($password);
          
          if(!empty($this->_protectedLoginDataadmin($username))){
            $now_final_verify  = $this->_protectedDataLoginadmin($username);
            
          if($now_final_verify->pass == $passwordx)
          {
              $admin = array(
                  'admin'=>$now_final_verify->name,
                  'admin_email'=>$now_final_verify->email,
                  );
                $this->session->set_userdata($admin);
                $url= base_url().'admin/dashboard';
                echo json_encode(['link'=>$url]);
          } else{
      
                  echo json_encode(['notfound'=>'username and password is incorrect']);
              }
          } else{
               echo json_encode(['notfound'=>'username and password is incorrect']);
              }
          
    
        } else{
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
      return $this->db->where('member_id',$username)->select('status')->get('login')->row('status');
    }  
    protected function _protectedLoginDataadmin($username)
    {
        return $this->register->login_verifyadmin($username); 
    }
    protected function _protectedDataLoginadmin($username)
    {
        return $this->register->login_verifyadmin($username);
    }
    // ceate a new user
  public function accountCreateme()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('password','Password','trim|required|min_length[8]|max_length[12]');
    $this->form_validation->set_rules('sponser','Sponser','trim|required');
    $this->form_validation->set_rules('name','full name.','required');
    $this->form_validation->set_rules('email','email.','trim|required|valid_email');//|is_unique[login.email]
    $this->form_validation->set_rules('phone_number','phone number.','trim|required|numeric|min_length[10]|max_length[10]'); //is_unique[login.phone]
    //$this->form_validation->set_rules('agree-term','term and conditions.','required');
    $this->form_validation->set_rules('side','Postion.','trim|required');
    $this->form_validation->set_rules('cnfpassword','Confirm password','trim|required|min_length[8]|max_length[12]|matches[password]');

    if($this->form_validation->run())
    {
        $credential = $this->_fetchCredentials();
        
        $id = $this->input->post('sponser');
        $count = $this->_protectdFetchSponsertSeven($_POST);
        $fetchActiveSponser = $this->_fetchActiveSponser($id);
        if(!empty($fetchActiveSponser)){
            if($count <= 7){    
            $valid_sponser = $this->register->fetch_valid_sponser($id);
            if(empty($valid_sponser)) {
                    echo json_encode(['sponser'=>'SPONSER ID IS INVALID']);
                  } else{
                      $sponser = $this->input->post('sponser');
                      $side = $this->input->post('side');
                      $lastValue='';
                      $right = $this->register->fetch_last_id_tree($sponser,$side);
                  
                  if($right == $sponser) {
                      $date = date('d-M-Y'); // date you want to upgade
                      
                      
                      $final = 'GG'.rand(000000,999999);
                    
                      $unique_id = $this->register->make_u_id($final);
                      if(empty($unique_id)){
                          $mspa = $this->input->post('password');
                          $data = array(
                              'agree-term'   => '0',
                              'side'   => $this->input->post('side'),
                              'sponser'   => $this->input->post('sponser'),
                              'direct_sp'   => $this->input->post('sponser'),
                              'name'   => $this->input->post('name'),
                              'email'   => $this->input->post('email'),
                              'phone'   => $this->input->post('phone_number'),
                              'status'   => 1,
                              'pass'   => md5($this->input->post('password')),
                              'time'  => $date,
                              'member_id' =>$final
                            );
    
                          $daily_p = array(
                              'userid'=>$final,
                              'day'=>date('Y-m-d'),
                              'amount'=>'0.00'
                            );
                            
                          $nm = $this->input->post('name');  
                              
                          $this->db->insert('daily_p',$daily_p);
                          $this->register->registerme($data);
                          
                          
                          $field = array(
                              "sender_id" => "SMSIND",
                              "language" => "english",
                              "route" => "qt",
                              "numbers" => $this->input->post('phone_number'),
                              "message" => "31910",
                              "variables" => "{#BB#}|{#CC#}",
                              "variables_values" =>"$final|$mspa"
                          );
                          forget($field);
                          $data =array(
                            'from'=>$credential->smtp_user,
                            'title'=>$credential->website_name,
                            'to'=>$this->input->post('email'),
                            'sub'=>''.$credential->website_name.' (Registeration done.)',
                            'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one. <br> Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                            'heading'=>'Registeration',
                            'pre'=>'thank you for chooseing us, now you can earn money with us.',
                            'btn'=>'Sign in to account'
                          );
                          $this->_protectedFunctiontest($data);
                          echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);
                        
                        }else{
                          $date = date('d-M-Y'); // date you want to upgade
                          $final = 'GG'.rand(000000,999999);
                          $mspa = $this->input->post('password');
                          $data = array(
                            'agree-term'   => '0',
                            'side'   => $this->input->post('side'),
                            'sponser'   => $this->input->post('sponser'),
                            'direct_sp'   => $this->input->post('sponser'),
                            'name'   => $this->input->post('name'),
                            'email'   => $this->input->post('email'),
                            'phone'   => $this->input->post('phone_number'),
                            'status'   => 1,
                            'pass'   => md5($this->input->post('password')),
                            'time'  => $date,
                            'member_id' =>$final
                          );   
                          $daily_p = array(
                            'userid'=>$final,
                            'day'=>date('Y-m-d'),
                            'amount'=>'0.00'
                          );
                          $nm = $this->input->post('name');  
                          $this->db->insert('daily_p',$daily_p);
                          $this->register->registerme($data);
                          $field = array(
                            "sender_id" => "SMSIND",
                            "language" => "english",
                            "route" => "qt",
                            "numbers" => $this->input->post('phone_number'),
                            "message" => "31910",
                            "variables" => "{#BB#}|{#CC#}",
                            "variables_values" =>"$final|$mspa"
                          );
                          forget($field);
                          $data =array(
                            'from'=>$credential->smtp_user,
                            'title'=>$credential->website_name,
                            'to'=>$this->input->post('email'),
                            'sub'=>''.$credential->website_name.' (Registeration done.)',
                            'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one.<br>Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                            'heading'=>'Registeration',
                            'pre'=>'thank you for chooseing us, now you can earn money with us.',
                            'btn'=>'Sign in to account'
                          );
                          $this->_protectedFunctiontest($data);
                          echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);   
                          }          // echo json_encode(['DB'=>'DB Error']);
                      } else{
                        if(isset($right[0])) {
                                    $lastValue = $right[0]['user_id'];    
                                    while(isset($right[0])) {
                                        $right = $right[0]['child'];
                                        if(isset($right[0])){
                                            $lastValue = $right[0]['user_id'];
                                        }
                                    }
                                }
                                $fetchActiveSponser = $this->_fetchActiveSponser($lastValue);
                               if(!empty($fetchActiveSponser)){
                                $date = date('d-M-Y'); // date you want to upgade
                                $final = 'GG'.rand(000000,999999);
                                $unique_id = $this->register->make_u_id($final);
                                if(empty($unique_id)) {
                                    $mspa = $this->input->post('password');
                                    $data = array(
                                        'agree-term'   => '0',
                                        'side'   => $this->input->post('side'),
                                        'sponser'   => $lastValue,
                                        'direct_sp'   => $this->input->post('sponser'),
                                        'name'   => $this->input->post('name'),
                                        'email'   => $this->input->post('email'),
                                        'phone'   => $this->input->post('phone_number'),
                                        'status'   => 1,
                                        'pass'   => md5($this->input->post('password')),
                                        'time'  => $date,
                                        'member_id' =>$final
                                    );   
                                    $daily_p = array(
                                        'userid'=>$final,
                                        'day'=>date('Y-m-d'),
                                        'amount'=>'0.00'
                                    );
                                    
                                    $nm = $this->input->post('name');  
                              
                              $this->db->insert('daily_p',$daily_p);
                              $this->register->registerme($data);
                              
                                 $field = array(
                                    "sender_id" => "SMSIND",
                                    "language" => "english",
                                    "route" => "qt",
                                    "numbers" => $this->input->post('phone_number'),
                                    "message" => "31910",
                                    "variables" => "{#BB#}|{#CC#}",
                                    "variables_values" =>"$final|$mspa"
                                );
                                forget($field);
                                $data =array(
                                          'from'=>$credential->smtp_user,
                                          'title'=>$credential->website_name,
                                          'to'=>$this->input->post('email'),
                                          'sub'=>''.$credential->website_name.' (Registeration done.)',
                                          'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one.<br>Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                                          'heading'=>'Registeration',
                                          'pre'=>'thank you for chooseing us, now you can earn money with us.',
                                          'btn'=>'Sign in to account'
                                        );
                                $this->_protectedFunctiontest($data);
                                echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);
                                                     
                                      }else{
                                            
                                            $date = date('d-M-Y'); // date you want to upgade
                                              
                                            
                                            $final = 'GG'.rand(000000,999999);
                                            $mspa = $this->input->post('password');
                                            $data = array(
                                            'agree-term'   =>  $this->input->post('agree-term'),
                                            'side'   => $this->input->post('side'),
                                            'sponser'   => $lastValue,
                                            'direct_sp'   => $this->input->post('sponser'),
                                            'name'   => $this->input->post('name'),
                                            'email'   =>  $this->input->post('email'),
                                            'phone'   => $this->input->post('phone_number'),
                                            'status'   => 1,
                                            'pass'   => md5($this->input->post('password')),
                                            'time'  => $date,
                                            
                                            'member_id' =>$final
                                          );   
                                            $daily_p = array(
                                                    'userid'=>$final,
                                                    'day'=>date('Y-m-d'),
                                                    'amount'=>'0.00'
                                                  );
                                                  
                                                  
                                $nm = $this->input->post('name');  
                                
                                $this->db->insert('daily_p',$daily_p);
                                $this->register->registerme($data);
                                
                                   $field = array(
                                      "sender_id" => "SMSIND",
                                      "language" => "english",
                                      "route" => "qt",
                                      "numbers" => $this->input->post('phone_number'),
                                      "message" => "31910",
                                      "variables" => "{#BB#}|{#CC#}",
                                      "variables_values" =>"$final|$mspa"
                                  );
                                  forget($field);
                                            $data =array(
                                          'from'=>$credential->smtp_user,
                                          'title'=>$credential->website_name,
                                          'to'=>$this->input->post('email'),
                                          'sub'=>''.$credential->website_name.' (Registeration done.)',
                                          'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one.<br>Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                                          'heading'=>'Registeration',
                                          'pre'=>'thank you for chooseing us, now you can earn money with us.',
                                          'btn'=>'Sign in to account'
                                        );
                                      $this->_protectedFunctiontest($data);
                                      echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);
                                             
                                            
                                      }
                          
                            }else{
                            echo json_encode(['DB'=>'Your sponser is inactive, please active your id']);     
                        }
                            }
                        
              }
          
          
            }else{
                echo json_encode(['DB'=>'You can not add more then 7 directs']);       
            }
        } else{
            echo json_encode(['DB'=>'Your sponser is inactive, please active your id']);     
        }    
        
    } 
     
    
    else{

        $array = array(
            'error'   => true,
            'name' => form_error('name'),
            'sponser' => form_error('sponser'),
            'email' => form_error('email'),
            'phone' => form_error('phone_number'),
            'term' => form_error('agree-term'),
            'term' => form_error('agree-term'),
            'cnf' => form_error('cnfpassword'),
            'pass' => form_error('password'),
            'side' => form_error('side')
        );
        echo json_encode($array);
    }
    
  }
  
  protected function _fetchActiveSponser($id){
      return $this->db->where('member_id',$id)->where('status !=','1')->select('member_id')->get('login')->row();
  }

  protected function _protectdFetchSponsertSeven($req){
    $direct = $this->db->where('direct_sp',$req['sponser'])->where('email',$req['email'])->where('phone',$req['phone_number'])->select('name,member_id')->get('login')->result();
    $i=1;
    foreach($direct as $row){$i++;

    }
    return $i;
  }
  
    public function adminaccountCreateme()
    {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('password','Password','trim|required|min_length[8]|max_length[20]');
    $this->form_validation->set_rules('sponser','Sponser','trim|required');
    $this->form_validation->set_rules('name','full name.','required');
    $this->form_validation->set_rules('email','email.','trim|required|valid_email');//|is_unique[login.email]
    $this->form_validation->set_rules('phone_number','phone number.','trim|required|numeric|min_length[10]|max_length[10]'); //is_unique[login.phone]
    $this->form_validation->set_rules('pkg','package','required');
    $this->form_validation->set_rules('side','Postion.','trim|required');
    $this->form_validation->set_rules('cnfpassword','Confirm password','trim|required|min_length[8]|max_length[20]|matches[password]');

    if($this->form_validation->run())
    {    
        $credential = $this->_fetchCredentials();
        
        if(empty($this->input->post('numbers'))){
            $id = $this->input->post('sponser');
            $valid_sponser = $this->register->fetch_valid_sponser($id);    
            if(empty($valid_sponser)) {
                echo json_encode(['sponser'=>'SPONSER ID IS INVALID']);
              } else{
                  $sponser = $this->input->post('sponser');
                  $side = $this->input->post('side');
                  $lastValue='';
                  $right = $this->register->fetch_last_id_tree($sponser,$side);
              
              if($right == $sponser) {
                  $date = date('d-M-Y'); // date you want to upgade
                  
                 
                  $final = 'GG'.rand(000000,999999);
                
                  $unique_id = $this->register->make_u_id($final);
                  if(empty($unique_id)) {
                      $mspa = $this->input->post('password');
                      $data = array(
                          'agree-term'   => '0',
                          'side'   => $this->input->post('side'),
                          'sponser'   => $this->input->post('sponser'),
                          'direct_sp'   => $this->input->post('sponser'),
                          'name'   => $this->input->post('name'),
                          'email'   => $this->input->post('email'),
                          'phone'   => $this->input->post('phone_number'),
                          'status'   => 0,
                          'pass'   => md5($this->input->post('password')),
                          'time'  => $date,
                          'activation'=>date('Y-m-d'),
                              'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                              'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                              'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                              'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                              'scracth'=>rand(0000000000,9999999999),
                              'pin'=>rand(00000000,99999999),
                              'member_id' =>$final
                        );
                      $daily_p = array(
                          'userid'=>$final,
                          'day'=>date('Y-m-d'),
                          'amount'=>'0.00'
                        );
                        
                          $this->db->insert('daily_p',$daily_p);
                          $ok = $this->register->registerme($data);
                          if($ok == '1') {
                            /* $field = array(
                                "sender_id" => "SMSIND",
                                "language" => "english",
                                "route" => "qt",
                                "numbers" => $this->input->post('phone_number'),
                                "message" => "31910",
                                "variables" => "{#BB#}|{#CC#}",
                                "variables_values" =>"$final|$mspa"
                            );
                            forget($field);*/
                            $data =array(
                                'from'=>$credential->smtp_user,
                                'title'=>$credential->website_name,
                                'to'=>$this->input->post('email'),
                                'sub'=>''.$credential->website_name.' (Registeration done.)',
                                'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one. <br> Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                                'heading'=>'Registeration',
                                'pre'=>'thank you for chooseing us, now you can earn money with us.',
                                'btn'=>'Sign in to account'
                              );
                            $this->_protectedFunctiontest($data);
                             echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);
                          } else {
                             echo json_encode(['DB'=>'DB Error']);       
                            }    
                        } else{
                  
                             $date = date('d-M-Y'); // date you want to upgade
                             
                             
                             $final = 'GG'.rand(000000,999999);
                             $mspa = $this->input->post('password');
                             $data = array(
                                  'agree-term'   => '0',
                                  'side'   => $this->input->post('side'),
                                  'sponser'   => $this->input->post('sponser'),
                                  'direct_sp'   => $this->input->post('sponser'),
                                  'name'   => $this->input->post('name'),
                                  'email'   => $this->input->post('email'),
                                  'phone'   => $this->input->post('phone_number'),
                                  'status'   => 0,
                                  'pass'   => md5($this->input->post('password')),
                                  'time'  => $date,
                                  'activation'=>date('Y-m-d'),
                              'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                              'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                              'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                              'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                              'scracth'=>rand(0000000000,9999999999),
                              'pin'=>rand(00000000,99999999),
                              'member_id' =>$final
                              );   
                             $daily_p = array(
                                  'userid'=>$final,
                                  'day'=>date('Y-m-d'),
                                  'amount'=>'0.00'
                                );
                                
                          
                              $this->db->insert('daily_p',$daily_p);
                        
                                $ok =$this->register->registerme($data);
                                  if($ok == '1') {
                                    /*$field = array(
                                        "sender_id" => "SMSIND",
                                        "language" => "english",
                                        "route" => "qt",
                                        "numbers" => $this->input->post('phone_number'),
                                        "message" => "31910",
                                        "variables" => "{#BB#}|{#CC#}",
                                        "variables_values" =>"$final|$mspa"
                                    );
                                    forget($field);*/
                                    $data =array(
                                      'from'=>$credential->smtp_user,
                                      'title'=>$credential->website_name,
                                      'to'=>$this->input->post('email'),
                                      'sub'=>''.$credential->website_name.' (Registeration done.)',
                                      'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one.<br>Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                                      'heading'=>'Registeration',
                                      'pre'=>'thank you for chooseing us, now you can earn money with us.',
                                      'btn'=>'Sign in to account'
                                    );
                                  $this->_protectedFunctiontest($data);
                                    echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);   
                                } else{
                                 
                                      echo json_encode(['DB'=>'DB Error']);       

                                  }
                            }
                                    
                        } else{
                            if(isset($right[0])) {
                                $lastValue = $right[0]['user_id'];    
                                while(isset($right[0])) {
                                    $right = $right[0]['child'];
                                    if(isset($right[0])){
                                        $lastValue = $right[0]['user_id'];
                                    }
                                }
                            }
                            $date = date('d-M-Y'); // date you want to upgade
                            
                            
                            $final = 'GG'.rand(000000,999999);
                            $unique_id = $this->register->make_u_id($final);
                            if(empty($unique_id)) {
                                $mspa = $this->input->post('password');
                                $data = array(
                                    'agree-term'   => '0',
                                    'side'   => $this->input->post('side'),
                                    'sponser'   => $lastValue,
                                    'direct_sp'   => $this->input->post('sponser'),
                                    'name'   => $this->input->post('name'),
                                    'email'   => $this->input->post('email'),
                                    'phone'   => $this->input->post('phone_number'),
                                    'status'   => 0,
                                    'pass'   => md5($this->input->post('password')),
                                    'time'  => $date,
                                    'activation'=>date('Y-m-d'),
                              'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                              'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                              'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                              'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                              'scracth'=>rand(0000000000,9999999999),
                              'pin'=>rand(00000000,99999999),
                              'member_id' =>$final
                                );   
                                $daily_p = array(
                                    'userid'=>$final,
                                    'day'=>date('Y-m-d'),
                                    'amount'=>'0.00'
                                );
                                
                            
                                
                                $this->db->insert('daily_p',$daily_p);
                                $ok =$this->register->registerme($data);
                                if($ok == '1')  {
                                    /*$field = array(
                                        "sender_id" => "SMSIND",
                                        "language" => "english",
                                        "route" => "qt",
                                        "numbers" => $this->input->post('phone_number'),
                                        "message" => "31910",
                                        "variables" => "{#BB#}|{#CC#}",
                                        "variables_values" =>"$final|$mspa"
                                    );
                                    forget($field);*/
                                    $data =array(
                                      'from'=>$credential->smtp_user,
                                      'title'=>$credential->website_name,
                                      'to'=>$this->input->post('email'),
                                      'sub'=>''.$credential->website_name.' (Registeration done.)',
                                      'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one.<br>Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                                      'heading'=>'Registeration',
                                      'pre'=>'thank you for chooseing us, now you can earn money with us.',
                                      'btn'=>'Sign in to account'
                                    );
                                  $this->_protectedFunctiontest($data);
                                      echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);
                                                  } else{
                                              echo json_encode(['DB'=>'DB Error']);       
                                            }    
                                  }else{
                                        
                                        $date = date('d-M-Y'); // date you want to upgade
                                          
                                        
                                        $final = 'GG'.rand(000000,999999);
                                        $mspa = $this->input->post('password');
                                        $data = array(
                                        'agree-term'   =>  $this->input->post('agree-term'),
                                        'side'   => $this->input->post('side'),
                                        'sponser'   => $lastValue,
                                        'direct_sp'   => $this->input->post('sponser'),
                                        'name'   => $this->input->post('name'),
                                        'email'   =>  $this->input->post('email'),
                                        'phone'   => $this->input->post('phone_number'),
                                        'status'   => 0,
                                        'pass'   => md5($this->input->post('password')),
                                        'time'  => $date,
                                        'activation'=>date('Y-m-d'),
                              'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                              'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                              'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                              'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                              'scracth'=>rand(0000000000,9999999999),
                              'pin'=>rand(00000000,99999999),
                              'member_id' =>$final
                                      );   
                                        $daily_p = array(
                                                'userid'=>$final,
                                                'day'=>date('Y-m-d'),
                                                'amount'=>'0.00'
                                              );
                                              
                                        
                                              $this->db->insert('daily_p',$daily_p);
                                  $ok =$this->register->registerme($data);
                                      if($ok == '1') {
                                          
                                        /*$field = array(
                                            "sender_id" => "SMSIND",
                                            "language" => "english",
                                            "route" => "qt",
                                            "numbers" => $this->input->post('phone_number'),
                                            "message" => "31910",
                                            "variables" => "{#BB#}|{#CC#}",
                                            "variables_values" =>"$final|$mspa"
                                        );
                                        forget($field);*/
                                        $data =array(
                                      'from'=>$credential->smtp_user,
                                      'title'=>$credential->website_name,
                                      'to'=>$this->input->post('email'),
                                      'sub'=>''.$credential->website_name.' (Registeration done.)',
                                      'text'=>'Thank you for choosing us, now you are member of winners mind. plaese do not share your username and password to any one.<br>Your Member ID: '.$final.'<br> Your Password '.$mspa.' ',
                                      'heading'=>'Registeration',
                                      'pre'=>'thank you for chooseing us, now you can earn money with us.',
                                      'btn'=>'Sign in to account'
                                    );
                                  $this->_protectedFunctiontest($data);
                                            echo json_encode(['success'=>'Thank You for Registering your username and password send your Email ID','su'=>$final,'sp'=>$this->input->post('password')]);
                                              } else{
                                                  echo json_encode(['DB'=>'DB Error']);       
                                        }
                                        
                                  }
                                      }
                                  }
        }else{
        $count = $this->input->post('numbers');
    $sponser = $this->input->post('sponser');
    $side = $this->input->post('side');
    //$fetch_last_id = $this->register->fetch_last_id_tree($sponser,$side_tree);
      $i=0;
      $user_id=0;
      $lastValue = '';
            $right = $this->register->fetch_last_id_tree($sponser,$side);
            if($right == $sponser)
            {
                while($i != $count){
            if($user_id == 0){
            $date = date('d-M-Y'); // date you want to upgade
            
            
            $final = 'GG'.rand(000000,999999);
            $unikq = $this->register->make_u_id($final);
            if(empty($unikq)){
              $data = array(
                              'agree-term'   => '0',
                              'side'   => $this->input->post('side'),
                              'sponser'   =>$sponser,
                              'direct_sp'   => $this->input->post('sponser'),
                              'name'   => $this->input->post('name'),
                              'email'   => $this->input->post('email'),
                              'phone'   => $this->input->post('phone_number'),
                              'status'   => 0,
                              'pass'   => md5($this->input->post('password')),
                              'time'  => $date,
                              'activation'=>date('Y-m-d'),
                              'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                              'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                              'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                              'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                              'scracth'=>rand(0000000000,9999999999),
                              'pin'=>rand(00000000,99999999),
                              'member_id' =>$final
                            );
                      $daily_p = array(
                          'userid'=>$final,
                          'day'=>date('Y-m-d'),
                          'amount'=>'0.00'
                        );
                        
                    
                            $this->db->insert('daily_p',$daily_p);
                $this->db->insert('login',$data);
              $user_id = $this->db->insert_id();
              $sp = $this->db->select('member_id')->where('id',$user_id)->get('login')->row();
              
            } else{
                    
                  
                  
                  $final = 'GG'.rand(000000,999999);
                    $data = array(
                                          'agree-term'   => '0',
                                          'side'   => $this->input->post('side'),
                                          'sponser'   =>$sponser,
                                          'direct_sp'   => $this->input->post('sponser'),
                                          'name'   => $this->input->post('name'),
                                          'email'   => $this->input->post('email'),
                                          'phone'   => $this->input->post('phone_number'),
                                          'status'   => 0,
                                          'pass'   => md5($this->input->post('password')),
                                          'time'  => $date,
                                          'activation'=>date('Y-m-d'),
                                          'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                                          'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                                          'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                                          'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                                          'scracth'=>rand(0000000000,9999999999),
                                          'pin'=>rand(00000000,99999999),
                                          'member_id' =>$final
                                        );
                                      $daily_p = array(
                                          'userid'=>$final,
                                          'day'=>date('Y-m-d'),
                                          'amount'=>'0.00'
                                        );
                                        
                                            
                                            $this->db->insert('daily_p',$daily_p);
                            $this->db->insert('login',$data);
                          $user_id = $this->db->insert_id();
                          $sp = $this->db->select('member_id')->where('id',$user_id)->get('login')->row();
                }
                    } else{
                        $date = date('d-M-Y'); // date you want to upgade
                $final = 'GG'.rand(000000,999999);
                $unikq = $this->register->make_u_id($final);
                if(empty($unikq))
                {
                  $data = array(
                                          'agree-term'   => '0',
                                          'side'   => $this->input->post('side'),
                                          'sponser'   =>$sp->member_id,
                                          'direct_sp'   => $this->input->post('sponser'),
                                          'name'   => $this->input->post('name'),
                                          'email'   => $this->input->post('email'),
                                          'phone'   => $this->input->post('phone_number'),
                                          'status'   => 0,
                                          'pass'   => md5($this->input->post('password')),
                                          'time'  => $date,
                                          'activation'=>date('Y-m-d'),
                                          'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                                          'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                                          'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                                          'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                                          'scracth'=>rand(0000000000,9999999999),
                                          'pin'=>rand(00000000,99999999),
                                          'member_id' =>$final
                                        );
                                      $daily_p = array(
                                          'userid'=>$final,
                                          'day'=>date('Y-m-d'),
                                          'amount'=>'0.00'
                                        );
                                        
                                         

                                          
                                          $this->db->insert('daily_p',$daily_p);
                  $user_id = $this->db->insert_id();
                  $sp = $this->db->select('*')->where('id',$user_id)->get('login')->row();
                  
                }
                else{
                    
                    

                    $final = 'GG'.rand(000000,999999);
                    $data = array(
                                          'agree-term'   => '0',
                                          'side'   => $this->input->post('side'),
                                          'sponser'   =>$sp->member_id,
                                          'direct_sp'   => $this->input->post('sponser'),
                                          'name'   => $this->input->post('name'),
                                          'email'   => $this->input->post('email'),
                                          'phone'   => $this->input->post('phone_number'),
                                          'status'   => 0,
                                          'pass'   => md5($this->input->post('password')),
                                          'time'  => $date,
                                          'activation'=>date('Y-m-d'),
                                          'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                                          'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                                          'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                                          'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                                          'scracth'=>rand(0000000000,9999999999),
                                          'pin'=>rand(00000000,99999999),
                                          'member_id' =>$final
                                        );
                                      $daily_p = array(
                                          'userid'=>$final,
                                          'day'=>date('Y-m-d'),
                                          'amount'=>'0.00'
                                        );
                                        
                                          
                                          $this->db->insert('daily_p',$daily_p);
                    $this->db->insert('login',$data);
                    $user_id = $this->db->insert_id();
                    $sp = $this->db->select('*')->where('id',$user_id)->get('login')->row();
                    
                  }
                }
              $i++;
                }
                if($i == $count)
                {
                    echo json_encode(['success'=>'BULK Registering DONE all credentials are send your mail id']);
                }
                else{
                    echo json_encode(['DB'=>'DB Error']);       
                }
            }else{
                if(isset($right[0]))
            {
                  $lastValue = $right[0]['user_id'];    
                              while(isset($right[0])) {
                                  $right = $right[0]['child'];
                                        if(isset($right[0])){
                                          $lastValue = $right[0]['user_id'];
                                      }
                                    }
            }
        while($i != $count)
        {
                    if($user_id == 0)
                    {
                    $date = date('d-M-Y'); // date you want to upgade
                
              
              $final = 'GG'.rand(000000,999999);
              $unikq = $this->register->make_u_id($final);
                          if(empty($unikq))
                          {
                      $data = array(
                                          'agree-term'   => '0',
                                          'side'   => $this->input->post('side'),
                                          'sponser'   =>$lastValue,
                                          'direct_sp'   => $this->input->post('sponser'),
                                          'name'   => $this->input->post('name'),
                                          'email'   => $this->input->post('email'),
                                          'phone'   => $this->input->post('phone_number'),
                                          'status'   => 0,
                                          'pass'   => md5($this->input->post('password')),
                                          'time'  => $date,
                                          'activation'=>date('Y-m-d'),
                                          'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                                          'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                                          'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                                          'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                                          'scracth'=>rand(0000000000,9999999999),
                                          'pin'=>rand(00000000,99999999),
                                          'member_id' =>$final
                                        );
                                  $daily_p = array(
                                      'userid'=>$final,
                                      'day'=>date('Y-m-d'),
                                      'amount'=>'0.00'
                                    );
                                    
                                        
                                        
                                        $this->db->insert('daily_p',$daily_p);
                          $this->db->insert('login',$data);
                          $user_id = $this->db->insert_id();
                        $sp = $this->db->select('member_id')->where('id',$user_id)->get('login')->row();
                        
                }else{
          
                  
                  
                  $final = 'GG'.rand(000000,999999);
                    $data = array(
                                          'agree-term'   => '0',
                                          'side'   => $this->input->post('side'),
                                          'sponser'   =>$lastValue,
                                          'direct_sp'   => $this->input->post('sponser'),
                                          'name'   => $this->input->post('name'),
                                          'email'   => $this->input->post('email'),
                                          'phone'   => $this->input->post('phone_number'),
                                          'status'   => 0,
                                          'pass'   => md5($this->input->post('password')),
                                          'time'  => $date,
                                          'activation'=>date('Y-m-d'),
                                          'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                                          'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                                          'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                                          'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                                          'scracth'=>rand(0000000000,9999999999),
                                          'pin'=>rand(00000000,99999999),
                                          'member_id' =>$final
                                        );
                                  $daily_p = array(
                                      'userid'=>$final,
                                      'day'=>date('Y-m-d'),
                                      'amount'=>'0.00'
                                    );
                                    
                                        
                                        
                                        $this->db->insert('daily_p',$daily_p);
                      $this->db->insert('login',$data);
                      $user_id = $this->db->insert_id();
                      $sp = $this->db->select('member_id')->where('id',$user_id)->get('login')->row();
                      
                  }
                }
                    else{
                        $date = date('d-M-Y'); // date you want to upgade
                  
                
                $final = 'GG'.rand(000000,999999);
                $unikq = $this->register->make_u_id($final);
                            if(empty($unikq))
                            {
                  $data = array(
                                          'agree-term'   => '0',
                                          'side'   => $this->input->post('side'),
                                          'sponser'   =>$sp->member_id,
                                          'direct_sp'   => $this->input->post('sponser'),
                                          'name'   => $this->input->post('name'),
                                          'email'   => $this->input->post('email'),
                                          'phone'   => $this->input->post('phone_number'),
                                          'status'   => 0,
                                          'pass'   => md5($this->input->post('password')),
                                          'time'  => $date,
                                          'activation'=>date('Y-m-d'),
                                          'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                                          'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                                          'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                                          'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                                          'scracth'=>rand(0000000000,9999999999),
                                          'pin'=>rand(00000000,99999999),
                                          'member_id' =>$final
                                        );
                                  $daily_p = array(
                                      'userid'=>$final,
                                      'day'=>date('Y-m-d'),
                                      'amount'=>'0.00'
                                    );
                                    
                                        
                                        
                                        $this->db->insert('daily_p',$daily_p);
                      
                     
                     $this->db->insert('login',$data);
                     $user_id = $this->db->insert_id();
                   $sp = $this->db->select('*')->where('id',$user_id)->get('login')->row();
                   
                   
                  } else{
                                
                  $final = 'GG'.rand(000000,999999);
                                $data = array(
                                          'agree-term'   => '0',
                                          'side'   => $this->input->post('side'),
                                          'sponser'   =>$sp->member_id,
                                          'direct_sp'   => $this->input->post('sponser'),
                                          'name'   => $this->input->post('name'),
                                          'email'   => $this->input->post('email'),
                                          'phone'   => $this->input->post('phone_number'),
                                          'status'   => 0,
                                          'pass'   => md5($this->input->post('password')),
                                          'time'  => $date,
                                          'activation'=>date('Y-m-d'),
                                          'package_name'=>$this->db->where('id',$this->input->post('pkg'))->select('name')->from('package')->get()->row('name'),
                                          'package_amt'=>$this->db->where('id',$this->input->post('pkg'))->select('price')->from('package')->get()->row('price'),
                                          'tableId'=>$this->db->where('type','single')->select('id')->from('single_leg')->get()->row('id'),
                                          'timePeriod'=>date('Y-m-d h:i:s',strtotime(date('Y-m-d h:i:s') ." +300 day")),
                                          'scracth'=>rand(0000000000,9999999999),
                                          'pin'=>rand(00000000,99999999),
                                          'member_id' =>$final
                                        );
                                  $daily_p = array(
                                      'userid'=>$final,
                                      'day'=>date('Y-m-d'),
                                      'amount'=>'0.00'
                                    );
                                    
                                        
                                        
                                        $this->db->insert('daily_p',$daily_p);
                     $this->db->insert('login',$data);
                     $user_id = $this->db->insert_id();
                     $sp = $this->db->select('*')->where('id',$user_id)->get('login')->row();
                     
                  }
              }
              $i++;
                }
                if($i == $count)
        {
            echo json_encode(['success'=>'BULK Registering DONE all credentials are send your mail id','su'=>'BULK ADDEDD','sp'=>'BULK ADDEDD']);
        }
        else{
            echo json_encode(['DB'=>'DB Error']);       
        }    
            }   
        
        }    
        
                                  
    }else{
                            
        //false
        $array = array(
            'error'   => true,
            'name' => form_error('name'),
            'sponser' => form_error('sponser'),
            'email' => form_error('email'),
            'phone' => form_error('phone_number'),
            'term' => form_error('agree-term'),
            'term' => form_error('agree-term'),
            'cnf' => form_error('cnfpassword'),
            'pass' => form_error('password'),
            'side' => form_error('side')
        );
        echo json_encode($array);
    }
    
  }
    // Log out
    function logoutuser()
    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('admin');
        $this->session->unset_userdata('admin_email');
        $this->session->sess_destroy();
        redirect(base_url());
    }
    
    //forget password
    
    function forgetpassword(){
        //echo forget('hii');
        
        if($_GET['q'] == 'app'){
            $this->session->set_userdata('some_name', 'api/form');
        }elseif($_GET['q'] == 'forget'){
            $this->session->set_userdata('some_name', 'customer/login-to-account');
        }
        
        $this->load->view('forget');
    }
    
    public function checkMemberIdFunction(){
        if(!empty($_POST['member'])){
            $credential = $this->_fetchCredentials();
            $exist = $this->_protectedCeheckUser($_POST);

            if(!empty($exist)){
                // substr(str_shuffle('123456789wertyuiop'),0,4);
                $rand = rand(11111,99999);
                $this->session->set_userdata('mil',$exist);
                $this->session->set_userdata('otp',$rand);
                $this->session->set_userdata('keyGenerate',$exist.'-'.bin2hex($this->encryption->create_key(16)).''.date('Ymdhis'));
                $field = array(
                    "sender_id" => "SMSIND",
                    "language" => "english",
                    "route" => "qt",
                    "numbers" => $this->_fetchPhone($_POST),
                    "message" => "27378",
                    "variables" => "{#AA#}",
                    "variables_values" => "$rand"
                );
                //https://www.fast2sms.com/dev/quick-templates?authorization=iKut0fm9SL217WRUJ5TYVv4q6BazscyDA3GMOkXHb8hlpQoPjZTl8dBYf293vscrKQLPMFkiHgxVpUXu
                $res = forget($field);
                if($res == 0){
                    $makeArray = [
                        'memberId'=>$this->session->userdata('mil'),
                        'key'=>$this->session->userdata('keyGenerate'),
                        'created_at'=>date('Y-M-d')
                    ];
                    $this->db->insert('forget',$makeArray);    
                    echo json_encode(['ok'=>'we send Otp your register mobile']);    
                }else{
                    echo json_encode(['er'=>'something went wrong']);    
                }
            }else{
                echo json_encode(['er'=>'inavlid member id.']);
                
            }
        }else{
            echo json_encode(['er'=>'member id required']);
            
        }
    }
    
    protected function _fetchPhone($data){
        return $this->db->where('member_id',$_POST['member'])
                        ->select('phone')
                        ->from('login')
                        ->get()->row('phone');
    }
    
    protected function _protectedCeheckUser($data){
        return $this->db->where('member_id',$_POST['member'])
                        ->select('member_id')
                        ->from('login')
                        ->get()->row('member_id');
    }
    
    function otp_verifyFunction(){

        $ot = $this->_verifyProtected($_POST);
        if($ot == 2){
            echo json_encode(['er'=>'otp required']);
        }
        elseif($ot == 0){
            echo json_encode(['er'=>'invalid otp']);
        }elseif($ot == 1){
            
            $this->session->unset_userdata('otp');
            $this->session->set_userdata('chng',$this->session->userdata('mil'));
            $this->session->unset_userdata('mil');
            echo json_encode(['ok'=>'verifyed']);
        }else{
            echo json_encode(['er'=>'something went wrong']);
        }
    }
    
    protected function _verifyProtected($data){
        if(empty($data['otp'])){
            return '2';
        }else{
            
            if($data['otp'] == $this->session->userdata('otp')){
                return '1';
            }else{
                return '0';
            }
        }
    }
    
    function updatePasswordFunction(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[12]');
        $this->form_validation->set_rules('cnfpassword','Confirm password','trim|required|min_length[6]|max_length[12]|matches[password]');
        if($this->form_validation->run())
        {
            $dat = $this->_protectedFunctionChangePassword($_POST['cnfpassword']);
            if($dat == 3){
                $makeArray = [
                    'status'=>'SUCCESS'
                ];
                $this->db->where('memberId',$this->session->userdata('chng'))->where('key',$this->session->userdata('keyGenerate'))->where('created_at',date('Y-M-d'))->set($makeArray)->update('forget');
                $data =array(
                  'from'=>'programmingfinger@gmail.com',
                  'title'=>'winners mind',
                  'to'=>$this->_fetchEMail($this->session->userdata('chng')),
                  'sub'=>'change password',
                  'text'=>'your password has been changed successfully,if you not changed your password then complain to admin?',
                  'heading'=>'Password Changed',
                  'pre'=>'password has been changed successfully.',
                  'btn'=>'Sign in'
                );
                $this->_protectedFunctiontest($data);
                $this->session->unset_userdata('chng');
                $this->session->unset_userdata('keyGenerate');
                if($this->session->userdata('some_name') !=''){
                     $url = base_url().''.$this->session->userdata('some_name');
                }
                
                echo json_encode(['ok'=>$url]);   

            }else{
                echo json_encode(['er'=>'something went wrong try again!']);   
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
    
    protected function _fetchEMail($id){
      return $this->db->where('member_id',$id)
                  ->select('email')
                  ->get('login')->row('email');
    }
    protected function _protectedFunctionChangePassword($data){
        $this->db->where('member_id',$this->session->userdata('chng'))
                  ->set('pass',md5($data))
                  ->update('login');
                  
        $this->db->where('user',$this->session->userdata('chng'))
                  ->set('pass',md5($data))
                  ->update('gamelogin');          
        return 3;
        
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
                                  <a href="'.base_url().'/customer/login-to-account" target="_blank" style="display: inline-block;">
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
                                              <a href="'.base_url().'customer/login-to-account" target="_blank" style="display: inline-block; padding: 25px 35px; font-family: "Source Sans Pro", Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">'.$data['btn'].'</a>
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

  protected function _fetchCredentials(){
      return $this->db->select('smtp_user,smpt_pass,smpt_port,smtp_host,protocol,website_name')
                      ->from('admin')
                      ->get()->row();
  }  
}

