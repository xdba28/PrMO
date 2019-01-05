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

		// echo "<pre>",print_r($_POST),"</pre>";
		// die();
		try{
			//details about the project being processed
			$projectDetails = $user->get('projects', array('project_ref_no', '=', Input::get('projectReference')));
			
			//enduser/s
			$enduserList=json_decode($projectDetails->end_user, true);
			//number of enduser of this project
			$noOfEndusers = count($enduserList);


			switch(Input::Get('action')){
				case "PreprocResult":

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
				$user->startTrans();
					if(isset($_POST['resolution'])){

						if($_POST['resolution'] == "yes"){

							// update the mop etc.. again
							// register a resolution log
							//update steps accomplished to 3

						$user->startTrans();

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
							
						$user->endTrans();

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
								'evaluator' => Input::Get('evaluator'),
								'evaluators_comment' => Input::Get('comment')
		
							));
							
							//check if there is an evaluation issue
							if(isset($_POST['issue'])){

							  $user->startTrans();							
							  
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

							  $user->endTrans();
		
							}else{
								#no issues and may proceed to another steps

							  $user->startTrans();
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
							  $user->endTrans();


								
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
								'mop_peritem' => json_encode($mops),
								'evaluators_comment' => Input::Get('comment')
		
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
				$user->endTrans();
					break;

				case 'twgPreprocResult':
				// (
					// [projectReference] => GDS2018-1
					// [mopOption] => overall
					// [MOPbyTwg] => Negociated Procurement
					// [issue] => on
					// [commentbyTwg] => g
					// [evaluator] => Lala Mercado Celis
					// [action] => twgPreprocResult
				// )




					if(isset($_POST['resolution'])){
						//this project already encountered a pre-procurement evaluation issue
						if($_POST['resolution'] == "yes"){

						//new steps for this project based on the new MOP
						$newSteps = json_encode($stepsStructure['modeOfProcurement'][Input::Get('MOPbyTwg')]['steps'], JSON_FORCE_OBJECT);
						//new number of steps based on the new MOP
						$noOfSteps = $stepsStructure['modeOfProcurement'][Input::Get('MOPbyTwg')]['noofsteps'];	

							//resolve this issue and proceed to next step
							//check if the classification is overall mop or multiple
							if(Input::get('mopOption') === "overall"){

								// update the mop etc.. again
								// register a resolution log
								//update steps accomplished to 3

							$user->startTrans();
								//update the mop and the evaluator
								$user->update('projects', 'project_ref_no', $projectDetails->project_ref_no, array(
									'MOP' => Input::Get('MOPbyTwg'),
									'steps' => $noOfSteps,
									'stepdetails' => $newSteps,
									'evaluator' => Input::Get('evaluator'),
									'accomplished' => "3",
									'workflow' => "DBMPS Checking / Canvass",
									'evaluators_comment' => Input::Get('commentbyTwg')
								));

								//register resolution log
								$user->register('project_logs',  array(
									'referencing_to' => $projectDetails->project_ref_no,
									'remarks' => "SOLVE^pre-procurement evaluation^Issue regarding to pre-procurement evaluation was successfully solved. Project process continued.",
									'logdate' => Date::translate('now', 'now'),
									'type' => 'IN'
								));
								
								//send dashboard notifs about resolution
								foreach ($enduserList as $endusers){
									$user->register('notifications', array(
										'recipient' => $endusers,
										'message' => "Issue regarding to pre-procurement evaluation of {$projectDetails->project_ref_no} was successfully solved",
										'datecreated' => Date::translate('test', 'now'),
										'seen' => 0,
										'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
									));
									notif(json_encode(array(
										'receiver' => $endusers,
										'message' => "Issue regarding to pre-procurement evaluation of {$projectDetails->project_ref_no} was successfully solved",
										'date' => Date::translate(Date::translate('test', 'now'), '1'),
										'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
									)));								
								}
							$user->endTrans();
								
								#send sms to enduser "issue resolved and process may continue"

							}else if(Input::get('mopOption') === "muptiple"){
								//mop classification shifted to overall(single) to multiple; conclusion:"it is shifted because in the first place if the classification is multiple already it wont we appearing here.
								// update the project accomplishment to 2 to pass this duty to procurement aids

							  $user->startTrans();

								$user->update("projects", "project_ref_no", $projectDetails->project_ref_no, array(
									'accomplished' => "2",
									'proposed_evaluator' => "procurement aid"

								));

								//register resolution log
								$user->register('project_logs',  array(
									'referencing_to' => $projectDetails->project_ref_no,
									'remarks' => "SOLVE^pre-procurement evaluation^Issue regarding to pre-procurement evaluation was successfully solved. Project process continued.",
									'logdate' => Date::translate('now', 'now'),
									'type' => 'IN'
								));

								//send dashboard notifs about resolution
								foreach ($enduserList as $endusers){
									$user->register('notifications', array(
										'recipient' => $endusers,
										'message' => "Issue regarding to pre-procurement evaluation of {$projectDetails->project_ref_no} was successfully solved.",
										'datecreated' => Date::translate('test', 'now'),
										'seen' => 0,
										'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
									));
									notif(json_encode(array(
										'receiver' => $endusers,
										'message' => "Issue regarding to pre-procurement evaluation of {$projectDetails->project_ref_no} was successfully solved.",
										'date' => Date::translate(Date::translate('test', 'now'), '1'),
										'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
									)));								
								}								
							  $user->endTrans();

							}				

								

						}else if($_POST['resolution'] == "no"){

						//new steps for this project based on the new MOP
						$newSteps = json_encode($stepsStructure['modeOfProcurement'][Input::Get('MOPbyTwg')]['steps'], JSON_FORCE_OBJECT);
						//new number of steps based on the new MOP
						$noOfSteps = $stepsStructure['modeOfProcurement'][Input::Get('MOPbyTwg')]['noofsteps'];	
							
							$user->startTrans();
							
							//register declaration again
							$user->update('projects', 'project_ref_no', $projectDetails->project_ref_no, array(
								'MOP' => Input::Get('MOPbyTwg'),
								'steps' => $noOfSteps,
								'stepdetails' => $newSteps,
								'evaluator' => Input::Get('evaluator'),
								'evaluators_comment' => Input::Get('commentbyTwg')
							));								

							//register issue log again
							$logRemark = 'ISSUE^pre-procurement^Pre-procurement evaluation issue encountered, Technical member noted the request with "'.Input::Get('commentbyTwg').'", please wait for the return of your submission documents. NOTE: If the technical member comment is concerning to clarification of specifics of your item listing in your request, You can immidiately resort it by editing your request details in this <a href="my-forms">Link</a>. Then it will be approved by our procurement-aid to apply your changes after then, you can now reprint the updated PR/JO as an attachment in your original submission then return it to the PrMO.';
							$user->register('project_logs',  array(
	
								'referencing_to' => $projectDetails->project_ref_no,
								'remarks' => $logRemark,
								'logdate' => Date::translate('now', 'now'),
								'type' => 'IN'
							));

							//send dashboard notifs about this issue again
							foreach ($enduserList as $endusers){
								$user->register('notifications', array(
									'recipient' => $endusers,
									'message' => "Pre-procurement evaluation issue encountered regarding to {$projectDetails->project_ref_no}, Click here for details.",
									'datecreated' => Date::translate('test', 'now'),
									'seen' => 0,
									'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
								));
								notif(json_encode(array(
									'receiver' => $endusers,
									'message' => "Pre-procurement evaluation issue encountered regarding to {$projectDetails->project_ref_no}, Click here for details.",
									'date' => Date::translate(Date::translate('test', 'now'), '1'),
									'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
								)));								
							}							


							#send sms to enduser about this issue "issue again"

						  $user->endTrans();

						}

					}else{
						//this project is first time to be evaluated

						if(Input::get('mopOption') === "overall"){
							// register direct
							//new steps for this project based on the new MOP
							$newSteps = json_encode($stepsStructure['modeOfProcurement'][Input::Get('MOPbyTwg')]['steps'], JSON_FORCE_OBJECT);
							//new number of steps based on the new MOP
							$noOfSteps = $stepsStructure['modeOfProcurement'][Input::Get('MOPbyTwg')]['noofsteps'];
	
						$user->startTrans();
	
							$user->update('projects', 'project_ref_no', $projectDetails->project_ref_no, array(
			
								'MOP' => Input::Get('MOPbyTwg'),
								'steps' => $noOfSteps,
								'stepdetails' => $newSteps,
								'evaluator' => Input::Get('evaluator'),
								'evaluators_comment' => Input::Get('commentbyTwg')
		
							));						
	
							if(isset($_POST['issue'])){
									//register issue log
									$logRemark = 'ISSUE^pre-procurement^Pre-procurement evaluation issue encountered, Technical member noted the request with "'.Input::Get('commentbyTwg').'", please wait for the return of your submission documents. NOTE: If the technical member comment is concerning to clarification of specifics of your item listing in your request, You can immidiately resort it by editing your request details in this <a href="my-forms">Link</a>. Then it will be approved by our procurement-aid to apply your changes after then, you can now reprint the updated PR/JO as an attachment in your original submission then return it to the PrMO';
									$user->register('project_logs',  array(
										'referencing_to' => $projectDetails->project_ref_no,
										'remarks' => $logRemark,
										'logdate' => Date::translate('now', 'now'),
										'type' => 'IN'
									));

									foreach ($enduserList as $endusers){
										$user->register('notifications', array(
											'recipient' => $endusers,
											'message' => "Pre-procurement evaluation issue encountered regarding to {$projectDetails->project_ref_no}, Click here for details.",
											'datecreated' => Date::translate('test', 'now'),
											'seen' => 0,
											'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
										));
										notif(json_encode(array(
											'receiver' => $endusers,
											'message' => "Pre-procurement evaluation issue encountered regarding to {$projectDetails->project_ref_no}, Click here for details.",
											'date' => Date::translate(Date::translate('test', 'now'), '1'),
											'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
										)));								
									}										

									#send sms to enduser about this issue
			
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
	
									//notify procurement aid that this project has been successfully evaluated
									// *******do refer to group classification "procurement aid in sending this notif
	
									// $user->register('notifications', array(
									// 	'recipient' => "admins aid",
									// 	'message' => "Project request {$projectDetails->project_ref_no} just finished pre-procurement evaluation.",
									// 	'datecreated' => Date::translate('test', 'now'),
									// 	'seen' => 0,
									// 	'href' => "Ongoing-projects"
									// ));								
							}
						$user->endTrans();
	
						}else if(Input::get('mopOption') === "muptiple"){
							// procurement aid registration	
							
						$user->startTrans();

							// update the project accomplishment to 2 to pass this duty to procurement aids
							$user->update("projects", "project_ref_no", $projectDetails->project_ref_no, array(
								'accomplished' => "2",
								'proposed_evaluator' => "procurement aid",
								'evaluators_comment' => Input::Get('commentbyTwg')

							));

							if(isset($_POST['issue'])){

									//register issue log
									$logRemark = 'ISSUE^pre-procurement^Pre-procurement evaluation issue encountered, Technical member noted the request with "'.Input::Get('commentbyTwg').'", please wait for the return of your submission documents. NOTE: If the technical member comment is concerning to clarification of specifics of your item listing in your request, You can immidiately resort it by editing your request details in this <a href="my-forms">Link</a>. Then it will be approved by our procurement-aid to apply your changes after then, you can now reprint the updated PR/JO as an attachment in your original submission then return it to the PrMO';
									$user->register('project_logs',  array(
										'referencing_to' => $projectDetails->project_ref_no,
										'remarks' => $logRemark,
										'logdate' => Date::translate('now', 'now'),
										'type' => 'IN'
									));

									foreach ($enduserList as $endusers){
										$user->register('notifications', array(
											'recipient' => $endusers,
											'message' => "Pre-procurement evaluation issue encountered regarding to {$projectDetails->project_ref_no}, Click here for details.",
											'datecreated' => Date::translate('test', 'now'),
											'seen' => 0,
											'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
										));
										notif(json_encode(array(
											'receiver' => $endusers,
											'message' => "Pre-procurement evaluation issue encountered regarding to {$projectDetails->project_ref_no}, Click here for details.",
											'date' => Date::translate(Date::translate('test', 'now'), '1'),
											'href' => "project-details?refno=".base64_encode($projectDetails->project_ref_no)
										)));								
									}	

									#send sms to enduser about this issue
			
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
								//let the aid finish this step
							}						
					
						$user->endTrans();	

						}	

					}					

				break;

				default:
					#code
					break;
				
			}

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