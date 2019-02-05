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

		$swal_remark = '';

		// if workflow is 3
			// then update project to finished
			// notify the enduser that items are available in dbmps
		if($_POST['workflow'] === "3"){
			
			$user->update('projects', 'project_ref_no', $_POST['gds'], array(
				'accomplished' => '10',
				'workflow' => 'Project Finished',
				'project_status' => 'FINISHED'
			));

			// project logs
			$user->register('project_logs',  array(
				'referencing_to' => $_POST['gds'],
				'remarks' => "DECLARATION^FINISH^Project request ".$_POST['gds']." is dismissed for all items are available in DMB upon checking.",
				'logdate' => Date::translate('now', 'now'), 
				'type' => 'IN'
			));

			$end_users = $user->get('projects', array('project_ref_no', '=', $_POST['gds']));
			foreach(json_decode($end_users->end_user, true) as $end_user){

				$user->register('notifications', array(
					'recipient' => $end_user,
					'message' => "Project request ".$_POST['gds']." is dismissed for all items are available in DMB upon checking.",
					'datecreated' => Date::translate('test', 'now'),
					'seen' => 0,
					'href' => "project-details?refno=".base64_encode($_POST['gds'])
				));

				notif(json_encode(array(
					'receiver' => $end_user,
					'message' => "Project ".$_POST['gds']." is request dismissed for all items are available in DMB upon checking.",
					'date' => Date::translate(Date::translate('test', 'now'), '1'),
					'href' => "project-details?refno=".base64_encode($_POST['gds'])
				)));

				$enduserData = $user->get("enduser", array("edr_id", "=", $end_user));
				sms($enduserData->phone, "System", "Project ".$_POST['gds']." is request dismissed for all items are available in DMB upon checking.");
				
			}


		}elseif($_POST['workflow'] === "5"){

			$user->update('projects', 'project_ref_no', $_POST['gds'], array(
				'accomplished' => '6',
				'workflow' => 'Routing for signatories'
			));

			// project logs
			$user->register('project_logs',  array(
				'referencing_to' => $_POST['gds'],
				'remarks' => "Project queued to outgoing for signatories.",
				'logdate' => Date::translate('now', 'now'),
				'type' => 'IN'
			));


			$user->register('outgoing', array(
				'project' => $_POST['gds'],
				'transmitting_to' => 'Respective Offices',
				'specific_office' => 'Respective Offices',
				'remarks' => 'Routing for signatories',
				'transactions' => 'SIGNATURES',
				'date_registered' => Date::translate('test', 'now')
			));

			// register in outgoing

		}else if($_POST['workflow'] === "6"){

			// check in outgoing
				// if not, register
				// else alert user already in outgoing

			$project_outgoing = $user->get('outgoing', array('project', '=', $_POST['gds']));

			if($project_outgoing){

				$swal_remark = 'Project is already for outgoing.';

			}else{

				// project logs
				$user->register('project_logs',  array(
					'referencing_to' => $_POST['gds'],
					'remarks' => "Project queued to outgoing for signatories.",
					'logdate' => Date::translate('now', 'now'),
					'type' => 'IN'
				));

				$user->register('outgoing', array(
					'project' => $_POST['gds'],
					'transmitting_to' => 'Respective Offices',
					'specific_office' => 'Respective Offices',
					'remarks' => 'Routing for signatories',
					'transactions' => 'SIGNATURES',
					'date_registered' => Date::translate('test', 'now')
				));

			}

		}elseif($_POST['workflow'] === "7"){

			// check in outgoing
				// if not, register
				// else alert user already in outgoing

			$project_outgoing = $user->get('outgoing', array('project', '=', $_POST['gds']));

			if($project_outgoing){

				$swal_remark = 'Project is already for outgoing.';

			}else{

				// project logs
				$user->register('project_logs',  array(
					'referencing_to' => $_POST['gds'],
					'remarks' => "Project queued to outgoing for signatories.",
					'logdate' => Date::translate('now', 'now'),
					'type' => 'IN'
				));

				$user->update('projects', 'project_ref_no', $_POST['gds'], array(
					'accomplished' => '8',
					'workflow' => 'Routing for signatories'
				));

				$user->register('outgoing', array(
					'project' => $_POST['gds'],
					'transmitting_to' => 'Respective Offices',
					'specific_office' => 'Respective Offices',
					'remarks' => 'Routing for signatories',
					'transactions' => 'SIGNATURES',
					'date_registered' => Date::translate('test', 'now')
				));

			}

		}elseif($_POST['workflow'] === "8"){

			// check in outgoing
				// if not, register
				// else alert user already in outgoing

			$project_outgoing = $user->get('outgoing', array('project', '=', $_POST['gds']));

			if($project_outgoing){

				$swal_remark = 'Project is already for outgoing.';

			}else{

				$user->update('projects', 'project_ref_no', $_POST['gds'], array(
					'accomplished' => '9',
					'workflow' => 'For Conforme of Winning Bidder/s'
				));

				// project logs
				$user->register('project_logs',  array(
					'referencing_to' => $_POST['gds'],
					'remarks' => "Notice of Award is now for conforme of winning bidder/s",
					'logdate' => Date::translate('now', 'now'),
					'type' => 'IN'
				));

				$user->register('outgoing', array(
					'project' => $_POST['gds'],
					'transmitting_to' => 'Winning Bidder/s',
					'specific_office' => 'Respective Offices',
					'remarks' => 'For Conforme of Winning Bidder/s',
					'transactions' => 'SIGNATURES',
					'date_registered' => Date::translate('test', 'now')
				));

				// get suppliers

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
						'message' => "Notice of Award of project ".$_POST['gds']." is now available.",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "project-details?refno=".base64_encode($_POST['gds'])
					));
					notif(json_encode(array(
						'receiver' => $end_user,
						'message' => "Notice of Award of project ".$_POST['gds']." is now available.",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "project-details?refno=".base64_encode($_POST['gds'])
					)));
					
					#sms
					$enduserData = $user->get("enduser", array("edr_id", "=", $end_user));
					sms($enduserData->phone, "System", "Project ".$_POST['gds']." is now for conforme of winning bidders.");
				}
			}

		}elseif($_POST['workflow'] === "9"){
			
			$user->update('projects', 'project_ref_no', $_POST['gds'], array(
				'accomplished' => '10',
				'workflow' => 'Project Finished',
				'project_status' => 'FINISHED'
			));

			// project logs
			$user->register('project_logs',  array(
				'referencing_to' => $_POST['gds'],
				'remarks' => "DECLARATION^FINISH^Project is now finished.",
				'logdate' => Date::translate('now', 'now'),
				'type' => 'IN'
			));

			$end_users = $user->get('projects', array('project_ref_no', '=', $_POST['gds']));
				

			foreach(json_decode($end_users->end_user, true) as $end_user){
				$user->register('notifications', array(
					'recipient' => $end_user,
					'message' => "",
					'datecreated' => Date::translate('test', 'now'),
					'seen' => 0,
					'href' => "project-details?refno=".base64_encode($_POST['gds'])
				));
				notif(json_encode(array(
					'receiver' => $end_user,
					'message' => "",
					'date' => Date::translate(Date::translate('test', 'now'), '1'),
					'href' => "project-details?refno=".base64_encode($_POST['gds'])
				)));

				$enduserData = $user->get("enduser", array("edr_id", "=", $end_user));
				sms($enduserData->phone, "System", "Project ".$_POST['gds']." is finally finished and completed all required processes and transactions in PrMO.");
			}

		}

		$user->endTrans();

		echo json_encode([
			'success' => 'true',
			'remark' => $swal_remark
		]);
	}else{
		echo json_encode(['success' => 'error']);
	}
}
catch(Exception $e)
{
	echo json_encode(['success' => false, 'error' => $e]);
}

?>