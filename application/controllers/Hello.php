<?php
class Hello extends CI_Controller {
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
            echo $i.'Binary Chart : '.$row->member_id.'<br>';
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
        'activation'=> $data->activation,
        'p_amt'=> $data->package_amt,
        'status'=> $data->status,
        'teamside'=>'Left'
    ];
        $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
        if(empty($empty)){
            $this->db->insert('makebinary',$emp);
        }
        $emp['child'] = $this->left_countApi($emp,$parent);
        array_push($employe,$emp);
    }
    return $employe;
}

function left_countApi($emp,$parent){
    $tree = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$emp)->from('login')->get()->result();  
    $employe = [];
    foreach($tree as $data){
        $emp = [
            'parent' =>$parent,  
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
        $emp['child'] = $this->left_countApi($emp,$parent);
        array_push($employe,$emp);
    }
    return $employe;
}


//right inary start ;

public function makeRightFirstTreedata($req,$parent){
    $result = $this->db->select('member_id,activation,package_amt,status')->where('side','Right')->where(array('sponser'=>$req))->from('login')->get()->result();
    $employe = [];
    foreach($result as $data){
        $emp = [
            'parent' =>$parent,  
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
        $emp['child'] = $this->right_countApi($emp,$parent);
       array_push($employe,$emp);
    }
    return $employe;
}

function right_countApi($emp,$parent){
 
    $tree = $this->db->select('member_id,activation,package_amt,status')->where_in('sponser',$emp)->from('login')->get()->result();
    $employe = [];
        foreach($tree as $data){
            $emp = [
                'parent' =>$parent,  
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
            $emp['child'] = $this->right_countApi($emp,$parent);
        array_push($employe,$emp);
        }
    return $employe;
}



} ?>