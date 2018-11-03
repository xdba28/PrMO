<?php
require_once('../../core/init.php');

$user = new User(); 

if($user->isLoggedIn()){
 //do nothing
}else{
   Redirect::To('../../index');
	die();
}

	if(!empty($_POST)){
		
		//$sample = json_decode($_POST, true);
		
		echo "<pre>".print_r($_POST['orig']['items'])."</pre>";
		
		//echo $_POST['orig'];

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


?>