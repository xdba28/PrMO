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
					<?php include '../../includes/parts/superadmin_side_nav.php'; ?>
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
                <div class="col-sm-4">
                    <h2>This is main title</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Breadcrumb</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                    <a href="Dashboard" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
									<!-- start accordion -->
									<div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
									  <div class="panel">
										<a class="panel-heading" role="tab" id="headingOne1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne">
										  <h4 class="panel-title">Request</h4>
										</a>
										<div id="collapseOne1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
										  <div class="panel-body">
										  
												<p><strong>Requisition of Equipment</strong></p>
												<p>The first process the Property Management System does is to manage various request of equipments from the all the employees of the Commision on Audit Region V has</p><br>
												
												<p><strong>Approval of Requests</strong></p>
												<p>All request made by the employees are automatically pending until an Approving personel approves the request, then the process is now in the Procurement Stage</p>												
												
										  </div>
										</div>
									  </div>
									  <div class="panel">
										<a class="panel-heading collapsed" role="tab" id="headingTwo1" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo">
										  <h4 class="panel-title">Procurement</h4>
										</a>
										<div id="collapseTwo1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
										  <div class="panel-body">
											<p><strong>Procurement</strong></p>
											
											<p>
												Procurement, in the simplest sense, involves a series of activities and processes that are mecessary for an organization to acquire necesarry products, equipments, property or services from the best suppliers at the best price. Such products or services
												that are procured include raw materials, officer equipments, furniture and facilities, technical equipment and support, telecommunications, printed collateral, contingent worker recruitment, testing and training, and travel-relate services, among any others.
											</p>
											
											<p>
												But this process is excluded in the task the PMS can offer the end users. Only persons in the authority is authorized to be in this process.
											</p>
											
										
											
										  </div>
										</div>
									  </div>
									  <div class="panel">
										<a class="panel-heading collapsed" role="tab" id="headingThree1" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree1" aria-expanded="false" aria-controls="collapseThree">
										  <h4 class="panel-title">Registration</h4>
										</a>
										<div id="collapseThree1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
										  <div class="panel-body">
											<p><strong>Registration of Purchased Property / Equipment</strong></p>
											
											<p>
												Process continuation, after purchasing of the procured requests. Registration starts in the <u>Registration of new Equipment</u> where you can choose among the approved request whether which of these are already purchased and ready for registration.
											</p>
											
											<p><strong>Awknoledment Receipt of Equipent (ARE)</strong></p>
											
											<p>
												After the equipment is registered, an Awknoledment Receipt is automatically created and to be printed <u>(awarded together with the requested equipment)</u> for the assignee and to be signed by approving personel.
											</p>
											
										  </div>
										</div>
									  </div>
									  <div class="panel">
										<a class="panel-heading collapsed" role="tab" id="headingFour" data-toggle="collapse" data-parent="#accordion1" href="#collapseFour1" aria-expanded="false" aria-controls="collapseFour">
										  <h4 class="panel-title">Maintenance Reports</h4>
										</a>
										<div id="collapseFour1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
										  <div class="panel-body">
											<p><strong>Property Inspection Report</strong></p>
											
											<p>Last process the PMS can offer is the registration of Inspection Reports of all equipments registered in the system. You can monitor when the equipment is inspected, what the remarks is, the inspector who conducted the inspection, and the update of the status of each equipment.</p>
											
											
										  </div>
										</div>
									  </div>
									</div>
									<!-- end of accordion -->
                </div>
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once'../../includes/parts/admin_scripts.php'; ?>
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
