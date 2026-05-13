<?php 
include('db_connect.php');
session_start();

$landlord_id = $_SESSION['login_landlordid'];

if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the ID from the GET request$landlord_id = $_GET['landlord_id']; // Get the landlord_id from the GET request

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE landlord_id = ? AND id = ?");
    $stmt->bind_param("ii", $landlord_id, $id); // Bind the parameters as integers
    $stmt->execute(); // Execute the statement

    // Fetch the result
    $result = $stmt->get_result();

    $meta = [];
    while ($row = $result->fetch_assoc()) {
        foreach ($row as $k => $v) {
            $meta[$k] = $v; // Store the fetched data in $meta array
        }
    }

    $stmt->close(); // Close the statement
}

?>
<div class="container-fluid">
    
    <form action="" id="manage_user"> 
       <div class="form-group" style="margin-bottom: 15px;">
            <label for="username" style="color:#000000; font-weight: bold;">Name</label>
            <input type="text" name="name" id="name" class="form-control" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;" value="<?php echo isset($meta['name']) ? $meta['name'] : ''; ?>" required autocomplete="off">
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="username" style="color:#000000; font-weight: bold;">Username</label>
            <input type="text" name="username" id="username" class="form-control" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;" value="<?php echo isset($meta['username']) ? $meta['username'] : ''; ?>" required autocomplete="off">
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="password" style="color:#000000; font-weight: bold;">Password</label>
            <input type="password" name="password" id="password" class="form-control" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;" value="" autocomplete="off">
            <?php if(isset($meta['id'])): ?>
                <small style="color: #666;"><i> </i></small>
            <?php endif; ?>
        </div>
        
        <?php if(isset($meta['type'])): ?>
            <input type="hidden" name="type" value="<?php echo $meta['type'] ?>">
        <?php else: ?>
            <?php if(!isset($_GET['type'])): ?>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="type" style="color:#000000; font-weight: bold;">Select User Type</label>
                    <select name="type" id="type" class="custom-select" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
						<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <p align="center">User rights 0 => Not Allow || 1 => Allow  </p>
		<?php
		
		
		?>

        <!-- Checkbox for Add House Information -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="add_house" style="color:#000000; font-weight: bold;">Add House Information [Category, New House]</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
			<?php 
    if(isset($meta['add_house'])) {
?>
        <input type="text" name="add_house" maxlength="1" value="<?php echo $meta['add_house']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
        <input type="checkbox" name="add_house" id="add_house" class="form-check-input" value="1">
<?php
    }
?>

            </div>
        </div>

        <!-- Checkbox for Enter New Tenant -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="enter_tenant" style="color:#000000; font-weight: bold;">Enter New Tenant</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
               
				<?php 
    if(isset($meta['enter_tenant'])) {
?>
        <input type="text" name="enter_tenant" maxlength="1" value="<?php echo $meta['enter_tenant']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
        <input type="checkbox" name="enter_tenant" id="enter_tenant" class="form-check-input" value="1">
		
<?php
    }
?>
            </div>
        </div>

        <!-- Checkbox for Manage Utility Bills -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="manage_bills" style="color:#000000; font-weight: bold;">Manage Utility Bills</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
                
				<?php 
    if(isset($meta['manage_utility_bills'])) {
?>
        <input type="text" name="manage_bills" maxlength="1" value="<?php echo $meta['manage_utility_bills']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
        <input type="checkbox" name="manage_bills" id="manage_bills" class="form-check-input" value="1">
		
<?php
    }
?>
            </div>
        </div>

        <!-- Checkbox for Manage Tenant Wallet -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="manage_wallet" style="color:#000000; font-weight: bold;">Manage Tenant Wallet</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
				<?php 
    if(isset($meta['manage_tenant_wallant'])) {
?>
        <input type="text" name="manage_wallet" maxlength="1" value="<?php echo $meta['manage_tenant_wallant']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
        <input type="checkbox" name="manage_wallet" id="manage_wallet" class="form-check-input" value="1">
		
<?php
    }
?>
            </div>
        </div>

        <!-- Checkbox for Manage Invoices -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="manage_invoices" style="color:#000000; font-weight: bold;">Manage Invoices</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
              
				<?php 
    if(isset($meta['manage_invoices'])) {
?>
        <input type="text" name="manage_invoices" maxlength="1" value="<?php echo $meta['manage_invoices']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
          <input type="checkbox" name="manage_invoices" id="manage_invoices" class="form-check-input" value="1">
		
<?php
    }
?>
            </div>
        </div>

        <!-- Checkbox for View Invoices -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="view_invoices" style="color:#000000; font-weight: bold;">View Invoices</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
			<?php 
    if(isset($meta['view_invoices'])) {
?>
        <input type="text" name="view_invoices" maxlength="1" value="<?php echo $meta['view_invoices']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
          <input type="checkbox" name="view_invoices" id="view_invoices" class="form-check-input" value="1">
		
<?php
    }
?>
                
            </div>
        </div>

        <!-- Checkbox for View/Print Statements -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="view_statements" style="color:#000000; font-weight: bold;">View / Print Statements</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
                
					<?php 
    if(isset($meta['view_statements'])) {
?>
        <input type="text" name="view_statements" maxlength="1" value="<?php echo $meta['view_statements']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
          <input type="checkbox" name="view_statements" id="view_statements" class="form-check-input" value="1">
		
<?php
    }
?>
            </div>
        </div>

        <!-- Checkbox for House Overview Report -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="house_report" style="color:#000000; font-weight: bold;">House Overview Report</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
               
				<?php 
    if(isset($meta['house_overview_report'])) {
?>
        <input type="text" name="house_report" maxlength="1" value="<?php echo $meta['house_overview_report']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
    <input type="checkbox" name="house_report" id="house_report" class="form-check-input" value="1">
		
<?php
    }
?>
            </div>
        </div>

        <!-- Checkbox for Monthly Reports -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="monthly_reports" style="color:#000000; font-weight: bold;">Monthly Reports</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
               
				<?php 
    if(isset($meta['house_overview_report'])) {
?>
        <input type="text" name="monthly_reports" maxlength="1" value="<?php echo $meta['monthly_reports']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
   <input type="checkbox" name="monthly_reports" id="monthly_reports" class="form-check-input" value="1">
		
<?php
    }
?>
            </div>
        </div>

        <!-- Checkbox for Financial Reports -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="financial_reports" style="color:#000000; font-weight: bold;">Financial Reports</label>
            <div style="display: flex; justify-content: flex-end; align-items: center;">
				<?php 
    if(isset($meta['financial_reports'])) {
?>
        <input type="text" name="financial_reports" maxlength="1" value="<?php echo $meta['financial_reports']; ?>" style="width: 20px; text-align: center;">
<?php
    } else {
?>
    <input type="checkbox" name="financial_reports" id="financial_reports" class="form-check-input" value="1">
		
<?php
    }
?>
				
            </div>
        </div>
    </form>
</div>
<script>
    $('#manage_user').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=processUserForm',
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



