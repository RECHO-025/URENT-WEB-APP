<?php
include('db_connect.php');

session_start();

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=tenant_statement.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Output the column headers
echo "Acc.Name\tDate\tMode of Payment\tPaid by\tAmount\tDescription\tCumulative Balance\n";

$landlordid = $_SESSION['login_landlordid'];
$i = 1;
$query = "SELECT w.id,
            CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS name,
            w.amount, w.Trans_date,
            (SELECT SUM(w2.amount) 
            FROM wallet w2 
            WHERE w2.tenant_id = w.tenant_id 
            AND w2.id <= w.id) AS cumulative_amount,
            w.paid_by,
            w.Mode_pay 
            FROM wallet w 
            JOIN tenants t ON w.tenant_id = t.id 
            WHERE t.shopid = '$landlordid' 
            ORDER BY w.tenant_id, w.id";

$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Format and echo each row as a tab-separated line
        echo ucwords($row['name']) . "\t";
        echo $row['Trans_date'] . "\t";
        echo $row['Mode_pay'] . "\t";
        echo $row['paid_by'] . "\t";
        echo number_format($row['amount'], 2) . "\t";
        echo ($row['amount'] < 0 ? "Debit" : "Credit") . "\t";
        echo number_format($row['cumulative_amount'], 2) . "\n";
    }
} else {
    echo "Error fetching data: " . $conn->error;
}

$conn->close();
?>
