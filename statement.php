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
                        <?php
                        switch ($account_type) {
                            case 1:
                                echo '<b>Tenant statement</b>';
                                break;
                            case 2:
                                echo '<b>Buyer statement</b>';
                                break;
                            default:
                                echo '<b>Tenants</b>';
                        }
                        ?>
					</div>
					<div class="card-body">
						<!-- Table -->
<table class="table table-condensed table-bordered table-hover" id="tenant-report">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Acc.Name</th>
            <th>Date</th>
			<th>Mode of Payment </th>
			<th> Paid for </th>
            <th>Amount</th>
            <th>Description</th>
            <th>Cumulative balance</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $tenant = $conn->query("SELECT w.id,
            CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS name,
            w.amount,w.Trans_date,
            (SELECT SUM(w2.amount) 
            FROM wallet w2 
            WHERE w2.tenant_id = w.tenant_id 
            AND w2.id <= w.id) AS cumulative_amount,
            w.paid_by,
            w.Mode_pay 
            FROM wallet w 
            JOIN tenants t ON w.tenant_id = t.id 
            WHERE t.shopid = '$landlordid' 
            ORDER BY w.tenant_id, w.id");

        if ($tenant) {
            while ($row = $tenant->fetch_assoc()) {
        ?>
        <tr>
            <td class="text-center"><b><?php echo $i++ ?></b></td>
            <td><b><?php echo ucwords($row['name']) ?></b></td>
			 <td><b><?php echo $row['Trans_date'] ?></b></td>
            <td><b><?php echo $row['Mode_pay'] ?></b></td>
			 <td><b><?php echo $row['paid_by'] ?></b></td>
            <td><b><?php echo number_format($row['amount'], 0) ?></b></td>
            <td><b><?php echo ($row['amount'] < 0) ? 'Debit' : 'Credit' ?></b></td>
            <td class="text-left"><b><?php echo number_format($row['cumulative_amount'], 2) ?></b></td>
        </tr>
        <?php
            }
        } else {
            echo "Error: " . $conn->error;
        }
        ?>
    </tbody>
</table>
					</div>
				</div> 
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<!-- Print Button -->
<button id="print">Print Report</button>

<button id="export_excel">Export to Excel</button>



<!-- JavaScript -->
<script>
$('#export_excel').click(function() {
    window.location.href = 'statement_excel.php';
});

$('#print').click(function(){
    var _style = '<style>' +
                 'table { width: 100%; border-collapse: collapse; }' +
                 'table, th, td { border: 1px solid black; }' +
                 'th, td { padding: 8px; text-align: left; }' +
                 '</style>';
    var _content = $('#tenant-report').clone();
    var nw = window.open("", "_blank", "width=800,height=700");

    // Add the styles and the content to the new window
    nw.document.write(_style);
    nw.document.write('<h2>Tenant Statement</h2>'); // Optional title
    nw.document.write(_content.prop('outerHTML'));
    nw.document.close();

    // Print and close after a short delay
    nw.print();
    setTimeout(function(){
        nw.close();
    }, 500);
});
</script>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height:150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_tenant').click(function(){
		uni_modal("New Tenant","manage_tenant.php","mid-large")
		
	})

	$('.view_wallat').click(function(){
		uni_modal("view wallat","view_wallat.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.edit_tenant').click(function(){
		uni_modal("Manage Tenant Details","manage_tenant.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_tenant').click(function(){
		_conf("Are you sure to delete this Tenant?","delete_tenant",[$(this).attr('data-id')])
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
  // Function to export statement (PDF or Excel) using AJAX
function exportStatement(format, tenant_id) {
    $.ajax({
        url: 'export_statement.php',
        type: 'POST',
        data: { format: format, tenant_id: tenant_id },
        success: function(response) {
            if (response.status == 'success') {
                if (format == 'pdf') {
                    // Open PDF in new tab
                    window.open(response.file_url);
                } else if (format == 'excel') {
                    // Redirect to Excel file download
                    window.location.href = response.file_url;
                }
            } else {
                alert('Failed to export statement.');
            }
        }
    });
}
</script>