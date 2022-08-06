
<div class="container-fluid"  style="margin-top:70px;">
    <div class="card">
        <div class="card-body" style="box-shadow:1px 1px 12px #dde;">
            <div class="" style="padding: 20px 0px;">
                <form id="redirectForm" method="post" action="<?=base_url()?>api/requestTo">
                    <div class="form-group">
                      <label style="font-weight:900;">ENTER AMOUNT</label><br>
                      <input class="form-control <?php if(!empty(form_error('orderAmount'))){echo ' is_invalid';}?>" name="orderAmount"  style="border-radius:35px 35px 35px 35px;"/>
                      <input name="<?= $this->security->get_csrf_token_name()?>" value="<?= $this->security->get_csrf_hash(); ?>" type="hidden">
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="money">100</div>
                        <div class="money">200</div>
                        <div class="money">500</div>
                    </div>
                    <style>
                        .money {
                            border: 1px solid #ff3366;
                            padding: 5px 15px;
                            border-radius: 35px 35px 35px 35px;
                            font-size: 17px;
                            color: #fff;
                            font-weight: 900;
                            background: #ff3366;
                            font-family: inherit;
                            cursor:pointer;
                        }
                    </style>
                    <button type="submit" class="btn btn-block" value="Pay" id="submitTodata" style="background: #ff3366;color: #fff;border-radius: 35px 35px 35px 35px;padding: 15px;">ADD BALANCE</button>
                    
                  </form>
            </div>
        </div>
    </div>
</div>

