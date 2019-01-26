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

				$swal_remark = 'Project is status is already in for outgoing.';

			}else{

				$user->register('outgoing', array(
					'project' => $_POST['gds'],
					'transmitting_to' => 'For signatories',
					'specific_office' => 'For signatories',
					'remarks' => 'For signatories',
					'transactions' => 'SIGNATURES',
					'date_registered' => Date::translate('test', 'now')
				));

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