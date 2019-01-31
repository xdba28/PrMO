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

    <title>PrMO OPPTS | Procurement Aid</title>

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
				
					<div class="col-lg-12 animated fadeInLeft">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>High Priority Projects</h5>
							</div>
							<div class="ibox-content">
								<div class="row">

									<div class="col-sm-12">
										<div class="input-group mb-3">
											<input type="text" class="form-control form-control-sm" placeholder="Search" id="filter0">
											<div class="input-group-append">
												<button class="btn btn-sm btn-primary" type="button">Go!</button>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table class="footable table table-striped toggle-arrow-tiny" data-filter="#filter0" style="border-collapse: collapse;border-radius: 10px;overflow: hidden;width: 100%;margin: 0 auto;position: relative;">
										<thead>
										<tr style="background-color:#c21749">

											<th style="color:white">#</th>
											<th style="color:white">Reference </th>
											<th style="color:white">Title </th>
												
												<th data-hide="all">Enduser/s</th>
												<th data-hide="all">Current Work</th>
											
											<th style="color:white">Implementation in</th>
											<th style="color:white">Status</th>
											<th style="color:white">Progress </th>
											<th style="color:white">Actions </th>
										</tr>
										</thead>
										<tbody>
										<?php
											$user = new Admin();
											
											$projects = $user->ongoing_projects();
											$leftBorder = "hprow1";
											$hpCount = false;

											if($projects){
											foreach($projects as $project){
												
												if($project->priority_level === "HIGH"){
													
													$hpCount = true;
													$leftBorder = ($leftBorder == "hprow" ? $leftBorder = "hprow1" : $leftBorder = "hprow");
													
													$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);
													if(isset($count)){$count++;}else{$count=1;}
													
													//decode the step details
													$stepDetails = json_decode($project->stepdetails, true);
													$currentWork = $stepDetails[$project->accomplished];

													if($project->project_status == "PAUSED"){
														$bg = "label-warning";
														$progress = "yellow-bg";
													}else{
														$bg = "label-info";
														$progress = "lazur-bg";
													}


										?>
										<tr class="<?php echo $leftBorder;?>">
											<td><?php echo $count;?></td>
											<td><?php echo $project->project_ref_no;?></td>
											<td class="td-project-title"><?php echo $project->project_title;?></td>
											<td>
											<?php 

												$decodedEndusers = json_decode($project->end_user, true);
												$namesArray = array();

													foreach ($decodedEndusers as $empID) {
														array_push($namesArray, $user->fullnameOfEnduser($empID));
														
													}
												$enduserNames =  implode(", ", $namesArray);
												echo $enduserNames;
													
											?></td>
											<td><?php echo $currentWork;?></td>
											<td><?php echo Date::translate($project->ABC, 'php');?></td>
											<td>
												<span class="label <?php echo $bg;?>"><?php echo $project->project_status;?></span>
																							
											</td>
											<td class="project-completion">
												<small>Completion with: <?php echo $accomplishment;?>%</small>
												<div class="progress progress-mini" style="background-color:#b7bfc7">
													<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar <?php echo $progress;?>"></div>
												</div>
											</td>
											<td>
												<a href="project-details?refno=<?php echo base64_encode($project->project_ref_no);?>" class="btn btn-default btn-outline" style="border:1px solid #8c8c8d; color:black">View Details</a>
												<button class="btn btn-default btn-outline"  style="border:1px solid #8c8c8d; color:black" data-toggle="modal" data-target="#actionsModal" data-reference="<?php echo $project->project_ref_no;?>">
													<i class="fas fa-project-diagram"></i> View Options
												</button>
											</td>
										</tr>
										<?php
												}
											}
										}
											
											if(!$hpCount){
												echo '<tr>
														<td colspan="7" style="text-align:center;color:#c21749">No projects on high priority</td>
													</tr>';
											}
											unset($count);
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

					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Ongoing Projects</h5>
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
									<table class="footable table table-striped toggle-arrow-tiny" data-filter="#filter">
										<thead>
										<tr>

											<th>#</th>
											<th>Reference </th>
											<th>Title </th>
												
												<th data-hide="all">Enduser/s</th>
												<th data-hide="all">Current Work</th>
											
											<th>ABC</th>
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
												
												if($project->priority_level !== "HIGH"){
													
													$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);
													if(isset($count)){$count++;}else{$count=1;}
													
													//decode the step details
													$stepDetails = json_decode($project->stepdetails, true);
													$currentWork = $stepDetails[$project->accomplished];

													if($project->project_status == "PAUSED"){
														$bg = "label-warning";
														$progress = "yellow-bg";
													}else{
														$bg = "label-info";
														$progress = "lazur-bg";
													}


										?>
										<tr>
											<td><?php echo $count;?></td>
											<td><?php echo $project->project_ref_no;?></td>
											<td class="td-project-title"><?php echo $project->project_title;?></td>
											<td>
											<?php 

												$decodedEndusers = json_decode($project->end_user, true);
												$namesArray = array();

													foreach ($decodedEndusers as $empID) {
														array_push($namesArray, $user->fullnameOfEnduser($empID));
														
													}
												$enduserNames =  implode(", ", $namesArray);
												echo $enduserNames;
													
											?></td>
											<td><?php echo $currentWork;?></td>
											<td><?php echo Date::translate($project->ABC, 'php');?></td>
											<td>
												<span class="label <?php echo $bg;?>"><?php echo $project->project_status;?></span>
																							
											</td>
											<td class="project-completion">
												<small>Completion with: <?php echo $accomplishment;?>%</small>
												<div class="progress progress-mini" style="background-color:#b7bfc7">
													<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar <?php echo $progress;?>"></div>
												</div>
											</td>
											<td>
												<a href="project-details?refno=<?php echo base64_encode($project->project_ref_no);?>" class="btn btn-default btn-outline" style="border:1px solid #8c8c8d; color:black">View Details</a>
												<button class="btn btn-default btn-outline"  style="border:1px solid #8c8c8d; color:black" data-toggle="modal" data-target="#actionsModal" data-reference="<?php echo $project->project_ref_no;?>">
													<i class="fas fa-project-diagram"></i> View Options
												</button>
											</td>
										</tr>
										<?php
												}
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
<script>
	$(function(){
		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1000);
	});
</script>
</html>
