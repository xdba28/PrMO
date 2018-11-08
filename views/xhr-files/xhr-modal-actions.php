<?php
require_once('../../core/init.php');

$user = new Admin(); 

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

		//get the json file for step details
		$json = file_get_contents('../xhr-files/jsonsteps.json');
		//Decode JSON
		$stepsStructure = json_decode($json,true);


	if(!empty($_POST))
	{
		try{

			//details about the project being processed
			$projectDetails = $user->get('projects', array('project_ref_no', '=', Input::get('projectReference')));
			//new steps for this project based on the new MOP
			$newSteps = json_encode($stepsStructure['modeOfProcurement'][Input::Get('MOP')]['steps'], JSON_FORCE_OBJECT);
			//new number of steps based on the new MOP
			$noOfSteps = $stepsStructure['modeOfProcurement'][Input::Get('MOP')]['noofsteps'];
			//enduser/s
			$enduserList=json_decode($projectDetails->end_user, true);
			//number of enduser of this project
			$noOfEndusers = count($enduserList);


			//start transaction
			$user->startTrans();

			switch(Input::Get('action')){
				case "PreprocResult":

					$user->update('projects', 'project_ref_no', Input::Get('projectReference'), array(

						'MOP' => Input::Get('MOP'),
						'evaluation_comment' => Input::Get('comment'),
						'steps' => $noOfSteps,
						'stepdetails' => $newSteps,
						'evaluator' => Input::Get('evaluator')

					));
					
					if(isset($_POST['issue'])){

						//register issue log
						$logRemark = 'ISSUE^pre-procurement^ Pre-procurement evaluation issue encountered, Technical member noted the request with "'.Input::Get('comment').'", please wait for the return of your submission documents. NOTE: If the technical member comment is concerning to clarification of specifics of your item listing in your request, You can immidiately resort it by editing your request details in this <a href="my-forms">Link</a>. Then it will be approved by our procurement-aid to apply your changes after then, you can now reprint the updated PR/JO as an attachment in your original submission then return it to the PrMO';
						$user->register('project_logs',  array(

							'referencing_to' => $projectDetails->project_ref_no,
							'remarks' => $logRemark,
							'logdate' => Date::translate('now', 'now'),
							'type' => 'IN'
						));

						//check if the project has multiple enduser
						if($noOfEndusers > 1){
							$transmitting = "Endusers offices";
							$specificOffice = "Endusers offices";
						}else{
							$enduserAccountDetail = $user->get('enduser', array('edr_id', '=', $enduserList[0]));
							$officeDetails = $user->get('units', array('ID', '=', $enduserAccountDetail->edr_designated_office));
							$transmitting = $officeDetails->office_name;
							$specificOffice = $enduserAccountDetail->current_specific_office;
						}

						//queue to outgoing to be returned to enduser
						$user->register('outgoing', array(

							'project' => $projectDetails->project_ref_no,
							'transmitting_to' => $transmitting,
							'specific_office' => $specificOffice,
							'remarks' => "To be returned to enduser/s for clarification.",
							'transactions' => "EVALUATION ISSUE",
							'date_registered' => Date::translate('now', 'now')

						));

					}else{

					
						#no issues and may proceed to another steps
						//update accomplished to next step
						$user->update('projects', 'project_ref_no', Input::Get('projectReference'), array(
							'accomplished' => "3",
							'workflow' => "DBMPS Checking"
						));
						//register a log
						$user->register('project_logs',  array(

							'referencing_to' => $projectDetails->project_ref_no,
							'remarks' => "Evaluation Succesfully completed and no issues encountered.",
							'logdate' => Date::translate('now', 'now'),
							'type' => 'IN'

						));

				}

					break;

				default:
					#code
					break;
				
			}

			//commit
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