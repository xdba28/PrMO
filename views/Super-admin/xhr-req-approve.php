<?php

require_once('../../core/init.php');

$admin = new Admin(); 

if($admin->isLoggedIn()){
 //do nothing
}else{
   Redirect::To('../../blyte/acc3ss');
	die();
}

$sa = new Super_admin();

$r = $sa->get("account_requests", array("ID", "=", $_POST['id']));

if($r->ext_name == "none") $extention = "XXXXX";
else $extention = $r->ext_name;



$office = $sa->get("units", array("ID", "=", $r->designation));

try
{
	$salt = Hash::salt(32);
	$OTP = strtoupper(StringGen::password());
	$sa->startTrans();

	$sa->register("enduser", array(
		'edr_id' => $r->employee_id,
		'edr_fname' => $r->fname,
		'edr_mname' => $r->midle_name,
		'edr_lname' => $r->last_name,
		'edr_ext_name' => $extention,
		'edr_email' => $r->email,
		'phone' => $r->contact,
		'edr_designated_office' => $office->ID,
		'current_specific_office' => $r->specific_office,
		'edr_job_title' => $r->jobtitle,
		'edr_profile_photo' => NULL
	));

	$sa->register('edr_account', array(
		'account_id' => $r->employee_id,
		'username' => $r->username,
		'salt' => $salt,
		'userpassword' => Hash::make($OTP, $salt),
		'group_' => 1
	));
	
	$sa->delPersn("account_requests", array("employee_id", "=", $r->employee_id));
	
	$sa->endTrans();

	//send sms to enduser that his/her account is already created with the One-time password
	$customMessage = 'Hello '.$r->fname.'! Welcome to PrMO OPPTS. Your account request has been successfully verified, you may now login to '.Config::get('links/standarduser').' with your one-time password "'.$OTP.'".';
	sms($r->contact, "System", $customMessage);
}
catch(Exception $e)
{
	$data = [
		'success' => false
	];
	header("Content-type:application/json");
	echo json_encode($data);
}
?>