<?php
class RightSide extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //ini_set('max_execution_time', 180);
        $this->output->enable_profiler(true);
        $this->load->model('register');
        date_default_timezone_set('Asia/Kolkata');
    }

function index(){
        $leg = $this->register->dailypayoutBinary(); // wait i will open 
        $i =0;
        if(!empty($leg)){
             foreach ($leg as $row) {
                 //die;
                echo $i.' Right Binary: '.$row->member_id.'<br>';
                $this->makeRightFirstTreedata($row->member_id,$row->id);
                $i++;
            }
            
            
        }
}

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
        }
        $emp['child'] = $this->right_countApi($emp,$parent);
       array_push($employe,$emp);
    }
    return $employe;
}

function right_countApi($emp,$parent)
{
   // $this->db->cache_on();    
    $tree = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$emp)->from('login')->get()->result();
    //$this->db->cache_off();
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
                //$this->db->cache_on();    
                $this->db->insert('makebinary',$emp);
                //$this->db->cache_off();
            }
            $emp['child'] = $this->right_countApi($emp,$parent);
        array_push($employe,$emp);
        }
    return $employe;
}



} ?>