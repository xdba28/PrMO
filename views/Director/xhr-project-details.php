<?php
require_once('../../core/init.php');
header("Content-type:application/json");

$user = new Admin();

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

try
{
	if(!empty($_GET['id'])){
		$project_details = $user->get("projects", array("project_ref_no", '=', $_GET['id']));
		echo json_encode([
			'success' => 'true',
			'project_details' => $project_details
		]);
	}else{
		echo json_encode(['success' => 'error']);
	}
}
catch(Exception $e)
{
	echo json_encode(['success' => false, 'error' => $e]);
}

?>