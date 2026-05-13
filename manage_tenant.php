<?php 
include 'db_connect.php'; 
session_start();
 
$landlordid = $_SESSION['login_landlordid']; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $qry = $conn->query("SELECT * FROM tenants WHERE shopid ='$landlordid' AND id ='$id'");
    
    if ($qry) {
        $row11 = $qry->fetch_assoc();
        // Assigning values from the fetched row
        $firstname = $row11['firstname'];
        $middlename = $row11['middlename'];
        $lastname = $row11['lastname'];
        $email = $row11['email'];    
        $contact = $row11['contact'];
        $id_card = $row11['id_card']; // ID card type
        $id_number = $row11['id_number']; // ID number
        $id_document = $row11['id_document']; // ID document (file path or name)				
?>
<div class="container-fluid">
    <form action="" id="manage-tenant" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">Tenant Id </label>
                <input type="text" class="form-control" name="tenantid"  value="<?php
                if (isset($_GET['id'])) {
                    echo $_GET['id'];
                } else {
                    echo $idd;
                }
                ?>" readonly="readonly">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">Last Name</label>
                <input type="text" class="form-control" name="lastname"  value="<?php echo isset($lastname) ? $lastname :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">First Name</label>
                <input type="text" class="form-control" name="firstname"  value="<?php echo isset($firstname) ? $firstname :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">Middle Name</label>
                <input type="text" class="form-control" name="middlename"  value="<?php echo isset($middlename) ? $middlename :'' ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label class="control-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">Contact</label>
                <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact :'' ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label class="control-label">ID Card Type</label>
                <input type="text" class="form-control" name="id_card" value="<?php echo isset($id_card) ? $id_card :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">ID Number</label>
                <input type="text" class="form-control" name="id_number" value="<?php echo isset($id_number) ? $id_number :'' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">ID Document</label>
                <?php if (isset($id_document) && !empty($id_document)): ?>
                    <div class="mb-2">
                        <label>Current Document: 
                        <?php 
                        $file = 'uploads/national_ids/' . $id_document; 
                        $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])):
                        ?>
                            <img src="<?php echo $file; ?>" alt="ID Document" style="" />
                        <?php else: ?>
                            <a href="<?php echo $file; ?>" target="_blank"><?php echo $id_document; ?></a>
                        <?php endif; ?>
                        </label>
                    </div>
                <?php endif; ?>
                <input type="file" name="id_document" class="form-control">
                <small class="text-muted">Upload a new ID document (optional)</small>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label class="control-label">House</label>
                <select name="house_id" id="" class="custom-select select2">
                    <?php 
                    $houses = $conn->query("SELECT * FROM houses WHERE shopid ='$landlordid' AND status = 0 ORDER BY id ASC");
                    ?>
                    <option selected="" value=" ">Select</option>
                    <?php
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
<?php
 } else {
        ?>
		
	<div class="container-fluid">
	<form action="" id="manage-tenant">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Tenant Id </label>
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
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Last Name</label>
				<input type="text" class="form-control" name="lastname"  value="" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname"  value="" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Middle Name</label>
				<input type="text" class="form-control" name="middlename"  value="">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label class="control-label">Email</label>
				<input type="email" class="form-control" name="email"  value="" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Contact </label>
				<input type="text" class="form-control" name="contact"  value="" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">House</label>
				<select name="house_id" id="" class="custom-select select2">
				<?php 
									$houses = $conn->query("SELECT * FROM houses where shopid ='$landlordid' AND status = 0 order by id asc");
									if($houses->num_rows > 0):
									while($row= $houses->fetch_assoc()) :
									?>
									<option value="<?php echo $row['house_no'] ?>"><?php echo $row['house_no'] ?></option>
								<?php endwhile; ?>
								<?php else: ?>
									<option selected="" value="" disabled="">Please check the houses list.</option>
								<?php endif; ?>
								</select>
			</div>
			
		</div>
		
</div>	
		
		<?php
       
    }
}
?><style>
	label.control-label{
	    color:black;
	}
</style>
<script>
	
	$('#manage-tenant').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_tenant',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved.",'success')
						setTimeout(function(){
							location.reload()
						},1000)
				}
				
			}
		})
	})
</script>