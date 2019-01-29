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

				$note = ($v['note'] !== "") ? $v['note'] : 'unset';

				$note_pos = ($v['n_pos'] !== "") ? $v['n_pos'] : 'unset';

				$verify = ($v['verify'] !== "") ? $v['verify'] : 'unset';

				$verify_pos = ($v['v_pos'] !== "") ? $v['v_pos'] : 'unset';

				$approve = ($v['approve'] !== "") ? $v['approve'] : 'unset';

				$approve_pos = ($v['a_pos'] !== "") ? $v['a_pos'] : 'unset';


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