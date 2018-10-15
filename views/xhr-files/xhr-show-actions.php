<?php
require_once('../../core/init.php');

$user = new Admin(); 

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

	if(!empty($_POST)){

		$refno = $_POST['ref'];

		$project = $user->get('projects', array('project_ref_no', '=', $refno));
		$standing=$project->accomplished;
		$mop=$project->MOP;

		//get the json file
		$json = file_get_contents('../xhr-files/jsonsteps.json');
		//Decode JSON
		$actionsData = json_decode($json,true);

		$availableActions = $actionsData['actionsByMop'][$mop][$standing];
		

		$data = ['success' => true, 'fetchedResult' => $availableActions];
		header("Content-type:application/json");
		echo json_encode($data);
	}
	else
	{
		$data = ['success' => false];
		header("Content-type:application/json");
		echo json_encode($data);
	}


?>