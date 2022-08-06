<?php
class Right extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
                $this->output->enable_profiler(true);
        $this->load->model('register');
        date_default_timezone_set('Asia/Kolkata');
    }

function index(){
        $leg = $this->register->dailypayoutBinary(); // wait i will open 
        $i =0;
        if(!empty($leg)){
             foreach ($leg as $row) {
                 
                echo $i.'Income :=> '.$row->member_id.'<br>';
                //$this->makeLeftFirstTreedata($row->member_id,$row->id);
                $right = $this->_protectedFetchRightTeam($row->id);
    $left = $this->_protectedFetchLeftTeam($row->id);
    $rightD = $this->_directRightUser($row->member_id);
    $leftD = $this->_directLeftUser($row->member_id);
    $binary =[];
  if(!empty($rightD) && !empty($leftD)) {
    //$binary = [];
    if(!empty($right) && !empty($left)){
      
      $rightTotal = 0;
          foreach ($right as $key) {
            $rightTotal += $key['p_amt'];
          }
          $leftTotal = 0;
          foreach ($left as $key) {
            $leftTotal += $key['p_amt'];
          }

      $exist = $this->_fetchLastBinary($row->member_id);
        if(!empty($exist)){

            $rightE = $this->_protectedFetchRightTeam($row->id);
            $leftE = $this->_protectedFetchLeftTeam($row->id);
            $rightETotal = 0;
            foreach ($rightE as $key) {
              $rightETotal += $key['p_amt'];
            }
            $leftETotal = 0;
            foreach ($leftE as $key) {
              $leftETotal += $key['p_amt'];
            } 
                  $pre = $this->_previousBinaryRecord($row->member_id);
                  if($pre->side == 'Right'){
                      $finalRight = $rightETotal - $exist->right_id;
                      $finalLeft = $leftETotal - $exist->left_id;

                      if($finalLeft != 0 && $finalRight !=0){
                        if($finalRight > $finalLeft){
                             
                              $binary[] = [ 
                                'userid'=>$row->member_id,
                                'amount'=>$finalLeft * $this->_fetchPercent()/100,
                                'carry'=>$finalRight - $finalLeft,
                                'side'=>'Right',
                                'right_id'=>$finalLeft + $exist->right_id,
                                'left_id'=>$finalLeft + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        } elseif ($finalRight < $finalLeft) {
                          
                          $binary[] = [ 
                                'userid'=>$row->member_id,
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Left',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        }else{
                          
                          $binary[] = [ 
                                'userid'=>$row->member_id,
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Null',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];
                        }  
                      }
                      
                  } elseif ($pre->side == 'Left') {

                      $finalLeft = $leftETotal - $exist->left_id;
                      $finalRight = $rightETotal - $exist->right_id;

                      if($finalLeft != 0 && $finalRight !=0){
                        
                        if($finalRight > $finalLeft){
                             
                              $binary[] = [ 
                                'userid'=>$row->member_id,
                                'amount'=>$finalLeft * $this->_fetchPercent()/100,
                                'carry'=>$finalRight - $finalLeft,
                                'side'=>'Right',
                                'right_id'=>$finalLeft + $exist->right_id,
                                'left_id'=>$finalLeft + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        } elseif ($finalRight < $finalLeft) {
                          
                          $binary[] = [ 
                                'userid'=>$row->member_id,
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Left',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];

                        }else{
                          
                          $binary[] = [ 
                                'userid'=>$row->member_id,
                                'amount'=>$finalRight * $this->_fetchPercent()/100,
                                'carry'=>$finalLeft - $finalRight,
                                'side'=>'Null',
                                'right_id'=>$finalRight + $exist->right_id,
                                'left_id'=>$finalRight + $exist->left_id,
                                'created_at'=>date('Y-m-d'),
                              ];
                          }  

                      } 

        }else{
      
                $finalLeft = $leftETotal - $exist->left_id;
                $finalRight = $rightETotal - $exist->right_id;

                  if($finalLeft != 0 && $finalRight !=0){
                    
                    if($finalRight > $finalLeft){
                         
                          $binary[] = [ 
                            'userid'=>$row->member_id,
                            'amount'=>$finalLeft * $this->_fetchPercent()/100,
                            'carry'=>$finalRight - $finalLeft,
                            'side'=>'Right',
                            'right_id'=>$finalLeft + $exist->right_id,
                            'left_id'=>$finalLeft + $exist->left_id,
                            'created_at'=>date('Y-m-d'),
                          ];

                    } elseif ($finalRight < $finalLeft) {
                      
                      $binary[] = [ 
                            'userid'=>$row->member_id,
                            'amount'=>$finalRight * $this->_fetchPercent()/100,
                            'carry'=>$finalLeft - $finalRight,
                            'side'=>'Left',
                            'right_id'=>$finalRight + $exist->right_id,
                            'left_id'=>$finalRight + $exist->left_id,
                            'created_at'=>date('Y-m-d'),
                          ];

                    }else{
                      
                      $binary[] = [ 
                            'userid'=>$row->member_id,
                            'amount'=>$finalRight * $this->_fetchPercent()/100,
                            'carry'=>$finalLeft - $finalRight,
                            'side'=>'Null',
                            'right_id'=>$finalRight + $exist->right_id,
                            'left_id'=>$finalRight + $exist->left_id,
                            'created_at'=>date('Y-m-d'),
                          ];
                    }  
              }
      }
          

           

        }else{// if condition end else start;            
        /// count b usineess notghing else;

        if( $rightTotal > $leftTotal){
                  $binary[] = [ 
                  'userid'=>$row->member_id,
                  'amount'=>$leftTotal * $this->_fetchPercent()/100,
                  'carry'=>$rightTotal - $leftTotal,
                  'side'=>'Right',
                  'right_id'=>$leftTotal,
                  'left_id'=>$leftTotal,
                  'created_at'=>date('Y-m-d')
                ];
                  
          


        }elseif ( $rightTotal < $leftTotal) {

          
                  $binary[] = [ 
                  'userid'=>$row->member_id,
                  'amount'=>$rightTotal * $this->_fetchPercent()/100,
                  'carry'=>$leftTotal - $rightTotal,
                  'side'=>'Left',
                  'right_id'=>$rightTotal,
                  'left_id'=>$rightTotal,
                  'created_at'=>date('Y-m-d')
                ];
                  
          
        }else{

          
                  $binary[] = [ 
                  'userid'=>$row->member_id,
                  'amount'=>$rightTotal * $this->_fetchPercent()/100,
                  'carry'=>$rightTotal - $leftTotal,
                  'side'=>'Null',
                  'right_id'=>$rightTotal,
                  'left_id'=>$rightTotal,
                  'created_at'=>date('Y-m-d')
                ];
                  
          
          

        }//insert condition end;
      }
    }
    
    for ($i=0; $i < count($binary) ; $i++) { 
        if(!empty($binary[$i])){
          if(!empty($this->_protectedFunctiontestfetchToday($row->member_id,date('Y-m-d')))){
              $total = $binary[$i]['amount'] + $this->fetchPerviousAmount($row->member_id,date('Y-m-d'));
               $this->db->where('userid',$binary[$i]['userid'])
                         ->where('created_at',$binary[$i]['created_at'])
                         ->set('amount',$total)
                         ->set('right_id',$binary[$i]['right_id'])
                         ->set('left_id',$binary[$i]['left_id']) 
                         ->set('carry',$binary[$i]['carry']) 
                         ->set('side',$binary[$i]['side']) 
                         ->update('binary');      

          }else{
            $this->db->insert('binary',$binary[$i]);
          
          }
            
        }
    }

  }
    //bv caluclations
      $bvMatching = $this->_fetchMatchingBusinessBinary($row->member_id);
      
      $bvArray = [
          'userid' => $row->member_id,
          'RightBv' => $bvMatching/$this->_bvMakeFunctionAmount(),
          'created' => date('Y-m-d')
      ];
      
        if(!empty($bvArray)){
          if(!empty($this->_protectedFetchBvExistOrnot($row->member_id))){
              $this->db->where('userid',$bvArray['userid'])
                       ->set('RightBv',$bvArray['RightBv'])
                       ->set('created',$bvArray['created'])
                       ->update('bv');
          }else{

              $this->db->insert('bv',$bvArray);
          }
        }

    //end;
    //direct spopnser 

    $referral[] = $this->_makeDirectIncomeUsingReferral($row->member_id);
       for($i=0; $i<count($referral); $i++){
      if(!empty($referral[$i])){   
          //echo '<pre>';
          //print_r($referral[$i]);
          if(!empty($this->_directIncomeExist($referral[$i]['user_id']))){
              $this->db->where('user_id',$referral[$i]['user_id'])
                      ->set('income',$referral[$i]['income'])
                      ->set('direct',$referral[$i]['direct'])
                      ->update('directincome');       
                //  echo 'Referral update '.$referral[$i]['user_id'].',';
          }
          else{
              $this->db->insert('directincome',$referral[$i]);
              //echo 'Referral insert '.$referral[$i]['user_id'].',';
          }
      }else{
          
      }
  }




    //end

    $fetchCapping = $this->_protectedMakeCappingdata($row->member_id,date('Y-m-d'));
    
    $cappingRecord =[];
    foreach ($fetchCapping as $key) {
      $cappingRecord[] =[
          'userid'=>$key['userid'],
          'amount'=>$this->_cappingPriceGenerate($key['userid'],$key['created_at']),
          'created_at'=>$key['created_at']
      ];
    }

      for ($c=0; $c < count($cappingRecord) ; $c++) { 

        if(!empty($cappingRecord[$c])){
            if(!empty($this->_cappingExist($row->member_id,date('Y-m-d')))){
                  $this->db->where('userid',$cappingRecord[$c]['userid'])
                           ->where('created_at',$cappingRecord[$c]['created_at'])
                           ->set('amount',$cappingRecord[$c]['amount']) 
                           ->update('userbinary');

            }else{
                $this->db->insert('userbinary',$cappingRecord[$c]);
              
            }
        }
      
    }
    
            sleep(1);    
                $i++;
            }
            
            
        }
}

protected function _protectedFetchBvExistOrnot($id){
  return $this->db->where('userid',$id)->select('userid')->get('bv')->row();
}

protected function _bvMakeFunctionAmount(){
  return $this->db->where('type','BV')->select('amount')->get('single_leg')->row('amount');
}

protected function _fetchMatchingBusinessBinary($id){
  $id =  $this->db->where('userid',$id)->order_by('id','desc')->limit('1')->select('right_id')->get('binary')->row();
  if(!empty($id)){
      return $id->right_id;
  }else{
      return 0;
  }
}

protected function _directRightUser($id){
  return $this->db->where('direct_sp',$id)->where('status !=','1')->where('side','Right')->select('member_id')->get('login')->row('member_id');
}

protected function _directLeftUser($id){
  return $this->db->where('direct_sp',$id)->where('status !=','1')->where('side','Left')->select('member_id')->get('login')->row('member_id');
}
protected function _cappingPriceGenerate($id,$today){
    $cappingRatio =  $this->_fetchcapping();
    $binaryToday = $this->db->where('userid',$id)->where('created_at',$today)->select('amount')->get('binary')->row('amount'); 
    $package = $this->_fetchUIserPackageDetails($id);
    $cappincome = $package * $cappingRatio;

    if($binaryToday > $cappincome){
        return $cappincome;
    }elseif($binaryToday < $cappincome){
        return $binaryToday;
    }else{
      return $binaryToday;
    }
}

protected function _fetchUIserPackageDetails($id){
  return $this->db->where('member_id',$id)->select('package_amt')->get('login')->row('package_amt');
}
protected function _cappingExist($id,$today){
    return $this->db->where('userid',$id)->where('created_at',$today)->select('userid')->get('userbinary')->row();
}

protected function _fetchcapping(){
  return $this->db->where('type','capping')
                  ->where('isActive','0')
                  ->select('amount')
                  ->get('single_leg')->row('amount');
}

protected function _protectedMakeCappingdata($id,$today){
   return $this->db->where('userid',$id)->where('created_at',$today)->select('*')->get('binary')->result_array(); 
}

protected function fetchPerviousAmount($id,$today){
  return $this->db->where('userid',$id)->where('created_at',$today)->select('amount')->get('binary')->row('amount'); 
}

protected function _protectedFunctiontestfetchToday($id,$today){
    return $this->db->where('userid',$id)->where('created_at',$today)->select('userid')->get('binary')->row();
}

protected function _previousBinaryRecord($id){
  return $this->db->where('userid',$id)
                  ->order_by('id','desc')
                  ->select('userid,amount,side,carry,right_id,left_id')
                  ->from('binary')
                  ->get()->row();
}

protected function _fetchPercent(){
  return $this->db->where('type','binary')
                  ->where('isActive','0')
                  ->select('amount')
                  ->get('single_leg')->row('amount');
}




protected function _fetchLastBinary($id){
  return $this->db->where('userid',$id)->select('right_id,left_id,carry')->order_by('id','desc')->get('binary')->row();
}
protected function _protectedFetchRightTeam($id){
  return $this->db->where('parent',$id)
                  ->where('teamside','Right')
                  ->select('p_amt,id,user_id')
                  ->order_by('id','asc')
                  ->where('status !=','1')
                  ->get('makebinary')->result_array();
}

protected function _protectedFetchLeftTeam($id){
  return $this->db->where('parent',$id)
                  ->where('teamside','Left')
                  ->select('p_amt,id,user_id')
                  ->where('status !=','1')
                  ->order_by('id','asc')
                  ->get('makebinary')->result_array();
}

protected function _makeDirectIncomeUsingReferral($id){
  $data = $this->db->where('direct_sp',$id)
                   ->where('status !=','1')
                   ->select('member_id,side,package_amt')
                   ->from('login')
                   ->get()->result();
  $ref = $this->db->where('type','referral')
                  ->select('direct,amount')                     
                  ->get('single_leg')->row();
  
  $d = [];
  $loop =0;
  foreach($data as $row){
      $d[]= [
          'user'=>$loop,
          'bal'=>$row->package_amt*$ref->amount/100
          ];
  ++$loop;}

  $total = 0;
  for($c = 0; $c < count($d); $c++){
      $total += $d[$c]['bal'];
  }
  return [
        'user_id'=>$id,
        'income'=>$total,
        'direct'=>$loop
    ];  
  
}


protected function _directIncomeExist($id){
  return $this->db->where('user_id',$id)
                  ->select('user_id')
                  ->from('directincome')
                  ->get()->row();
}


} ?>