<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
    $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
    foreach($system as $k => $v){
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $_SESSION['system']['name']; ?></title>
  <link rel="stylesheet" href="style.css"> <!-- Assuming this is the CSS file -->
  <?php include('./header.php'); ?>
  <?php if(isset($_SESSION['login_id'])) header("location:index.php?page=home"); ?>
</head>

<style>
  <style>
body {
    width: 100%;
    height: 100%;
    background: #f7f9fc; /* Light background for overall page */
}

main#main {
    width: 100%;
    height: 100vh;
    display: flex;
    background-color: #ffffff;
}

/* Styling for the left section with the background image */
#login-left {
    position: relative;
    width: 50%; /* Make it 50% width for better layout */
    height: 100%;
    background: url('images/rent.jpg') no-repeat center center;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

#login-left:before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0); /* Semi-transparent overlay for better text contrast */
}

/* Right section with the login form */
#login-right {
    position: relative;
    width: 50%; /* Matching the left section */
    height: 100%;
    background: #0D2074; /* Soft NAVY color */
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

#login-right .card {
    margin: auto;
    z-index: 1;
    width: 100%;
    max-width: 400px; /* Restrict max width for better form alignment */
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
}

#login-right h4 {
    font-size: 1.8rem;
    color: #333;
    font-weight: bold;
    margin-bottom: 20px;
}

/* Form styling */
#login-form .form-group label {
    font-weight: bold;
    color: #333;
}

#login-form .form-group input {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    transition: all 0.3s ease-in-out;
}

#login-form .form-group input:focus {
    border-color: #59b6ec;
    box-shadow: 0 0 5px rgba(89, 182, 236, 0.5);
}

#login-form button {
    background-color: #49bde4;
    color: white;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    padding: 10px;
    transition: background-color 0.3s ease-in-out;
}
#register a{
    background-color: #0D2074;
    color: white;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    padding: 10px;
    transition: background-color 0.3s ease-in-out;
}
#login-form button:hover {
    background-color: #0D2074;
}

/* Responsive Design for smaller screens */
@media (max-width: 768px) {
    main#main {
        flex-direction: column; /* Stack sections vertically */
    }
    #login-left h4 {
            color: #000;
        }

    #login-left, #login-right {
        width: 100%;
        height: 50%;
       
         /* Adjust height for stacked layout */
    }

    #login-left {
        background-position: center;
        background-size: cover;
    }

    #login-right .card {
        width: 90%; /* More responsive on mobile */
    }
  
}

@media (max-width: 576px) {
    #login-left, #login-right {
        height: auto;
        
    }

    #login-right .card {
        max-width: 350px;
        padding: 10px;
    }

   
    }
</style>
<body>
  <main id="main" class="bg-light">
    <div id="login-left"></div>
    <div id="login-right">
      <div class="w-100">
        <h4 class="text-center text-white"><b><?php echo $_SESSION['system']['name']; ?></b></h4>
        <br><br>
        <div class="card col-md-8">
          <div class="card-body">
            <form id="login-form">
              <div class="form-group">
                <label for="landlord_id" class="control-label">LandLord ID</label>
                <input type="text" id="landlord_id" name="landlord_id" class="form-control">
              </div>
              <div class="form-group">
                <label for="username" class="control-label">Username</label>
                <input type="text" id="username" name="username" class="form-control">
              </div>
              <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" id="password" name="password" class="form-control">
              </div>
              <center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary" type="submit">Login</button></center>
            </form>
            <br>
            <div id ="register">
            <div class="text-center">
              <p class="text-dark">Don't have an account?</p>
              <center><a href="#" class="btn-sm btn-block btn-wave col-md-4 btn-success" onClick="window.location.href ='signup.php';">Register</a></center>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <script>
    $('#login-form').submit(function(e){
        e.preventDefault();
        $('#login-form button[type="submit"]').attr('disabled',true).html('Logging in...');
        if($(this).find('.alert-danger').length > 0)
            $(this).find('.alert-danger').remove();
        $.ajax({
            url:'ajax.php?action=login',
            method:'POST',
            data:$(this).serialize(),
            error:err=>{
                console.log(err);
                $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
            },
            success:function(resp){
                if(resp == 1){
                    location.href ='index.php?page=home';
                }else{
                    $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                }
            }
        });
    });
  </script>

  <script>
    // Register service worker for offline support
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
          console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
          console.log('ServiceWorker registration failed: ', err);
        });
      });
    }
  </script>
</body>
</html>
