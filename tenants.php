<?php include('db_connect.php');
$landlord_id = $_SESSION['login_landlordid'];
$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlord_id'";

		$account_type1 =mysqli_query($conn,$sql222);

$account_type = mysqli_fetch_assoc($account_type1)["account_type"];
?>
<body id="page">
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<?php
						switch ($account_type) {
							case '1':
								echo '<b>List of Tenants</b>';
								break;
							case '2':
								echo '<b>List of Buyers</b>';
							break;
							default:
								echo '<b>Tenants</b>';
						}
						?>
						<?php
						$landlord_id = $_SESSION['login_landlordid'];

$username = $_SESSION['login_username'];
	
$users_details = $conn->query("SELECT type FROM users WHERE landlord_id = '" . $conn->real_escape_string($landlord_id) . "' AND username = '" . $conn->real_escape_string($username) . "'");


    $row133 = $users_details->fetch_assoc();  // Fetch user details as an associative array
    $type = $row133['type'];
	
	if($type == 1)
			     {switch ($account_type) {
					case 1:
						?>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_tenant">
					<i class="fa fa-plus"></i> New Tenant
				</a></span>
						<?php
						break;
					case 2:
						?>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_tenant">
					<i class="fa fa-plus"></i> New Buyer
				</a></span>
						<?php
						break;
					default:
					break;
				}
						?>	
				 <?php
					    }
						else {
						
						}
					?>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Name</th>
									<th class="">Contacts</th>
									<?php
									switch ($account_type) {
										case '1':
											echo '<th class="">Houses Rent</th>';
											break;
										case '2':
											echo '<th class="">Buyers Purchases</th>';
											break;
										default:
											echo '<th class="">Houses Rent</th>';
									}
									?>
								     <?php
									$landlord_id = $_SESSION['login_landlordid'];

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
								$i = 1;
								$tenant = $conn->query("SELECT CONCAT( firstname, ' ', middlename, ' ',lastname) AS name, email,contact,id,id_card,id_number,nextkin,kin_no
								FROM tenants WHERE shopid = '$landlordid'");
								while($row=$tenant->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php
										 $tenantid = $row['id'];
										 echo  $tenantid;
										 echo "   ";
										 echo ucwords($row['name'])
										  ?>
									</td>
									<td>
									<?php 
									echo "<strong>Contact: </strong>" . ucwords($row['contact']) . "<br>";
									echo "<strong>Email: </strong>" . ucwords($row['email']) . "<br>"; 
									echo "<strong>ID Card: </strong>" . ucwords($row['id_card']) . "<br>"; 
									echo "<strong>ID Number: </strong>" . ucwords($row['id_number']) . "<br>";
									echo "<strong>Next of Kin: </strong>" . ucwords($row['nextkin']) . "<br>";
									echo "<strong>Kin No: </strong>" . ucwords($row['kin_no']);
									?>
								</td>

									<td class="">
										 <p>Price: <?php
										 
										  $IDD = $conn->query("SELECT *FROM house_renting WHERE tenant_id = '$tenantid' AND shopid = '$landlordid'");
										  
										  while($row1=$IDD->fetch_assoc()):
										 
										  $houseid = $row1['house_id']; 
										  
										  $date_in = $row1['date_in'];
										  
										 $year = date('Y');
										 $month = date('F');

										  $IDD2 = $conn->query("SELECT price FROM house_data WHERE house_id = '$houseid' AND month = '$month' AND 
										  year = '$year' AND shopid = '$landlordid'");

											if ($IDD2 && $IDD2->num_rows > 0) {
    											$row2 = $IDD2->fetch_assoc();
    													$price = $row2['price'];
														echo number_format($price);
												} else {
    											echo "Price not found.";
													}
														
										 ?> 
										<br>  Description: 
										<?php
										   $IDD3 = $conn->query("SELECT *FROM houses WHERE house_no = '$houseid' AND shopid = '$landlordid'");

											if ($IDD3 && $IDD3->num_rows > 0) {
											  $row3 = $IDD3->fetch_assoc();
    													$housenumber = $row3['house_no'];
														$category = $row3['category_id'];
														$description = $row3['description'];
														$IDD55 = $conn->query("SELECT Status FROM house_renting WHERE 
														house_id = '$houseid' AND shopid = '$landlordid' AND tenant_id ='$tenantid'");
														$row5 = $IDD55->fetch_assoc();

														$status = (isset($row5['Status'])) ? (int)$row5['Status'] : null;
   														 #echo $housenumber;
														 ?>
													
														  <?php 
														  
														  //echo $category;
														  
														     ?> 
															 <?php 
														   echo $housenumber;
														   echo "   ";
														  echo $description ;
														   echo "   ";
														  
														   echo " Status:  "; 
														   if($status =='1')
														     {
														   echo "Occupied" ;
														     }
															 else {
															 
															 echo "Left" ;
															 }
														  
														     ?>
															
															 <?php 
														  
														 // echo $status;
														  
														     ?>
														

														 <?php
												} else {
    											echo "House Data not found.";
													}
										 
										   endwhile;
										 ?>
										 </p>
									</td>
									<?php
									if($type == 1)
			     {	
									
									?>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_tenant" type="button" data-id="<?php echo $row['id'];?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id']; ?>">Delete</button>
									</td>
									<?php
								  }
								  else {
								  
								  }
								
								?>
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
    transform: scale(1.1); /* Slightly scale the image to avoid visible edges due to blur */
}

body:not(#homepage) {
   /*  position: relative; /* Ensures the pseudo-element aligns correctly */
}
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: 150px;
	}
	.card{
	    background-color:transparent;
	}
label.control-label {
    color: black;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable();
	});
	
	$('#new_tenant').click(function(){
		uni_modal("New Tenant", "manage_tenants.php", "mid-large");
	});

	$('.view_payment').click(function(){
		uni_modal("Tenants Payments", "view_payment.php?id=" + $(this).attr('data-id'), "large");
	});

	$('.edit_tenant').click(function(){
		uni_modal("Manage Tenant Details", "manage_tenant.php?id=" + $(this).attr('data-id'), "mid-large");
	});

	$('.delete_tenant').click(function(){
		_conf("Are you sure to delete this Tenant? This action will delete all Payments, Houses rent relating to this tenant.", "delete_tenant", [$(this).attr('data-id')]);
	});

	function delete_tenant(id){
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_tenant',
			method: 'POST',
			data: { id: id },
			success: function(resp){
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success');
					setTimeout(function(){
						location.reload();
					}, 1500);
				}
			}
		});
	}
</script>

</body>
