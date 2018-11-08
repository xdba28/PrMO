<?php
require_once('../../core/init.php');

$user = new Admin(); 

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

$staff = new Staff();

<<<<<<< HEAD
$Project = json_decode($_POST['obj'], true);

try
{
	if($staff->register("project_logs", array(
		'referencing_to' => $Project['id'],
		'remarks' => "START_PROJECT",
		'logdate' => date('Y-m-d H:i:s'),
		'type' => "IN"
	))){
=======
try
{
	if(!empty($_POST))
	{
		$Project = json_decode($_POST['obj'], true);
		if($staff->register("project_logs", array(
			'referencing_to' => $Project['id'],
			'remarks' => "START_PROJECT",
			'logdate' => date('Y-m-d H:i:s'),
			'type' => "IN"
		))){
			header("Content-type:application/json");
			echo json_encode($staff->allPRJO_req_detail());
		}
	}
	else
	{
>>>>>>> denver
		header("Content-type:application/json");
		echo json_encode($staff->allPRJO_req_detail());
	}
}
catch(Exception $e)
{
	$data = [
		'success' => false
	];
	header("Content-type:application/json");
	echo json_encode($data);
}

?>