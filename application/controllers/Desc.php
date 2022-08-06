<?php
class Desc extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //ini_set('max_execution_time', 180);
        $this->output->enable_profiler(true);
        $this->load->model('register');
        date_default_timezone_set('Asia/Kolkata');
    }

function index(){
        $leg = $this->register->dailypayoutBinaryDesc(); // wait i will open 
        $i =0;
        if(!empty($leg)){
             foreach ($leg as $row) {
                 //die;
                echo $i.' Binary Desc :=> '.$row->member_id.'<br>';
                $this->makeLeftFirstTreedata($row->member_id,$row->id);
                $this->makeRightFirstTreedata($row->member_id,$row->id);
            
                $i++;
            }
            
            
        }
}

// left make Binary Close Data;

public function makeLeftFirstTreedata($req,$parent){
    $result = $this->db->select('member_id,activation,package_amt,status')->where('side','Left')->where(array('sponser'=>$req))->from('login')->get()->result();
    $employe = [];
    foreach($result as $data){
        $emp = [
        'parent' =>$parent,  
        'user_id'=> $data->member_id,
        //'name'=> $data->name,
        //'time'=> $data->time,
        'activation'=> $data->activation,
        'p_amt'=> $data->package_amt,
        'status'=> $data->status,
        //'side'=> $data->side,
        'teamside'=>'Left'
    ];
           $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
            if(empty($empty)){
                //$this->db->cache_on();
                $this->db->insert('makebinary',$emp);
                //$this->db->cache_off();
            }/*else{
            //$this->db->cache_on();    
            $this->db->where('parent',$parent)
                         ->where('user_id',$emp['user_id'])
                         //->set('name',$emp['name'])
                         ->set('status',$emp['status'])
                         ->set('p_amt',$emp['p_amt'])
                         ->set('activation',$emp['activation'])
                         ->update('makebinary');
                //$this->db->cache_off();
            }*/
            $emp['child'] = $this->left_countApi($emp,$parent);
         array_push($employe,$emp);
        }
        return $employe;
}

function left_countApi($emp,$parent)
{
    ////$this->db->cache_on();
    $tree = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$emp)->from('login')->get()->result();  
    ////$this->db->cache_off();
    $employe = [];
    //$employee= [];
        foreach($tree as $data){
            $emp = [
                'parent' =>$parent,  
                'user_id'=> $data->member_id,
                //'name'=> $data->name,
                //'time'=> $data->time,
                'activation'=> $data->activation,
                'p_amt'=> $data->package_amt,
                'status'=> $data->status,
                //'side'=> $data->side,
                'teamside'=>'Left'
            ];
             $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
            if(empty($empty)){
                ////$this->db->cache_on();    
                $this->db->insert('makebinary',$emp);
                ////$this->db->cache_off();
            }
            
            $emp['child'] = $this->left_countApi($emp,$parent);
            array_push($employe,$emp);
        }
    return $employe;
}


//right inary start ;

public function makeRightFirstTreedata($req,$parent){
    //$this->db->cache_on();    
    $result = $this->db->select('member_id,activation,package_amt,status')->where('side','Right')->where(array('sponser'=>$req))->from('login')->get()->result();
    //$this->db->cache_off();
    $employe = [];
    foreach($result as $data){
        $emp = [
            'parent' =>$parent,  
            'user_id'=> $data->member_id,
            //'name'=> $data->name,
            //'time'=> $data->time,
            'activation'=> $data->activation,
            'p_amt'=> $data->package_amt,
            'status'=> $data->status,
            ////'side'=> $data->side,
            'teamside'=>'Right'
        ];
         $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
            if(empty($empty)){
            //$this->db->cache_on();    
            $this->db->insert('makebinary',$emp);
            //$this->db->cache_off();
        }/*else{
            //$this->db->cache_on();    
            $this->db->where('parent',$parent)
                     ->where('user_id',$emp['user_id'])
                     ->set('status',$emp['status'])
                     //->set('name',$emp['name'])
                     ->set('p_amt',$emp['p_amt'])
                     ->set('activation',$emp['activation'])
                     ->update('makebinary');
            //$this->db->cache_off();
        }*/
        $emp['child'] = $this->right_countApi($emp,$parent);
       array_push($employe,$emp);
    }
    return $employe;
}

function right_countApi($emp,$parent)
{
    ////$this->db->cache_on();    
    $tree = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$emp)->from('login')->get()->result();
    ////$this->db->cache_off();
    $employe = [];
        foreach($tree as $data){
            $emp = [
                'parent' =>$parent,  
                'user_id'=> $data->member_id,
                //'name'=> $data->name,
                //'time'=> $data->time,
                'activation'=> $data->activation,
                'p_amt'=> $data->package_amt,
                'status'=> $data->status,
                //'side'=> $data->side,
                'teamside'=>'Right'
            ];
             $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
            if(empty($empty)){
                ////$this->db->cache_on();    
                $this->db->insert('makebinary',$emp);
                ////$this->db->cache_off();
            }/*else{
                //$this->db->cache_on();    
            $this->db->where('parent',$parent)
                         ->where('user_id',$emp['user_id'])
                         //->set('name',$emp['name'])
                         ->set('status',$emp['status'])
                         ->set('p_amt',$emp['p_amt'])
                         ->set('activation',$emp['activation'])
                         ->update('makebinary');
                //$this->db->cache_off();         
            }*/
            $emp['child'] = $this->right_countApi($emp,$parent);
        array_push($employe,$emp);
        }
    return $employe;
}


protected function _requestExistOrnot($id,$req){
    //$this->db->cache_on();    
    return $this->db->where('user_id',$req)->where('parent',$id)->select('user_id')->get('makebinary')->row();
    //$this->db->cache_off();
}


} ?>