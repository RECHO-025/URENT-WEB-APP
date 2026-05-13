<?php include('db_connect.php');
$landlord_id = $_SESSION['login_landlordid'];
$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlord_id'";

		$account_type1 =mysqli_query($conn,$sql222);

$account_type = mysqli_fetch_assoc($account_type1)["account_type"];


?>
<body id="page">
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-house">
				<div class="card">
					<div class="card-header">
						<?php
						switch ($account_type){
						    case 1:
						        ?>
						        <b>House Form</b>
						        <?php
						        break;
						    case 2:
						        ?>
						        <b>Plot Form</b>
						        <?php
						        break;
						    default:
						        ?>
						        <b>House/Plot Form</b>
						        <?php
						}
						?>
				  	</div>
					<div class="card-body">
							<div class="form-group" id="msg"></div>
							<input type="hidden" name="id">
							<div class="form-group">
								<?php
								switch ($account_type){
									case 1:
										?>
								<label class="control-label"><b>House No</b></label>
								<input type="text" class="form-control" name="house_no" required="">
										<?php
										break;
									case 2:
										?>
								<label class="control-label"><b>Plot No</b></label>
								<input type="text" class="form-control" name="house_no" required="">
										<?php
										break;
										default:
										?>
								<label class="control-label"><b>House/Plot No</b></label>
								<input type="text" class="form-control" name="house_no" required="">
								<?php
										break;

								}
								?>
							</div>
							<div class="form-group">
								<label class="control-label"><b>Category</b></label>
								<select name="category_id" id="" class="custom-select" required>
									<?php 
									$categories = $conn->query("SELECT * FROM categories where shopid ='$landlordid' order by name asc");
									if($categories->num_rows > 0):
									while($row= $categories->fetch_assoc()) :
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
								<?php endwhile; ?>
								<?php else: ?>
									<option selected="" value="" disabled="">Please check the category list.</option>
								<?php endif; ?>
								</select>
							</div>
							<div class="form-group">
								<label for="" class="control-label"><b>Description</b></label>
								<textarea name="description" id="" cols="30" rows="4" class="form-control" required></textarea>
							</div>
							
							<div class="form-group">
								<?php
								switch ($account_type){
									case 1:
										?>
								<label for="" class="control-label"><b>Status</b> </label>
								<input type="text" class="form-control" name="status" >
								<p  style= "color:white;"><b> 1 => House is occupied, 0 => Empty</b></p>
										<?php
										break;
									case 2:
										?>
								<label for="" class="control-label"><b>Status</b> </label>
								<input type="text" class="form-control" name="status" >
								<p  style= "color:white;"><b> 1 => Plot is taken, 0 => Unsold plot</b></p>
										<?php
										break;
										default:
										?>
								<label for="" class="control-label"><b>Status</b> </label>
								<input type="text" class="form-control" name="status" >
								<p  style= "color:white;"><b> 1 => House/Plot is occupied, 0 => Empty</b></p>
								<?php
										break;
								}
								?>
							</div>
							<div class="form-group">
								<?php
								switch ($account_type){
									case 1:
										?>
							<label for="" class="control-label"><b>House Image</b> </label>
							<input type="file" class="form-control" name="unit_img" accept=".pdf, .jpg, .jpeg, .png">
							<?php
										break;
									case 2:
										?>
							<label for="" class="control-label"><b>Plot Image</b> </label>
							<input type="file" class="form-control" name="unit_img" accept=".pdf, .jpg, .jpeg, .png">
							<?php
							break;
										default:
										?>
							<label for="" class="control-label"><b>Unit Image</b> </label>
							<input type="file" class="form-control" name="unit_img" accept=".pdf, .jpg, .jpeg, .png">
							<?php
										break;
								}
								?>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary space-bottom col-sm"> Save</button>
								<button class="btn btn-sm btn-light col-sm" type="reset" > Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<?php
						switch ($account_type){
						    case 1:
						        ?>
						        <b>House List</b>
						        <?php
						        break;
						    case 2:
						        ?>
						        <b>Plot List</b>
						        <?php
						        break;
						    default:
						        ?>
						        <b>House/Plot List</b>
						        <?php
						}
						?>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Property Image</th>
									<?php
									switch ($account_type){
									    case 1:
									        ?>
									        <th class="">House Details</th>
									        <?php
									        break;
									    case 2:
									        ?>
									        <th class="">Plot Details</th>
									        <?php
									        break;
									    default:
									        ?>
									        <th class="">House/Plot Details</th>
									        <?php
									}

									?>
									<?php
									$landlord_id = $_SESSION['login_landlordid'];
									$landlord_id = $conn->real_escape_string($landlord_id); // Sanitize input

$username = $_SESSION['login_username'];
	
$users_details = $conn->query("SELECT type FROM users WHERE landlord_id = '" . $conn->real_escape_string($landlord_id) . "' AND username = '" . $conn->real_escape_string($username) . "'");


    $row133 = $users_details->fetch_assoc();  // Fetch user details as an associative array
    $type = $row133['type'];
	
	if($type == 1)
			     {	
									
									?>
									<th class="text-center">Action</th>
									
									<?php
								  }
								  else {
								  
								  }
								
								?>
								</tr>
							</thead>
							<tbody>
								<?php
								$landlord_id = $_SESSION['login_landlordid'];
							    $landlord_id = $conn->real_escape_string($landlord_id); // Sanitize input 
								$i = 1;
								$house23 = $conn->query("SELECT h.*, c.name as cname, h.unit_img as houseImage
                         FROM houses h 
                         INNER JOIN categories c ON c.id = h.category_id 
                         WHERE h.shopid = '$landlord_id' 
                         ORDER BY h.id ASC");
						 
						 if (!$house23) {
    die("Query failed: " . $conn->error); // Output the specific error
}


								while($row77=$house23->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<!-- Display the image if it exists -->
									<?php if (!empty($row77['houseImage']) && file_exists("uploads/house_images/" . $row77['houseImage'])): ?>
    <img src="uploads/house_images/<?php echo $row77['houseImage']; ?>" alt="House Image" style="width:150px; height:auto;">
<?php else: ?>
    <p>No image available</p>
<?php endif; ?>

									</td>
									<td class="">
										<?php
										switch ($account_type){
										    case 1:
										        ?>
										<p>House #: <b><?php echo $row77['house_no'] ?></b></p>
										<p><small>House Type: <b><?php echo $row77['cname'] ?></b></small></p>
											<p><small>Description: <b><?php echo $row77['description'] ?></b></small></p></td>
										<?php
										        break;
										    case 2:
										        ?>
										<p>Plot #: <b><?php echo $row77['house_no'] ?></b></p>
										<p><small>Plot Category: <b><?php echo $row77['cname'] ?></b></small></p>
											<p><small>Description: <b><?php echo $row77['description'] ?></b></small></p></td>
										<?php
										        break;
										    default:
										        ?>
										<p>House/Plot #: <b><?php echo $row77['house_no'] ?></b></p>
										<p><small>House/Plot Type: <b><?php echo $row77['cname'] ?></b></small></p>
										<p><small>Description: <b><?php echo $row77['description'] ?></b></small></p></td>
										<?php	
										        break;
										}
										?>
										
									
									
    <?php if ($type == 1): ?>


        <td class="text-center">
            <button class="btn btn-sm btn-primary edit_house" type="button" 
                    data-id="<?php echo $row77['id']; ?>" 
                    data-house_no="<?php echo $row77['house_no']; ?>" 
                    data-description="<?php echo $row77['description']; ?>" 
                    data-category_id="<?php echo $row77['category_id']; ?>" 
                    data-status_id="<?php echo $row77['status']; ?>" 
                    data-unit_img="<?php echo $row77['houseImage']; ?>">Edit</button>
            <button class="btn btn-sm btn-danger delete_house" type="button" data-id="<?php echo $row77['id']; ?>">Delete</button>
        </td>
    <?php endif; ?>
</tr>
<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	body:not(#homepage)::before{
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('images/rentbg.webp'); /* Same background image */
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    filter: blur(8px); /* Apply blur effect */
    z-index: -1; /* Ensure the blurred image stays behind the content */
   transform: scale(1.1);   /*Slightly scale the image to avoid visible edges due to blur */
}

body:not(#homepage) {
   /*  position: relative; /* Ensures the pseudo-element aligns correctly */
}
	td{
		vertical-align: middle !important;
	}
	td p {
		margin: unset;
		padding: unset;
		line-height: 1em;
	}
	.card-header{
	    color:white;
	}
	label.control-label{
	    color:white;
	}
	.card{
	    background-color:transparent;
	}
</style>
<script>
	$('#manage-house').submit(function(e){
    e.preventDefault();
    start_load();
    $('#msg').html('');

    let formData = new FormData($(this)[0]);

    $.ajax({
        url: 'ajax.php?action=save_house',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        success: function(resp){
            console.log('Response:', resp); // Debugging: log the response
            if (resp == 1) {
                alert_toast("Data successfully saved", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else if (resp == 2) {
                $('#msg').html('<div class="alert alert-danger">Changes Saved</div>');
            } else {
                $('#msg').html('<div class="alert alert-danger">Error: ' + resp + '</div>');
            }
            end_load();
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('#msg').html('<div class="alert alert-danger">AJAX Error: ' + error + '</div>');
            end_load();
        }
    });
});

	$('.edit_house').click(function(){
		start_load()
		var cat = $('#manage-house')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='house_no']").val($(this).attr('data-house_no'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		//cat.find("[name='price']").val($(this).attr('data-price'))
		cat.find("[name='category_id']").val($(this).attr('data-category_id'))
		cat.find("[name='status']").val($(this).attr('data-status_id'))
		end_load()
	})
	$('.delete_house').click(function(){
		_conf("Are you sure to delete this house?","delete_house",[$(this).attr('data-id')])
	})
	function delete_house($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_house',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>
</body>
