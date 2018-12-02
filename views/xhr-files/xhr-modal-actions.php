<?php
require_once('../../core/init.php');
header("Content-type:application/json");

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
			

			if($_POST['a'] === "whole"){

				//new steps for this project based on the new MOP
				$newSteps = json_encode($stepsStructure['modeOfProcurement'][Input::Get('MOP')]['steps'], JSON_FORCE_OBJECT);
				//new number of steps based on the new MOP
				$noOfSteps = $stepsStructure['modeOfProcurement'][Input::Get('MOP')]['noofsteps'];

			}elseif($_POST['a'] === "perItem"){

				//new steps for this project based on the new MOP
				$newSteps = json_encode($stepsStructure['modeOfProcurement']['Mixed']['steps'], JSON_FORCE_OBJECT);
				//new number of steps based on the new MOP
				$noOfSteps = $stepsStructure['modeOfProcurement']['Mixed']['noofsteps'];

			}

			//enduser/s
			$enduserList=json_decode($projectDetails->end_user, true);
			//number of enduser of this project
			$noOfEndusers = count($enduserList);

			//start transaction
			$user->startTrans();

			switch(Input::Get('action')){
				case "PreprocResult":

					if(isset($_POST['resolution'])){

						if($_POST['resolution'] == "yes"){

							// update the mop etc.. again
							// register a resolution log
							//update steps accomplished to 2

							//update the mop and the evaluator
							$user->update('projects', 'project_ref_no', $projectDetails->project_ref_no, array(
								'MOP' => Input::Get('MOP'),
								'steps' => $noOfSteps,
								'stepdetails' => $newSteps,
								'evaluator' => Input::Get('evaluator')
							));

							//update accomplished to next step
							$user->update('projects', 'project_ref_no', $projectDetails->project_ref_no, array(
								'accomplished' => "3",
								'workflow' => "DBMPS Checking / Canvass"
							));

							//register resolution log
							$user->register('project_logs',  array(
								'referencing_to' => $projectDetails->project_ref_no,
								'remarks' => "SOLVE^pre-procurement evaluation^Issue regarding to pre-procurement evaluation was successfully solved. Project process continued.",
								'logdate' => Date::translate('now', 'now'),
								'type' => 'IN'
							));						

						}else if($_POST['resolution'] == "no"){
								//register issue log again
								$logRemark = 'ISSUE^pre-procurement^Pre-procurement evaluation issue encountered, Technical member noted the request with "'.Input::Get('comment').'", please wait for the return of your submission documents. NOTE: If the technical member comment is concerning to clarification of specifics of your item listing in your request, You can immidiately resort it by editing your request details in this <a href="my-forms">Link</a>. Then it will be approved by our procurement-aid to apply your changes after then, you can now reprint the updated PR/JO as an attachment in your original submission then return it to the PrMO';
								$user->register('project_logs',  array(
		
									'referencing_to' => $projectDetails->project_ref_no,
									'remarks' => $logRemark,
									'logdate' => Date::translate('now', 'now'),
									'type' => 'IN'
								));
						}

					}else{

						if($_POST['a'] === "whole"){
							//update the mop and the evaluator
							$user->update('projects', 'project_ref_no', $projectDetails->project_ref_no, array(
		
								'MOP' => Input::Get('MOP'),
								'steps' => $noOfSteps,
								'stepdetails' => $newSteps,
								'evaluator' => Input::Get('evaluator')
		
							));
							
							//check if there is an evaluation issue
							if(isset($_POST['issue'])){
		
								//register issue log
								$logRemark = 'ISSUE^pre-procurement^Pre-procurement evaluation issue encountered, Technical member noted the request with "'.Input::Get('comment').'", please wait for the return of your submission documents. NOTE: If the technical member comment is concerning to clarification of specifics of your item listing in your request, You can immidiately resort it by editing your request details in this <a href="my-forms">Link</a>. Then it will be approved by our procurement-aid to apply your changes after then, you can now reprint the updated PR/JO as an attachment in your original submission then return it to the PrMO';
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
									'workflow' => "DBMPS Checking / Canvass"
								));
								//register a log
								$user->register('project_logs',  array(
									'referencing_to' => $projectDetails->project_ref_no,
									'remarks' => "Evaluation Succesfully completed and no issues encountered.",
									'logdate' => Date::translate('now', 'now'),
									'type' => 'IN'
								));


								
							}

						}elseif($_POST['a'] === "perItem"){

							$stringMops = '';
							$mops = [];
							$count = 0;
							foreach($_POST['individialMOP'] as $mode){
							
								if(array_key_exists($mode, $mops)){
									array_push($mops[$mode], $_POST['item'][$count]);
								}else{
									$stringMops .= $mode.",";
									$mops[$mode] = [];
									array_push($mops[$mode], $_POST['item'][$count]);
								}
								$count++;
							}

							$user->update('projects', 'project_ref_no', $projectDetails->project_ref_no, array(
		
								'MOP' => $stringMops,
								'steps' => $noOfSteps,
								'stepdetails' => $newSteps,
								'evaluator' => Input::Get('evaluator'),
								'mop_peritem' => json_encode($mops)
		
							));
							
							//check if there is an evaluation issue
							if(isset($_POST['issue'])){
		
								//register issue log
								$logRemark = 'ISSUE^pre-procurement^Pre-procurement evaluation issue encountered, Technical member noted the request with "'.Input::Get('comment').'", please wait for the return of your submission documents. NOTE: If the technical member comment is concerning to clarification of specifics of your item listing in your request, You can immidiately resort it by editing your request details in this <a href="my-forms">Link</a>. Then it will be approved by our procurement-aid to apply your changes after then, you can now reprint the updated PR/JO as an attachment in your original submission then return it to the PrMO';
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
									'workflow' => "DBMPS Checking / Canvass"
								));
								//register a log
								$user->register('project_logs',  array(
									'referencing_to' => $projectDetails->project_ref_no,
									'remarks' => "Evaluation Succesfully completed and no issues encountered.",
									'logdate' => Date::translate('now', 'now'),
									'type' => 'IN'
								));

							}

							

						}
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
			echo json_encode($data);
			exit();
			// log files
		}
		

		$data = ['success' => true];
		echo json_encode($data);
	}
	else
	{
		$data = ['success' => false];
		echo json_encode($data);
	}


?>