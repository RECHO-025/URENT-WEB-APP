<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

switch ($action) {
    case 'login':
        $login = $crud->login();
        if ($login) echo $login;
        break;
    case 'login2':
        $login = $crud->login2();
        if ($login) echo $login;
        break;
    case 'logout':
        $logout = $crud->logout();
        if ($logout) echo $logout;
        break;
    case 'logout2':
        $logout = $crud->logout2();
        if ($logout) echo $logout;
        break;
    case 'save_user':
        $save = $crud->save_user();
        if ($save) echo $save;
        break;
    case 'delete_user':
        $save = $crud->delete_user();
        if ($save) echo $save;
        break;
    case 'signup':
        $save = $crud->signup();
        if ($save) echo $save;
        break;
    case 'update_account':
        $save = $crud->update_account();
        if ($save) echo $save;
        break;
    case 'save_settings':
        $save = $crud->save_settings();
        if ($save) echo $save;
        break;
    case 'save_category':
        $save = $crud->save_category();
        if ($save) echo $save;
        break;
    case 'delete_category':
        $delete = $crud->delete_category();
        if ($delete) echo $delete;
        break;
    case 'save_house':
        $save = $crud->save_house();
        if ($save) echo $save;
        break;
    case 'delete_house':
        $save = $crud->delete_house();
        if ($save) echo $save;
        break;
    case 'save_tenant':
        $save = $crud->save_tenant();
        if ($save) echo $save;
        break;
    case 'delete_tenant':
        $save = $crud->delete_tenant();
        if ($save) echo $save;
        break;
    case 'get_tdetails':
        $get = $crud->get_tdetails();
        if ($get) echo $get;
        break;
    case 'save_payment':
        $save = $crud->save_payment();
        if ($save) echo $save;
        break;
    case 'delete_payment':
        $save = $crud->delete_payment();
        if ($save) echo $save;
        break;
}

ob_end_flush();
?>
