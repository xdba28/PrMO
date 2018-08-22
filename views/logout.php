<?php
    session_start();

    $GLOBALS['config'] = [
        'mysql'     =>[
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => '',
            'db'       => 'bubac_prmo'
        ],
        'session'  =>[
            'session_name'  => 'user',
            'token_name'    => 'token'
        ],
        'remember' =>[
            'cookie_name' => 'hash',
            'cookie_expiry' => 604800
        ]
    ];


    spl_autoload_register(function($class){
        require_once "../classes/{$class}.php";
    });

    $user = new Admin();
    $user->logout();
    Session::flash('Loggedout', 'You logged out successfuly.');
    Redirect::To('../blyte/acc3ss');
?>