<style>
	.logo {
    margin: auto;
    font-size: 20px;
    background: white;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #0d2074;
}
.navbar{background-color: #0d2074;}

</style>
<?php

include('db_connect.php');

$landlord_id = $_SESSION['login_landlordid'];

$username = $_SESSION['login_username'];


 $users_details = $conn->query("SELECT type FROM users WHERE landlord_id = '" . $conn->real_escape_string($landlord_id) . "' AND username = '" . $conn->real_escape_string($username) . "'");


    $row133 = $users_details->fetch_assoc();  // Fetch user details as an associative array
    $type = $row133['type'];

?>
<nav class="navbar navbar-light fixed-top" style="padding:0;min-height: 3.5rem">
  <div class="container-fluid mt-2 mb-2">
  	<div class="col-lg-12">
  		<div class="col-md-1 float-left" style="display: flex;">
  		
  		</div>
      <div class="col-md-4 float-left text-white">
        <large><b><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></b></large>
      </div>
	  	<div class="float-right">
        <div class=" dropdown mr-4">
            <a href="#" class="text-white dropdown-toggle"  id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['login_name'] ?> </a>
              <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
			  <?php 
			  if($type == 1)
			     {
			  ?>
			  
                <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
				<?php
				  }
				  else {
				  
				  
				  }
				
				?>
                <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
              </div>
        </div>
      </div>
  </div>
  
</nav>

<script>
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo '$landlord_id'; ?>&mtype=own")
  })
</script>