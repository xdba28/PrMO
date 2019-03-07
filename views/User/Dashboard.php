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
					// die($e->getMessage());
					Syslog::put($e,null,"error_log");
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
	
	<link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">

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
				<div class="row">
					<?php

						// get user id
						$current_user = Session::get(Config::get('session/session_name'));
						$myRequests = $user->like('projects', 'end_user', "%{$current_user}%");

						// select all projects
						// foreach count noa bac reso evaluation issue
						$reso_count = 0;
						$noa_count = 0;
						$eval_count = 0;

						$form_revision = $user->getAll('form_update_requests', array('requested_by', '=', $user->data()->account_id));

						if($myRequests){
							foreach($myRequests as $project){
								$logs = $user->getAll('project_logs', array('referencing_to', '=', $project->project_ref_no));
								foreach($logs as $log){
									$ex_detail = explode("^", $log->remarks);
									$identifier = substr($log->remarks, 0, 5);
									if($identifier === 'AWARD' && $ex_detail[1] === 'BAC Resolution') $reso_count++;
									if($identifier === 'AWARD' && $ex_detail[1] === 'Notice of Award') $noa_count++;
									if($identifier === 'ISSUE' && $ex_detail[1] === 'pre-procurement') $eval_count++;
								}
							}
						}
					
						if(Session::exists('greet')){
							//Session::flash('greet');
							$now =  date('H');
							if($now < 12){
								$checked = "";
								$secondGreeting = "Good Evening";
							}else if(($now == 12) || ($now < 18)){
								$checked = "checked";
								$secondGreeting = "Good Afternoon";
							}else{
								$checked = "checked";
								$secondGreeting = "Good Evening";
							}
							
							
							
					?>
					
						<div class="col-lg-4">
							<input id="greetingsSwitch" class="switch" <?php echo $checked;?> type="checkbox">
							<div class="switch-day-night">
								<div class="top" >
									<div class="sun-moon"></div>
									<div class="cloud a"><span></span></div>
									<div class="cloud b"><span></span></div>
									<div class="cloud c"><span></span></div>
								</div>
								<div class="bottom">
									<div class="text" data-day="Goodmorning <?php echo $currentUser[2];?>" data-night="<?php echo $secondGreeting;?> <?php echo $currentUser[2];?>"></div>
								
								</div>
							</div>	
						</div>

						<div class="col-lg-8">
							<div class="ibox-content forum-container">

								<div class="forum-title">
									<div class="float-right forum-desc">
										
										<div id="clockbox" style="color:black; font-size:18px"></div>
									</div>
									<h3>General Information</h3>
								</div>
								
								<?php
								
									$reports = $user->dashboardReports($user->data()->account_id);
									
								
								?>

								<div class="forum-item">
									<div class="row">
										<div class="col-md-8">
											<div class="forum-icon">
												<i class="fas fa-file" style="color:#089edb"></i>
											</div>
											<a href="#" class="forum-item-title">Projects</a>
											<div class="forum-sub-title">Shows basic status records of projects related to you.</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->projectStatusCount->TOTAL_PROJECTS;?>
											</span>
											<div>
												<small>Total</small>
											</div>
										</div>
										
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->projectStatusCount->ONGOING;?>
											</span>
											<div>
												<small>Ongoing</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->projectStatusCount->FINISHED;?>
											</span>
											<div>
												<small>Finished</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
											   <?php echo $reports->projectStatusCount->FAILED;?>
											</span>
											<div>
												<small>Failed</small>
											</div>
										</div>									
									</div>
								</div>

							
								
								<div class="forum-item">
									<div class="row">
										<div class="col-md-9">
											<div class="forum-icon">
												<i class="fas fa-star" style="color:#b1e831"></i>
											</div>
											<a href="#" class="forum-item-title">Important Updates</a>
											<div class="forum-sub-title">Includes important updates which needs your attention and action like Release of BAC Resolution,  Notice of Award, Pre-procurement issues to be resolved, etc...</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reso_count;?>
											</span>
											<div>
												<small>BAC Reso</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $noa_count;?>
											</span>
											<div>
												<small>NOA</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $eval_count;?>
											</span>
											<div>
												<small>Evaluation Issue/s</small>
											</div>
										</div>
									</div>
								</div>
								
								<div class="forum-item">
									<div class="row">
										<div class="col-md-9">
											<div class="forum-icon">
												<i class="fas fa-folder-open" style="color:#ffc107"></i>
											</div>
											<a href="#" class="forum-item-title">Request Forms</a>
											<div class="forum-sub-title">Summary of Forms created by the system, Unreceived forms, and Pending revision request to be confirmed by procurement aids.</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->request_forms_related->REQUEST_FORMS;?>
											</span>
											<div>
												<small>Created</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->request_forms_related->UNRECEIVED;?>
											</span>
											<div>
												<small>Unreceived</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
											   <?php echo $reports->request_forms_related->REVISION_REQUEST;?>
											</span>
											<div>
												<small>Pending Revision</small>
											</div>
										</div>
									</div>
								</div>

							</div>
							<br>
						</div>
						
						
						<!--user guidlines-->
						<div class="container" style="padding: 50px">
							<div class="text-center">
								<h1 class="animated fadeInDown text-navy ">User Guidline</h1>
							</div>
							<hr role="tournament1" style="margin-bottom: 10px;">
							<div class="container" style="margin:0px 0px 60px 47.49%;">
							  <div class="chevron"></div>
							  <div class="chevron"></div>
							  <div class="chevron"></div>
							</div>							
							
							
						<br><br>
						  <div class="row">
							<div class="col">
							  <div class="main-timeline">
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 1</span>
									<div class="timeline-icon">
									  <i class="fas fa-file-invoice" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Request Form Creation</h3>
									  <p class="description">
										PrMO Online Procurement Project Tracking System Innovates the creation of Requests forms from manual and non-uniform kind of creation of Purchase Request and Job Order Forms. You can now create request forms guided by the system to minimize erroneous practice of creating request forms.										
									  </p><br>
									  
									  <p class="description">
										To create your first request form click on the <code>Request Forms</code> from the side navigation and pick from which requests form you wish to create.
									  </p>									  
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 2</span>
									<div class="timeline-icon">
									  <i class="far fa-handshake" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Printing and Submission</h3>
									  <p class="description">
										After filling up the required field for your request and submitting the form, the system automatically generate the request form based on what your inputs are, by then you will be directed to a page where you can edit, delete, and review all your created forms with its status.										
									  </p><br>
									  
									  <p class="description">
										In the actual submission side, we still follow the tradional practice where you personally submits the request form/s together with the other requirement but as an improvement feature in the system, an incharge personnel receives your request form both physically and digitally then validates your actual submission from your inputed request data in the system.
									  </p>									  
									  
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 3</span>
									<div class="timeline-icon">
									  <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Tracking</h3>
									  <p class="description">
									  	Project tracking takes place when your request form/s are received and registered as a project, the system sends you a SMS to notify you that your project is up and processing
									  </p><br>
									  <p class="description">
									  	To start tracking click on the <code>Projects</code> from the left side navigation then click <code>Current Projets</code>, a list of your ongoing projects will be shown, from there you can click on the "Details" to view the latest updates and status of your project.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 4</span>
									<div class="timeline-icon">
									  <i class="fas fa-users" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Evaluation</h3>
									  <p class="description">
										After your request forms are successfully received and registered as a project, your request details is automatically sent to designated technical member to evaluate your request.
									  </p><br>
									  <p class="description">
									  	Also if the technical member declared some issues from your submissions specifically on your Purchase request or Job Order form, you will be notified through SMS and you can immidiately resolve the issue by editing your request form created through system and reprint it for resubimssion.
										To do this click on the <code>Projects</code> from the left side navigation, hit <code>Forms Created</code> then all your created forms will show, click "Details" to view your possible option on revising your request details.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 5</span>
									<div class="timeline-icon">
									  <i class="fas fa-gavel" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Procurement Phase</h3>
									  <p class="description">
										Procurement phase includes a lot of processes and transactions that you might not know. Some of this are DBMPS checking, Publication and Posting, Canvassing, creation of Abstract of bids, creation of BAC Resolutions and creation of many more documents to complete the required documents for your project to be finished. Some of this processes and transactions are already assisted by the system to lessen the average length of days manually processing these requirements.
									  </p><br>
									  <p class="description">
										Important updates during this phase like release of BAC Resolution and Notice of Award will be sent to you through SMS or Dashboard notifications to keep you informed on what is happening to your current projects.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 6</span>
									<div class="timeline-icon">
									  <i class="fas fa-archway" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Post Procurement transition</h3>
									  <p class="description">
										Post Procurement transition processes and transactions are creation of Purchase Order or Letter Order and Request for OS, it also includes transmital of some documents for Conforme of winning bidders. Meaning you can expect the for project's required documents from the BAC to be finished within a week to enter the Post Procurement Phase. Again, you will receive notifications with regards to documents that needs your concent before it enter the post procurement.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 7</span>
									<div class="timeline-icon">
									  <i class="far fa-flag" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Finish</h3>
									  <p class="description">
										The last process your project will be dealing with the system is logging out your project documents for transmital to COA for audit, after that your project will be labeled as "Finished" and will be kept in the system for future references and reports.
									  </p>
									</div>
								  </a>
								</div>
							  </div>
							</div>
						  </div>
						</div>    						
					
					<?php
					echo "<pre>",print_r($_SESSION),"</pre>";
						Session::flash("greet");
						}else{
							
					?>
					

						<div class="col-lg-12">
							<div class="ibox-content forum-container">

								<div class="forum-title">
									<div class="float-right forum-desc">
										
										<div id="clockbox" style="color:black; font-size:18px"></div>
									</div>
									<h3>General Information</h3>
								</div>
								
								<?php
								
									$reports = $user->dashboardReports($user->data()->account_id);
									
								
								?>

								<div class="forum-item">
									<div class="row">
										<div class="col-md-8">
											<div class="forum-icon">
												<i class="fas fa-file" style="color:#089edb"></i>
											</div>
											<a href="#" class="forum-item-title">Projects</a>
											<div class="forum-sub-title">Shows basic status records of projects related to you.</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->projectStatusCount->TOTAL_PROJECTS;?>
											</span>
											<div>
												<small>Total</small>
											</div>
										</div>
										
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->projectStatusCount->ONGOING;?>
											</span>
											<div>
												<small>Ongoing</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->projectStatusCount->FINISHED;?>
											</span>
											<div>
												<small>Finished</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
											   <?php echo $reports->projectStatusCount->FAILED;?>
											</span>
											<div>
												<small>Failed</small>
											</div>
										</div>									
									</div>
								</div>
								
								<div class="forum-item">
									<div class="row">
										<div class="col-md-9">
											<div class="forum-icon">
												<i class="fas fa-star" style="color:#b1e831"></i>
											</div>
											<a href="#" class="forum-item-title">Important Updates</a>
											<div class="forum-sub-title">Includes important updates which needs your attention and action like Release of BAC Resolution,  Notice of Award, Pre-procurement issues to be resolved, etc...</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reso_count;?>
											</span>
											<div>
												<small>BAC Reso</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $noa_count;?>
											</span>
											<div>
												<small>NOA</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $eval_count;?>
											</span>
											<div>
												<small>Evaluation Issue/s</small>
											</div>
										</div>
									</div>
								</div>
								
								<div class="forum-item">
									<div class="row">
										<div class="col-md-9">
											<div class="forum-icon">
												<i class="fas fa-folder-open" style="color:#ffc107"></i>
											</div>
											<a href="#" class="forum-item-title">Request Forms</a>
											<div class="forum-sub-title">Summary of Forms created by the system, Unreceived forms, and Pending revision request to be confirmed by procurement aids.</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->request_forms_related->REQUEST_FORMS;?>
											</span>
											<div>
												<small>Created</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												<?php echo $reports->request_forms_related->UNRECEIVED;?>
											</span>
											<div>
												<small>Unreceived</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
											   <?php echo $reports->request_forms_related->REVISION_REQUEST;?>
											</span>
											<div>
												<small>Pending Revision</small>
											</div>
										</div>
									</div>
								</div>

							</div>
							<br>
						</div>
						
						
						<!--user guidlines-->
						<div class="container" style="padding: 50px">
							<div class="text-center">
								<h1 class="animated fadeInDown text-navy ">User Guidline</h1>
							</div>
							<hr role="tournament1" style="margin-bottom: 10px;">
							<div class="container" style="margin:0px 0px 60px 47.49%;">
							  <div class="chevron"></div>
							  <div class="chevron"></div>
							  <div class="chevron"></div>
							</div>							
							
							
						<br><br>
						  <div class="row">
							<div class="col">
							  <div class="main-timeline">
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 1</span>
									<div class="timeline-icon">
									  <i class="fas fa-file-invoice" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Request Form Creation</h3>
									  <p class="description">
										PrMO Online Procurement Project Tracking System Innovates the creation of Requests forms from manual and non-uniform kind of creation of Purchase Request and Job Order Forms. You can now create request forms guided by the system to minimize erroneous practice of creating request forms.										
									  </p><br>
									  
									  <p class="description">
										To create your first request form click on the <code>Request Forms</code> from the side navigation and pick from which requests form you wish to create.
									  </p>									  
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 2</span>
									<div class="timeline-icon">
									  <i class="far fa-handshake" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Printing and Submission</h3>
									  <p class="description">
										After filling up the required field for your request and submitting the form, the system automatically generate the request form based on what your inputs are, by then you will be directed to a page where you can edit, delete, and review all your created forms with its status.										
									  </p><br>
									  
									  <p class="description">
										In the actual submission side, we still follow the tradional practice where you personally submits the request form/s together with the other requirement but as an improvement feature in the system, an incharge personnel receives your request form both physically and digitally then validates your actual submission from your inputed request data in the system.
									  </p>									  
									  
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 3</span>
									<div class="timeline-icon">
									  <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Tracking</h3>
									  <p class="description">
									  	Project tracking takes place when your request form/s are received and registered as a project, the system sends you a SMS to notify you that your project is up and processing
									  </p><br>
									  <p class="description">
									  	To start tracking click on the <code>Projects</code> from the left side navigation then click <code>Current Projets</code>, a list of your ongoing projects will be shown, from there you can click on the "Details" to view the latest updates and status of your project.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 4</span>
									<div class="timeline-icon">
									  <i class="fas fa-users" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Evaluation</h3>
									  <p class="description">
										After your request forms are successfully received and registered as a project, your request details is automatically sent to designated technical member to evaluate your request.
									  </p><br>
									  <p class="description">
									  	Also if the technical member declared some issues from your submissions specifically on your Purchase request or Job Order form, you will be notified through SMS and you can immidiately resolve the issue by editing your request form created through system and reprint it for resubimssion.
										To do this click on the <code>Projects</code> from the left side navigation, hit <code>Forms Created</code> then all your created forms will show, click "Details" to view your possible option on revising your request details.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 5</span>
									<div class="timeline-icon">
									  <i class="fas fa-gavel" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Procurement Phase</h3>
									  <p class="description">
										Procurement phase includes a lot of processes and transactions that you might not know. Some of this are DBMPS checking, Publication and Posting, Canvassing, creation of Abstract of bids, creation of BAC Resolutions and creation of many more documents to complete the required documents for your project to be finished. Some of this processes and transactions are already assisted by the system to lessen the average length of days manually processing these requirements.
									  </p><br>
									  <p class="description">
										Important updates during this phase like release of BAC Resolution and Notice of Award will be sent to you through SMS or Dashboard notifications to keep you informed on what is happening to your current projects.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 6</span>
									<div class="timeline-icon">
									  <i class="fas fa-archway" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Post Procurement transition</h3>
									  <p class="description">
										Post Procurement transition processes and transactions are creation of Purchase Order or Letter Order and Request for OS, it also includes transmital of some documents for Conforme of winning bidders. Meaning you can expect the for project's required documents from the BAC to be finished within a week to enter the Post Procurement Phase. Again, you will receive notifications with regards to documents that needs your concent before it enter the post procurement.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="" class="timeline-content">
									<span class="timeline-year">Step 7</span>
									<div class="timeline-icon">
									  <i class="far fa-flag" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Finish</h3>
									  <p class="description">
										The last process your project will be dealing with the system is logging out your project documents for transmital to COA for audit, after that your project will be labeled as "Finished" and will be kept in the system for future references and reports.
									  </p>
									</div>
								  </a>
								</div>
							  </div>
							</div>
						  </div>
						</div>
							
					<?php

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
	<script>
		function toggleGreetings(){
			setTimeout(function(){
				$('#greetingsSwitch').trigger('click');
			}, 300);			
		}
		
	</script>

	<script>
		$(document).ready(function () {
			toggleGreetings();
			$('#username-div').remove();
		});
	</script>



	<script>
		$(window).scroll(function() {
		  $('.wpb_animate_when_almost_visible').each(function() {
			  //console.log(this+$(this).position().top);
			if ($(window).scrollTop()+$(window).height() >= $(this).position().top && $(window).scrollTop() < $(this).position().top + $(this).height()) {
			  //console.log(this+$(this).position().top);
			  //if ($(this).hasClass('wpb_start_animation')){
			  if (!$(this).hasClass('wpb_start_animation')){
			  $(this).addClass('wpb_start_animation');
			  }
			}
			else if ($(this).hasClass('wpb_start_animation')) {
			  //console.log(this+$(this).position().top);
			  //if ($(this).hasClass('wpb_start_animation')){
			  $(this).removeClass('wpb_start_animation');
			  //}
			}
		  });
		});		
	</script>

	<script type="text/javascript">
		var tday=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
		var tmonth=["January","February","March","April","May","June","July","August","September","October","November","December"];

		function GetClock(){
		var d=new Date();
		var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate();
		var nhour=d.getHours(),nmin=d.getMinutes(),ap;
		if(nhour==0){ap=" AM";nhour=12;}
		else if(nhour<12){ap=" AM";}
		else if(nhour==12){ap=" PM";}
		else if(nhour>12){ap=" PM";nhour-=12;}

		if(nmin<=9) nmin="0"+nmin;

		var clocktext=""+tday[nday]+", "+tmonth[nmonth]+" "+ndate+" "+nhour+":"+nmin+ap+"";
		document.getElementById('clockbox').innerHTML=clocktext;
		}

		GetClock();
		setInterval(GetClock,1000);
	</script>


</body>

</html>
