<?php
require_once('../../core/init.php');
header("Content-type:application/json");

$user = new Admin();

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

try
{
	if(!empty($_POST)){

		$user->startTrans();
		$redirect = false;

		if($_POST['lot_type'] === "1"){
			// update this supplier that they have been awarded
			$user->update('canvass_supplier', 'cvsp_id', $_POST['supplier'], array(
				'award' => '1',
			));

			// update status of each item as awarded
			foreach($_POST['items'] as $item){
				$user->awardUpdateItem($_POST['supplier'], $item, $_POST['req_type'], $_POST['cvf_id']);
			}

			// register delivery details
			// $date = explode("/", $_POST['delivery']['date']);
			$user->register('award', array(
				'canvass_form_id' => $_POST['cvf_id'],
				'place' => htmlspecialchars($_POST['delivery']['place']),
				'delivery_term' => htmlspecialchars($_POST['delivery']['term']),
				'payment' => htmlspecialchars($_POST['delivery']['pay']),
				'dateawarded' => Date::translate('now', 'now')
			));

			// check each item if awarded
			// if not continue with awarding items
			// check if all lots are awarded

			if($user->checkProjectAward($_POST['gds'])){
				$user->update('projects', 'project_ref_no', $_POST['gds'], array(
					'accomplished' => '7',
					'workflow' => 'Finalization of Notice of Award, Purchase Order/Letter Order, and Request for OS'
				));
		
				// bac reso
				$user->register('project_logs',  array(
					'referencing_to' => $_POST['gds'],
					'remarks' => "AWARD^BAC Resolution^BAC Resolution declairing winning bidder is now available",
					'logdate' => Date::translate('now', 'now'),
					'type' => 'IN'
				));

			}

		}else{
			// update this supplier that they have been awarded
			$user->update('canvass_supplier', 'cvsp_id', $_POST['supplier'], array(
				'award' => '1',
			));
			
			// register award details
			// $date = explode("/", $_POST['delivery']['date']);
			$user->register('award', array(
				'canvass_form_id' => $_POST['cvf_id'],
				'place' => htmlspecialchars($_POST['delivery']['place']),
				'delivery_term' => htmlspecialchars($_POST['delivery']['term']),
				'payment' => htmlspecialchars($_POST['delivery']['pay']),
				'dateawarded' => Date::translate('now', 'now')
			));

			// check if all lots are awarded
			// By lot award doesn't need to update each item
	
			// update project
			$award = true;	
			foreach($user->checkProjectAward($_POST['gds']) as $check){
				if($check === false){
					$award = false;
				}
			}
		
			if($award){
				$user->update('projects', 'project_ref_no', $_POST['gds'], array(
					'accomplished' => '7',
					'workflow' => 'Finalization of Notice of Award, Purchase Order/Letter Order, and Request for OS'
				));
				
				// bac reso log
				$user->register('project_logs',  array(
					'referencing_to' => $_POST['gds'],
					'remarks' => "AWARD^BAC Resolution^BAC Resolution declairing winning bidder is now available",
					'logdate' => Date::translate('now', 'now'),
					'type' => 'IN'
				));

				

				// notif to be moved in step 8

				// if all lots awarded redirect
				$redirect = true;
				Session::flash('update', 'Successfully awarded supplier.');


			}
		}
		
		$user->endTrans();
		
		echo json_encode(['success' => 'true', 'finish' => $redirect]);
	}else{
		echo json_encode(['success' => 'error']);
	}
}
catch(Exception $e)
{
	echo json_encode(['success' => false, 'error' => $e]);
}

?>