<div class="container-fluid">
    <form action="" id="manage-deposit">
	
		<div class="card-header">
						<b>Generate Invoices </b>
						
					</div>
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="card mx-auto">
		<div class="card-body">
		<div class="form-group col-md">
            <label for="tenant_id">Tenant ID</label>
            <input type="text" class="form-control text-right" name="amount" value="">
        </div>
		
			<div class="form-group col-md">
									<label class="control-label">Month</label>
									<select name="month" class="form-control">
										<option value="">Select Month</option>
										<option value="January">January</option>
										<option value="February">February</option>
										<option value="March">March</option>
										<option value="April">April</option>
										<option value="May">May</option>
										<option value="June">June</option>
										<option value="July">July</option>
										<option value="August">August</option>
										<option value="September">September</option>
										<option value="October">October</option>
										<option value="November">November</option>
										<option value="December">December</option>
									</select>
								</div>
								<div class="form-group col-md">
									<label class="control-label">Year</label>
									<select name="year" class="form-control">
										<option value="">Select Year</option>
										<?php
											$currentYear = date("Y");
											for ($year = $currentYear; $year >= 2000; $year--) {
												echo "<option value='$year'>$year</option>";
											}
										?>
									</select>
								</div>
								<div class="form-group col-md-6">
            
				<input type="radio" id="all_tenant" name="all_tenant" value="all_tenant">
				<label for="tenant">Generate Invoice for all Tenants</label> 
				<input type="radio" id="one_tenant" name="one_tenant" value="one_tenant">
				<label for="tenant">Only this Tenant</label>
			        </div>
					<span class=""><a class="btn btn-primary btn-block btn-sm col-sm-2" href="javascript:void(0)" id="">
					<i class=""></i> Apply
				</a></span>
								</div>
								</div>
    </form>
</div>

<script>
    $('#manage-deposit').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_deposit',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp == 1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    })
	$('#new_deposit').click(function(){
		uni_modal("New deposit","manage_deposit.php","mid-large")
		
	})
	$('.edit_deposit').click(function(){
		uni_modal("Manage deposit Details","manage_deposit.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.view_deposit').click(function(){
		uni_modal("Tenants deposits","view_deposit.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_deposit').click(function(){
		_conf("Are you sure to delete this deposit?","delete_deposit",[$(this).attr('data-id')])
	})
</script>
