<?php include('db_connect.php'); 

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM wallet WHERE shopid = '$landlordid' AND id=".$_GET['id']);
    
}

?>
<body id="page">
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="managehouseprice">
					<div class="card">
						<div class="card-header">
							<b>House Price Form</b>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							
							<div class="row">
								<!-- House Dropdown -->
								<div class="form-group col-md-6">
									<label class="control-label"><b>House</b></label>
									<select name="house_id" class="form-control">
										<option value=""></option>
										<b><?php 
											$houses = $conn->query("SELECT id, house_no FROM houses WHERE shopid = '$landlordid' ORDER BY house_no ASC");
											while($row = $houses->fetch_assoc()):
										?></b>
											<option value="<?php echo $row['house_no'] ?>"><?php echo $row['house_no'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
								<!-- Price Input -->
								<div class="form-group col-md-6">
									<label class="control-label"><b>Price</b></label>
									<input type="number" class="form-control" name="price">
								</div>
							</div>

							<div class="row">
								<!-- Month Dropdown -->
								<div class="form-group col-md-6">
									<label class="control-label"><b>Month</b></label>
									 <div>
<input type="checkbox" name="month[]" value="January"> 
<span style="color: white;">January</span>
        
    </div>
    <div>
        
        <input type="checkbox" name="month[]" value="February"> 
        <span style="color: white;">February</span>
    </div>
    <div>
        <input type="checkbox" name="month[]" value="March"> 
                <span style="color: white;">March</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="April"> 
                <span style="color: white;">April</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="May"> 
                <span style="color: white;">May</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="June"> 
                <span style="color: white;">June</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="July"> 
                <span style="color: white;">July</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="August"> 
                <span style="color: white;">August</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="September"> 
                <span style="color: white;">September</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="October">
                <span style="color: white;">October</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="November"> 
                <span style="color: white;">November</span>

    </div>
    <div>
        <input type="checkbox" name="month[]" value="December"> 
                <span style="color: white;">December</span>

    </div>
								</div>
								<!-- Year Dropdown -->
								<div class="form-group col-md-6">
									<label class="control-label"><b>Year</b></label>
									<select name="year" class="form-control">
										<option value="">Select Year</option>
										<?php
            							$currentYear = date("Y");
            							$endYear = 2034;  // Define the future year limit
            							for ($year = $currentYear; $year <= $endYear; $year++) {
               						 echo "<option value='$year'>$year</option>";
            							}
       								 ?>
									</select>
								</div>
							</div>
						</div>

						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary space-bottom col-sm" type="submit"> Save</button>
									<button class="btn btn-sm btn-light col-sm" type="reset"> Cancel</button> 
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
						<b>House Prices</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">House</th>
									<th class="text-center">Price</th>
									<th class="text-center">Month</th>
									<th class="text-center">Year</th>
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
								$house_price = $conn->query("SELECT hd.*, h.house_no 
FROM house_data hd 
INNER JOIN houses h ON h.house_no = hd.house_id 
WHERE h.shopid = '$landlordid' 
ORDER BY hd.id ASC
");
								while($row = $house_price->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><b><?php echo $i++ ?></b></td>
									<td class=""><b><?php echo $row['house_no'] ?></b></td>
									<td class=""><b><?php echo number_format($row['price'],0) ?><b></td>
									<td class=""><b><?php echo $row['month'] ?></b></td>
									<td class=""><b><?php echo $row['year'] ?></b></td>
									<?php
									if($type == 1)
			     {	
									
									?>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_houseprice" type="button"data-price="<?php echo $row['price'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_houseprice" type="button">Delete</button>
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
   /* background-image: url('images/logo.jpg');  Same background image */
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    filter: blur(20px); /* Apply blur effect */
    z-index: -1; /* Ensure the blurred image stays behind the content */
    transform: scale(1.1); /* Slightly scale the image to avoid visible edges due to blur */
}

body:not(#homepage) {
   /*  position: relative; /* Ensures the pseudo-element aligns correctly */
}
	td {
		vertical-align: middle !important;
	}
	.card{
	    background-color:transparent;
	}
	label.control-label{
	    color:#fff;
	}
	
</style>

<script>
	$('#managehouseprice').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_houseprice',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else if(resp == 2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
// Edit House Price
$('.edit_houseprice').click(function() {
	var row = $(this).closest('tr');
	var id = row.find('td:first').text();
	var house_no = row.find('td:eq(1)').text();
	var price = $(this).attr('data-price');
	var month = row.find('td:eq(3)').text();
	var year = row.find('td:eq(4)').text();

	// Fill the form with existing values for editing
	$('input[name="id"]').val(id);  // Hidden ID field to know what to update
	$('select[name="house_id"]').val(house_no);
	$('input[name="price"]').val(price);
	
	// Handle month checkboxes
	$('input[name="month[]"]').each(function() {
		$(this).prop('checked', month.includes($(this).val()));
	});

	$('select[name="year"]').val(year);

	// Scroll to the form for better user experience
	$('html, body').animate({
		scrollTop: $('#managehouseprice').offset().top
	}, 'slow');
});

// Delete House Price
$('.delete_houseprice').click(function() {
	var id = $(this).closest('tr').find('td:first').text();

	if (confirm("Are you sure you want to delete this house price?")) {
		start_load();

		$.ajax({
			url: 'ajax.php?action=delete_houseprice',
			method: 'POST',
			data: {id: id},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("House price successfully deleted", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else {
					alert_toast("An error occurred while deleting", 'danger');
				}
			}
		});
	}
});


	$('table').dataTable()
</script>

</body>
