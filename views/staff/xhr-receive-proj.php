<?php
require_once('../../core/init.php');

$user = new Admin();

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

$staff = new Staff();

// echo "<pre>",print_r(json_decode($_POST['obj'], true)),"</pre>";
// die();

try
{
	if(!empty($_POST))
	{
		$Project = json_decode($_POST['obj'], true);

		$staff->startTrans();

			$staff->register("project_logs", array(
				'referencing_to' => $Project['id'],
				'remarks' => "START_PROJECT",
				'logdate' => date('Y-m-d H:i:s'),
				'type' => "IN"
			));

			$staff->update('project_request_forms', 'form_ref_no', $Project['id'], array(
				'status' => 'received'
			));

		$staff->endTrans();

		$request_info = $staff->get("project_request_forms", array("form_ref_no", "=", $Project['id']));

		#send Dashboard notif
		$staff->register('notifications', array(
			'recipient' => $request_info->requested_by,
			'message' => "Your project request form {$Project['id']} was received in the PrMO.",
			'datecreated' => Date::translate('test', 'now'),
			'seen' => 0,
			'href' => "my-forms"
		));
		notif(json_encode(array(
			'receiver' => $request_info->requested_by,
			'message' => "Your project request form {$Project['id']} was received in the PrMO.",
			'date' => Date::translate(Date::translate('test', 'now'), '1'),
			'href' => "my-forms"
		)));


		

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