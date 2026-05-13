<?php
include 'db_connect.php'; // your DB connection

require 'collect_data.php';

$sql = "SELECT house_no, description, status FROM houses WHERE status = 1";
$result = mysqli_query($conn, $sql);

$houses = array();

while ($row = mysqli_fetch_assoc($result)) {
    $houses[] = array(
        "title" => $row['house_no'],
        "price" => "N/A", // you can add price column later
        "location" => $row['description']
    );
}

echo json_encode($houses);
?>