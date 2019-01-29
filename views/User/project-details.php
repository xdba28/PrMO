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

    <title>PrMO OPPTS | Projects Details</title>

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
                        <li class="breadcrumb-item">
                            <a href="#">Current Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Project Details</strong>
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
				<?php
					if(isset($_GET['refno'])){
						$refno = base64_decode($_GET['refno']);
						
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
            <div id="details-div" class="col-lg-9">
                <div class="wrapper wrapper-content animated fadeInUp">
				
			
				
                    <div id="status-div" class="ibox myShadow">
                        <div class="ibox-content" style="min-height:800px">
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
                                        <div class="col-sm-8 text-sm-left"><dd class="mb-1"><span id="status-span" class="label label-primary"><?php echo $project->project_status;?></span></dd></div>
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
                                            <dt>Origin Forms:</dt>
                                        </div>
                                        <div class="col-sm-8 text-sm-left">
                                            <dd class="project-people mb-1">
											<?php 
												
												$forms = json_decode($project->request_origin, true);
												
												//$test = '<a value="something" href="my-forms?q='.implode('"></a>, <a href="my-forms?q=', $forms).'"></a>';
												//echo $test;
												$comaCounter = 1;
												foreach($forms as $individualOrigin => $value){
													echo '<a href="my-forms?q='.base64_encode($value).'">'.$value.'</a>';
													if($comaCounter < (count($forms))){
														echo '<a style="font-size:16px; color:red"> / </a>';
													}
													$comaCounter++;
												}
												
											
											?>
                                            </dd>
                                        </div>
                                    </dl>
									
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <dl class="row mb-0">
                                        <div class="col-sm-2 text-sm-right">
                                            <dt>Completion:</dt>
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
															$icon = 'ti-flag-alt text-warning';
															$message ="<strong>Uh.. Oh, </strong> Your project encountered an issue related to ". $remarksParts[1] .". Check the details in your project's detailed history tab.";
															break;
														case "DECLA":													
															if($remarksParts[1] == "FAILURE"){
																$icon = 'fas fa-exclamation-triangle text-danger';
																$message ="<strong>We're sorry, but </strong> Your project was declared as failed project. Check the details in your project's detailed history tab.  ";
															}else if($remarksParts[1] == "FINISH"){
																$icon = 'ti-check-box text-info';
																$message ="<strong>You're good to go!!</strong> for this project has finished its required transactions in the system.";
															}															
															
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
													case 'SOLVE':
														$remarksParts =  explode('^', $detail->remarks);
														$announcementClass = 'far fa-thumbs-up text-success';
														$newRemarks = $remarksParts[2];
														break;
													case 'DECLA':
														$remarksParts =  explode('^', $detail->remarks);
															if($remarksParts[1] == "FAILURE"){
																
																$announcementClass = 'ti-alert text-danger';
																$newRemarks = $remarksParts[2];
															}else if($remarksParts[1] == "FINISH"){
																
																$announcementClass = 'far fa-flag text-info';
																$newRemarks = $remarksParts[2];
															}
														
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

                </div>
            </div>
            <div id="timeline-div" class="col-lg-3" style="padding-left:0px">
                    <div id="vertical-timeline" class="vertical-container light-timeline no-margins" style="width:100%; padding-top:17px">
											
					
							<?php
							

								if($Updates = $user->importantUpdates2($refno)){

									
									if(($upd_count = count($Updates)) > 5){

										for($x = 1; $x<6; $x++){

							?>
							
                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon <?php echo $bg;?>">
                                <i class="<?php echo $timelineIcon;?>"></i>
                            </div>

                            <div class="vertical-timeline-content" style="margin-left:45px">
                                <h2><?php echo strtoupper($activityLogs[$activity_count-$x]->type);?></h2>
                                <p>	

									<?php 
									echo $activityLogs[$activity_count-$x]->activity;
									
									?>
                                </p>
                                
                                    <span class="vertical-date">										
										<?php echo Date::translate($activityLogs[$activity_count-$x]->date_log, 1);?>
										<br>
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
										// echo "<pre>",print_r($Updates),"</pre>";
										foreach ($Updates as $update) {

											$identifier = explode("^", $update->remarks);
											switch ($identifier[0]) {
												case "AWARD":
													$timelineIcon = "fas fa-award";
													$bg = "lazur-bg";
													$displayMessage = $identifier[2];
													break;
												case "SOLVE":
													$timelineIcon = "fas fa-thumbs-up";
													$bg = "bg-success";
													$displayMessage = "Pre-procurement evaluation issue resolved";

													break;
												case "ISSUE":
													$timelineIcon = "fas fa-exclamation-triangle";
													$bg = "yellow-bg";
													switch ($identifier[1]) {
														case 'pre-procurement':
															$displayMessage = "Pre-procurement evaluation issue encountered";
															break;
														
														default:
															# code...
															break;
													}
													break;
												case "DECLARATION":
													if($identifier[1]=="FINISH"){
														$timelineIcon = "far fa-flag";
														$bg = "bg-info";
														$displayMessage = "This project has completed all required processes and transactions in the system";

													}elseif($identifier[1] == "FAILURE"){
														$timelineIcon = "ti-face-sad";
														$bg = "bg-danger";
														$displayMessage = "Project was declared as a failed project";

													}
													
													break;
												default:

													$identifier[1] = "TECHNICAL MEMBER EVALUATION";
													$timelineIcon = "fas fa-users";
													$bg = "bg-primary";
													$displayMessage = "Project documents are being evaluated by respective Technical member.";
													break;
											}
										
							?>

                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon <?php echo $bg;?>">
                                <i class="<?php echo $timelineIcon;?>"></i>
                            </div>

                            <div class="vertical-timeline-content" style="margin-left:45px">
                                <h2><?php echo strtoupper($identifier[1]);?></h2>
                                <p>	
									
									<?php 
									 echo $displayMessage;
									?>
                                </p>
                                
                                    <span class="vertical-date">										
										<?php echo Date::translate($update->logdate, 1);?>
										<br>
                                        <small>
                                        <?php
											$interval = date_diff(date_create(Date::translate('now', 'now')), date_create($update->logdate));
											echo $interval->format("%a days ago");										
										?>										
										</small>
                                    </span>
                            </div>
                        </div>									

							<?php
										}
									}
									
									$noUpdates = false;
								}else{
									
									$noUpdates = true;
									
							?>
							
									<h1>no updates</h1>
							
							<?php
								}
							?>						


                    </div>
            </div>			
			
			<?php
				}
			?>			

        </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<button class="back-to-top" type="button"></button>
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<div class="modal fade" id="documents" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			
				<div class="modal-header">
					<h3 class="modal-title">Available Documents</h3>		
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<?php
							$admin = new Admin();
							$documents = $admin->checkDocuments($refno);
	
							foreach($documents['request'] as $request){
								echo '									
								<div class="my-file-box">
									<div class="file">
										<a href="#" onclick="window.open(\'../../bac/forms/pr-jo-doc?id='.$request['ref_no'].'&type='.$request['type'].'\');">
											<span class="corner"></span>
											<div class="icon">
												<i class="fas fa-file-word"></i>
											</div>
											<div class="file-name">
												'.$request['title'].'.docx
											</div>
										</a>
									</div>
								</div>
								';
							}
	
							if($documents['technical']){
								echo '									
								<div class="my-file-box">
									<div class="file">
										<a href="#" onclick="window.open(\'../../bac/forms/pre-eval-form?g='.$refno.'\');">
											<span class="corner"></span>
											<div class="icon">
												<i class="fas fa-file-word"></i>
											</div>
											<div class="file-name">
												Technical Working Group Pre-evaluation.docx
											</div>
										</a>
									</div>
								</div>
								';

								if(isset($documents['canvass_forms'])){
									foreach($documents['canvass_forms'] as $lot){
										echo '									
										<div class="my-file-box">
											<div class="file">
												<a href="#" onclick="window.open(\'../../bac/forms/canv-prop?rq='.base64_encode($refno).'&t='.base64_encode($lot['title']).'&i='.$lot['canvass_id'].'\');">
													<span class="corner"></span>
													<div class="icon">
														<i class="fas fa-file-word"></i>
													</div>
													<div class="file-name">
														'.$lot['title'].'.docx
													</div>
												</a>
											</div>
										</div>
										';

										foreach($lot['publication'] as $key => $pub){
											$pub_quo = ($project->type === "PR") ? "Quotation" : "Proposal";
											echo '
											<div class="my-file-box">
												<div class="file">
													<a href="#" onclick="window.open(\'../../bac/forms/reso-prop?rq='.base64_encode($refno).'&f='.$lot['canvass_id'].'&m='.$pub['mode_index'].'\');">
														<span class="corner"></span>
														<div class="icon">
															<i class="fas fa-file-word"></i>
														</div>
														<div class="file-name">
															Request for '.$pub_quo.' - '.$pub['mode'].'.docx
														</div>
													</a>
												</div>
											</div>
											';
										}
									}
								}
							}
							// echo "<pre>".print_r($documents)."</pre>";
						?>
	
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include_once'../../includes/parts/user_scripts.php'; ?>
	
	<script>

		
		 $(document).ready(function(){
			<?php
				if($updates = $user->importantUpdates($refno)){
					$identifier = explode("^", $updates[0]->remarks);
						switch($identifier[0]){
							case "AWARD":
								echo "$('#status-div').removeClass().addClass('ibox myShadow');";
								break;
							case "SOLVE":
								// echo "$('#status-div').removeClass().addClass('ibox myShadow');";
								echo "
								$('#status-div').removeClass().addClass('ibox myShadow').delay(2000).queue(function( next ){

									$(this).addClass('ibox successShadow').delay(5000).queue(function( next ){
										$(this).removeClass().addClass('ibox myShadow'); 
										next();
									});
								
									next();
								});
								";					
								break;
							case "ISSUE":
								echo "$('#status-div').removeClass().addClass('ibox warningShadow');";
								echo "$('#status-span').removeClass().addClass('label label-warning');";
								echo "$('.progress-bar').addClass('yellow-bg');";
								break;
							case "FAIL":							
								echo "$('#status-div').removeClass().addClass('ibox dangerShadow');";
								echo "$('.progress-bar').addClass('bg-danger');";
								break;						
						}
				}else{
					echo "$('#status-div').removeClass().addClass('ibox myShadow');";
				}


				// check the status of the project failed or finished
				if($thisProject = $user->get("projects", array("project_ref_no", "=", $refno))){

					if($thisProject->project_status == "FINISHED"){

						echo "$('#status-div').removeClass().addClass('ibox infoShadow');";
						echo "$('.progress-bar').removeClass().addClass('progress-bar progress-bar-striped progress-bar-animated lazur-bg');";
						echo "$('#status-span').removeClass().addClass('label label-info');";

					}else if($thisProject->project_status == "FAILED"){

						echo "$('#status-div').removeClass().addClass('ibox dangerShadow');";
						echo "$('.progress-bar').removeClass().addClass('progress-bar progress-bar-striped progress-bar-animated bg-danger');";
						echo "$('#status-span').removeClass().addClass('label label-danger');";

					}else if($thisProject->project_status == "PAUSED"){

						echo "$('#status-div').removeClass().addClass('ibox warningShadow');";
						echo "$('.progress-bar').removeClass().addClass('progress-bar progress-bar-striped progress-bar-animated yellow-bg');";
						echo "$('#status-span').removeClass().addClass('label label-warning');";

					}else{

						echo "$('#status-div').removeClass().addClass('ibox myShadow');";
						echo "$('.progress-bar').removeClass().addClass('progress-bar progress-bar-striped progress-bar-animated primary-bg');";
						echo "$('#status-span').removeClass().addClass('label label-primary');";

					}



					
					// echo "<pre>",print_r($status),"</pre>";

				}
			
			?>
			
			var noUpdates = <?php echo $noUpdates;?>;
			
			if(noUpdates == true){
				$('#timeline-div').remove();
				$('#details-div').removeClass('col-lg-9').addClass('col-lg-12');
			}
			 
		 });
		
	</script>

</body>

</html>
