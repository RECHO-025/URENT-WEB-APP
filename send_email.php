<?php include 'db_connect.php'; 

 
$landlordid = $_SESSION['login_landlordid']; 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM wallet WHERE shopid = '$landlordid' AND id=".$_GET['id']);
    
}

// Fetch the rental amount from the database
$rental_price = '';
$houseid = "house_id"; // replace with actual house ID
$month = date('F'); // replace with the current or selected month
$year = date('Y'); // replace with the current or selected year

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Notification </title>
    <style>
     body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f5;
    margin: 0;
    padding: 20px;
    color: #333;
}

.container {
    background-color: #0d2074;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    max-width: 500px;
    margin: 0 auto;
    color: white;
    transition: all 0.3s ease;
}

.container:hover {
    transform: scale(1.02);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 28px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
}

label {
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}

input[type="text"], textarea, select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
    background-color: #f4f4f9;
    color: #333;
    transition: border 0.3s ease;
}

input[type="text"]:focus, textarea:focus, select:focus {
    border-color: #007ACC;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 122, 204, 0.4);
}

textarea {
    min-height: 120px;
    resize: vertical;
}

input[type="submit"] {
    background-color: #49bde4;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

input[type="submit"]:hover {
    background-color: #007aff;
    transform: translateY(-2px);
}

.row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.col-6 {
    flex: 48%;
}

.col {
    width: 100%;
}

textarea[readonly], input[readonly] {
    background-color: #ececec;
    color: #666;
}

@media (max-width: 768px) {
    .col-6 {
        flex: 100%;
    }

    h2 {
        font-size: 24px;
    }
}

    </style>
    <script>
      /*  function updateMessage() {
            var tenantName = document.getElementById("name").value;
            var rentalAmount = document.getElementById("price").value;
            var month = document.getElementById("month").value;
            var year = document.getElementById("year").value;
            var arrears = document.getElementById("arrears").value;

            var messageTemplate = `Dear ${tenantName}, your rental amount of ${rentalAmount} for ${month} ${year} is due. Arrears: ${arrears}`;
            document.getElementById("message_template").value = messageTemplate;
        }
        window.onload = function() {
        updateMessage();
    }

    // Also, call updateMessage whenever there's an input change
    document.getElementById("arrears").addEventListener("input", updateMessage);
    document.getElementById("name").addEventListener("input", updateMessage);*/
    </script>
</head>
<body>

<div class="container">
    <h2>Send Notifications to Tenants</h2>
	<?php
	
	
	?>

    <form method="POST" action="send_sms.php">
        <!-- Tenant Contacts Text Area -->
        <div class="col">
            <label for="tenant_contacts">Tenant Contacts :</label>
           <textarea id="contact" name="contact" placeholder="e.g. 256702014626 256705384982" style="margin-left: auto; text-align: left;" required>
    <?php
    $tenant = $conn->query(" SELECT t.id, CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS name, t.contact as contact,
           SUM(p.amount) AS total_balance
    FROM tenants t
    INNER JOIN payments p ON t.id = p.tenant_id
    WHERE t.shopid = '$landlordid' AND p.pay_status = 0
    GROUP BY t.id");
    
    if ($tenant) {
        $contacts = [];
        while ($row = $tenant->fetch_assoc()) {
            $contacts[] = ucwords($row['contact']);
        }
		 echo implode(PHP_EOL, $contacts); 
	  
    }
    ?>
</textarea>

        </div>
        <input type="hidden" id="rental" name="month" value="">
      
        <input type="hidden" id="month" name="month" value="">
        <input type="hidden" id="year" name="year" value="">

        <!-- Other Form Fields -->
        <div class="col">
            <label for="tenant_name">Tenant Names:</label>
            <textarea id="tenant_name" name="tenant_name"  placeholder="" required>
                <?php
$tenant_query = $conn->query("
    SELECT t.id, CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS name, t.contact,
           SUM(p.amount) AS total_balance
    FROM tenants t
    INNER JOIN payments p ON t.id = p.tenant_id
    WHERE t.shopid = '$landlordid' AND p.pay_status = 0
    GROUP BY t.id
");

if ($tenant_query) {
    $tenants = [];
    while ($row = $tenant_query->fetch_assoc()) {
        $tenant_name = ucwords($row['name']);
        $balance = number_format($row['total_balance'], 2); // Format the balance to 2 decimal places
        $tenants[] = "$tenant_name [$balance]";
    }
    echo implode("\n", $tenants);
}
?>
            </textarea>
        
            <div class="col-6">
                <label for="sender_id">Sender Id (Use any word less than 11 words): </label>
                <input type="text" id="sender_id" name="sender_id" maxlength="11" required>
            </div>
      

        <div class="col-md-12">
            <label for="message_template">Message Preview:</label>
       <textarea id="message_template" name="message_template" style="text-align: left; height: 200px;" oninput="countCharacters()">
    Good afternoon, I would like to remind you to deposit some money and submit your pay slip to the management office.
    Your outstanding rent is [] as of [current month]. Thank you.
</textarea>

<p> Count: <span id="character_count">0</span></p>

<script>
// Function to replace [current month] with actual month
function replaceCurrentMonth() {
    var textArea = document.getElementById("message_template");
    var text = textArea.value;
    
    // Get the current month name
    var currentMonth = new Date().toLocaleString('default', { month: 'long' });

    // Replace [current month] with the actual current month
    textArea.value = text.replace("[current month]", currentMonth);
}

// Function to count characters
function countCharacters() {
    var text = document.getElementById("message_template").value;
    var characterCount = text.length;
    document.getElementById("character_count").innerText = characterCount;
}

// Replace the [current month] on page load
replaceCurrentMonth();
</script>

        <!-- Submit Button -->
        <div class="send">
        <input type="submit" value="Send Message">
        </div>
    </form>
</div>
</div>
<script>
    // Call updateMessage on form load to populate the message preview
    window.onload = function() {
        updateMessage();
    }
</script>

</body>
</html>
