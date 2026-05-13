<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .success-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 400px;
        }

        .success-icon {
            font-size: 50px;
            color: #4CAF50; /* Green color to signify success */
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #666;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50; /* Green color */
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049; /* Slightly darker green on hover */
        }
    </style>
</head>
<body>

    <div class="success-container">
        <!-- <div class="success-icon">✔️</div>-->
		<?php
		
#$email = "balikjose2005@gmail.com";

#$landlord_id = "WBG2024000005";

#$resperson ="JOSEPH";

#$business_name ="JOSEPH KIWUUWA ";


#send_activation_email($email,$landlord_id, $resperson, $business_name);	
		
		
		?>
        <h2>Registration Successful!</h2>
        <p>Your account has been registered successfully. Please check your email for account activation</p>

        <!--<a href="login.php" class="button">Go to Login</a>-->
    </div>

</body>
</html>
