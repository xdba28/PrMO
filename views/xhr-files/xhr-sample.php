<?php

		//get the json file
		$json = file_get_contents('jsonsteps.json');
		// //Decode JSON
		$stepsStructure = json_decode($json,true);

		// echo "<pre>",print_r($stepsStructure),"</pre>";

		// include ('jsonsteps.json');

		// $availableActions = $actionsData['actionsByMop'][$mop][$standing];
		
		// echo $stepsStructure['modeOfProcurement']['Shopping']['noofsteps'];

		echo $stepsStructure['modeOfProcurement']['Shopping']['noofsteps'];
?>