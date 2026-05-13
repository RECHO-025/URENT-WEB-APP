<?php 
include 'db_connect.php';
$landlordid = $_SESSION['login_landlordid']; 
$sql222 ="SELECT account_type FROM landlords WHERE landlord_id ='$landlordid'";

$account_type1 = mysqli_query($conn,$sql222);
$account_type = mysqli_fetch_assoc($account_type1)["account_type"];
?>
<body id="page">
<div class="container-fluid py-4">
	<div class="col-lg-12">
		<div class="card shadow">
	
			<div class="card-body">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6 mb-4">
							<div class="card border-primary h-100">
								<div class="card-header">
									<h4 class="mb-0"><b><i class="fas fa-money-bill-wave mr-2"></i>Monthly Payments Report</b></h4>
								</div>
								<div class="card-body">
									<p class="card-text">View detailed monthly payment records and transactions.</p>
								</div>
								<div class="card-footer bg-transparent">
									<a href="index.php?page=payment_report" class="btn btn-outline-primary btn-block d-flex justify-content-between align-items-center">
										<span>View Report</span>
										<i class="fas fa-chevron-circle-right"></i>
									</a>
								</div>
							</div>
						</div>
						
						<div class="col-md-6 mb-4">
							<div class="card border-primary h-100">
								<div class="card-header">
									<h4 class="mb-0"><b><i class="fas fa-file-invoice-dollar mr-2"></i> Balances Report</b></h4>
								</div>
								<div class="card-body">
									<p class="card-text">Review outstanding balances and dues.</p>
								</div>
								<div class="card-footer bg-transparent">
									<a href="index.php?page=balance_report" class="btn btn-outline-primary btn-block d-flex justify-content-between align-items-center">
										<span>View Report</span>
										<i class="fas fa-chevron-circle-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6 mb-4">
							<div class="card border-primary h-100">
								<div class="card-header">
									<h4 class="mb-0"><b><i class="fas fa-chart-line mr-2"></i>Expense Performance Reports</b></h4>
								</div>
								<div class="card-body">
									<p class="card-text">Analyze expense trends and performance metrics.</p>
								</div>
								<div class="card-footer bg-transparent">
									<a href="index.php?page=form_data_entry" class="btn btn-outline-primary btn-block d-flex justify-content-between align-items-center">
										<span>View Report</span>
										<i class="fas fa-chevron-circle-right"></i>
									</a>
								</div>
							</div>
						</div>
						
						        <?//php if($account_type == 2): ?>
						<div class="col-md-6 mb-4">
							<div class="card border-primary h-100">
								<div class="card-header">
									<h4 class="mb-0"><b><i class="fas fa-chart-pie mr-2"></i>Reports per Block</b></h4>
									<small class="text-muted">Detailed Analysis - Biases, Hits / Misses</small>
								</div>
								<div class="card-body">
									<p class="card-text">Detailed block-level analysis reports.</p>
								</div>
								<div class="card-footer bg-transparent">
									<a href="index.php?page=form_data_entry2" class="btn btn-outline-primary btn-block d-flex justify-content-between align-items-center">
										<span>View Report</span>
										<i class="fas fa-chevron-circle-right"></i>
									</a>
								</div>
							</div>
						</div>
						                <?//php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
    body:not(#homepage)::before{
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('images/rent bg.webp');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        filter: blur(8px);
        z-index: -1;
        transform: scale(1.1);
    }

    body:not(#homepage) {
        position: relative;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-header {
		background-color: #0d2074; /* Dark header */
		color: white; /* White text */
	}
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
	.btn-outline-primary{
		color: #0d2074;
	}
	.btn-outline-primary:hover{
		background-color: #0d2074;
		color: white;
	}
</style>
</body>