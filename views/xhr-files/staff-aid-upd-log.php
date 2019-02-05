<?php
require_once('../../core/init.php');
header("Content-type:application/json");

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
		$eval = false;
		if(isset($_POST['remarks'])){
			$updateRemark = $_POST['remarks'];
		}

		try{

				switch ($action){
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

							$user->startTrans();
								$user->register('project_logs', array(

									'referencing_to' => $reference,
									'remarks' => $remarks,
									'logdate' => Date::translate('test', 'now'),
									'type' => 'OUT'
								));

								$user->delete('outgoing_register', array('project', '=', $reference));
							$user->endTrans();

							Syslog::put('Update '.$reference.' from released documents. transaction:'.$project->transactions);
							
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

							$user->startTrans();
								$user->register('project_logs', array(

									'referencing_to' => $reference,
									'remarks' => $remarks,
									'logdate' => Date::translate('test', 'now'),
									'type' => 'IN'
								));

								$user->delete('outgoing_register', array('project', '=', $reference));
							$user->endTrans();

							Syslog::put('Update '.$reference.' from released documents. transaction:'.$project->transactions);
							
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


							$user->startTrans();
								$user->delete('outgoing_register', array('project', '=', $reference));

								$user->register('project_logs', array(

									'referencing_to' => $reference,
									'remarks' => "Project Documents returned to outgoing files queue, previous transaction canceled.",
									'logdate' => Date::translate('test', 'now'),
									'type' => 'IN'
								));
							$user->endTrans();

							Syslog::put('Update '.$reference.' from released documents. transaction:'.$project->transactions);

							
							
						}					
						break;


					case '4': //receive failure

						foreach ($outgoing as $reference){
								
							$project = $user->get('outgoing_register', array('project', '=', $reference));

							$user->startTrans();
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
							$user->endTrans();

							Syslog::put('Update '.$reference.' from released documents.');
								
						}					
						break;
					default: 
						# code...
						break;
				}

				// Syslog::put('Update released documents');


		}catch(Exception $e){
			Syslog::put($e,null,'error_log');
			Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0001');
			$e->getMessage()."A Fatal Error Occured";
			$data = ['success' => 'error', 'codeError' => $e];
			echo json_encode($data);
			exit();
			// log files
		}



		$outData = NULL;
		$signiture = NULL;
		$gen = NULL;
		$updateDoc = NULL;

		$outgoing = $user->selectAll('outgoing');
		if(!empty($outgoing)){
			foreach($outgoing as $document){
				switch($document->transactions){
					case "EVALUATION":
						$project = $user->get('projects', array('project_ref_no', '=', $document->project));
						$outData[] = [
							'project' => $document->project,
							'title' => $project->project_title,
							'date_registered' => Date::translate($document->date_registered, 1)
						];
						break;

					case "SIGNATURES":
						$project = $user->get('projects', array('project_ref_no', '=', $document->project));
						// $unit = $user->get('units', array('office_name', '=', $document->transmitting_to));
						$signiture[] = [
							'project' => $document->project,
							'title' => $project->project_title,
							'transmitting_to' => $document->transmitting_to,
							'specific_office' => $document->specific_office,
							'date_registered' => Date::translate($document->date_registered, 1)
						];
						break;

					default:
						$project = $user->get('projects', array('project_ref_no', '=', $document->project));
						$unit = $user->get('units', array('office_name', '=', $document->transmitting_to));

						$gen[] = [
							'project' => $document->project,
							'title' => $project->project_title,
							'transmitting_to' => $unit->office_name,
							'specific_office' => $document->specific_office,
							'transaction' => $document->transactions,
							'remark' => $document->remarks,					
							'date_registered' => Date::translate($document->date_registered, 1)
						];
						break;
				}
			}
		}

		$released = $user->selectAll('outgoing_register');
		if(!empty($released)){
			foreach($released as $a){
				$project = $user->get('projects', array('project_ref_no', '=', $a->project));

				$updateDoc[] = [
					'project' => $a->project,
					'title' => $project->project_title,
					'date_registered' => Date::translate($a->date_registered, 1)
				];
			}
		}
		
		$data = [
			'success' => true, 
			'twg' => $outData,
			'sign' => $signiture,
			'gen' => $gen,
			'updateDoc' => $updateDoc,
			'forEval' => [
				'bool' => $eval, 
				'data' => $_POST['outgoing']
			]
		];

		echo json_encode($data);
	}
	else
	{
		$data = ['success' => false];
		echo json_encode($data);
	}


?>