<?php

	$thisYear = date('Y');
	for ($x=1; $x <= 12 ; $x++){

		$month = date("F", mktime(0, 0, 0, $x, 10));

		$totalLogs = 0;
		$failed = 0;
		$succeed = 0;
		$errors = 0;

		$fileToRead = $month.".".$thisYear.".txt";

		if(file_exists("./".$fileToRead)){

			$myfile = fopen($fileToRead, "r");

				while(!feof($myfile)){
					$line = str_replace(array("\r", "\n"),'',fgets($myfile));
					switch ($line) {
						case '-------------------------':
							$totalLogs++;
							break;
						case 'Attempt: success':
							$succeed++;
							break;
						case 'Attempt: failed':
							$failed++;
							break;
						case 'Attempt: error_log':
							$errors++;
							break;
					}
				}

			fclose($myfile);

			$logs[$month] = array("total" => $totalLogs, "succeed" => $succeed, "failed" => $failed, "error" => $errors);

		}else{
			$logs[$month] = array("total" => 0, "succeed" => 0, "failed" => 0, "error" => 0);
		}
}




?>