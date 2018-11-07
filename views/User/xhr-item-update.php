<?php
require_once('../../core/init.php');

$user = new User(); 

if($user->isLoggedIn()){
 //do nothing
}else{
   Redirect::To('../../index');
	die();
}

	if(!empty($_POST)){
		
		try{

			$notif = false;
			// echo $_POST['orig']['type'];
			// echo $_POST['orig']['status'];
			

			$originalData =  $_POST['orig'];
			$editedData =  $_POST['edit'];

			$originalItemsEncoded =  json_encode($originalData['items'], JSON_FORCE_OBJECT);
			$editedItemsEncoded =  json_encode($editedData['items'], JSON_FORCE_OBJECT);

			// echo "<pre>",print_r($_POST),"</pre>";


			
			if($_POST['orig']['status'] == "unreceived"){
				if($_POST['action'] == "update"){
					
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

								$newLotCost =  $user->recompute($originalData['origin_form'], $individualLot);

								$user->updateLots($individualLot, $originalData['origin_form'], array(
										$newLotCost,
										"none"
								));
								
							}


						$user->endTrans();


						// echo "<pre>",print_r(array_diff($originalData['items'][0],$originalData['items'][2])),"</pre>";
					}else{
						#update trans for jo
						$user->startTrans();
	
							foreach ($originalData['items'] as $item) {
	
								if(isset($count)){$count++;}else{$count = 0;}
								
	
								$user->update('lot_content_for_jo', 'ID', $item['item_id'], array(
									'header' => $editedData['items'][$count]['list'],
									'tags' => $editedData['items'][$count]['tags']
								));


								$user->updateLots($editedData['items'][$count]['lot'], $originalData['origin_form'], array(
									$editedData['items'][$count]['cost'],
									$editedData['items'][$count]['notes']

								));
							}

							
	
						$user->endTrans();
					}

				}else if($_POST['action'] == "delete"){
					#rekta delete
				}

			}else{

				if($_POST['action'] == "update"){
					$user->startTrans();

					 $user->register('form_update_requests', array(

						'form_origin' => $originalData['origin_form'],
						'original_data' => $originalItemsEncoded,
						'update_data' => $editedItemsEncoded,
						'requested_by' => Session::get(Config::get('session/session_name')),
						'date_registered' => Date::translate('test', 'now')

					 ));

					
						 # code...
					//  send notif to aids
					$notif = true;
			
					$user->endTrans();

				}else if($_POST['action'] == "delete"){
					#register delete request
				}				

			}
			






		}catch(Exception $e){
			$e->getMessage()."A Fatal Error Occured";
			$data = ['success' => 'error', 'codeError' => $e];
			header("Content-type:application/json");
			echo json_encode($data);
			// log files
		}

		$data = ['success' => true, 'notif' => $notif];
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