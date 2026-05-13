<head>
    <meta http-equiv="refresh" content="3">
</head>
<?php
// Database connection
include('admin_class.php');

$crud = new Action();

include('db_connect.php');

// Get current year and month
$currentMonth = date('F'); // Full name of the month, e.g., "November"
$currentYear = date('Y');  // Four-digit year, e.g., "2024"

$date_created = date('Y-m-d H:i:s'); // Set date_created to the current date and time

// Array to store landlord IDs
$landlords = [];

// Step 1: Select landlord_id from landlords table
$sqlLandlords = "SELECT landlord_id FROM landlords";
$resultLandlords = $conn->query($sqlLandlords);

if ($resultLandlords && $resultLandlords->num_rows > 0) {
    while ($row = $resultLandlords->fetch_assoc()) {
        $landlords[] = $row['landlord_id'];
    }
} else {
    echo "No landlords found.";
}
print_r($landlords);
// Array to store payment data
$tenant_id = [];

// Step 2: For each landlord, select tenant_id from house_renting table
foreach ($landlords as $landlordid) {
    $sqlTenants = "SELECT tenant_id FROM house_renting WHERE Status = 1 AND Shopid = '$landlordid'";
    $resultTenants = $conn->query($sqlTenants);

    if ($resultTenants && $resultTenants->num_rows > 0) {
        while ($tenant = $resultTenants->fetch_assoc()) {
            $tenant_id[] = $tenant['tenant_id'];
        }
    } else {
        echo "No Tenant found.";
    }
}
#print_r($tenant_id);

function getActiveTenantsByLandlord($conn, $landlordid) {
    $tenant_ids = [];

    // Prepare the SQL query
    $sqlTenants = "SELECT tenant_id FROM house_renting WHERE Status = 1 AND Shopid = '$landlordid'";
    $resultTenants = $conn->query($sqlTenants);

    // Check if tenants were found for this landlord
    if ($resultTenants && $resultTenants->num_rows > 0) {
        while ($tenant = $resultTenants->fetch_assoc()) {
            $tenant_ids[] = $tenant['tenant_id'];
        }
    } else {
        echo "No Tenant found for landlord ID: $landlordid\n";
    }

    return $tenant_ids;
}

foreach ($landlords as $landlordid) {
    $tenant_ids = getActiveTenantsByLandlord($conn, $landlordid);

    for ($i = 0; $i < count($tenant_ids); $i++) {
        $tenant_id_active = $tenant_ids[$i];

        // Fetch house_id based on tenant_id
        $IDD = $conn->query("SELECT house_id FROM house_renting WHERE shopid ='$landlordid' AND tenant_id = '$tenant_id_active' AND Status = 1");

        if ($IDD && $row55 = $IDD->fetch_assoc()) {
            $houseid = $row55['house_id'];

            // Getting price of renting a house using the house id, month, and year
            $priceQuery = $conn->query("SELECT price FROM house_data WHERE shopid ='$landlordid' AND house_id = '$houseid' AND month = '$currentMonth' AND year = '$currentYear'");

            if ($priceQuery && $row1 = $priceQuery->fetch_assoc()) {
                $amount = $row1['price'];

                // Step 3: For each tenant, select payments based on current year, month, and invoice type
                $sqlPayments = "SELECT * FROM payments WHERE tenant_id = '$tenant_id_active' AND Year = '$currentYear' 
                AND Month = '$currentMonth' AND InvoiceType = 'Monthly_Rent' AND shopid ='$landlordid' AND House_id ='$houseid'";

                $resultPayments = $conn->query($sqlPayments);

                if ($resultPayments && $resultPayments->num_rows > 0) {
                    echo "Invoice already exists for tenant ID $tenant_id_active with landlord ID $landlordid.";

                    // Update the payment amount if the invoice exists
                    $conn->query("UPDATE payments SET amount = '$amount' WHERE tenant_id = '$tenant_id_active' AND month = '$currentMonth' AND year = '$currentYear' 
                    AND InvoiceType = 'Monthly_Rent' AND shopid ='$landlordid' AND House_id ='$houseid'");
                } else {
                    echo "New Invoice added for tenant ID $tenant_id_active, landlord ID:$landlordid, month $currentMonth, year $currentYear.";

                    // Check if money exists in the customer's wallet
                    $wallet_amount = $conn->query("SELECT SUM(amount) AS total_amount FROM wallet WHERE tenant_id = '$tenant_id_active' AND shopid ='$landlordid'");

                    if ($wallet_amount && $row1 = $wallet_amount->fetch_assoc()) {
                        $wallet_balance = $row1['total_amount'] ?? 0;

                        // Retrieve landlord and tenant details
                        $landlorddetails = $conn->query("SELECT * FROM landlords WHERE landlord_id = '$landlordid'");
                        $landlord_tenant = $conn->query("SELECT email, CONCAT(firstname, ' ', middlename, ' ', lastname) AS name FROM tenants WHERE shopid = '$landlordid' AND id = '$tenant_id_active'");

                        if ($landlorddetails && $landlord_tenant && $row133 = $landlorddetails->fetch_assoc() && $row134 = $landlord_tenant->fetch_assoc()) {
                            $business_name = $row133['business_name'];
                            $contact_person = $row133['contact_person'];
                            $pemail_address = $row133['email_address'];
                            $telephone_contact = $row133['telephone_contact'];
                            $tenant_name = $row134['name'];
                            $email = $row134['email'];

                            if ($wallet_balance >= $amount) {
                                // Insert a new payment record and debit wallet
                                $insert_query = $conn->query("INSERT INTO payments (tenant_id, amount, InvoiceType, Year, Month, House_id, date_created, pay_status, shopid) 
                                VALUES ('$tenant_id_active', '$amount', 'Monthly_Rent', '$currentYear', '$currentMonth','$houseid','$date_created', '1','$landlordid')");

                                $insert_wallet_query = $conn->query("INSERT INTO wallet (tenant_id, transaction_id, amount, paid_by, Mode_pay, Trans_date, shopid) 
                                VALUES ('$tenant_id_active', 'Monthly_Rent $currentMonth $currentYear', '-$amount', '$currentYear', 'Wallet for $houseid $currentMonth $currentYear ','$date_created','$landlordid')");

                                if ($insert_query && $insert_wallet_query) {
                                    $status = 'Paid';
                                     $crud->send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $houseid, $currentMonth, $currentYear);
                                    return 1;
                                } else {
                                    return 0;
                                }
                            } else {
                                // Wallet has insufficient funds
                                $status = 'Not Paid';

                                $insert_query3 = $conn->query("INSERT INTO payments (tenant_id, amount, InvoiceType, Year, Month, House_id, date_created, pay_status, shopid) 
                                VALUES ('$tenant_id_active', '$amount', 'Monthly_Rent', '$currentYear', '$currentMonth','$houseid', '$date_created', '0','$landlordid')");

                                if ($insert_query3) {
                                     $crud->send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $houseid, $currentMonth, $currentYear);
                                    return 1;
                                } else {
                                    return 0;
                                }
                            }
                        } else {
                            echo "Error fetching landlord or tenant details.";
                        }
                    } else {
                        echo "Insufficient wallet balance.";
                    }
                }
            } else {
                echo "No price found for house ID: $houseid for the current month and year.";
            }
        } else {
            echo "No house ID found for landlord ID: $landlordid and tenant ID: $tenant_id_active.";
        }
    }
}

$insert_query322 = $conn->query("DELETE FROM payments WHERE amount = 0");

                        if ($insert_query322) {
                            #$crud->send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $house_id, $currentMonth, $currentYear);
                            #echo "Success\n";
                        } else {
                            #echo "Failure\n";
                           
                        }
?>
