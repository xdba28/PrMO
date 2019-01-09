<?php
function sms(){
	$basic  = new \Nexmo\Client\Credentials\Basic('616ad05e', 'N0tmWLWROKJLG5Lu');
	$client = new \Nexmo\Client($basic);

	$message = $client->message()->send([
		'to' => '639085937139',
		'from' => 'Nexmo',
		'text' => 'Hello Denver from Nexmo'
	]);
	// $message = $client->message()->send([
	// 	'to' => '639981964334',
	// 	'from' => 'Nexmo',
	// 	'text' => 'Hello Nico of PrMO from Nexmo'
	// ]);
}
?>