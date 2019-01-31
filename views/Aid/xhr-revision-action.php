<?php
require_once('../../core/init.php');
header("Content-type:application/json");

$admin = new Admin(); 

if($admin->isLoggedIn()){
 //do nothing
}else{
   Redirect::To('../../blyte/acc3ss');
	die();
}

// echo "<pre>".print_r($_POST)."</pre>";
// die();

if(!empty($_POST))
{
	try{

		$request = $admin->get('form_update_requests', array('ID', '=', base64_decode(Input::get('request'))));		
		if(Input::get('action') === "Grant"){

			$formIdentifier = substr($request->form_origin, 0, 2);
			
			
			if($request->action === "delete"){
				//decode the required data for update/delete
				$updateData = json_decode($request->update_data);
				echo "<pre>",print_r($updateData),"</pre>";				


				if($formIdentifier === "PR"){

					$admin->startTrans();

						foreach ($updateData->items as $item) {
							$admin->delete("lot_content_for_pr", array("ID", "=", $item->item_id));
						}

						// recompute for all affected lots
						foreach($updateData->lotref as $individualLot){
							// recompute lot cost
							$dataParts = explode("blyt322", $individualLot);
							// $dataParts[0] is the lot no and $dataParts[1] is the lot_id from the database.
							$lotId = $dataParts[1];
							$newLotCost =  $admin->recompute($request->form_origin, $dataParts[0]);
							$admin->update('lots', 'lot_id', $lotId, array(
								'lot_cost' => $newLotCost
							));

							
						}
						
						$admin->update('form_update_requests', 'ID', base64_decode(Input::get('request')) ,array(
							'response' => 'granted',
							'response_date' => Date::translate('raw', 'now'),
							'response_message' => "Request Granted"
						));

						//check if this PR/JO is registered as a project, if said so register a project log
						$isProject = $admin->like('projects', 'request_origin', $request->form_origin);
							if($isProject){

								$customRemark =  $request->action.' request on <a>'.$request->form_origin. '</a> was granted by "'.$admin->fullnameOf($admin->data()->account_id).'"';
								$admin->register('project_logs', array(
									'referencing_to' => $request->form_origin,
									'remarks' => $customRemark,
									'logdate' => Date::translate('raw', 'now'),
									'type' => 'IN'
								));
							}						

					$admin->endTrans();

					#send Dashboard notif
					$admin->register('notifications', array(
						'recipient' => $request->requested_by,
						'message' => "Revision request on {$request->form_origin} was granted.",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "my-forms?q=".base64_encode($request->form_origin)
					));
					notif(json_encode(array(
						'receiver' => $request->requested_by,
						'message' => "Revision request on {$request->form_origin} was granted.",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "my-forms?q=".base64_encode($request->form_origin)
					)));					




				}else if($formIdentifier === "JO"){
					#we still have errors to resolve here from the enduser side, incorrect reason input
				}






			}else if($request->action === "update"){
				//decode the required data for update/delete
				$originalData =  json_decode($request->original_data);
				$updateData = json_decode($request->update_data, true);
				// echo "<pre>",print_r($originalData),"THIS IS THE ORIGINAL DATA","</pre>";
				// echo "<pre>",print_r($updateData),"THIS IS THE EDITED DATA","</pre>";
				

				if($formIdentifier === "PR"){
					// update PR data
					$admin->startTrans();
						// update each data in the original data by update data
						foreach ($originalData as $item){
							// counter to access the updated data parallely
							if(isset($count)){$count++;}else{$count = 0;}

							$admin->update('lot_content_for_pr', 'ID', $item->item_id, array(
								'stock_no' => $updateData['items'][$count]['stockNo'],
								'unit' => $updateData['items'][$count]['unit'],
								'item_description' => $updateData['items'][$count]['description'],
								'quantity' => $updateData['items'][$count]['quantity'],
								'unit_cost' => $updateData['items'][$count]['unitCost'],
								'total_cost' => $updateData['items'][$count]['totalCost']
							));
						}

						// recompute for all affected lots
						foreach($updateData['affectedLots'] as $individualLot){
							// recompute lot cost
							$dataParts = explode("blyt322", $individualLot);
							//$dataParts[0] is the lot no and $dataParts[1] is the lot_id from the database.
							$lotId = $dataParts[1];
							$newLotCost =  $admin->recompute($request->form_origin, $dataParts[0]);
							$admin->update('lots', 'lot_id', $lotId, array(
								'lot_cost' => $newLotCost
							));
							
						}
						
						$admin->update('form_update_requests', 'ID', base64_decode(Input::get('request')) ,array(
							'response' => 'granted',
							'response_date' => Date::translate('raw', 'now'),
							'response_message' => "Request Granted"
						));

					//check if this PR/JO is registered as a project, if said so register a project log
					$isProject = $admin->like('projects', 'request_origin', $request->form_origin);
						if($isProject){

							$customRemark =  $request->action.' request on <a>'.$request->form_origin. '</a> was granted by "'.$admin->fullnameOf($admin->data()->account_id).'"';
							$admin->register('project_logs', array(
								'referencing_to' => $request->form_origin,
								'remarks' => $customRemark,
								'logdate' => Date::translate('raw', 'now'),
								'type' => 'IN'
							));
						}						

					$admin->endTrans();

					#send Dashboard notif
					$admin->register('notifications', array(
						'recipient' => $request->requested_by,
						'message' => "Revision request on {$request->form_origin} was granted.",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "my-forms?q=".base64_encode($request->form_origin)
					));
					notif(json_encode(array(
						'receiver' => $request->requested_by,
						'message' => "Revision request on {$request->form_origin} was granted.",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "my-forms?q=".base64_encode($request->form_origin)
					)));
				}else if($formIdentifier === "JO"){
					// update JO data

					$admin->startTrans();

						foreach ($originalData as $item){
							if(isset($count)){$count++;}else{$count = 0;}

							$admin->update('lot_content_for_jo', 'ID', $item->item_id, array(
								'header' => $updateData['items'][$count]['list'],
								'tags' => $updateData['items'][$count]['tags']
							));
						}

						#update new estimated cost and note per affected lot						
						foreach ($updateData['affectedLots'] as $affectedLot) {
							$dataParts = explode("blyt322", $affectedLot);
							$lotId = $dataParts[1];
							$newLotEstCost = $dataParts[2];
							$newNote = $dataParts[3];


							$admin->update('lots', 'lot_id', $lotId, array(
								'lot_cost' => $newLotEstCost,
								'note' => $newNote
							));

						}

						$admin->update('form_update_requests', 'ID', base64_decode(Input::get('request')) ,array(
							'response' => 'granted',
							'response_date' => Date::translate('raw', 'now'),
							'response_message' => "Request Granted"
						));						

						//check if this PR/JO is registered as a project, if said so register a project log
						$isProject = $admin->like('projects', 'request_origin', $request->form_origin);
							if($isProject){

								$customRemark =  $request->action.' request on <a>'.$request->form_origin. '</a> was granted by "'.$admin->fullnameOf($admin->data()->account_id).'".';
								$admin->register('project_logs', array(
									'referencing_to' => $request->form_origin,
									'remarks' => $customRemark,
									'logdate' => Date::translate('raw', 'now'),
									'type' => 'IN'
								));
							}						

					$admin->endTrans();

					#send Dashboard notif
					$admin->register('notifications', array(
						'recipient' => $request->requested_by,
						'message' => "Revision request on {$request->form_origin} was granted.",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "my-forms?q=".base64_encode($request->form_origin)
					));
					notif(json_encode(array(
						'receiver' => $request->requested_by,
						'message' => "Revision request on {$request->form_origin} was granted.",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "my-forms?q=".base64_encode($request->form_origin)
					)));					
					
					
				}
				


				


				
			}


			


		}else if(Input::get('action') === "Decline"){
			
			$admin->startTrans();
				$admin->update('form_update_requests', 'ID', base64_decode(Input::get('request')) ,array(
					'response' => 'declined',
					'response_date' => Date::translate('raw', 'now'),
					'response_message' => Input::get('remark')
				));

				//check if this PR/JO is registered as a project, if said so register a project log
				$isProject = $admin->like('projects', 'request_origin', $request->form_origin);
					if($isProject){

						$customRemark =  $request->action.' request on <a>'.$request->form_origin. '</a> was declined by "'.$admin->fullnameOf($admin->data()->account_id).'". click in this <a href="revision-request">link</a> to view details.';
						$admin->register('project_logs', array(
							'referencing_to' => $request->form_origin,
							'remarks' => $customRemark,
							'logdate' => Date::translate('raw', 'now'),
							'type' => 'IN'
						));
					}
			$admin->endTrans();

				#send Dashboard notif
				$admin->register('notifications', array(
					'recipient' => $request->requested_by,
					'message' => "Admin responded to your {$request->action} request on {$request->form_origin}",
					'datecreated' => Date::translate('test', 'now'),
					'seen' => 0,
					'href' => "revision-request?q=".base64_encode($request->ID)
				));
				notif(json_encode(array(
					'receiver' => $request->requested_by,
					'message' => "Your revision request on {$request->form_origin} was declined.",
					'date' => Date::translate(Date::translate('test', 'now'), '1'),
					'href' => "revision-request?q=".base64_encode($request->ID)
				)));
				#send SMS
				$requestor = $admin->get("enduser", array("edr_id", "=", $request->requested_by));
				sms($requestor->phone, "System", "Your revision request on {$request->form_origin} was declined.");


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