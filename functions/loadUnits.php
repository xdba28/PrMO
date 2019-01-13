<?php

	if($fileHandler = fopen('../xhr-files/units.txt', 'r')){

		$availableUnits = array();

		while(!feof($fileHandler)){
			$line = fgetss($fileHandler);
			array_push($availableUnits, $line);
		}

		fclose($fileHandler);

	}

?>