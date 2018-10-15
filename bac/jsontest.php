<?php
	$jsonstring = '{"0": "Waiting for documents to be transmitted to technical member for evaluation", "1": "Technical Member Evaluation", "2": "Finalization of BAC Resolution Recomending mode of Procurement, Publication, and canvass/RFQ form", "3":"Canvassing", "4":"Finalization of Abstract of Bid and BAC Resolution", "5":"Routing for signatories", "6":"Finalization of Notice of Award, Purchase Order/Letter Order, and Request for OS", "7":"For Conforme of Winning Bidder/s", "8":"Transmittal to COA and University Supply Office for Audit"}';
	// $simpleString = '2015-15096,2015-11583';
	//$jsonstring = '{"1": "sample, ginagawa mue", "2": "again"}';

	// $users = explode(",", $simpleString);
	
	 $jsondecoded = json_decode($jsonstring, true);
	 echo "<pre>", print_r($jsondecoded), "</pre>";
	// print_r($jsonencoded);
	// die();

	// $user = "2015-15096";
	// $myArray = ["0" => $user];

	// $encoded = json_encode($myArray, JSON_FORCE_OBJECT);
	// print_r($encoded);
	// die();

	// $temp = json_decode($jsonstring, true);
	//print_r($temp);


	// foreach ($temp as $key => $value) {
	// 	echo $value, "<br>";
	// }

?>