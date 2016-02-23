<?php  
  
// ??????????deviceToken???????????????  
//$deviceToken = 'c95f661371b085e2517b4c12cc76293522775e5fd9bb1dea17dd80fe85583b41';  
  

$deviceToken = 'ee66b79484e46ded099f54f8e2dbf9591da9db999ce951184dd49a165f036524';

  
// Put your private key's passphrase here:  
$passphrase = '123123';  
  
// Put your alert message here:  
$message = 'My first push test!';  
  
////////////////////////////////////////////////////////////////////////////////  
  
$ctx = stream_context_create();  
stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');  
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);  
  
// Open a connection to the APNS server  
//??????????  
 //$fp = stream_socket_client(?ssl://gateway.push.apple.com:2195?, $err, $errstr, 60, //STREAM_CLIENT_CONNECT, $ctx);  
//?????????????appstore??????  
$fp = stream_socket_client(  
'ssl://gateway.sandbox.push.apple.com:2195', $err,  
$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);  
  
if (!$fp)  
exit("Failed to connect: $err $errstr" . PHP_EOL);  
  
echo 'Connected to APNS' . PHP_EOL;  
  
// Create the payload body  
$body['aps'] = array(  
'alert' => $message,  
'sound' => 'http://www.baidu.com'  
);  
  
// Encode the payload as JSON  
$payload = json_encode($body);  
  
// Build the binary notification  
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;  
  
// Send it to the server  
$result = fwrite($fp, $msg, strlen($msg));  
  
if (!$result)  
echo 'Message not delivered' . PHP_EOL;  
else  
echo 'Message successfully delivered' . PHP_EOL;  
  
// Close the connection to the server  
fclose($fp);  
?> 