<?php 
include 'db_connect.php'; 
session_start();
 
$landlordid = $_SESSION['login_landlordid']; 
 

if (isset($_GET['id'])) {
$pid = $_GET['id'];
        $qry = $conn->query("SELECT tenant_id,transaction_id,amount,paid_by,Mode_pay,Trans_date 
		FROM wallet WHERE shopid = '$landlordid' AND id ='$pid'");
    
    if ($qry) {
        $row11 = $qry->fetch_assoc();
		 // Assigning values from the fetched row
		$tenant_id = $row11['tenant_id'];
		$amount = $row11['amount'];
		$paid_by = $row11['paid_by'];
		$Mode_pay  = $row11['Mode_pay'];
		$Trans_date  = $row11['Trans_date'];
		$transaction_id  = $row11['transaction_id'];
	
		$qry = $conn->query("SELECT * FROM tenants WHERE shopid ='$landlordid' AND id ='$tenant_id'");
		$row2 = $qry->fetch_assoc();
		
        $firstname = $row2['firstname'];
        $middlename = $row2['middlename'];
        $lastname = $row2['lastname'];
        $email = $row2['email'];	
		$contact = $row2['contact'];	
		
		// Get distribution details
		$distribution = $conn->query("SELECT * FROM deposit_distribution WHERE wallet_id = '$pid'")->fetch_assoc();
?>
<style>
    label.control-label {
    color: black;
}
.distribution-box {
    border: 1px solid #ddd;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    background: #f9f9f9;
}
.distribution-item {
    display: flex;
    justify-content: space-between;
    margin: 5px 0;
}
</style>
<div class="container-fluid">
	<form action="" id="managewallat"> 
		<input type="hidden" name="id" value="<?php echo $pid; ?>">
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Tenant Id </label>
				<input type="text" class="form-control" name="tenant_id"  value="<?php echo $tenant_id; ?>" readonly="readonly">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Last Name</label>
				<input type="text" class="form-control" name="lastname"  value="<?php echo $lastname; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname"  value="<?php echo $firstname; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label class="control-label">Middle Name</label>
				<input type="text" class="form-control" name="middlename"  value="<?php echo $middlename;?>" readonly="readonly">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label class="control-label">Email</label>
				<input type="email" style="width: 300px;" class="form-control" name="email" value="<?php echo $email; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label class="control-label">Contact #</label>
				<input type="text" class="form-control" name="contact"  value="<?php echo $contact;?>" readonly="readonly">	
			</div>
		</div>
		<label class="control-label"> Deposit Information </label>

		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label"> Transaction Details: </label>
				<input type="text" class="form-control" name="transaction_id"  value="<?php echo $transaction_id; ?>">
			</div>
			<div class="col-md-4">
				<label class="control-label"> Paid By: </label>
				<input type="text" class="form-control" name="paid_by"  value="<?php echo $paid_by; ?>">
			</div>
			<div class="col-md-4">
				<label class="control-label"> Mode of Payment:  </label>
				<input type="text" class="form-control" name="payment_method"  value="<?php echo $Mode_pay; ?>">
			</div>
			<div class="col-md-4">
				<label class="control-label">Amount </label>
				<input type="text" class="form-control" name="amount"  value="<?php echo number_format($amount, 2); ?>">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label class="control-label">Deposit Date: </label>
				<input type="date" style="width: 300px;" class="form-control" name="datedd" value="<?php echo $Trans_date; ?>">
			</div>
		</div>
		
		<!-- Distribution Details -->
		<?php if($distribution): ?>
		<div class="distribution-box">
			<label class="control-label"><strong>Amount Distribution</strong></label>
			<div class="distribution-item">
				<span>Property Manager (<?php echo $distribution['pm_percent']; ?>%):</span>
				<span class="text-primary"><?php echo number_format($distribution['pm_amount'], 2); ?></span>
			</div>
			<div class="distribution-item">
				<span>Urent (<?php echo $distribution['urent_percent']; ?>%):</span>
				<span class="text-info"><?php echo number_format($distribution['urent_amount'], 2); ?></span>
			</div>
			<div class="distribution-item">
				<span>Landlord (<?php echo number_format(100 - $distribution['pm_percent'] - $distribution['urent_percent'], 2); ?>%):</span>
				<span class="text-success"><?php echo number_format($distribution['landlord_amount'], 2); ?></span>
			</div>
			<div class="distribution-item" style="border-top: 1px solid #ccc; padding-top: 5px; margin-top: 5px;">
				<strong><span>Total Amount:</span></strong>
				<strong><span><?php echo number_format($distribution['total_amount'], 2); ?></span></strong>
			</div>
		</div>
		<?php endif; ?>
	</form>
</div>
<?php
 } else {
      echo $pid;
      }
	 }	  
?>
	  
<script>
	$('#managewallat').submit(function(e){
		e.preventDefault();
		start_load();
		$('#msg').html('');
		$.ajax({
			url: 'ajax.php?action=save_deposit',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					alert_toast("Data successfully saved.", 'success');
					setTimeout(function(){
						location.reload();
					}, 1000);
				} else {
					alert_toast("Error: " + resp, 'error');
				}
			},
			error: function(xhr, status, error) {
				console.log("Error:", error);
				alert_toast("An error occurred.", 'error');
			}
		});
	});
</script>