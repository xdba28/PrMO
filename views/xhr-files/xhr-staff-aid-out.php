<?php
require_once('../../core/init.php');

$user = new Admin();

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

	if(!empty($_POST))
	{
		$releasedBy = $user->fullnameOf(Session::get(Config::get('session/session_name')));
		$outgoing = $_POST['outgoing'];
		$eval = false;

		try{

			$user->startTrans();
		
			foreach ($outgoing as $reference){

				$project = $user->get('outgoing', array('project', '=', $reference));

				/*Transfer the outgoing data to outgoing register*/
				$user->transfer($reference, $releasedBy);

				/*Delete original data from the outgoing table*/
				$user->delete('outgoing', array('project', '=', $reference));

				switch($project->transactions){

					case 'EVALUATION':
						$remarks = "Project {$reference} has shifted to Technical Member for evaluation by {$releasedBy}.";

						/*Update the progress of the project*/
						$user->update('projects', 'project_ref_no', $reference, array(
							'accomplished' => '1',
							'workflow' => 'Pre-procurement Evaluation'
						));

						$eval = true;

						break;
					case 'SIGNATURES':
						$remarks = "Project released for routing";
						# code...
						break;
					case 'EVALUATION ISSUE':
						$remarks = "Documents released to be returned to end user(s)";
						break;						
					default:
						# code...
						break;

				}

				/*Create a project log referencing to this project*/
				$user->register('project_logs', array(

					'referencing_to' => $reference,
					'remarks' => $remarks,
					'logdate' => Date::translate('test','now'),
					'type' => 'OUT'

				));				

				
			}

			$user->endTrans();

		}catch(Exception $e){
			$e->getMessage()."A Fatal Error Occured";
			$data = ['success' => 'error', 'codeError' => $e];
			header("Content-type:application/json");
			echo json_encode($data);
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