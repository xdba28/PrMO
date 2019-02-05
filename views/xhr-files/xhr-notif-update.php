<?php
require_once('../../core/init.php');

$user = new User();
$admin = new Admin();

if($user->isLoggedIn())
{
	if(!empty($_POST))
	{
		try{
			$user->startTrans();

				$user->update('notifications', 'recipient', $_POST['id'], array(
					'seen' => 1 
				));

			$user->endTrans();

		}catch(Exception $e){
			Syslog::put($e,null,'error_log');
			Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0001');
			$e->getMessage()."A Fatal Error Occured";
			$data = ['success' => 'error', 'codeError' => $e];
			header("Content-type:application/json");
			echo json_encode($data);
			// log files
		}
		

		$data = ['success' => true];
		header("Content-type:application/json");
		echo json_encode($data);
	}
	else
	{
		$data = ['success' => false];
		header("Content-type:application/json");
		echo json_encode($data);
	}
}elseif($admin->isLoggedIn()){
	if(!empty($_POST))
	{
		try{
			$admin->startTrans();

				$admin->update('notifications', 'recipient', $_POST['id'], array(
					'seen' => 1 
				));

			$admin->endTrans();

		}catch(Exception $e){
			Syslog::put($e,null,'error_log');
			Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0002');
			$e->getMessage()."A Fatal Error Occured";
			$data = ['success' => 'error', 'codeError' => $e];
			header("Content-type:application/json");
			echo json_encode($data);
			// log files
		}
		

		$data = ['success' => true];
		header("Content-type:application/json");
		echo json_encode($data);
	}
	else
	{
		$data = ['success' => false];
		header("Content-type:application/json");
		echo json_encode($data);
	}
}else{
		Redirect::To('../../blyte/acc3ss');
	die();

}


?>