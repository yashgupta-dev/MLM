	    <!-- partial:partials/_footer.html -->
			<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
				<p class="text-muted text-center text-md-left">Copyright © 2019  All rights reserved</p>
			</footer>
			<!-- partial -->
		
		</div>
	</div>
	
<script src="<?=base_url()?>adminassets/vendors/core/core.js"></script>
<script src="<?=base_url()?>adminassets/vendors/feather-icons/feather.min.js"></script>
<script src="<?=base_url()?>adminassets/js/template.js"></script>
<script src="<?=base_url()?>adminassets/js/dashboard.js"></script>
<script src="<?=base_url()?>adminassets/js/toastr.min.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-3.4.1.js"></script>
<script src="<?=base_url()?>adminassets/js/dropzone.js"></script>
<script src="<?=base_url()?>adminassets/js/admin.js"></script>
<script src="<?=base_url()?>adminassets/js/jquery-confirm.js"></script>
<script src="<?=base_url()?>adminassets/js/holder.min.js"></script>
	<!-- end custom js for this page -->
  <script type="text/javascript">
    $(function(){
      <?php echo $this->session->flashdata('acc');?>    
    });
    
  </script>
<script>
function leftTableFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("leftInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("LeftTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
function rightTableFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("rightInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("RightTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
$(document).on('click','#clickTopass',function(e){
            e.preventDefault();
            var form = $('#passwordForm').serialize();
            $.ajax({
                url:'<?=base_url()?>update_password', 
                method:'post',
                data:form,
                cache:false,
                dataType:'json',
                beforeSend:function(){
                    $('#clickTopass').addClass('disabled');
                    $('#clickTopass').html('please wait <i class="fa fa-spin fa-spinner"></i>');
                },
                success:function(data)
                {
                  $('#clickTopass').removeClass('disabled');
                  $('#clickTopass').html('Change Password');
                  console.log(data);
                  if(data.cr){
                    toastr.error(data.cr);   
                  }
                  if(data.ok){
                    toastr.success(data.ok);      
                    
                  }
                  
                },
                error: function (jqXhr, textStatus, errorMessage) { // error callback 
                    $('#clickTopass').removeClass('disabled');
                    $('#clickTopass').html('Change Password');
                    toastr.error('' + errorMessage + '');
                } 
            });
       });
       
   

</script>
</body>


</html>    