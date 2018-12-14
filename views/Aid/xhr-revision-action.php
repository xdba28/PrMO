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

// echo "<pre>",print_r($admin->data()),"</pre>";
echo "<pre>".print_r($_POST)."</pre>";
// die();

if(!empty($_POST))
{
	try{
		$request = $admin->get('form_update_requests', array('ID', '=', Input::get('request')));

			//decode the required data for update/delete
			if($request->action === "delete"){
				$updateData = json_decode($request->update_data);
			}else if($request->action === "update"){
				$originalData =  json_decode($request->original_data);
				$updateData = json_decode($request->update_data);
			}

		if(Input::get('action') === "Grant"){

			if($request->action === "delete"){
				//decode the required data for update/delete
				$updateData = json_decode($request->update_data);
				echo "<pre>",print_r($updateData),"</pre>";
			}else if($request->action === "update"){
				//decode the required data for update/delete
				$originalData =  json_decode($request->original_data);
				$updateData = json_decode($request->update_data);
			}


		}else if(Input::get('action') === "Decline"){
			
			$admin->startTrans();
				$admin->update('form_update_requests', 'ID', Input::get('request') ,array(
					'response' => 'declined',
					'response_date' => Date::translate('raw', 'now'),
					'response_message' => Input::get('remark')

				));

				//check if this PR/JO is registered as a project, if said so register a project log

				$isProject = $admin->like('projects', 'request_origin', $request->form_origin);
				if($isProject){

					$customRemark =  $request->action.' request on <a>'.$request->form_origin. '</a> was declined by "'.$admin->fullnameOf($admin->data()->account_id).'". click in this <a href="revision-request?q='.Input::get('request').'">link</a> to view details.';
					$admin->register('project_logs', array(
						'referencing_to' => $request->form_origin,
						'remarks' => $customRemark,
						'logdate' => Date::translate('raw', 'now'),
						'type' => 'IN'
					));

					#send Dashboard notif
					$admin->register('notifications', array(
						'recipient' => $request->requested_by,
						'message' => "Admin responded to your {$request->action} request on {$request->form_origin}",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "revision-request?q={$request->ID}"
					));
				}else{
					#do nothing
					$admin->register('notifications', array(
						'recipient' => $request->requested_by,
						'message' => "Admin responded to your {$request->action} request on {$request->form_origin}",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "revision-request?q={$request->ID}"
					));
				}

			$admin->endTrans();
		}

		
		// $admin->startTrans();
		// $admin->endTrans();

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