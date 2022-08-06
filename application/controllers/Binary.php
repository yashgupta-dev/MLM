<?php
class Binary extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //ini_set('max_execution_time', 180);
        $this->output->enable_profiler(true);
        $this->load->model('register');
        date_default_timezone_set('Asia/Kolkata');
    }

function run(){
        //redirect(base_url().'admin/all-user');
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
        die;
        $data = [];
        //show me model and table structure ok!
        if($_GET['q']){
            $id = $this->_get_paranetId($_GET['q']);
            if(!empty($id) && $_GET['q']){
                    echo '<p style="text-align:center;">Wait a while...</p>';
                    $data = [
                         'id'=>$_GET['q'],
                         'key' => $id,
                         'Right' => $this->makeRightFirstTreedata($_GET['q'], $id),
                         'Left' => $this->makeLeftFirstTreedata($_GET['q'], $id)
                        ];
                echo '<pre>';
                print_r($data);
                
            }else{
                exit;
            }
        }else{
            
        }
}

protected function _get_paranetId($id){
      return $this->db->where('member_id',$id)->select('id')->get('login')->row('id');
    }


// left make Binary Close Data;

public function makeLeftFirstTreedata($req,$parent){
    //$this->db->cache_on();
    $result = $this->db->select('member_id,activation,package_amt,status')->where('side','Left')->where(array('sponser'=>$req))->from('login')->get()->result();
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
        //'side'=> $data->side,
        'teamside'=>'Left'
    ];
           $empty =  $this->db->where('user_id',$emp['user_id'])->where('parent',$emp['parent'])->select('user_id')->get('makebinary')->row();
            if(empty($empty)){
                //$this->db->cache_on();
                $this->db->insert('makebinary',$emp);
                //$this->db->cache_off();
            }
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
        }
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
            }
            $emp['child'] = $this->right_countApi($emp,$parent);
        array_push($employe,$emp);
        }
    return $employe;
}


} ?>