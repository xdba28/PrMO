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
	if(!empty($_GET['id'])){
		$date = explode("/", $_GET['id']);
		$projects = $user->monthlyReport($date[0]);
		$bool = ($projects) ? true : false;
		echo json_encode([
			'success' => 'true',
			'check' => $bool
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