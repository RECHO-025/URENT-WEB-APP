<?php
// Include DB connection
// include 'db_connect.php';


// Connect landlord details
$sql_c = "SELECT * FROM landlords WHERE landlord_id ='$landlordid'";
$compannydetails = $conn1->query_database_many_rows($conn, $sql_c);
$namec = $compannydetails["business_name"] ?? '';
$location = $compannydetails["geographical_description"] ?? '';
$email = $compannydetails["email_address"] ?? '';
$phone = $compannydetails["telephone_contact"] ?? '';

if(isset($_GET['house_id']))
  {
  $house_id = $_GET['house_id'];
  $tenant_id = $_GET['tenant_id'];
  $house_id = $_GET['house_id'];
   $house_price = $_GET['house_price'];
  }
  else {
  
  
  
  }
// Check if searching for invoice
if (isset($_GET['search_invoice'])) {
    $search_invoice = $_GET['search_invoice'];
    $sql_invoice = "SELECT * FROM invoices WHERE invoice_number = '$search_invoice'";
    $inv_result = $conn->query($sql_invoice);

    if ($inv_result && $inv_result->num_rows > 0) {
        $invoice = $inv_result->fetch_assoc();

        $new_invoice_number = $invoice['invoice_number'];
        $customer_name = $invoice['customer_name'];
        $house_id = $invoice['house_id'];
        $house_price = $invoice['house_price'];
        $date = date("d M Y", strtotime($invoice['invoice_date']));
    } else {
        die("<p style='text-align:center; color:red;'>Invoice not found.</p>");
    }
} else {
    // Fetch tenant info
    $sql = "SELECT * from tenants where id ='$tenant_id' AND shopid ='$landlordid'";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $customer_name = $row['firstname'] . ' ' . $row['lastname'];
        $date = date("d M Y");
    } else {
        die("Invalid rental ID or no data found.");
    }

    // Generate new invoice number
    $date_str = date("Ymd");
    $prefix = "INV-$date_str-";
    $sql_max = "SELECT MAX(invoice_number) AS max_inv FROM invoices WHERE invoice_number LIKE '$prefix%'";
    $result = $conn1->query_database_many_rows($conn, $sql_max);

    $last_num = 0;
    if (!empty($result['max_inv'])) {
        $last_num = (int)substr($result['max_inv'], -4);
    }

    $new_invoice_number = $prefix . str_pad($last_num + 1, 4, "0", STR_PAD_LEFT);

    // Insert into invoices table
    $insert_sql = "INSERT INTO invoices (invoice_number, landlord_id, customer_name, house_id, house_price, invoice_date)
    VALUES ('$new_invoice_number', '$landlordid', '$customer_name', '$house_id', '$house_price', CURDATE())";
    $conn1->update_record($conn, $insert_sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f7f7f7;
        }
        .invoice-box {
            background: #fff;
            border: 1px solid #eee;
            padding: 30px 40px;
            max-width: 800px;
            margin: auto;
            font-size: 16px;
            line-height: 24px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            color: #555;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .contact-info {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-bottom: 30px;
        }
        .invoice-details p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .text-right {
            text-align: right;
        }
        .print-btn, .search-form {
            margin-top: 30px;
            text-align: center;
        }
        .print-btn button, .search-form button {
            padding: 10px 20px;
            font-size: 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .print-btn button:hover {
            background: #0056b3;
        }
        .search-form input {
            padding: 8px;
            width: 300px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #invoice, #invoice * {
                visibility: visible;
            }
            #invoice {
                position: absolute;
                left: 0;
                right: 0;
                top: 0;
                margin: auto;
                box-shadow: none;
            }
            .print-btn, .search-form {
                display: none;
            }
        }
    </style>

    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</head>
<body>
<?php
$sql233 = "SELECT category_id from houses where house_no ='$house_id' AND shopid ='$landlordid'";
 
$house = $conn1-> query_database_many_rows($conn,$sql233);
 
$category_id =$house["category_id"];

$sql2334 = "SELECT name from categories where id ='$category_id' AND shopid ='$landlordid'";
 
$category = $conn1-> query_database_many_rows($conn,$sql2334);
 
$category_name =$category["name"];



?>
<!-- ?? Invoice Section -->
<div id="invoice" class="invoice-box">
    <div class="header">
        <h2><?php echo $namec; ?></h2>
    </div>

    <div class="contact-info">
        <div><?php echo $location; ?></div>
        <div>Email: <?php echo $email; ?> | Tel: <?php echo $phone; ?></div>
    </div>

    <h4 style="text-align: center; color: #0066FF;">Sales Invoice</h4>
    <div style="text-align: center; color: red; font-weight: bold;">Invoice No: <?php echo $new_invoice_number; ?></div>

    <div class="invoice-details">
        <p><strong>Date:</strong> <?php echo $date; ?></p>
        <p><strong>Customer Name:</strong> <?php echo $customer_name; ?></p>
        <p><strong>Property:</strong><?php echo $category_name; ?> |  <?php echo $house_id; ?>   </p>
    </div>

    <table>
        <tr>
            <td><strong>Description</strong></td>
            <td class="text-right"><strong>Amount</strong></td>
        </tr>
        <tr>
            <td>Price</td>
            <td class="text-right">UGX <?php echo number_format($house_price); 
			
			?></td>
        </tr>
        <tr class="total">
            <td>Total</td>
            <td class="text-right">UGX <?php echo number_format($house_price); 
			?>
			[
			<?php
			$formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
			echo $formatter->format($house_price);  
			
			
			
			?>
			  ]
			</td>
        </tr>
    </table>
</div>

<!-- ??? Print Button -->
<div class="print-btn">
    <button onClick="printInvoice()">Print Invoice</button>
</div>

</body>
</html>
