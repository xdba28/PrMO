<?php
function sms($to, $from, $message){
	$basic  = new \Nexmo\Client\Credentials\Basic('616ad05e', 'N0tmWLWROKJLG5Lu');
	$client = new \Nexmo\Client($basic);

	$message = $client->message()->send([
		'to' => $to,
		'from' => $from,
		'text' => $message
	]);
	// $message = $client->message()->send([
	// 	'to' => '639981964334',
	// 	'from' => 'Nexmo',
	// 	'text' => 'Hello Nico of PrMO from Nexmo'
	// ]);
}
?>