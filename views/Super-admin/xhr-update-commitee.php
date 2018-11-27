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
			if($v['note'] !== "") $note = htmlspecialchars($v['note']);
			else $note = 'unset';

			if($v['n_pos'] !== "") $note_pos = htmlspecialchars($v['n_pos']);
			else $note_pos = 'unset';

			if($v['verify'] !== "") $verify = htmlspecialchars($v['verify']);
			else $verify = 'unset';

			if($v['v_pos'] !== "") $verify_pos = htmlspecialchars($v['v_pos']);
			else $verify_pos = 'unset';

			if($v['approve'] !== "") $approve = htmlspecialchars($v['approve']);
			else $approve = 'unset';

			if($v['a_pos'] !== "") $approve_pos = htmlspecialchars($v['a_pos']);
			else $approve_pos = 'unset';


			$admin->update('units', 'acronym', $v['acronym'], array(
				'note' => $note,
				'note_position' => $note_pos,
				'verifier' => $verify,
				'verifier_position' => $verify_pos,
				'approving' => $approve,
				'approving_position' => $approve_pos
			));
		}

		$admin->endTrans();

	}catch(Exception $e){
		$e->getMessage()."A Fatal Error Occured";
		$data = ['success' => 'error', 'codeError' => $e];
		echo json_encode($data);
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