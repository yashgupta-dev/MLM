<?php
class NewTools extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(true);
        $this->load->model('register');
        date_default_timezone_set('Asia/Kolkata');
    }

function index(){
    $leg = $this->register->dailypayoutDesc();
    
    $data = []; // make array all user data payout;
    $wallet =[];
    $binaryIncome = [];
    if(!empty($leg)){
        foreach ($leg as $key) { 
            if($key->daily_payout == '0'){
            $single = $this->_fetchSingleLagData($key->tableId);
            if(!empty($single)){
                $directCheck = $this->_directCheck($key->member_id,$key->timePeriod,$key->tableId);
                if($directCheck == 'ok'){
                    $data[] = [
                        'userid'=>$key->member_id,
                        'day'=>date('Y-m-d'),
                        'amount'=>$this->_timeDateCheck($key->member_id,$key->timePeriod,$key->tableId),
                        'rank'=>$this->_fetchDataForDailyP($key->tableId,'rank'),
                        'team'=>$this->_maketeamdirect($key->member_id),
                        'dm'=>$this->_fetchDataForDailyP($key->tableId,'amount').'%'
                    ];
                }
            } // if end single leg data;
            }    
           
            $transactionRecoverp[] =$this->_maketransactionStatusChangeAble($key->member_id);
            
            $wallet[] =[
                'user'=>$key->member_id,
                'amount'=>$this->_dailyPWallet($key->member_id) 
            ];
    
       }
        
        $table =[];
        for($i=0; $i<count($data); $i++){
                if(empty($this->_checkIUserHaveOrNot($data[$i]['userid']))){
                    $this->db->insert('daily_p',$data[$i]);
                    $table [] =[
                        'msg'=>'daily inserted '.$data[$i]['userid'].''
                    ];
                }else{
                    if(!empty($this->_dailyPayoutFunctionUserExist($data[$i]['userid'],$data[$i]['day']))){
                        /*$this->db->where('userid',$data[$i]['userid'])
                                 ->where('day',$data[$i]['day'])   
                                 ->set('amount',$data[$i]['amount'])
                                 ->set('rank',$data[$i]['rank'])
                                 ->set('team',$data[$i]['team'])
                                 ->set('dm',$data[$i]['dm'])
                                 ->update('daily_p');*/
                     }else{

                        $this->db->insert('daily_p',$data[$i]);
                        $table [] =[
                            'msg'=>'daily inerted '.$data[$i]['userid'].''
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
        
    
        for ($i=0; $i<count($table); $i++) { 
                echo $i.' : -> '.$table[$i]['msg'].'<br>';
        }
       
    }// if statement close $leg;
    
    $gameData = $this->_fetchGameDetails();
    
    if(!empty($gameData)){
        $game = [];
        foreach($gameData as $row){
            $game[] = [
                'end'=>$row->end,
                'id'=>$row->id,
                'del'=>$this->_checkProbalityGameOver($row->id,$row->end)
                ];
        }
    }
    for($i=0; $i<count($game); $i++){
        $this->db->where('id',$game[$i]['id'])
                 ->update('game',$game[$i]);
        }
    echo '<pre>';
    print_r($game);
    
}

protected function _checkIUserHaveOrNot($id){
    return $this->db->where('userid',$id) 
                    ->select('userid')
                    ->from('daily_p')
                    ->get()->row();
}

protected function _dailyPayoutFunctionUserExist($id,$date){
    return $this->db->where('userid',$id)
                    ->where('day',$date)    
                    ->select('userid')
                    ->from('daily_p')
                    ->get()->row();
}

protected function _checkProbalityGameOver($id,$date){
    if(date('Y-m-d h:i:s') > $date){
        return 0;
    }else{
        return 1;
    }
}

protected function _fetchGameDetails(){
    return $this->db->select('id,del,start,end')->get('game')->result();
}

/*protected function _bvExistOrNot($id){
    return $this->db->where('userid',$id)
                    ->select('userid')
                    ->from('bv')
                    ->get()->row();
}*/

protected function _getWalletBalance($id){
    return $this->db->where('user',$id)
                    ->select('amount')
                    ->from('wallet')
                    ->get()->row('amount');
}

protected function _directIncomeExist($id){
    return $this->db->where('user_id',$id)
                    ->select('user_id')
                    ->from('directincome')
                    ->get()->row();
}
/*
protected function _makeDirectIncomeUsingReferral($id){
    $data = $this->db->where('direct_sp',$id)
                     ->where('status','0')
                     ->select('member_id,side,package_amt')
                     ->from('login')
                     ->get()->result();
    $ref = $this->db->where('type','referral')
                    ->select('direct,amount')                     
                    ->get('single_leg')->row();
    
    $d = [];
    $loop = 1;
    foreach($data as $row){
        
        $d[]= [
            'user'=>$loop,
            'bal'=>$row->package_amt*$ref->amount/100
            ];
    ++$loop;}
    /*if($ref->direct <= count($data)){
        return [
            'user_id'=>$id,
            'income'=>$ref->amount * count($data),
            'direct'=>count($data)
        ];
    }*//*
    $userCount = 0;
    for($a = 0; $a < count($d); $a++){
        $userCount = $d[$a]['user'];
    }
    $total = 0;
    for($c = 0; $c < count($d); $c++){
        $total += $d[$c]['bal'];
    }
    
    $num = $ref->direct;
    for($i = 1; $i < count($d); $i++){
        $table = $i*$num;
        if($userCount == $table){
            return [
                'user_id'=>$id,
                'income'=>$total,
                'direct'=>$table
            ];
            break;
            
        }else{
            
        }
    }
    
    
}*/

//end referral income;


protected function _fetchPercent(){
    return $this->db->where('type','binary')
                    ->where('isActive','0')
                    ->select('amount')
                    ->get('single_leg')->row('amount');
}
protected function _walletExistOrNot($id){
    return $this->db->where('user',$id)
                      ->select('amount')
                      ->from('wallet')
                      ->get()->row();
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

protected function _winningAMountCal($id){
    $data = $this->_countWinningBalance($id);
    $total = 0;
   foreach ($data as $key) {
     $total += $key->amount;
   }
   return $total;
}
protected function _countWinningBalance($id)
  {
      return $this->db->where('user',$id)
                      ->select('amount')
                      ->from('winners')
                      ->get()->result();
  }
protected function _fetchCDate($day,$user){
        return $this->db->where('day',$day)
                        ->where('userid',$user)
                        ->from('daily_p')
                        ->get()->row();
}

protected function _updateDataDailyPay($data){
    return $this->db->update('daily_p', $data,'userid');
}
protected function _directCheck($id,$time,$tab){
    if(date('Y-m-d h:i:s') <= $time){
        return 'ok';
    }

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

protected function _fetchSingleLagData($id){
    return $this->db->where('id',$id)
                    ->where('isActive','0')
                    ->where('type','single')
                    ->select('rank,amount,days,direct,team')
                    ->from('single_leg')
                    ->get()->row();    
}

protected function _maketeamdirect($direct){
    
    return 1 + $this->_fetch_perviousTeam($direct);  
}

protected function _fetch_perviousTeam($direct){
    return $this->db->where('userid',$direct)
                    ->select('team')
                    ->from('daily_p')
                    ->get()->row('daily_p');
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