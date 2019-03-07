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
	
    <title>PrMO OPPTS | System Logs</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon"> 
	<?php include_once'../../includes/parts/user_styles.php'; ?>

</head>

<body>
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
				<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
						<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>System Logs</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">System Settings</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>System Logs</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        <a href="Dashboard" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>				
            </div>			


			<div class="wrapper wrapper-content">
			

				<div class="row">
					<div class="col-lg-12">
						<div class="ibox">
							<div class="ibox-title">
								<h5>System Logs Preview</h5>
							</div>
							<div class="ibox-content">

								<div class="m-b-lg">

									<div class="input-group input-group-sm">
										<input type="text" class="form-control" placeholder="Search" >
										<div class="input-group-append">
											<button class="btn btn-white" type="button">Search</button>
										</div>
									</div>

									<div class="m-t-md">

										<div class="float-right" style="display:none">
											<button type="button" class="btn btn-sm btn-white"> <i class="fa fa-comments"></i> </button>
											<button type="button" class="btn btn-sm btn-white"> <i class="fa fa-user"></i> </button>
											<button type="button" class="btn btn-sm btn-white"> <i class="fa fa-list"></i> </button>
											<button type="button" class="btn btn-sm btn-white"> <i class="fa fa-pencil"></i> </button>
											<button type="button" class="btn btn-sm btn-white"> <i class="fa fa-print"></i> </button>
											<button type="button" class="btn btn-sm btn-white"> <i class="fa fa-cogs"></i> </button>
										</div>
										<small>Showing read logs from current month logs</small><br>
										



									</div>

								</div>



								<div class="table-responsive">
								<table class="table table-hover issue-tracker">
								<?php

									$thisYear = date('Y');
										$month = date("F");

										$fileToRead = $month.".".$thisYear.".txt";

										if(file_exists("../../data/logfiles/".$fileToRead)){
											$entries[$month] = array();
											$myfile = fopen("../../data/logfiles/".$fileToRead, "r");

												$i=0;
												while(!feof($myfile)){
													$line = str_replace(array("\r", "\n"),'',fgets($myfile));
													if($line == "-------------------------"){$i++;}else{$entries[$month][$i][] = $line;}
												}
											fclose($myfile);
										}else{
											$entries[$month] = array();
										}
									


								?>
									<tbody>
									<?php 
										foreach ($entries as $asmonth){
											foreach ($asmonth as $singleLog){
												if(count($singleLog) != 4);
												else{

													// $singleLog[0] contains the IP and logdate separated by "-"
													// $singleLog[1] is the action
													// $singleLog[2] attempt?success:failed
													// $singleLog[3] contains Userid and username

													$IPandDate = explode("-", $singleLog[0]); //$IPandDate[1] is the dete log
													$IP = explode(":",$IPandDate[0]);	//$IP[1] is the ip address
													$action = explode(":",$singleLog[1]);

													$attempt = explode(":",$singleLog[2]);
														switch ($attempt[1]){
															case 'success':
																$labelClass = "label label-info";
																$label = "Success";
																break;
															case 'failed':
																$labelClass = "label label-warning";
																$label = "Failed";
																break;
															default:
																$labelClass = "label label-primary";
																$label = "Default";
																break;									
														}

													$identification = explode(":",$singleLog[3]);
													




													echo '
													<tr>
														<td>
															<span class="'.$labelClass.'">'.$label.'</span>
														</td>

														<td class="issue-info">
															<a>'.$identification[1].'</a>
															<small>
															'.$action[1].'
															</small>
														</td>

														<td>
															'.$identification[2].'
															<br><small>'.$IP[1].'</small>
														</td>
														
														<td>
														'.$IPandDate[1].'
														</td>
													</tr>													
													';
												}
											}
											
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


			<div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
			</div>

        </div>

    </div>
	
	<?php include_once'../../includes/parts/admin_scripts.php'; ?>

</body>
</html>
