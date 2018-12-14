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

		foreach ($_POST['col'] as $v){

			$name = ($v['name'] !== "") ? htmlspecialchars($v['name']) : 'unset';
			$position = ($v['position'] !== "") ? htmlspecialchars($v['position']) : 'unset';

			$office_id = $admin->get('units', array('ID', '=', $v['unit_office']));

			switch($v['type']){
				case 1:
					$type = "GEN";
					break;
				case 2:
					$type = "GDS";
					break;
				case 3:
					$type = "INF";
					break;
				default:
					$type = "unset";
					break;
			}

			$admin->update('commitee', 'ID', $v['id'], array(
				'name' => $name,
				'position' => $position,
				'type' => $type,
				'unit_office' => $office_id->ID
			));
		}

		$admin->endTrans();

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