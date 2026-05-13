<?php
function SendSMS($sender, $password, $username, $message_type, $message_category, $number, $message) { 

    // Base URL for SMS API
    $url = "sms.thepandoranetworks.com/API/send_sms/?";

    // Build parameters string
    $parameters = "number=[number]&message=[message]&username=[username]&password=[password]&sender=[sender]&message_type=[message_type]&message_category=[message_category]";

    // Replace placeholders with actual values, ensuring proper URL encoding
    $parameters = str_replace("[message]", urlencode($message), $parameters);
    $parameters = str_replace("[sender]", urlencode($sender), $parameters);
    $parameters = str_replace("[number]", urlencode($number), $parameters);
    $parameters = str_replace("[username]", urlencode($username), $parameters);
    $parameters = str_replace("[password]", urlencode($password), $parameters);
    $parameters = str_replace("[message_type]", urlencode($message_type), $parameters);
    $parameters = str_replace("[message_category]", urlencode($message_category), $parameters);

    // Construct the full URL
    $live_url = "https://" . $url . $parameters;

    // Fetch the response from the API
    $parse_url = @file($live_url); // Suppress warnings in case of connection issues

    if (!$parse_url) {
        // Handle potential connection issues
        return "Failed to connect to SMS API";
    }

    // Get the first line of the response
    $response = $parse_url[0];

    // Check for insufficient credit message
    if ($response == "Insufficient credit to send SMS") {
        $msg = "Contact Systems Administrator";
        return $msg;
    }

    // Parse response for status
    $arr_msg = explode(" ", $response);
    $sent = $arr_msg[0]; // First word of the response

    if ($sent == 'Message') {
        $status_msg = "OK";
    } else {
        $status_msg = "Non";
    }

    return $status_msg;
}

// Example function call
$sender = 'User';
$password1 = '0702014626@Bjk';
$username1 = '0702014626';
$message_type = 'non_customised';
$message_category = 'bulk';

// Uncomment to test sending SMS
// $number = '256702014626'; 
// $message = 'Jotell at Pandora';
// $mysms = SendSMS($sender, $password1, $username1, $message_type, $message_category, $number, $message);
// echo $mysms;
?>
