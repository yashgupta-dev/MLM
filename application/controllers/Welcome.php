<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
 	{
  		parent::__construct();
  		$this->load->model('register');
  		date_default_timezone_set('Asia/Kolkata');
  		//$this->session->set_userdata('username','user');
 	}
	
	
	function captcha(){
        $this->load->helper('captcha');
        $vals = array(
            'word'          => 'Random word',
            'img_path'      => './captcha/',
            'img_url'       => base_url().'/captcha/',
            // 'font_path'     => './path/to/fonts/texb.ttf',
            'img_width'     => '150',
            'img_height'    => 30,
            'expiration'    => 7200,
            'word_length'   => 8,
            'font_size'     => 16,
            'img_id'        => 'Imageid',
            'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            // White background and border, black text and red grid
                'colors'        => array(
                        'background' => array(255, 255, 255),
                        'border' => array(255, 255, 255),
                        'text' => array(0, 0, 0),
                        'grid' => array(255, 40, 40)
                )
            );

        $cap = create_captcha($vals);
        echo $cap['image'];

    }
    public function index(){
        
        redirect(base_url().'customer/login-to-account');
    }
    
    function fetchUsernameSponsertPage(){
        echo json_encode($this->_fetchMane($_POST));
    }
    
    private function _fetchMane($req){
        $na = $this->db->where('member_id',$req['id'])->select('name')->from('login')->get()->row('name');
        return ['n'=>$na];
    }
    
    public function apifunction_getdata(){
        if($_GET['q']){
            $exist = $this->_checkdataExist($_GET['q']);
            if(!empty($exist)){
                $data = array(
                'subCode'=>'200',
                'status'=>'success',
                'right'=>$this->_apiCallRightTeam($_GET['q']),
                'msg'=>'data sucessfull reterive'
              );
              echo json_encode($data);
              
            }else{
                echo json_encode(['subCode'=>'401','status'=>'failed','right'=>'error','msg'=>'id not found']);    
            }
            
        }else{
            echo json_encode(['subCode'=>'201','status'=>'failed','msg'=>'invalid credentials']);
        }
    }
    
    protected function _checkdataExist($q){
        return $this->db->where('member_id',$q)->select('member_id')->get('login')->row();
    }
    protected function _apiCallRightTeam($id)
    {
        $data = $this->_get_paranetId($id);
        $coun = $this->db->where('parent',$data)->where('teamside','Right')->select('teamside')->get('makebinary')->result();

        $i = 0;
        foreach ($coun as $key) {
          $i++;
        }
         return $i;
    }

    protected function _get_paranetId($id){
      return $this->db->where('member_id',$id)->select('id')->get('login')->row('id');
    }
    
    function apiFunctionCuntLeftData(){
        if($_GET['q']){
            $exist = $this->_checkdataExist($_GET['q']);
            if(!empty($exist)){
                $data = array(
                'subCode'=>'200',
                'status'=>'success',
                'right'=>$this->_apiCallLeftTeam($_GET['q']),
                'msg'=>'data sucessfull reterive'
              );
              echo json_encode($data);
            }else{
                echo json_encode(['subCode'=>'401','status'=>'failed','right'=>'error','msg'=>'id not found']);    
            }
            
        }else{
            echo json_encode(['subCode'=>'201','status'=>'failed','msg'=>'invalid credentials']);
        }
    }
        
    protected function _apiCallLeftTeam($id)
    {
        $data = $this->_get_paranetId($id);
        $coun = $this->db->where('parent',$data)->where('teamside','Left')->select('teamside')->get('makebinary')->result();

        $i = 0;
        foreach ($coun as $key) {
          $i++;
        }
         return $i;

    }

    //Right Business
    function getRightBusinessCountData(){
        if($_GET['q']){
            $exist = $this->_checkdataExist($_GET['q']);
            if(!empty($exist)){
                
                $data = array(
                'subCode'=>'200',
                'status'=>'success',
                'rightBusiness'=>$this->_apiCallRightBusiness($_GET['q']),
                'msg'=>'data sucessfull reterive'
              );
              echo json_encode($data);
              
            }else{
                echo json_encode(['subCode'=>'401','status'=>'failed','rightBusiness'=>'error','msg'=>'id not found']);    
            }
            
        }else{
            echo json_encode(['subCode'=>'201','status'=>'failed','msg'=>'invalid credentials']);
        }
    }

    
    protected function _apiCallRightBusiness($id)
    {

      $parent = $this->_get_paranetId($id);
      $coun = $this->db->where('parent',$parent)->where('teamside','Right')->select('p_amt')->get('makebinary')->result();

        $i = 0;
        foreach ($coun as $key) {
          $i += $key->p_amt;
        }
         
     return $i;
    }
    
    function leftBusinessTotalShow(){
        if($_GET['q']){
            $exist = $this->_checkdataExist($_GET['q']);
            if(!empty($exist)){
                $data = array(
                'subCode'=>'200',
                'status'=>'success',
                'leftBusiness'=>$this->_apiCallLeftBusiness($_GET['q']),
                'msg'=>'data sucessfull reterive'
              );
              echo json_encode($data);
              
            }else{
                echo json_encode(['subCode'=>'401','status'=>'failed','leftBusiness'=>'error','msg'=>'id not found']);    
            }
            
        }else{
            echo json_encode(['subCode'=>'201','status'=>'failed','msg'=>'invalid credentials']);
        }
    }
    protected function _apiCallLeftBusiness($id)
    {
        $parent = $this->_get_paranetId($id);
        $coun = $this->db->where('parent',$parent)->where('teamside','Left')->select('p_amt')->get('makebinary')->result();

        $i = 0;
        foreach ($coun as $key) {
          $i += $key->p_amt;
        }
         
     return $i;
    }
    
}
