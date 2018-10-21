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

						break;
					case 'SIGNATURES':
						# code...
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
		

		$outgoing = $user->selectAll('outgoing');
		if(!empty($outgoing)){
			foreach($outgoing as $doc){
				$outData[] = [
					'ID' => $doc->ID,
					'project' => $doc->project,
					'transmitting_to' => $doc->transmitting_to,
					'specific_office' => $doc->specific_office,
					'remarks' => $doc->remarks,
					'transactions' => $doc->transactions,
					'date_registered' => Date::translate($doc->date_registered, 1)
				];
				echo "a <br>";
			}
		}else $outData = NULL;

		
		$data = ['success' => true, 'outgoing' => $outData];
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