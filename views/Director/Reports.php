<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

	$reports = $user->dashboardReports();
	$date = new DateTime();
   

?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Reports</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include_once'../../includes/parts/admin_styles.php'; ?>
    <!-- orris -->
    <link href="../../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/director_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg" style="background-color:#e7e7ec">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Procurement Reports</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Reports</strong>
                        </li>
                    </ol>
                </div>

            </div>
			
			<!-- Main Content -->
        <div class="wrapper wrapper-content animated fadeInRight" >

			<?php  $reports=$user->dashboardReports(); ?>
			<div class="p-w-md m-t-sm">
				<div class="row">

					<div class="col-lg-4">
						<div class="row">
							<div class="col-lg-12">
								<h2> Overall Projects Classification</h2>
								<div id="morris-donut-chart" class="text-center"></div>
							</div>
							<div class="col-lg-12">
								<h3>Classification Breakdown</h3>
								<div class="row text-center">


								<?php

									if(isset($reports["current_projects_breakdown"])){
										$breakdownCount = count($reports["current_projects_breakdown"]);
											if($breakdownCount == 1){$col = "col-lg-12";}else if($breakdownCount == 2){$col = "col-lg-6";}else if($breakdownCount == 3){$col = "col-lg-4";}
										for ($x=1; $x <= $breakdownCount; $x++){ 
											echo '
												<div class="'.$col.'">
													<canvas id="doughnutChart'.$x.'" width="120" height="120" style="margin: 18px auto 0"></canvas>
													<h5 id="doughnutLabel'.$x.'">Default Label</h5>
												</div>
											';
										}
										
									}
								
								?>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-8">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Projects Success Ratio (<?php echo $date->format('Y');?>)</h5>
							</div>
							<div class="ibox-content">
								<div class="alert alert-success">Try clicking on the legends below to toggle their display.</div>
							<div class="form-group" id="data_4">
                                <label class="font-normal">Select month to generate monthly procurement reports</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</span>
									<?php
										$date = new DateTime();
									?>
									<input type="text" class="form-control" data-date="month" value="<?php echo $thisMonth = $date->format('m/d/Y');?>">
									<span class="input-group-addon">
										<a href="../../bac/forms/report?m=<?php echo $thisMonth = $date->format('m/d/Y');?>" data-btn="dl">Download</a>
									</span>
                                </div>
                            </div>								
								<div>
									<canvas id="barChart" height="180"></canvas>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-lg-4">
						<a data-toggle="modal" data-target="#s_implementing">
						<div class="widget style1 myShadow" style="background-color:#ea3c14 !important; color:white; height: 120px;">
							<div class="row">
								<div class="col-4">
									<i class="fas fa-university fa-5x"></i>
								</div>
								<div class="col-8 text-right">
									<span>Ongoing Projects by sorted by Implementing office</span>
									
								</div>
							</div>
						</div>
						</a>
					</div>
					<div class="col-lg-4">
						<a data-toggle="modal" data-target="#s_usertag">
						<div class="widget style1 myShadow" style="background-color:#f26a1c !important; color:white; height: 120px;">
							<div class="row">
								<div class="col-4">
									<i class="fas fa-user-tag fa-5x"></i>
								</div>
								<div class="col-8 text-right">
									<span>Ongoing Projects by sorted by College or Unit Origin</span>
								</div>
							</div>
						</div>
						</a>
					</div>
					<div class="col-lg-4">
						<a href="statistical-reports">
						<div class="widget style1 myShadow" style="background-color:#F99324 !important; color:white; height: 120px;">
							<div class="row">
								<div class="col-4">
									<i class="far fa-chart-bar fa-5x"></i>
								</div>
								<div class="col-8 text-right">
									<span>Procurement Summary & College / Units Statistical Reports</span>
								</div>
							</div>
						</div>
						</a>
					</div>
					<!-- <div class="col-lg-3">
						<div class="widget style1" style="background-color:#fbb03b !important; color:white; height: 120px;">
							<div class="row">
								<div class="col-4">
									<i class="far fa-comments fa-5x"></i>
								</div>
								<div class="col-8 text-right">
									<span>Procurement Summary Report</span>
								</div>
							</div>
						</div>
					</div>					 -->
				</div><br>
				
				

		
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>All Ongoing Projects</h5>
							</div>
							<div class="ibox-content">

								<div class="table-responsive">
									<table id="ongoing_report" class="table table-striped table-bordered table-hover dataTables-example" >
									
									<thead>
										<tr>
											<th>#</th>
											<th>Reference</th>
											<th>Title</th>
											<th>MOP</th>
											<th>ABC</th>
											<th>Date Registered</th>
											<th>Implementation</th>
											<th>Workflow</th>
											<th>Accomplishment</th>
										</tr>
									</thead>
									
									<tbody>

										<?php

											$counter = 0;
											if($reports["current_projects"]){
											foreach ($reports["current_projects"] as $project){
												$counter++;

												$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1); //computation on progress report
												
											
										?>
										<tr class="gradeX">
											<td><?php echo $counter;?></td>
											<td>
												<?php echo $project->project_ref_no;?>
											</td>
											<td><?php echo $project->project_title;?></td>
											<td class="center"><?php echo $project->MOP;?></td>
											<td class="center"><?php echo Date::translate($project->ABC, "php");?></td>
											<td class="center"><?php echo Date::translate($project->date_registered, "2");?></td>
											<td class="center"><?php echo Date::translate($project->implementation_date, "2");?></td>
											<td class="center"><?php echo $project->workflow;?></td>
											<td class="center"><?php echo $accomplishment;?>%</td>										
										</tr>


										<?php
											}
										}
										?>
									</tbody>
									
									<tfoot>
										<tr>
											<th>#</th>
											<th>Reference</th>
											<th>Title</th>
											<th>MOP</th>
											<th>ABC</th>
											<th>Date Registered</th>
											<th>Implementation</th>
											<th>Workflow</th>
											<th>Accomplishment</th>
										</tr>
									</tfoot>
									</table>
								</div>

							</div>
						</div>
					</div>
				</div>
				
					
			</div>




        </div>
			<!-- Main Content End -->
			<button class="back-to-top" type="button"></button>		
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>
	
	<div class="modal inmodal fade" id="s_implementing" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-expanded">
			<div class="modal-content animated bounceIn">
				<div class="modal-header">
					<i class="fas fa-university modal-icon" style="color:#F99324"></i>
					<h4 class="modal-title">Implementing Offices</h4>
					<small class="font-bold">Shows the list of Ongoing projects sorted by given implementing office upon registration.</small>
				</div>
				<div class="modal-body" style="">
				
				<?php 

					if($projects = $user->ongoing_projects()){
						foreach ($projects as $project){
							$i_offices = json_decode($project->implementing_office, true);					
							$i_office_count = count($i_offices);

							if($i_office_count > 1){
								$multiple_ioffice[] = $project;
							}else if($i_office_count != 0){
								$single_ioffice[$i_offices[0]][] = $project;
							}
							
						}
				?>

						<div class="ibox collapsed myShadow">
							<div class="ibox-title">
								<h5 id="office_names">Multiple Implementing Office</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content">

							<?php
								if(isset($multiple_ioffice)){
								
							?>

								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-3">
											<input type="text" class="form-control form-control-sm" placeholder="Search" id="filter">
											<div class="input-group-append">
												<button class="btn btn-sm btn-primary" type="button">Go!</button>
											</div>
										</div>
									</div>
								</div>
								
								<div class="table-responsive">
									<table class="footable table table-striped toggle-arrow-tiny" data-filter=#filter>
										<thead>
										<tr>

											<th>#</th>
											<th>Reference </th>
											<th>Title </th>
												
												<th data-hide="all">Enduser/s</th>
												<th data-hide="all">Implementing Office</th>
												<th data-hide="all">Current Work</th>
												<th data-hide="all"></th>
												<th data-hide="all">Project Type</th>
												<th data-hide="all">Breakdown</th>
											
											<th>ABC</th>
											<th  style="text-align:center">Priority Level</th>
											<th>Status</th>
											<th>Progress </th>
											<th>Actions </th>
										</tr>
										</thead>
										<tbody>
										<?php																						
											$projects = $multiple_ioffice;
											foreach($projects as $project){
												
												$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);
												if(isset($count)){$count++;}else{$count=1;}
												
												//decode the step details
												$stepDetails = json_decode($project->stepdetails, true);
												$currentWork = $stepDetails[$project->accomplished];

												//change the color of status display
												switch ($project->project_status){
													case 'PROCESSING':
														$status_style = "text-navy";
														$progress_style = "";
														break;
													case 'PAUSED':
														$status_style = "text-warning";
														$progress_style = "yellow-bg";
														break;
												}

												//decode request form origins
												$origins = json_decode($project->request_origin, true);
												$prCounter = 0;
												$joCounter = 0;
												foreach ($origins as $origin){
													$prefix = substr($origin, 0,2);
													if($prefix === "PR"){
														$prCounter++;
													}else if($prefix === "JO"){
														$joCounter++;
													}
												}

												if($project->priority_level === "HIGH"){
													$pl = $project->priority_level;
													$plclass = "text-danger";
												}else{
													$pl ="LOW";
													$plclass = "text-warning";
												}


										?>
										<tr>
											<td><?php echo $count;?></td>
											<td><?php echo $project->project_ref_no;?></td>
											<td class="td-project-title"><?php echo $project->project_title;?></td>
												<td>
													<?php
														// echo $project->end_user;
														$displayEndusers = array();

														$endusersDecoded = json_decode($project->end_user);
															foreach($endusersDecoded as $singleUser){
																$enduserFullname = $user->fullnameOfEnduser($singleUser);
																array_push($displayEndusers, $enduserFullname);
															}

														$finalUsers =  implode(", ", $displayEndusers);
														echo $finalUsers;
													?>
												</td>
												<td><?php echo $implentingOffices = implode(", ",json_decode($project->implementing_office, true));?></td>
												<td><?php echo $currentWork;?></td>
												<td><p style="color:">----------------------------------------------------------------------------------</p></td>
												<td><b style="color:#009bdf"><?php echo strtoupper($project->type);?></b></td>
												<td>
													number of Purchase Request Form:&nbsp&nbsp <b style="color:red"><?php echo $prCounter;?></b><br>
													number of Job Order Form:&nbsp&nbsp <b style="color:red"><?php echo $joCounter;?></b>
												</td>
											<td><?php echo Date::translate($project->ABC, 'php');?></td>
											<td style="text-align:center" class="<?php echo $plclass?>"><b><?php echo $pl;?></b></td>
											<td class="<?php echo $status_style;?>"><b><?php echo $project->project_status;?></b></td>
											<td class="project-completion">
												<small>Completion with: <?php echo $accomplishment;?>%</small>
												<div class="progress progress-mini" style="background-color:#b7bfc7">
													<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar  <?php echo $progress_style;?>"></div>
												</div>
											</td>
											<td><a href="project-details?refno=<?php echo base64_encode($project->project_ref_no);?>" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a></td>
										</tr>
										<?php
											}
										?>
										</tbody>
										<tfoot>
										<tr>
											<td colspan="8">
												<ul class="pagination float-right"></ul>
											</td>
										</tr>
										</tfoot>										
									</table>
																	
								</div>								
								
								<?php
								}else{
									echo '<div style="text-align: center;vertical-align: middle;"><h1>No Data Available</h1></div>';
								}
								?>
								
							</div>
						</div>
						<hr role="tournament5">				

				<?php

						if(isset($single_ioffice)){
					
							$filter_counter = 1;
							asort($single_ioffice);
							foreach ($single_ioffice as $key => $s_ioffice){
								unset($count);
								
				?>

							<div class="ibox collapsed myShadow" style="margin-bottom:12px">
								<div class="ibox-title">
									<h5 style="color:#f99324"><?php echo $key;?></h5>
									<div class="ibox-tools">
										<a class="collapse-link">
											<i class="fa fa-chevron-up"></i>
										</a>
									</div>
								</div>
								<div class="ibox-content">
								
									<div class="row">
										<div class="col-sm-12">
											<div class="input-group mb-3">
												<input type="text" class="form-control form-control-sm" placeholder="Search" id="filter<?php echo $filter_counter;?>">
												<div class="input-group-append">
													<button class="btn btn-sm btn-primary" type="button">Go!</button>
												</div>
											</div>
										</div>
									</div>
									
									<div class="table-responsive">
										<table class="footable table table-striped toggle-arrow-tiny" data-filter=#filter<?php echo $filter_counter;?>>
											<thead>
											<tr>

												<th>#</th>
												<th>Reference </th>
												<th>Title </th>
													
													<th data-hide="all">Enduser/s</th>
													<th data-hide="all">Implementing Office</th>
													<th data-hide="all">Current Work</th>
													<th data-hide="all"></th>
													<th data-hide="all">Project Type</th>
													<th data-hide="all">Breakdown</th>
												
												<th>ABC</th>
												<th  style="text-align:center">Priority Level</th>
												<th>Status</th>
												<th>Progress </th>
												<th>Actions </th>
											</tr>
											</thead>
											<tbody>
											<?php																						
												
												foreach($s_ioffice as $project){
													
													$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);
													if(isset($count)){$count++;}else{$count=1;}
													
													//decode the step details
													$stepDetails = json_decode($project->stepdetails, true);
													$currentWork = $stepDetails[$project->accomplished];

													//change the color of status display
													switch ($project->project_status){
														case 'PROCESSING':
															$status_style = "text-navy";
															$progress_style = "";
															break;
														case 'PAUSED':
															$status_style = "text-warning";
															$progress_style = "yellow-bg";
															break;
													}

													//decode request form origins
													$origins = json_decode($project->request_origin, true);
													$prCounter = 0;
													$joCounter = 0;
													foreach ($origins as $origin){
														$prefix = substr($origin, 0,2);
														if($prefix === "PR"){
															$prCounter++;
														}else if($prefix === "JO"){
															$joCounter++;
														}
													}

													if($project->priority_level === "HIGH"){
														$pl = $project->priority_level;
														$plclass = "text-danger";
													}else{
														$pl ="LOW";
														$plclass = "text-warning";
													}


											?>
											<tr>
												<td><?php echo $count;?></td>
												<td><?php echo $project->project_ref_no;?></td>
												<td class="td-project-title"><?php echo $project->project_title;?></td>
													<td>
														<?php
															// echo $project->end_user;
															$displayEndusers = array();

															$endusersDecoded = json_decode($project->end_user);
																foreach($endusersDecoded as $singleUser){
																	$enduserFullname = $user->fullnameOfEnduser($singleUser);
																	array_push($displayEndusers, $enduserFullname);
																}

															$finalUsers =  implode(", ", $displayEndusers);
															echo $finalUsers;
														?>
													</td>
													<td><?php echo $implentingOffices = implode(", ",json_decode($project->implementing_office, true));?></td>
													<td><?php echo $currentWork;?></td>
													<td><p style="color:">----------------------------------------------------------------------------------</p></td>
													<td><b style="color:#009bdf"><?php echo strtoupper($project->type);?></b></td>
													<td>
														number of Purchase Request Form:&nbsp&nbsp <b style="color:red"><?php echo $prCounter;?></b><br>
														number of Job Order Form:&nbsp&nbsp <b style="color:red"><?php echo $joCounter;?></b>
													</td>
												<td><?php echo Date::translate($project->ABC, 'php');?></td>
												<td style="text-align:center" class="<?php echo $plclass?>"><b><?php echo $pl;?></b></td>
												<td class="<?php echo $status_style;?>"><b><?php echo $project->project_status;?></b></td>
												<td class="project-completion">
													<small>Completion with: <?php echo $accomplishment;?>%</small>
													<div class="progress progress-mini" style="background-color:#b7bfc7">
														<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar  <?php echo $progress_style;?>"></div>
													</div>
												</td>
												<td><a href="project-details?refno=<?php echo base64_encode($project->project_ref_no);?>" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a></td>
											</tr>
											<?php
												}
											?>
											</tbody>
											<tfoot>
											<tr>
												<td colspan="8">
													<ul class="pagination float-right"></ul>
												</td>
											</tr>
											</tfoot>										
										</table>
																		
									</div>									

								
								</div>
							</div>				

				<?php			
							$filter_counter++;
							}

						}

					}else{
						echo '<div style="text-align: center;vertical-align: middle;"><h1>No Data Available</h1></div>';
					}				

				?>


					
				
				</div>

				<div class="modal-footer">
					<button id="" class="btn btn-primary btn-outline">Action</button>
					<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal inmodal fade" id="s_usertag" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-expanded">
			<div class="modal-content animated bounceIn">
				<div class="modal-header">
					<i class="fas fa-user-tag modal-icon" style="color:#F99324"></i>
					<h4 class="modal-title">Users Account based Filtering</h4>
					<small class="font-bold">Shows the list of Ongoing projects sorted based on where endusers' account originated.</small>
				</div>
				<div class="modal-body" style="">

					<?php

						if($projects = $user->ongoing_projects()){

							$multiple_designation = array();
							$single_designation = array();
							
							foreach ($projects as $project){
								$endusers = json_decode($project->end_user, true);
								$endusers_counter = count($endusers);
								

								if($endusers_counter > 1){
									foreach ($endusers as $enduser){
										$enduser_data = $user->get('enduser', array('edr_id', '=', $enduser));
										$testarray[$enduser] = $enduser_data->edr_designated_office;
									}

									$testarray = array_unique($testarray);
									if(count($testarray) > 1){
										$m_index = implode("-",$testarray);
										$multiple_designation[$m_index][] = $project;
									}else if(count($testarray) == 1){
										$single_designation[$testarray[0]] = $project;
									}
									
									unset($testarray);

								}else{
									$enduser_data = $user->get('enduser', array('edr_id', '=', $endusers[0]));
									$single_designation[$enduser_data->edr_designated_office][] = $project;
									
								}
	
								
							}

							if(isset($multiple_designation) OR isset($single_designation)){
								if($multiple_designation){
									foreach ($multiple_designation as $key => $combination){
										unset($count);

										$parts = explode("-", $key);
											foreach($parts as $part){
												if($find_office =  $user->get('units', array('ID', '=', $part))){
													$office_names[$part] =  '<a id="office_names">'.$find_office->office_name.'</a>';
												}
											}
										$last_office = array_pop($office_names);
										$display_combination = implode(", ",$office_names)." and ".$last_office;
										
					?>					
											<div class="ibox collapsed myShadow" style="margin-bottom:12px">
												<div class="ibox-title">
													<h5><?php echo $display_combination;
																	unset($office_names);
													?></h5>
													<div class="ibox-tools">
														<a class="collapse-link">
															<i class="fa fa-chevron-up"></i>
														</a>
													</div>
												</div>
												<div class="ibox-content">
													<div class="row">
														<div class="col-sm-12">
															<div class="input-group mb-3">
																<input type="text" class="form-control form-control-sm" placeholder="Search" id="filter<?php echo $filter_counter;?>">
																<div class="input-group-append">
																	<button class="btn btn-sm btn-primary" type="button">Go!</button>
																</div>
															</div>
														</div>
													</div>
													<div class="table-responsive">
														<table class="footable table table-striped toggle-arrow-tiny" data-filter=#filter<?php echo $filter_counter;?>>
															<thead>
															<tr>

																<th>#</th>
																<th>Reference </th>
																<th>Title </th>
																	
																	<th data-hide="all">Enduser/s</th>
																	<th data-hide="all">Implementing Office</th>
																	<th data-hide="all">Current Work</th>
																	<th data-hide="all"></th>
																	<th data-hide="all">Project Type</th>
																	<th data-hide="all">Breakdown</th>
																
																<th>ABC</th>
																<th  style="text-align:center">Priority Level</th>
																<th>Status</th>
																<th>Progress </th>
																<th>Actions </th>
															</tr>
															</thead>
															<tbody>
															<?php																						
																
																foreach($combination as $project){
																	
																	$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);
																	if(isset($count)){$count++;}else{$count=1;}
																	
																	//decode the step details
																	$stepDetails = json_decode($project->stepdetails, true);
																	$currentWork = $stepDetails[$project->accomplished];

																	//change the color of status display
																	switch ($project->project_status){
																		case 'PROCESSING':
																			$status_style = "text-navy";
																			$progress_style = "";
																			break;
																		case 'PAUSED':
																			$status_style = "text-warning";
																			$progress_style = "yellow-bg";
																			break;
																	}

																	//decode request form origins
																	$origins = json_decode($project->request_origin, true);
																	$prCounter = 0;
																	$joCounter = 0;
																	foreach ($origins as $origin){
																		$prefix = substr($origin, 0,2);
																		if($prefix === "PR"){
																			$prCounter++;
																		}else if($prefix === "JO"){
																			$joCounter++;
																		}
																	}

																	if($project->priority_level === "HIGH"){
																		$pl = $project->priority_level;
																		$plclass = "text-danger";
																	}else{
																		$pl ="LOW";
																		$plclass = "text-warning";
																	}


															?>
															<tr>
																<td><?php echo $count;?></td>
																<td><?php echo $project->project_ref_no;?></td>
																<td class="td-project-title"><?php echo $project->project_title;?></td>
																	<td>
																		<?php
																			// echo $project->end_user;
																			$displayEndusers = array();

																			$endusersDecoded = json_decode($project->end_user);
																				foreach($endusersDecoded as $singleUser){
																					$enduserFullname = $user->fullnameOfEnduser($singleUser);
																					array_push($displayEndusers, $enduserFullname);
																				}

																			$finalUsers =  implode(", ", $displayEndusers);
																			echo $finalUsers;
																		?>
																	</td>
																	<td><?php echo $implentingOffices = implode(", ",json_decode($project->implementing_office, true));?></td>
																	<td><?php echo $currentWork;?></td>
																	<td><p style="color:">----------------------------------------------------------------------------------</p></td>
																	<td><b style="color:#009bdf"><?php echo strtoupper($project->type);?></b></td>
																	<td>
																		number of Purchase Request Form:&nbsp&nbsp <b style="color:red"><?php echo $prCounter;?></b><br>
																		number of Job Order Form:&nbsp&nbsp <b style="color:red"><?php echo $joCounter;?></b>
																	</td>
																<td><?php echo Date::translate($project->ABC, 'php');?></td>
																<td style="text-align:center" class="<?php echo $plclass?>"><b><?php echo $pl;?></b></td>
																<td class="<?php echo $status_style;?>"><b><?php echo $project->project_status;?></b></td>
																<td class="project-completion">
																	<small>Completion with: <?php echo $accomplishment;?>%</small>
																	<div class="progress progress-mini" style="background-color:#b7bfc7">
																		<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar  <?php echo $progress_style;?>"></div>
																	</div>
																</td>
																<td><a href="project-details?refno=<?php echo base64_encode($project->project_ref_no);?>" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a></td>
															</tr>
															<?php
																}
															?>
															</tbody>
															<tfoot>
															<tr>
																<td colspan="8">
																	<ul class="pagination float-right"></ul>
																</td>
															</tr>
															</tfoot>										
														</table>
																						
													</div>													
												</div>
											</div>
					<?php
									$filter_counter++;
									}
									
									echo '<hr role="tournament5">';
								}

								if($single_designation){
									foreach ($single_designation as $key => $designation){
										unset($count);
										$key_display = $user->get('units', array('ID', '=', $key));
					?>
										<div class="ibox collapsed myShadow" style="margin-bottom:12px">
											<div class="ibox-title">
												<h5 style="color:#f99324"><?php echo $key_display->office_name;?></h5>
												<div class="ibox-tools">
													<a class="collapse-link">
														<i class="fa fa-chevron-up"></i>
													</a>
												</div>
											</div>
											<div class="ibox-content">
												<div class="row">
													<div class="col-sm-12">
														<div class="input-group mb-3">
															<input type="text" class="form-control form-control-sm" placeholder="Search" id="filter<?php echo $filter_counter;?>">
															<div class="input-group-append">
																<button class="btn btn-sm btn-primary" type="button">Go!</button>
															</div>
														</div>
													</div>
												</div>
												<div class="table-responsive">
													<table class="footable table table-striped toggle-arrow-tiny" data-filter=#filter<?php echo $filter_counter;?>>
														<thead>
														<tr>

															<th>#</th>
															<th>Reference </th>
															<th>Title </th>
																
																<th data-hide="all">Enduser/s</th>
																<th data-hide="all">Implementing Office</th>
																<th data-hide="all">Current Work</th>
																<th data-hide="all"></th>
																<th data-hide="all">Project Type</th>
																<th data-hide="all">Breakdown</th>
															
															<th>ABC</th>
															<th  style="text-align:center">Priority Level</th>
															<th>Status</th>
															<th>Progress </th>
															<th>Actions </th>
														</tr>
														</thead>
														<tbody>
														<?php																						
															
															foreach($designation as $project){
																
																$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);
																if(isset($count)){$count++;}else{$count=1;}
																
																//decode the step details
																$stepDetails = json_decode($project->stepdetails, true);
																$currentWork = $stepDetails[$project->accomplished];

																//change the color of status display
																switch ($project->project_status){
																	case 'PROCESSING':
																		$status_style = "text-navy";
																		$progress_style = "";
																		break;
																	case 'PAUSED':
																		$status_style = "text-warning";
																		$progress_style = "yellow-bg";
																		break;
																}

																//decode request form origins
																$origins = json_decode($project->request_origin, true);
																$prCounter = 0;
																$joCounter = 0;
																foreach ($origins as $origin){
																	$prefix = substr($origin, 0,2);
																	if($prefix === "PR"){
																		$prCounter++;
																	}else if($prefix === "JO"){
																		$joCounter++;
																	}
																}

																if($project->priority_level === "HIGH"){
																	$pl = $project->priority_level;
																	$plclass = "text-danger";
																}else{
																	$pl ="LOW";
																	$plclass = "text-warning";
																}


														?>
														<tr>
															<td><?php echo $count;?></td>
															<td><?php echo $project->project_ref_no;?></td>
															<td class="td-project-title"><?php echo $project->project_title;?></td>
																<td>
																	<?php
																		// echo $project->end_user;
																		$displayEndusers = array();

																		$endusersDecoded = json_decode($project->end_user);
																			foreach($endusersDecoded as $singleUser){
																				$enduserFullname = $user->fullnameOfEnduser($singleUser);
																				array_push($displayEndusers, $enduserFullname);
																			}

																		$finalUsers =  implode(", ", $displayEndusers);
																		echo $finalUsers;
																	?>
																</td>
																<td><?php echo $implentingOffices = implode(", ",json_decode($project->implementing_office, true));?></td>
																<td><?php echo $currentWork;?></td>
																<td><p style="color:">----------------------------------------------------------------------------------</p></td>
																<td><b style="color:#009bdf"><?php echo strtoupper($project->type);?></b></td>
																<td>
																	number of Purchase Request Form:&nbsp&nbsp <b style="color:red"><?php echo $prCounter;?></b><br>
																	number of Job Order Form:&nbsp&nbsp <b style="color:red"><?php echo $joCounter;?></b>
																</td>
															<td><?php echo Date::translate($project->ABC, 'php');?></td>
															<td style="text-align:center" class="<?php echo $plclass?>"><b><?php echo $pl;?></b></td>
															<td class="<?php echo $status_style;?>"><b><?php echo $project->project_status;?></b></td>
															<td class="project-completion">
																<small>Completion with: <?php echo $accomplishment;?>%</small>
																<div class="progress progress-mini" style="background-color:#b7bfc7">
																	<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar  <?php echo $progress_style;?>"></div>
																</div>
															</td>
															<td><a href="project-details?refno=<?php echo base64_encode($project->project_ref_no);?>" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a></td>
														</tr>
														<?php
															}
														?>
														</tbody>
														<tfoot>
														<tr>
															<td colspan="8">
																<ul class="pagination float-right"></ul>
															</td>
														</tr>
														</tfoot>										
													</table>

												</div>
												
											</div>
										</div>									
					<?php			
									$filter_counter++;			
									}
								}



							}else{
								echo '<div style="text-align: center;vertical-align: middle;"><h1>No Data Available</h1></div>';
							}


						}else{
							echo '<div style="text-align: center;vertical-align: middle;"><h1>No Data Available</h1></div>';
						}
					
					?>
				
			


					
				
				</div>

				<div class="modal-footer">
					<button id="" class="btn btn-primary btn-outline">Action</button>
					<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

    <?php include '../../includes/parts/admin_scripts.php'; ?>
	
	<!-- Report Scripts -->
	
	<!-- Morris -->
    <script src="../../assets/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="../../assets/js/plugins/morris/morris.js"></script>
    <!-- ChartJS-->
    <script src="../../assets/js/plugins/chartJs/Chart.min.js"></script>
   
	
	<!-- Data for pie -->
    <script>
		$(function() {

			$('[data-date="month"]').on('change', function(){
				let value = this.value;
				SendDoSomething('GET', 'xhr-check-monthly-report.php', {
					id: value
				}, {
					do: function(d){
						if(d.check){
							$('[data-btn="dl"]').attr('href', `../../bac/forms/report?m=${value}`);
						}else{
							swal({
								title: 'There are no existing projects for this month!',
								text: '',
								type: 'info'
							});
						}
					}
				});
			});


			<?php
					// Overall Projects Classification This year
					if($projects = $reports['all_projects_thisyear']){

						$prCounter = 0;
						$joCounter = 0;
						$mixedCounter = 0;						
					
						foreach($projects as $project){
							
							$origin = json_decode($project->request_origin, true);

							switch ($project->type) {
								case 'single':
									if(substr($origin[0], 0, 2) === "PR"){
										$prCounter++;
										$prArray[] = $project;
									}else{
										$joCounter++;
										$joArray[] = $project;
									}
									break;
								
								case 'consolidated':
									$mixedCounter ++;
									$mixedArray[] = $project;
									break;
							}
						}
					}

			?>
			
			Morris.Donut({
				element: 'morris-donut-chart',
				data: [
					{ label: "Purchase Request", value: <?php echo $prCounter;?> },
					{ label: "Job Orders", value: <?php echo $joCounter;?> },
					{ label: "Mixed or Consolidated", value: <?php echo $mixedCounter;?> } 
					],
				resize: true,
				colors: ['#f8ac59', '#1B6AA5','#b6325e'],
			});		
			
		});
	</script>
	
	
	<!--Mini Donut data-->
    <script>
        $(document).ready(function(){

			var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };

			// count available reports
			
			<?php
						if(isset($reports["current_projects_breakdown"])){
							$breakdown_count = count($reports["current_projects_breakdown"]);
							$x = 1;

							foreach ($reports["current_projects_breakdown"] as $key => $value) {
								
								$processing = 0;
								$finished = 0;
								$failed = 0;

								// loop through individual values and identify their status
								switch ($key) {
									case 'PR':

										foreach ($value as $individualProject) {
											
											switch ($individualProject->project_status) {
												case 'PROCESSING':
													$processing++;
													break;
												case 'PAUSED':
													$processing++;
													break;
												case 'FINISHED':
													$finished++;
													break;
												case 'FAILED':
													$failed++;
													break;	
											}
										}
										echo 'document.getElementById("doughnutLabel'.$x.'").innerHTML = "Purchase Request";';
										break;
									case 'JO':
										foreach ($value as $individualProject) {
											
											switch ($individualProject->project_status) {
												case 'PROCESSING':
													$processing++;
													break;
												case 'PAUSED':
													$processing++;
													break;
												case 'FINISHED':
													$finished++;
													break;
												case 'FAILED':
													$failed++;
													break;	
											}
										}									
										echo 'document.getElementById("doughnutLabel'.$x.'").innerHTML = "Job Order";';
										break;
									case 'MIXED':
										foreach ($value as $individualProject) {
											
											switch ($individualProject->project_status) {
												case 'PROCESSING':
													$processing++;
													break;
												case 'PAUSED':
													$processing++;
													break;
												case 'FINISHED':
													$finished++;
													break;
												case 'FAILED':
													$failed++;
													break;	
											}
										}									
										echo 'document.getElementById("doughnutLabel'.$x.'").innerHTML = "Mixed";';
										break;
								}
								echo'
								var doughnutData = {
									labels: ["Ongoing","Finished","Failed"],
									datasets: [{
										data: ['.$processing.','.$finished.','.$failed.'],
										backgroundColor: ["#65c7d0","#8CC63E","#ea3c14"]
									}]
								};
								var ctx4 = document.getElementById("doughnutChart'.$x.'").getContext("2d");
								new Chart(ctx4, {type: "doughnut", data: doughnutData, options:doughnutOptions});
							';

								$x++;
							}

							unset($processing);
							unset($paused);
							unset($finished);
							unset($failed);

						}
			?>			

        });
    </script>	
	
	<!-- Data for bar and line graph -->
	<script>
		$(function (){


			<?php

					$months = $user->success_ratio();

					foreach ($months as $month) {
						$registered[] = $month["registered"];
						$finished[] = $month["finished"];
						$failed[] = $month["failed"];
					}
					
			?>



			var barData = {
				labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
				datasets: [
					{
						label: "Registered Projects",
						borderColor: "rgb(0, 0, 0)",
						backgroundColor: '#65c7d0',
						pointBorderColor: "#fff",
						data: [<?php echo implode(", ",$registered);?>]
					},
					{
						label: "Projects Awarded",
						backgroundColor: '#8CC63E',
						borderColor: "rgb(0, 0, 0)",
						pointBackgroundColor: "rgba(26,179,148,1)",
						pointBorderColor: "#fff",
						data: [<?php echo implode(", ",$finished);?>]
					},
					{
						label: "Projects Failed",
						backgroundColor: '#ea3c14',
						borderColor: "rgb(0, 0, 0)",
						pointBackgroundColor: "rgba(26,179,148,1)",
						pointBorderColor: "#fff",
						data: [<?php echo implode(", ",$failed);?>]
					}
				]
			};

			var barOptions = {
				responsive: true
			};


			var ctx2 = document.getElementById("barChart").getContext("2d");
			new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});


		});	
	</script>
<script>
	$(function(){
		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1400);
	});
</script>

</body>

</html>
