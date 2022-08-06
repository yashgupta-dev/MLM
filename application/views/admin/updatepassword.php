<div class="page-content">
	<div class="row">
		<div class="col-12 grid-margin">
			<div class="card">
			    <div class="card-body">
				    <h4 class="card-title">Password Update</h4>
					<form class="cmxform" id="passwordForm" method="post">
						<fieldset>
							<div class="form-group">
								<label for="name">Current Passowrd</label>
								<input id="crt_pass" class="form-control"  required="" name="crt_pass" type="text">
								<small class="text-danger" id="cur_pass"></small>
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input id="new_pass" class="form-control"  required="" name="new_pass" type="password">
							</div>
							<div class="form-group">
								<label for="confirm_password">Confirm password</label>
								<input id="cnf_pass" class="form-control"  required="" name="cnf_pass" type="password">
								<small class="text-danger" id="cnf_pass"></small>
							</div>
							<button class="btn btn-primary" type="button" name="submit" id="clickTopass">Change Password</button>
							
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
					
					
					
					<script src="<?php echo base_url();?>adminassets/vendors/core/core.js"></script>
