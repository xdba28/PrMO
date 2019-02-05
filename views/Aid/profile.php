<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
	}

	// echo "<pre>",print_r($_SESSION),"</pre>";
	

    
	if(Input::exists()){
		
		$success_notifs=[];
		
		 if(Token::check("nametoken", Input::get('nametoken'))){
			 try{
				 
				 if(empty($_POST["extname"])){
					$extname = "XXXXX";
				 }else{
					$extname = Input::get("extname");
				 }
				 
				$user->startTrans();
					$user->update("personnel", "prnl_id", Input::get("user"), array(
						'prnl_fname' => Input::get("fname"),
						'prnl_mname' => Input::get("mname"),
						'prnl_lname' => Input::get("lname"),
						'prnl_ext_name' => $extname
					));
				
					//register activity log
					$user->register("activity_log", array(
						'activity' => 'Updated fullname',
						'made_by' => Input::get("user"),
						'hidden' => false,
						'date_log' => Date::translate('now', 'now')					
					));
				$user->endTrans();
				
				$success_notifs[] = "You successfully updated your personal information.";
				Syslog::put("fullname update");

				
			}catch(Exception $e){
				//create system logs
				Syslog::put($e,null,"error_log");
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0001");				
			}


			 
		 }else if(Token::check("emailtoken", Input::get('emailtoken'))){

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'newemail' => [
					'required' => true,
					'unique_edr_email'   => 'enduser',
					'unique_prnl_email'   => 'personnel'					
				]
			));

			if($validation->passed()){
				try{
								
					$user->startTrans();
						$user->update("personnel", "prnl_id", Input::get("user"), array(
							'prnl_email' => Input::get("newemail")
						));
					
						//register activity log
						$user->register("activity_log", array(
							'activity' => 'Updated email address',
							'made_by' => Input::get("user"),
							'hidden' => false,
							'date_log' => Date::translate('now', 'now')
						
						));
					$user->endTrans();
					
					$success_notifs[] = "You successfully updated your Email Address.";
					Syslog::put("email address update");
				
				
				}catch(Exception $e){
					//create system logs
					Syslog::put($e,null,"error_log");
					Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0002");
				}
			}

		 }elseif (Token::check("phonetoken", Input::get('phonetoken'))) {
			try{
							
				$user->startTrans();
					$user->update("personnel", "prnl_id", Input::get("user"), array(
						'phone' => "+63".Input::get("newphone")
					));
				
					//register activity log
					$user->register("activity_log", array(
						'activity' => 'Updated phone number',
						'made_by' => Input::get("user"),
						'hidden' => false,
						'date_log' => Date::translate('now', 'now')
					
					));
				$user->endTrans();
				
				$success_notifs[] = "You successfully updated your Phone number.";
				Syslog::put("phone number update");
			
			
			}catch(Exception $e){
				//create system logs
				Syslog::put($e,null,"error_log");
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0003");
			}
			
		 }else if(Token::check("designationtoken", Input::get('designationtoken'))){
			try{
								
				$user->startTrans();
					$user->update("enduser", "prnl_id", Input::get("user"), array(
						'prnl_designated_office' => Input::get("newunit")
					));
				
					//register activity log
					$user->register("activity_log", array(
						'activity' => 'Updated Designation/Unit',
						'made_by' => Input::get("user"),
						'hidden' => false,
						'date_log' => Date::translate('now', 'now')
					
					));
				$user->endTrans();
				
				$success_notifs[] = "You successfully updated your Designation/Unit.";
				Syslog::put("designation update");
			
			
			}catch(Exception $e){
				//create system logs
				Syslog::put($e,null,"error_log");
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0004");
			}
		 }else if (Token::check("specificofficetoken", Input::get('specificofficetoken'))) {
			try{
									
				$user->startTrans();
					$user->update("personnel", "prnl_id", Input::get("user"), array(
						'current_specific_office' => Input::get("newspecificoffice")
					));
				
					//register activity log
					$user->register("activity_log", array(
						'activity' => 'Updated Specific Office',
						'made_by' => Input::get("user"),
						'hidden' => false,
						'date_log' => Date::translate('now', 'now')
					
					));
				$user->endTrans();
				
				$success_notifs[] = "You successfully updated your Specific Office.";
				Syslog::put("specific office update");
			
			
			}catch(Exception $e){
				//create system logs
				Syslog::put($e,null,"error_log");
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0005");
			}			
		 }else if (Token::check("usernametoken", Input::get('usernametoken'))) {

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'newusername' => [
					'unique'   => 'edr_account',
					'unique_prnl_username'   => 'prnl_account'
				]
			));

			if($validation->passed()){
				try{
									
					$user->startTrans();
						$user->update("prnl_account", "account_id", Input::get("user"), array(
							'username' => Input::get("newusername")
						));
					
						//register activity log
						$user->register("activity_log", array(
							'activity' => 'Updated Username',
							'made_by' => Input::get("user"),
							'hidden' => false,
							'date_log' => Date::translate('now', 'now'),
							'type' => 'login info update'
						
						));
					$user->endTrans();
										
					$success_notifs[] = "You successfully updated your Username.";
					Syslog::put("username update");
				
				
				}catch(Exception $e){
					//create system logs
					Syslog::put($e,null,"error_log");
					Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0006");
				}

			}
		 }else if (Token::check("newpasstoken", Input::get('newpasstoken'))){

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'currentpassword' => [
					'validate_password' => 'prnl_account'
				],
				'newpassword' => [
					'required' => true,
					'min' => 6,
				],
				'passwordagain' => [
					'required' => true,
					'min' => 6,
					'matches' => 'newpassword'
				]

			));

			if($validation->passed()){

				try{
					$salt = Hash::salt(32);

					$user->startTrans();
						$user->update("prnl_account", "account_id", Input::get("user"), array(
							'userpassword' => Hash::make($_POST["newpassword"], $salt),
							'salt' => $salt
						));
					
						//register activity log
						$user->register("activity_log", array(
							'activity' => 'Updated Password',
							'made_by' => Input::get("user"),
							'hidden' => false,
							'date_log' => Date::translate('now', 'now'),
							'type' => 'login info update'
						
						));
					$user->endTrans();
										
					$success_notifs[] = "You successfully updated your Password.";
					Syslog::put("password update");
				
				}catch(Exception $e){
					//create system logs
					Syslog::put($e,null,"error_log");
					Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0006");
				}

			}

		 }else if (Token::check("profile-photo-token", Input::get('profile-photo-token'))){

			
			try{
				
				if($_FILES["profilePhoto"]["name"]){

					$new_filename = rand(1000,100000)."-".Session::get(Config::get('session/session_name')).".".pathinfo($_FILES["profilePhoto"]["name"], PATHINFO_EXTENSION);
					
					$target_dir = "../../data/profile_images/";
					$target_file = $target_dir . basename($new_filename);
					$uploadOk = 1;
					$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
					
					// Check if image file is a actual image or fake image
					
						if ($target_file == "../data/profile_images/") {							
								$msg = "cannot be empty";
							$uploadOk = 0;
						} // Check if file already exists
						else if (file_exists($target_file)){
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
								// echo $_FILES["profilePhoto"]["tmp_name"],"<br>";
								// echo $target_file;
							if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $target_file)) {
								
								 $msg = "The file " . basename($_FILES["profilePhoto"]["name"]) . " has been uploaded.";
								
								$user->startTrans();
									$user->update("personnel", "prnl_id", Session::get(Config::get('session/session_name')), array(
										'prnl_profile_photo' => $new_filename
									));
									
									//register activity log
									$user->register("activity_log", array(
										'activity' => 'Updated profile photo',
										'made_by' => Session::get(Config::get('session/session_name')),
										'hidden' => false,
										'date_log' => Date::translate('now', 'now'),
										'type' => 'personal info update'
									));									
								$user->endTrans();
								
								$success_notifs[] = "You successfully updated your profile photo.";
								Syslog::put("profile photo update");
								
							}else{
								Session::flash("FATAL_ERROR", "There was an error uploading your file. ERRORCODE:0007s1");
							}
						}
				}
				
				
				 
			}catch(Exception $e){
				//create system logs
				Syslog::put($e,null,"error_log");
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0007");
			}
			 
			 
			 
		 }
		
	}

	



?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">

    <title>PrMO OPPTS | Profile</title>

	<?php include_once'../../includes/parts/user_styles.php'; ?>
	

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
                <div class="col-sm-4">
                    <h2>User Profile</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>User Profile</strong>
                        </li>
                    </ol>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content animated fadeInUp">

				
			<div class="row m-b-lg m-t-lg">
				<div class="col-md-6">

					<div class="profile-image">
						<img src="<?php if(is_null($profilePhoto)){
							echo "../../assets/pics/flaticons/random/avatar1.jpg";
						}else{
							echo "../../data/profile_images/".$profilePhoto;
			}?>" class="rounded-circle circle-border m-b-md" alt="profile">
				<a href="#" data-toggle="modal" data-target="#profile-photo-modal"><span class="label label-warning" style="position: absolute;display: inline-block;height: 0;line-height: 0; border: 0.6em solid transparent;left: 2.8em;text-align: left;top: 6em;bottom: 0em;">Edit
				</span></a>
					</div>
					<div class="profile-info">
							<?php 
								
								$userData = $user->get("personnel", array("prnl_id", "=", Session::get(Config::get("session/session_name"))));
								$accountData = $user->get("prnl_account", array("account_id", "=", Session::get(Config::get("session/session_name"))));
								$userDesignation =  $user->get("units", array("ID", "=", $userData->prnl_designated_office));

							?>						
						<div class="">
							<div>
								<h2 class="no-margins">
									<?php echo $currentUser[0];?>
								</h2>
								<h4><?php echo $currentUser[1];?></h4>
								<small>
										<b>date joined:</b> <a style="font-size:12px;" class="text-navy"><?php echo Date::translate($userData->date_joined, 2); ?></a>
								</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<table class="table small m-b-xs">
						<tbody>
						<tr>
							<td>
								<strong style="color:black">0</strong> Projects
							</td>
							<td>
								<strong>0</strong> Request Forms Created
							</td>

						</tr>
						<tr>
							<td>
								<strong style="color:#28a745">0</strong> Ongoing
							</td>
							<td>
								<strong>0</strong> Uncreceived
							</td>
						</tr>
						<tr>
							<td>
								<strong style="color:#007bff">0</strong> Finished
							</td>
							<td>
								<strong>0</strong> Revision Requests
							</td>
						</tr>
						<tr>
							<td>
								<strong style="color:red">0</strong> Failed
							</td>
						</tr>						
						</tbody>
					</table>
				</div>
				<div class="col-md-3">
				<?php
					// current week
					$weekNo = date('W');
					$startOftheWeek = date('Y-m-d', strtotime('monday this week'))." 00:00:00";
					$endOftheWeek = date('Y-m-d', strtotime('sunday this week'))." 23:59:59";
				?>
					<small><?php echo Date::translate("now","weekno");?><br>
					<?php echo Date::translate('monday this week',2). "&nbsp&nbsp to &nbsp&nbsp" .Date::translate('sunday this week',2) ;?>
					</small>
					<h2 class="no-margins">Activity Frequency</h2>
					<div id="sparkline1"></div>
				</div>


            </div>
            <div class="row">

				<div class="col-lg-8">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li><a class="nav-link active show" data-toggle="tab" href="#tab-1"> <i class="far fa-user" style="font-size:18px"></i> Personal Information</a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#tab-2"><i class="fas fa-user-shield" style="font-size:18px"></i>Security Settings</a></li>
                           
                        </ul>
                        <div class="tab-content">

                            <div id="tab-1" class="tab-pane active show">
                                <div class="panel-body">
									
									<div class="alert alert-warning" style="color:#dc3545"><i class="fas fa-info"></i>
										Keep in mind that your personal information should always be updated so you don't miss any notifications when you need it the most.
									</div>
								
								
                                    <div class="form-group row" style="margin-bottom:0px">
										<label class="col-lg-3 col-form-label" style="font-weight:bold">Name</label>
										<div class="col-lg-7" style="margin-top:5px"><p class="form-control-static" ><?php echo $currentUser[0];?></p></div>
										<div class="col-lg-2"><button class="btn btn-default btn-xs" type="button" style="margin-top:5px" data-toggle="modal" data-target="#profile-setting" data-rcontent="name" data-user="<?php echo $user->data()->account_id;?>" data-fname="<?php echo $userData->prnl_fname;?>" data-mname="<?php echo $userData->prnl_mname;?>" data-lname="<?php echo $userData->prnl_lname;?>" data-extname="<?php echo $userData->prnl_ext_name;?>" ><i class="fas fa-edit"></i> Edit</button></div>
									</div>
									<div class="hr-line-dashed" style="margin:0px 0"></div>		
									
                                    <div class="form-group row" style="margin-bottom:0px">
										<label class="col-lg-3 col-form-label" style="font-weight:bold">Email</label>
										<div class="col-lg-7" style="margin-top:5px"><p class="form-control-static" ><?php echo $userData->prnl_email;?></p></div>
										<div class="col-lg-2"><button class="btn btn-default btn-xs" type="button" style="margin-top:5px" data-toggle="modal" data-target="#profile-setting-small" data-user="<?php echo $user->data()->account_id;?>" data-rcontent="email" data-toupdate="<?php echo $userData->prnl_email;?>"><i class="fas fa-edit"></i> Edit</button></div>
									</div>
									<div class="hr-line-dashed" style="margin:0px 0"></div>		

									
                                    <div class="form-group row" style="margin-bottom:0px">
										<label class="col-lg-3 col-form-label" style="font-weight:bold">Phone</label>
										<div class="col-lg-7" style="margin-top:5px"><p class="form-control-static" ><?php echo $userData->phone;?></p></div>
										<div class="col-lg-2"><button class="btn btn-default btn-xs" type="button" style="margin-top:5px" data-toggle="modal" data-target="#profile-setting-small" data-user="<?php echo $user->data()->account_id;?>" data-rcontent="phone" data-toupdate="<?php 
										$z = substr($userData->phone, 3,12);
										echo $z;?>"><i class="fas fa-edit"></i> Edit</button></div>
									</div>
									<div class="hr-line-dashed" style="margin:0px 0"></div>
									
                                    <div class="form-group row" style="margin-bottom:0px">
										<label class="col-lg-3 col-form-label" style="font-weight:bold">Designation / College</label>
										<div class="col-lg-7" style="margin-top:5px"><p class="form-control-static" ><?php echo $userDesignation->office_name;?></p></div>
										<div class="col-lg-2"><button class="btn btn-default btn-xs" type="button" style="margin-top:5px" data-toggle="modal" data-target="#profile-setting-small" data-user="<?php echo $user->data()->account_id;?>" data-rcontent="designation" data-toupdate="<?php echo $userData->edr_designated_office;?>" ><i class="fas fa-edit"></i> Edit</button></div>
									</div>
									<div class="hr-line-dashed" style="margin:0px 0"></div>		
									
                                    <div class="form-group row" style="margin-bottom:0px">
										<label class="col-lg-3 col-form-label" style="font-weight:bold">Specific Office</label>
										<div class="col-lg-7" style="margin-top:5px"><p class="form-control-static" ><?php echo $userData->current_specific_office;?></p></div>
										<div class="col-lg-2"><button class="btn btn-default btn-xs" type="button" style="margin-top:5px" data-toggle="modal" data-target="#profile-setting-small" data-user="<?php echo $user->data()->account_id;?>" data-rcontent="office" data-toupdate="<?php echo $userData->current_specific_office;?>"><i class="fas fa-edit"></i> Edit</button></div>
									</div>
									<div class="hr-line-dashed" style="margin:0px 0"></div>			
																	
									
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
								
									<div class="alert alert-warning" style="color:#dc3545"><i class="fas fa-info"></i>
										Keep in mind that your personal information should always be updated so you don't miss any notifications when you need it the most.
									</div>

                                    <div class="form-group row" style="margin-bottom:0px">
										<label class="col-lg-3 col-form-label" style="font-weight:bold">Username</label>
										<div class="col-lg-7" style="margin-top:5px"><p class="form-control-static" ><?php echo $accountData->username;?></p></div>
										<div class="col-lg-2"><button class="btn btn-default btn-xs" type="button" style="margin-top:5px" data-toggle="modal" data-target="#profile-setting-small" data-user="<?php echo $user->data()->account_id;?>" data-rcontent="username" data-toupdate="<?php echo $accountData->username;?>"><i class="fas fa-edit"></i> Edit</button></div>
									</div>     
									<div class="hr-line-dashed" style="margin:0px 0"></div>																		
									
                                    <div class="form-group row" style="margin-bottom:0px">
										<label class="col-lg-3 col-form-label" style="font-weight:bold">Password</label>
										<div class="col-lg-7" style="margin-top:5px"><p class="form-control-static" >&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;</p></div>
										<div class="col-lg-2"><button class="btn btn-default btn-xs" type="button" style="margin-top:5px" data-toggle="modal" data-target="#profile-setting-small" data-user="<?php echo $user->data()->account_id;?>" data-rcontent="password" data-toupdate=""><i class="fas fa-edit"></i> Edit</button></div>
									</div>     
									<div class="hr-line-dashed" style="margin:0px 0"></div>									
                                </div>
							</div>							

                        </div>
                    </div>
                </div>			



                <div class="col-lg-4 m-b-lg">
					<h2>Recent Activities</h2>
                    <div id="vertical-timeline" class="vertical-container light-timeline no-margins">
					
					
							<?php

								if($activityLogs =  $user->getAll("activity_log", array("made_by", "=", Session::get(Config::get('session/session_name'))))){

									if(($activity_count = count($activityLogs)) > 5){

										for($x = 1; $x<6; $x++){


											switch ($activityLogs[$activity_count-$x]->type){
												case 'login info update':
													$timelineIcon = "fas fa-shield-alt";
													$bg = "yellow-bg";
													break;
												case 'personal info update':
													$timelineIcon = "far fa-address-card";
													$bg = "lazur-bg";
													break;
												
												default:
													# code...
													break;
											}
							?>
							
                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon <?php echo $bg;?>">
                                <i class="<?php echo $timelineIcon;?>"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                <h2><?php echo strtoupper($activityLogs[$activity_count-$x]->type);?></h2>
                                <p>	

									<?php 
									echo $activityLogs[$activity_count-$x]->activity;
									
									
									?>
                                </p>
                                
                                    <span class="vertical-date">										
										<?php echo Date::translate($activityLogs[$activity_count-$x]->date_log, 1);?><br>
                                        <small>
                                        <?php
											$datetime_today = Date::translate('now', 'now');
											$interval = date_diff(date_create($datetime_today), date_create($activityLogs[$activity_count-$x]->date_log));
											echo $interval->format("%a days %h hours ago");										
										?>										
										</small>
                                    </span>
                            </div>
                        </div>							
											
							<?php

										}

									}else{
										foreach ($activityLogs as $log) {
											switch ($log->type) {
												case 'login info update':
													$timelineIcon = "fas fa-shield-alt";
													$bg = "yellow-bg";
													break;
												case 'personal info update':
													$timelineIcon = "far fa-address-card";
													$bg = "lazur-bg";
													break;
												
												default:
													# code...
													break;
											}
							?>
									<div class="vertical-timeline-block">
										<div class="vertical-timeline-icon <?php echo $bg;?>">
											<i class="<?php echo $timelineIcon;?>"></i>
										</div>

										<div class="vertical-timeline-content">
											<h2><?php echo strtoupper($log->type);?></h2>
											<p>	

												<?php 
												echo $log->activity;
												
												
												?>
											</p>
											
												<span class="vertical-date">										
													<?php echo Date::translate($log->date_log, 1);?><br>
													<small>
													<?php
														$datetime_today = Date::translate('now', 'now');
														$interval = date_diff(date_create($datetime_today), date_create($log->date_log));
														echo $interval->format("%a days %h hours ago");										
													?>										
													</small>
												</span>
										</div>
									</div>
							<?php
										}
									}

								}


								//echo "<pre>",print_r($activityLogs),"</pre>";
							
							?>						


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

	<?php include_once'../../includes/parts/admin_scripts.php'; ?>
    <!-- Sparkline -->
    <script src="../../assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>	
	<script>

        $(document).ready(function() {
			
			$('.custom-file-input').on('change', function() {
			   let fileName = $(this).val().split('\\').pop();
			   $(this).next('.custom-file-label').addClass("selected").html(fileName);
			}); 			






			<?php
				$activities =  $user->activity_frequency(Session::get(Config::get('session/session_name')));
			?>

            $("#sparkline1").sparkline([<?php echo implode(", ",$activities);?>],{
                type: 'line',
                width: '100%',
                height: '100',
                lineColor: '#1ab394',
                fillColor: "#fff3cd"
            });


        });
    </script>

</body>

</html>
