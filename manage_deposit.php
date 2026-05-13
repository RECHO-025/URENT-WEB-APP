<?php 
include('db_connect.php');

session_start();
 
$landlordid = $_SESSION['login_landlordid']; 

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM wallet WHERE id=".$_GET['id']);
    
}
?>
<div class="container-fluid">
    <form action="" id="manage_deposit">
	<div class="">
			<p> Deposit Transaction Details</p>
		</div>
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row">
        <div class="form-group col-md">
            <label for="tenant_id">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2">
                <option value=""></option>
                <?php 
                $tenant = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM tenants WHERE 
				shopid ='$landlordid' order by name asc");
                while($row = $tenant->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($tenant_id) && $tenant_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
		
        </div>
		<div class="form-group col-md">
            <label for="amount">Amount</label>
            <input type="number" class="form-control text-right" name="amount" value="<?php echo isset($amount) ? $amount : '' ?>" required>
        </div>
        <div class="form-group col-md-3">
            <label for="paid_by">Paid by</label>
            <input type="text" class="form-control" name="paid_by" value="<?php echo isset($paid_by) ? $paid_by : '' ?>" required>
        </div>
		<div class="form-group col-md-3">
            <label for="datedd">Date of Payment</label>
            <input type="date" class="form-control" name="datedd" 
       value="<?php echo isset($Trans_date) ? $Trans_date : date('Y-m-d'); ?>" required>
        </div>
		<div class="form-group col-md-6">
            <p>Select mode of payment</p>
				<input type="radio" id="bank" name="payment_method" value="Bank" <?php echo isset($Mode_pay) && $Mode_pay == 'Bank' ? 'checked' : '' ?>>
				<label for="bank">Bank</label> 
				<input type="radio" id="cash" name="payment_method" value="Cash" <?php echo isset($Mode_pay) && $Mode_pay == 'Cash' ? 'checked' : '' ?>>
				<label for="cash">Cash</label>
				<input type="radio" id="mm" name="payment_method" value="Mobile Money" <?php echo isset($Mode_pay) && $Mode_pay == 'Mobile Money' ? 'checked' : '' ?>>
				<label for="mm">Mobile Money</label>  
        </div>
		<div class="form-group col-md-6">
            <label for="transaction_id">Transaction Id </label>
			<?php
			// Query to count the number of rows
$count_query = $conn->query("SELECT COUNT(*) AS total FROM wallet where shopid ='$landlordid'");

    $row = $count_query->fetch_assoc();
    $total_records = $row['total'] + 1;
    
?>
           <input type="text" class="form-control" name="transaction_id" value="<?php echo isset($transaction_id) ? $transaction_id : $total_records; ?>" />
		</div>
		
		<!-- New checkbox for clearing unpaid invoices -->
		<div class="form-group col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="clear_invoices" name="clear_invoices" value="1" checked>
                <label class="form-check-label" for="clear_invoices">
                    Clear unpaid invoices with this deposit
                </label>
            </div>
        </div>
    </form>
</div>

<script>
    $('#manage_deposit').submit(function(e){
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
                } else {
                    alert_toast("Error: " + resp,'error')
                    end_load()
                }
            }
        })
    })
</script>