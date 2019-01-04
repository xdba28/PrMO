<?php
require_once('../../core/init.php');

$user = new Admin();

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

$staff = new Staff();

	//Denver, after creating a log here update form's status unreceived to received.

try
{
	if(!empty($_POST))
	{
		$staff->startTrans();
		$Project = json_decode($_POST['obj'], true);

		$staff->register("project_logs", array(
			'referencing_to' => $Project['id'],
			'remarks' => "START_PROJECT",
			'logdate' => date('Y-m-d H:i:s'),
			'type' => "IN"
		));

		// $staff->register("notifications")

		$staff->update('project_request_forms', 'form_ref_no', $Project['id'], array(
			'status' => 'received'
		));
		
		$staff->endTrans();
		header("Content-type:application/json");
		echo json_encode($staff->allPRJO_req_detail());
		
	}
	else
	{
		header("Content-type:application/json");
		echo json_encode($staff->allPRJO_req_detail());
	}
}
catch(Exception $e)
{
	$data = ['success' => false];
	header("Content-type:application/json");
	echo json_encode($data);
}

?>