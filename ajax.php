<?php
// Sanitize and validate input
$action = isset($_GET['action']) ? trim($_GET['action']) : '';

include 'admin_class.php';

include 'collect_data.php';

if (
    isset($_POST['landlordId1']) &&
    isset($_POST['username1']) &&
    isset($_POST['password1'])
) {

    $landlord_id1 = $_POST['landlordId1'];
    $username1 = $_POST['username1'];
    $password1 = $_POST['password1'];

}



$crud = new Action();

try {
    switch ($action) {
        case 'register_landlord':
            $result = $crud->register_landlord(); 
			#$result = $crud->save_user(); 
           break;
            case 'login':
            $result = $crud->login();
            break;
		case 'login_m': 
            $result = $crud->login_m($landlord_id1, $username1, $password1);
            break;
        case 'login2':
            $result = $crud->login2();
            break;
         case 'processUserForm':
            $result = $crud->processUserForm();
            break;    
        case 'logout':
            $result = $crud->logout();
            break;
		case 'monthlyrates':
            $result = $crud->monthlyrates();
            break;	
		case 'logout2':
            $result = $crud->logout2();
            break;		
		case 'houserenting':
            $result = $crud->houserenting();
			$house_id = $_GET['house_id'];
			$tenant_id = $_GET['tenant_id'];
			$house_id = $_GET['house_id'];
			$house_price = $_GET['house_price'];
			header("Location: index.php?page=house_renting&house_id=$house_id&house_price=$house_price&tenant_id=$tenant_id");
            break;	
        case 'logout2':
            $result = $crud->logout2();
            break;
        case 'save_user':
            $result = $crud->save_user();
            break;
        case 'delete_user':
            $result = $crud->delete_user();
            break;
        case 'signup':
            $result = $crud->signup();
            break;
        case 'update_account':
            $result = $crud->update_account();
            break;
        case 'save_settings':
            $result = $crud->save_settings();
            break;
        case 'save_category':
            $result = $crud->save_category();
            break;
		case 'save_payment':
            $result = $crud->save_payment();
            break;	
        case 'delete_category':
            $result = $crud->delete_category();
            break;
        case 'save_house':
            $result = $crud->save_house($conn1,$conn);
            break;
        case 'delete_house':
            $result = $crud->delete_house();
            break;
        case 'save_tenant':
            $result = $crud->save_tenant();
            break;
        case 'delete_tenant':
            $result = $crud->delete_tenant();
            break;
        case 'save_houseprice':
            $result = $crud->save_houseprice();
            break;
        case 'delete_houseprice':
            $result = $crud->delete_houseprice();
            break;
        case 'save_deposit':
            $result = $crud->save_deposit();
            break;        
        case 'get_tdetails':
            $result = $crud->get_tdetails();
            break;
        case 'delete_payment':
            $result = $crud->delete_payment();
            break;
        case 'save_utility':
            $result = $crud->save_utility();  
            break;
        case 'delete_utility':
            $result = $crud->delete_utility();
            break;
		
		case 'manage_generateInvoice':
            $result = $crud->manage_generateInvoice();
            break;
        case 'Invoice_makechanges':
            $result = $crud->Invoice_makechanges();
            break;
            
		default:
            #throw new Exception('Invalid action');
			$result = $crud->houserenting();
			include 'item_invoice.php';
    }

    // Check if the result is not empty or false and echo it
    if (isset($result) && $result !== false) {
        echo $result;
    } else {
        // Optionally handle the case where no result is produced
        echo 'No action performed or result is false.';
    }
} catch (Exception $e) {
    // Handle errors gracefully
    error_log("Error: " . $e->getMessage());
    echo 'An error occurred: ' . htmlspecialchars($e->getMessage());
}
?>
