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


	if(!empty($_POST))
	{
		try{
			$admin->startTrans();

			foreach ($_POST['col'] as $v) {

				$office_name = ($v['specific_office'] !== '') ? $v['specific_office'] : 'unset';
				$acro = ($v['acronym'] !== '') ? $v['acronym'] : 'N/A';

				$admin->update('offices', 'office_id', $v['id'], array(
					'specific_office' => $office_name,
					'acronym' => $acro
				));
			}

			$admin->endTrans();
			Syslog::put('System Offices Update');

		}catch(Exception $e){
			Syslog::put($e,null,'error_log');
			Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0001');
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