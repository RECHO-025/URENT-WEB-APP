
<?php


include 'index.php';
include 'collect_data2.php';
include 'db_connect.php';
include 'timezone.php';

$arry1 = explode("\r\n", $_POST['contact']); // Explode by new line
$real_nos = array_diff($arry1, array('')); // Filter out empty values
$msgs = count($real_nos); // Count valid numbers

// Fetch SMS credits
$result45 = "SELECT Amount, Rate FROM sms_credits WHERE schoolid ='1'";
$info45 = $conn12->query_database_many_rows($conn2, $result45);

if ($info45) {
    $credit = $info45["Amount"];
    $rate = $info45["Rate"];
    $no = ($credit / $rate);
    $amount = ($rate * $msgs) + 3500; 

    if ($no <= $msgs) {
        ?>
        <p align="center">
            Insufficient funds, In order to be able to send SMS, send Shs 
            <?php echo $amount; ?>/= to 0776027733 / 0702 014626. Reason: <?php echo $landlordid; ?>
        </p>
        <?php
    } else {
        // Select SMS route
        $result4500 = "SELECT Channel FROM smschannels";
        $info2 = $conn12->query_database_many_rows($conn2, $result4500);

        if ($info2) {
            $route = $info2["Channel"];
        } else {
            $route = 3;
        }

        // Switch case for different SMS providers
        switch ($route) {
            case 1:
                include 'sms_care.php';
                break;
            case 2:
                include 'sms_group.php';
                break;
            case 3:
                include 'sms_ego.php';
                break;
            case 4:
                include 'ugsms.php';
                break;
            case 5:
                include 'sms_pandora.php';
                break;
            default:
                echo "No SMS route selected.";
        }

        $sender = $_POST['sender_id'];

        // Fetch tenants with outstanding payments
        $tenant_query = $conn->query("
            SELECT t.id, CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS name, t.contact,
                   SUM(p.amount) AS total_balance
            FROM tenants t
            INNER JOIN payments p ON t.id = p.tenant_id
            WHERE t.shopid = '$landlordid' AND p.pay_status = 0
            GROUP BY t.id
        ");

        if ($tenant_query) {
            while ($row = $tenant_query->fetch_assoc()) {
                $tenant_name = ucwords($row['name']);
                $contact = $row['contact'];
                $current_month = date('F');
                $balance = number_format($row['total_balance'], 2); // Format balance to 2 decimal places
                $message = "Dear $tenant_name, I would like to remind you to deposit some money and submit your pay slip to the management office. Your outstanding rent is $balance as of $current_month. Thank you.";
                $sender = 'User';
                
                // Send SMS (assuming SendSMS is a defined function)
                $status = SendSMS($sender, $password1, $username1, $message_type, $message_category, $contact, $message);
                
                // Output status
                ?>
                <p align="center">NO: <?php echo $contact; ?>: Status: <?php echo $status; ?></p>
                <?php
                
                // Insert SMS log (uncomment when necessary)
                /*
                $sql_trans = "INSERT INTO sms_sent (Phone, Msg, Status, Schoolid, Dated) VALUES ('$contact', '$message', '$status', '$landlordid', '$date_2day')";
                $execute_query2 = $conn12->update_record($conn2, $sql_trans);
                */
            }
        }

        // Update SMS credits
        $amt_used = $rate * $msgs;
        $new_amt = $credit - $amt_used;
        
        $sql = "UPDATE sms_credits SET Amount = '$new_amt' WHERE schoolid = '$landlordid'";
        $execute_query = $conn12->update_record($conn2, $sql);

        // Handle date processing (uncomment if needed)
        //$date = mktime(0, 0, 0, date("d"), date("m"), date("Y"));
        //$date_2day = date("Y-m-d", $date);
    }
}
?>
</body>
</html>
