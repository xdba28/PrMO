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
		
		if($_POST['workflow'] === "5"){

			$user->update('projects', 'project_ref_no', $_POST['gds'], array(
				'accomplished' => '6',
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

			// register in outgoing

		}else if($_POST['workflow'] === "6"){

			// check in outgoing
				// if not, register
				// else alert user already in outgoing

			$project_outgoing = $user->get('outgoing', array('project', '=', $_POST['gds']));

			if($project_outgoing){

				$swal_remark = 'Project is already for outgoing.';

			}else{

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

				$user->update('projects', 'project_ref_no', $_POST['gds'], array(
					'accomplished' => '8',
					'workflow' => 'For Conforme of Winning Bidder/s'
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

				$user->register('outgoing', array(
					'project' => $_POST['gds'],
					'transmitting_to' => 'Winning Bidder/s',
					'specific_office' => 'Respective Offices',
					'remarks' => 'For Conforme of Winning Bidder/s',
					'transactions' => 'SIGNATURES',
					'date_registered' => Date::translate('test', 'now')
				));
			}

		}elseif($_POST['workflow'] === "9"){
			
			$user->update('projects', 'project_ref_no', $_POST['gds'], array(
				'accomplished' => '10',
				'workflow' => 'Project Finished',
				'project_status' => 'FINISHED'
			));

			// notif and sms
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