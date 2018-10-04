<?php
require_once('../../core/init.php');

$user = new Admin(); 

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}


	// sample data from post - -> > array('GSD2018-4', GSD2018-5)

	if(!empty($_POST))
	{

		$releasedBy = $user->fullnameOf(Session::get(Config::get('session/session_name')));
		$outgoing = $_POST['outgoing'];
		$action = $_POST['action'];
		if(isset($_POST['remarks'])){
			$updateRemark = $_POST['remarks'];
		}

		try{

			$user->startTrans();

				switch ($action) {
					case '1': //succesfully received

						foreach ($outgoing as $reference){

							$project = $user->get('outgoing-register', array('project', '=', $reference));
							switch($project->transactions){
								case 'EVALUATION':
									$remarks = "Procurement Evaluation Form and other supporting documents are successfuly received in the Technical Member's office for Evaluation.";
									break;
								case 'SIGNATURES':
									$remarks = "Project Documents for signatures are successfully received in {$project->transmitting_to}, {$project->specific_office}.";
									break;
								default:
									#code..
									break;
							}
							
						}
						break;


					case '2': //received and immidiately returned
						# code...
						break;
					case '3': //return items to outgoing queue
						# code...
						break;
					case '4': //receive failure
						# code...
						break;
					default: 
						# code...
						break;
				}

			$user->endTrans();

		}catch(Exception $e){
			die($e->getMessages()."A Fatal Error Occured");
		}
		




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

	// $data = ['success' => false];
	// header("Content-type:application/json");
	// echo json_encode($data);


?>