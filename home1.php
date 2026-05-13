<?php 

include 'db_connect.php';

if (isset($_POST['expense_id'])) {
    include 'expense.php';
} else {
?>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 20px;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
    font-weight: 700;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 28px;
}

.dashboard {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.dashboard-card {
    background-color: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    width: calc(25% - 15px);
    margin: 10px 0;
    text-align: center;
    color: white;
    position: relative;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
}

.dashboard-card h2 {
    font-size: 22px;
    font-weight: 600;
    margin: 20px 0;
    /*text-transform: uppercase;*/
    letter-spacing: 1px;
}

.dashboard-card .icon {
    font-size: 45px;
    margin-bottom: 15px;
}

.dashboard-card .view-link {
    color: white;
    font-size: 16px;
    text-decoration: none;
    padding: 12px 20px;
    border-radius: 50px;
    background-color: rgba(255, 255, 255, 0.3);
    display: inline-block;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 10px; 
}

.dashboard-card .view-link:hover {
    background-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
}

/* Add Gradient Backgrounds for the Cards */
.card-houses {
    background: linear-gradient(135deg, #0d2074, #007bff);
}

.card-tenants {
    background: linear-gradient(135deg, #0d2074, #007bff);
}

.card-payments {
    background: linear-gradient(135deg, #0d2074, #007bff);
}

.card-comm {
    background: linear-gradient(135deg, #0d2074, #007bff);
}
/* Responsive Design */
@media (max-width: 1024px) {
    .dashboard-card {
        width: calc(50% - 15px);
    }
}

@media (max-width: 600px) {
    .dashboard-card {
        width: 100%;
    }
}
.dashboard {
    margin-top: 60px; /* Move the dashboard content down */
}

</style>

<div class="container">
    <div class="row">
        <div class="card-body text-white">
           
					<?php echo "Hello ". $_SESSION['login_name']."!"  ?>
					<?php   
					#$landlordid = $_SESSION['login_landlordid']; 
					
					echo $landlordid;
					  ?>
                    <hr>
                    </div>
    </div>
   
    <div class="dashboard">
        <div class="dashboard-card card-houses">
            <div class="icon">🏠</div>
            <h2><b>
            <?php
                $landlordid = $conn->real_escape_string($landlordid);
                $query = $conn->query("SELECT * FROM houses WHERE shopid = '$landlordid'"); 
                echo $query->num_rows;
            ?>
            </b></h2>
            <p><b>Total Houses</b></p>
            <a href="index.php?page=houses" class="view-link">View List</a>
        </div>
        <div class="dashboard-card card-tenants">
            <div class="icon">👥</div>
            <h2><b>
            <?php 
                echo $conn->query("SELECT tenant_id AS total_rows FROM house_renting where shopid ='$landlordid'")->num_rows;
            ?>
            </b></h2>
            <p><b>Total Tenants</b></p>
            <a href="index.php?page=tenants" class="view-link">View List</a>
        </div>
        <div class="dashboard-card card-payments">
            <div class="icon">💰</div>
            <h2><b>
            <?php 
                $today = date('Y-m-d');
                $stmt1 = $conn->prepare("SELECT sum(amount) as paid FROM payments WHERE shopid = ? AND date(date_created) = ?");
                $stmt1->bind_param("is", $landlordid, $today);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $expense1 = $result1->num_rows > 0 ? $result1->fetch_array()['paid'] : 0;
                $stmt1->close();
                
                $stmt2 = $conn->prepare("SELECT sum(AMOUNT) as paid FROM expenses WHERE shopid = ? AND date(Date_of_entry) = ?");
                $stmt2->bind_param("is", $landlordid, $today);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $expense2 = $result2->num_rows > 0 ? $result2->fetch_array()['paid'] : 0;
                $stmt2->close();
                
                $total_expense1 = $expense1 + $expense2;
                echo number_format($total_expense1, 2, '.', ',');
            ?>
            </b></h2>
            <p><b>Payments Today</b></p>
            <a href="index.php?page=invoices" class="view-link space-bottom">View Payments</a>
            <a href="index.php?page=expense" class="view-link">Expense Entry</a>
        </div>
        <div class="dashboard-card card-comm">
            <div class="icon">💬</div>
            <h2><b>Communication</b></h2>
            <!-- <a href="index.php?page=sms2" class="view-link">Send Message</a> -->
            <a href="index.php?page=email" class="view-link">Send SMS </a>
        </div>
    </div>
</div>

<?php 
} 
?>

<script>
    $('#manage-records').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp);
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success');
                    setTimeout(function(){
                        location.reload();
                    },800);
                }
            }
        });
    });

    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person();
        }
    });

    $('#check').on('click',function(e){
        get_person();
    });

    function get_person(){
        start_load();
        $.ajax({
            url:'ajax.php?action=get_pdetails',
            method:"POST",
            data:{tracking_id : $('#tracking_id').val()},
            success:function(resp){
                if(resp){
                    resp = JSON.parse(resp);
                    if(resp.status == 1){
                        $('#name').html(resp.name);
                        $('#address').html(resp.address);
                        $('[name="person_id"]').val(resp.id);
                        $('#details').show();
                        end_load();
                    }else if(resp.status == 2){
                        alert_toast("Unknown tracking id.",'danger');
                        end_load();
                    }
                }
            }
        });
    }
</script>
