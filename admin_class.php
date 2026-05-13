<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("authenticate.php");

require_once('class.phpmailer.php');

include("class.smtp.php"); // Optional, only needed if not already loaded in PHPMailer


class Action {

	private $db;

	public function __construct() {
		ob_start();
		include 'db_connect.php';
		$this->db = $conn;
	}
	
	function __destruct() {
		$this->db->close();
		ob_end_flush();
	}


function login_m($landlord_id1, $username1, $password1) {

    header('Content-Type: application/json');

    // STEP 1: Get user type safely
    $users_details = $this->db->query("
        SELECT type 
        FROM users 
        WHERE landlord_id = '" . $this->db->real_escape_string($landlord_id1) . "'
        AND username = '" . $this->db->real_escape_string($username1) . "'
        LIMIT 1
    ");

    if ($users_details->num_rows == 0) {
        echo json_encode([
            "status" => "error",
            "message" => "User not found"
        ]);
        exit;
    }

    $row133 = $users_details->fetch_assoc();
    $type = $row133['type'];

    // STEP 2: Main login query
    $qry = $this->db->query("
        SELECT * 
        FROM users 
        INNER JOIN landlords 
        ON users.landlord_id = landlords.landlord_id 
        WHERE users.username = '" . $this->db->real_escape_string($username1) . "' 
        AND users.password = '" . md5($password1) . "' 
        AND users.type = '" . $this->db->real_escape_string($type) . "' 
        AND landlords.date_of_validity > CURRENT_DATE() 
        AND landlords.status = 'active' 
        AND users.landlord_id = '" . $this->db->real_escape_string($landlord_id1) . "'
        LIMIT 1
    ");

    if ($qry->num_rows > 0) {

        $userData = $qry->fetch_assoc();

        // Store session safely
        foreach ($userData as $key => $value) {
            if ($key != 'password') {
                $_SESSION['login_' . $key] = $value;
            }
        }

        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "landlord_id" => $userData['landlord_id'],
                "username" => $userData['username'],
                "type" => $userData['type']
            ]
        ]);
        exit;

    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid credentials or account expired"
        ]);
        exit;
    }
}


function login() {
    extract($_POST);
	
	$users_details = $this->db->query("SELECT type FROM users WHERE landlord_id = '" . $this->db->real_escape_string($landlord_id) . "' AND username = '" . $this->db->real_escape_string($username) . "'");

    $row133 = $users_details->fetch_assoc();  // Fetch user details as an associative array
    $type = $row133['type'];


    // Corrected SQL query with proper escaping, conversion, and conditions
    $qry = $this->db->query("SELECT * 
                             FROM users 
                             INNER JOIN landlords 
                             ON CONVERT(users.landlord_id USING utf8mb4) = CONVERT(landlords.landlord_id USING utf8mb4) 
                             WHERE CONVERT(users.username USING utf8mb4) = CONVERT('" . $this->db->real_escape_string($username) . "' USING utf8mb4) 
                               AND CONVERT(users.password USING utf8mb4) = CONVERT('" . md5($password) . "' USING utf8mb4) 
                               AND users.type = '$type' 
                               AND landlords.date_of_validity > CURRENT_DATE() 
                               AND CONVERT(landlords.status USING utf8mb4) = 'active' 
                               AND users.landlord_id = '" . $this->db->real_escape_string($landlord_id) . "'");

    if ($qry->num_rows > 0) {
        $userData = $qry->fetch_array();
        
        // Store user data in session, except for the password and numeric keys
        foreach ($userData as $key => $value) {
            if ($key != 'password' && !is_numeric($key)) {
                $_SESSION['login_' . $key] = $value;
            }
        }

        // Add username, password, and landlord_id to session variables
        $_SESSION['login_username'] = $username;
        $_SESSION['login_password'] = $password;
        $_SESSION['login_landlordid'] = $landlord_id;

        return 1; // Successful login
    } else {
        return 2; // Invalid credentials
    }
}

function processUserForm() {

         $landlordid = $_SESSION['login_landlordid']; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Debug: print POST data to verify
        #echo "Form data: <pre>" . print_r($_POST, true) . "</pre><br>";
        extract($_POST);
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
        $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
        $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
        $type = isset($_POST['type']) ? intval($_POST['type']) : 1; // Default to Admin

         // Checkboxes with default values if not checked
		$addHouse = isset($_POST['add_house']) ? $_POST['add_house'] : 0;
	$enter_tenant = isset($_POST['enter_tenant']) ? $_POST['enter_tenant'] : 0;
	$viewInvoices = isset($_POST['view_invoices']) ? $_POST['view_invoices'] : 0;
	$manageInvoices = isset($_POST['manage_invoices']) ? $_POST['manage_invoices'] : 0;
	$manageTenantWallant = isset($_POST['manage_wallet']) ? $_POST['manage_wallet'] : 0;
	$manageUtilityBills = isset($_POST['manage_bills']) ? $_POST['manage_bills'] : 0;
	$houseOverviewReport = isset($_POST['house_report']) ? $_POST['house_report'] : 0;
	$monthlyReports = isset($_POST['monthly_reports']) ? $_POST['monthly_reports'] : 0;
	$financialReports = isset($_POST['financial_reports']) ? $_POST['financial_reports'] : 0;
	$view_statements = isset($_POST['view_statements']) ? $_POST['view_statements'] : 0;

        // Optional password handling
        if (empty($password)) {
            $password = null; // Do not update the password
        } else {
            // Hash password if provided (Consider using password_hash() for stronger security)
            $password = md5($password);
        }

        // Prepare SQL query (update or insert based on id)
       $qry11 = $this->db->query("SELECT * FROM users WHERE username = '".$this->db->real_escape_string($username)."' AND
    landlord_id = '".$this->db->real_escape_string($landlordid)."'");

if ($qry11->num_rows > 0) {
            #echo "Updating user with ID $id<br>";
            // Update existing user (Correct landlord_id to use variable $id instead of fixed '1')
            $sql = "UPDATE users SET 
                        password = '$password', 
                        type = '$type', 
                        add_house = '$addHouse',
						enter_tenant = '$enter_tenant', 
						view_invoices = '$viewInvoices', 
                        manage_invoices = '$manageInvoices', 
                        manage_tenant_wallant = '$manageTenantWallant', 
                        manage_utility_bills = '$manageUtilityBills', 
                        house_overview_report = '$houseOverviewReport', 
                        monthly_reports = '$monthlyReports', 
                        financial_reports = '$financialReports',
						view_statements = '$view_statements',
						password = '$password'
                    WHERE landlord_id = '$landlordid' AND name = '$name' AND username = '$username'";  // Changed fixed '1' to $id
        } else {
            #echo "Inserting new user<br>";
            // Insert new user (Remove $id from values if landlord_id is auto-incremented)
            $sql = "INSERT INTO users (name, username, password, type,landlord_id, add_house,enter_tenant, view_invoices, manage_invoices, manage_tenant_wallant, manage_utility_bills, house_overview_report, monthly_reports, financial_reports,view_statements) 
                    VALUES ('$name', '$username', '$password', '$type','$landlordid','$addHouse','$enter_tenant','$viewInvoices', '$manageInvoices', '$manageTenantWallant', '$manageUtilityBills', '$houseOverviewReport', '$monthlyReports', '$financialReports','$view_statements')";
        }

        // Debugging the SQL query
        #echo "SQL Query: $sql<br>";

        // Execute query and check success or failure
        if ($this->db->query($sql) === TRUE) {
            return 1; // Successful execution
        } else {
            return 2; // Failure (Assumed error)
        }
    } else {
        #echo "Form not submitted correctly.<br>";
    }
}


	function login2(){
		extract($_POST);
		if(isset($email))
			$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$this->db->real_escape_string($username)."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			$userData = $qry->fetch_array();
			foreach ($userData as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					$bioData = $bio->fetch_array();
					foreach ($bioData as $key => $value) {
						if($key != 'password' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
			}
			return 1;
		}else{
			return 3;
		}
	}

	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
			$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		
		if($type == 1) {
			return 2;
		} else {
			$chk = $this->db->query("Select * from users where username = '$username' and type = '$type'")->num_rows;
			if(!empty($chk)){
				return 2;
			} else {
				$save = $this->db->query("INSERT INTO users set ".$data);
				return 1;
			}
		}
	}

	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

	function signup(){
		extract($_POST);
		$data = " name = '".$this->db->real_escape_string($firstname.' '.$lastname)."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '".$this->db->real_escape_string($v)."' ";
				else
					$data .= ", $k = '".$this->db->real_escape_string($v)."' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
				$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($save_alumni){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
					return 1;
			}
		}
	}

	function update_account(){
		// To be implemented
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".$this->db->real_escape_string($name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".$this->db->real_escape_string(htmlentities($about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";
		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if(!is_numeric($key))
					$_SESSION['system'][$key] = $value;
			}
			return 1;
		}
	}

	function save_category() {
	extract($_POST);
	
	$landlordid = $_SESSION['login_landlordid']; 

	// Escaping the name to avoid SQL injection
	$safe_name = $this->db->real_escape_string($name);
    $safe_cprice = $this->db->real_escape_string($cprice);
	$safe_landlordid = $this->db->real_escape_string($landlordid);

	// Checking if the category already exists
	$check_query = $this->db->query("SELECT id FROM categories WHERE name = '$safe_name' AND cprice = '$safe_cprice' AND shopid = '$safe_landlordid'");

	if ($check_query->num_rows > 0) {
		// If category exists, update it
		$update_query = $this->db->query("UPDATE categories SET name = '$safe_name', cprice = '$safe_cprice' WHERE name = '$safe_name' AND shopid = '$safe_landlordid'");
		if ($update_query) {
			return 1;
		} else {
			return 0;
		}
	} else {
		// If category doesn't exist, insert it
		$insert_query = $this->db->query("INSERT INTO categories (name, cprice, shopid) VALUES ('$safe_name', '$safe_cprice', '$safe_landlordid')");
		if ($insert_query) {
			return 1;
		} else {
			return 0;
		}
	}
}


	function delete_category(){
		extract($_POST);
	$landlordid = $_SESSION['login_landlordid']; 
	$safe_landlordid = $this->db->real_escape_string($landlordid);
		$delete = $this->db->query("DELETE FROM categories where shopid ='$safe_landlordid' AND id = ".$id);
		if($delete){
			return 1;
		}
	}
	
	
	function houserenting() {
    extract($_POST);

    $landlordid = $_SESSION['login_landlordid']; 
	$house_id = $_GET['house_id'];
	$tenant_id = $_GET['tenant_id'];
	$house_id = $_GET['house_id'];
	$house_price = $_GET['house_price'];
	
	

    // Escape inputs
    $safe_landlordid = $this->db->real_escape_string($landlordid);
    $safe_house_id = $this->db->real_escape_string($house_id);
    $safe_tenant_id = $this->db->real_escape_string($tenant_id);
    $safe_house_price = $this->db->real_escape_string($house_price);
    $today = date("Y-m-d");

    // Check if house is already rented but still inactive (status = 0)
    $check1 = $this->db->query("SELECT * FROM house_renting 
                               WHERE house_id = '$safe_house_id' 
                               AND shopid = '$safe_landlordid'");

    if ($check1->num_rows > 0) {
	
        // Update existing rental record
        $update = $this->db->query("UPDATE house_renting 
                                    SET tenant_id = '$safe_tenant_id', 
                                        date_in = '$today', 
                                        status = '1' 
                                    WHERE house_id = '$safe_house_id' 
                                    AND shopid = '$safe_landlordid'");

        $update_house = $this->db->query("UPDATE houses 
                                          SET status = '1' 
                                          WHERE house_no = '$safe_house_id' 
                                          AND shopid = '$safe_landlordid'");

        return ($update && $update_house) ? 1 : 0;
    } else {
        // Insert new rental record
        $insert = $this->db->query("INSERT INTO house_renting 
                                    (tenant_id, house_id, date_in, status, shopid, price) 
                                    VALUES 
                                    ('$safe_tenant_id', '$safe_house_id', '$today', '1', '$safe_landlordid', '$safe_house_price')");

        $update_house1 = $this->db->query("UPDATE houses 
                                          SET status = '1' 
                                          WHERE house_no = '$safe_house_id' 
                                          AND shopid = '$safe_landlordid'");

        return ($insert && $update_house1) ? 1 : 0;
    }
}



	/*function save_house(){
    extract($_POST);
    $landlordid = $_SESSION['login_landlordid']; 
    $safe_landlordid = $this->db->real_escape_string($landlordid);

    // Escaping user input for security
    $safe_house_no = $this->db->real_escape_string($house_no);
    $safe_description = $this->db->real_escape_string($description);
    $safe_category_id = $this->db->real_escape_string($category_id);
    $safe_status = $this->db->real_escape_string($status);

    // Creating the data string with escaped values
    $data = " house_no = '$safe_house_no' ";
    $data .= ", description = '$safe_description' ";
    $data .= ", category_id = '$safe_category_id' ";
    $data .= ", shopid = '$safe_landlordid' ";
    $data .= ", status = '$safe_status' ";

    // Check if house already exists
    $chk = $this->db->query("SELECT * FROM houses WHERE house_no = '$safe_house_no' AND shopid = '$safe_landlordid'")->num_rows;

    if($chk > 0) {
        // Update existing house
        $save = $this->db->query("UPDATE houses SET status = '$safe_status',description ='$safe_description' WHERE shopid = '$safe_landlordid' AND house_no = '$safe_house_no'");
        return 2;
    } else {
        // Insert new house
        $save = $this->db->query("INSERT INTO houses SET $data");
        return 1;
    }
}*/
function save_house($conn1,$conn) {
    extract($_POST);
    $landlordid = $_SESSION['login_landlordid']; 
    $safe_landlordid = $this->db->real_escape_string($landlordid);
    
    // Escaping user input for security
    $safe_house_no = $this->db->real_escape_string($house_no);
    $safe_description = $this->db->real_escape_string($description);
    $safe_category_id = $this->db->real_escape_string($category_id);
    $safe_status = $this->db->real_escape_string($status);

    $image_path = '';

    // Handle file upload if present
    if (isset($_FILES['unit_img']) && $_FILES['unit_img']['error'] == 0) {
        $fileTmpPath = $_FILES['unit_img']['tmp_name'];
        $fileName = $_FILES['unit_img']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate allowed file types (JPG, JPEG, PNG)
        $allowedExtensions = array('jpg', 'jpeg', 'png');
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = 'uploads/house_images/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    return "Failed to create upload directory.";
                }
            }

            // Generate a unique file name
            $newFileName = $safe_house_no . '.' . $fileExtension;
            $image_path = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $image_path)) {
                return "Error uploading file.";
            }
        } else {
            return "Invalid file type.";
        }
    }

    // Create the data string with escaped values
    $data = " house_no = '$safe_house_no', description = '$safe_description', category_id = '$safe_category_id', shopid = '$safe_landlordid', status = '$safe_status'";

    if (!empty($image_path)) {
        $data .= ", unit_img = '$newFileName'";
    }

    // Count allowed records for this landlord
    $sql2221 = "SELECT no_of_records FROM landlords WHERE landlord_id = '$safe_landlordid'";
    $no_of_records1 = $conn1->query_database_many_rows($conn, $sql2221);

    $no_of_records = $no_of_records1['no_of_records'];
   
    // Count current number of houses
    $nosofhouses = "SELECT COUNT(*) AS total1 FROM houses WHERE shopid = '$safe_landlordid'";
    $nosofhouses1 = $conn1->query_database_many_rows($conn, $nosofhouses);
	
    $nosofhouses2 = $nosofhouses1['total1'];
    
    // Check if house already exists
    $chk_query = "SELECT * FROM houses WHERE house_no = '$safe_house_no' AND shopid = '$safe_landlordid'";
    $chk = $this->db->query($chk_query);

    if ($chk === false) {
        return "Error in select query: " . $this->db->error;
    }

    if ($chk->num_rows > 0) {
        // Update existing house
        $update_query = "UPDATE houses SET description = '$safe_description', status = '$safe_status'";
        if (!empty($image_path)) {
            $update_query .= ", unit_img = '$newFileName'";
        }
        $update_query .= " WHERE shopid = '$safe_landlordid' AND house_no = '$safe_house_no'";
        $save = $this->db->query($update_query);

        if ($save === false) {
            return "Error updating house: " . $this->db->error;
        }
        return 2; // House updated
    } else {
        // Insert new house only if limit not exceeded
        if ($nosofhouses2 < $no_of_records) {
            $insert_query = "INSERT INTO houses SET $data";
            $save = $this->db->query($insert_query);

            if ($save === false) {
                return "Error inserting new house: " . $this->db->error;
            }
            return 1; // New house inserted
        } else {
            return "Limit exceeded: Only $no_of_records / $nosofhouses2 houses allowed, to enter more records contact support at 0757875053";
        }
    }
}


	function delete_house(){
		extract($_POST);
		$landlordid = $_SESSION['login_landlordid']; 
		$safe_landlordid = $this->db->real_escape_string($landlordid);
		$delete = $this->db->query("DELETE FROM houses where shopid ='$safe_landlordid' AND id = ".$id);
		if($delete){
			return 1;
		}
	}
function save_tenant() {
    // Access POST data directly
    $landlordid = $_SESSION['login_landlordid']; 
    $safe_landlordid = $this->db->real_escape_string($landlordid);
    
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $house_id = $_POST['house_id'] ?? ''; 
    $tenantid = $_POST['tenantid'] ?? '';
    $id_card = $_POST['id_card'] ?? '';
    $id_number = $_POST['id_number'] ?? ''; 
    $date_created = date('Y-m-d H:i:s'); // Set date_created to the current date and time
    
    // File upload handling (National ID document)
    $document_path = '';
    if (isset($_FILES['id_document']) && $_FILES['id_document']['error'] == 0) {
        $fileTmpPath = $_FILES['id_document']['tmp_name'];
        $fileName = $_FILES['id_document']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        // Validate allowed file types (PDF, JPG, PNG)
        $allowedExtensions = array('pdf', 'jpg', 'jpeg', 'png');
        if (in_array($fileExtension, $allowedExtensions)) {
            // Define upload directory and generate unique file name
            $uploadDir = 'uploads/national_ids/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);  // Create directory if it doesn't exist
            }
            // Use tenantid as the file name
            $newFileName = $tenantid . '.' . $fileExtension;
            $document_path = $uploadDir . $newFileName;

            // Move the uploaded file to the destination
            if (!move_uploaded_file($fileTmpPath, $document_path)) {
                die("Error uploading file.");
            }
        } else {
            die("Invalid file type. Only PDF, JPG, JPEG, and PNG are allowed.");
        }
    }

    // Check if tenant exists
    $chk = $this->db->query("SELECT 1 FROM tenants WHERE id = '$tenantid' AND shopid = '$safe_landlordid'");

    if ($chk->num_rows > 0) {
        // Update existing tenant
        $updateQuery = $this->db->prepare("UPDATE tenants SET firstname = ?, lastname = ?, middlename = ?, email = ?, 
            contact = ?, id_card = ?, id_number = ?, id_document = ? 
            WHERE shopid = ? AND id = ?");
        $updateQuery->bind_param("ssssssssss", $firstname, $lastname, $middlename, $email, $contact, $id_card, $id_number, $newFileName, $safe_landlordid, $tenantid);
        if (!$updateQuery->execute()) {
            die("Error updating tenant: " . $this->db->error);
        }

        // If update is successful, return 1
        return 1;  
    } else {
        // Insert new tenant
        $insertQuery = $this->db->prepare("INSERT INTO tenants (id, firstname, lastname, middlename, email, contact, id_card, id_number, id_document, shopid) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertQuery->bind_param("ssssssssss", $tenantid, $firstname, $lastname, $middlename, $email, $contact, $id_card, $id_number, $newFileName, $safe_landlordid);
        if (!$insertQuery->execute()) {
            die("Error inserting tenant: " . $this->db->error);
        }

        // Insert house renting record
        $insertRentingQuery = $this->db->prepare("INSERT INTO house_renting (tenant_id, house_id, date_in, shopid) 
            VALUES (?, ?, ?, ?)");
        $insertRentingQuery->bind_param("ssss", $tenantid, $house_id, $date_created, $safe_landlordid);
        if (!$insertRentingQuery->execute()) {
            die("Error inserting house renting record: " . $this->db->error);
        }

        // Update house status
        $updateHouseQuery = $this->db->prepare("UPDATE houses SET status = '0' WHERE shopid = ? AND house_no = ?");
        $updateHouseQuery->bind_param("ss", $safe_landlordid, $house_id);
        if (!$updateHouseQuery->execute()) {
            die("Error updating house status: " . $this->db->error);
        }

        // Return 1 after successful insertions
        return 1;  
    }
}



	function get_tdetails(){
		extract($_POST);
		$data =array();
		$tenants =$this->db->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price,p.invoice  FROM tenants t inner join houses h on h.id = t.house_id LEFT JOIN payments p ON p.tenant_id = t.id where t.id = {$id} ");
		$tenantData = $tenants->fetch_array();
		foreach($tenantData as $k => $v){
			if(!is_numeric($k)){
				$$k = $v;
			}
		}
		$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
		$months = floor(($months) / (30*60*60*24));
		$data['months'] = $months;
		$payable= abs($price * $months);
		$data['payable'] = number_format($payable,2);
		$paid = $this->db->query("SELECT SUM(amount) as paid FROM payments where id != '$pid' and tenant_id =".$id);
		$last_payment = $this->db->query("SELECT * FROM payments where id != '$pid' and tenant_id =".$id." order by unix_timestamp(date_created) desc limit 1");
		$paidData = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
		$data['paid'] = number_format($paidData,2);
		$data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
		$data['outstanding'] = number_format($payable - $paidData,2);
		$data['price'] = number_format($price,2);
		$data['name'] = ucwords($name);
		$data['rent_started'] = date('M d, Y',strtotime($date_in));
	
		return json_encode($data);
	}

	function save_payment() {
    extract($_POST);
    $date_created = date('Y-m-d H:i:s'); // Set date_created to the current date and time
	
	$landlordid = $_SESSION['login_landlordid']; 
	
	$safe_landlordid = $this->db->real_escape_string($landlordid);

    // Concatenate data fields correctly
    $data = " tenant_id = '$tenant_id', ";
    $data .= " amount = '$amount', ";
    //$data .= " invoice = '$invoice', ";
    $data .= " date_created = '$date_created', ";
	
	$data .= " shopid = '$safe_landlordid', ";
	
	
 

    if(empty($id)){
        $save = $this->db->query("INSERT INTO payments SET $data");
        $id = $this->db->insert_id;
    } else {
        $save = $this->db->query("UPDATE payments SET $data WHERE shopid = '$safe_landlordid' AND Sid = $id");
    }

    if($save){
        return 1;
    } else {
        return 0; // Indicate failure
    }
}


	function delete_payment(){
		extract($_POST);
		$landlordid = $_SESSION['login_landlordid']; 
		$safe_landlordid = $this->db->real_escape_string($landlordid);
		$delete = $this->db->query("DELETE FROM payments where shopid = '$safe_landlordid' AND id = ".$id);
		if($delete){
			return 1;
		}
	}

	function save_utility(){
    extract($_POST);
    $data = "name = '$name'";
    $data .= ", amount = '$amount'";
    
    $landlordid = $_SESSION['login_landlordid']; 
    
    $date_created = date('Y-m-d H:i:s'); // Set date_created to the current date and time
    
    $data .= ", created_on = '$date_created'";
    
    $safe_landlordid = $this->db->real_escape_string($landlordid);
    
    $data .= ", shopid = '$safe_landlordid'";
    
    $check = $this->db->query("SELECT * FROM utility_bill_categories 
        WHERE name = '$name' AND shopid = '$safe_landlordid'");
        
    if($check->num_rows > 0){
        $save = $this->db->query("UPDATE utility_bill_categories SET $data WHERE shopid = '$safe_landlordid' AND name = '$name'");
        return 2; // Duplicate found
    } else {
        $save = $this->db->query("INSERT INTO utility_bill_categories SET $data");
        return 1;
    }
}

	

// Delete utility
	function delete_utility(){
		extract($_POST);
		$landlordid = $_SESSION['login_landlordid']; 
	
		$safe_landlordid = $this->db->real_escape_string($landlordid);
		
		$delete = $this->db->query("DELETE FROM utility_bill_categories  where shopid ='$safe_landlordid' AND id = ".$id);
		if($delete){
			return 1;
		}
	}


		////////////RECHOZ CODE///////
	function save_houseprice() {
    // Extract the POST data
    extract($_POST);
    $house_id = $_POST['house_id'] ?? '';
    $months = $_POST['month'] ?? []; // Array of months
    $year = $_POST['year'] ?? ''; 
    $price = $_POST['price'] ?? '';

    // Safely escape the input values to prevent SQL injection
    $safe_house_id = $this->db->real_escape_string($house_id);
    $safe_year = $this->db->real_escape_string($year);
    $safe_price = $this->db->real_escape_string($price);
	
	$landlordid = $_SESSION['login_landlordid']; 
	
	$safe_landlordid = $this->db->real_escape_string($landlordid);
	
	

    // Loop through the selected months to handle each one individually
    foreach ($months as $month) {
        $safe_month = $this->db->real_escape_string($month);

        // Check if a price entry for the same house, month, and year already exists
        $check_query = $this->db->query("SELECT id FROM house_data WHERE house_id = '$safe_house_id' AND month = '$safe_month' AND year = '$safe_year' AND shopid ='$safe_landlordid'");

        if ($check_query->num_rows > 0) {
            // If the record exists, update it
            $update_query = $this->db->query("UPDATE house_data SET price = '$safe_price' WHERE house_id = '$safe_house_id' AND month = '$safe_month' AND year = '$safe_year' AND shopid ='$safe_landlordid'");

            if (!$update_query) {
                return 0; // Failure on update
            }
        } else {
            // If the record does not exist, insert a new record
            $insert_query = $this->db->query("INSERT INTO house_data (house_id, month, year, price,shopid) VALUES ('$safe_house_id', '$safe_month', '$safe_year', '$safe_price','$safe_landlordid')");

            if (!$insert_query) {
                return 0; // Failure on insert
            }
        }
    }

    return 1; //
}
	
function Invoice_makechanges() {
    // Extract the POST data
    extract($_POST);
    $invoice_type = $_POST['invoice_type'] ?? '';  
    $tenant_id = $_POST['tenant_id'] ?? '';
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';
    $transaction_type = $_POST['transaction_type'] ?? ''; 
    $pid = $_POST['pid'] ?? ''; 
    $houseid = $_POST['houseid'] ?? ''; 
    $amount = $_POST['amount'] ?? ''; 
    
    // Safely escape the input values to prevent SQL injection
    $safe_tenant_id = $this->db->real_escape_string($tenant_id);
    $safe_month = $this->db->real_escape_string($month);
    $safe_year = $this->db->real_escape_string($year);
    $safe_invoice_type = $this->db->real_escape_string($invoice_type);
    $date_created = date('Y-m-d H:i:s'); // Set date_created to the current date and time
    
    $landlordid = $_SESSION['login_landlordid']; 
    $safe_landlordid = $this->db->real_escape_string($landlordid);
    $safe_pid = $this->db->real_escape_string($pid);
    
    if ($safe_invoice_type == 'Utility_Bills') {
        $invoice_type = "Monthly Plus Utility Bills";
        include 'utilityBills.php';
		
		return 1; // Success
    } else {
        $invoice_type = "Monthly Bills Only";

    // Check if a price entry for the same house, month, and year already exists
    $check_query = $this->db->query("SELECT id FROM payments WHERE tenant_id = '$safe_tenant_id' AND month = '$safe_month' AND year = '$safe_year' 
        AND InvoiceType = '$safe_invoice_type' AND shopid ='$safe_landlordid' AND id ='$safe_pid'");

    if ($check_query->num_rows > 0) {
        // If the record exists, update it
        // Check if money exists in the customer's wallet
        $wallet_amount = $this->db->query("SELECT SUM(amount) AS total_amount FROM wallet WHERE tenant_id = '$safe_tenant_id' AND shopid ='$safe_landlordid'");

        if ($wallet_amount->num_rows > 0) {
            $row1 = $wallet_amount->fetch_assoc();
            $wallet_balance = $row1['total_amount'];

            if ($wallet_balance >= $amount) {
                // Update the existing payment record
                $update_query = $this->db->query("UPDATE payments SET amount = '$amount', pay_status = '1',House_id = '$houseid' 
                    WHERE tenant_id = '$safe_tenant_id' AND month = '$safe_month' AND year = '$safe_year' 
                    AND InvoiceType = '$safe_invoice_type' AND shopid = '$safe_landlordid' AND id = '$safe_pid'");

                // Debit the account in the wallet
                $insert_wallet_query = $this->db->query("INSERT INTO wallet (tenant_id, transaction_id, amount, paid_by, Mode_pay, Trans_date, shopid) 
                    VALUES ('$safe_tenant_id', '$safe_invoice_type', '-$amount', '$safe_month $safe_year', 'Wallet for $houseid','$date_created', '$safe_landlordid')");

                if ($update_query && $insert_wallet_query) {
                    return 1; // Success
                } else {
                    return 0; // Failure
                }
            }
        }
    }
    
    return 0; // Default return in case conditions are not met
}
}

function manage_generateInvoice() {
    // Extract the POST data
    extract($_POST);
    $invoice_type = $_POST['invoice_type'] ?? '';  
    $tenant_id = $_POST['tenant_id'] ?? '';
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';
    $transaction_type = $_POST['transaction_type'] ?? ''; 
    
    // Safely escape the input values to prevent SQL injection
    $safe_tenant_id = $this->db->real_escape_string($tenant_id);
    $safe_month = $this->db->real_escape_string($month);
    $safe_year = $this->db->real_escape_string($year);
    $safe_invoice_type = $this->db->real_escape_string($invoice_type);
    $date_created = date('Y-m-d H:i:s'); // Set date_created to the current date and time
    
    $landlordid = $_SESSION['login_landlordid']; 
    $safe_landlordid = $this->db->real_escape_string($landlordid);
	
	 // Fetch house_id based on tenant_id
    $IDD = $this->db->query("SELECT house_id FROM house_renting WHERE shopid ='$safe_landlordid' AND tenant_id = '$safe_tenant_id' AND Status =1");
    
    $row55 = $IDD->fetch_assoc();
    
	$houseid = $row55['house_id'];  // Use $row55 here instead of $row1
    
    if($safe_invoice_type == 'Utility_Bills') {
        $invoice_type = "Monthly Plus Utility Bills";
        include 'utilityBills.php';
		return 1;
    } else {
        $invoice_type = "Monthly Bills Only";
      
        // Getting price of renting a house using the house id, month, and year
        $priceQuery = $this->db->query("SELECT price FROM house_data WHERE shopid ='$safe_landlordid' AND house_id = '$houseid' AND month = '$safe_month' AND year = '$safe_year'");
        
        if ($priceQuery && $priceQuery->num_rows > 0) {
            $row1 = $priceQuery->fetch_assoc();
            $amount = $row1['price'];
        } else {
            $amount = 0; // Set a default value if no price is found
        }
        
        // Check if a price entry for the same house, month, and year already exists
        $check_query = $this->db->query("SELECT id FROM payments WHERE tenant_id = '$safe_tenant_id' AND month = '$safe_month' AND year = '$safe_year' 
        AND InvoiceType = '$safe_invoice_type' AND shopid ='$safe_landlordid'");
        
        if ($check_query->num_rows > 0) {
            // If the record exists, update it
            $update_query = $this->db->query("UPDATE payments SET amount = '$amount' WHERE tenant_id = '$safe_tenant_id' AND month = '$safe_month' AND year = '$safe_year' 
            AND InvoiceType = '$safe_invoice_type' AND shopid ='$safe_landlordid' AND House_id ='$houseid'");
            
            if ($update_query) {
                return 1; // Success
            } else {
                return 0; // Failure
            }
        } else {
            // Check if money exists in the customer's wallet
            $wallet_amount = $this->db->query("SELECT SUM(amount) AS total_amount FROM wallet WHERE tenant_id = '$safe_tenant_id' AND shopid ='$safe_landlordid'");

            if ($wallet_amount && $wallet_amount->num_rows > 0) {
                $row1 = $wallet_amount->fetch_assoc();
                $wallet_balance = $row1['total_amount'];
				
				 $landlorddetails = $this->db->query("SELECT * FROM landlords WHERE landlord_id = '$safe_landlordid'");
						 
				 $landlord_tenant = $this->db->query("SELECT email, CONCAT(firstname, ' ', middlename, ' ', lastname) AS name FROM tenants WHERE shopid = '$safe_landlordid' AND id ='$safe_tenant_id'");
						 
				 $row133 =  $landlorddetails->fetch_assoc();
						 
				 $business_name = $row133['business_name'];
						 
				 $contact_person = $row133['contact_person'];
						 
				 $pemail_address = $row133['email_address'];
						 
				 $telephone_contact = $row133['telephone_contact'];
						 
				 $row134 = $landlord_tenant->fetch_assoc();
						  
				 $tenant_name = $row134['name'];
						  
				 $email = $row134['email'];
        
                if ($wallet_balance >= $amount) {
                    // Insert a new payment record

                    $insert_query = $this->db->query("INSERT INTO payments (tenant_id, amount, InvoiceType, Year, Month, House_id, date_created, pay_status, shopid) 
                    VALUES ('$safe_tenant_id', '$amount', '$safe_invoice_type', '$safe_year', '$safe_month','$houseid','$date_created', '1','$safe_landlordid')");
            
                    // Debit the account in the wallet
                    $insert_wallet_query = $this->db->query("INSERT INTO wallet (tenant_id, transaction_id, amount, paid_by, Mode_pay, Trans_date, shopid) 
                    VALUES ('$safe_tenant_id', '$safe_invoice_type', '-$amount', '$safe_month $safe_year', 'Wallet for $houseid','$date_created','$safe_landlordid')");
                    
                    if ($insert_query && $insert_wallet_query) {
                        
						$status = 'Paid';
						 
						$this->send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $houseid, $safe_month, $safe_year);
						
						 return 1; // Success
	   
                    } else {
                        return 0; // Failure
                    }
                }
				else {
				  $status = 'Not Paid';
				
				  $insert_query3 = $this->db->query("INSERT INTO payments (tenant_id, amount, InvoiceType, Year, Month, House_id, date_created, pay_status, shopid) 
                    VALUES ('$safe_tenant_id', '$amount', '$safe_invoice_type', '$safe_year', '$safe_month','$houseid', '$date_created', '0','$safe_landlordid')");
					
					
					 if ($insert_query3) {
						 $this->send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $houseid, $safe_month, $safe_year);
                        return 1; // Success
                    } else {
                        return 0; // Failure
                    }
				}
            }
        }
    }
}


function SendSMSF($landlordid, $tenantid, $amount) {

    // Fetch SMS credits
    $result45 = $this->db->query("SELECT Amount, Rate FROM sms_credits WHERE schoolid ='$landlordid'");
    $info45 = $result45->fetch_assoc();  // Fetch the result as an associative array

    if ($info45) {
        $credit = $info45["Amount"];
        $rate = $info45["Rate"];
        $msgs = ceil($credit / $rate);  // Assuming $msgs is calculated based on available credits
        $amount_needed = ($rate * 1);

        if ($credit < $amount_needed) {
            return 0; // Not enough credit, abort SMS sending
        } else {
            // Select SMS route
            $result4500 = $this->db->query("SELECT Channel FROM smschannels");
            $info2 = $result4500->fetch_assoc();  // Fetch the result as an associative array

            if ($info2) {
                $route = $info2["Channel"];
            } else {
                $route = 3; // Default route if no result
            }

            // Switch case for different SMS providers
            switch ($route) {
                case 1:
                    include 'sms_care.php';
                    break;
                case 2:
                    include 'sms_group.php';
                    break;
                case 3:
                    include 'sms_ego.php';
                    break;
                case 4:
                    include 'ugsms.php';
                    break;
                case 5:
                    include 'sms_pandora.php';
                    break;
                default:
                    echo "No SMS route selected.";
                    break;
            }

            // Fetch tenant details with outstanding payments
            $tenant_query = $this->db->query("SELECT t.id, CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS name, t.contact
                FROM tenants t
                WHERE t.shopid = '$landlordid' AND id ='$tenantid'");
				
            $row1 = $tenant_query->fetch_assoc();  // Fetch tenant details as associative array

            if ($row1) {
                $tenant_name = ucwords($row1['name']);
                $contact = $row1['contact'];

                // Fetch shop details
                $tenant_shop_query = $this->db->query("SELECT * FROM landlords WHERE landlord_id = '$landlordid'");
                $row133 = $tenant_shop_query->fetch_assoc();  // Fetch shop details as associative array

                if ($row133) {
                    $shopname = $row133['business_name'];
                    $rsv = $row133['telephone_contact'];

                    // Prepare the SMS message
                    $message_to_send = "Dear $tenant_name, $shopname informs you that Shs $amount has been deposited on your account. RSV $rsv";

                    // Send the SMS (assumes SendSMS function exists)
                    SendSMS($sender, $password1, $username1, $message_type, $message_category, $contact, $message_to_send);
                }
            }

            // Update SMS credits
            $amt_used = $rate * 1;  // Assuming each message uses 1 unit of rate
            $new_amt = $credit - $amt_used;

            $this->db->query("UPDATE sms_credits SET Amount = '$new_amt' WHERE schoolid = '$landlordid'");
        }
    }
    return 1; // Sending succeeded
}


	function delete_houseprice(){
	extract($_POST);
	$landlordid = $_SESSION['login_landlordid']; 
	
	$safe_landlordid = $this->db->real_escape_string($landlordid);

	$delete = $this->db->query("DELETE FROM house_data where shopid = '$safe_landlordid' AND house_id = ".$id);
	if($delete){
		return 1;
	}
}
	
function save_deposit() {
    // Access POST data directly
    $tenant_id = $_POST['tenant_id'] ?? '';
    $transaction_id = $_POST['transaction_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $paid_by = $_POST['paid_by'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $id = $_POST['id'] ?? '';
    $datedd = !empty($_POST['datedd']) ? $_POST['datedd'] : date('Y-m-d H:i:s');

    $landlordid = $_SESSION['login_landlordid'];
    $safe_landlordid = $this->db->real_escape_string($landlordid);

    // Prepare sanitized input
    $safe_tenant_id = $this->db->real_escape_string($tenant_id);
    $safe_transaction_id = $this->db->real_escape_string($transaction_id);
    $safe_amount = $this->db->real_escape_string($amount);
    $safe_paid_by = $this->db->real_escape_string($paid_by);
    $safe_payment_method = $this->db->real_escape_string($payment_method);
    $safe_datedd = $this->db->real_escape_string($datedd);

    // Start a transaction
    $this->db->begin_transaction();

    try {
        // Check if the transaction already exists
        $check_query = $this->db->prepare("SELECT id FROM wallet WHERE shopid = ? AND transaction_id = ?");
        $check_query->bind_param('ss', $safe_landlordid, $safe_transaction_id);
        $check_query->execute();
        $check_query->store_result();
        
        if ($check_query->num_rows > 0) {
            // Update existing record
            $update_query = $this->db->prepare("UPDATE wallet SET tenant_id = ?, Trans_date = ?, amount = ?, paid_by = ?, Mode_pay = ? WHERE shopid = ? AND id = ?");
            $update_query->bind_param('ssssssi', $safe_tenant_id, $safe_datedd, $safe_amount, $safe_paid_by, $safe_payment_method, $safe_landlordid, $id);
            $result = $update_query->execute();
        } else {
            // Insert new record
            $insert_query = $this->db->prepare("INSERT INTO wallet (tenant_id, transaction_id, amount, paid_by, Mode_pay, Trans_date, shopid) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert_query->bind_param('sssssss', $safe_tenant_id, $safe_transaction_id, $safe_amount, $safe_paid_by, $safe_payment_method, $safe_datedd, $safe_landlordid);
            $result = $insert_query->execute();
            
            // Send SMS after insertion
            $this->SendSMSF($safe_landlordid, $safe_tenant_id, $safe_amount);
        }

        // Commit the transaction
        $this->db->commit();
        return $result ? 1 : 0;
        
    } catch (Exception $e) {
        // Rollback in case of error
        $this->db->rollback();
        error_log("Error saving deposit: " . $e->getMessage());
        return 0;
    }
}



function send_activation_email($email, $landlord_id, $resperson, $business_name) {
    // Construct the activation link with the landlord_id
    $converter = new Encryption();
    $encoded = $converter->encode($landlord_id);

    $mail = new PHPMailer();

    // SMTP Configuration
    $mail->IsSMTP();
    $mail->SMTPDebug  = 0;                     // Debugging (0 for production)
    $mail->SMTPAuth   = true;                  // Enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // TLS encryption, `ssl` can also be used
    $mail->Host       = "mail.eljotellug.com";      // Gmail's SMTP server

    // Try Port 587 (TLS)
    $mail->Port       = 465;                   
    $mail->Username   = "urent@eljotellug.com";   // Email username
    $mail->Password   = "0702014626@Bjk"; // Email password

    $mail->SetFrom('urent@eljotellug.com', 'Jotell Technologies Uganda Ltd');
    $mail->AddReplyTo('urent@eljotellug.com', 'Jotell Technologies Uganda Ltd');

    // Email Subject
    $mail->Subject = "JOTELL RENTALS MIS HOSTING PLAN";

    // Construct the email body  
    $url = "https://urent.eljotellug.com/activate.php?iddd=" . urlencode($landlord_id);

    $body = "
        <p>Dear $resperson,</p>
        <p>We are pleased to inform you that your account for $business_name was successfully opened.</p>
        <p>To activate your account, follow this link: <a href='$url'>$url</a> or copy and paste the link in the browser address bar.</p>
        <p>The account will be activated with a 2-month free subscription for system use.</p>
        <p>Download the attached systems manual for setup and configuration, and the Master's Agreement for understanding our terms of service and privacy policy.</p>
        <p>We kindly request that you print, read, understand, sign, scan, and mail us the Master Agreement at jotelltechnologiesugltd@gmail.com.</p>
        <p>If we do not hear from you within 2 months, your account will be suspended indefinitely.</p>
        <p>Support hotline: +256 776027733 / 757 875053</p>
        <p>Thank you for your business.</p>
        <p>Jotell Technologies Uganda Limited</p>
        <p>Management Team</p>
    ";

    // HTML Body
    $mail->isHTML(true); 
    $mail->MsgHTML($body);

    // Add recipient
    $mail->AddAddress($email, $resperson);

    // Send email
    if(!$mail->Send()) {
        // If Port 587 fails, try Port 465 (SSL)
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        if(!$mail->Send()) {
            return "Mailer Error: " . $mail->ErrorInfo; // Return error message if sending fails on both ports
        } else {
            return 1; // Sending succeeded with port 465
        }
    } else {
        return 1; // Sending succeeded with port 587
    }
}





function register_landlord() {
    // Include database connection (ensure proper db connection setup)

    // Access POST data directly with null coalescing operator for default values
    $business_name = $_POST['business_name'] ?? '';
    $registration_no = $_POST['registration_no'] ?? '';
    $geographical_description = $_POST['geographical_description'] ?? '';
    $district = $_POST['district'] ?? '';
    $sub_county = $_POST['sub_county'] ?? '';
    $parish = $_POST['parish'] ?? '';
    $telephone_contact = $_POST['telephone_contact'] ?? '';
    $email_address = $_POST['email_address'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $business_initials = $_POST['business_initials'] ?? '';
    $year = date('Y'); // Gets the current year
	
	$username = $_POST['username'] ?? '';
	
	$password = md5($_POST['password']) ?? '';
	
	$type  = 1;
	
	

    // Check if landlord already exists using a prepared statement
    $chk_stmt = $this->db->prepare("SELECT * FROM landlords WHERE business_name = ? AND registration_no = ? AND contact_person = ? AND telephone_contact = ?");
    if (!$chk_stmt) {
        die('Prepare failed: ' . $this->db->error);
    }
    
    // Bind parameters
    $chk_stmt->bind_param("ssss", $business_name, $registration_no, $contact_person, $telephone_contact);
    $chk_stmt->execute();
    $chk_result = $chk_stmt->get_result();
    
    if ($chk_result->num_rows > 0) {
        // Landlord already exists
        $existing_landlord = $chk_result->fetch_assoc();
        $existing_id = $existing_landlord['id'];

        // Update existing landlord record
        $update_stmt = $this->db->prepare("UPDATE landlords SET geographical_description = ?, sub_county = ?, parish = ?, email_address = ?, contact_person = ? WHERE id = ?");
        if (!$update_stmt) {
            die('Prepare failed: ' . $this->db->error);
        }

        // Bind parameters for update
        $update_stmt->bind_param("sssssi", $geographical_description, $sub_county, $parish, $email_address, $contact_person, $existing_id);
        if (!$update_stmt->execute()) {
            die('Execute failed: ' . $update_stmt->error);
        }

        // Clean up
        $update_stmt->close();
        $chk_result->free();
        $chk_stmt->close();

        // Indicate successful update
        return 2;
    } else {
        // Get the count of records in the landlords table
        $count_stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM landlords");
        if (!$count_stmt) {
            die('Prepare failed: ' . $this->db->error);
        }
        
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $count_row = $count_result->fetch_assoc();
        $count = $count_row['count'];

        // Clean up count statement
        $count_stmt->close();
        $count_result->free();

        // Generate the landlord ID
        $landlord_id = $business_initials . $year . str_pad($count + 1, 6, '0', STR_PAD_LEFT);

        // Insert new landlord record with the generated landlord ID
        $insert_stmt = $this->db->prepare("INSERT INTO landlords (landlord_id, business_name, registration_no, geographical_description, district, sub_county, parish, telephone_contact, email_address, contact_person, business_initials, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$insert_stmt) {
            die('Prepare failed: ' . $this->db->error);
        }

        // Bind parameters for insert statement
        $insert_stmt->bind_param("ssssssssssss", $landlord_id, $business_name, $registration_no, $geographical_description, $district, $sub_county, $parish, $telephone_contact, $email_address, $contact_person, $business_initials, $year);
        
        if (!$insert_stmt->execute()) {
            die('Execute failed: ' . $insert_stmt->error);
        }

        // Clean up insert statement
        $insert_stmt->close();
		
		// Assume $this->db is an instance of a database connection class that supports mysqli or PDO

// Check if the user exists
$query = "SELECT * FROM users WHERE username = ? AND type = '1' AND landlord_id = ?";
$stmt = $this->db->prepare($query);

if ($stmt) {
    $stmt->bind_param('ss', $username, $landlord_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $chk = $result->num_rows;

    // If the user exists
    if ($chk > 0) {
        // User exists, no need to insert
        #return 2;
    } else {
        // Prepare and execute the insert query
        $insertQuery = "INSERT INTO users (name, username, password, type, landlord_id) VALUES (?, ?, ?, '1', ?)";
        $insertStmt = $this->db->prepare($insertQuery);

        if ($insertStmt) {
            $insertStmt->bind_param('ssss', $contact_person, $username, $password, $landlord_id);
            if ($insertStmt->execute()) {
                // Successfully inserted
                #return 1;
            } else {
                // Handle execution error
                #return 0; // Error inserting
            }
        } else {
            // Handle prepare error for insert query
            #return 0; // Error preparing statement
        }

        $insertStmt->close();
    }

    $stmt->close();
} else {
    // Handle prepare error for select query
    #return 0; // Error preparing statement
}

			
		
        // Send activation email
       $this->send_activation_email($email_address,$landlord_id,$contact_person,$business_name);
	   

        // Indicate successful insert
        return 1;
    }
}





}
/*function SendSMS($username,$password,$numbers,$message,$senderid)
 { 
$url = "www.egosms.co/api/v1/plain/?";
$parameters="number=[number]&message=[message]&username=[username]&password=[password]&sender=[sender]";
$parameters = str_replace("[message]", urlencode($message), $parameters); $parameters = str_replace("[senderid]", urlencode($senderid),$parameters); $parameters = str_replace("[number]", urlencode($numbers),$parameters); $parameters = str_replace("[username]", urlencode($username),$parameters); $parameters = str_replace("[password]", urlencode($password),$parameters); $live_url="http://".$url.$parameters;
$parse_url=file($live_url); 
$response = $parse_url[0];
return $response;
}

$username1 ="jotellug";
$password1 ="ugandan1983";

$status = SendSMS($username,$password,$number,$message,$sender);

if($status == "sucess")



echo $status*/

function send_email_balance($email, $tenant_name, $contact_person, $business_name, $telephone_contact, $pemail_address, $amount, $status, $house, $month, $year) {
  
    $mail = new PHPMailer();

    // SMTP Configuration
    $mail->IsSMTP();
    $mail->SMTPDebug  = 0;                     // Debugging (0 for production)
    $mail->SMTPAuth   = true;                  // Enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // TLS encryption, `ssl` can also be used
    $mail->Host       = "mail.eljotellug.com"; // SMTP server

    // Try Port 587 (TLS)
    $mail->Port       = 587;                   // Corrected to 587 for TLS (originally had 465, which is for SSL)
    $mail->Username   = "urent@eljotellug.com"; // Email username
    $mail->Password   = "0702014626@Bjk";      // Email password

    $mail->SetFrom('urent@eljotellug.com', 'Jotell Technologies Uganda Ltd');
    $mail->AddReplyTo('urent@eljotellug.com', 'Jotell Technologies Uganda Ltd');

    // Email Subject
    $mail->Subject = "Email: Notification: Incoming Invoice,"; // Removed the extra comma
	
	$body = "
	    <p>Tenant: $tenant_name, House: $house, Month: $month, Year: $year</p>
        <p>Status: $status</p>
		<p>Amount: $amount</p>
        <p>You are required to pay this amount before the end of the due date.</p>
        <p>For further support and clarification, contact support at:</p>
		<p>$contact_person</p>
        <p>$business_name</p>
		<p>Tel: $telephone_contact || Email: $pemail_address</p>
		<p>Thank you</p>";
		
    $mail->isHTML(true); 
    $mail->MsgHTML($body);

    // Add recipient
    $mail->AddAddress($email, $tenant_name); // Changed $resperson to $tenant_name for proper recipient name

    // Send email
    if(!$mail->Send()) {
        // If Port 587 fails, try Port 465 (SSL)
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        if(!$mail->Send()) {
            return "Mailer Error: " . $mail->ErrorInfo; // Return error message if sending fails on both ports
        } else {
            return 1; // Sending succeeded with port 465
        }
    } else {
        return 1; // Sending succeeded with port 587
    }
}
/*
function SendSMS($username, $password, $number, $message, $senderid) {
    $url = "www.egosms.co/api/v1/plain/?";
    $parameters = "number=$number&message=" . urlencode($message) . "&username=" . urlencode($username) . "&password=" . urlencode($password) . "&sender=" . urlencode($senderid);
    $live_url = "http://" . $url . $parameters;
    
    $response = file($live_url);
    return $response[0];
}

*/
?>