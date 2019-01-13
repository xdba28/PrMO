<?php
require_once('../../core/init.php');
header("Content-type:application/json");

$sa = new Super_admin();

if($sa->isLoggedIn()){
 //do nothing
}else{
   Redirect::To('../../blyte/acc3ss');
	die();
}

if(!empty($_POST))
{

	// echo "<pre>",print_r($_POST),"</pre>";
	// die();
	try{
				

		$salt = Hash::salt(32);
		$newPass = htmlspecialchars($_POST['newPass']);

		if(Input::get("user") === "user"){
			$sa->startTrans();
				$sa->update("edr_account", "account_id", Input::get("id"), array(
					'salt' => $salt,
					'userpassword' => Hash::make(Input::get("newPass"), $salt)
				));
			$sa->endTrans();

			//SEND SMS
			if($user = $sa->get("enduser", array("edr_id", "=", Input::get("id")))){
					$customMessage = 'Hello '.$user->edr_fname.' Your Password has been reset to "'.$newPass.'" by the Super Admin.';
					sms($user->phone, "Super Admin", $customMessage);
			}
		}else{
			$sa->startTrans();
				$sa->update("prnl_account", "account_id", Input::get("id"), array(
					'salt' => $salt,
					'userpassword' => Hash::make(Input::get("newPass"), $salt)
				));
			$sa->endTrans();
			
			//SEND SMS
			if($user = $sa->get("personnel", array("prnl_id", "=", Input::get("id")))){
				$customMessage = 'Hello '.$user->prnl_fname.' Your Password has been reset to "'.$newPass.'" by the Super Admin.';
				sms($user->phone, "Super Admin", $customMessage);
			}
		}
		

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