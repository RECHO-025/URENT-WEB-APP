<?php include 'db_connect.php' ?>
<?php 

$month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m');

$landlord = $conn->query("SELECT business_name FROM landlords WHERE landlord_id = '$landlordid'");

$row33=$landlord ->fetch_assoc();

$compannyname = $row33['business_name'];

$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlordid'";

		$account_type1 =mysqli_query($conn,$sql222);

$account_type = mysqli_fetch_assoc($account_type1)["account_type"];
?>
<style>
	.on-print{
		display: none;}
		
		table {
    background-color: white;  /* Set background to white */
    opacity:1;            /* Slight transparency to match design */
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #ddd;
}

th, td {
    padding: 8px;
    text-align: left;
    border: 1px solid black;
}

th {
    background-color: #f2f2f2;  /* Different background for headers */
    color: black;
}

tr:hover {
    background-color: #f5f5f5;  /* Hover effect on rows */
}

tbody {
       /* Remove blur effect */
    color: black;               /* Text color */
}
#report {
    background-color: white; 
}
	}

</style>
<noscript>
	
</noscript>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="col-md-12">
					<form id="filter-report">
						<div class="row form-group">
							<label class="control-label col-md-2 offset-md-2 text-right">Month of: </label>
							<input type="month" name="month_of" class='from-control col-md-4' value="<?php echo ($month_of) ?>">
							<button class="btn btn-sm btn-block btn-primary col-md-2 ml-1">Filter</button>
						</div>
					</form>
					<hr>
						<div class="row">
							<div class="col-md-12 mb-2">
							<button class="btn btn-sm btn-block btn-success col-md-2 ml-1 float-right" type="button" id="print"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
					<div id="report">
						<div class="on-print">
						     <p><center><?php echo $compannyname;     ?></center></p>
							 <p><center>Rental Payments Report</center></p>
							 <p><center>for the Month of <b><?php echo date('F ,Y',strtotime($month_of.'-1')) ?></b></center></p>
						</div>
						<div class="row">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Date</th>
										<?php
										switch($account_type){
										    case 1:
										        ?>
										        <th>Tenant</th>
										        <?php
										        break;
										        case 2:
										            ?>
										            <th>Buyer</th>
										            <?php
										            break;
										            default:
										                ?>
										                <th>Buyer/Tenant</th>
										                <?php
										}
										?>
											<?php
										switch($account_type){
										    case 1:
										        ?>
										        <th>House  No</th>
										        <?php
										        break;
										        case 2:
										            ?>
										            <th>Plot No</th>
										            <?php
										            break;
										            default:
										                ?>
										                <th>House/Plot</th>
										                <?php
										}
										?>
										<th>Invoice</th>
										<th>Amount</th>
										<th>Month</th>
										<th>Year</th>
										<th>Date Created </th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									$tamount = 0;
									$payments  = $conn->query("SELECT p.id AS payment_id, 
       p.tenant_id, 
       p.amount, 
       p.InvoiceType, 
       p.Year, 
       p.Month, 
	   p.pay_status as status,
       p.date_created, 
       p.pay_status, 
       p.House_id as houseid 
FROM payments p WHERE date_format(p.date_created, '%Y-%m') = '$month_of' 
  AND p.shopid = '$landlordid' 
ORDER BY unix_timestamp(p.date_created) ASC
");
									if($payments->num_rows > 0 ):
									while($row=$payments->fetch_assoc()):
										$tamount += $row['amount'];
									?>
									<tr>
										<td><?php echo $i++ ?></td>
										<td><?php echo date('M d,Y',strtotime($row['date_created'])) ?></td>
										<td><?php
										$tenantid = $row['tenant_id'];
										
										 echo ucwords($tenantid);
										 
										 echo "  ";
										 
										 $tenant22 = $conn->query("
										 SELECT CONCAT( firstname, ' ', middlename, ' ',lastname) AS name,contact,id 
										FROM tenants WHERE shopid = '$landlordid' AND id ='$tenantid'");
										
										$row4=$tenant22->fetch_assoc();
										
										$tenantname = $row4['name'];
										
										echo $tenantname; 
										 
										 ?></td>
										
										
										
										<td><?php echo $row['houseid'] ?></td>
										<td><?php echo $row['InvoiceType'] ?></td>
										<td class="text-right"><?php 
										echo number_format($row['amount'],2)
										   ?>
										[
										   <?php
										  $status = $row['status'];
										  if($status ==1) 
										    {
											
											echo 'paid';
											
											}
											else {
											echo 'Not paid';
											
											}
										 ?>
										 ]
										 </td>
										<td class="text-right"><?php echo $row['Month'] ?></td>
										<td class="text-right"><?php echo $row['Year'] ?></td>
										<td class="text-right"><?php echo $row['date_created'] ?></td>
									</tr>
								<?php endwhile; ?>
								<?php else: ?>
									<tr>
										<th colspan="6"><center>No Data.</center></th>
									</tr>
								<?php endif; ?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan="5">Total Amount:</th>
										<th class='text-right'><?php echo number_format($tamount,2) ?></th>
									</tr>
									<tr>
										<th colspan="5">Paid: </th>
										<th class='text-right'><?php
										
									$payments45  = $conn->query("SELECT SUM(amount) FROM payments 
WHERE date_format(date_created, '%Y-%m') = '$month_of' AND shopid = '$landlordid' AND pay_status =1");

                              $row5=$payments45->fetch_assoc();
							  
							  $mount_paid = $row5['SUM(amount)'];										
										
										 echo number_format($mount_paid,2) ?></th>
									</tr>
									<tr>
										<th colspan="5">Not paid: </th>
										<th class='text-right'><?php 
										$payments46  = $conn->query("SELECT SUM(amount) FROM payments 
WHERE date_format(date_created, '%Y-%m') = '$month_of' AND shopid = '$landlordid' AND pay_status =0");

                              $row6=$payments46->fetch_assoc();
							  
							  $mount_paid6 = $row6['SUM(amount)'];										
										
										 echo number_format($mount_paid6,2)
										
										
										?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#print').click(function(){
		var _style = $('noscript').clone()
		var _content = $('#report').clone()
		var nw = window.open("","_blank","width=800,height=700");
		nw.document.write(_style.html())
		nw.document.write(_content.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
		nw.close()
		},500)
	})
	$('#filter-report').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=payment_report&'+$(this).serialize()
	})
</script>