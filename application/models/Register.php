<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Model {
        public function __construct()
         	{
          		parent::__construct();
          		date_default_timezone_set('Asia/Kolkata');
          		
         	}
         	
         	function fetch_sposer($id)
         	{
         	    $this->db->where('direct_sp',$id);

         	    $q = $this->db->get('login');
         	    return $q;
         	}
         	
         	
         	function fetch_my_details($id)
         	{
         	    $this->db->where('member_id',$id);
         	    $this->db->where('del','0');
         	    $q = $this->db->get('login');
         	    return $q->row();
         	}
         	function login_verify2($username)
            {
            
            	$this->db->where('member_id',$username);
            	$this->db->where('del','0');
            	$verfify = $this->db->get('login');
            	return $verfify->row();
            	
            }
         	
function left_team_register($id,$side)
{ 
   
    $result = $this->db->select('member_id,side')->from('login')->where('side',$side)->where('sponser',$id)->get()->result();
        $employee = array();
        foreach($result as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['side']=$data->side;
            $emp['child'] = $this->left_count_register($data->member_id,$data->side);
            array_push($employee,$emp);
        }
        return $employee;
  
}
       	
         	function fetch_last_id_tree($sponser,$side)
         	{   
         	    if($sponser)
         	{
         	        //$this->db->select('tree_li,tree_ls');
         	        $this->db->where('side',$side);
         	        $this->db->where('sponser',$sponser);
         	        $q = $this->db->get('login');    
         	        if($q->num_rows() > 0)
         	        {
         	            $result = $this->db->select('member_id,side')->from('login')->where('side',$side)->where('sponser',$sponser)->get()->result();
                        $employee = array();
                        foreach($result as $data){
                            $emp = array();
                            $emp['user_id']=$data->member_id;
                            $emp['side']=$data->side;
                            $emp['child'] = $this->left_count_register($data->member_id,$data->side);
                            array_push($employee,$emp);
                        }
                        return $employee;
  
         	        }
         	        else{
         	    return $sponser;
         	    }
         	}
         	    
         	    
         	}
  

function left_count_register($emp,$side)
{
        $this->db->where_in('sponser',$emp);
        $this->db->where_in('side',$side);
        $q = $this->db->get('login');
        //$this->db->order_by('id','desc');
        //$this->db->limit('1');
        $tree = $q->result();    
        $employee = array();
        foreach($tree as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['side']=$data->side;
            
            $emp['child'] = $this->left_count_register($data->member_id,$data->side);
            array_push($employee,$emp);
        }
        return $employee;
    
    //return $tree;
    
    
    
}
         	function fetch_three_reocrd($id)
         	{
         	    $this->db->where('userid',$id);
         	    $this->db->where('matching','3');
         	    $q = $this->db->get('matching_income');
         	    return $q->row();
         	}
         	
         	function fetch_unik($store)
         	{
         	    $this->db->where('scratch',$store);
         	    $q = $this->db->get('scratch');
         	    return $q->row();
         	}
         	function update_request($id2)
         	
         	{
         	    $this->db->where('id',$id2);
         	    $this->db->set('status','DONE');
         	    $q = $this->db->update('reuest_scratch');
         	    return $q;
         	}
         	function fetch_request($id2)
         	{
         	    $this->db->where('id',$id2);
         	    $q = $this->db->get('reuest_scratch');
         	    return $q->row();
         	}
         	function insertId($data)
         	{
         	    $q = $this->db->insert('reuest_scratch',$data);
         	    return $q;
         	    
         	}
         	
         	function fetch_package_price($pack)
         	{
         	    $this->db->where('id',$pack);
         	    $q = $this->db->get('package');
         	    return $q->row();
         	}
         	
         	function fetch_user_scarth($user_id)
         	{
         	    $this->db->select('member_id,del,status');
         	    $this->db->where('member_id',$user_id);
         	    $this->db->where('del','0');
         	    $q = $this->db->get('login');
         	    return $q->row();
         	}
         	
         	function update_pass($cnf)
         	{
         	    $this->db->where('member_id',$this->session->userdata('username'));
         	    $this->db->set('pass',md5($cnf));
         	    $q = $this->db->update('login');
         	    return $q;
         	}
         	
         	
         	
         	
         	function make_u_id($final)
            {
                $this->db->where('member_id',$final);
         	    $this->db->where('del','0');
         	    $q = $this->db->get('login');
         	    return $q->row();
            }
         	
         	function fetch_valid_sponser($id)
         	{
         	    $this->db->where('member_id',$id);
         	    $this->db->where('del','0');
         	    $q = $this->db->get('login');
         	    return $q->row();
         	}
  
    function panelstatus(){
        return $this->_protecetedFetchStatus($this->session->userdata('username'));
    }
  function ROISTATUS($user)
  { 
    
        //$wallet = $this->_myBlanceLeft();
        
        $bv = $this->db->where('userid',$user)->select('RightBV')->from('bv')->get()->row();
        if(!empty($bv)){
            $finalBv = $bv->RightBV;    
        }else{
            $finalBv=0;
        }

      return $finalBv;
      
  }
  protected function _protecetedFetchStatus($user){
    $st = $this->db->where('member_id',$user)->select('status')->from('login')->get()->row('status');
    if($st == 0){
        return  'Activate';
    }elseif($st == 1){
        return 'Join Now';
    }elseif($st == 2){
        return 'Deactivate';
    }elseif($st == 3){
        return 'Blocked';
    }
  }
protected function _myBlanceLeft(){
    $main = $this->_mywallet();
    $trns = $this->_TransactionTableCount();
    
    if($trns > $main){
        return 0;
    }else{
        return $main - $trns;
    }
}
protected function _mywallet(){
    return $this->db->where('user',$this->session->userdata('username'))
                    ->select('amount')
                    ->from('wallet')
                    ->get()->row('amount');
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
                        ->where('order !=','Join Megacontest')
                        ->where('order !=','Upgrade Account')
                        ->select('t_amt')
                        ->from('transaction')
                        ->get()->result();  
            
  }


function left_team_insert()
    { 
   
        $this->db->select('*');
        $this->db->from('login');
        $this->db->where('side','Left');
        $this->db->where('sponser',$this->session->userdata('username'));
        $result = $this->db->get();
            $result =$result->result();
        $employee = array();
        foreach($result as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['side']=$data->side;
            $emp['child'] = $this->left_count_insert($data->member_id,$data->side);
            array_push($employee,$emp);
        }
        return $employee;
  
}

function left_count_insert($emp,$side)
{
        $this->db->where_in('sponser',$emp);
        $this->db->where('side',$side);
        $q = $this->db->get('login');
        $tree = $q->result();    
        $employee = array();
        foreach($tree as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['side']=$data->side;
            $emp['child'] = $this->left_count_insert($data->member_id,$data->side);
            array_push($employee,$emp);
        }
        return $employee;
    
    //return $tree;
    
    
    
}

    function dailypayoutDesc()
 	{
        return $this->db->select('tableId,timePeriod,member_id,direct_sp,daily_payout')
 	             ->where('status !=','1')
 	             ->order_by('id','desc')
 	             ->get('login')->result();
	
    }
    
    function dailypayoutWhereUser($id)
 	{
        return $this->db->where('member_id',$id)->select('tableId,timePeriod,member_id,direct_sp,daily_payout')
 	             ->where('status !=','1')
 	             ->get('login')->row();
	
    }     	
    
 	function dailypayout()
 	{
        return $this->db->select('tableId,timePeriod,member_id,direct_sp,daily_payout')
 	             ->where('status !=','1')
 	             ->get('login')->result();
	
    }
    
    
    function dailypayoutBinary() {
        return $this->db->select('id,member_id')->get('login')->result();
	
    }
    
    function dailypayoutBinaryDesc() {
        return $this->db->select('id,member_id')
                        //->where('status !=','1')
 	                    ->order_by('id','desc')
 	                    //->limit('2451','900')// wait...
 	                    ->get('login')->result();
	
    }
 	
 	function daily_paise_widBal($id)
 	{
 	    /*return $this->db->where('userid',$id)
                        ->select('amount')
                        ->from('daily_p')
                        ->get()->row('amount');*/
        return $this->db->query("SELECT sum(amount) as total FROM daily_p where userid='$id'")->row('total');                
 	}
 	
 	function daily_paise($id)
 	{
 	    $this->db->where('userid',$id);
 	    $this->db->where('paid','0');
 	    $q = $this->db->get('daily_p');
 	    return $q->result();
 	}
 	
 	
 
function registerme($data)
{
	$q = $this->db->insert('login',$data);
	return $q;
	

}
function registerme2($data)
{
	$q = $this->db->insert('login',$data);
	
	

}
function update_final($data,$user)
{
    $this->db->where('member_id',base64_decode($user));
    $this->db->set($data);
    $q = $this->db->update('login');
    if($q)
    {
        $this->db->where('user_id',base64_decode($user));
        $this->db->set('status','1');
        $q= $this->db->update('forget_url');
        return $q;
    }
    else{
        
    }
}
function expir($data,$link,$i)
{
    $this->db->where('link',$link);
    $this->db->where('user_id',base64_decode($i));
    $this->db->set('status','1');
    $q = $this->db->update('forget_url');
    return $q;
}
function find($link,$i)
{
    $this->db->where('link',$link);
    $this->db->where('status','0');
    $this->db->where('user_id',base64_decode($i));
    $q = $this->db->get('forget_url');
    return $q->row();
}
function verfiyuser($member)
{
    $this->db->where('member_id',$member);
    $this->db->where('del','0');
    $q = $this->db->get('login');
    return $q->row();
}

function forgetdata($data)
{
    $q = $this->db->insert('forget_url',$data);
    return $q;
}
function fetch_pin($pinm)
{       
        //$this->db->where('user_id',$this->session->userdata('username'));
        $this->db->where('pin',$pinm);
        $q = $this->db->get('scratch');
        if($q)
        {
            return $q->row();
        }
        else{
            return 'Oops! pin not found';
        }
        
    
}

function infoUpdate($id,$pinm,$scratch,$price,$package)
{
    
    $this->db->where('pin',$pinm);
    $this->db->set('usedORnot','used');
    $q = $this->db->update('scratch');
    if($q)
        {
            $this->db->where('member_id',$id);
            $this->db->set('status','0');
            $this->db->set('pin',$pinm);
            $this->db->set('package_amt',$price);
            $this->db->set('package_name',$package);
            $this->db->set('activation',date('Y-m-d'));
            $this->db->set('scracth',$scratch);
            $q = $this->db->update('login');
            return $q;
        }
    else{
        exit();
    }
    
}

function fetchsponser()
{
    $this->db->where('del','0');
    $this->db->limit('1');
    $this->db->order_by('id','desc');
    $q = $this->db->get('login');
    return $q->row();
    
}

function login_verify($username)
{
	$this->db->where('member_id',$username);
	$this->db->where('del','0');
	$verfify = $this->db->get('login');
	return $verfify->row();
}

function login_verifyAdmin($username)
{
    return $this->db->where('user',$username)
                    ->get('admin')->row();
    
}







function profileinfo()
{
    $this->db->select('name,father_name,dob,gender');
    $this->db->from('login');
    $this->db->where('member_id',$this->session->userdata('username'));
    $profile = $this->db->get();
    return $profile;
}


function Accountinfo()
{
    //$this->db->where('user_id',$this->session->userdata('username'));
    //$accunt = $this->db->get('account');
    //return $accunt;
}
function nomineeinfo()
{
    $this->db->select('nominee_name,nominee_reltion');
    $this->db->from('login');
    $this->db->where('member_id',$this->session->userdata('username'));
    $nominee = $this->db->get();
    return $nominee;
}
function contacteinfo()
{
    $this->db->select('email,phone,city,state,postal,address,district');
    $this->db->from('login');
    $this->db->where('member_id',$this->session->userdata('username'));
    $contact = $this->db->get();
    return $contact;
}
function account_update($data)
{
    $this->db->where('user_id',$this->session->userdata('username'));
    $this->db->set($data);
    $q = $this->db->update('account');
    return $q;
    
}
function account_add($data)
{
    
    
    $q = $this->db->insert('account',$data);
    return $q;
    
}
function nominee_update($data)
{
    $this->db->where('member_id',$this->session->userdata('username'));
    $this->db->set($data);
    $q = $this->db->update('login');
    return $q;
}
function profile_update($data)
{
    $this->db->where('member_id',$this->session->userdata('username'));
    $this->db->set($data);
    $q = $this->db->update('login');
    return $q;
}



function right_team()
{ 
    $this->db->select('*');
    $this->db->from('login');
    //$this->db->join('scratch as s','s.pin=l.pin','left');
    $this->db->where('side','Right');
    $this->db->where(array('sponser'=>$this->session->userdata('username')));
    $result = $this->db->get();
    $result = $result->result();
        $employee = array();
        foreach($result as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['name']=$data->name;
            $emp['time']=$data->time;
            $emp['activation']=$data->activation;
            $emp['p_name']=$data->package_name;
            $emp['p_amt']=$data->package_amt;
            //$emp['price']=$data->price;
            $emp['status']=$data->status;
            //$emp['sponser']=$data->sponser;
            $emp[] = $data->member_id;
            $emp['child'] = $this->right_count($emp);
            array_push($employee,$emp);
        }
        return $employee;
   
}

function right_count($emp)
{
        $this->db->select('*');
        $this->db->from('login');
        //$this->db->join('scratch as b','b.pin=a.pin','left');
        $this->db->where_in('sponser',$emp);
        $q = $this->db->get();
        $tree = $q->result();    
        $employee = array();
        foreach($tree as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['name']=$data->name;
            $emp['time']=$data->time;
            $emp['p_name']=$data->package_name;
            $emp['p_amt']=$data->package_amt;
            //$emp['price']=$data->price;
            $emp['activation']=$data->activation;
            $emp['status']=$data->status;
           // $emp['sponser']=$data->sponser;
            $emp[] = $data->member_id;
            $emp['child'] = $this->right_count($emp);
            array_push($employee,$emp);
        }
        return $employee;
    
}


//left side

function left_team_o($id)
{ 
    $this->db->select('member_id,name,time,package_amt,status');
    $this->db->from('login');
    //$this->db->join('scratch as s','s.pin=l.pin','left');
    $this->db->where('side','Left');
    $this->db->where('sponser',$id);
    $result = $this->db->get();
    $result = $result->result();
        $employee = array();
        foreach($result as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['name']=$data->name;
            $emp['time']=$data->time;
            $emp['p_amt']=$data->package_amt;
            $emp['status']=$data->status;
            //$emp['sponser']=$data->sponser;
            $emp[] = $data->member_id;
            $emp['child'] = $this->left_count_o($emp);
            array_push($employee,$emp);
        }
        return $employee;
   
}

function left_count_o($emp)
{
        $this->db->select('member_id,name,time,package_amt,status,activation');
        $this->db->from('login');
        //$this->db->join('scratch as b','b.pin=a.pin','left');
        $this->db->where_in('sponser',$emp);
        $q = $this->db->get();
        $tree = $q->result();    
        $employee = array();
        foreach($tree as $data){
            $emp = array();
            $emp['user_id']=$data->member_id;
            $emp['name']=$data->name;
            $emp['time']=$data->time;
            $emp['p_amt']=$data->package_amt;
            $emp['activation']=$data->activation;
            $emp['status']=$data->status;
           // $emp['sponser']=$data->sponser;
            $emp[] = $data->member_id;
            $emp['child'] = $this->left_count_o($emp);
            array_push($employee,$emp);
        }
        return $employee;
    
}


// site setting

function update_seo($seo)
    {
        $this->db->where('id','1');
        $this->db->set($seo);
        return $this->db->update('site_setting');
    }
    function update_title($title)
    {
        $this->db->where('id','1');
        $this->db->set($title);
        return $this->db->update('site_setting');
    }
    function upload_favicon($favicon)
    {
        $this->db->where('id','1');
        $this->db->set($favicon);
        return $this->db->update('site_setting');
        
    }
    function upload_logo($favicon)
    {
        $this->db->where('id','1');
        $this->db->set($favicon);
        return $this->db->update('site_setting');
        
        
    }


function fetch_all_member_records($params = array())
    {
        $this->db->select('*');
        $this->db->from('login');
        $this->db->where('del','0');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('member_id',$params['search']['keywords']);
            $this->db->or_like('phone',$params['search']['keywords']);
            $this->db->or_like('name',$params['search']['keywords']);
        }
        
        //sort data by ascending or desceding order
        
        if(!empty($params['search']['datefilter'])){
            $this->db->where('activation',$params['search']['datefilter']);
        }else{
            
        }
        
        if(!empty($params['search']['rank'])){
            $this->db->where('tableId',$params['search']['rank']);
        }else{
            
        }
        if(!empty($params['search']['pkgwise'])){
            $this->db->where('package_amt',$params['search']['pkgwise']);
        }else{
            
        }
        if(!empty($params['search']['statuswise'])){
            $this->db->where('status',$params['search']['statuswise']);
        }else{
            
        }
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('email',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }




function fetch_scratch($params = array())
    {
        $this->db->select('*');
        $this->db->from('scratch');
        $this->db->where('user_id',$this->session->userdata('username'));
        $this->db->where('del','0');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('scratch',$params['search']['keywords']);
            $this->db->or_like('pin',$params['search']['keywords']);
            
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['type'])){
            $this->db->order_by('usedOrnot',$params['search']['type']);
        }else{
            $this->db->order_by('pin','desc');
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('date',$params['search']['sortBy']);
        }else{
            $this->db->order_by('pin','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }


function fetch_scratch_request($params = array())
{

        $this->db->select('*'); 
        $this->db->from('reuest_scratch');
        //$this->db->where('user_id',$this->session->userdata('username'));
        $this->db->where('del','0');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('s_id',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('date',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
}

function dailypayoutTable($params = array())
    {
        $this->db->select('*');
        $this->db->from('daily_p');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('userid',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['rank'])){
            $this->db->where('rank',$params['search']['rank']);
        }else{
            
        }
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('userid',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','asc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }



    function my_request($params = array())
    {
        $this->db->select('*');
        $this->db->from('reuest_scratch');
        $this->db->where('s_id',$this->session->userdata('username'));
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('s_name',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('date',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }

    function feth_single_leg($params = array())
    {
        $this->db->select('*');
        $this->db->from('single_leg');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('rank',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('amount',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }

    function kyc($params = array())
    {
        $this->db->select('userid,created_at,status,id');
        $this->db->from('kyccomplete');
        //$this->db->group_by('userid');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('userid',$params['search']['keywords']);
            $this->db->or_like('panno',$params['search']['keywords']);
            $this->db->or_like('panname',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('timestamp',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }

    function transactionDB($params = array())
    {
        $this->db->select('*');
        $this->db->from('transaction');
        //$this->db->group_by('userid');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('user',$params['search']['keywords']);
            $this->db->or_like('txn',$params['search']['keywords']);
            $this->db->or_like('refrence',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('timestamp',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }

    

    function directincome($params = array())
    {
        $this->db->select('*');
        $this->db->from('directincome');
        //$this->db->group_by('userid');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('user_id',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('date',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }

    
    function tree_data_fetch($id)
    {
        $this->db->select("*");
        $this->db->from("login");
        $this->db->where('member_id',$id);
        $this->db->where('del','0');
        $q = $this->db->get();
    	$final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("scratch");
                $this->db->where("pin", $data->pin);
                $q = $this->db->get();
                if($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
    	return $final;
    
        
    }
    
    }
    
    function fetch_child_more($left)
    {
        $this->db->select('*');
    	$this->db->from('login');
    	$this->db->where('member_id',$left);
    	$this->db->where('del','0');
    	$q = $this->db->get();
    	$final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where('side','Left');
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
    	return $final;
    
        
    }
    }
    function fetch_child_more_right_third($left)
    {
        $this->db->select('*');
        $this->db->from('login');
        $this->db->where('member_id',$left);
        $this->db->where('del','0');
        $q = $this->db->get();
        $final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where('side','Right');
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
        return $final;
    
        
    }
    }
    
    function fetch_child_more2($right)
    {
        $this->db->select('*');
        $this->db->from('login');
        $this->db->where('member_id',$right);
        $this->db->where('del','0');
        $q = $this->db->get();
        $final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where('side','Left');
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
        return $final;
    
        
    }
    }
    function fetch_child_more_right_third2($right)
    {
        $this->db->select('*');
        $this->db->from('login');
        $this->db->where('member_id',$right);
        $this->db->where('del','0');
        $q = $this->db->get();
        $final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where('side','Right');
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
        return $final;
    
        
    }
    }
    function fetch_child_more_right($id)
    {
        $this->db->select('*');
    	$this->db->from('login');
    	$this->db->where('member_id',$id);
    	$this->db->where('del','0');
    	$q = $this->db->get();
    	$final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
    	return $final;
    
        
    }
    }
    function fetch_child_second($rightSecond)
    {
     $this->db->select('*');
        $this->db->from('login');
        $this->db->where('member_id',$rightSecond);
        $this->db->where('del','0');
        $q = $this->db->get();
        $final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where('side','Right');
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
        return $final;
       
    }
    }
    
    function tree_fetch($id)
    {
    	$this->db->select('*');
    	$this->db->from('login');
    	$this->db->where('member_id',$id);
    	$this->db->where('del','0');
    	$q = $this->db->get();
    	$final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where('side','Left');
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
    	return $final;
    }
    
    }
    function tree_fetch_right($id)
    {
        $this->db->select('*');
        $this->db->from('login');
        $this->db->where('member_id',$id);
        $this->db->where('del','0');
        $q = $this->db->get();
        $final = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $data) {
    
                $this->db->select("*");
                $this->db->where('del','0');
                $this->db->from("login");
                $this->db->where('side','Right');
                $this->db->where("sponser", $data->member_id);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $data->sub_name = $q->result();
                }
                
                
                
                array_push($final,$data);
            }
    
        return $final;
    }
    
    }
    
    function transactionUser($params = array())
    {
        $this->db->select('*');
        $this->db->from('transaction');
        $this->db->where('user',$this->session->userdata('username'));
        //$this->db->group_by('userid');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('txn',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('timestamp',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }
    
    function binaryAdmin($params = array())
    {
        $this->db->select('*');
        $this->db->from('binary');
        //$this->db->where('user',$this->session->userdata('username'));
        //$this->db->group_by('userid');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('userid',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('date',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }
    
    function teamFetchRecordSide($params = array())
    {
        
        $this->db->select('*');
        $this->db->from('makebinary');
        if(array_key_exists("side",$params)){
            $this->db->where('teamside',$params['side']);    
        }else{
            //$this->db->where('teamside','');    
        }
        
        $this->db->where('parent',$this->session->userdata('usrf'));
        //$this->db->group_by('userid');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('user_id',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('id',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','asc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
        
    }

}

?>
