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

		if(strpos($project->MOP, ',')){
			$mop = "Mixed";
		}else{
			$mop = $project->MOP;
		}

		// revise this logic
		//if mop has , return


		//get the json file
		$json = file_get_contents('../xhr-files/jsonsteps.json');
		//Decode JSON
		$actionsData = json_decode($json,true);

		$availableActions = $actionsData['actionsByMop'][$mop][$standing];

		$issue = $user->checkProjectIssue($_POST['ref']);
		if(!empty($issue) && $issue !== false){
			$PreEvalIssue = true;
		}else{
			$PreEvalIssue = false;
		}

		$formData = $user->projectDetails($refno);

		$data = ['success' => true, 
			'fetchedResult' => $availableActions, 
			'issue' => $PreEvalIssue, 
			'formData' => $formData,
			'standing' => $standing
		];
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