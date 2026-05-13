<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Automatic Invoice </title>
</head>
<body>
<?php 
include 'admin_class.php';

include 'db_connect.php';

$crud = new Action();

include 'collect_data4.php';

// Define $date_created before it's used
$date_created = date('Y-m-d H:i:s'); // This gives the current date and time in 'YYYY-MM-DD HH:MM:SS' format

$landlords = "SELECT date_of_validity, landlord_id, business_name FROM landlords";
$myquery12 = $conn1->query_database_many($conn, $landlords);

while ($nt2 = $myquery12->fetch_assoc()) {
    $validity = $nt2["date_of_validity"];
    $landlord_id = $nt2["landlord_id"];
    $business_name = $nt2["business_name"];

    # Convert the validity date and current date to DateTime objects
    $dateOfValidity = new DateTime($validity);
    $currentDate = new DateTime(); // This gives the current date and time

    // Compare the dates
    if ($dateOfValidity > $currentDate) {
        #echo "$landlord_id\n";
        #echo "$business_name\n";

        // Fetch house_id and tenant_id based on landlord_id
        $IDD = "SELECT house_id, tenant_id FROM house_renting WHERE shopid ='$landlord_id' AND Status = 1";
        $info11 = $conn1->query_database_many_rows($conn, $IDD);

        if ($info11) {
            $house_id = $info11["house_id"];
            $tenant_id = $info11["tenant_id"];

            #echo "$house_id\n";
           # echo " "; // Space
            #echo "$tenant_id\n";
        }

        // Invoice type logic
        if ($validity == 'Utility_Bills') {
            $invoice_type = "Monthly Plus Utility Bills";
            # include 'utilityBills.php';
            return 1;
        } else {
            $invoice_type = "Monthly Bills Only";

            // Get current month and year
            $currentMonth = date('F'); // Full month name (e.g., October)
            $currentYear = date('Y');  // Full year (e.g., 2024)

            // Fetch the price of renting a house
            $priceQuery = "SELECT price FROM house_data WHERE shopid ='$landlord_id' AND house_id = '$house_id' AND month = '$currentMonth' AND year = '$currentYear'";
            if ($priceResult = $conn1->query_database_many_rows($conn, $priceQuery)) {
                $amount = $priceResult['price'];
               # echo "$amount\n";
            } else {
                $amount = 0; // Default value if no price is found
                #echo "$amount\n";
            }

            // Check if a price entry for the same house, month, and year already exists
            $check_query = $crud->getDb()->query("SELECT id FROM payments WHERE tenant_id = '$tenant_id' AND month = '$currentMonth' AND year = '$currentYear' 
            AND InvoiceType = '$invoice_type' AND shopid ='$landlord_id'");
            
            if ($check_query->num_rows > 0) {
                // Update the record if it exists
                $update_query = $crud->getDb()->query("UPDATE payments SET amount = '$amount' WHERE tenant_id = '$tenant_id' AND month = '$currentMonth' AND year = '$currentYear' 
                AND InvoiceType = '$invoice_type' AND shopid ='$landlord_id' AND house_id = '$house_id'");
                
                if ($update_query) {
                    #echo "Success\n";
                    return 1; // Success
                } else {
                    #echo "Failure\n";
                    return 0; // Failure
                }
            } else {
                // Check the wallet balance
                $wallet_amount = $crud->getDb()->query("SELECT SUM(amount) AS total_amount FROM wallet WHERE tenant_id = '$tenant_id' AND shopid ='$landlord_id'");
                if ($wallet_amount && $wallet_amount->num_rows > 0) {
                    $row1 = $wallet_amount->fetch_assoc();
                    $wallet_balance = $row1['total_amount'];

                    // Get landlord and tenant details
                    $landlorddetails = $crud->getDb()->query("SELECT * FROM landlords WHERE landlord_id = '$landlord_id'");
                    $landlord_tenant = $crud->getDb()->query("SELECT email, CONCAT(firstname, ' ', middlename, ' ', lastname) AS name FROM tenants WHERE shopid = '$landlord_id' AND id ='$tenant_id'");

                    $row133 =  $landlorddetails->fetch_assoc();
                    $business_name = $row133['business_name'];
                    $contact_person = $row133['contact_person'];
                    $pemail_address = $row133['email_address'];
                    $telephone_contact = $row133['telephone_contact'];

                    $row134 = $landlord_tenant->fetch_assoc();
                    $tenant_name = $row134['name'];
                    $email = $row134['email'];

                    // If sufficient wallet balance
                    if ($wallet_balance >= $amount) {
                        // Insert a new payment record
                        $insert_query = $crud->getDb()->query("INSERT INTO payments (tenant_id, amount, InvoiceType, year, month, house_id, date_created, pay_status, shopid) 
                        VALUES ('$tenant_id', '$amount', '$invoice_type', '$currentYear', '$currentMonth', '$house_id', '$date_created', '1', '$landlord_id')");

                        // Debit wallet
                        $insert_wallet_query = $crud->getDb()->query("INSERT INTO wallet (tenant_id, transaction_id, amount, paid_by, Mode_pay, Trans_date, shopid) 
                        VALUES ('$tenant_id', '$invoice_type', '-$amount', '$currentMonth $currentYear', 'Wallet', '$date_created', '$landlord_id')");

                        if ($insert_query && $insert_wallet_query) {
                            $status = 'Paid';
                            #$crud->send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $house_id, $currentMonth, $currentYear);
                            #echo "Success\n";
                            return 1; // Success
                        } else {
                            #echo "Failure\n";
                            return 0; // Failure
                        }
                    } else {
                        $status = 'Not Paid';
                        // Insert unpaid payment record
                        $insert_query3 = $crud->getDb()->query("INSERT INTO payments (tenant_id, amount, InvoiceType, year, month, house_id, date_created, pay_status, shopid) 
                        VALUES ('$tenant_id', '$amount', '$invoice_type', '$currentYear', '$currentMonth', '$house_id', '$date_created', '0', '$landlord_id')");

                        if ($insert_query3) {
                            #$crud->send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $house_id, $currentMonth, $currentYear);
                            #echo "Success\n";
                            return 1; // Success
                        } else {
                            #echo "Failure\n";
                            return 0; // Failure
                        }
                    }
                }
            }
        }
    } else {
        #echo "Invalid\n";
    }
}






?>
</body>
</html>
