<?php

    require_once('../../core/init.php');

    $user = new Admin(); 

    
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }


?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Ongoing Projects</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include_once'../../includes/parts/admin_styles.php'; ?>

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

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Ongoing Projects</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Ongoing Projects</strong>
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
            <div class="wrapper wrapper-content animated fadeInUp">
				<div class="row">

					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Complete Ongoing Projects</h5>
							</div>
							<div class="ibox-content">
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
											$user = new Admin();
											
											
											$projects = $user->ongoing_projects();
											
											if($projects){
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


										?>
										<tr>
											<td><?php echo $count;?></td>
											<td><?php echo $project->project_ref_no;?></td>
											<td class="td-project-title"><?php echo $project->project_title;?></td>
												<td>
													<?php
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
											<td style="text-align:center">HIGH</td>
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
											}else{
												echo '<td colspan="8" style="text-align:center">No Data Available</td>';
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

    <?php include '../../includes/parts/admin_scripts.php'; ?>

</body>

</html>
