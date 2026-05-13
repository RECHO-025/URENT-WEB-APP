<?php include('db_connect.php');?>
<body id="page">
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-category">
				<div class="card">
					<div class="card-header">
						    <b>Category Form</b>
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label"><b>Name</b></label>
								<input type="text" class="form-control" name="name">
							</div>
							<div class="form-group">
								<label class="control-label"><b>Price</b></label>
								<input type="text" class="form-control" name="cprice">
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md">
								<button class="btn btn-sm btn-primary space-bottom col-sm "type=" button "> Save</button>
								<button class="btn btn-sm btn-light col-sm " type="button" onclick="$('#manage-category').get(0).reset()"> Cancel</button>
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
						<b>Category List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Category</th>
									<th class="text-center">Price</th>
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
								$category = $conn->query("SELECT * FROM categories where shopid ='$landlordid' order by id asc");
								while($row=$category->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p><b><?php echo $row['name'] ?></b></p>
								</td>
										<td class="">
										<p></b> <?php echo number_format($row['cprice'], 0	) ?></b></p>
									</td>
										<?php
							
     if($type == 1)
			     {	
				?>				
									
									
									
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_category" type="button" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['name'] ?>" >Edit</button>
										<button class="btn btn-sm btn-danger delete_category" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
    /*  background-image: url('images/logo.jpg');Same background image */
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    filter: blur(8px);  /*  Apply blur effect */
    z-index: -1; /* Ensure the blurred image stays behind the content */
    transform: scale(1.1); /* Slightly scale the image to avoid visible edges due to blur */
}

body:not(#homepage) {
  /*position: relative; /* Ensures the pseudo-element aligns correctly */
}
	td{
		vertical-align: middle !important;
	}
	.card-header{
	    color:white;
	}
	label.control-label{
	    color:white;
	}
	.card{
	    background-color: transparent;
	}
</style>
<script>
	
	$('#manage-category').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_category',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_category').click(function(){
		start_load()
		var cat = $('#manage-category')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		end_load()
	})
	$('.delete_category').click(function(){
		_conf("Are you sure to delete this category?","delete_category",[$(this).attr('data-id')])
	})
	function delete_category($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_category',
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