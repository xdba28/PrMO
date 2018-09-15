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

$salt = Hash::salt(32);

$office = $sa->get("units", array("ID", "=", $r->designation));

try
{
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
		'edr_job_title' => "none",
		'edr_profile_photo' => NULL
	));

	$sa->register('edr_account', array(
		'account_id' => $r->employee_id,
		'username' => $r->username,
		'salt' => $salt,
		'userpassword' => Hash::make($r->userpassword, $salt),
		'group' => 1
	));
	$sa->endTrans();
}
catch(Exception $e)
{
	echo $e;
}


?>