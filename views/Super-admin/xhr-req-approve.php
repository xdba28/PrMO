<?php

require_once('../../core/init.php');

$user = new Admin(); 

if($user->isLoggedIn()){
 //do nothing
}else{
   Redirect::To('../../blyte/acc3ss');
	die();
}



?>