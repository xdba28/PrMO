<?php

	require_once 'core/outer-init.php';


	if(Input::exists()){
        if(Token::check("registerToken", Input::get('registerToken'))){

            $validate = new Validate();
            $validation = $validate->check($_POST, array(

                'username' => ['required' => true],
                'password' => ['required' => true]

            ));


            if($validation->passed()){

                 $user = new User();
				 $remember = (Input::get('remember') === 'on') ? true : false; 
				 
                 $login = $user->login(Input::get('username'), Input::get('password'), $remember);

                 if($login){
                     Redirect::To('views/User/Dashboard');
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

    <title>PrMO OPPTS | Login</title>



    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <link href="css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">Welcome to PrMO Online Procurement Project Tracking System</h2>

                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.
                </p>

                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
					
                </p>

                <p>
                    When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                </p>

                <p>
                    <small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small>
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
					
					<?php
						if(Session::exists('Loggedout')){		
                            echo '<center><h2>'.Session::flash('Loggedout').'</h2></center>';
                        }
						if(Session::exists('incorrect')){		
                            echo '<center><h4>'.Session::flash('incorrect').'</h4></center>';
                        }
					?>
                    <form class="m-t" action="" method="POST">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password" required autocomple="off">
                        </div>
						<input type="hidden" name="registerToken" value="<?php echo Token::generate("registerToken");?>">
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
						
                                <div class="form-group row">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <div class="i-checks"><label> <input type="checkbox" id="remember" name="remember"><i></i> Remember me </label></div>
                                    </div>
                                </div>


                        <a href="#">
                            <small>Forgot password?</small>
                        </a>

                        <p class="text-muted text-center">
                            <small>Do not have an account?</small>
                        </p>
                        <a class="btn btn-sm btn-white btn-block" href="register.php">Request an account</a>
                    </form>
                    <?php
						if(Session::exists('request_success')){		
                            echo '<center><h2>'.Session::flash('request_success').'</h2></center>';
                        }                    
                    ?>
                    <p class="m-t">
                        <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small>
                    </p>
					<!-- <button type="button" id="req">Send Request</button> -->
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright BU-BAC PrMo
            </div>
            <div class="col-md-6 text-right">
               <small>© 2018-2019</small>
            </div>
        </div>
    </div>
	
    <!-- Mainly scripts -->
    <script src="assets/js/jquery-3.1.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="assets/js/inspinia.js"></script>
    <script src="assets/js/plugins/pace/pace.min.js"></script>
	
    <!-- iCheck -->
    <script src="assets/js/plugins/iCheck/icheck.min.js"></script>
	<script>
		$(document).ready(function () {
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
			});

			// var conn = new WebSocket('ws://localhost:8080');
			// conn.onopen = function(e) {
			// 	console.log("Connection established!");
			// 	conn.send('Hello');
			// };

			// conn.onmessage = function(e) {
			// 	console.log(e.data);
			// };

			// document.getElementById('req').addEventListener('click', function()
			// {
			// 	conn.send("Hello!!!");
			// });
		});
	</script>


</body>

</html>

