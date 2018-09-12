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

        require_once "../functions/sanitize.php";

        if(Session::exists(Config::get('session/session_name')) && isset($_SESSION[Config::get('session/session_name')])){

            $admin = new Admin();

            $found = $admin->findById($_SESSION[Config::get('session/session_name')]);

            if($found){
                Redirect::To('-dashboard');
            }else{
                 Session::flash('error403', 'Forbidden Access');
                 Redirect::To(403);
            }


        }




        /* Codes on top are the actual init.php content */

	if(Input::exists()){
        if(Token::check("blyteToken", Input::get('blyteToken'))){

            $validate = new Validate();
            $validation = $validate->check($_POST, array(

                'username' => ['required' => true],
                'password' => ['required' => true]

            ));


            if($validation->passed()){

                 $user = new Admin();
				 
                 $login = $user->login(Input::get('username'), Input::get('password'));

                 if($login){
                     Redirect::To('-dashboard');
                 }else{
                     Session::flash('incorrect', 'Incorrect Username or Password');
                 }

            }else{
                foreach($validation->errors() as $error){
                    echo $error,"<br>";
                }
            }



        }

    }
	
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO | Admin Login</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name" style="margin-left: -70px">PrMO</h1>

            </div>
            <h3>Welcome Admin</h3>

            <p>
                <?php
                    if(Session::exists('Loggedout')){
                        echo Session::flash('Loggedout');
                    }else if(Session::exists('incorrect')){
                        echo Session::flash('incorrect');
                    }else{
                        echo 'Please Login in.';
                    }
                ?>
            </p>
            <form class="m-t" role="form" action="" method="POST">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required="" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required="">
                </div>
				<input type="hidden" name="blyteToken" value="<?php echo Token::generate("blyteToken");?>">
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

               
            </form>
            <p class="m-t"> <small>Copyright BU-BAC PrMO &copy; 2018-2019</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.js"></script>

</body>

</html>
