<?php include 'db_connect.php' ?>
<?php 

$month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m');
$tenant_filter = isset($_GET['tenant_filter']) ? $_GET['tenant_filter'] : '';

$landlord = $conn->query("SELECT business_name FROM landlords WHERE landlord_id = '$landlordid'");
$row33 = $landlord->fetch_assoc();
$compannyname = $row33['business_name'];

// Get list of tenants for filter dropdown
$tenants = $conn->query("SELECT id, CONCAT(firstname, ' ', lastname) as name FROM tenants WHERE shopid = '$landlordid' ORDER BY name");

?>
<style>
    .on-print{ display: none; }
    table {
        background-color: white;
        opacity:1;
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid black;
    }
    th {
        background-color: #f2f2f2;
        color: black;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    tbody {
        color: black;
    }
    #report {
        background-color: white; 
    }
</style>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <form id="filter-report">
                        <div class="row form-group">
                            <label class="control-label col-md-2 text-right">Month of: </label>
                            <input type="month" name="month_of" class='form-control col-md-2' value="<?php echo $month_of ?>">
                            
                            <label class="control-label col-md-2 text-right">Filter Tenant: </label>
                            <select name="tenant_filter" class="form-control col-md-2">
                                <option value="">All Tenants</option>
                                <?php while($tenant = $tenants->fetch_assoc()): ?>
                                    <option value="<?php echo $tenant['id'] ?>" <?php echo $tenant_filter == $tenant['id'] ? 'selected' : '' ?>>
                                        <?php echo $tenant['name'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            
                            <button class="btn btn-sm btn-block btn-primary col-md-2 ml-1">Filter</button>
                        </div>
                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <button class="btn btn-sm btn-block btn-success col-md-2 ml-1 float-right" type="button" id="print">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    
                    <div id="report">
                        <div class="on-print">
                            <p><center><?php echo $compannyname; ?></center></p>
                            <p><center>Rental Payments Report - Dues Only</center></p>
                            <p><center>for the Month of <b><?php echo date('F, Y',strtotime($month_of.'-1')) ?></b></center></p>
                            <?php if(!empty($tenant_filter)): ?>
                                <p><center>Filtered by Tenant</center></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                      <?php
										switch($account_type){
										    case 1:
										        ?>
										        <th>Tenant</th>
										        <?php
										        break;
										        case 2:
										            ?>
										            <th>Buyer</th>
										            <?php
										            break;
										            default:
										                ?>
										                <th>Buyer/Tenant</th>
										                <?php
										}
										?>
											<?php
										switch($account_type){
										    case 1:
										        ?>
										        <th>House  No</th>
										        <?php
										        break;
										        case 2:
										            ?>
										            <th>Plot No</th>
										            <?php
										            break;
										            default:
										                ?>
										                <th>House/Plot</th>
										                <?php
										}
										?>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $tamount = 0;
                                    $total_balance = 0;
                                    
                                    // Get wallet balances
                                    $wallet_balances = [];
                                    $wallet_query = "SELECT tenant_id,Trans_date, SUM(amount) as wallet_amount FROM wallet";
                                    if(!empty($tenant_filter)) {
                                        $wallet_query .= " WHERE tenant_id = '$tenant_filter'";
                                    }
                                    $wallet_query .= " GROUP BY tenant_id";
                                    
                                    $query_wallets = $conn->query($wallet_query);
                                    while ($wallet = $query_wallets->fetch_assoc()) {
                                        $wallet_balances[$wallet['tenant_id']] = $wallet['wallet_amount'];
                                    }
                                    
                                    // Get payments
                                    $payment_query = "SELECT p.id AS payment_id, p.tenant_id, p.amount, p.InvoiceType, 
                                        p.Year, p.Month, p.pay_status as status, p.date_created, 
                                        p.pay_status, p.House_id as houseid 
                                        FROM payments p 
                                        WHERE date_format(p.date_created, '%Y-%m') = '$month_of' 
                                        AND p.shopid = '$landlordid' AND p.pay_status = 0";
                                        
                                    if(!empty($tenant_filter)) {
                                        $payment_query .= " AND p.tenant_id = '$tenant_filter'";
                                    }
                                    
                                    $payment_query .= " ORDER BY unix_timestamp(p.date_created) ASC";
                                    
                                    $payments = $conn->query($payment_query);
                                    
                                    if($payments->num_rows > 0):
                                        while($row = $payments->fetch_assoc()):
                                            $tamount += $row['amount'];
                                            $tenant_id = $row['tenant_id'];
                                            $total_due = (float)$row['amount'];
                                            $wallet_amount = isset($wallet_balances[$tenant_id]) ? (float)$wallet_balances[$tenant_id] : 0;
                                            $difference = $total_due - $wallet_amount;
                                            $total_balance += $difference;
                                    ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['date_created'])) ?></td>
                                        <td>
                                            <?php
                                                $tenantid = $row['tenant_id'];
                                                $tenant22 = $conn->query("SELECT CONCAT(firstname, ' ', lastname) AS name 
                                                    FROM tenants WHERE id = '$tenantid'");
                                                $tenant = $tenant22->fetch_assoc();
                                                echo ucwords($tenant['name']);
                                            ?>
                                        </td>
                                        <td><?php echo $row['houseid'] ?></td>
                                        <td><?php echo $row['InvoiceType'] ?></td>
                                        <td class="text-right"><?php echo number_format($row['amount'], 0) ?></td>
                                        <td class="text-right"><?php echo number_format(max($difference, 0), 0) ?></td>
                                        <td><?php echo $row['Month'] ?></td>
                                        <td><?php echo $row['Year'] ?></td>
                                        <td><?php echo $row['date_created'] ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <th colspan="10"><center>No Data Found</center></th>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5">Total Amount Dues:</th>
                                        <th class="text-right"><?php echo number_format($tamount, 0) ?></th>
                                        <th class="text-right"><?php echo number_format($total_balance, 0) ?></th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
        </tbody>
    </table>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#print').click(function(){
        var _style = $('noscript').clone();
        var _content = $('#report').clone();
        var nw = window.open("", "_blank", "width=800,height=700");
        nw.document.write(_style.html());
        nw.document.write(_content.html());
        nw.document.close();
        nw.print();
        setTimeout(function(){ nw.close(); }, 500);
    });
    
    $('#filter-report').submit(function(e){
        e.preventDefault();
        location.href = 'index.php?page=balance_report&' + $(this).serialize();
    });
});
</script>