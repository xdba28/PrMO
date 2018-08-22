<?php

    session_start();
    date_default_timezone_set('Asia/Manila');

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
        require_once "classes/{$class}.php";
    });

        require_once "functions/sanitize.php";

        if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
            //echo 'User asked to be Remembered<br>';
            $cookie_name = Config::get('remember/cookie_name');

            $hash = Cookie::get($cookie_name);
            $hashCheck =  DB::getInstance()->get('users_session', array('hash', '=', $hash));

            // echo $hashCheck->first()->hash, "<br>";   //check if the current hash in the database matches the cookie in the browser
            // echo $hash;

            if($hashCheck->count()){    //chech if there is a result from the hashCheck execution
                 $user = new User($hashCheck->first()->user_id);
                 $user->login();
            }

        }else if(Session::exists(Config::get('session/session_name')) && isset($_SESSION[Config::get('session/session_name')])){

            $user = new User();

            $found = $user->findById($_SESSION[Config::get('session/session_name')]);

            if($found){
                Redirect::To('views/User/Dashboard');
            }else{
                 Session::flash('error403', 'Forbidden Access');
                 Redirect::To(403);
            }



        }


?>