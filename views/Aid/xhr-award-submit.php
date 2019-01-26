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
			$date = explode("/", $_POST['delivery']['date']);
			$user->register('award', array(
				'canvass_form_id' => $_POST['cvf_id'],
				'place' => htmlspecialchars($_POST['delivery']['place']),
				'date' => $date[2].'-'.$date[0].'-'.$date[1],
				'delivery_term' => htmlspecialchars($_POST['delivery']['term']),
				'payment' => htmlspecialchars($_POST['delivery']['pay'])
			));

			// check each item if awarded
			// if not continue with awarding items
			// check if all lots are awarded

			if($user->checkProjectAward($_POST['gds'])){
				$user->update('projects', 'project_ref_no', $_POST['gds'], array(
					'accomplished' => '7',
					'workflow' => 'Finalization of Notice of Award, Purchase Order/Letter Order, and Request for OS'
				));
		
				$user->register('project_logs',  array(
					'referencing_to' => $_POST['gds'],
					'remarks' => "AWARD^Notice of Award^Notice of Award to Suppliers is now available.",
					'logdate' => Date::translate('now', 'now'),
					'type' => 'IN'
				));

				$end_users = $user->get('projects', array('project_ref_no', '=', $_POST['gds']));
				foreach(json_decode($end_users, true) as $end_user){
					$user->register('notifications', array(
						'recipient' => $end_user,
						'message' => "Notice of Award of project ".$_POST['gds']." is now available",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "project-details?refno=".base64_encode($project_ref_no)
					));
					notif(json_encode(array(
						'receiver' => $end_user,
						'message' => "Notice of Award of project ".$_POST['gds']." is now available",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "project-details?refno=".base64_encode($project_ref_no)
					)));
				}

				// notif
			}

		}else{
			// update this supplier that they have been awarded
			$user->update('canvass_supplier', 'cvsp_id', $_POST['supplier'], array(
				'award' => '1',
			));
			
			// register award details
			$date = explode("/", $_POST['delivery']['date']);
			$user->register('award', array(
				'canvass_form_id' => $_POST['cvf_id'],
				'place' => htmlspecialchars($_POST['delivery']['place']),
				'date' => $date[2].'-'.$date[0].'-'.$date[1],
				'delivery_term' => htmlspecialchars($_POST['delivery']['term']),
				'payment' => htmlspecialchars($_POST['delivery']['pay'])
			));

			// check if all lots are awarded
			// By lot award doesn't need to update each item
	
			// update project
			// notif users
		
			if($user->checkProjectAward($_POST['gds'])){
				$user->update('projects', 'project_ref_no', $_POST['gds'], array(
					'accomplished' => '7',
					'workflow' => 'Finalization of Notice of Award, Purchase Order/Letter Order, and Request for OS'
				));
		
				$user->register('project_logs',  array(
					'referencing_to' => $_POST['gds'],
					'remarks' => "AWARD^Notice of Award^Notice of Award to Suppliers is now available.",
					'logdate' => Date::translate('now', 'now'),
					'type' => 'IN'
				));


				$end_users = $user->get('projects', array('project_ref_no', '=', $_POST['gds']));
				foreach(json_decode($end_users->end_user, true) as $end_user){
					$user->register('notifications', array(
						'recipient' => $end_user,
						'message' => "Notice of Award of project ".$_POST['gds']." is now available",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "project-details?refno=".base64_encode($_POST['gds'])
					));
					notif(json_encode(array(
						'receiver' => $end_user,
						'message' => "Notice of Award of project ".$_POST['gds']." is now available",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "project-details?refno=".base64_encode($_POST['gds'])
					)));
				}

				// notif

			}
		}
		
		$user->endTrans();
		
		echo json_encode(['success' => 'true']);
	}else{
		echo json_encode(['success' => 'error']);
	}
}
catch(Exception $e)
{
	echo json_encode(['success' => false, 'error' => $e]);
}

?>