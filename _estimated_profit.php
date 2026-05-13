<?php
include 'db_connect.php';
$landlord_id = $_SESSION['login_landlordid'];

$blockid = $_POST['reporttype'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Profit Analysis Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            color: #333;
        }
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 8px;
        }
        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 30px;

        }
        .chart-box {
            width: 48%;
            min-width: 400px;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            background-color:#ffff;
        }
        .chart-title {
            text-align: center;
            margin-top: 0;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color:#ffff;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        
        .profit-positive {
            color: #14ca84ff;
            font-weight: bold;
        }
        .profit-negative {
            color: #e02f0fff;
            font-weight: bold;
        }
        .profit-neutral {
            color: #3498db;
            font-weight: bold;
        }
        .category-header {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }
        .summary-card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .report-title{
            color:white;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <h1 class="report-title">Estates Analysis Report</h1>
        
        <!-- Summary Cards -->
        <div class="summary-card">
            <h3>Key Metrics</h3>
            <div style="display: flex; justify-content: space-around;">
                <div style="text-align: center;">
    <h4>Block cost: </h4>
	<?php
	
$houseids = [];

$estimated_px = [];

$actual_pxs = [];

$biases = [];


$sql_housepxx3 = "SELECT houses.house_no 
               FROM houses 
               INNER JOIN house_renting
                   ON house_renting.house_id = houses.house_no  
               WHERE houses.category_id = '$blockid' 
               AND houses.shopid = '$landlordid'";
			 			   

			   
$myquery24 = $conn1->query_database_many($conn, $sql_housepxx3);

while ($info34 = $myquery24->fetch_assoc()) {
    $house_id = $info34['house_no'];
    array_push($houseids, $house_id);
	
	$sql_housepxx5 ="SELECT price FROM house_data 
               WHERE house_id ='$house_id' 
               AND shopid = '$landlordid'";
			   
	$myquery25 = $conn1->query_database_many($conn, $sql_housepxx5);		   
			  	   
	while ($info35 = $myquery25->fetch_assoc()) {
        
		$estimated_price = $info35['price'];
		
		array_push($estimated_px,$estimated_price);
		
			   
        }	
		
	$sql_housepxx6 ="SELECT Price FROM house_renting
               WHERE house_id ='$house_id' 
               AND shopid = '$landlordid'";
			   
	$myquery26 = $conn1->query_database_many($conn, $sql_housepxx6);		   
			  	   
	while ($info36 = $myquery26->fetch_assoc()) {
        
		$actual_px = $info36['Price'];
		
		array_push($actual_pxs,$actual_px);
		
			   
        }	
		
}

for ($i = 0; $i < count($actual_pxs); $i++) {
    $bias = $estimated_px[$i] - $actual_pxs[$i]; 
    array_push($biases, $bias);
}




 ?>
	
		<?php
$sqlcprice = "SELECT name, cprice FROM categories 
                     WHERE shopid = '$landlord_id' AND id ='$blockid'";
	 
	$blockPx = $conn1-> query_database_many_rows($conn, $sqlcprice);
 
$blockpx = $blockPx["cprice"];

$blockname = $blockPx["name"];

echo $blockname; 
?>
    </div>
    
    <div style="text-align: center;">
                    <h4>Block Price: </h4>
                    <?php
                    // Display the block price
                    echo "UGX ";
echo number_format($blockpx);

	?>
	</div>
	
    <div style="text-align: center;">
                    <h4>Total Plots Sold : </h4>
                    <?php
                   $count1 = count($actual_pxs);
echo $count1;

                   ?>
                  <h4>Total sales of plots: </h4>
<?php

$totalss = array_sum($actual_pxs);
echo "UGX ";
echo number_format($totalss);


?> </div>
<div style="text-align: center;">
                    <h4>Total Bias:  </h4>
                    <p class="profit-positive">UGX<?php 
					
					$sumb = array_sum($biases);
echo number_format($sumb);
?>
</p></div>
                
        </div>

<!--<div style="text-align: center;">
                    <h4>Total Profit</h4>
                    <p class="profit-positive">UGX<//?php echo number_format(array_sum($category_profits), 0); ?></p>
                </div>
                <div style="text-align: center;">
                    <h4>Total Loss</h4>
                    <p class="profit-negative">UGX<//?php echo number_format(array_sum($category_losses), 0); ?></p>
                </div>
                <div style="text-align: center;">
                    <h4>Net Profit</h4>
                    <p style="font-weight: bold; color: <//?php echo (array_sum($category_totals) >= 0 ? '#27ae60' : '#e74c3c'); ?>">
                        UGX<//?php echo number_format(array_sum($category_totals), 2); ?>
                    </p>
                </div>
            </div>
        </div>-->
        
        <!-- Charts Section -->
        <div class="chart-container">
            <div class="chart-box">
                <h3 class="chart-title">Bias Distribution by Category</h3>
                 <canvas id="biasChart" width="500" height="500"></canvas>
            </div>
           
        </div>

        <!-- Detailed Table -->
        <table>
            <thead>
			  <?php
$sql_category = "SELECT name FROM categories 
                     WHERE shopid = '$landlord_id' AND id ='$blockid'";
					 
$blockname1 = $conn1-> query_database_many_rows($conn, $sql_category);
 
$blockname = $blockname1["name"];
?>
			<tr class="category-header">
    <td colspan="7"><?php echo htmlspecialchars($blockid); ?> : <?php echo htmlspecialchars($blockname); ?></td>
</tr>
                <tr>
                    <th>Plot No</th>
                    <th>Description</th>
                    <th>Estimated Price</th>
                    <th>Purchase Price</th>
                    <th> Bias Amount </th>
                    <th> Target </th>
                </tr>
            </thead>
            <tbody>
<?php
				
for ($j = 0; $j < count($actual_pxs); $j++) {




?>

<tr>
    <td><?php echo htmlspecialchars($houseids[$j]); ?></td>
    <td><?php 
	$sql_house = "SELECT description FROM houses 
                     WHERE shopid = '$landlord_id' AND category_id ='$blockid' AND house_no ='$houseids[$j]'";
					 
$housename1 = $conn1-> query_database_many_rows($conn, $sql_house);
 
$housename = $housename1["description"];
	
echo htmlspecialchars($housename); 
	?></td>
    <td>UGX <?php echo number_format($estimated_px[$j], 2); ?></td>
    <td>UGX <?php echo number_format($actual_pxs[$j], 2); ?></td>
    <td>UGX <?php echo $biases[$j]; ?></td>
    <td>
        <?php 
        if ($biases[$j] == 0) {
            echo "<span style='color: green;'>Target</span>";
        } else {
            echo "<span style='color: red;'>Miss</span>";
        }
        ?>
    </td>
</tr>

<?php 
  }
// Step 2: Get actual (rented/sold) prices


// Example fallback if arrays are empty (avoid JS error)
if (empty($houseids)) $houseids = ['No Data'];
if (empty($biases)) $biases = [1];
?>
    <script>
        // Get PHP data into JavaScript
        const houseLabels = <?php echo json_encode($houseids); ?>;
        const biasValues = <?php echo json_encode($biases); ?>;

        // Draw pie chart
        const ctx = document.getElementById('biasChart').getContext('2d');
        const biasChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: houseLabels,
                datasets: [{
                    label: 'Bias Value',
                    data: biasValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(199, 199, 199, 0.6)',
                        'rgba(100, 149, 237, 0.6)'
                    ],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: ''
                    },
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    </script>
</body>
</html>
