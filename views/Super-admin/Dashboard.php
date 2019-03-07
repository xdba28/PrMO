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
    
        $commonFields =  "'". implode("', '", $myArray) ."'";
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
						if($user->update('prnl_account', 'account_id', $ID, array(
							'newAccount' => 0,
							'username' => Input::get('new_username'),
							'salt' => $salt,
							'userpassword' => Hash::make(Input::get('new_password'), $salt)
							
							))){
							Session::delete("accounttype");
							Session::put("accounttype", 0);
							Session::flash('accountUpdated', 'Your Account has been succesfuly updated, Please Re-Login');
							Syslog::put('Account setup');
							$user->logout();
							Redirect::To('../../blyte/acc3ss');
							exit();
						}
					}catch(Exception $e){
						// die($e->getMessage());
						Syslog::put($e,null,'error_log');
						Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0001');
					}
					
				}else{
					Syslog::put('Account setup', null, 'failed');
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


			<div class="wrapper wrapper-content animated fadeInUp">
			
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox-content m-b-sm border-bottom">
							<div class="p-xs">
								<div class="float-left m-r-md">
									<i class="fas fa-users-cog text-navy mid-icon"></i>
								</div>
								<h2>Welcome back <?php											
									$hold = $user->fullname();
									$currentUser = json_decode($hold,true);	
									
									
									echo $currentUser[0];				
								
								?>!</h2>
								<span>Super Admin</span>
							</div>
						</div>
					</div>
				</div>
								

				<div class="row">
				
				<div class="col-lg-6 animated fadeInLeft">
					<div class="widget style1 lazur-bg">
						<div class="row">
							<div class="col-8">
								<div class="">
									<h1 class="m-xs">0</h1>

									<h3 class="font-bold no-margins">
										Overall Ongoing Projects
									</h3>
									<small>Ongoing and Paused</small>
								</div>
							</div>
							<div class="col-4 text-right text-center">
								<!-- <span> New albums </span> -->
								<a href="Ongoing-projects" class="btn btn-default btn-outline">View Details</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 animated fadeInRight">
					<div class="widget style1" style="background-color:#8CC63E; color:white">
						<div class="row">
							<div class="col-8">
								<div class="">
									<h1 class="m-xs">0</h1>

									<h3 class="font-bold no-margins">
										Account Requests waiting for approval
									</h3>
									<small>Requests from end users</small>
								</div>
							</div>
							<div class="col-4 text-right text-center">
								<!-- <span> New albums </span> -->
								<a href="account-request" class="btn btn-default btn-outline">View Details</a>
							</div>
						</div>
					</div>
				</div>		

					<div class="col-lg-12">
						<div class="ibox ">
							<div class="ibox-content">
								<div>
									<span class="float-right text-right">
									<small>The system automatically creates a new log file every month</small>
										<br/>
										Files available: <?php
										 echo (count(scandir('../../data/logfiles/'))-2) ;
										 
										?>
									</span>
									<h3 class="font-bold no-margins">
										System Logs Overview
									</h3>
									<small>Line Graph</small>
								</div>

								<div class="m-t-sm">

									<div class="row">
							
										<div class="col-md-10">
											<div>
												<canvas id="lineChart" height="114"></canvas>
											</div>
										</div>
										<div class="col-md-2">
											<ul class="stat-list m-t-lg">
												<li>
													<h2 id="overall1" class="no-margins">0</h2>
													<small>Overall System Logs</small>
													<div class="progress progress-mini">
														<div class="progress-bar" style="width: 100%; background-color:#ababac"></div>
													</div>
												</li>
												<li>
													<h2 id="overall2" class="no-margins ">0</h2>
													<small>Overall attempt succeeded</small>
													<div class="progress progress-mini">
														<div class="progress-bar" style="width: 100%; background-color:#8cc63e"></div>
													</div>
												</li>
												<li>
													<h2 id="overall3" class="no-margins ">0</h2>
													<small>Overall attempt failure</small>
													<div class="progress progress-mini">
														<div class="progress-bar" style="width: 100%; background-color:#ea3c14"></div>
													</div>
												</li>
												<!-- <li>
													<h2 id="overall4" class="no-margins ">0000</h2>
													<small>Overall errors detected</small>
													<div class="progress progress-mini">
														<div class="progress-bar" style="width: 100%; background-color:#ea3c14"></div>
													</div>
												</li>												 -->
												<li>
													<a href="system-logs" class="btn btn-block btn-warning">View System Logs</a>
												</li>
											</ul>
										</div>
									</div>

								</div>

								<div class="m-t-md">
									<small class="float-right">
										<i class="fa fa-clock-o"> </i>
										Last Updated <?php echo Date::translate(Date::translate("now", "now"), 1); ?>
									</small>
									
								</div>

							</div>
						</div>
					</div>
					



				</div>
				
				
				
				
				
		



				<div id="div-to-delete" class="row" style="display:none">
					<div class="flot-chart m-t-lg" style="height: 55px;">
						<div class="flot-chart-content" id="flot-chart1"></div>
					</div>
				</div>	
				
			</div>


			<div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
			</div>

        </div>

    </div>
	
	<?php include_once'../../includes/parts/admin_scripts.php'; ?>
	<!-- Password meter -->
	<script src="../../assets/js/plugins/pwstrength/pwstrength-bootstrap.min.js"></script>
	<script src="../../assets/js/plugins/pwstrength/zxcvbn.js"></script>
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
				zxcvbnTerms: ['asdasdasd', 'shogun', 'bushido', 'daisho', 'seppuku', <?php 
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

    <!-- Flot -->
    <script src="../../assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="../../assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../../assets/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="../../assets/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="../../assets/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="../../assets/js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="../../assets/js/plugins/flot/curvedLines.js"></script>

    <!-- ChartJS-->
    <script src="../../assets/js/plugins/chartJs/Chart.min.js"></script>
	
	

    <script>
        $(document).ready(function() {
			
			


            var d1 = [[1262304000000, 6], [1264982400000, 3057], [1267401600000, 20434], [1270080000000, 31982], [1272672000000, 26602], [1275350400000, 27826], [1277942400000, 24302], [1280620800000, 24237], [1283299200000, 21004], [1285891200000, 12144], [1288569600000, 10577], [1291161600000, 10295]];
            var d2 = [[1262304000000, 5], [1264982400000, 200], [1267401600000, 1605], [1270080000000, 6129], [1272672000000, 11643], [1275350400000, 19055], [1277942400000, 30062], [1280620800000, 39197], [1283299200000, 37000], [1285891200000, 27000], [1288569600000, 21000], [1291161600000, 17000]];
			//var d3 = [[1262304000000, 9], [1264982400000, 500], [1267401600000, 1800], [1270080000000, 6129], [1272672000000, 11643], [1275350400000, 19055], [1277942400000, 30062], [1280620800000, 39197], [1283299200000, 37000], [1285891200000, 27000], [1288569600000, 21000], [1291161600000, 17000]];

            var data1 = [
                { label: "Data 1", data: d1, color: '#17a084'},
                { label: "Data 2", data: d2, color: '#127e68' }
				//{ label: "Data 3", data: d3, color: '#dc3545' }
				
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
				labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
				<?php

					$thisYear = date('Y');
					for ($x=1; $x <= 12 ; $x++){

						$month = date("F", mktime(0, 0, 0, $x, 10));

						$totalLogs = 0;
						$failed = 0;
						$succeed = 0;
						$errors = 0;

						$fileToRead = $month.".".$thisYear.".txt";

						if(file_exists("../../data/logfiles/".$fileToRead)){

							$myfile = fopen("../../data/logfiles/".$fileToRead, "r");

								while(!feof($myfile)){
									$line = str_replace(array("\r", "\n"),'',fgets($myfile));
									switch ($line) {
										case '-------------------------':
											$totalLogs++;
											break;
										case 'Attempt:success':
											$succeed++;
											break;
										case 'Attempt:failed':
											$failed++;
											break;
										case 'Attempt:error_log':
											$errors++;
											break;
									}
								}

							fclose($myfile);

							$logs[$month] = array("total" => $totalLogs, "succeed" => $succeed, "failed" => $failed, "error" => $errors);

						}else{
							$logs[$month] = array("total" => 0, "succeed" => 0, "failed" => 0, "error" => 0);
						}

						
					}

					foreach ($logs as $log) {
						$totalArray[] = $log["total"];
						$succeedArray[] = $log["succeed"];
						$failedArray[] = $log["failed"];
						$errorsArray[] = $log["error"];
					}

					
					

					
					
				
				?>

				// dataset for errors
                    // {
                    //     label: "Errors",
                    //     backgroundColor: "rgba(234,60,20,0.5)",
                    //     borderColor: "rgba(234,60,20,1)",
                    //     pointBackgroundColor: "#e51313",
                    //     pointBorderColor: "rgba(234,60,20,1)",
                    //     data: [<?php echo implode(', ', $errorsArray); ?>]
                    // }			
                datasets: [

                    {
                        label: "Failed",
                        backgroundColor: "rgba(234,60,20,0.5)",
                        borderColor: "rgba(234,60,20,1)",
                        pointBackgroundColor: "#e51313",
                        pointBorderColor: "rgba(234,60,20,1)",
                        data: [<?php echo implode(', ', $failedArray); ?>]
                    },
                    {
                        label: "Success",
                        backgroundColor: "rgba(140,198,62,0.5)",
                        borderColor: "rgba(140,198,62,1)",
                        pointBackgroundColor: "rgba(140,198,62,1)",
                        pointBorderColor: "#fff",
                        data: [<?php echo implode(', ', $succeedArray); ?>]
					},
					{
                        label: "Total Logs",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",						
                        pointBorderColor: "#fff",
                        data: [<?php echo implode(', ', $totalArray); ?>]
                    }
					
                ]
            };

            var lineOptions = {
                responsive: true
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
			new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
			


				$("#overall1").text('<?php echo number_format(array_sum($totalArray));?>');
				$("#overall2").text('<?php echo number_format(array_sum($succeedArray));?>');
				$("#overall3").text('<?php echo number_format(array_sum($failedArray));?>');
				// $("#overall4").text('<?php echo number_format(array_sum($errorsArray));?>');

				



        });
    </script>	
</body>
</html>
