<?php include('db_connect.php');?>

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
						<b>List of deposits</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" 
						href="javascript:void(0)" id="new_deposit">
					<i class="fa fa-plus"></i> New Entry
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Date</th>
									<th class="">Tenant</th>
									<th class="">Amount</th>
									<th class="">Paid By </th>
									<th class=""> Payment Mode  </th>
									<th class=""> Transid  </th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$deposits =$conn->query("SELECT 
    CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS full_name, 
    t.id AS tenant_id, 
    w.transaction_id, 
    w.amount, 
    w.id AS id, 
    w.paid_by, 
    w.Trans_date,
    w.Mode_pay 
FROM 
    tenants t 
INNER JOIN 
    wallet w ON t.id = w.tenant_id 
WHERE 
    t.shopid = '$landlordid' 
ORDER BY 
    w.Trans_date DESC");
									while($row=$deposits->fetch_assoc()):
								?>
								
							<tr>
									<td class="text-center"><?php echo ucwords($row['id']) ?></td>
									<td class="">
										 <p> <b><?php echo ucwords($row['Trans_date']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['full_name']) ?></b></p>
									</td>
									<td class="text-right">
										 <p> <b><?php echo number_format($row['amount'],0) ?></b></p>
									</td>
									
									<td class="text-right">
										 <p> <b><?php echo ucwords($row['paid_by']) ?></b></p>
									</td>
									
									<td class="text-right">
										 <p> <b><?php echo ucwords($row['Mode_pay']) ?></b></p>
									</td>
									
									<td class="text-right">
										 <p> <b><?php echo ucwords($row['transaction_id']) ?></b></p>
									</td>
										<td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary view_wallat" type="button" data-id="<?php echo $row['id'] ?>">View</button>
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
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_deposit').click(function(){
		uni_modal("New deposit","manage_deposit.php","mid-large")
		
	})
	
	$('.view_wallat').click(function(){
		uni_modal("view wallat","view_wallat.php?id="+$(this).attr('data-id'),"large")
		
	})
	
	$('.edit_deposit').click(function(){
		uni_modal("Manage deposit Details","manage_deposit.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.view_deposit').click(function(){
		uni_modal("Tenants deposits","view_deposit.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_deposit').click(function(){
		_conf("Are you sure to delete this deposit?","delete_deposit",[$(this).attr('data-id')])
	})
	
	/*function delete_deposit($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_deposit',
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
	}*/
</script>