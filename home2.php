<?php 

include 'db_connect.php';

if (isset($_POST['expense_id'])) {
    include 'expense.php';
} else {
      

    ?>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 20px;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
    font-weight: 700;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 28px;
}

.dashboard {
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.dashboard-card {
    background-color: #fff;
    padding: 20px;
    border-radius: 158px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    width: calc(23% - 10px); /* Reduced width for smaller cards */
    margin: 10px 0;
    text-align: center;
    color: white;
    position: relative;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
}

.dashboard-card h2 {
    font-size: 20px;
    font-weight: 600;
    margin: 15px 0;
    letter-spacing: 1px;
}

.dashboard-card .icon {
    font-size: 40px;
    margin-bottom: 10px;
}

.dashboard-card .view-link {
    color: white;
    font-size: 14px;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 50px;
    background-color: rgba(255, 255, 255, 0.3);
    display: inline-block;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 10px;
}

.dashboard-card .view-link:hover {
    background-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
}

.card-houses, .card-tenants, .card-payments, .card-comm {
    background: linear-gradient(135deg, #0d2074, #007bff);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .dashboard-card {
        width: calc(48% - 10px); /* Slightly larger on tablets */
    }
}

@media (max-width: 600px) {
    .dashboard-card {
        width: 100%;
    }
}

.tenants-summary {
    background-color: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    position: absolute;
    top: 67px;
    right: 19px;
    width: 300px;
    height: 550px; /* Limit height to make it scrollable */
    overflow-y: auto; /* Enable vertical scrolling */
    z-index: 999;
}

.tenants-summary h2 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    text-align: center;
    color: #333;
}

.tenant-status {
    margin-bottom: 20px;
}

.tenant-status h3 {
    font-size: 16px;
    margin-bottom: 10px;
    color: #333;
    font-weight: 600;
    text-align: left;
}

  
}

.tenants-summary::-webkit-scrollbar {
    width: 8px;
}

.tenants-summary::-webkit-scrollbar-thumb {
    background-color: rgba(0, 123, 255, 0.8);
    border-radius: 5px;
}

.tenants-summary::-webkit-scrollbar-track {
    background-color: #f4f7fc;
}
</style>

<div class="container">
    <div class="row">
        <div class="card-body text-white">
            <?php echo "Hello ". $_SESSION['login_name']."!" ?>
            <?php echo $landlordid; ?>
            <hr>
        </div>
    </div>
	<?php
	
$landlord_id = $_SESSION['login_landlordid'];

$username = $_SESSION['login_username'];
	
$users_details = $conn->query("SELECT type FROM users WHERE landlord_id = '" . $conn->real_escape_string($landlord_id) . "' AND username = '" . $conn->real_escape_string($username) . "'");

$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlord_id'";

$account_type1 = $conn1-> query_database_many_rows($conn,$sql222);
 
$account_type =$account_type1["account_type"];


    $row133 = $users_details->fetch_assoc();  // Fetch user details as an associative array
    $type = $row133['type'];
	
     if($type == 1)
			     {

				 
	switch ($account_type) {
    case 1:
       ?>
	    <div class="tenants-summary">
        <h2>Tenant Summary</h2>
        <div class="tenant-status dues">
            <h3>Tenants whose rent is due</h3>
	   
	   <?php
        break;
    case 2:
          ?>
		   <div class="tenants-summary">
        <h2>Buyer Summary</h2>
        <div class="tenant-status dues">
            <h3> Buyer whose purchase is due;</h3>
		  
		  <?php
        break;
    default:
       
        break;
}			 			 
	?>
   
       <?php

$dues_tenants = [];
$wallet_balances = [];

// 1. Fetch all wallet balances and store in an array by tenant_id
$query_wallets = $conn->query("SELECT tenant_id, SUM(amount) as wallet_amount 
    FROM wallet 
    GROUP BY tenant_id");

while ($wallet = $query_wallets->fetch_assoc()) {
    $wallet_balances[$wallet['tenant_id']] = $wallet['wallet_amount'];
}

// 2. Fetch tenant dues
$query_tenants = $conn->query("SELECT t.*, 
    CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, 
    SUM(p.amount) AS total_amount
    FROM tenants t
    JOIN payments p ON t.id = p.tenant_id 
    WHERE t.shopid = '$landlordid' AND p.pay_status = 0
    GROUP BY t.id 
    ORDER BY name ASC");

$counter = 1;



if ($query_tenants->num_rows > 0) {
    while ($tenant = $query_tenants->fetch_assoc()) {
        $tenant_id = $tenant['id'];
        $total_due = (float)$tenant['total_amount'];
        $wallet_amount = isset($wallet_balances[$tenant_id]) ? (float)$wallet_balances[$tenant_id] : 0;
        $difference = $total_due - $wallet_amount;

        // Only display if due is greater than zero
        if ($total_due > 0) {
            echo "<p style='color: red; list-style-type: none;'>
                {$counter}. {$tenant['firstname']} {$tenant['lastname']} 
                [Invoice: " . number_format($total_due) . 
                " | Paid: " . number_format($wallet_amount) . 
                " | Balance: " . number_format($difference) . "]</p>";
            $counter++;
        }
    }
} else {
    echo "<p style='color:red; list-style-type: none;'>No customer with dues</p>";
}

echo "</div>";
?>
</div>
    </div>
	<?php 
	   }
	   else {
	   
	   }
	   
	  if($type == 1)
			     {
	?>
	
    <div class="dashboard">
        <div class="dashboard-card card-houses">
            <div class="icon">🏠</div>
            <h2><b>
            <?php
                $landlordid = $conn->real_escape_string($landlordid);
                $query = $conn->query("SELECT * FROM houses WHERE shopid = '$landlordid'");
                echo $query->num_rows;
            ?>
            </b></h2>
            <?php
            switch ($account_type) {
                case 1:
                    echo "<p><b>Total Houses</b></p>";
                    break;
                case 2:
                    echo "<p><b>Total Plots</b></p>";
                    break;
                default:
                    echo "<p><b>Total Units</b></p>";
                    break;
            }
            ?>
            <a href="index.php?page=houses" class="view-link">View List</a>
        </div>
        <div class="dashboard-card card-tenants">
            <div class="icon">👥</div>
            <h2><b>
            <?php 
                //echo $conn->query("SELECT tenant_id AS total_rows,house_id FROM house_renting where shopid ='$landlordid'")->num_rows;
             echo $conn->query("SELECT id AS total_rows FROM tenants where shopid ='$landlordid'")->num_rows;
            ?>
            </b></h2>
            <?php
            switch ($account_type) {
                case 1:
                    echo "<p><b>Total Tenants</b></p>";
                    break;
                case 2:
                    echo "<p><b>Total Buyers</b></p>";
                    break;
                default:
                    echo "<p><b>Total Residents</b></p>";
                    break;
            }
            ?>
            <a href="index.php?page=tenants" class="view-link">View List</a>
        </div>
        <div class="dashboard-card card-payments">
            <div class="icon">💰</div>
            <h2><b>
            <?php 
                $today = date('Y-m-d');
                $stmt1 = $conn->prepare("SELECT sum(amount) as paid FROM payments WHERE shopid = ? AND date(date_created) = ?");
                $stmt1->bind_param("is", $landlordid, $today);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $expense1 = $result1->num_rows > 0 ? $result1->fetch_array()['paid'] : 0;
                $stmt1->close();
                
                $stmt2 = $conn->prepare("SELECT sum(AMOUNT) as paid FROM expenses WHERE shopid = ? AND date(Date_of_entry) = ?");
                $stmt2->bind_param("is", $landlordid, $today);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $expense2 = $result2->num_rows > 0 ? $result2->fetch_array()['paid'] : 0;
                $stmt2->close();
                
                $total_expense1 = $expense1 + $expense2;
                echo number_format($total_expense1, 2, '.', ',');
            ?>
            </b></h2>
            <p><b>Payments Today</b></p>
            <a href="index.php?page=invoices" class="view-link">View Payments</a>
            <a href="index.php?page=expense" class="view-link">Expense Entry</a>
			<a href="index.php?page=invoicing_auto" class="view-link">Automatic Invoice</a>
        </div>
    </div>
	<<br />
<?php
   } 
   else {
   
   }

?>
    <!-- Communication Card placed below -->
    <div class="dashboard">
        <div class="dashboard-card card-comm">
            <div class="icon">💬</div>
            <h2>Communication</h2>
            <p>Check Messages</p>
            <a href="index.php?page=sendsms" class="view-link">View</a>
        </div>
    </div>
	
</div>




<?php 
} 
?>

<script>
    $('#manage-records').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp);
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success');
                    setTimeout(function(){
                        location.reload();
                    },800);
                }
            }
        });
    });

    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person();
        }
    });

    $('#check').on('click',function(e){
        get_person();
    });

    function get_person(){
        start_load();
        $.ajax({
            url:'ajax.php?action=get_pdetails',
            method:"POST",
            data:{tracking_id : $('#tracking_id').val()},
            success:function(resp){
                if(resp){
                    resp = JSON.parse(resp);
                    if(resp.status == 1){
                        $('#name').html(resp.name);
                        $('#address').html(resp.address);
                        $('[name="person_id"]').val(resp.id);
                        $('#details').show();
                        end_load();
                    }else if(resp.status == 2){
                        alert_toast("Unknown tracking id.",'danger');
                        end_load();
                    }
                }
            }
        });
    }
</script>
