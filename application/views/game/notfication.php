
<div class="container" style="margin-top:70px;">
    
    <div class="row">
        <?php $notifi = $this->db->where('user',$this->session->userdata('gameuser'))->order_by('id','desc')->from('notification')->get()->num_rows();?>
        <?php if(!empty($notifi)){?>
            <div class="mb-3">
            <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8">
                <tbody>
                    <tr>
                        <td>
                            <table style="/* background-color: #f2f3f8; *//* max-width:670px; *//* margin:0 auto; */" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width: 100%;background:#fff;border-radius:3px;text-align:left;/* -webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06); */-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow: 0 0px 18px 0 rgba(0,0,0,.06);">
                                                <tbody>
                                                    <tr>
                                                        <td style="padding: 25px;">
                                                            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                                                                <tbody>
                                                                                <?php $notific  = $this->db->where('user',$this->session->userdata('gameuser'))->order_by('id','desc')->from('notification')->get()->result();
                                                                                foreach($notific as $row){?>
                                                                                    <tr style="border-bottom:1px solid #ebebeb; margin-bottom:10px; padding-bottom:10px; display:block;">
                                                                                        <td style="vertical-align:top;">
                                                                                            <h3 class="addclassNew" style="color: #4d4d4d; font-size: 16px; font-weight: 400; line-height: 30px; margin-bottom: 3px; margin-top:0;">
                                                                                                <strong><?=$row->type?></strong> 
                                                                                                <div clas="border-top"></div>
                                                                                                <p style="font-size:15px; text-transform: capitalize;"><?=$row->msg?></p>
                                                                                                <span style="color:#737373; font-size:14px;"><?=$row->created_at?></span>
                                                                                            </h3>
                                                                                        </td>
                                                                                    </tr>      
                                                                                <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <?php } else{ ?>
                <div class="col-md-12">
                    <div class="text-center">
                        <p style="font-size:20px;color: #47474c;">No Notifications</p>
                        <div class="">
                          <i class="fa fa-bell" style="font-size:200px;color: #eaeaea;"></i>
                        </div>
                    </div>
                </div>
              <?php } ?>
      </div>
</div>
