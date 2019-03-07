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

    <title>PrMO OPPTS | Overview</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include "../../includes/parts/admin_styles.php"?>




</head>

<body class="fixed-navigation">
    <div id="wrapper">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/superadmin_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>End-user Accounts Overview</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">User Accounts</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>End Users Overview</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                       <a href="Dashboard" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>
            </div>

			<div class="wrapper wrapper-content animated fadeInUp">
				
				
				<?php
					if(!isset($_GET['q'])){
				?>
				<div class="row">
					<div class="col-lg-12  animated fadeInRight">
					
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>College and Individual offices Level</h5>

							</div>
							<div class="ibox-content">
								<div class="row m-b-sm m-t-sm">
									<div class="col-md-1">
										<button type="button" id="loading-example-btn" class="btn btn-white btn-sm" ><i class="fa fa-refresh"></i> Refresh</button>
									</div>
									<div class="col-md-11">
										<div class="input-group"><input type="text" placeholder="Search" class="form-control-sm form-control" id="filter"> <span class="input-group-btn">
											<button type="button" class="btn btn-sm btn-primary"> Go!</button> </span></div>
									</div>
								</div>

								<div class="project-list custom-project-list">

									<table class="table footable table-hover" data-filter=#filter>
										<tbody>
										
										<?php
											$user =  new Super_admin();
											$userOverview = $user->userOverview();
											
											foreach($userOverview as $data){
												
												$partition = number_format(($data->registered_users/$data->overall_users)*100, 2);
											
										?>
											<tr>
												<td class="project-status">
													<span class="label label-warning"><?php echo $data->acronym;?></span>
												</td>
												<td class="project-title">
													<a><?php echo $data->office_name;?></a>
													<br/>
													<small><?php echo $data->campus;?> Campus</small>
												</td>
												<td class="project-title" style="text-align:left">
													<a href=""><?php echo $data->registered_users;?></a>
													<small>Active Users</small>
												</td>
												<td class="project-completion">
														<small><?php echo $partition;?>% of overall users</small>
														<div class="progress progress-mini">
															<div style="width: <?php echo $partition;?>%;" class="progress-bar"></div>
														</div>
												</td>
		
												<td class="project-actions">
													<a href="?unit=<?php echo $data->office_name;?>&q=<?php echo $data->ID;?>" class="btn btn-white btn-sm"><i class="far fa-folder-open"></i> Users Info</a>
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
				<?php
				}else{
				?>		
				

		
            <div class="row">
                <div class="col-lg-12  animated fadeInRight">
                <div class="ibox myShadow">
                    <div class="ibox-title">
                        <h5>Showing All Registered Accounts from <a style="color:#3399FF"><?php echo Input::get('	');?></a></h5>
                        <div class="ibox-tools">
							<a href ="overview" class="btn btn-info btn-rounded btn-outline btn-xs" style="color:black"><i class="ti-angle-double-left"></i> Back to Overview</a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                    <table id="DataTables_userOverview" class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Owner</th>
                        <th>Username</th>
						<th>Password</th>
						<th>Office</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
						<?php

							$user = new Super_admin();
							
							$unitAccounts =  $user->unitUsers(Input::get('q'));
							
							foreach ($unitAccounts as $account) {
								if(isset($count)){$count++;}else{$count=1;}
								// echo "<pre>",print_r($account),"</pre>";
								$enduser = $user->get('enduser', array('edr_id', '=', $account->account_id));
						?>
							<tr class="gradeX">
								<td><?php echo $count;?></td>
								<td><?php echo $user->fullnameOfEnduser($account->account_id);?></td>
								<td><?php echo $account->username; ?></td>
								<td class=""><?php echo $account->userpassword; ?></td>
								<td class="center"><?php echo $account->current_specific_office; ?></td>
								<td class="center">
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-warning btn-xs dropdown-toggle">Options </button>
										<ul class="dropdown-menu">
											<li><a class="dropdown-item" data-toggle="modal" data-phone="<?php echo $enduser->phone;?>" data-target="#resetPassword" data-id="<?php echo $account->account_id;?>" data-office="<?php echo Input::get('unit'), " - ", $account->current_specific_office; ?>" data-name="<?php echo $user->fullnameOfEnduser($account->account_id);?>">Reset Password</a></li>
											<li class="dropdown-divider"></li>
											<li><a class="dropdown-item" href="#">other option</a></li>
										</ul>
									</div>										
								</td>
							</tr>
						<?php
							}
						?>
					
                    </tfoot>
                    </table>
                        </div>

                    </div>
                </div>
            </div>
            </div>
		
    
							
				<?php
					}
				?>					
				
            </div>
			<div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
			</div>

        </div>

    </div>
	<?php include_once '../../includes/parts/modals.php'; ?>
    <?php include_once '../../includes/parts/admin_scripts.php'; ?>


</body>
<script>
	if(error !== ""){
		swal({
			title: "An error occurred!",
			text: error,
			confirmButtonColor: "#DD6B55",
			type: 'error',
			timer: 13000
		});
	}
</script>
</html>
