
<?php

function SendSMS($username,$password,$numbers,$message,$senderid)
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

?>