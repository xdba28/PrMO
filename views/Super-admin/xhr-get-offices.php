<?php
require_once('../../core/init.php');
header("Content-type:application/json");

$admin = new Admin();


if(!empty($_POST))
{
	try{
		$offices = $admin->getAll('offices', array('unit', '=', $_POST['id']));
		$office_names = [];
		foreach($offices as $office){
			array_push($office_names, $office->specific_office);
		}

	}catch(Exception $e){
		$e->getMessage()."A Fatal Error Occured";
		$data = ['success' => 'error', 'codeError' => $e];
		echo json_encode($data);
		exit();
		// log files
	}
	
	$data = ['success' => true, 'offices' => $office_names];
	echo json_encode($data);
}
else
{
	$data = ['success' => false];
	echo json_encode($data);
}


?>