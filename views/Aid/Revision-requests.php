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

    <title>PrMO OPPTS | Revision Requests</title>

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
                    <h2>Revision Requests</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Revision Requests</strong>
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
			
				<?php
					if(!isset($_GET['q'])){
						
						$allUpdateRequests = $user->selectAll('form_update_requests');

						echo "<pre>",print_r($allUpdateRequests),"</pre>";
					
				?>
				<div class="row">
					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Received Purchase requests and Job Order forms</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content">
							
							
								<div class="table-responsive">
									<input type="text" class="form-control form-control-sm m-b-xs" id="filter" placeholder="Search in table">
									<table class="footable table table-stripped toggle-arrow-tiny" data-filter=#filter>
									
										<thead>
											<tr>

												<th data-toggle="true">#</th>
												<th>Requested by</th>
												<th>Reference No.</th>
												<th>Date Requested</th>
													<th data-hide="all">Current Workflow</th>
													<th data-hide="all">Completion</th>
													<th data-hide="all">Phone</th>
													<th data-hide="all">Phase</th>
													<th data-hide="all">Account Status</th>
												<th style="text-align:center">Action</th>
											</tr>
										</thead>
										
										<tbody>
										
											<?php
												foreach($allUpdateRequests as $request){
													if(isset($count)){$count++;}else{$count=1;}
														$projectDetail = $user->like('projects', 'request_origin', $request->form_origin);
														
													if($projectDetail){
														$displayProject = '<a style="color:#F37123">"'.$projectDetail->project_title.'"</a>';
														$workflow = $projectDetail->workflow;
														$accomplishment = number_format(($projectDetail->accomplished / $projectDetail->steps) * 100, 1);
														$completion = '
																<small>'.$accomplishment.'%</small>
																<div class="progress progress-mini">
																	<div style="width:'.$accomplishment.'%;" class="progress-bar"></div>
																</div>';
														
													}else{
														$displayProject = '<a style="color:black">Received but Unregistered Project</a>';
														$workflow = 'NA';
														$completion = 'NA';
													}
													
													
											?>
								                                      
													<tr>
														<td><?php echo $count;?></td>
														<td><?php echo $user->fullnameOfEnduser($request->requested_by);?></td>
														<td style="color:#009bdf"><?php echo $request->form_origin, " - ", $displayProject; ?></td>
														<td><?php echo Date::translate($request->date_registered, 1);?></td>
															<td><?php echo $workflow;?></td>
															<td><?php echo $completion;?></td>
															<td>'.$data->phone.'</td>
															<td>'.$data->prnl_assigned_phase.'</td>
															<td><b><a  class="'.$color.'">'.$data->status.'</a></b></td>
														<td style="text-align:center">
															<a href="#" class="btn btn-info btn-outline btn-sm" style="color:black"><i class="ti-layers-alt"></i> view changes </a>								
														</td>
													</tr>   
											<?php
												}
											?>
													
												
												
										</tbody>
										
										<tfoot>
											<tr>
												<td colspan="5">
													<ul class="pagination float-right"></ul>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>							

							</div>
							<div class="ibox-footer">
								<span class="float-right">
								The righ side of the footer
								</span>
								This is simple footer example
							</div>
						</div>
					</div>
				</div>
				<?php
				}else{
				?>
				<div class="row">
					<div class="col-lg-12 animated fadeInRight">
						<h1>Test</h1>
					</div>
				</div>
				<?php
				}
				?>
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
