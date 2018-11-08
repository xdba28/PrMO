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
		<div class="wrapper wrapper-content animated fadeInUp">
			<div class="row">
				<div class="col-lg-12 animated fadeInRight">

						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>All projects entitled to this account</h5>
							</div>
							<div class="ibox-content">
								<div class="row m-b-sm m-t-sm">
									<div class="col-md-1">
										<button type="button" id="loading-example-btn" class="btn btn-white btn-sm" onclick="window.location.href='current-projects'"><i class="fa fa-refresh"></i> Refresh</button>
									</div>
									<div class="col-md-11">
										<div class="input-group"><input type="text" placeholder="Search" class="form-control-sm form-control" id="filter"> <span class="input-group-btn">
											<button type="button" class="btn btn-sm btn-primary"> Go!</button> </span></div>
									</div>
								</div>

								<div class="project-list">

									<table class="table footable table-hover" data-filter=#filter>
										<tbody>
										<?php
											$user = new User();
											$current_user = Session::get(Config::get('session/session_name'));
											$myRequests = $user->like('projects', 'end_user', "%{$current_user}%");

											foreach($myRequests as $request){

												$accomplishment = number_format(($request->accomplished / $request->steps) * 100, 1);

										?>
										<tr>
											<td class="project-title">
												<a><?php echo $request->project_ref_no;?></a>
												<br/>
												<small>Registered <?php echo $request->date_registered;?></small>
											</td>

											<td style="max-width: 220px"><?php echo $request->project_title;?></td>

											<td class="project-status">
												<span class="label label-primary"><?php echo $request->project_status;?></span>
												<br/>
												<small><?php echo $request->type;?> project</small>
											</td>

											<td class="project-completion">
												<small>Completion with: <?php echo $accomplishment;?>%</small>
												<div class="progress progress-mini">
													<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar"></div>
												</div>
											</td>

											<td class="project-actions">
												<a href="project-details?refno=<?php echo$request->project_ref_no;?>" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a>
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
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once'../../includes/parts/user_scripts.php'; ?>

</body>

</html>
