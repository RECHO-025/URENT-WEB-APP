<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db_connect.php';

// Determine the base URL
$base_url = basename($_SERVER['PHP_SELF']);
?>

<style>
/* Overall container styling */
.houses-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Increased space between columns */
    padding: 20px; /* Padding around the container */
}
.house-item {
    flex: 1 1 calc(25% - 20px); /* Four columns with space between */
    box-sizing: border-box; /* Ensure padding and margin are included in width */
    background-color: #ccc; /* Light background for contrast */
    border-radius: 8px;
    padding: 15px; /* Padding inside each house item */
    color: #fff; /* Text color for contrast */
    transition: transform 0.2s; /* Smooth transition for hover effect */
   
}
/* Hover effect for house items */
.house-item:hover {
    transform: scale(1.02); /* Slight zoom effect on hover */
}

/* Background color for occupied status */
.house-item.occupied {
    background-color: #4CAF50; /* Green */
}

/* Background color for empty status */
.house-item.empty {
    background-color: #f44336; /* Red */
}

/* Styling for strong elements */
strong {
    display: block;
    margin-bottom: 10px; /* Added space below strong elements */
    font-weight: bold;
}

/* Additional styling for occupancy history */
.occupancy-history {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #fff; /* Divider line for history section */
}

/* Styling for tenant details */
.tenant-detail {
    margin: 5px 0;
}

/* Pagination styling */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a {
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color 0.3s;
    border: 1px solid #ddd;
    margin: 0 4px;
    border-radius: 5px;
    background-color: #007bff;
}

.pagination a.active {
    background-color: #0056b3;
    color: white;
    border: 1px solid #0056b3;
}

.pagination a:hover:not(.active) {
    background-color: #0056b3;
}
/* Search input styling */
.search-container {
    margin-bottom: 20px;
    text-align: center;
}

.search-input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 500px;
    margin-right: 10px;
}

/*.search-button {
    padding: 10px 15px;
    background-color: #5cb85c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.search-button:hover {
    background-color: #449d44;
}*/
</style>

<p style="font-size: 1.5rem; font-weight: bold; text-align: center; margin-bottom: 20px; color: white;">
    Houses Graphical Overview
</p>

<div class="search-container">
    
        <input type="hidden" name="page" value="house_gui">
        <input type="text" name="searchgui" id="searchgui" class="search-input" placeholder="Search by House No, Description, Name Plot number">
        <!--<button type="submit" class="search-button">Search</button>-->
   <p style="font-size: 0.8rem; color: #ddd; margin-top: 5px;">
        Example: House No - G001,  Name - Stella Plot Number - 1815
   </p>
</div>

<div class="houses-container">
<?php 

// Assuming landlordid is stored in session
$landlordid = isset($_SESSION['login_landlordid']) ? $_SESSION['login_landlordid'] : null;

if ($landlordid === null) {
    echo "<p>Landlord ID not set in session. Please log in.</p>";
    return; // Stop further execution
}

// Define number of results per page
$results_per_page = 8;

// Get the search keyword
$search2 = isset($_GET['searchgui']) ? $_GET['searchgui'] : '';

// Base SQL query
$sqlsearch = "SELECT houses.* FROM houses 
        LEFT JOIN house_renting ON houses.shopid = house_renting.shopid AND houses.house_no = house_renting.house_id 
        LEFT JOIN tenants ON house_renting.shopid = tenants.shopid AND house_renting.tenant_id = tenants.id 
        WHERE houses.shopid = '$landlordid'";

// Add search conditions if a search keyword is provided
if (!empty($search2)) {
    $search_condition = " AND (houses.house_no LIKE '%$search2%' 
                            OR houses.description LIKE '%$search2%' 
                            OR tenants.firstname LIKE '%$search2%' 
                            OR tenants.lastname LIKE '%$search2%')";
    $sqlsearch .= $search_condition;
}

// Count the total number of results
$result = $conn->query($sqlsearch);
$number_of_results = $result->num_rows;

// Determine the total number of pages available
$number_of_pages = ceil($number_of_results / $results_per_page);

// Determine which page number visitor is currently on
if (!isset($_GET['house_gui_page'])) {
    $page = 1;
} else {
    $page = isset($_GET['house_gui_page']) && is_numeric($_GET['house_gui_page']) ? (int)$_GET['house_gui_page'] : 1;
}

// Determine the SQL LIMIT starting number for the results on the displaying page
$starting_limit_number = ($page - 1) * $results_per_page;

// Add the LIMIT clause to the SQL query
$sqlsearch .= " ORDER BY houses.id DESC LIMIT " . (int)$starting_limit_number . ',' . (int)$results_per_page;

// Get the data for the current page
$result1 = $conn->query($sqlsearch);

if (!$result1) {
    die('Error executing House query: ' . $conn->error);
}

$houses = [];
while ($row = $result1->fetch_assoc()) {
    $houses[$row['house_no']] = $row;  // Store house data indexed by house_no
}

// Print the list as columns if not empty
if (!empty($houses)) {
    foreach ($houses as $house) {
        // Determine the class based on the status
        $statusClass = $house['status'] == 1 ? 'occupied' : 'empty';
        echo '<div class="house-item ' . $statusClass . '">';
       
        switch ($account_type) {
            case 1: // Landlord
                echo '<strong>House No:</strong> ' . htmlspecialchars($house['house_no']) . '<br>';
                break;
            case 2: 
                echo '<strong>Plot No:</strong> ' . htmlspecialchars($house['house_no']) . '<br>';
                break;
            default:
                echo '<strong>House No:</strong> ' . htmlspecialchars($house['house_no']) . '<br>';
        }
        //echo '<strong> No:</strong> ' . htmlspecialchars($house['house_no']) . '<br>';
        echo '<strong>Description:</strong> ' . htmlspecialchars($house['description']) . '<br>';
        
        $category_id = $house['category_id'];
        ?>

       <strong>Category:</strong> 
       <?php
      // Prepare SQL statement to prevent SQL injection
$sql5 = "SELECT name FROM categories WHERE shopid = ? AND id = ?";

// Initialize a prepared statement
$stmt = $conn->prepare($sql5);

if (!$stmt) {
    die('Error preparing SQL statement: ' . $conn->error);
}

// Bind parameters to the SQL query
$stmt->bind_param('si', $landlordid, $category_id);

// Execute the prepared statement
$stmt->execute();

// Get the result
$result5 = $stmt->get_result();

// Fetch the category name
if ($row5 = $result5->fetch_assoc()) {
    $category = $row5['name'];
    echo htmlspecialchars($category);
} else {
    echo 'Category not found';
}

// Close the statement
$stmt->close();
       
       ?>
       <br>
        
        <?php
        switch($account_type){
            case 1:
                echo '<strong>Status:</strong> ' . ($house['status'] == 1 ? 'Occupied' : 'Empty') . '<br>';
                break;
            case 2:
                echo '<strong>Status:</strong> ' . ($house['status'] == 1 ? 'Sold' : 'Unsold') . '<br>';
                break;
            default:
                echo '<strong>Status:</strong> ' . ($house['status'] == 1 ? 'Occupied' : 'Empty') . '<br>';
        }

        // Occupancy History Section
        echo '<div class="occupancy-history">';
          ?>
          <?php
          switch ($account_type) {
              case 1:
                  echo '<strong>Monthly Rent:</strong> ';
                  break;
              case 2:
                  echo '<strong>Price:</strong> ';
                  break;
              default:
                  echo '<strong>Rent/Price:</strong> ';
          }
        ?>
        <?php
        $houseno = $house['house_no'];
        
        $currentYear = date('Y');
        
        $currentMonth = date('F');

        $sql4 = "SELECT price FROM house_data WHERE shopid = '$landlordid' AND house_id ='$houseno' 
        AND month ='$currentMonth' AND year ='$currentYear'"; // Be cautious of SQL injection here
        $result4 = $conn->query($sql4);
        
        $row4 = $result4->fetch_assoc();
        
        $price = isset($row4['price']) ? $row4['price'] : 'N/A'; 
        
        echo number_format((float)$price, 0);
        
        ?>
        </strong>
        
        
        <?php
    switch ($account_type) {
        case 1:
            echo '<strong>Occupancy History:</strong>';
            break;
        case 2:
            echo '<strong>Sales History:</strong>';
            break;
        default:
            echo '<strong>Occupancy/Sales History:</strong>';
        }

        $sql2 = "SELECT * FROM house_renting WHERE shopid = '$landlordid' AND house_id ='$houseno'"; // Be cautious of SQL injection here
        $result2 = $conn->query($sql2);

        if (!$result2) {
            die('Error executing House Renting query: ' . $conn->error);
        }

        while ($row2 = $result2->fetch_assoc()) {
            $tenantid = $row2['tenant_id'];
            $sql3 = "SELECT * FROM tenants WHERE shopid = '$landlordid' AND id ='$tenantid'"; // Be cautious of SQL injection here
            $result3 = $conn->query($sql3);
            
            if (!$result3) {
                die('Error executing Tenant query: ' . $conn->error);
            }

            $row3 = $result3->fetch_assoc();
            if ($row3) {
            echo '<div class="tenant-detail">' . htmlspecialchars($tenantid) . ' - ' . htmlspecialchars($row3['firstname']) . ' ' . htmlspecialchars($row3['middlename']) . ' ' . htmlspecialchars($row3['lastname']) . '</div>';
            }
        }
        
        echo '</div>'; // End of occupancy history section
        echo '</div>'; // End of house-item div
    }
} else {
    echo '<p>No houses found.</p>';
}

?>
</div>

<div class="pagination">
    <?php
    for ($page = 1; $page <= $number_of_pages; $page++) {
        echo '<a href="index.php?page=house_gui&house_gui_page=' . $page . '&searchgui=' . htmlspecialchars($search2) . '" ' . (($page == (isset($_GET['house_gui_page']) && is_numeric($_GET['house_gui_page']) ? (int)$_GET['house_gui_page'] : 1)) ? 'class="active"' : '') . '>' . $page . '</a> ';
    }
    ?>
</div>

<script>
document.getElementById('searchgui').addEventListener('input', function() {
    var searchgui = this.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector('.houses-container').innerHTML = this.responseText;
        }
    };
    xhttp.open('GET', 'house_gui_search.php?searchgui=' + searchgui, true);
    xhttp.send();
});
</script>
