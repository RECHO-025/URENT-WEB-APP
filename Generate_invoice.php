<?php 
include('db_connect.php');

session_start();
 
$landlordid = $_SESSION['login_landlordid']; 



if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM wallet WHERE id=".$_GET['id']);
    
}
?>
<div class="container-fluid">
    <form action="" id="manage_generateInvoice">
	<div class="">
			<p> Generate Invoice(s) </p>
		</div>
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row">
        
		<div class="form-group col-md">
            <label for="tenant_id">Invoice Type </label>
            <select name="invoice_type" id="tenantid" class="custom-select select2" onchange="showResult56(this.value)">
                <option value=""> Select </option>
				<option value="Monthly_Rent"> Monthly Rent </option>
				 <option value="Utility_Bills"> Utility Bills  </option>
            </select>
			<p align="center"> <div id="txtHint56"></div></p>
        </div>
		<div class="form-group col-md">
            <label for="tenant_id">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2">
                <option value=""></option>
                <?php 
                $tenant2 = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name 
				FROM tenants where shopid ='$landlordid' order by name asc");
                while($row2 = $tenant2->fetch_assoc()):
                ?>
                <option value="<?php echo $row2['id'] ?>" <?php echo isset($tenant_id) && $tenant_id == $row2['id'] ? 'selected' : '' ?>><?php echo ucwords($row2['name']) ?></option>
                <?php endwhile; ?>
            </select>
		
        </div>
		<div class="form-group col-md">
            <label for="amount">Months</label>
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
        <div class="form-group col-md-3">
            <label for="paid_by"> Year </label>
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
		</div>
		<!-- <div class="form-group col-md-6">
    <p> Transaction type: </p>
    <input type="radio" id="bank" name="transaction_type" value="manyt">
    <label for="bank"> Generate Invoices for all Tenants</label>  <br />
    <input type="radio" id="cash" name="transaction_type" value="Onlyone">
    <label for="cash">Only this Tenant </label>
</div> -->
    </form>
</div>

<script>
    $('#manage_generateInvoice').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=manage_generateInvoice',
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
</script>
