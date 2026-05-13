<?php 
include 'db_connect.php'; 
session_start();
 
$landlordid = $_SESSION['login_landlordid']; 

?>
<div class="container-fluid">
	<form action="" id="manage-tenant">
		<div class="row form-group">
			<div class="col-md-3">
				<label class="control-label">Tenant Id </label>
				<input type="text" class="form-control" name="tenantid"  value="<?php 
$IDD = $conn->query("SELECT COUNT(id) as max_id FROM tenants where shopid ='$landlordid'");
$row = $IDD->fetch_assoc();
$maxid = $row['max_id'];
$idd = $maxid +1;
echo $idd;
 ?>" required>
			</div>
			<div class="col-md-3">
	            <label for="id_card" class="control-label">ID Card</label>
				<select name="id_card" class="form-control" required>
					<option value="">Select Identification Card</option>
					<option value="National ID">National ID</option>
					<option value="Driving License">Driving License</option>
					<option value="Passport">Passport</option>
				</select>
            </div>
			<div class="col-md-3">
	            <label for="id_number" class="control-label">ID Number</label>
	            <input type="text" class="form-control" name="id_number" maxlength="15"  required>
            </div>
            <div class="col-md-3">
	            <label for="id_document" class="control-label">Upload ID Document (PDF/Image)</label>
	            <input type="file" class="form-control" name="id_document" accept=".pdf, .jpg, .jpeg, .png" required>
            </div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Last Name</label>
				<input type="text" class="form-control" name="lastname" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Middle Name</label>
				<input type="text" class="form-control" name="middlename">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label class="control-label">Email</label>
				<input type="email" class="form-control" name="email" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Contact </label>
				<input type="text" class="form-control" name="contact"  maxlength="15" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">House</label>
				<select name="house_id" class="custom-select select2">
				<?php 
					$houses = $conn->query("SELECT * FROM houses WHERE shopid ='$landlordid' AND status = 0 ORDER BY id ASC");
					if($houses->num_rows > 0):
						while($row = $houses->fetch_assoc()) :
				?>
					<option value="<?php echo $row['house_no'] ?>"><?php echo $row['house_no'] ?></option>
				<?php endwhile; ?>
				<?php else: ?>
					<option selected="" value="" disabled="">Please check the houses list.</option>
				<?php endif; ?>
				</select>
			</div>
		</div>
	</form>
</div>	

<script>
    $('#manage-tenant').submit(function(e){
        e.preventDefault();
        start_load();
        $('#msg').html('');

        // Get all the form field values
        var tenantid = $('input[name="tenantid"]').val();
        var id_card = $('select[name="id_card"]').val();
        var id_number = $('input[name="id_number"]').val();
        var id_document = $('input[name="id_document"]').val();
        var lastname = $('input[name="lastname"]').val();
        var firstname = $('input[name="firstname"]').val();
        var middlename = $('input[name="middlename"]').val();
        var email = $('input[name="email"]').val();
        var contact = $('input[name="contact"]').val();
        var house_id = $('select[name="house_id"]').val();

        // Validation messages
        if (tenantid === "") {
            alert_toast("Tenant ID is required.", );
            end_load();
            return false;
        }

        // Validate that 'ID Card' is selected
        if (id_card === "") {
            alert_toast("Please select an ID card type.", 'danger');
            end_load();
            return false;
        }

        // Validate that 'ID Number' is not empty and is of a reasonable length
        if (id_number === "") {
            alert_toast("Please enter the ID number.", 'danger');
            end_load();
            return false;
        } else if (id_number.length < 6) {
            alert_toast("ID number should be at least 6 characters.", 'danger');
            end_load();
            return false;
        }

        // Validate that a valid document has been uploaded (PDF, JPG, JPEG, PNG)
        var fileInput = $('input[name="id_document"]')[0];
        var filePath = fileInput.value;
        var allowedExtensions = /(\.pdf|\.jpg|\.jpeg|\.png)$/i;

        if (filePath === "") {
            alert_toast("Please upload an ID document.", 'danger');
            end_load();
            return false;
        }

        if (!allowedExtensions.exec(filePath)) {
            alert_toast("Invalid file type. Only PDF, JPG, JPEG, and PNG are allowed.", 'danger');
            end_load();
            return false;
        }

        // Validate Last Name
        if (lastname === "") {
            alert_toast("Please enter the last name.", 'danger');
            end_load();
            return false;
        }

        // Validate First Name
        if (firstname === "") {
            alert_toast("Please enter the first name.", 'danger');
            end_load();
            return false;
        }

        // Optional: Validate Middle Name (only if necessary)
        // if (middlename === "") {
        //     alert_toast("Please enter the middle name.", 'error');
        //     end_load();
        //     return false;
        // }

        // Validate Email
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (email === "") {
            alert_toast("Please enter an email address.", 'danger');
            end_load();
            return false;
        } else if (!emailPattern.test(email)) {
            alert_toast("Please enter a valid email address.", 'danger');
            end_load();
            return false;
        }

        // Validate Contact Number (should be numeric and of valid length)
        if (contact === "") {
            alert_toast("Please enter a contact number.", 'danger');
            end_load();
            return false;
        } else if (!/^\d{10,15}$/.test(contact)) {  // Adjust length based on requirements
            alert_toast("Please enter a valid contact number.", 'danger');
            end_load();
            return false;
        }

        // Validate House ID is selected
        if (house_id === "") {
            alert_toast("Please select a house.", 'danger');
            end_load();
            return false;
        }

        // If all validations pass, proceed with the form submission
        var formData = new FormData($(this)[0]);

        $.ajax({
            url: 'ajax.php?action=save_tenant',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if(resp == 1){
                    alert_toast("Data successfully saved.", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            }
        });
    });
</script>

