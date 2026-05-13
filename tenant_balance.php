<?php
// Database configuration
$host = 'localhost';
$dbname = 'eljotellug_house_rental2'; // Adjust based on your database name
$username = 'root';
$password = '';



if (isset($_POST['from8']) && isset($_POST['to8'])) {

    // Capture input from the entry form
    $tenant_id = $_POST['tenant_id'];
    $from = $_POST['from8']; // dd-mm-yy
    $to = $_POST['to8'];     // dd-mm-yy

    // ✅ Convert dd-mm-yy → yyyy-mm-dd (MySQL-compatible)
    $from_date = date('Y-m-d', strtotime(str_replace('-', '/', $from)));
    $to_date = date('Y-m-d', strtotime(str_replace('-', '/', $to)));

    // Optional: Get today’s date for reference
    $date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $date_2day = date("Y-m-d", $date);

    // ✅ WALLET QUERY
    $wallet_sql = "SELECT SUM(amount) AS tamount 
                   FROM wallet 
                   WHERE tenant_id = '$tenant_id' 
                   AND Trans_date BETWEEN '$from_date' AND '$to_date' 
                   AND amount > 0";

    $wallet_result = mysqli_query($conn, $wallet_sql);
    $wallet_row = mysqli_fetch_assoc($wallet_result);
    $wallet_total = isset($wallet_row['tamount']) ? $wallet_row['tamount'] : 0;

    // ✅ PAYMENTS QUERY
    $payment_sql = "SELECT SUM(amount) AS tamount 
                    FROM payments 
                    WHERE tenant_id = '$tenant_id' 
                    AND date_created BETWEEN '$from_date' AND '$to_date' 
                    AND amount > 0";

    $payment_result = mysqli_query($conn, $payment_sql);
    $payment_row = mysqli_fetch_assoc($payment_result);
    $payment_total = isset($payment_row['tamount']) ? $payment_row['tamount'] : 0;

    // ✅ CALCULATE DIFFERENCE (Debt)
    $difference = $wallet_total - $payment_total;

    // ✅ OUTPUT RESULTS
    echo "<div style='font-family: Arial; margin: 20px;'>";
    echo "<h2 style='color:#003366;'>Tenant Profit / Loss Report</h2>";
    echo "<p><strong>Tenant ID:</strong> $tenant_id</p>";
    echo "<p><strong>Reporting Period:</strong> $from → $to</p>";
    echo "<hr>";
    echo "<p><strong>Wallet Total:</strong> " . number_format($wallet_total, 0, '.', ',') . "</p>";
    echo "<p><strong>Payments Total:</strong> " . number_format($payment_total, 0, '.', ',') . "</p>";
    echo "<p><strong>Difference (Debt):</strong> " . number_format($difference, 0, '.', ',') . "</p>";
    echo "</div>";

    // ✅ OPTIONAL DEBUG SECTION (can uncomment for testing)
    /*
    echo "<pre style='background:#f4f4f4; padding:10px;'>";
    echo "DEBUG INFO:\n";
    echo "From (input): $from\n";
    echo "To (input): $to\n";
    echo "From (SQL format): $from_date\n";
    echo "To (SQL format): $to_date\n";
    echo "Wallet SQL: $wallet_sql\n";
    echo "Payments SQL: $payment_sql\n";
    echo "Wallet Total: $wallet_total\n";
    echo "Payment Total: $payment_total\n";
    echo "Difference: $difference\n";
    echo "</pre>";
    */
}
?>
