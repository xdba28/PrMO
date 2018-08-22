<?php

    require_once'core/init.php';


    // echo Session::get(Config::get('session/session_name'));
     echo $_SESSION['user'];


    $user = new User("2015-11583"); // current user;

    echo $user->data()->username;



?>