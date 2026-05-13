<?php
session_start();

include 'db_connect.php'; // Include your database connection



if (isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];
    $landlordid = $_SESSION['login_landlordid']; 

    // Query to get houses under the selected category
    $houses = $conn->query("SELECT * FROM houses WHERE shopid = '$landlordid' AND category_id = '$categoryId' ORDER BY id ASC");

    if ($houses->num_rows > 0) {
        echo '<option value="General">General</option>'; // Add General option if needed
        while ($row = $houses->fetch_assoc()) {
            echo '<option value="' . $row['house_no'] . '">' . $row['house_no'] . ' : ' . $row['description'] . '</option>';
        }
    } else {
        echo '<option selected="" value="" disabled="">No houses available for this category.</option>';
    }
} else {
    echo '<option selected="" value="" disabled="">Invalid category ID.</option>';
}
?>
