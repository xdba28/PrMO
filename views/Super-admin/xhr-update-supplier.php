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

			foreach($_POST['col'] as $v){

				$type = ($v['type'] === "1") ? "Supplies" : "Services";

				$admin->update('supplier', 's_id', $v['id'], array(
					'name' => $v['name'],
					'address' => $v['address'],
					'tin' => $v['tin'],
					'type' => $type
				));
			}

			$admin->endTrans();
			Syslog::put('System Supplier Update');

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