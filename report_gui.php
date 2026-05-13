
<?php
$mystatus = 1;

#include 'index.php';


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Tenancy Performance Report</title>
    <link rel="stylesheet" href="style.css">
    <!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- DataTables.js CDN for table filtering -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<style>
    /* General body and layout styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

/* Header styling */
header {
    background-color: #0D2074;
    color: white;
    padding: 20px;
    text-align: center;
}

h1 {
    margin: 0;
}

h2 {
    color: #333;
}

/* Section container styling */
section {
    padding: 20px;
    margin: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    table-layout: fixed;  /* Ensures table adjusts in smaller screens */
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
    vertical-align: top; /* Ensures vertical alignment of content */
    word-wrap: break-word; /* Ensures long words wrap to the next line */
    height: auto; /* Adjusts height based on the content */
    white-space: normal; /* Ensures that text wraps within cells */
}
th {
    background-color: #f2f2f2;
}
/* Button styling for showing/hiding charts */
#show-charts-btn {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin: 20px;
    display: block;
    text-align: center;
}

#show-charts-btn:hover {
    background-color: #45a049;
}

/* Chart Section Styling */
#charts {
    padding: 20px;
    margin: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

canvas {
    max-width: 100%;
    height: auto;
    margin: 20px 0;
}


/* Media query for smaller screens */
@media (max-width: 768px) {
    header {
        padding: 15px;
    }

    section {
        margin: 10px;
        padding: 15px;
    }

    h2 {
        font-size: 1.2em;
    }

    table, th, td {
        font-size: 14px;
        padding: 8px;
    }
}

@media (max-width: 480px) {
    body {
        font-size: 14px;  /* Adjust the global font size */
    }

    h1 {
        font-size: 1.5em;  /* Resize header title */
    }

    h2 {
        font-size: 1.1em;  /* Resize section headers */
    }

    table {
        font-size: 12px;  /* Make text smaller to fit */
    }

    th, td {
        padding: 6px;  /* Reduce padding for better fit */
    }

    section {
        margin: 5px;
        padding: 10px;
    }
}

@media (max-width: 320px) {
    table, th, td {
        font-size: 11px;  /* Further reduce text size */
    }

    th, td {
        padding: 4px;  /* Tighten up table spacing for very small screens */
    }

    section {
        padding: 8px;
    }

    h1 {
        font-size: 1.3em;  /* Resize for the smallest screens */
    }

    h2 {
        font-size: 1em;
    }
}

</style>
</head>
<body>
<?php
$from =$_POST['from9'];
$to =$_POST['to9'];

$date = mktime(0,0,0,date("Y"),date("m"),date("d")); 
$date_2day = date("Y-m-d", $date);
$from_date =date("$from",$date);
$to_date =date("$to",$date);


?>
    <header>
        <h1>House Tenancy Performance Report</h1>
    </header>
    <div class="row">
    <section id="chart-toggle">
        <button id="show-charts-btn">Show Performance Charts</button>
        </section>
<section id="print">
    <button class="btn btn-sm btn-block btn-primary col-md-2 ml-1 float-right" type="button" id="print"><i class="fa fa-print"></i> Print</button>
</section>
    
						     
	</div>
    
    <!-- Rent Collection Performance -->
    <section id="rent-collection">
	<?php
	$result_dues = "SELECT SUM(amount) as total_amount FROM payments
where shopid ='$landlordid' AND date_created BETWEEN '$from_date' AND '$to_date' AND pay_status = 0";

// Execute the query
$info2456 = $conn1->query_database_many_rows($conn,$result_dues);

// Correctly reference the result from $info24
$amount_due = $info2456["total_amount"]; 


$result_collected = "SELECT SUM(amount) as total_amount FROM payments
where shopid ='$landlordid' AND date_created BETWEEN '$from_date' AND '$to_date' AND pay_status = 1";

// Execute the query
$info2457 = $conn1->query_database_many_rows($conn,$result_collected);

// Correctly reference the result from $info24
$amount_collected = $info2457["total_amount"]; 

# Total number of tenants with dues

$result_tenants = "SELECT count(*) as total_tenants FROM payments
WHERE shopid ='$landlordid' AND date_created BETWEEN '$from_date' AND '$to_date' AND pay_status = 0";

// Execute the query
$info2458 = $conn1->query_database_many_rows($conn, $result_tenants);

// Correctly reference the result from $info2458
$no_of_tenants = $info2458["total_tenants"]; // Use index 0 if multiple rows aren't expected



	
	?>
        <h2>Monthly Rent Collection Performance</h2>
        <table>
            <tr>
                <th>Metric</th>
                <th>Description</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Total Rent Due</td>
                <td>Total amount of rent that was due for the month</td>
                <td><?php echo number_format($amount_due); ?></td>
            </tr>
            <tr>
                <td>Total Rent Collected</td>
                <td>Total amount of rent collected for the month</td>
                <td><?php echo number_format($amount_collected); ?></td>
            </tr>
            <tr>
                <td>Rent Collection Rate</td>
                <td>Percentage of rent collected versus total due</td>
                <td><?php
				$percentage = ($amount_due / ($amount_collected + $amount_due)) * 100;
echo number_format($percentage, 1) . '%';

				
				 ?></td>
            </tr>
            <tr>
                <td>Number of Tenants dues </td>
                <td>Number of tenants with dues</td>
                <td><?php echo $no_of_tenants; ?></td>
            </tr>
        </table>
    </section>

    <!-- Occupancy and Vacancy Report -->
    <section id="occupancy-vacancy">
        <h2>Occupancy and Vacancy Report</h2>
        <table>
            <tr>
                <th>House/Property</th>
                <th>Total Units</th>
                <th>Occupied Units</th>
                <th>Vacant Units</th>
                <th>Occupancy Rate</th>
                <th>Vacancy Rate</th>
            </tr>
			<?php
$result22 = "SELECT category_id,CONCAT(category_id, ': ', description) AS combined, COUNT(*) AS total_units 
FROM houses 
WHERE shopid = '$landlordid' GROUP BY category_id";

$myquery12 = $conn1-> query_database_many($conn,$result22);

while($nt2 = $myquery12->fetch_assoc()) {

    $combined = $nt2["combined"];
    $total_units = $nt2["total_units"];
	$category_id = $nt2["category_id"];

?> 
            <tr>
                <td><?php echo $combined; ?></td>
                <td><?php echo $total_units; ?></td>
                <td><?php
				$result24c = "SELECT COUNT(*) AS tot_units 
FROM houses 
WHERE shopid = '$landlordid' AND category_id='$category_id' AND status =1"; 

 $info2c = $conn1-> query_database_many_rows($conn,$result24c);

 $count_category =  $info2c["tot_units"];
 echo $count_category; 
				
				?></td>
                <td>
				<?php
				$result24cC = "SELECT COUNT(*) AS tot_units 
FROM houses 
WHERE shopid = '$landlordid' AND category_id='$category_id' AND status =0"; 

 $info2cC = $conn1-> query_database_many_rows($conn,$result24cC);

 $count_categoryC =  $info2cC["tot_units"];
 echo $count_categoryC; 
				
				?>
				
				
				</td>
                <td>
				<?php
				$percentage1 = ($count_category / ($count_categoryC + $count_category)) * 100;
echo number_format($percentage1, 1) . '%';

				
				?>
				</td>
                <td><?php
				$percentage2 = ($count_categoryC / ($count_categoryC + $count_category)) * 100;
echo number_format($percentage2, 1) . '%';

				
				?></td>
            </tr>
			<?php
			    }
			
			?>
        </table>
    </section>
    <!-- Tenant Satisfaction Report 
    <section id="tenant-satisfaction">
        <h2>Tenant Satisfaction Report</h2>
        <table>
            <tr>
                <th>Metric</th>
                <th>Description</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Average Tenant Satisfaction</td>
                <td>Average tenant satisfaction score (out of 5)</td>
                <td>4.2</td>
            </tr>
            <tr>
                <td>Number of Complaints</td>
                <td>Total number of tenant complaints received</td>
                <td>2</td>
            </tr>
            <tr>
                <td>Resolved Complaints</td>
                <td>Number of complaints resolved</td>
                <td>2</td>
            </tr>
            <tr>
                <td>Complaints Response Time</td>
                <td>Average time taken to respond to tenant complaints</td>
                <td>1 Day</td>
            </tr>
            <tr>
                <td>Tenant Retention Rate</td>
                <td>Percentage of tenants who renewed their leases</td>
                <td>85%</td>
            </tr>
        </table>
        <p><strong>Notes:</strong> Tenant satisfaction is relatively high at 4.2 out of 5.</p>
    </section>
-->
    <!-- Financial Performance Report -->
    <section id="financial-performance">
        <h2>Financial Performance Report</h2>
		<?php
		$result225 = "SELECT SUM(amount) as total_amount FROM payments
WHERE shopid = '$landlordid' AND date_created BETWEEN '$from_date' AND '$to_date' AND pay_status = 1";

// Execute the query
$info245625 = $conn1->query_database_many_rows($conn, $result225); // Use $result225 here

// Correctly reference the result from $info245625
$gross_amount = isset($info245625["total_amount"]) ? $info245625["total_amount"] : 0; // Handle case where there's no result

 
		
		?>
        <table>
            <tr>
                <th>Metric</th>
                <th>Description</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Gross Rental Income</td>
                <td>Total rental income received for the period</td>
                <td><?php echo number_format($gross_amount);  ?></td>
            </tr>
            <tr>
                <td>Operational Expenses</td>
                <td>Total expenses for the period (e.g. maintenance, utilities)</td>
                <td><?php 
				$result6 = "SELECT SUM(AMOUNT) as total_expenses 
            FROM expenses 
            WHERE shopid ='$landlordid' 
            AND Date_of_trans BETWEEN '$from_date' AND '$to_date'";

// Execute the query
$info24 = $conn1->query_database_many_rows($conn, $result6);

// Correctly reference the result from $info24
$total_expenses = $info24["total_expenses"];

echo number_format($total_expenses, 0, '.', ','); 
				
				?></td>
            </tr>
            <tr>
                <td>Net Operating Income (NOI)</td>
                <td>Rental income minus operating expenses</td>
                <td><?php
				$netincome = $gross_amount - $total_expenses;
				echo number_format($netincome, 0, '.', ','); 
				
				 ?></td>
            </tr>
			 <!-- 
            <tr>
                <td>Maintenance Costs</td>
                <td>Total costs related to property maintenance and repairs</td>
                <td>$1,500</td>
            </tr>
			-->
            <tr>
                <td>Return on Investment (ROI)</td>
                <td>ROI based on rental income and operational expenses</td>
                <td> </td>
            </tr>
        </table>
    <!-- Chart Section (initially hidden) -->
    <section id="charts" style="display:none;">
        <h2>Performance Charts</h2>
        <div>
            <h3>Rent Collection Rate</h3>
            <canvas id="rentCollectionChart"></canvas>
        </div>
        <div>
            <h3>Occupancy and Vacancy Rates</h3>
            <canvas id="occupancyChart"></canvas>
        </div>
       
	   <!-- 
<div>
    <h3>Tenant Satisfaction</h3>
    <canvas id="satisfactionChart"></canvas>
</div> 
-->
		
    </section>

    <script>
$(document).ready(function() {
    // Initialize DataTables for tables
    $('table').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true
    });
});

// Variable to track if charts are already loaded
let chartsLoaded = false;

document.addEventListener('DOMContentLoaded', function() {
    // Button to toggle the charts section
    const showChartsBtn = document.getElementById('show-charts-btn');
    const chartsSection = document.getElementById('charts');

    showChartsBtn.addEventListener('click', function() {
        // Toggle visibility of the charts section
        if (chartsSection.style.display === "none") {
            chartsSection.style.display = "block";
            showChartsBtn.textContent = "Hide Performance Charts";

            // Load charts only if they haven't been loaded yet
            if (!chartsLoaded) {
                loadCharts();
                chartsLoaded = true;
            }
        } else {
            chartsSection.style.display = "none";
            showChartsBtn.textContent = "Show Performance Charts";
        }
    });

    // Function to load the charts using Chart.js
    function loadCharts() {
        // Rent Collection Rate Chart
        var rentCollectionCtx = document.getElementById('rentCollectionChart').getContext('2d');
        new Chart(rentCollectionCtx, {
            type: 'bar',
            data: {
                labels: ['Total Rent Due', 'Rent Collected'],
                datasets: [{
                    label: 'Rent Collection Performance',
                    data: [<?php echo $amount_due; ?>,<?php echo $amount_collected; ?>],
                    backgroundColor: ['#4CAF50', '#2196F3'],
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Occupancy and Vacancy Chart
var occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
<?php
$result22 = "SELECT category_id, CONCAT(category_id, ': ', description) AS combined, COUNT(*) AS total_units 
FROM houses 
WHERE shopid = '$landlordid' 
GROUP BY category_id";

$myquery12 = $conn1->query_database_many($conn, $result22);

$labels = [];
$occupiedData = [];
$vacantData = [];

while ($nt2 = $myquery12->fetch_assoc()) {
    $combined = $nt2["combined"];
    $total_units = $nt2["total_units"];
    $category_id = $nt2["category_id"];

    // Store label
    $labels[] = $combined;

    // Query for occupied units
    $result24c = "SELECT COUNT(*) AS tot_units 
    FROM houses 
    WHERE shopid = '$landlordid' AND category_id='$category_id' AND status = 1";
    
    $info2c = $conn1->query_database_many_rows($conn, $result24c);
    $count_category = $info2c["tot_units"];
    $occupiedData[] = $count_category;

    // Query for vacant units
    $result24cC = "SELECT COUNT(*) AS tot_units 
    FROM houses 
    WHERE shopid = '$landlordid' AND category_id='$category_id' AND status = 0";
    
    $info2cC = $conn1->query_database_many_rows($conn, $result24cC);
    $count_categoryC = $info2cC["tot_units"];
    $vacantData[] = $count_categoryC;
}

// Convert PHP arrays to JavaScript arrays
?>
new Chart(occupancyCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [
            {
                label: 'Occupied Units',
                data: <?php echo json_encode($occupiedData); ?>,
                backgroundColor: '#4CAF50',
            },
            {
                label: 'Vacant Units',
                data: <?php echo json_encode($vacantData); ?>,
                backgroundColor: '#FF5722',
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

  /*
        // Tenant Satisfaction Chart (Pie Chart)
        var satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
        new Chart(satisfactionCtx, {
            type: 'pie',
            data: {
                labels: ['Satisfied', 'Complaints'],
                datasets: [{
                    data: [4.2, 2],  // Example: Satisfaction score out of 5
                    backgroundColor: ['#4CAF50', '#FF5722']
                }]
            }
        });
		*/
    }
});

    </script>
	<script>
// Function to trigger the print dialog
document.getElementById('print').addEventListener('click', function() {
    window.print();
});
</script>
</body>
</html>
