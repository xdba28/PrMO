<?php
require_once('../../core/init.php');

$user = new Admin();

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

try
{
	// sample data from post - -> > array('GSD2018-4', GSD2018-5)

	if(!empty($_POST))
	{

		try{

			$releasedBy = $user->fullnameOf(Session::get(Config::get('session/session_name')));
			$outgoing = $_POST['outgoing'];

			$user->startTrans();
		
			foreach ($outgoing as $reference){

				$project = $user->get('outgoing', array('project', '=', $reference));


				/*Transfer the outgoing data to outgoing register*/
				$user->transfer($reference, $releasedBy);

				/*Delete original data from the outgoing table*/
				//$user->delete('outgoing', array('project', '=', $reference));

				switch($project->transactions){

					case 'EVALUATION':
						$remarks = "Project {$reference} has shifted to Technical Member for evaluation by {$releasedBy}.";

						/*Update the progress of the project*/
						$user->update('projects', 'project_ref_no', $reference, array(
							'accomplished' => '1'
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
			die($e->getMessage()."A Fatal Error Occured");
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
}
catch(Exception $e)
{
	$data = ['success' => false];
	header("Content-type:application/json");
	echo json_encode($data);
}

?>