<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

        /* This is for the validator modal admin level */
        $user = new Admin();
        $data = $user->userData(Session::get(Config::get('session/session_name')));
        $myArray = array('default');
        foreach($data as $element => $val){
            array_push($myArray, $val);
        }
    
        $commonFields =  ","." '". implode("', '", $myArray) ."'";

    if(Input::exists()){
        if(Token::check("passwordToken", Input::get('passwordToken'))){

            $validate = new Validate();

            $validation = $validate->check($_POST, array(
                    'new_username' => [
                        'required' => true,
                        'unique' => 'edr_account',
                        'unique' => 'prnl_account'
                    ],
                    'new_password' => [
                        'required' => true
                    ],
                    'password_again' => [
                        'matches' => 'new_password'
                    ]
            ));

            if($validation->passed()){
                $user = new User();
                $salt = Hash::salt(32);
                $ID = Session::get(Config::get('session/session_name'));

                try{
					$user->startTrans();
                    if($user->update('prnl_account', 'account_id', $ID, array(
                        'newAccount' => 0,
                        'username' => Input::get('new_username'),
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('new_password'), $salt)
                        
                        ))){
                        Session::delete("accounttype");
                        Session::put("accounttype", 0);
                        Session::flash('accountUpdated', 'Your Account has been succesfuly updated, Please Re-Login');
                        $user->logout();
                        Redirect::To('../../blyte/acc3ss');
					}
					$user->endTrans();
                }catch(Exception $e){
                    die($e->getMessage());
                }
                
            }
        }
    }


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Dashboard</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">
            <!--<div class="navbar-header">-->
                <!--<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">-->
                    <!--<i class="fa fa-reorder"></i>-->
                <!--</button>-->

                <a href="#" class="navbar-brand">PrMO OPPTS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-reorder"></i>
                </button>

            <!--</div>-->
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav mr-auto">
                    <li class="active">
                        <a aria-expanded="false" role="button" href="./"> Dashboard</a>
                    </li>
                    <li class="dropdown">
                        <a  role="button" href="#evaluation-list"> Evaluation</a>
                    </li>
                    <!-- <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item</a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item</a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item</a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li> -->

                </ul>
				
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<span class="m-r-sm text-muted welcome-message">Welcome to PrMO OPPTS</span>
					</li>
			
								<li class="dropdown">
									<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="NotifClick">
										<i class="fa fa-bell"></i>  
										<?php 
											$ClassNotif = new User();
											$notif = $ClassNotif->listNotification();
											if($notif['count']->seen === '0') echo '<span class="label label-primary" id="NotifCount" style="display: none;"></span>';
											else echo '<span class="label label-primary" id="NotifCount">'.$notif['count']->seen.'</span>';
										?>
									</a>
									<ul class="dropdown-menu dropdown-alerts" id="NotifList" style="overflow: auto; height:350px">
										<?php
											if(!empty($notif['list'])){
												foreach($notif['list'] as $n){
													if($n->seen === '0'){
														?>
															<li class="active">
																<?php 
																	if($n->href === null) echo '<a href="#" class="dropdown-item">';
																	else echo '<a href="'. $n->href .'" class="dropdown-item">';
																?>
																	<div>
																		<i class="fa fa-bell fa-fw"></i> <?php echo $n->message;?>
																	</div>
																	<small>Time: <?php echo Date::translate($n->datecreated, '1');?></small>
																</a>
															</li>
															<li class="dropdown-divider"></li>
														<?php
													}else{
														?>
															<li>
																<?php
																	if($n->href === null) echo '<a href="#" class="dropdown-item">';
																	else echo '<a href="'. $n->href .'" class="dropdown-item">';
																?>
																	<div>
																		<i class="fa fa-bell fa-fw"></i> <?php echo $n->message;?>
																	</div>
																	<small>Time: <?php echo Date::translate($n->datecreated, '1');?></small>
																</a>
															</li>
															<li class="dropdown-divider"></li>
														<?php
													}
												}
											}else{
												?>
												<div id="message">
													<li>
														<a href="#" class="dropdown-item">
															<div>
																<i class="fa fa-bell fa-fw"></i> No Messages
															</div>
														</a>
													</li>
													<li class="dropdown-divider"></li>
												</div>
												<?php
											}
										?>
									</ul>
								</li>
							
							
						
					<!--</li>-->
					
					<li>
						<a href="../logout">
							<i class="fa fa-sign-out-alt"></i> Log out
						</a>
					</li>
				</ul>				

            </div>
		
        </nav>
        </div>
        <div class="wrapper wrapper-content animated AnimateFadeInUp">

            <div class="container">
				<div class="row">
					<div class="col-lg-12">
					<div class="ibox-content m-b-sm border-bottom">
						<div class="p-xs">
							<div class="float-left m-r-md">
								<i class="fas fa-users text-navy mid-icon"></i>
								
							</div>
							<h2>Welcome back <?php
								$evaluationRequests = $user->evaluation(Session::get(Config::get('session/session_name')));						
								$hold = $user->fullname();
								$currentUser = json_decode($hold,true);
								
								
								echo $currentUser[0];				
							
							?>!</h2>
							<span>Technical Working Group / Technical Member</span>
						</div>
					</div>					
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="panel panel-warning">
							<div class="panel-heading">
								 New Projects to be Evaluated
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6" style="border-right:solid; border-color:#f8ac59">
										<h1 class="no-margins"><?php
										
										
										
										if($evaluationRequests == 0){
											echo $evaluationRequests;
										}else{
											echo count($evaluationRequests);
										}
										
										
										?></h1>
										<div class="font-bold "><i class="far fa-list-alt"></i> <small>Total Requests</small></div>
									</div>
									<div class="col-md-6">
										<h1 class="no-margins"><?php 
										
											$criticalRequestsCount = 0;

											if($evaluationRequests == 0){
												echo "0";
											}else{
												foreach ($evaluationRequests as $request){
													$dateToday = date_create(date('Y-m-d H:i:s'));
													$implementationDate = date_create($request->implementation_date);
													$diff=date_diff($dateToday,$implementationDate);
																										
													if($diff->format("%a") <= 7){
														$criticalRequestsCount++;
													}
												}
												echo $criticalRequestsCount;
											}


										
										?></h1>
										<div class="font-bold text-danger"><i class="fas fa-exclamation-triangle"></i> <small>Less than a Week before Required Implementation</small></div>
									</div>
								</div>							
							</div>
							<div class="panel-footer">
								<!-- Prioritize mo to gago -->
							</div>
						</div>
					</div>

					<div class="col-lg-8">
                        <div class="ibox ">
                            <div class="ibox-title">
                                <!-- <span class="label label-warning float-right">Data has changed</span> -->
                                <h5>Projects Evaluated</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-4">
                                        <small class="stats-label">Passed</small>
                                        <h4>0</h4>
                                    </div>

                                    <div class="col-4">
                                        <small class="stats-label">Evaluation with Issue</small>
                                        <h4>0</h4>
                                    </div>
                                    <div class="col-4">
                                        <small class="stats-label">Projects for Evaluation</small>
                                        <h4>0</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="ibox-content">
                                <div class="row">
                                    <div class="col-4">
                                        <small class="stats-label">Pages / Visit</small>
                                        <h4>643 321.10</h4>
                                    </div>

                                    <div class="col-4">
                                        <small class="stats-label">% New Visits</small>
                                        <h4>92.43%</h4>
                                    </div>
                                    <div class="col-4">
                                        <small class="stats-label">Last week</small>
                                        <h4>564.554</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-4">
                                        <small class="stats-label">Pages / Visit</small>
                                        <h4>436 547.20</h4>
                                    </div>

                                    <div class="col-4">
                                        <small class="stats-label">% New Visits</small>
                                        <h4>150.23%</h4>
                                    </div>
                                    <div class="col-4">
                                        <small class="stats-label">Last week</small>
                                        <h4>124.990</h4>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>


					<!-- <div class="col-md-4">
						<div class="ibox ">
							<div class="ibox-title">
								<span class="label label-primary float-right">Today</span>
								<h5>visits</h5>
							</div>
							<div class="ibox-content">

								<div class="row">
									<div class="col-md-6">
										<h1 class="no-margins">$ 406,420</h1>
										<div class="font-bold text-navy">44% <i class="fa fa-level-up"></i> <small>Rapid pace</small></div>
									</div>
									<div class="col-md-6">
										<h1 class="no-margins">206,120</h1>
										<div class="font-bold text-navy">22% <i class="fa fa-level-up"></i> <small>Slow pace</small></div>
									</div>
								</div>


							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Monthly income</h5>
								<div class="ibox-tools">
									<span class="label label-primary">Updated 12.2015</span>
								</div>
							</div>
							<div class="ibox-content no-padding">
								<div class="flot-chart m-t-lg" style="height: 55px;">
									<div class="flot-chart-content" id="flot-chart1"></div>
								</div>
							</div>

						</div>
					</div> -->
				</div>
                <!-- <div class="row">
                    <div class="col-lg-8">
                        <div class="ibox ">
                            <div class="ibox-content">
                                <div>
                                        <span class="float-right text-right">
                                        <small>Average value of sales in the past month in: <strong>United states</strong></small>
                                            <br/>
                                            All sales: 162,862
                                        </span>
                                    <h3 class="font-bold no-margins">
                                        Half-year revenue margin
                                    </h3>
                                    <small>Sales marketing.</small>
                                </div>

                                <div class="m-t-sm">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div>
                                            <canvas id="lineChart" height="114"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <ul class="stat-list m-t-lg">
                                                <li>
                                                    <h2 class="no-margins">2,346</h2>
                                                    <small>Total orders in period</small>
                                                    <div class="progress progress-mini">
                                                        <div class="progress-bar" style="width: 48%;"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h2 class="no-margins ">4,422</h2>
                                                    <small>Orders in last month</small>
                                                    <div class="progress progress-mini">
                                                        <div class="progress-bar" style="width: 60%;"></div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                                <div class="m-t-md">
                                    <small class="float-right">
                                        <i class="fa fa-clock-o"> </i>
                                        Update on 16.07.2015
                                    </small>
                                    <small>
                                        <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over $50,000.
                                    </small>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox ">
                            <div class="ibox-title">
                                <span class="label label-warning float-right">Data has changed</span>
                                <h5>User activity</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-4">
                                        <small class="stats-label">Pages / Visit</small>
                                        <h4>236 321.80</h4>
                                    </div>

                                    <div class="col-4">
                                        <small class="stats-label">% New Visits</small>
                                        <h4>46.11%</h4>
                                    </div>
                                    <div class="col-4">
                                        <small class="stats-label">Last week</small>
                                        <h4>432.021</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-4">
                                        <small class="stats-label">Pages / Visit</small>
                                        <h4>643 321.10</h4>
                                    </div>

                                    <div class="col-4">
                                        <small class="stats-label">% New Visits</small>
                                        <h4>92.43%</h4>
                                    </div>
                                    <div class="col-4">
                                        <small class="stats-label">Last week</small>
                                        <h4>564.554</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-4">
                                        <small class="stats-label">Pages / Visit</small>
                                        <h4>436 547.20</h4>
                                    </div>

                                    <div class="col-4">
                                        <small class="stats-label">% New Visits</small>
                                        <h4>150.23%</h4>
                                    </div>
                                    <div class="col-4">
                                        <small class="stats-label">Last week</small>
                                        <h4>124.990</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> -->

                <div class="row" id="evaluation-list">

                    <div class="col-lg-12">
                        <div class="ibox ">
                            <div class="ibox-title">
                                <h5>Evaluation List</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-5 m-b-xs">
                                        <div class="alert alert-success">
											<i class="fas fa-info"></i>	Listed below are evaluation requests entitled to you, please do observe the Remaining days Legend.
                                        </div>
									</div>
                                    <div class="col-sm-4 m-b-xs">
										<div class="panel panel-info">
											<div class="panel-heading">
												<i class="fas fa-info"></i> Remaining days Legend
											</div>
											<div class="panel-body">
												<span class="label label-success" style="border-radius:6.25em; background-color:#02a2f8">8</span> days and up
												<span class="label label-success" style="border-radius:6.25em; background-color:#f98111">1</span> less than a week
												<span class="label label-success" style="border-radius:6.25em; background-color:#de1010">1</span> 1-3 days
												<br>
												<p>before Project's Required Implemetation date</p>
											</div>
										</div>
                                    </div>									
                                    <div class="col-sm-3">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control form-control-sm" id="filter" placeholder="Search">
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-primary" type="button">Go!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
								
                                    <table class="table footable table-striped" data-filter="#filter">
                                        <thead>
											<tr>

												<th>#</th>
												<th>Enduser/s</th>
												<th>Reference</th>
												<th>Project</th>
												<th>ABC</th>
												<th style="width:110px">Registration Date </th>
												<th style="width:110px">Implementation Date </th>
												<th style="width:110px">Days Remaining</th>
												<th>Actions</th>
												
											</tr>
                                        </thead>
										
                                        <tbody>
										
											<?php
										if($evaluationRequests != 0){
											
										

												foreach($evaluationRequests as $request){

													$dateToday = date_create(date('Y-m-d H:i:s'));
													$implementationDate = date_create($request->implementation_date);
													$diff=date_diff($dateToday,$implementationDate);

													$counter = (isset($counter) ? $counter++ : $counter = 1);

													switch (true) {
														case ($diff->format("%a") <= 3):
															$class = "dangerGradient";
															break;
														
														case ($diff->format("%a") <= 7):
															$class = "warningGradient";
															break;
														default:
															$class = "greenGradient";
															break;															
													}
											?>
										
											<tr>
												<td><?php echo $counter = (isset($counter) ? $counter++ : $counter = 1);?></td>
												<td style="width: 150px">
													<?php
														$endusers = json_decode($request->end_user);
														$names = [];
														foreach ($endusers as $value){
															array_push($names, $user->fullnameOfEnduser($value));			
														}
														$displayNames =  implode(" / ", $names);
														echo $displayNames;


														

													?>
												</td>
												<td style="max-width:80px"><?php echo $request->project_ref_no;?></td>
												<td style="max-width: 300px"><?php echo $request->project_title;?></td>
												<td style="font-weight:bold">&#x20b1; <?php echo number_format($request->ABC, 2);?></td>
												<td><?php echo Date::translate($request->date_registered, 1); ?></td>
												<td><?php echo Date::translate($request->implementation_date, 2); ?></td>
												<td class="<?php echo $class;?>" style="text-align:center"><?php echo $diff->format("%R%a days");?> Days</td>
												<td><a href="evaluation?q=<?php echo base64_encode($request->project_ref_no);?>" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> Evaluate </a></td>
											</tr>
										
											
											<?php
												}
											}else{
												echo '<tr>
															<td colspan="9" style="text-align:center">No data Available</td>
												      </tr>';
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

        </div>
		<button class="back-to-top" type="button"></button>		
        <div class="footer">
			<?php include '../../includes/parts/footer.php'; ?>
        </div>

        </div>
        </div>


    <?php include '../../includes/parts/admin_scripts.php'; ?>
<!-- Password meter -->
	<script src="../../assets/js/plugins/pwstrength/pwstrength-bootstrap.min.js"></script>
	<script src="../../assets/js/plugins/pwstrength/zxcvbn.js"></script>
	<script>
		function openPanel(targetPannel) {
			$(targetPannel).trigger('click');
		}	
	</script>
	
	<script>	
		$(document).ready(function(){
           // Example 4 password meter
            var options4 = {};
            options4.ui = {
                container: "#pwd-container",
                viewports: {
                    progress: ".pwstrength_viewport_progress4",
                    verdict: ".pwstrength_viewport_verdict4"
                }
            };

            options4.common = {

                zxcvbn: true,
				zxcvbnTerms: ['asdasdasd', 'shogun', 'bushido', 'daisho', 'seppuku' <?php 
					if(isset($commonFields)) echo $commonFields;
					else{
						echo  $commonFields = '';
					}
				?>],
                userInputs: ['#year', '#new_username']
            };
            $('.example4').pwstrength(options4);

			
			//password valide
			var password = document.getElementById("new_password")
			  , confirm_password = document.getElementById("password_again");

			function validatePassword(){
			  if(password.value != confirm_password.value) {
				confirm_password.setCustomValidity("Passwords Don't Match");
			  } else {
				confirm_password.setCustomValidity('');
			  }
			}

			password.onchange = validatePassword;
			confirm_password.onkeyup = validatePassword;						
		})
	
	</script>

    <script>
        $(document).ready(function() {


            var d1 = [[1262304000000, 6], [1264982400000, 3057], [1267401600000, 20434], [1270080000000, 31982], [1272672000000, 26602], [1275350400000, 27826], [1277942400000, 24302], [1280620800000, 24237], [1283299200000, 21004], [1285891200000, 12144], [1288569600000, 10577], [1291161600000, 10295]];
            var d2 = [[1262304000000, 5], [1264982400000, 200], [1267401600000, 1605], [1270080000000, 6129], [1272672000000, 11643], [1275350400000, 19055], [1277942400000, 30062], [1280620800000, 39197], [1283299200000, 37000], [1285891200000, 27000], [1288569600000, 21000], [1291161600000, 17000]];

            var data1 = [
                { label: "Data 1", data: d1, color: '#17a084'},
                { label: "Data 2", data: d2, color: '#127e68' }
            ];
            $.plot($("#flot-chart1"), data1, {
                xaxis: {
                    tickDecimals: 0
                },
                series: {
                    lines: {
                        show: true,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 1
                            }, {
                                opacity: 1
                            }]
                        },
                    },
                    points: {
                        width: 0.1,
                        show: false
                    },
                },
                grid: {
                    show: false,
                    borderWidth: 0
                },
                legend: {
                    show: false,
                }
            });

            var lineData = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [48, 48, 60, 39, 56, 37, 30]
                    },
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 40, 51, 36, 25, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});

        });
    </script>
	<script>
	// Select all links with hashes
		$('a[href*="#"]')
		  // Remove links that don't actually link to anything
		  .not('[href="#"]')
		  .not('[href="#0"]')
		  .click(function(event) {
			// On-page links
			if (
			  location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
			  && 
			  location.hostname == this.hostname
			) {
			  // Figure out element to scroll to
			  var target = $(this.hash);
			  target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			  // Does a scroll target exist?
			  if (target.length) {
				// Only prevent default if animation is actually gonna happen
				event.preventDefault();
				$('html, body').animate({
				  scrollTop: target.offset().top
				}, 1000, function() {
				  // Callback after animation
				  // Must change focus!
				  var $target = $(target);
				  $target.focus();
				  if ($target.is(":focus")) { // Checking if the target was focused
					return false;
				  } else {
					$target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
					$target.focus(); // Set focus again
				  };
				});
			  }
			}
		  });
	</script>

</body>

</html>
