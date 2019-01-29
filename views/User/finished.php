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
	
	<link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">

    <title>PrMO OPPTS | Finished Projects</title>

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
                    <h2>Finished Projects</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Finished / Failed Projects</strong>
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
								<h5>All Finished / Failed projects entitled to this account</h5>
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
										<thead>
											<tr>
												<th>Reference</th>
												<th>Title</th>
												<th>Status</th>
												<th>Declaration</th>
												<th>Progress</th>
												<th style="text-align:center">Actions</th>
											</tr>
										</thead>										
										<tbody>
										<?php
											$user = new User();
											$current_user = Session::get(Config::get('session/session_name'));
											$myRequests = $user->like('projects', 'end_user', "%{$current_user}%");											
											
											if($myRequests){
												
											

											foreach($myRequests as $request){

												if(($request->project_status == "FINISHED") OR ($request->project_status == "FAILED")){

													$logs = $user->getAll("project_logs", array("referencing_to", "=", $request->project_ref_no));
													// echo "<pre>",print_r($logs),"</pre>";
							

													if($request->project_status == "FINISHED"){

														foreach ($logs as $log) {
															$parts = explode("^", $log->remarks);
															if(($parts[0]=="DECLARATION") AND ($parts[1] == "FINISH")){
																$declarationDate = $log->logdate;
															}
														}

														$bg = "label-warning";
														$progress = "yellow-bg";
													}else{

														foreach ($logs as $log) {
															$parts = explode("^", $log->remarks);
															if(($parts[0]=="DECLARATION") AND ($parts[1] == "FAILURE")){
																$declarationDate = $log->logdate;
															}
														}

														$bg = "label-info";
														$progress = "lazur-bg";
													}

													
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
													<span class="label <?php echo $bg;?>"><?php echo $request->project_status;?></span>
													<br/>
													<small><?php echo $request->type;?> project</small>
												</td>

												<td class="">
													<?php echo Date::translate($declarationDate, 1);?>
												</td>												

												<td class="project-completion">
													<small>Completion with: <?php echo $accomplishment;?>%</small>
													<div class="progress progress-mini" style="background-color:#b7bfc7">
														<div style="width: <?php echo $accomplishment;?>%;" class="progress-bar <?php echo $progress;?>"></div>
													</div>
												</td>

												<td class="project-actions" >
													<a href="project-details?refno=<?php echo base64_encode($request->project_ref_no);?>" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a>
												</td>
											</tr>

										<?php
											}
										}
										

											}else{
												
												echo '
													<tr>
														<td colspan="5" style="text-align:center">No Data Available</td>
													</tr>
												';
												
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
