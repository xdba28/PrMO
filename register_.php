<?php 
//sample code for super admin registering a new enduser account

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

        Session::flash('error403', 'Forbidden Access');
        Redirect::To(403);


    }

        /* Codes on top are the actual init.php content */

    if (Input::exists()){
        if(Token::check(Input::get('token'))){
            //allow to submit the form


            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'ID' => [
                    'required' => true,
                    'min'      => '2',
                    'max'      => '50',
                    'unique'   => 'prnl_account'
                ],
                'username' => [
                    'required' => true,
                    'min'      => '2',
                    'max'      => '50',
                    'unique'   => 'prnl_account'
                ],
                'password' => [
                    'required' => true,
                    'min'      => '6'
                ],
                'password_again' => [
                    'required'   => true,
                    'matches'    => 'password'
                ],
                'group' => [
                    'required'   => true
                ],
                'name' => [
                    'required' => true,
                    'min'      => '2',
                    'max'      => '50'                
                ]
    
            ));
    
            if($validation->passed()){

                $sa = new Super_admin();

                $salt = Hash::salt(32);

                try{

                    $sa->register('prnl_account', array(

                        'account_id' => Input::get('ID'),
                        'username' => Input::get('username'),
                        'group' => Input::get('group'),
                        'userpassword' => Hash::make(Input::get('password'), $salt),
                        'salt' => $salt

                    ));
                    
                    Session::flash('error404', 'Page not Found');
                    echo "success";

                }catch(Exception $e){
                    die($e->getMessage());
                }

            }else{        
              foreach($validation->errors() as $error){
                  echo $error,'<br>';
              }
        
            }               
        }
            
    }

?>


<form action="" method="POST">

    <div class="field">
        <label for="username">ID</label>
        <input type="text" name="ID" id="ID" value="<?php echo escape(Input::get('ID'));?>" autocomplete="off">
    </div>
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">password</label>
        <input type="password" name="password" id="password">
    </div>
    <div class="field">
        <label for="password_again">Retype your password</label>
        <input type="password" name="password_again" id="password_again">
    </div>
    <div class="field">
        <label for="name">Fullname</label>
        <input type="text" name="name" value="<?php echo escape(Input::get('name'));?>" id="name">
    </div>
    <div class="field">
        <label for="name">group</label>
        <input type="number" name="group" value="<?php echo escape(Input::get('group'));?>" id="group">
    </div>

    <div class="field"> 
        <input type="hidden" name="token" value="<?php echo Token::generate();?>">       
        <input type="submit" value="Register">

    </div>




</form>