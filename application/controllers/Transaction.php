<?php
class Transaction extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(true);
        $this->load->model('register');
        date_default_timezone_set('Asia/Kolkata');
    }

function index(){
    $leg = $this->register->dailypayout();
    $transactionRecoverp = [];
    if(!empty($leg)){
        foreach ($leg as $key) { 
            $transactionRecoverp[] =$this->_maketransactionStatusChangeAble($key->member_id);
        }
        echo '<pre>';
        print_r($transactionRecoverp);
    }// if statement close $leg;
}

protected function _maketransactionStatusChangeAble($id){

    return $this->_getTransactionIDUserBANK($id);

}

protected function _getTransactionIDUserBANK($id){
    $data = $this->db->where('user',$id)->where('method','BANK')->select('txn,user')->from('transaction')->get()->result_array();
    if(!empty($data)){
        $cred = $this->_getPayoutDetails();
        $total = [];
        foreach($data as $ta){
            $token = $this->_getToken($cred->appid,$cred->secretkey,$cred->mode);
            $total[] =[
                'user'=>$ta['user'],
                'txn'=>$ta['txn'],
                'status'=>$this->_getTransferStatus($token,$ta['txn'],$cred->mode)
                ];
            }
        $for = [];
        
        for($i=0; $i<count($total); $i++){
            if(isset($total[$i]['status']['data']['transfer'])){
                $this->db->where('user',$total[$i]['user'])
                         ->where('txn',$total[$i]['txn'])
                         ->set('status',$total[$i]['status']['data']['transfer']['status'])
                         ->update('transaction');       
                         
                $noti = [
                    'user' => $total[$i]['user'],
                    'type' => 'PAYMENT '.$total[$i]['status']['data']['transfer']['status'],
                    'msg' => 'amount transfer successfully of '.$total[$i]['status']['data']['transfer']['amount'].' Rs. of refrence id #'.$total[$i]['status']['data']['transfer']['referenceId'].' at '.$total[$i]['status']['data']['transfer']['processedOn'],
                    'refrence'=>$total[$i]['status']['data']['transfer']['referenceId'],
                    'created_at' => date('Y-m-d h:i:s')
                ];
                
                /*$this->db->where('user',$total[$i]['user'])
                         ->where('refrence',$total[$i]['status']['data']['transfer']['referenceId'])
                         ->set('type','PAYMENT '.$total[$i]['status']['data']['transfer']['status'])
                         ->set('msg','amount transfer successfully of '.$total[$i]['status']['data']['transfer']['amount'].' Rs. of refrence id #'.$total[$i]['status']['data']['transfer']['referenceId'].' at '.$total[$i]['status']['data']['transfer']['processedOn'])
                         ->set('created_at',date('Y-m-d h:i:s'))
                         ->update('transaction');  */     
                
                if(empty($this->_fetchRefrenceExistOrnot($total[$i]['status']['data']['transfer']['referenceId']))){
                    $this->db->insert('notification',$noti);    
                }
                
                $for[] =[
                    'noti'=>'Notificatio Updated',
                    'msg'=>'Transaction Status Updated '.$total[$i]['user'].','
                ];
                
            }
            
        }
        return $for;
        
    }else{
        //return $data;
    }
    
    
}

protected function _fetchRefrenceExistOrnot($id){
    return $this->db->where('refrence',$id)->select('refrence')->get('notification')->row();
}

protected function _getTransferStatus($token,$txn,$mode){
    if($mode == 'PROD'){
 	        $url='https://payout-api.cashfree.com/';
 	    }else{
 	        $url='https://payout-gamma.cashfree.com/';    
 	    }
    $final  = $url.'payout/v1/getTransferStatus?transferId='.$txn;
    $ch = curl_init($final);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$token.'',
        'Content-Type: application/json'
    ]);

    $result = curl_exec($ch);
    if(curl_errno($ch)){
        print('error in posting');
        print(curl_error($ch));
        die();
    }
    curl_close($ch);
    $rObj = json_decode($result,true);
    return $rObj;
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
protected function _getPayoutDetails(){
 	    return $this->db->select('appid,secretkey,mode')
 	                    ->where('type','payout')
                        ->from('credential')
                        ->get()->row();
}

    
}