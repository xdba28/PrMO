<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }


	$sa = new Super_admin();
	
<<<<<<< HEAD
	$er_data = null;
=======
	$e = "";
>>>>>>> denver

    if(Input::exists()){
        if(Token::check("newUserToken", Input::get('newUserToken'))){
            //allow to submit the form

            $validate = new Validate();
            $validation =  $validate->check($_POST, array(
                'ID' => [
                    'required' => true,
                    'unique_prnl_id'   => 'personnel'
                ],
                
                'email' => [
                    'required' => true,
                    'unique_prnl_email'   => 'personnel'
                ],
                'username' => [
                    'required' => true,
                    'unique' => 'prnl_account'
                ]
            ));

            if($validation->passed()){

                $sa =  new Super_admin();
                $salt = Hash::salt(32);

                try{
                    
                    $sa->register('personnel', array(
                        'prnl_id' => Input::get('ID'),
                        'prnl_fname' => Input::get('first'),
                        'prnl_mname' => Input::get('middle'),
                        'prnl_lname' => Input::get('last'),
                        'prnl_ext_name' => 'XXXXX',
                        'prnl_email' => Input::get('email'),
                        'phone' => Input::get('phone'),
                        'prnl_designated_office' => Input::get('office'),
                        'prnl_job_title' => Input::get('jobtitle'),
                    )); //No profile photo yet

                    $sa->register('prnl_account', array(
                        'account_id' => Input::get('ID'),
                        'username' => Input::get('username'),
                        'group_' => Input::get('account_type'),
                        'status' => 'ACTIVATED',
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('defaultPassword'), $salt)
                    ));

<<<<<<< HEAD
					Session::flash('new_user', 'User Successfuly registered in the System.'); /* DISPLAY THIS TO TOUST */
=======
                    Session::flash('new_user', 'User Successfuly registered in the System.'); /* DISPLAY THIS TO TOUST */
>>>>>>> denver

                }catch(Exception $e){

                }

            }else{

                /* DENVER!!!!! Display errors in toust*/
                foreach($validation->errors() as $error){
<<<<<<< HEAD
                    $er_data .= $error.'<br>';
                }
            }

        }
    }

=======
                    $e .= $error;
                }
            }





        }
    }

   

>>>>>>> denver
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Dashboard v.3</title>
	<?php include "../../includes/parts/admin_styles.php"?>
<<<<<<< HEAD
	<script>
		var err = '<?php echo $er_data?>';
=======

	<script>
		var error = '<?php echo $e?>';
>>>>>>> denver
	</script>

</head>

<body class="fixed-navigation">
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
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>User Registration</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Employees</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Register User</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        <a href="Dashboard.php" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">
					
            <div class="row">
                <div class="col-sm-8">
                    <div class="ibox">
                        <div class="ibox-content">
							<h2>User Profile</h2>
                            <p>
                                Specify all required fields for personal information of new user.
                            </p>
							
							<div class="row">
								
									<div class="col-sm-6 b-r"> 
										<form id="profile" role="form" method="POST" enctype= multipart/form-data>
									
											<div class="form-group">	
												<label class="col-form-label" for="ID">Employee ID</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-address-card my-blue"></i></span><input id="ID" name="ID" type="text" class="form-control" required>
												</div>
											</div>
											<div class="form-group mt-20">
											  <label class="form-label" for="first">First Name</label>
											  <input id="first" name="first" class="form-input" type="text" required>
											</div>
											<div class="form-group mt-20">
											  <label class="form-label" for="middle">Middle Name</label>
											  <input id="middle" name="middle" class="form-input" type="text" required>
											</div>
											<div class="form-group mt-20">
											  <label class="form-label" for="last">Last Name</label>
											  <input id="last" name="last" class="form-input" type="text" required>
											</div>
											<div class="form-group mt-20">
											  <label class="form-label" for="ext">Extension Name</label>
											  <input id="ext" name="ext" class="form-input" type="text">
											</div>											
											<div class="form-group mt-20">
												<label class="col-form-label" for="email">Email Address</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-at my-blue"></i></span><input id="email" name="email" type="email" class="form-control" required>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label" for="phone">Phone No.</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-phone my-blue"></i></span><input id="phone" name="phone" data-mask="9999 999 9999" type="text" class="form-control" required>
												</div>
											</div>											
									</div>
									<div class="col-sm-6"> 												
											<div class="form-group">
												<label>Office / Unit</label>
																							
													<select class="form-control m-b required chosen-select" name="office" required>
														<option value="unchanged"> Select... </option>
														<?php																				
														
															$units = $sa->selectAll('units');
															foreach($units as $unit){
																echo "<option value ='{$unit->ID}'>{$unit->office_name}</option>";
															}
														?>
													</select>
												
											</div>	
										
											<div class="form-group">
												<label class="col-form-label" for="typeahead">Job Title</label>												
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-briefcase my-blue"></i></span><input id="typeahead" name="jobtitle" type="text" class="form-control" required>
												</div>												
											</div>                                          
											<div class="form-group">
												<label class="col-form-label" for="date_added">Profile Photo</label>	
												<input type="file" name="profilePhoto" class="dropify" data-allowed-file-extensions="png jpeg jpg">		
												<input type="text" name="newUserToken" hidden required value="<?php echo Token::generate("newUserToken");?>">
											</div>										
										</form>							
									</div>
									<div class="col-lg-12">
												<button class="btn btn-primary btn-rounded pull-right" type="submit" form="profile">Submit</button>
												<a href="#" class="btn btn-danger btn-rounded pull-right" style="margin-right:5px">Cancel</a>	
									</div>									
							</div>
						</div>
					</div>
                </div>
                <div class="col-sm-4">
                    <div class="ibox">
                        <div class="ibox-content">
							<h2>Account information</h2>
                            <p class="alert alert-warning"><i class="ti-info-alt"></i>
                                 Some fields here are pre-defined and cannot be edited such as the default username and password.
                            </p>
							<div class="row">
								<div class="col-sm-12"> 	
									<div class="form-group">
										<label class="col-form-label" for="date_added">Account Type</label>												
											<div class="i-checks"><label> <input type="radio" form="profile" checked="" value="5" name="account_type"> <i></i> Procurement Aid </label></div>
											<div class="i-checks"><label> <input type="radio" form="profile" value="3" name="account_type"> <i></i> Super Admin </label></div>
											<div class="i-checks"><label> <input type="radio" form="profile" value="4" name="account_type"> <i></i> Director </label></div>	
											<div class="i-checks"><label> <input type="radio" form="profile" value="6" name="account_type"> <i></i> Staff </label></div>
									</div>
									<div class="form-group">
										<label class="col-form-label" for="date_added">Username</label>
										<div class="input-group">
<<<<<<< HEAD
											<span class="input-group-addon"><i class="ti-user my-blue"></i></span><input value="sample.username" type="text" name="username" form="profile" class="form-control" required>
=======
											<span class="input-group-addon"><i class="ti-user my-blue"></i></span><input value="" type="text" id="username" name="username" form="profile" class="form-control" required>
>>>>>>> denver
										</div>
									</div>		
									<div class="form-group">
										<label class="col-form-label" for="date_added">Password</label>
										<div class="input-group">
<<<<<<< HEAD
											<span class="input-group-addon"><i class="ti-lock my-blue"></i></span><input value="<?php echo StringGen::password();?>" readonly type="text" name="defaultPassword" form="profile" class="form-control" required>
										</div>
									</div>										
=======
											<span class="input-group-addon"><i class="ti-lock my-blue"></i></span><input value="" readonly type="text" id="defaultPassword" name="defaultPassword" form="profile" class="form-control" required>
										</div>
									</div>	

									<button type="button" id="passwordGen" class="pull-right btn btn-sm btn-default">Generate Password</button>									
>>>>>>> denver
								</div>	
							</div>
							
						</div>
					</div>
					
  				


                </div>
            </div>

            </div>
			<div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
			</div>

        </div>
        <div id="right-sidebar">
            <div class="sidebar-container">

                <ul class="nav nav-tabs navs-3">

                    <ul class="nav nav-tabs navs-2">
                        <li>
                            <a class="nav-link active" data-toggle="tab" href="#tab-3"> Settings </a>
                        </li>					
                        <li>
                            <a class="nav-link" data-toggle="tab" href="#tab-2"> Projects </a>
                        </li>
                    </ul>

                <div class="tab-content">


                    <div id="tab-3" class="tab-pane active">

                        <div class="sidebar-title">
                            <h3><i class="fa fa-gears"></i> Settings</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>

                        <div class="setings-item">
                    <span>
                        Show notifications
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                                    <label class="onoffswitch-label" for="example">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Disable Chat
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
                                    <label class="onoffswitch-label" for="example2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Enable history
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                                    <label class="onoffswitch-label" for="example3">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Show charts
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                                    <label class="onoffswitch-label" for="example4">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Offline users
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
                                    <label class="onoffswitch-label" for="example5">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Global search
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
                                    <label class="onoffswitch-label" for="example6">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Update everyday
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                                    <label class="onoffswitch-label" for="example7">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-content">
                            <h4>Settings</h4>
                            <div class="small">
                                I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                And typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                            </div>
                        </div>

                    </div>
                    <div id="tab-2" class="tab-pane">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-cube"></i> Latest projects</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>



                    </div>				
                </div>

            </div>



        </div>
    </div>

    <?php include_once '../../includes/parts/admin_scripts.php'; ?>
<<<<<<< HEAD
	<script>
		$(function()
		{
			if(err === '')
			{
				swal({
					title: "hello",
					text: "hello",
					type: "error"
				});
				toastr.success("Hello");
			}
		});
	</script>

</body>
=======
</body>
<script>
	if(error !== ""){
		swal({
			title: "An error occurred!",
			text: error,
			confirmButtonColor: "#DD6B55",
			type: 'error',
			timer: 13000
		});
	}

		$('#first').change(function() {
			var last = $('#last').val();
			$('#username').val($(this).val()+'.'+last);
		});
		$('#last').change(function() {
			var first = $('#first').val();
			$('#username').val(first+'.'+$(this).val());
		});		

		$('#passwordGen').click(function(){

			<?php
			$availablePassword = array();
			for($x=0; $x < 10; $x++){
				array_push($availablePassword, StringGen::password());
			}

			?>
			var tempPassword = <?php echo json_encode($availablePassword); ?>;
			
			$('#defaultPassword').val(tempPassword[Math.floor(Math.random() * 10)]);
		});



</script>
>>>>>>> denver
</html>
