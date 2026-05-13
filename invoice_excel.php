<?php
include('db_connect.php');

session_start();

$landlordid = $_SESSION['login_landlordid'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=invoice_payments.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr>
        <th>#</th>
        <th>Site</th>
		<th>House id </th>
        <th>House</th>
        <th>Tenant</th>
        <th>Invoice</th>
        <th>Amount</th>
        <th>Month</th>
        <th>Year</th>
        <th>Date of creation</th>
        <th>Status</th>
      </tr>";

$i = 1;
$tenant = $conn->query("SELECT p.id AS payment_id, 
       p.tenant_id AS pid, 
       p.amount, 
       p.InvoiceType, 
       p.Year, 
       p.Month, 
       p.House_id as houseid,
       p.date_created,
       p.pay_status,
       CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS tenant_name,
       h.description AS house_description,
       c.name AS category_name
FROM payments p
JOIN tenants t ON p.tenant_id = t.id
JOIN houses h ON p.House_id = h.house_no
JOIN categories c ON h.category_id = c.id
WHERE p.shopid = '$landlordid'");

while ($row = $tenant->fetch_assoc()) {
    $status = $row['pay_status'] == 1 ? 'Paid' : 'Not paid';

    echo "<tr>
            <td>" . $i++ . "</td>
            <td>" . htmlspecialchars($row['category_name']) . "</td>
			 <td>" . htmlspecialchars($row['houseid']) . "</td>
            <td>" . htmlspecialchars($row['house_description']) . "</td>
            <td>" . htmlspecialchars($row['tenant_name']) . "</td>
            <td>" . htmlspecialchars($row['InvoiceType']) . "</td>
            <td>" . number_format($row['amount'], 2) . "</td>
            <td>" . htmlspecialchars($row['Month']) . "</td>
            <td>" . htmlspecialchars($row['Year']) . "</td>
            <td>" . htmlspecialchars($row['date_created']) . "</td>
            <td>" . $status . "</td>
          </tr>";
}

echo "</table>";
$conn->close();
?>
