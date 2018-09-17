<?php
require_once "../../core/init.php";
require_once "../vendor/autoload.php";

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$admin = new Admin();

if($admin->isLoggedIn());
else{
	Redirect::To('../../index');
	die();
}

?>