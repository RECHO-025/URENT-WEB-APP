<?php

    // Initialize an array to store the selected items and their amounts
$selectedItems = [];

// Initialize a variable to store the total amount
$totalAmount = 0;

// Check if any items were selected
if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {

    // Loop through each selected item
    foreach ($_POST['selected_items'] as $itemName) {
        // Ensure that the item has an associated amount in the 'amounts' array
        if (isset($_POST['amounts'][$itemName])) {
            // Sanitize the item name and amount
            $safeItemName = htmlspecialchars($itemName, ENT_QUOTES, 'UTF-8');
            $safeAmount = htmlspecialchars($_POST['amounts'][$itemName], ENT_QUOTES, 'UTF-8');

            // Convert the sanitized amount to a float for summation
            $safeAmountFloat = floatval($safeAmount);

            // Add the item and amount to the selectedItems array
            $selectedItems[] = [
                'name' => $safeItemName,
                'amount' => $safeAmountFloat
            ];

            // Add the amount to the total amount
            $totalAmount += $safeAmountFloat;
        }
    }
}
$totalAmount_utilitybills = number_format($totalAmount, 2);

foreach ($selectedItems as $item) {
    $name2 = $item['name'];
    $amount2 = $item['amount'];

    // Check if money exists in the customer's wallet
    $wallet_amount2 = $this->db->query("SELECT SUM(amount) AS total_amount FROM wallet WHERE tenant_id = '$safe_tenant_id' AND shopid ='$safe_landlordid'");

    $row12 = $wallet_amount2->fetch_assoc();
    $wallet_balance2 = $row12['total_amount'];

    if ($wallet_balance2 >= $amount2) {
        // Insert a new payment record
        $insert_query1 = $this->db->query("INSERT INTO wallet (tenant_id, transaction_id, amount, paid_by, Mode_pay, Trans_date, shopid) 
VALUES ('$safe_tenant_id', '$safe_invoice_type', '-$amount2', '$name2 $safe_month $safe_year', 'Wallet for $houseid','$date_created', '$safe_landlordid')");

        $insert_query = $this->db->query("INSERT INTO payments (tenant_id, amount,InvoiceType, Year, Month,House_id, date_created, pay_status, shopid) 
VALUES ('$safe_tenant_id', '$amount2', '$safe_invoice_type', '$safe_year', '$safe_month','$houseid', '$date_created', '1','$safe_landlordid')");

    } else {

        $insert_query3 = $this->db->query("INSERT INTO payments (tenant_id, amount,InvoiceType, Year, Month,House_id, date_created, pay_status, shopid) 
VALUES ('$safe_tenant_id', '$amount2', '$safe_invoice_type', '$safe_year', '$safe_month','$houseid', '$date_created', '0','$safe_landlordid')");

    }
}

?>





