<style>
nav.navbar {
    z-index: 1000; /* Ensure the navbar is layered properly */
}

nav#sidebar {
    background-color: #0d2074;
    position: fixed;
    top: 0;
    bottom: 0;
    overflow-y: auto;
    width: 250px;
    max-height: 100vh; /* Set max height to viewport height */
}

/* Styling for links in the sidebar */
.collapse a {
    text-indent: 10px;
}

/* Adjust the sidebar list to ensure scrolling applies to it */
.sidebar-list {
    padding-top: 20px; /* Add some padding */
    padding-bottom: 20px;
}

/* Make sure the content will scroll if too long */
nav#sidebar::-webkit-scrollbar {
    width: 10px;
}

nav#sidebar::-webkit-scrollbar-thumb {
    background-color: #0d2074; /* Customize scrollbar color */
    border-radius: 5px;
}

nav#sidebar::-webkit-scrollbar-track {
    background-color: #0d2074; /* Match scrollbar track to sidebar */
}

/* Optional: Styling for active links */
.sidebar-list a.active {
    background-color: #0d2074; /* Highlight active item */
    color: white;
}
.sidebar-list{
	padding-top: 60px;}
	
</style>

<nav id="sidebar" class='mx-lt-5 ' >
<?php
include('db_connect.php');

$landlord_id = $_SESSION['login_landlordid'];

$username = $_SESSION['login_username'];


 $users_details = $conn->query("SELECT *FROM users WHERE landlord_id = '" . $conn->real_escape_string($landlord_id) . "' AND username = '" . $conn->real_escape_string($username) . "'");


    $row133 = $users_details->fetch_assoc();  // Fetch user details as an associative array
    $type = $row133['type'];
	$add_house = $row133['add_house'];
	$enter_tenant = $row133['enter_tenant'];
	$view_invoices = $row133['view_invoices'];
	$manage_invoices = $row133['manage_invoices'];
	$manage_tenant_wallant	 = $row133['manage_tenant_wallant'];
	$manage_utility_bills = $row133['manage_utility_bills'];
	$house_overview_report = $row133['house_overview_report'];
	$monthly_reports = $row133['monthly_reports'];
	$financial_reports = $row133['financial_reports'];
	$view_statements = $row133['view_statements'];

	$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlord_id'";

		$account_type1 =mysqli_query($conn,$sql222);

$account_type = mysqli_fetch_assoc($account_type1)["account_type"];

?>		
		<div class="sidebar-list">
				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-tachometer-alt "></i></span> Dashboard</a>
			<?php
			if($add_house == 1)
			   {
				switch($account_type) {
					case 1:
						?>
					<a href="index.php?page=categories" class="nav-item nav-categories"><span class='icon-field'><i class="fa fa-th-list "></i></span> Property Site</a>
					<a href="index.php?page=houses" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-home "></i></span> Houses</a>
					<a href="index.php?page=house_price" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-cog "></i></span> House Price</a>
					<a href="index.php?page=tenants" class="nav-item nav-tenants"><span class='icon-field'><i class="fa fa-user-friends "></i></span> Tenants</a>
				<a href="index.php?page=house_renting" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-home"></i></span> Assign Property </a>
				<a href="index.php?page=deposits" class="nav-item nav-houses"><span class='icon-field'><i class="fas fa-wallet" style='font-size:26px'></i></span>  Tenant's Wallet</a>

					<?php
						break;
					case 2:
						?>
					<a href="index.php?page=categories" class="nav-item nav-categories"><span class='icon-field'><i class="fa fa-th-list "></i></span> Land Block</a>
					<a href="index.php?page=houses" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-home "></i></span> Land Plot</a>
					<a href="index.php?page=house_price" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-cog "></i></span> Plot Price</a>
					<a href="index.php?page=tenants" class="nav-item nav-tenants"><span class='icon-field'><i class="fa fa-user-friends "></i></span> Plot Buyer</a>
				<a href="index.php?page=house_renting" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-home"></i></span> Assign Plot </a>
				<a href="index.php?page=deposits" class="nav-item nav-houses"><span class='icon-field'><i class="fas fa-wallet" style='font-size:26px'></i></span> Buyer's Wallet</a>


					<?php
						break;
					default:
					
				}
			?>	
				
<?php
			if($manage_utility_bills == 1)
			   {
			?>	
				<a href="index.php?page=utility_category" class="nav-item nav-utility"><span class='icon-field'><i class="fas fa-water "></i></span> Utilities</a>
				<?php
				  }
				  else {
				  
				  }
				   
			if($manage_tenant_wallant == 1)
			   {
			?>
				<!--<a href="index.php?page=tenants" class="nav-item nav-tenants"><span class='icon-field'><i class="fa fa-user-friends "></i></span> Tenants/Plot Buyer</a>-->
				
				<?php
				   }
				   else{
				  
				   }
				?>
					
				<!--<a href="index.php?page=house_renting" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-home"></i></span> Assign Property </a>-->
				<?php
				   }
				   else{
				  
				   }
				   
				   if($enter_tenant == 1)
			   {
				?>
				
				
				<!--<a href="index.php?page=deposits" class="nav-item nav-houses"><span class='icon-field'><i class="fas fa-wallet" style='font-size:26px'></i></span>  Wallet</a>-->
			     <?php
				       }
					   else {
					   
					   }
					   
					   
			if($manage_invoices == 1)
			   {
				 ?>
				<a href="index.php?page=invoices" class="nav-item nav-invoices"><span class='icon-field'><i class="fa fa-file-invoice "></i></span> Invoices</a>
			    <?php 
				   }
				   else{
				   
				   }
				  if($view_statements == 1)
			   {
				?>
				<a href="index.php?page=statement" class="nav-item nav-statement"><span class='icon-field'><i class="fas fa-water "></i></span>Statement</a>
				<?php
				   }
				   else{
				   
				   }
				    if($house_overview_report == 1)
			   {
				?>
				
				
				
				<a href="index.php?page=house_gui" class="nav-item nav-utility"><span class='icon-field'><i class="fas fa-desktop"></i></span> Property Overview </a>
				<?php 
				   }
				   else{
				   
				   }
				   
				   if($monthly_reports == 1)
			   {
				
				?>
				
				<a href="index.php?page=reports" class="nav-item nav-reports"><span class='icon-field'><i class="fa fa-list-alt "></i></span> Reports</a>
				  <?php
				      }
					  else {
					  
					  } 
					  
					 
					 
				  ?>
				<?php if($_SESSION['login_type'] == 1): 
				
				if($type == 1)
			         {
				
				?>
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users "></i></span> Users</a>
				  <?php 
				       }
					   else {
					   
					   
					   }
				  
				  ?>
				<!-- <a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class='icon-field'><i class="fa fa-cogs text-danger"></i></span> System Settings</a> -->
			<?php endif; ?>
		</div>

</nav>
<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
