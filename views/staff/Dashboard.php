<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

        /* This is for the validator modal admin level */
        $user = new Admin();
        $data = $user->userData(Session::get(Config::get('session/session_name')));
        $myArray = array('default');
        foreach($data as $element => $val){
            array_push($myArray, $val);
        }
    
        $commonFields =  ","." '". implode("', '", $myArray) ."'";

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
                    if($user->update('prnl_account', 'account_id', $ID, array(
                        'newAccount' => 0,
                        'username' => Input::get('new_username'),
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('new_password'), $salt)
                        
                        ))){
                        Session::delete("accounttype");
                        Session::put("accounttype", 0);
                        Session::flash('accountUpdated', 'Your Account has been succesfuly updated, Please Re-Login');
                        $user->logout();
                        Redirect::To('../../blyte/acc3ss');
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

    <title>PrMO OPPTS | Empty Page</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/staff_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg" style="background-color:#c2c2ca">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-12">
                    <h2>Staff</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Breadcrumb</strong>
                        </li>
                    </ol>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content animated fadeInUpBig">
                    <!-- <h3 class="font-bold">Staff</h3>
                    <div class="error-desc">
                        You can create here any grid layout you want. And any variation layout you imagine:) Check out
                        main dashboard and other site. It use many different layout.
                        <br/><a href="Dashboard" class="btn btn-primary m-t">Dashboard</a>
                    </div> -->
					

					<div class="container card-list">
					  <div class="card blue" style="margin-right:10px">
							<div class="title">All Projects</div><span class="glyphicon glyphicon-upload"></span>
							<div class="value">00</div>
							<div class="stat"><b>13</b>% increase</div>
					  </div>
					  <div class="card green" style="margin-right:10px">
							<div class="title">team members</div><span class="glyphicon glyphicon-upload"></span>
							<div class="value">5,990</div>
							<div class="stat"><b>4</b>% increase</div>
					  </div>
					  <div class="card orange" style="margin-right:10px">
							<div class="title">total budget</div><span class="glyphicon glyphicon-download"></span>
							<div class="value">$80,990</div>
							<div class="stat"><b>13</b>% decrease</div>
					  </div>
					  <div class="card red">
							<div class="title">new customers</div><span class="glyphicon glyphicon-download"></span>
							<div class="value">3</div>
							<div class="stat"><b>13</b>% decrease</div>
					  </div>
					</div><br>
					
					<div class="container projects">
					  <div class="projects-inner">
						<header class="projects-header">
						  <div class="title">Ongoing Projects</div>
						  <div class="count">| 00 Projects</div><span class="glyphicon glyphicon-download-alt"></span>
						</header>
						<table class="projects-table">
							 <thead>
								<tr>
									<th>Project</th>
									<th>Deadline</th>
									<th>Budget</th>
									<th>Status</th>
								</tr>
							 </thead>
						  <tr>
							<td>
							  <p>New Dashboard</p>
							  <p>Google</p>
							</td>
							<td>
							  <p>17th Oct, 15</p>
							  <p class="danger-text">Overdue</p>
							</td>
							<td>
							  <p>$4,670</p>
							  <p>Paid</p>
							</td>
							<td class="status"><span class="status-text status-orange">In progress</span>
							  <form class="form" action="#" method="POST">
								<select class="action-box">
								  <option>Actions</option>
								  <option>Start project</option>
								  <option>Send for QA</option>
								  <option>Send invoice</option>
								</select>
							  </form>
							</td>
						  </tr>
						  <tr class="danger-item">
							<td>
							  <p>New Dashboard</p>
							  <p>Google</p>
							</td>
							<td>
							  <p>17th Oct, 15</p>
							  <p class="danger-text">Overdue</p>
							</td>
							<td>
							  <p>$4,670</p>
							  <p>Paid</p>
							</td>
							<td class="status"><span class="status-text status-red">Blocked</span>
							  <form class="form" action="#" method="POST">
								<select class="action-box">
								  <option>Actions</option>
								  <option>Start project</option>
								  <option>Send for QA</option>
								  <option>Send invoice</option>
								</select>
							  </form>
							</td>
						  </tr>

						</table>
					  </div>
					</div><br><br>			

            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

    <?php include '../../includes/parts/admin_scripts.php'; ?>
<!-- Password meter -->
	<script src="../../assets/js/plugins/pwstrength/pwstrength-bootstrap.min.js"></script>
	<script src="../../assets/js/plugins/pwstrength/zxcvbn.js"></script>
	
	<script>	
		$(document).ready(function(){
           // Example 4 password meter
            var options4 = {};
            options4.ui = {
                container: "#pwd-container",
                viewports: {
                    progress: ".pwstrength_viewport_progress4",
                    verdict: ".pwstrength_viewport_verdict4"
                }
            };

            options4.common = {

                zxcvbn: true,
				zxcvbnTerms: ['asdasdasd', 'shogun', 'bushido', 'daisho', 'seppuku' <?php 
					if(isset($commonFields)) echo $commonFields;
					else{
						echo  $commonFields = '';
					}
				?>],
                userInputs: ['#year', '#new_username']
            };
            $('.example4').pwstrength(options4);

			
			//password valide
			var password = document.getElementById("new_password")
			  , confirm_password = document.getElementById("password_again");

			function validatePassword(){
			  if(password.value != confirm_password.value) {
				confirm_password.setCustomValidity("Passwords Don't Match");
			  } else {
				confirm_password.setCustomValidity('');
			  }
			}

			password.onchange = validatePassword;
			confirm_password.onkeyup = validatePassword;						
		})
	
	</script>

</body>

</html>
