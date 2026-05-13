<?php

include('db_connect.php'); 


if (isset($_POST['house_no'])) {
    $house_no = $_POST['house_no'];
    $house_no = $conn->real_escape_string($house_no);

    // Get the latest (MAX ID) record for the house
    $sql = "SELECT price FROM house_data 
            WHERE house_id = '$house_no' 
            AND ID = (SELECT MAX(ID) FROM house_data WHERE house_id = '$house_no') 
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo $row['price'];
    } else {
        echo "0"; // or "Not found"
    }
}
?>