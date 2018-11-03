<?php

		//get the json file
		// $json = file_get_contents('jsonsteps.json');
		// //Decode JSON
		// $stepsStructure = json_decode($json,true);

		// echo "<pre>",print_r($stepsStructure),"</pre>";

		// include ('jsonsteps.json');

		// $availableActions = $actionsData['actionsByMop'][$mop][$standing];
		
		// echo $stepsStructure['modeOfProcurement']['Shopping']['noofsteps'];
// 
		// echo $stepsStructure['modeOfProcurement']['Shopping']['noofsteps'];

		$test = '		[{"name":"prUpStk[]","value":""},{"name":"prUpUnt[]","value":"piece"},{"name":"prUpDesc[]","value":"Kyosera Toner"},{"name":"prUpQty[]","value":"3"},{"name":"prUpUc[]","value":"1700.00"},{"name":"prUpTc[]","value":"5100.00"}]';

		$json = json_decode($test);

		echo "<pre>", print_r($json), "</pre>";
?>