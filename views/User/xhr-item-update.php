<?php
require_once('../../core/init.php');

$user = new User(); 

if($user->isLoggedIn()){
 //do nothing
}else{
   Redirect::To('../../index');
	die();
}

	echo "<pre>",print_r($_POST),"</pre>";
	die();

	foreach ($_POST['del']['lotref'] as $ref) {
		$test = explode('blyt322', $ref);
		echo "<pre>",print_r($test),"</pre>";
	}
	
	die();

	if(!empty($_POST)){
		
		try{

			$notif = false;
			$delLot = false;
			$delproj = false;

			if($_POST['action'] === 'update'){
				//Update Action


				// this is the json formatted list of items to be updated
				$originalData =  $_POST['orig'];
				$editedData =  $_POST['edit'];

				// decode the given date
				$originalItemsEncoded =  json_encode($originalData['items'], JSON_FORCE_OBJECT);
				$editedItemsEncoded =  json_encode($editedData['items'], JSON_FORCE_OBJECT);
				$wholeEditEncoded =  json_encode($editedData, JSON_FORCE_OBJECT);
	

					if($_POST['orig']['status'] == "unreceived"){
					// update process for unreceived form #DIRECT UPDATE

						if($_POST['orig']['type'] == "PR"){
							#update trans for pr
							$user->startTrans();

								foreach ($originalData['items'] as $item) {
			
									if(isset($count)){$count++;}else{$count = 0;}

									$user->update('lot_content_for_pr', 'ID', $item['item_id'], array(
										
										'stock_no' => $editedData['items'][$count]['stockNo'],
										'unit' => $editedData['items'][$count]['unit'],
										'item_description' => $editedData['items'][$count]['description'],
										'quantity' => $editedData['items'][$count]['quantity'],
										'unit_cost' => $editedData['items'][$count]['unitCost'],
										'total_cost' => $editedData['items'][$count]['totalCost']

									));
									
								}
								
								foreach($_POST['orig']['lotref'] as $individualLot){
									// recompute lot cost
									$dataParts = explode("blyt322", $individualLot);
									//$dataParts[0] is the lot no and $dataParts[1] is the lot_id from the database.
									$lotId = $dataParts[1];
									$newLotCost =  $user->recompute($originalData['origin_form'], $dataParts[0]);
									$user->update('lots', 'lot_id', $lotId, array(
										'lot_cost' => $newLotCost
									));
									
								}

							$user->endTrans();


						}else if($_POST['orig']['type'] == "JO"){
							#update trans for jo
							$user->startTrans();
		
								foreach ($originalData['items'] as $item){
		
									if(isset($count)){$count++;}else{$count = 0;}
									
									$user->update('lot_content_for_jo', 'ID', $item['item_id'], array(
										'header' => $editedData['items'][$count]['list'],
										'tags' => $editedData['items'][$count]['tags']
									));

								}

								#update new estimated cost and note per affected lot
								// NOTE: below code is incomplete. waiting for new lot note.
									foreach ($editedData['affectedLots'] as $affectedLot) {
										$dataParts = explode("blyt322", $affectedLot);
										$lotId = $dataParts[1];
										$newLotEstCost = $dataParts[2];
										$newNote = $dataParts[3];

										$user->update('lots', 'lot_id', $lotId, array(
											'lot_cost' => $newLotEstCost,
											'note' => $newNote
										));

									}

		
							$user->endTrans();
						}

					}else if($_POST['orig']['status'] == "received"){
					//update process for received form #INDIRECT UPDATE
				
						if($_POST['orig']['type'] == "PR"){
							#register request
							$user->startTrans();

								$user->register('form_update_requests', array(
		
									'form_origin' => $originalData['origin_form'],
									'original_data' => $originalItemsEncoded,
									'update_data' => $wholeEditEncoded,
									'action' => 'update',
									'purpose' => $_POST['remark'],
									'requested_by' => Session::get(Config::get('session/session_name')),
									'date_registered' => Date::translate('test', 'now')
		
								));

								# send notif to aids
								$notif = true;
				
							$user->endTrans();	

						}else if($_POST['orig']['type'] == "JO"){
							#register request

							$user->startTrans();

								$user->register('form_update_requests', array(

									'form_origin' => $originalData['origin_form'],
									'original_data' => $originalItemsEncoded,
									'update_data' => $wholeEditEncoded,
									'action' => 'update',
									'purpose' => $_POST['remark'],
									'requested_by' => Session::get(Config::get('session/session_name')),
									'date_registered' => Date::translate('test', 'now')

								));

							$user->endTrans();

						}

			}
		}else if($_POST['action'] === 'delete'){
				//Delete Action

				#!!NOTE check the form if we still have any items left in lot or the form. else delete lot or form
				
				// this is the json formatted list of items to be updated
				$toBeDeletedItems =  json_encode($_POST['del']['items'], JSON_FORCE_OBJECT);
				$wholeDeleteEncoded = json_encode($_POST['del'], JSON_FORCE_OBJECT);

					if($_POST['del']['status'] == "unreceived"){
					// deletion process for unreceived form

					$user->startTrans();
						if($_POST['del']['type'] == "PR"){
							#direct delete
							foreach ($_POST['del']['items'] as $item) {
								$user->delete('lot_content_for_pr', array('ID', '=', $item['item_id']));
							}
							#Recompute lot_cost after deletion of some content from "lot_content_for_pr"
							foreach ($_POST['del']['lotref'] as $affectedLot) {
								$lotnoAndId = explode("blyt322" , $affectedLot);
								$lotNo = $lotnoAndId[0];
								$lotId =  $lotnoAndId[1];
								$newLotCost = $user->recompute($_POST['del']['origin_form'], $lotNo);

								$user->update('lots', 'lot_id', $lotId, array(
									'lot_cost' => $newLotCost
								));								
							}
						}else if($_POST['del']['type'] == "JO"){
							#direct delete
							foreach ($_POST['del']['items'] as $item) {
								$user->delete('lot_content_for_jo', array('ID', '=', $item['item_id']));
							}
							#NOTE:Update new estimated lot cost after deletion of some content from "lot_content_forjo"
						}
					$user->endTrans();


					#DENVER: Pop a basic swal saying deletion successful

							#check if lots affected still has any item left after the deletion of specific items.
							foreach($_POST['del']['lotref'] as $affectedLot){

								$lotnoAndId = explode("blyt322", $affectedLot);
								$remainingItems = $user->checkItems($_POST['del']['origin_form'], $lotnoAndId[0], $_POST['del']['type']);

									if($remainingItems->result){
										//there is still some item/s left
										#do nothing
									}else{
										//nothing is left in this lot
										#delete this lot
										$user->startTrans();
											$user->delete('lots', array('lot_id', '=', $lotnoAndId[1]));
										$user->endTrans();

										#DENVER: Pop a swal "lot $lotnoAndId[0] was deleted for it has no items left"
										$delLot = "Lot ".$lotnoAndId[0]." was deleted for it has no items left.";
									}
							}
					

							#check if this request form still has any lot left after deletion of empty lots
							$hasLot = $user->getAll('lots', array('request_origin', '=', $_POST['del']['origin_form']));
							if($hasLot){
								//there is still some lot/s left
								#do nothing
							}else{
								//nothing is left in this request form
								#delete this request
								$user->startTrans();			
									$user->delete('project_request_forms', array('form_ref_no', '=', $_POST['del']['origin_form']));
								$user->endTrans();

								#DENVER: POP a Toustr (top, fullwidth, error, no close button)"The form $_POST['del']['origin_form']  was deleted because there were no items left after the action."
								$delproj = "The form ".$_POST['del']['origin_form']." was deleted because there was no items left after the action.";
							}
																			


					}else if($_POST['del']['status'] == "received"){
					// deletion process for received forms
						if($_POST['del']['type'] == "PR"){

							#register delete request
							$user->register('form_update_requests', array(

								'form_origin' => $_POST['del']['origin_form'],
								'original_data' => 'NA',
								'update_data' => $wholeDeleteEncoded,
								'action' => 'delete',
								'purpose' => $_POST['remark'],
								'requested_by' => Session::get(Config::get('session/session_name')),
								'date_registered' => Date::translate('test', 'now')	

							));


						}else if($_POST['del']['type'] == "JO"){

							#register delete request
							$user->register('form_update_requests', array(

								'form_origin' => $_POST['del']['origin_form'],
								'original_data' => 'NA',
								'update_data' => $wholeDeleteEncoded,
								'action' => 'delete',
								'purpose' => $_POST['remark'],
								'requested_by' => Session::get(Config::get('session/session_name')),
								'date_registered' => Date::translate('test', 'now')	

							));

						}					
					
					}				
			}



		}catch(Exception $e){
			$e->getMessage()."A Fatal Error Occured";
			$data = ['success' => 'error', 'codeError' => $e];
			header("Content-type:application/json");
			echo json_encode($data);
			// log files
		}

		$data = ['success' => true, 'notif' => $notif, 'delLot' => $delLot, 'delproj' => $delproj];
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