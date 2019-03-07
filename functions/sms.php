<?php
function sms($to, $from, $message){
	// $basic  = new \Nexmo\Client\Credentials\Basic('616ad05e', 'N0tmWLWROKJLG5Lu');
	// $client = new \Nexmo\Client($basic);
	$basic  = new \Nexmo\Client\Credentials\Basic('69bee64b', 'VhgBVqz641LCygAJ');
	$client = new \Nexmo\Client($basic);

	$message = $client->message()->send([
		'to' => $to,
		'from' => $from,
		'text' => $message
	]);
}
?>