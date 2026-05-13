<?php include('db_connect.php'); 

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM wallet WHERE shopid = '$landlordid' AND id=".$_GET['id']);
    
}

$landlordid = $_SESSION['login_landlordid'];

if(isset( $_GET['house_id'])) 
  {
$house_id = $_GET['house_id'];
$tenant_id = $_GET['tenant_id'];
$house_id = $_GET['house_id'];
$house_price = $_GET['house_price'];

}
else {


}
$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlord_id'";

$account_type1 = $conn1-> query_database_many_rows($conn,$sql222);
 
$account_type =$account_type1["account_type"];

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#house_id_select').change(function() {
        var house_no = $(this).val();
        if (house_no != "") {
            $.ajax({
                url: 'get_housepx.php',
                type: 'POST',
                data: { house_no: house_no },
                success: function(response) {
                    $('#house_price').val(response);
                },
                error: function() {
                    $('#house_price').val('Error fetching price');
                }
            });
        } else {
            $('#house_price').val('');
        }
    });
});
</script>

           <body id="page">
<!-- ?? Search Invoice -->
<div class="search-form" align="center">
    <form method="GET" action="">
        <input type="text" name="search_invoice" placeholder="Enter Invoice Number" required />
		 <input type="hidden" name="page" value="house_renting">
        <button type="submit">Search Invoice</button>
    </form>
</div>

				<form action="ajax.php" id="">
					<div class="card">
						<div class="card-header">
							  <?php
                            switch($account_type) {
                                case 1:
                                    echo "House Renting || Assign A tenant a house";
                                    break;
                                case 2:
                                    echo "Plot buying|| Assign a buyer a plot";
                                    break;
                                default:
                                    echo "Manage Properties || Assign property";
                            }
                            ?>
						</div>
						<div class="card-body">
							<input type="hidden" name="action" value="houserenting">
							
							<div class="row">
								<!-- House Dropdown -->
								<div class="form-group col-md-6">
								    <?php
									 switch($account_type) {
                                        case 1:
                                            echo "<label class='control-label'>House</label>";
                                            break;
                                        case 2:
                                            echo "<label class='control-label'>Plot</label>";
                                            break;
                                        default:
                                            echo "<label class='control-label'>Property</label>";
                                    }
									?>
									<select name="house_id" class="form-control" id="house_id_select"> 
    <option value=""></option>
    <?php 
    $houses = $conn->query("SELECT id, house_no FROM houses WHERE shopid = '$landlordid' AND status = 0 ORDER BY house_no ASC");
    while($row = $houses->fetch_assoc()):
    ?>
       Item Number: <option value="<?php echo $row['house_no'] ?>"><?php echo $row['house_no'] ?></option>
    <?php endwhile; ?>
</select>

<!-- Field to display price -->
Price : <input type="text" name="house_price" id="house_price" class="form-control mt-2" placeholder="" >

								</div>
								<!-- Price Input -->
								<div class="form-group col-md-6">
									 <?php
                                    switch($account_type) {
                                        case 1:
                                            echo "<label class='control-label'>Tenant</label>";
                                            break;
                                        case 2:
                                            echo "<label class='control-label'>Buyer</label>";
                                            break;
                                        default:
                                            echo "<label class='control-label'></label>";
                                    }
									?>
									<select name="tenant_id" class="form-control">
										<option value=""></option>
										<?php 
											$tenants = $conn->query("SELECT 
    tenants.id, 
    CONCAT(tenants.firstname, ' ', tenants.middlename, ' ', tenants.lastname) AS name 
FROM 
    tenants
WHERE 
    tenants.shopid = '$landlordid'");
											while($row1 = $tenants->fetch_assoc()):
										?>
											<option value="<?php echo $row1['id'] ?>"><?php echo $row1['name'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
							</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm space-bottom" type="submit"> Save</button>
									<button class="btn btn-sm btn-light col-sm" type="reset"> Cancel</button> 
								</div>
							</div>
						</div>
					</div>
				</form>
				<?php
		if(isset( $_GET['house_id'])) 
  {		
				
		include 'item_invoice.php';
		
  }
  else {
  
  }

		if(isset( $_GET['search_invoice'])) 
  {		
				
		include 'item_invoice.php';
		
  }
  else {
  
  }	
	
	
	
				
				?>
<style>
body:not(#homepage)::before{
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
   /* background-image: url('images/logo.jpg'); /* Same background image */
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
	td {
		vertical-align: middle !important;
	}
	.card{
	    background-color:transparent;
	}
	label.control-label{
	    color:white;
	}
</style>

<script>
$('#houserenting').submit(function(e){
    e.preventDefault();
    start_load();

    $.ajax({
        url: 'ajax.php?action=houserenting',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp){
            if(resp == 1){
                alert_toast("Data successfully added", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else if(resp == 2){
                alert_toast("Data successfully updated", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                alert_toast("Failed to process the request", 'danger');
                end_load();
            }
        },
        error: function(){
            alert_toast("An error occurred", 'danger');
            end_load();
        }
    });
});

$('.edit_houseprice').click(function(){
    start_load();
    var form = $('#manage-houseprice');
    form.get(0).reset();
    form.find("[name='price']").val($(this).attr('data-price'));
    end_load();
});

$('.delete_houseprice').click(function(){
    _conf("Are you sure to delete this record?", "delete_houseprice", [$(this).attr('data-id')]);
});

function delete_houseprice(id){
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_houseprice',
        method: 'POST',
        data: { id: id },
        success: function(resp){
            if(resp == 1){
                alert_toast("Data successfully deleted", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            }
        },
        error: function(){
            alert_toast("Failed to delete data", 'danger');
            end_load();
        }
    });
}

$('table').dataTable();
</script>

</body>