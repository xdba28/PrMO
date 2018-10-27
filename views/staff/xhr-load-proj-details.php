<?php
	require_once "../../core/init.php";
	$user = new Staff();
	header("Content-type:application/json");
	echo json_encode($user->allPRJO_req_detail());		
?>