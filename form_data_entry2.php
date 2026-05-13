<?php
include 'db_connect.php'; 

$landlordid = $_SESSION['login_landlordid']; 
?>

<style>
  /* Form container styling */
  .form-container {
    max-width: 500px;
    margin: auto;
    background: rgba(255, 255, 255, 0.9);
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  /* Heading styling */
  .form-container h3 {
    text-align: center;
    color: #333;
    font-weight: bold;
    margin-bottom: 1.5rem;
  }

  /* Label styling */
  .form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #555;
  }

  /* Input and select styling */
  .form-group input[type="date"],
  .form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  /* Submit button styling */
  .form-group .submit-btn {
    display: block;
    width: 100%;
    padding: 0.75rem;
    background-color: #0d2074;
    color: #fff;
    font-weight: bold;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    
  }

  .form-group .submit-btn:hover {
    background-color: #0056b3;
  }

  /* Centered text styling */
  .center-text {
    text-align: center;
    font-weight: bold;
    color: #333;
    margin: 1rem 0;
  }
</style>

<div class="form-container">
  <!--<h3>Select By Date of Transact</h3>-->
  
  <form action="index.php?page=_estimated_profit" method="post" name="form1" id="form1">
   <!-- <div class="form-group">
      <label for="start_date">Start Date</label>
      <input type="date" name="datedo" id="start_date">
    </div>

    <div class="center-text">to</div>

    <div class="form-group">
      <label for="end_date">End Date</label>
      <input type="date" name="dated1" id="end_date">
      <input type="hidden" name="expense_id" value="1">
    </div>-->

    <div class="form-group">
      <label for="reporttype">Sort Report by:</label>
      <select id="reporttype" name="reporttype" required>
        <option value="">Select Block </option>
        
        <?php 
        
          $sites = $conn->query("SELECT * FROM categories WHERE shopid ='$landlordid' ORDER BY id ASC");
          if($sites->num_rows > 0):
            while($row2 = $sites->fetch_assoc()):
        ?>
          <option value="<?php echo $row2['id'] ?>"><?php echo $row2['id'] ?> : <?php echo $row2['name'] ?></option>
        <?php 
            endwhile;
          else: 
        ?>
          <option selected="" value="" disabled="">Please check the Sites list.</option>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group">
      <input type="submit" name="Save" id="Save" value="Generate Reports" class="submit-btn">
    </div>
  </form>
</div>
