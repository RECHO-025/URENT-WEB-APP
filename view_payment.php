<?php 
include 'db_connect.php'; 
session_start();
 
$landlordid = $_SESSION['login_landlordid']; 
 
if (isset($_GET['id'])) {
$pid = $_GET['id'];
        $qry = $conn->query("SELECT id, tenant_id, amount, InvoiceType, Year, Month, House_id, date_created, pay_status 
		FROM payments WHERE shopid = '$landlordid' AND id ='$pid'");
    
    if ($qry) {
        $row11 = $qry->fetch_assoc();
		 // Assigning values from the fetched row
		$payment_id = $row11['id'];
		$tenant_id = $row11['tenant_id'];
		$amount = $row11['amount'];
		$InvoiceType = $row11['InvoiceType'];
		$Year = $row11['Year'];
		$Month = $row11['Month'];
		$date_created = $row11['date_created'];
		$pay_status  = $row11['pay_status']; 
		$houseid  = $row11['House_id']; 
		
		$qry = $conn->query("SELECT * FROM tenants WHERE shopid ='$landlordid' AND id ='$tenant_id'");
		$row2 = $qry->fetch_assoc();
		
        $firstname = $row2['firstname'];
        $middlename = $row2['middlename'];
        $lastname = $row2['lastname'];
        $email = $row2['email'];	
		$contact = $row2['contact'];	
		
		// Get distribution details if invoice is paid
		$distribution = $conn->query("SELECT * FROM invoice_distribution WHERE payment_id = '$payment_id'")->fetch_assoc();
?>
<div class="container-fluid">
	<form action="" id="managepayment"> 
		<input type="hidden" name="id" value="<?php echo $payment_id; ?>">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Tenant Id </label>
				<input type="text" class="form-control" name="tenant_id"  value="<?php echo $tenant_id; ?>" readonly="readonly">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Last Name</label>
				<input type="text" class="form-control" name="lastname"  value="<?php echo $lastname; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname"  value="<?php echo $firstname; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Middle Name</label>
				<input type="text" class="form-control" name="middlename"  value="<?php echo $middlename;?>" readonly="readonly">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">Email</label>
				<input type="email" style="width: 300px;" class="form-control" name="email" value="<?php echo $email; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Contact #</label>
				<input type="text" class="form-control" name="contact"  value="<?php echo $contact;?>" readonly="readonly">	
			</div>
		</div>
		<label for="" class="control-label"> Payment Information </label>
	
		<div class="col-md-4">
			<label for="" class="control-label">Invoice Type:  </label>
			<input type="text" class="form-control" name="invoice_type"  value="<?php echo $InvoiceType; ?>" readonly="readonly">
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Month</label>
				<input type="text" class="form-control" name="month"  value="<?php echo $Month; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Year </label>
				<input type="text" class="form-control" name="year"  value="<?php echo $Year; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label"> House Id  </label>
				<input type="text" class="form-control" name="houseid"  value="<?php echo $houseid; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Amount </label>
				<input type="text" class="form-control" name="amount"  value="<?php echo $amount; ?>">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Payment Status </label>
				<select class="form-control" name="pay_status">
					<option value="0" <?php echo $pay_status == 0 ? 'selected' : ''; ?>>Not Paid</option>
					<option value="1" <?php echo $pay_status == 1 ? 'selected' : ''; ?>>Paid</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">Payment Date: </label>
				<input type="text" style="width: 300px;" class="form-control" name="datedd" value="<?php echo $date_created; ?>" readonly="readonly" >
			</div>
		</div>
		
		<!-- Distribution Details -->
		<?php if($pay_status == 1 && $distribution): ?>
		<div class="distribution-box" style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; background: #f9f9f9;">
			<label class="control-label"><strong>Amount Distribution</strong></label>
			<div class="distribution-item" style="display: flex; justify-content: space-between; margin: 5px 0;">
				<span>Property Manager (<?php echo $distribution['pm_percent']; ?>%):</span>
				<span class="text-primary"><?php echo number_format($distribution['pm_amount'], 2); ?></span>
			</div>
			<div class="distribution-item" style="display: flex; justify-content: space-between; margin: 5px 0;">
				<span>Urent (<?php echo $distribution['urent_percent']; ?>%):</span>
				<span class="text-info"><?php echo number_format($distribution['urent_amount'], 2); ?></span>
			</div>
			<div class="distribution-item" style="display: flex; justify-content: space-between; margin: 5px 0;">
				<span>Landlord (<?php echo number_format(100 - $distribution['pm_percent'] - $distribution['urent_percent'], 2); ?>%):</span>
				<span class="text-success"><?php echo number_format($distribution['landlord_amount'], 2); ?></span>
			</div>
			<div class="distribution-item" style="border-top: 1px solid #ccc; padding-top: 5px; margin-top: 5px; display: flex; justify-content: space-between;">
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
	$('#managepayment').submit(function(e){
		e.preventDefault();
		start_load();
		$('#msg').html('');
		$.ajax({
			url: 'ajax.php?action=save_payment',
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