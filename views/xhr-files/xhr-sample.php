<?php
require_once('../../core/init.php');

$user = new Admin(); 

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

try
{

	$json = json_decode(file_get_contents('php://input'), true);
	// sample data from post - -> > array('GSD2018-4', GSD2018-5)

	if(!empty($json))
	{

		


		$data = ['success' => true];
		header("Content-type:application/json");
		echo json_encode($data);
	}
	else
	{
		$data = ['success' => 'error'];
		header("Content-type:application/json");
		echo json_encode($data);
	}
}
catch(Exception $e)
{
	$data = ['success' => false];
	header("Content-type:application/json");
	echo json_encode($data);
}

?>