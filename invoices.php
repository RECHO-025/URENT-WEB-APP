<?php include('db_connect.php');
$landlordid = $_SESSION['login_landlordid']; 
$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlordid'";

		$account_type1 =mysqli_query($conn,$sql222);

$account_type = mysqli_fetch_assoc($account_type1)["account_type"];
?>

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
						<b>Invoice Payment</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="generateInvoice">
					<i class="fa fa-plus"></i> Generate Invoice(s)
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Site</th>
									<?php
									switch ($account_type){
									    case 1:
									        ?>
											<th class="">House No.</th>
											<th class="">House</th>
									        <th class="">Tenant</th>
											
									        <?php
									        break;
									    case 2:
									        ?>
									        <th class="">Plot No.</th>
											<th class="">Plot</th>
											<th class="">Buyer</th>
									        <?php
									        break;
									    default:
									        ?>
									        <th class="">House/Plot</th>
									        <?php
									}
									?>
									<th class="">Invoice</th>
									<th class="">Amount</th>
                                    <th class="">Month</th>
									<th class="">Year</th>
									<th class="">Date of creation</th>
									<th class="">Status</th>
									<!--<th class="">Distribution</th>-->			
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$tenant = $conn->query("SELECT id AS payment_id, 
       tenant_id as pid, 
       amount, 
       InvoiceType, 
       Year, 
       Month, 
	   House_id,
       date_created,
       pay_status 
FROM payments WHERE shopid = '$landlordid'");
								
								while($row=$tenant->fetch_assoc()):
									$amount = $row['amount'];
									$invoice = $row['InvoiceType'];
									$months = $row['Month'];
									$year = $row['Year'];
									$created_date = $row['date_created'];
									$status = $row['pay_status'];
									$houseid = $row['House_id'];
									$tenantid = $row['pid'];
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="text-center"><?php 
									  $tenant224 = $conn->query("SELECT description,category_id from houses
            WHERE shopid = '$landlordid' AND house_no ='$houseid'");
			
			 $row114 = $tenant224->fetch_assoc();
             $categoryid = $row114 ? $row114['category_id'] : null;
             
              $tenant23 = $conn->query("SELECT name from categories
            WHERE shopid = '$landlordid' AND id  ='$categoryid'");
            
             $row122 = $tenant23->fetch_assoc();
             
             ?>
            <p> <b><?php echo isset($row122['name']) ? ucwords($row122['name']) : 'N/A';
            
             ?></b>
									
									
									
									</td>
				<td class="text-center"><b><?php echo $houseid; ?></b></td>
                                    
                                    <td class="text-center">
            <p> <b><?php echo isset($row114['description']) ? ucwords($row114['description']) : 'N/A';  ?></b>
                                    
                                    
                                    </td>
									
									
									<td class=""><?php 
									  $tenant22 = $conn->query("SELECT
            CONCAT(firstname, ' ', middlename, ' ',lastname) AS name from tenants
            WHERE shopid = '$landlordid' AND id ='$tenantid'");
			
			 $row11 = $tenant22->fetch_assoc();
			 ?>
			<p> <b><?php echo ucwords($row11['name']) ?></b></p>
									
								 </td>
									<td class="">
									<b><?php  echo $invoice ;  
									echo "\n";
									
									$IDD = $conn->query("SELECT *FROM houses WHERE house_no = '$houseid' AND shopid = '$landlordid'");

											if ($IDD && $IDD->num_rows > 0) {
    											$row1 = $IDD->fetch_assoc();
    													$housenumber = $row1['house_no'];
														$category = $row1['category_id'];
														$description = $row1['description'];
														$status1 = $row1['status'];
   														 echo $housenumber;
														 ?>
														  
														  <?php 
														  
														  /*echo $category;*/
														  
														     ?>
															 <br /> 
															 <?php 
														  
														  echo $description;
														  
														     ?>
															<br/> 
															
															 <?php 
														  
														  /*echo $status1;*/
														  
														     ?>
															 
															 <?php
												} else {
    											echo "House Data not found.";
													}
										 
										 
										 ?></b>
															<br/> 
									</td>
									<td class=""><b>
									<?php echo number_format($row['amount'],0) ?></b>
									</td>
                                    <td class="">
									
									<b><?php echo $months;   ?></b>
									
									</td>
                                    <td class="">
									<b><?php echo $year;   ?></b>
									
									</td>
                                    <td class="">
								<b>	<?php echo $created_date;   ?></b>
									
									</td>
                                    <td class="">
									<b><?php
									if($status == 1)
									   {
									   echo "Paid";
									    }
										else {
										
										 echo "Not paid";
										} 
									   ?></b>
									</td>
                                  <!-- <td class="">
    <b>
    <</*?php
    if($status == 1) {
        // Get distribution details for paid invoices
        $dist_query = $conn->query("SELECT * FROM invoice_distribution WHERE payment_id = '".$row['payment_id']."'");
        if($dist_query && $dist_query->num_rows > 0) {
            $dist = $dist_query->fetch_assoc();
            echo "PM: " . number_format($dist['pm_amount'], 0) . "<br>";
            echo "Urent: " . number_format($dist['urent_amount'], 0) . "<br>"; 
            echo "Landlord: " . number_format($dist['landlord_amount'], 0);
        } else {
            echo "Distribution pending";
        }
    } else {
        echo "Not applicable";
    }
    ?>*/
    </b>
</td>-->
									<td class="text-center">
								<button class="btn btn-sm btn-outline-primary view_payment" type="button" data-id="<?php echo $row['payment_id'];?>" >Edit </button>
										<button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['payment_id']; ?>"> X </button>	
									</td>
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
<button id="export_excel">Export to Excel</button>
</div>
<style>
	
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
</style> 
<script>
 $('#export_excel').click(function() {
    window.location.href = 'invoice_excel.php';
});


	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_invoice').click(function(){
		uni_modal("New Tenant","manage_invoice.php","mid-large")
		
	})
	
	$('#generateInvoice').click(function(){
		uni_modal("Invoice Generation Process","Generate_invoice.php","mid-large")
		
	})

	$('.view_payment').click(function() {
    var id = $(this).attr('data-id');
    uni_modal("Tenants Payments", "view_payment.php?id=" + id, "large");
});

	$('.edit_tenant').click(function(){
		uni_modal("Manage Tenant Details","manage_tenant.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_tenant').click(function(){
		_conf("Erasing already insert Invoice is not possible, insteady- Edit","delete_tenant",[$(this).attr('data-id')])
	})
	
	function delete_tenant($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_tenant',
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
</script>