<?php
class Encryption {
var $skey = "yourSecretKey"; // change this
 
public function safe_b64encode($string) {
$data = base64_encode($string);
$data = str_replace(array('+','/','='),array('-','_',''),$data);
return $data;
}
 
public function safe_b64decode($string) {
$data = str_replace(array('-','_'),array('+','/'),$string);
$mod4 = strlen($data) % 4;
if ($mod4) {
$data .= substr('====', $mod4);
}
return base64_decode($data);
}
public function encode($ivlen){
if($ivlen){return false;}
$iv = openssl_random_pseudo_bytes($ivlen);
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

return trim($this->safe_b64encode($ciphertext));
}
 
public function decode($ciphertext){

$c = base64_decode($ciphertext);
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
$iv = substr($c, 0, $ivlen);
$hmac = substr($c, $ivlen, $sha2len=32);
$ciphertext_raw = substr($c, $ivlen+$sha2len);
$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
{
    $text = $original_plaintext."\n";
}
else {
     
	 $text = "nmmsmsmsmsmmsmsm";

}

return $text ; 

}
}

?>