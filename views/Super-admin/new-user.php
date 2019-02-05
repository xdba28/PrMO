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
	
	$e = "";

    if(Input::exists()){
        if(Token::check("newUserToken", Input::get('newUserToken'))){

            $validate = new Validate();
            $validation =  $validate->check($_POST, array(
                'ID' => [
                    'required' => true,
                    'unique_prnl_id'   => 'personnel',
					'unique_edr_id'	=> 'enduser'
                ],
                
                'email' => [
                    'required' => true,
                    'unique_prnl_email'   => 'personnel',
					'unique_edr_id' => 'enduser'
                ],
                'username' => [
                    'required' => true,
                    'unique' => 'edr_account',
					'unique_prnl_username' => 'prnl_account'
                ]
            ));

            if($validation->passed()){

                $sa =  new Super_admin();
                $salt = Hash::salt(32);

                try{


					// *************************


					$finalPhoto = null;

					if($_FILES["profilePhoto"]["name"]){

						$new_filename = rand(1000,100000)."-".Input::get('ID').".".pathinfo($_FILES["profilePhoto"]["name"], PATHINFO_EXTENSION);

						// die($new_filename);
						$target_dir = "../../data/profile_images/";
						$target_file = $target_dir . basename($new_filename);
						$uploadOk = 1;
						$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
						
						// Check if image file is a actual image or fake image
						
							if ($target_file == "../data/profile_images/") {
								$msg = "cannot be empty";
								$uploadOk = 0;
							} // Check if file already exists
							else if (file_exists($target_file)) {
								$msg = "Sorry, file already exists.";
								$uploadOk = 0;
							} // Check file size
							else if ($_FILES["profilePhoto"]["size"] > 5000000) {
								$msg = "Sorry, your file is too large.";
								$uploadOk = 0;
							} // Check if $uploadOk is set to 0 by an error
							else if ($uploadOk == 0) {
								$msg = "Sorry, your file was not uploaded.";
						
								// if everything is ok, try to upload file
							} else {
								if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $target_file)) {
									$msg = "The file " . basename($_FILES["profilePhoto"]["name"]) . " has been uploaded.";
									$finalPhoto = $new_filename;
								}
							}
					}

					
					
					// *************************



					
					If(Input::get('ext') == ""){
						$extensionName = "XXXXX";
					}else{
						$extensionName = Input::get('ext');
					}

                    $sa->register('personnel', array(
                        'prnl_id' => Input::get('ID'),
                        'prnl_fname' => Input::get('first'),
                        'prnl_mname' => Input::get('middle'),
                        'prnl_lname' => Input::get('last'),
                        'prnl_ext_name' => $extensionName,
                        'prnl_email' => Input::get('email'),
                        'phone' => "+63".Input::get('phone'),
                        'prnl_designated_office' => Input::get('office'),
						'prnl_job_title' => Input::get('jobtitle'),
						'prnl_profile_photo' => $finalPhoto,
						'date_joined' => Date::translate("now", "now")
                    )); 

                    $sa->register('prnl_account', array(
                        'account_id' => Input::get('ID'),
                        'username' => Input::get('username'),
                        'group_' => Input::get('account_type'),
                        'status' => 'ACTIVATED',
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('defaultPassword'), $salt)
                    ));

					// Session::flash('new_user', 'User Successfuly registered in the System.'); /* DISPLAY THIS TO TOUST */
					$success_notifs[] = "User successfully registered in the system";
					Syslog::put('Admin accouunt registration');

                }catch(Exception $e){
					Syslog::put($e,null,'error_log');
					Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0001");
                }

            }else{
				Syslog::put('Admin account registration', null, 'failed');
            }





        }
    }

   

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Admin Registration</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include "../../includes/parts/admin_styles.php"?>

	<script>
		var error = '<?php echo $e?>';
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
                <div class="col-sm-6">
                    <h2>Admin Account Registration</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">User Accounts</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Register Admin Account</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <div class="title-action">
                        <a href="Dashboard.php" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInUp">
					
            <div class="row">
                <div class="col-sm-8 animated fadeInLeft">
                    <div class="ibox myShadow">
                        <div class="ibox-content">
							<h2>User Profile</h2>
                            <p>
                                Specify all required fields for personal information of new user.
                            </p>
							
							<div class="row">
								
									<div class="col-sm-6 b-r"> 
										<form id="profile" action="" method="post" enctype="multipart/form-data">
									
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
													<span class="input-group-addon"><i class="fa fa-phone my-blue">+63</i></span><input id="phone" name="phone" data-mask="9999999999" type="text" class="form-control" required>
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
												<div class="alert alert-success">Only JPEG file format is accepted in this form.</div>	
												<!-- <input type="file" name="profilePhoto" id="profilePhoto" class="dropify" data-allowed-file-extensions="jpeg jpg">		 -->
												<div class="input-group">
												  <div class="custom-file">
													<input type="file" class="custom-file-input"name="profilePhoto" id="inputGroupFile01" aria-describedby="" accept="image/*">
													<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
												  </div>
												</div>	
												<input type="text" name="newUserToken" hidden required value="<?php echo Token::generate("newUserToken");?>">
											</div>	
										
											
										</form>							
									</div>
									<div class="col-lg-12">
												<button class="btn btn-primary btn-rounded pull-right" name="submit" value="submit" type="submit" form="profile">Submit</button>
												<a href="#" class="btn btn-danger btn-rounded pull-right" style="margin-right:5px">Cancel</a>	
									</div>									
							</div>
						</div>
					</div>
                </div>
                <div class="col-sm-4 animated fadeInRight">
                    <div class="ibox myShadow">
                        <div class="ibox-content" style="min-height: 610px;">
							<h2>Account information</h2>
                            <p class="alert alert-warning"><i class="ti-info-alt"></i>
                                 Some fields here are pre-defined and cannot be edited such as the default username and password.
                            </p>
							<div class="row">
								<div class="col-sm-12"> 	
									<div class="form-group">
										<label class="col-form-label" for="date_added">Account Type</label>
											<div class="i-checks"><label> <input type="radio" form="profile" value="7" checked name="account_type"> <i></i> Technical Member </label></div>									
											<div class="i-checks"><label> <input type="radio" form="profile" value="5" name="account_type"> <i></i> Procurement Aide </label></div>
											<div class="i-checks"><label> <input type="radio" form="profile" value="3" name="account_type"> <i></i> Super Admin </label></div>
											<div class="i-checks"><label> <input type="radio" form="profile" value="4" name="account_type"> <i></i> Director </label></div>	
											<div class="i-checks"><label> <input type="radio" form="profile" value="6" name="account_type"> <i></i> Staff </label></div>
									</div>
									<div class="form-group">
										<label class="col-form-label" for="date_added">Username</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="ti-user my-blue"></i></span><input value="" type="text" id="username" name="username" form="profile" class="form-control" required>
										</div>
									</div>		
									<div class="form-group">
										<label class="col-form-label" for="date_added">Password</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="ti-lock my-blue"></i></span><input value="<?php echo StringGen::password();?>" readonly type="text" id="defaultPassword" name="defaultPassword" form="profile" class="form-control" required>
										</div>
									</div>	

									<button type="button" id="passwordGen" class="pull-right btn btn-sm btn-default">Generate Password</button>									
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
 
    <?php include_once '../../includes/parts/admin_scripts.php'; ?>
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

	<script>

        $(document).ready(function() {

			$('.custom-file-input').on('change', function() {
			   let fileName = $(this).val().split('\\').pop();
			   $(this).next('.custom-file-label').addClass("selected").html(fileName);
			}); 


			
		});
			
	</script>
			
</html>
