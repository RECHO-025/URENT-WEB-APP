<?php
include("db_connect.php");

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$query = "SELECT landlords.landlord_id, 
                 landlords.telephone_contact, 
                 landlords.contact_person, 
                 landlords.email_address, 
                 houses.description, 
                 houses.unit_img AS houseImage 
          FROM landlords
          JOIN houses ON landlords.landlord_id = houses.shopid
          WHERE houses.status = 0";

if (!empty($search)) {
    $query .= " AND (houses.description LIKE '%$search%' 
                    OR landlords.telephone_contact LIKE '%$search%' 
                    OR landlords.contact_person LIKE '%$search%')";
}

$query .= " ORDER BY houses.id DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get account_type for this property
        $landlordId = $row['landlord_id'];
        $accountResult = $conn->query("SELECT account_type FROM landlords WHERE landlord_id = '$landlordId'");
        $accountData = $accountResult->fetch_assoc();
        $account_type = $accountData['account_type'] ?? 0;
        
        // Set default image based on account_type
        switch ($account_type) {
            case 1: $defaultImage = 'uploads/house_images/BH001.jpeg'; break;
            case 2: $defaultImage = 'uploads/house_images/land.jpeg'; break;
            default: $defaultImage = 'uploads/house_images/default.jpeg'; break;
        }
        
        $imagePath = (!empty($row['houseImage']) && file_exists("uploads/house_images/" . $row['houseImage']))
            ? "uploads/house_images/" . $row['houseImage']
            : $defaultImage;
?>
    <div class="property-card">
        <div class="image-container">
            <img src="<?= $imagePath ?>" alt="House Image" style="max-width: 300px; height: auto;">
        </div>
        <div class="property-description">
            <h3><?= htmlspecialchars($row['description']) ?></h3>
            <div class="contact-details">
                <p><strong>Contact:</strong> <?= htmlspecialchars($row['telephone_contact']) ?></p>
                <p><strong>Contact Person:</strong> <?= htmlspecialchars($row['contact_person']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($row['email_address']) ?></p>
            </div>
        </div>
    </div>
<?php
    }
} else {
    echo '<p>No results found.</p>';
}

// Close the connection
$conn->close();
?>