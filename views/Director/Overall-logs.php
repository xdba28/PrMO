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

    <title>PrMO OPPTS | Overall Logs</title>
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

        <div id="page-wrapper" class="gray-bg" style="background-color:#e7e7ec">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Overall Logs</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Logs & References</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Overall Logs</strong>
                        </li>
                    </ol>
                </div>

            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content animated fadeInUp">
			
		
            <div class="row">
                <div class="col-lg-12 animated fadeInRight">
                <div class="ibox myShadow">
                    <div class="ibox-title">
                        <h5>Overall Procurement Logs</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#" class="dropdown-item">Config option 1</a>
                                </li>
                                <li><a href="#" class="dropdown-item">Config option 2</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
							<table id="DataTables_overallLogs" class="table table-striped table-bordered table-hover dataTables-example" >
							<h4>Showing logs from most recent to oldest.</h4>
							<thead>
								<tr>
									<th>#</th>
									<th>Log ID</th>
									<th>Refering to</th>
									<th>Log Remark</th>
									<th>Type</th>
									<th>Log Date</th>
									
								</tr>
							</thead>
							<tbody>
							
								<?php 
									$overAllLogs = $user->selectAll('project_logs');
									$logcount = count($overAllLogs);
									
									for($index = $logcount-1; $index >= 0; $index--){ 
										if(isset($count)){$count++;}else{$count=1;}
											
											
												$identifier = substr($overAllLogs[$index]->remarks, 0, 5);
												switch ($identifier) {
													case 'ISSUE':
														$remarksParts =  explode('^', $overAllLogs[$index]->remarks);
														$newRemarks = $remarksParts[2];
														break;
													case 'START':
														$newRemarks = "PR/JO was received in the office.";
														break;
													case 'AWARD':
														$remarksParts =  explode('^', $overAllLogs[$index]->remarks);
														$newRemarks = $remarksParts[2];
														break;
													case 'SOLVE':
														$remarksParts =  explode('^', $overAllLogs[$index]->remarks);
														$newRemarks = $remarksParts[2];
														break;													
													default:
														$newRemarks = $overAllLogs[$index]->remarks;
														break;
												}
												
												if($overAllLogs[$index]->type == "IN"){
													$typeColor = "text-danger";
												}else{
													$typeColor = "text-success";
												}
										
								?>
									
									<tr class="gradeA">
										<td><?php echo $count;?></td>
										<td><?php echo $overAllLogs[$index]->ID;?></td>
										<td><?php echo $overAllLogs[$index]->referencing_to;?></td>
										<td class="td-project-title"><?php echo $newRemarks;?></td>
										<td class="center <?php echo $typeColor;?>"><?php echo $overAllLogs[$index]->type;?></td>
										<td class="center"><?php echo Date::translate($overAllLogs[$index]->logdate, '1');?></td>
									</tr>
									
								<?php
									}
								?>
							
							
							
							</tbody>
							<tfoot>
							<tr>
								<th>#</th>
								<th>Log ID</th>
								<th>Refering to</th>
								<th>Log Remark</th>
								<th>Type</th>
								<th>Log Date</th>
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
