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
                    if($user->update('prnl_account', 'account_id', $ID, array(
                        'newAccount' => 0,
                        'username' => Input::get('new_username'),
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('new_password'), $salt)
                        
                        ))){
                        Session::delete("accounttype");
                        Session::put("accounttype", 0);
						Session::flash('accountUpdated', 'Your Account has been succesfuly updated, Please Re-Login');
						Syslog::put('Account setup');
                        $user->logout();
                        Redirect::To('../../blyte/acc3ss');
                    }
                }catch(Exception $e){
					Syslog::put($e,null,'error_log');
                    Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0001");
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

    <title>PrMO OPPTS | Procurement Aide</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/aid_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-6">
                    <h2>Procurement Aide Dashboard</h2>
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
            <div class="wrapper wrapper-content animated fadeInUp">
			
			<div class="row">
                <div class="col-lg-12">
					<div class="ibox-content m-b-sm border-bottom">
						<div class="p-xs">
							<div class="float-left m-r-md">
								<i class="fas fa-user-tie text-navy mid-icon"></i>
							</div>
							<h2>Welcome back <?php											
								$hold = $user->fullname();
								$currentUser = json_decode($hold,true);	
								
								
								echo $currentUser[0];				
							
							?>!</h2>
							<span>Procurement Aide</span>
						</div>
					</div>
				</div>
				
			<?php
				$reports = $user->dashboardReports();

				$hpCounter = 0;
				$allproCounter = 0;
				if($reports["current_projects"]){
					foreach ($reports["current_projects"] as $project) {
						if($project->priority_level === "HIGH"){
							$hpCounter++;
						}
						$allproCounter++;
					}
				}

				// echo "<pre>",print_r($reports["current_projects"]),"</pre>";
			?>

				<div class="col-lg-6">
					<div class="widget style1 yellow-bg">
						<div class="row">
							<div class="col-8">
								<div class="">
									<h1 class="m-xs"><?php echo $hpCounter;?></h1>

									<h3 class="font-bold no-margins">
										High Priority Projects
									</h3>
									<small>Listed by the director</small>
								</div>
							</div>
							<div class="col-4 text-right text-center">
								<!-- <span> New albums </span> -->
								<a href="Ongoing-projects" class="btn btn-default btn-outline">View Details</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-lg-6">
					<div class="widget style1 lazur-bg">
						<div class="row">
							<div class="col-8">
								<div class="">
									<h1 class="m-xs"><?php echo $allproCounter;?></h1>

									<h3 class="font-bold no-margins">
										Overall Ongoing Projects
									</h3>
									<small>Ongoing and Paused</small>
								</div>
							</div>
							<div class="col-4 text-right text-center">
								<!-- <span> New albums </span> -->
								<a href="Ongoing-projects" class="btn btn-default btn-outline">View Details</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="widget style1" style="background-color:#8CC63E; color:white">
						<div class="row">
							<div class="col-8">
								<div class="">
									<h1 class="m-xs"><?php 
									
									$canvassReturns = $user->getAll("projects", array("accomplished", "=", 4));
									echo count($canvassReturns);
									
									
									?></h1>

									<h3 class="font-bold no-margins">
										Projects Waiting for Canvass Returns
									</h3>
									<small>Purchase Requests and Job Orders</small>
								</div>
							</div>
							<div class="col-4 text-right text-center">
								<!-- <span> New albums </span> -->
								<a href="Ongoing-projects" class="btn btn-default btn-outline">View Details</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="widget style1" style="background-color:#EF5720; color:white">
						<div class="row">
							<div class="col-8">
								<div class="">
									<h1 class="m-xs"><?php 
									if($reports["revision_requests"]){
										echo count($reports["revision_requests"]);
									}else{
										echo "0";
									}
									
									;?></h1>

									<h3 class="font-bold no-margins">
										Form Revisions to Review
									</h3>
									<small>Purchase Requests and Job Orders</small>
								</div>
							</div>
							<div class="col-4 text-right text-center">
								<!-- <span> New albums </span> -->
								<a href="Revision-requests" class="btn btn-default btn-outline">View Details</a>
							</div>
						</div>
					</div>
				</div>				
            </div>			
	
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
				zxcvbnTerms: ['asdasdasd', 'shogun', 'bushido', 'daisho', 'seppuku', <?php 
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
