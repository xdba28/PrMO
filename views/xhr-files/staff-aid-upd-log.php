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
							
							$project = $user->get('outgoing_register', array('project', '=', $reference));

							switch($project->transactions){
								case 'EVALUATION':
									$remarks = "Procurement Evaluation Form and other supporting documents are successfully received in the Technical Member's office for Evaluation.";
									$user->update('projects', 'project_ref_no', $reference, array(
										'accomplished' => '2',
										'workflow' => 'Pre Procurement Evaluation'
									));
									break;
								case 'SIGNATURES':
									$remarks = "Project Documents for signatures are successfully received in {$project->transmitting_to}, {$project->specific_office}.";
									break;
								default:
									$remarks = "Project Documents successfully received in {$project->transmitting_to}, {$project->specific_office}.";
									break;
							}

							$user->register('project_logs', array(

								'referencing_to' => $reference,
								'remarks' => $remarks,
								'logdate' => Date::translate('test', 'now'),
								'type' => 'OUT'
							));

							$user->delete('outgoing_register', array('project', '=', $reference));
							
						}
						break;


					case '2': //received, actions taken and immidiately returned

						foreach ($outgoing as $reference){
		
							$project = $user->get('outgoing_register', array('project', '=', $reference));

							switch($project->transactions){
								case 'EVALUATION':
									$remarks = "Project Documents are successfully evaluated and immidiately returned to PrMO. Result of evaluation will be updated in a few minutes.";

									$user->update('projects', 'project_ref_no', $reference, array(
										'accomplished' => '2',
										'workflow' => 'Pre Procurement Evaluation Finished'
									));
									break;
								case 'SIGNATURES':
									$remarks = "Project Documents for signatures are successfully received in {$project->transmitting_to}, {$project->specific_office} and immidiately returned to PrMO.";
									break;
								default:
									$remarks = "Project Documents successfully received in {$project->transmitting_to}, {$project->specific_office} and immidiately returned to PrMO";
									break;
							}

							$user->register('project_logs', array(

								'referencing_to' => $reference,
								'remarks' => $remarks,
								'logdate' => Date::translate('test', 'now'),
								'type' => 'IN'
							));

							$user->delete('outgoing_register', array('project', '=', $reference));
							
						}					
						break;


					case '3': //return items to outgoing queue
						
						foreach ($outgoing as $reference){
							
							$project = $user->get('outgoing_register', array('project', '=', $reference));

							//transfer from register to outgoing
							$user->register('outgoing', array(
								'project' => $project->project,
								'transmitting_to' => $project->transmitting_to,
								'specific_office' => $project->specific_office,
								'remarks' => $project->remarks,
								'transactions' => $project->transactions,
								'date_registered' => Date::translate('test', 'now')
							));

							$user->delete('outgoing_register', array('project', '=', $reference));

							$user->register('project_logs', array(

								'referencing_to' => $reference,
								'remarks' => "Project Documents returned to outgoing files queue, previous transaction canceled.",
								'logdate' => Date::translate('test', 'now'),
								'type' => 'IN'
							));

							
							
						}					
						break;


					case '4': //receive failure

						foreach ($outgoing as $reference){
								
							$project = $user->get('outgoing_register', array('project', '=', $reference));

							//transfer from register to outgoing
							$user->register('outgoing', array(
								'project' => $project->project,
								'transmitting_to' => $project->transmitting_to,
								'specific_office' => $project->specific_office,
								'remarks' => $project->remarks,
								'transactions' => $project->transactions,
								'date_registered' => Date::translate('test', 'now')
							));

							$user->delete('outgoing_register', array('project', '=', $reference));

							$user->register('project_logs', array(

								'referencing_to' => $reference,
								'remarks' => "Delivery Failure due to {$updateRemark}.",
								'logdate' => Date::translate('test', 'now'),
								'type' => 'IN'
							));
							
						}					
						break;
					default: 
						# code...
						break;
				}

			$user->endTrans();

		}catch(Exception $e){
			$e->getMessage()."A Fatal Error Occured";
			$data = ['success' => 'error', 'codeError' => $e];
			header("Content-type:application/json");
			echo json_encode($data);
			// log files
		}
		

		$data = ['success' => true];
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