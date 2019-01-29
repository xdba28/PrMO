<?php
	date_default_timezone_set('Asia/Manila');
    session_start();

    $GLOBALS['config'] = [
        'mysql'    =>[
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
		],
		'links'	   =>[
			'blyte' => '192.168.137.103/prmo/blyte/acc3ss',
			'standarduser'	=> '192.168.137.103/prmo/'
		]
    ]; 
    //accounttype is the identifier if the account is new or not
    //usertype determines if the account is personnel or standard user account

	// vendor notif
	require_once "../../vendor/autoload.php";


    spl_autoload_register(function($class){
        require_once "../../classes/{$class}.php";
    });


		require_once "../../functions/sanitize.php";
		require_once "../../functions/notif.php";
		require_once "../../functions/sms.php";



        if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
            //echo 'User asked to be Remembered<br>';
            $cookie_name = Config::get('remember/cookie_name');

            $hash = Cookie::get($cookie_name);
            $hashCheck =  DB::getInstance()->get('users_session', array('hash', '=', $hash));


            if($hashCheck->count()){    //chech if there is a result from the hashCheck execution
                 $user = new User($hashCheck->first()->user_id);
                 $user->login();
            }

        }

?>