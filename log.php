<?php

	$log = "Something".PHP_EOL;

	file_put_contents('system.log', $log, FILE_APPEND);

?>