<?php
include('db_connect.php');

// Check if the tenant ID is passed
if (isset($_GET['id'])) {
    $tenant_id = $_GET['id'];

    // Fetch tenant details
    $tenant_details_query = $conn->query("SELECT 
                                            CONCAT(firstname, ' ', IFNULL(middlename, ''), ' ', lastname) AS name,
                                            email,
                                            contact
                                          FROM tenants 
                                          WHERE id = '$tenant_id'");

    $tenant = $tenant_details_query->fetch_assoc();

    // Fetch tenant's financial transactions
    $financial_statement_query = $conn->query("SELECT 
                                                w.id,
                                                w.amount,
                                                w.paid_by,
                                                w.Mode_pay,
                                                w.date_created,
                                                (SELECT SUM(w2.amount) 
                                                 FROM wallet w2 
                                                 WHERE w2.tenant_id = w.tenant_id 
                                                   AND w2.id <= w.id) AS cumulative_amount
                                              FROM wallet w
                                              WHERE w.tenant_id = '$tenant_id'
                                              ORDER BY w.id ASC");

    if ($tenant_details_query->num_rows > 0 && $financial_statement_query->num_rows > 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Statement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .tenant-info {
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row tenant-info">
        <div class="col-md-12">
            <h3>Tenant Financial Statement</h3>
            <p><strong>Tenant Name:</strong> <?php echo ucwords($tenant['name']); ?></p>
            <p><strong>Email:</strong> <?php echo $tenant['email']; ?></p>
            <p><strong>Contact:</strong> <?php echo $tenant['contact']; ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Paid By</th>
                        <th>Description</th>
                        <th>Cumulative Balance</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    while ($row = $financial_statement_query->fetch_assoc()) { 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><?php echo number_format($row['amount'], 2); ?></td>
                        <td><?php echo $row['Mode_pay']; ?></td>
                        <td><?php echo $row['paid_by']; ?></td>
                        <td><?php echo $row['amount'] < 0 ? 'Debit' : 'Credit'; ?></td>
                        <td><?php echo number_format($row['cumulative_amount'], 2); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($row['date_created'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    } else {
        echo "<p>Invalid tenant or no financial data found.</p>";
    }
} else {
    echo "<p>Invalid request. Tenant ID is missing.</p>";
}
?>
