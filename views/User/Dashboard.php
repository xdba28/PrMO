<?php 

    require_once('../../core/init.php');

    $user = new User(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
    }
    
        /* This is for the validator modal standard user */
        $user = new User();
        $data = $user->userData(Session::get(Config::get('session/session_name')));
        $myArray = array('default');
        foreach($data as $element => $val){
            array_push($myArray, $val);
        }
    
        $commonFields =  "'". implode("', '", $myArray) ."'";

    if(Input::exists()){
        if(Token::check("passwordToken", Input::get('passwordToken'))){

            $validate = new Validate();

            $validation = $validate->check($_POST, array(
                    'new_username' => [
                        'required' => true,
                        'unique' => 'edr_account',
                        'unique' => 'prnl_account'
                    ],
                    'new_password' => [
                        'required' => true
                    ],
                    'password_again' => [
                        'matches' => 'new_password'
                    ]
            ));

            if($validation->passed()){
                $user = new User();
                $salt = Hash::salt(32);
                $ID = Session::get(Config::get('session/session_name'));

                try{
                    if($user->update('edr_account', 'account_id', $ID, array(
                        'newAccount' => 0,
                        'username' => Input::get('new_username'),
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('new_password'), $salt)
                        
                        ))){
                        Session::delete("accounttype");
                        Session::put("accounttype", 0);
                        Session::flash('accountUpdated', 'Your Account has been succesfuly updated, Please Re-Login');
                        $user->logout();
                        Redirect::To('../../index');
                    }
                }catch(Exception $e){
                    die($e->getMessage());
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

    <title>PrMO OPPTS | Dashboard</title>

	<?php include_once'../../includes/parts/user_styles.php'; ?>
	

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/user_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>End User Dashboard</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Dashboard</strong>
                        </li>
                    </ol>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
				<div class="row ">
					<button href="#" data-toggle="modal" data-target="#testing">test</button>
					<?php
						$now = date('H');
						
						if($now < 12){
							echo '
					<div class="col-lg-6">
						<div class="widget style1 yellow-bg">
							<div class="row">
								<div class="col-2">
									<i class="fa fa-sun fa-5x"></i>
								</div>
								<div class="col-10">
									<h3 class="">Good Morning ,</h3>
									<h2 class="font-bold">'.$currentUser[2].'</h2>
								</div>
							</div>
						</div>
					</div>						
							';
						}else if(($now == 12) || ($now < 18)){
							echo '
					<div class="col-lg-6">
						<div class="widget style1 afternoon-bg">
							<div class="row">
								<div class="col-2">
									<i class="fas fa-cloud-sun fa-5x"></i>
								</div>
								<div class="col-10">
									<h3 class="">Good Afternoon ,</h3>
									<h2 class="font-bold">'.$currentUser[2].'</h2>
								</div>
							</div>
						</div>
					</div>						
							
							';
						}else{
							echo '
							
					<div class="col-lg-6">
						<div class="widget style1 evening-bg">
							<div class="row">
								<div class="col-2">
									<i class="fas fa-cloud-moon fa-5x"></i>
								</div>
								<div class="col-10">
									<h3 class="">Good Evening ,</h3>
									<h2 class="font-bold">'.$currentUser[2].'</h2>
								</div>
							</div>
						</div>
					</div>						
							';
						}
					?>



				
				</div>
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once'../../includes/parts/user_scripts.php'; ?>

</body>

</html>
