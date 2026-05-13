<?php 
include 'db_connect.php'; 
session_start();
 
$landlordid = $_SESSION['login_landlordid']; 
 

if (isset($_GET['id'])) {
$pid = $_GET['id'];
        $qry = $conn->query("SELECT tenant_id,amount,InvoiceType,Year,Month,House_id,date_created,pay_status 
		FROM payments WHERE shopid = '$landlordid' AND id ='$pid'");
    #$qry = $conn->query("SELECT * FROM tenants WHERE shopid ='$landlordid' AND id ='$id'");
    
    if ($qry) {
        $row11 = $qry->fetch_assoc();
		 // Assigning values from the fetched row
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
?>
<div class="container-fluid">
	<form action="" id="managepayment"> 
		<input type="hidden" name="pid" value="<?php echo $pid; ?>">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Tenant Id </label>
				<input type="text" class="form-control" name="tenant_id"  value="<?php
				echo $tenant_id;
				 
				 ?>" readonly="readonly">
			</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Last Name</label>
				<input type="text" class="form-control" name="lastname"  value="<?php echo $lastname; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname"  value="<?php echo $firstname;'' ?>" readonly="readonly">
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
				</label>
				
			</div>
		</div>
		<label for="" class="control-label"> Payment Information </label>
	
		<div class="col-md-4">
				<label for="" class="control-label">Invoice Type:  </label>
				<input type="text" class="form-control" name="invoice_type"  value="<?php
				echo $InvoiceType;
				 
				 ?>" readonly="readonly">
			</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Months</label>
				<input type="text" class="form-control" name="month"  value="<?php echo $Month; ?>" readonly="readonly">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Year </label>
				<input type="text" class="form-control" name="year"  value="<?php echo $Year; ?>" readonly="readonly">
			</div>
			
		<div class="col-md-4">
				<label for="" class="control-label"> House Id  </label>
				<input type="text" class="form-control" name="houseid"  value="<?php echo $houseid; ?>">
			</div>
			
			<div class="col-md-4">
				<label for="" class="control-label">Amount </label>
				<input type="text" class="form-control" name="amount"  value="<?php echo $amount; ?>">
				<input type="hidden" class="form-control" name="transaction_type"  value="transaction_type">
			
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">Payment Date: </label>
				<input type="text" style="width: 300px;" class="form-control" name="datedd" value="<?php echo $date_created; ?>" readonly="readonly" >

			</div>
		</div>
			</div>
			
		</>
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
			url: 'ajax.php?action=Invoice_makechanges',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success: function(resp) {
				console.log(resp); // Debugging: check what response is coming
				if (resp == 1) {
					alert_toast("Data successfully saved.", 'success');
					setTimeout(function(){
						location.reload();
					}, 1000);
				}
			},
			error: function(xhr, status, error) {
				console.log("Error:", error); // Error callback for network/server issues
			}
		});
	});
</script>
