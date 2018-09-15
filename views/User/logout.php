<?php

    require_once '../../core/init.php';

    $user = new User();
    $user->logout();
    Session::flash('Loggedout', 'You logged out successfuly.');
    Redirect::To('../../index');

?>