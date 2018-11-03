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
		
		try{


			

			$originalData =  $_POST['orig'];
			$editedData =  $_POST['edit'];

			$originalItemsEncoded =  json_encode($originalData['items'], JSON_FORCE_OBJECT);
			$editedItemsEncoded =  json_encode($editedData['items'], JSON_FORCE_OBJECT);

			// echo "<pre>",print_r($_POST),"</pre>";

			#check the status of the form first before anything else, also include the type of form to be submitted whether it is PR or JO

				// if($_POST['action'] == "update"){
				// 	$user->startTrans();

				// 	 $user->register('form_update_requests', array(

				// 		'form_origin' => $originalData['origin_form'],
				// 		'original_data' => $originalItemsEncoded,
				// 		'update_data' => $editedItemsEncoded,
				// 		'requested_by' => Session::get(Config::get('session/session_name')),
				// 		'date_registered' => Date::translate('test', 'now')

				// 	 ));

					
				// 		 # code...
					 
			
				// 	 $user->endTrans();

				// }else if($_POST['action'] == "delete"){
				// 	// $asd = "asd";
				// }




		}catch(Exception $e){
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


?>