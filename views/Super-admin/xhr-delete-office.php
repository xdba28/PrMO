<?php
require_once('../../core/init.php');
header("Content-type:application/json");

$admin = new Admin();


if(!empty($_POST))
{
	try{
		$admin->startTrans();
		
		$admin->delete('offices', array('office_id', '=', $_POST['id']));

		$admin->endTrans();
	}catch(Exception $e){
		$e->getMessage()."A Fatal Error Occured";
		$data = ['success' => 'error', 'codeError' => $e];
		echo json_encode($data);
		exit();
		// log files
	}
	
	$data = ['success' => true];
	echo json_encode($data);
}
else
{
	$data = ['success' => false];
	echo json_encode($data);
}


?>