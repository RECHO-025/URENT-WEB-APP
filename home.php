<?php 
include 'db_connect.php';

if (isset($_POST['expense_id'])) {
    include 'expense.php';
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        :root {
            --primary: #0d2074;
            --secondary: #007bff;
            --light: #f4f7fc;
            --dark: #333;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        .welcome-section h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .welcome-section p {
            opacity: 0.9;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 25px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card.houses {
            border-top: 5px solid var(--primary);
        }
        
        .stat-card.tenants {
            border-top: 5px solid var(--info);
        }
        
        .stat-card.payments {
            border-top: 5px solid var(--success);
        }
        
        .stat-card.communication {
            border-top: 5px solid var(--warning);
        }
        
        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .stat-label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .stat-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }
        .tenant-list-horizontal {
    width: 100%;
    overflow-x: auto;
    padding-bottom: 10px;
}

.horizontal-scroll-container {
    display: flex;
    gap: 15px;
    padding: 10px 5px;
    min-width: min-content;
}

.tenant-item-horizontal {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
    min-width: 200px;
    max-width: 250px;
    border-left: 4px solid #dc3545;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    flex-shrink: 0;
}

.tenant-item-horizontal:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.tenant-number {
    background: #dc3545;
    color: white;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    margin-bottom: 10px;
}

.tenant-name {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 10px;
    color: #333;
    line-height: 1.3;
}

.tenant-details {
    font-size: 12px;
}

.detail-item {
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-item .label {
    font-weight: 500;
    color: #666;
}

.detail-item.balance {
    font-weight: 600;
    color: #dc3545;
    padding-top: 5px;
    border-top: 1px solid #e9ecef;
    margin-top: 5px;
}

/* Scrollbar styling */
.tenant-list-horizontal::-webkit-scrollbar {
    height: 8px;
}

.tenant-list-horizontal::-webkit-scrollbar-thumb {
    background-color: rgba(13, 32, 116, 0.8);
    border-radius: 4px;
}

.tenant-list-horizontal::-webkit-scrollbar-track {
    background-color: #f4f7fc;
    border-radius: 4px;
}

.no-dues {
    text-align: center;
    padding: 30px 20px;
    color: #28a745;
    font-weight: 500;
    font-size: 16px;
    background: #f8fff9;
    border-radius: 10px;
    border: 1px dashed #28a745;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tenant-item-horizontal {
        min-width: 180px;
        padding: 12px;
    }
    
    .tenant-name {
        font-size: 13px;
    }
    
    .tenant-details {
        font-size: 11px;
    }
}
        
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <h1>Hello <?php echo $_SESSION['login_name']; ?>!</h1>
            <p>Welcome to your dashboard</p>
        </div>
        <div class="summary-card">
    <?php
    switch ($account_type) {
        case 1:
            echo "<h2>Tenant Summary</h2>";
            break;
        case 2:
            echo "<h2>Buyer Summary</h2>";
            break;
        default:
            echo "<h2>Customer Summary</h2>";
            break;
    }
    ?>
    
    <div class="tenant-list-horizontal">
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
            echo '<div class="horizontal-scroll-container">';
            while ($tenant = $query_tenants->fetch_assoc()) {
                $tenant_id = $tenant['id'];
                $total_due = (float)$tenant['total_amount'];
                $wallet_amount = isset($wallet_balances[$tenant_id]) ? (float)$wallet_balances[$tenant_id] : 0;
                $difference = $total_due - $wallet_amount;
                
                // Only display if due is greater than zero
                if ($total_due > 0) {
                    echo "<div class='tenant-item-horizontal'>";
                    echo "<div class='tenant-number'>{$counter}</div>";
                    echo "<div class='tenant-info'>";
                    echo "<div class='tenant-name'>{$tenant['firstname']} {$tenant['lastname']}</div>";
                    echo "<div class='tenant-details'>";
                    echo "<div class='detail-item'><span class='label'>Invoice:</span> " . number_format($total_due) . "</div>";
                    echo "<div class='detail-item'><span class='label'>Paid:</span> " . number_format($wallet_amount) . "</div>";
                    echo "<div class='detail-item balance'><span class='label'>Balance:</span> " . number_format($difference) . "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    $counter++;
                }
            }
            echo '</div>';
        } else {
            echo "<div class='no-dues'>No customer with dues</div>";
        }
        ?>
    </div>
</div>
        <?php
        $landlord_id = $_SESSION['login_landlordid'];
        $username = $_SESSION['login_username'];
        
        $users_details = $conn->query("SELECT type FROM users WHERE landlord_id = '" . $conn->real_escape_string($landlord_id) . "' AND username = '" . $conn->real_escape_string($username) . "'");
        
        $sql222 = "SELECT account_type FROM landlords WHERE landlord_id ='$landlord_id'";
        $account_type1 = $conn1->query_database_many_rows($conn, $sql222);
        $account_type = $account_type1["account_type"];
        
        $row133 = $users_details->fetch_assoc();
        $type = $row133['type'];
        
        if($type == 1) {
        ?>
        
        <div class="dashboard-grid">
            <div class="main-content">
                <div class="stats-grid">
                    <div class="stat-card houses">
                        <div class="stat-icon">🏠</div>
                        <div class="stat-value">
                            <?php
                            $landlordid = $conn->real_escape_string($landlordid);
                            $query = $conn->query("SELECT * FROM houses WHERE shopid = '$landlordid'");
                            echo $query->num_rows;
                            ?>
                        </div>
                        <div class="stat-label">
                            <?php
                            switch ($account_type) {
                                case 1:
                                    echo "Total Houses";
                                    break;
                                case 2:
                                    echo "Total Plots";
                                    break;
                                default:
                                    echo "Total Units";
                                    break;
                            }
                            ?>
                        </div>
                        <div class="stat-actions">
                            <a href="index.php?page=houses" class="btn btn-primary">View List</a>
                        </div>
                    </div>
                    
                    <div class="stat-card tenants">
                        <div class="stat-icon">👥</div>
                        <div class="stat-value">
                            <?php 
                            echo $conn->query("SELECT id AS total_rows FROM tenants where shopid ='$landlordid'")->num_rows;
                            ?>
                        </div>
                        <div class="stat-label">
                            <?php
                            switch ($account_type) {
                                case 1:
                                    echo "Total Tenants";
                                    break;
                                case 2:
                                    echo "Total Buyers";
                                    break;
                                default:
                                    echo "Total Residents";
                                    break;
                            }
                            ?>
                        </div>
                        <div class="stat-actions">
                            <a href="index.php?page=tenants" class="btn btn-primary">View List</a>
                        </div>
                    </div>
                    
                    <div class="stat-card payments">
                        <div class="stat-icon">💰</div>
                        <div class="stat-value">
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
                        </div>
                        <div class="stat-label">Payments Today</div>
                        <div class="stat-actions">
                            <a href="index.php?page=invoices" class="btn btn-primary">View Payments</a>
                            <a href="index.php?page=expense" class="btn btn-primary">Expense Entry</a>
                            <a href="index.php?page=invoicing_auto" class="btn btn-primary">Auto Invoice</a>
                        </div>
                    </div>
                    
                    <div class="stat-card communication">
                        <div class="stat-icon">💬</div>
                        <div class="stat-label">Communication</div>
                        <div class="stat-actions">
                            <a href="index.php?page=sendsms" class="btn btn-primary">Check Messages</a>
                        </div>
                    </div>
                </div>
            </div>

        
        </div>
        
        <?php 
        } else {
            // For users with type != 1, show only the communication card
        ?>
        <div class="stats-grid">
            <div class="stat-card communication">
                <div class="stat-icon">💬</div>
                <div class="stat-label">Communication</div>
                <div class="stat-actions">
                    <a href="index.php?page=sendsms" class="btn btn-primary">Check Messages</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

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
</body>
</html>
<?php 
} 
?>
