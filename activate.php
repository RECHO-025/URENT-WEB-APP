<?php
include 'db_connect.php';
include 'admin_class.php';

// Include the database connection
if (isset($_GET['iddd'])) {
    $landlord_id = $_GET['iddd'];

    // Check if the landlord exists and is inactive
    $stmt = $conn->prepare("SELECT * FROM landlords WHERE landlord_id = ? AND status = 'inactive'");
    $stmt->bind_param("s", $landlord_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Get the current date and add two months
        $date_of_validity = date('Y-m-d', strtotime('+2 months'));

        // Landlord exists and is inactive, activate the account and update date_of_validity
        $update_stmt = $conn->prepare("UPDATE landlords SET status = 'active', date_of_validity = ? WHERE landlord_id = ?");
        $update_stmt->bind_param("ss", $date_of_validity, $landlord_id);
        
        if ($update_stmt->execute()) {
            echo "Your account has been activated. You can now log in.";
        } else {
            echo "Failed to activate your account. Please try again later.";
        }
    } else {
        echo "Invalid or already activated account.";
    }
} else {
    echo "No ID provided.";
}



?>