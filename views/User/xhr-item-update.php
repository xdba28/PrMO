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
		
		$sample = json_encode($_POST);
		// Actual Output
		// $_POST['editType'] -> "PR" || "JO" || "DEL"
		// $_POST['projId'] -> JO2018-B4EI73:42:34 === Project_ID:LotNo:ItemNo
		
		// PR
		// Input::get('prUpStk') stock
		// Input::get('prUpUnt') unit
		// Input::get('prUpDesc') description
		// Input::get('prUpQty') quantity
		// Input::get('prUpUc') unit cost
		// Input::get('prUpTc') total cost

		// JO
		// Input::get('joList') list title
		// Input::get('joCost') estimated cost
		// Input::get('joTags') tags
		// Input::get('joNotes') notes

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