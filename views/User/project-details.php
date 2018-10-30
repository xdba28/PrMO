<?php 

    require_once('../../core/init.php');

    $user = new User(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
    }
    


?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Projects</title>

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
                    <h2>Active Projects</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Current Projects</strong>
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
        <div class="row">
            <div class="col-lg-9">
                <div class="wrapper wrapper-content animated fadeInUp">
				
				<?php
					if(isset($_GET['refno'])){
						$refno = $_GET['refno'];

						$user = new User();
						$projects = $user->selectAll("projects");

						$valid = false;

						foreach ($projects as $project){
							if($refno == $project->project_ref_no){
								$valid = true;
							}
						}
		
						if(!$valid){
							include('../../includes/errors/404.php');
							exit();						
						}

						$project = $user->get('projects', array('project_ref_no', '=', $refno));
						$endusers = json_decode($project->end_user, true);
						$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1); //computation on progress report
						$stepDetails = json_decode($project->stepdetails, true);
						$projectOrigins = json_decode($project->request_origin, true);


				?>				
				
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        <h2><?php echo $project->project_title;?></h2>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right"> <dt>Reference No:</dt></div>
                                        <div class="col-sm-8 text-sm-left"> <dd class="mb-1"><?php echo $project->project_ref_no;?></dd></div>
                                    </dl>								
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right"><dt>Status:</dt> </div>
                                        <div class="col-sm-8 text-sm-left"><dd class="mb-1"><span class="label label-primary"><?php echo $project->project_status;?></span></dd></div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right"><dt>Requested by: </dt> </div>
										<div class="col-sm-8 text-sm-left"><dd class="mb-1"><?php 
										
										foreach ($endusers as $enduser) {
											echo $user->fullnameOfEnduser($enduser), "<br>";
										}
										
										?></dd> </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right"><dt>Type of project:</dt> </div>
                                        <div class="col-sm-8 text-sm-left"> <dd class="mb-1"><?php echo $project->type;?></dd></div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right"><dt>Current activity:</dt> </div>
                                        <div class="col-sm-8 text-sm-left"> <dd class="mb-1"><a href="#" class="text-danger"> <?php echo $stepDetails[$project->accomplished];?></a> </dd></div>
                                    </dl>
                                </div>
                                <div class="col-lg-6" id="cluster_info">

                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right">
                                            <dt>ABC:</dt>
                                        </div>
                                        <div class="col-sm-8 text-sm-left">
											<dd class="mb-1"><?php echo "₱ ",number_format($project->ABC, 2);?></dd>
                                        </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right">
                                            <dt>MOP:</dt>
                                        </div>
                                        <div class="col-sm-8 text-sm-left">
											<dd class="mb-1"><?php echo $project->MOP;?></dd>
                                        </div>
                                    </dl>									
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right">
                                            <dt>Registered:</dt>
                                        </div>
                                        <div class="col-sm-8 text-sm-left">
											<dd class="mb-1"><?php

											echo Date::translate($project->date_registered, '1');
											
											?></dd>
                                        </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right">
                                            <dt>Last Updated:</dt>
                                        </div>
                                        <div class="col-sm-8 text-sm-left">
											<dd class="mb-1"><?php
											
											$logdata = $user->logLastUpdated($refno);

											if($logdata->result != 0){
												echo Date::translate($logdata->logdate, '1');
											}else{
												echo "No update yet";
											}


											?></dd>
                                        </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-4 text-sm-right">
                                            <dt>Participants:</dt>
                                        </div>
                                        <div class="col-sm-8 text-sm-left">
                                            <dd class="project-people mb-1">
                                                <a href=""><img alt="image" class="rounded-circle" src="img/a3.jpg"></a>
                                                <a href=""><img alt="image" class="rounded-circle" src="img/a1.jpg"></a>
                                                <a href=""><img alt="image" class="rounded-circle" src="img/a2.jpg"></a>
                                                <a href=""><img alt="image" class="rounded-circle" src="img/a4.jpg"></a>
                                                <a href=""><img alt="image" class="rounded-circle" src="img/a5.jpg"></a>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <dl class="row mb-0">
                                        <div class="col-sm-2 text-sm-right">
                                            <dt>Completed:</dt>
                                        </div>
                                        <div class="col-sm-10 text-sm-left">
                                            <dd>
                                                <div class="progress m-b-1">
                                                    <div style="width: <?php echo $accomplishment;?>%;" class="progress-bar progress-bar-striped progress-bar-animated"></div>
                                                </div>
                                                <small>Project progress is now on <strong><?php echo $accomplishment;?>%</strong>. Next step to this is <?php echo $stepDetails[$project->accomplished+1];?>.</small><br>
												<small><?php echo $project->accomplished, " Out of ", $project->steps," steps accomplished.";?></small>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            <div class="row m-t-sm">
                                <div class="col-lg-12">
                                <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li><a class="nav-link active" href="#tab-1" data-toggle="tab">Important Updates</a></li>
                                            <li><a class="nav-link" href="#tab-2" data-toggle="tab">Detailed History</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">

                                <div class="tab-content">
                                <div class="tab-pane active" id="tab-1">
                                    <div class="feed-activity-list">
										
										<?php
											$updates = $user->importantUpdates($refno);
											
											if($updates){
												
												foreach($updates as $update){
													$identifier =  substr($update->remarks, 0, 5);
													$remarksParts = explode('^', $update->remarks);
													switch($identifier){
														case "AWARD":
															$icon = 'ti-medall text-info';
															$message ='<strong>Congratulation! </strong>'. $remarksParts[1] .' has been successfuly finalized';
															break;
														case "SOLVE":
															$icon = 'far fa-thumbs-up text-success';
															$message ='<strong>Cheer Up! </strong> Issues realated to '.$remarksParts[1].' was successfuly solved.';
															break;
														case "ISSUE":
															$icon = 'ti-flag-alt text-danger';
															$message ="<strong>Uh.. Oh, </strong> Your project encountered an issue related to ". $remarksParts[1] .". Check the details in your project's detailed history tab.";
															break;											
													}
													
													echo '
														<div class="feed-element">
															<a href="#" class="float-left">
																<i class="'.$icon.'" style="font-size: 50px;"></i>
															</a>
															<div class="media-body ">
																'.$message.' <br>
																<small class="text-muted">'.Date::translate($update->logdate, '1').'</small>
															</div>
														</div>													
													';
												}
											}else{
												echo '
												
												<div class="feed-element">
													<a href="#" class="float-left">
													  <i class="ti-info-alt " style="font-size:44px;"></i>
													</a>
													<div class="media-body ">	
													
														<strong>&nbsp&nbsp No Important Updates yet.</strong>
													</div>
												</div>												
												
												';
											}
										?>
                                    </div>

                                </div>
                                <div class="tab-pane" id="tab-2">

                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Type</th>
                                            <th>Logdate</th>
                                            <th>Remarks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
										<?php 
											$logdetails = $user->projectHistory($projectOrigins, $project->project_ref_no);
											
										
											foreach ($logdetails as $detail){												
												
												// $remark = ($detail->remarks == 'START_PROJECT') ? $newRemarks = "PR/JO was received in the office." : $newRemarks=$detail->remarks;

												//check if there is an issue identifier in the remarks
												$identifier = substr($detail->remarks, 0, 5);
												switch ($identifier) {
													case 'ISSUE':
														$remarksParts =  explode('^', $detail->remarks);
														$announcementClass = "ti-flag-alt text-danger";
														$newRemarks = $remarksParts[2];
														break;
													case 'START':
														$announcementClass = "ti-announcement  text-warning";
														$newRemarks = "PR/JO was received in the office.";
														break;
													case 'AWARD':
														$remarksParts =  explode('^', $detail->remarks);
														$announcementClass = 'ti-medall text-info';
														$newRemarks = $remarksParts[2];
														break;
													
													default:
														$announcementClass = "ti-announcement  text-warning";
														$newRemarks=$detail->remarks;
														break;
												}


										?>
                                        <tr>
                                            <td>
                                                <i class="<?php echo $announcementClass;?>" style="font-size: 25px;"></i>
                                            </td>
                                            <td>
                                              <?php echo $detail->type;?>
                                            </td>
                                            <td style=" min-width:120px;">
											<?php echo Date::translate($detail->logdate, '1');?>
                                            </td>
                                            <td>
                                            <p class="">
											<?php echo $newRemarks;?>
                                            </p>
                                            </td>

                                        </tr>
										<?php
											}
										?>
                                        </tbody>
                                    </table>

                                </div>
                                </div>

                                </div>

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
            <div class="col-lg-3">
                <div class="wrapper wrapper-content project-manager">
                    <h4>Project description</h4>
                    <img src="img/zender_logo.png" class="img-fluid">
                    <p class="small">
                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look
                        even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing
                    </p>
                    <p class="small font-bold">
                        <span><i class="fa fa-circle text-warning"></i> High priority</span>
                    </p>
                    <h5>Project tag</h5>
                    <ul class="tag-list" style="padding: 0">
                        <li><a href=""><i class="fa fa-tag"></i> Zender</a></li>
                        <li><a href=""><i class="fa fa-tag"></i> Lorem ipsum</a></li>
                        <li><a href=""><i class="fa fa-tag"></i> Passages</a></li>
                        <li><a href=""><i class="fa fa-tag"></i> Variations</a></li>
                    </ul>
                    <h5>Project files</h5>
                    <ul class="list-unstyled project-files">
                        <li><a href=""><i class="fa fa-file"></i> Project_document.docx</a></li>
                        <li><a href=""><i class="fa fa-file-picture-o"></i> Logo_zender_company.jpg</a></li>
                        <li><a href=""><i class="fa fa-stack-exchange"></i> Email_from_Alex.mln</a></li>
                        <li><a href=""><i class="fa fa-file"></i> Contract_20_11_2014.docx</a></li>
                    </ul>
                    <div class="text-center m-t-md">
                        <a href="#" class="btn btn-xs btn-primary">Add files</a>
                        <a href="#" class="btn btn-xs btn-primary">Report contact</a>

                    </div>
                </div>
            </div>
        </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<button class="back-to-top" type="button"></button>
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once'../../includes/parts/user_scripts.php'; ?>

</body>

</html>
