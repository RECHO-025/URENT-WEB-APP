<?php
 
include('db_connect.php');

session_start();
 
$landlordid = $_SESSION['login_landlordid']; 

$invoicetype = $_GET['tenantid'];

$IDD = $conn->query("SELECT name, amount FROM utility_bill_categories WHERE shopid ='$landlordid'");

if ($IDD->num_rows > 0) {

    if ($invoicetype == 'Utility_Bills') {
        while ($row1 = $IDD->fetch_assoc()) {
            $name = htmlspecialchars($row1['name'], ENT_QUOTES, 'UTF-8');
            $amount = htmlspecialchars($row1['amount'], ENT_QUOTES, 'UTF-8');
            ?>
            <div class="form-group">
                <input type="checkbox" id="<?php echo $name; ?>" name="selected_items[]" value="<?php echo $name; ?>" />
                <label for="<?php echo $name; ?>"><?php echo $name; ?></label>
                <input type="number" id="amount_<?php echo $name; ?>" name="amounts[<?php echo $name; ?>]" value="<?php echo $amount; ?>" step="0.01" min="0" />
            </div>
            <?php
        }
    } else {
        // You can add additional logic here if needed
    }
} else {
    ?>
    <p align="center">Zero records in the Utility bills records set</p>
    <?php
}
?>
