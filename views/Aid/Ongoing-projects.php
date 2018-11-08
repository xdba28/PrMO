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
            <div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">

					<div class="col-lg-12">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Complete Ongoing Projects</h5>
							</div>
							<div class="ibox-content">
								<div class="row">
									<div class="col-sm-9 m-b-xs">
										<div data-toggle="buttons" class="btn-group btn-group-toggle">
											<label class="btn btn-sm btn-white"> <input type="radio" id="option1" name="options"> Day </label>
											<label class="btn btn-sm btn-white"> <input type="radio" id="option2" name="options"> Week </label>
											<label class="btn btn-sm btn-white"> <input type="radio" id="option3" name="options"> Month </label>
										</div>
									</div>
									<div class="col-sm-3">
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
												<th data-hide="all">Current Work</th>
											
											<th>ABC</th>
											<th>Progress </th>
											<th>Actions </th>
										</tr>
										</thead>
										<tbody>
										<?php
											$user = new Admin();
											
											$projects = $user->getAll('projects', array('project_status', '=', 'processing'));
											foreach($projects as $project){
												
												$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);
												if(isset($count)){$count++;}else{$count=1;}
												
												//decode the step details
												$stepDetails = json_decode($project->stepdetails, true);
												$currentWork = $stepDetails[$project->accomplished];


										?>
										<tr>
											<td><?php echo $count;?></td>
											<td><?php echo $project->project_ref_no;?></td>
											<td class="td-project-title"><?php echo $project->project_title;?></td>
											<td><?php echo $project->end_user;?></td>
											<td><?php echo $currentWork;?></td>
											<td><?php echo $project->ABC;?></td>
											<td class="project-completion">
												<small>Completion with: <?php echo $accomplishment;?>%</small>
												<div class="progress progress-mini">
													<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar"></div>
												</div>
											</td>
											<td><button class="btn btn-success btn-outline" type="button" data-toggle="modal" data-target="#actionsModal" data-reference="<?php echo $project->project_ref_no;?>"><i class="fas fa-project-diagram"></i> View Options</button></td>
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
