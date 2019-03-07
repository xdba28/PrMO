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
                    }
                }catch(Exception $e){
					Syslog::put($e,null,'error_log');
					Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0001");
                }
                
            }
        }else if(Token::check("directors-action", Input::get('directors-action'))){
			try{
				$user->startTrans();
					$user->update("projects", "project_ref_no", Input::get('to-prioritize'), array(
						'priority_level' => 'HIGH'
					));

					$user->register("project_logs", array(
						'referencing_to' => Input::get('to-prioritize'),
						'remarks' => "project ".Input::get('to-prioritize')." was set to high-priority project by the director.",
						'logdate' => Date::translate('now', 'now'),
						'type' =>  'IN'						
					));
				$user->endTrans();

				
				$success_notifs[] = "Actions taken, ".Input::get('to-prioritize')." was listed on the high priority projects.";
				# send notif to aids
				$user->register('notifications', array(
					'recipient' => "group5",
					'message' => "project ".Input::get('to-prioritize')." was set to high-priority project by the director.",
					'datecreated' => Date::translate('test', 'now'),
					'seen' => 0,
					'href' => "Ongoing-projects"
				));
				notif(json_encode(array(
					'receiver' => "group5",
					'message' => "project ".Input::get('to-prioritize')." was set to high-priority project by the director.",
					'date' => Date::translate(Date::translate('test', 'now'), '1'),
					'href' => "Ongoing-projects"
				)), true);
				
				Syslog::put('Updated project '.Input::get('to-prioritize').' to high priority');
			}catch(Exception $e){
				Syslog::put($e,null,'error_log');
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0002");
			}

		}
    }

   

?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Director</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon"> 
	<?php include_once'../../includes/parts/admin_styles.php'; ?>
	
	
		
	<style>
		.ibox-title1 {
			-moz-border-bottom-colors: none;
			-moz-border-left-colors: none;
			-moz-border-right-colors: none;	
			-moz-border-top-colors: none;
			background-color: #ffffff;
			border-color: #e7eaec;
			border-image: none;
			/* border-style: solid solid none; */
			border-width: 2px 0 0;
			color: inherit;
			margin-bottom: 0;
			padding: 15px 90px 8px 15px;
			min-height: 48px;
			position: relative;
			clear: both;
		}	
	
	</style>

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

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Director's Dashboard</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Dashboard</strong>
                        </li>
                    </ol>
                </div>

            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">

				<?php
										
					$yearEntries = $user->dashboard_procurement_entries('year');
					$monthEntries = $user->dashboard_procurement_entries('month');
					$weekEntries = $user->dashboard_procurement_entries('week');
					$dayEntries = $user->dashboard_procurement_entries('day');
					

				?>
				<h1>Procurement Entries</h1>
				<div class="row">
						<div class="col-lg-3 animated fadeInUp">
							<div class="ibox minimal-shadow">
								<div class="ibox-title" style="border-style:none">
									<span class="label label-warning float-right pull-right">2018</span>
									<h5>Yearly Entries</h5>
								</div>
								<div class="ibox-content" style="min-height:115px; max-height:900px">
									<h1 class="no-margins"><?php echo $yearEntries[0];?></h1>
									 <small>Total Entries</small>
										<?php
											if($yearEntries[1] == "No Comparison Data available from previous year."){
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $yearEntries[1];?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($yearEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-info";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $yearEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last year's number of entries.</small></div><br><br>

										<?php
											}
										?>
										
								   
								</div>
							</div>
						</div>
						<div class="col-lg-3 animated fadeInDown">
							<div class="ibox minimal-shadow">
								<div class="ibox-title" style="border-style:none">
									<span class="label label-warning float-right pull-right"><?php echo Date::translate('test','month');?></span>
									<h5>Monthly</h5>
								</div>
								<div class="ibox-content" style="height:115px">
									<h1 class="no-margins"><?php echo $monthEntries[0];?></h1>
									<small>Total Entries</small>
										<?php
											if($monthEntries[1] == "No Comparison Data available from previous month."){
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $monthEntries[1];?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($monthEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-success";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $monthEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last month's number of entries.</small></div><br><br>

										<?php
											}
										?>
									
								</div>
							</div>
						</div>
						<div class="col-lg-3 animated fadeInUp">
							<div class="ibox minimal-shadow">
								<div class="ibox-title" style="border-style:none">
									<span class="label label-warning float-right pull-right"><?php echo Date::translate('test','weekno');?> <i class="fas fa-info"></i></span>
									<h5>Weekly</h5>
								</div>
								<div class="ibox-content" style="height:115px">
									<h1 class="no-margins"><?php echo $weekEntries[0];?></h1>
									<small>Total Entries</small>
										<?php
											if($weekEntries[1] == "No Comparison Data available from previous week."){
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $weekEntries[1];?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($weekEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-success";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $weekEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last week's number of entries.</small></div><br><br>

										<?php
											}
										?>						
								</div>
							</div>
						</div>
						<div class="col-lg-3 animated fadeInDown">
							<div class="ibox minimal-shadow">
								<div class="ibox-title" style="border-style:none">
									<span class="label label-warning float-right pull-right"><?php echo Date::translate('test','today');?></span>
									<h5>Today's Entries</h5>
								</div>
								<div class="ibox-content" style="height:115px">
									<h1 class="no-margins"><?php echo $dayEntries[0];?></h1>
									<small>Total Entries</small>
										<?php
											if($dayEntries[1] == "No Comparison Data available from previous day."){
												$dayEntries = "No Comparison Data available from yesterday."
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $dayEntries;?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($dayEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-info";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $dayEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last yesterday's number of entries.</small></div><br><br>

										<?php
											}
										?>								
								</div>
							</div>
						</div>
				</div>
				
				<h1>Current Projects Nearing Implemenation Date</h1>
				<div class="row">

					<?php
						$reports = $user->dashboardReports();
						if($reports["current_projects"]){
						foreach ($reports["current_projects"] as $project) {

							
							if($project->priority_level !== "HIGH"){

								
								$diff = date_diff(date_create(Date::translate('now', 'now')),date_create($project->implementation_date));
								$sign = ( $diff->format("%R%a") > 0 ) ? 1 : ( ( $diff->format("%R%a") < 0 ) ? -1 : 0 ); 


								if(($diff->format("%a") <= 10) AND ($sign == 1)){
									$urgentPriority[] = $project;
								}else if(($diff->format("%a") <= 29) AND ($sign == 1)){
									$highPriority[] = $project;
								}else if(($diff->format("%a") >= 30) AND ($diff->format("%a") <= 200) AND ($sign == 1)){
									$lowPriority[] = $project;
								}
							}
						}
					}else{
						echo '
						<div class="col-lg-12 animated fadeInRight">
							<div class="ibox-content m-b-sm border-bottom">
								<div class="p-xs">
									<div class="float-left m-r-md">
										<i class="fa fa-quote-left text-navy mid-icon"></i>
									</div>
									<h2>Sorry, but we do not have any data right now.</h2>
									
								</div>
							</div>
						</div>
						
						';
					}	
						
						
					?>


					<?php

						if(!empty($urgentPriority)){
							foreach ($urgentPriority as $project) {
								$diff = date_diff(date_create(Date::translate('now', 'now')),date_create($project->implementation_date));								
								if(strlen($project->project_title) > 90){
									$title =  substr($project->project_title, 0, 87) . "...";
								}else{
									$title = $project->project_title;
								}
								echo'
									<div class="col-lg-3 animated fadeInRight">
										<a data-toggle="modal" data-target="#nearing-projects" data-priority="urgent" data-project="'.$project->project_ref_no.'" data-title="'.$project->project_title.'">
										<div class="widget style1 bg-danger">
											<div class="row">
												<div class="col-4">
													<i class="ti-alarm-clock fa-4x"></i>
												</div>
												<div class="col-8 text-right" style="height:90px">
													<span>'.$title.'</span>
												</div>
												<div class="col-lg-12 text-right"><h2 class="font-bold">'.$diff->format("%R%a").' day/s</h2></div>
											</div>
										</div>
										</a>
									</div>
								';								
							}

						}

						if(!empty($highPriority)){
							foreach ($highPriority as $project) {
								$diff = date_diff(date_create(Date::translate('now', 'now')),date_create($project->implementation_date));					
								if(strlen($project->project_title) > 90){
									$title =  substr($project->project_title, 0, 87) . "...";
								}else{
									$title = $project->project_title;
								}								
								echo'
									<div class="col-lg-3 animated fadeInRight">
										<a data-toggle="modal" data-target="#nearing-projects" data-priority="high" data-project="'.$project->project_ref_no.'" data-title="'.$project->project_title.'">
										<div class="widget style1 yellow-bg">
											<div class="row">
												<div class="col-4">
													<i class="ti-alert fa-4x"></i>
												</div>
												<div class="col-8 text-right" style="height:90px">
													<span>'.$title.'</span>
												</div>
												<div class="col-lg-12 text-right"><h2 class="font-bold">'.$diff->format("%R%a").' days</h2></div>
											</div>
										</div>
										</a>
									</div>								
								';
							}

						}

						if(!empty($lowPriority)){
							foreach ($lowPriority as $project) {
								$diff = date_diff(date_create(Date::translate('now', 'now')),date_create($project->implementation_date));
								if(strlen($project->project_title) > 90){
									$title =  substr($project->project_title, 0, 87) . "...";
								}else{
									$title = $project->project_title;
								}
								echo'
									<div class="col-lg-3 animated fadeInRight">
									<a data-toggle="modal" data-target="#nearing-projects" data-priority="low" data-project="'.$project->project_ref_no.'" data-title="'.$project->project_title.'">
										<div class="widget style1 lazur-bg">
											<div class="row">
												<div class="col-4">
													<i class="ti-calendar fa-4x"></i>
												</div>
												<div class="col-8 text-right" style="height:90px">
													<span>'.$title.'</span>
												</div>
												<div class="col-lg-12 text-right"><h2 class="font-bold">'.$diff->format("%R%a").' days</h2></div>
											</div>
										</div>
										</a>
									</div>								
								';
							}
						}
					?>	


				
				</div>			


						
            </div>
			<!-- Main Content End -->
			
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

$(document).ready(function() {
	
	$('.custom-file-input').on('change', function() {
	   let fileName = $(this).val().split('\\').pop();
	   $(this).next('.custom-file-label').addClass("selected").html(fileName);
	}); 

});
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

	<script>
	$(function(){
		$('#nearing-projects').on('show.bs.modal', function (event){

			var editButton = $(event.relatedTarget) // Button that triggered the modal
			var currentProject = editButton.data('project')
			var title = editButton.data('title')
			var priority = editButton.data('priority')
			var modal = $(this);

			SendDoSomething("GET", "xhr-project-details.php", {
				id: currentProject
			}, {
				do: function(result){
					var projectDetails = result.project_details;

					var imp_date_split = projectDetails.implementation_date.split('-');
					var months = [
						"January", "February", "March",
						"April", "May", "June", "July",
						"August", "September", "October",
						"November", "December"
					];
					
					var display_date = `${months[parseInt(imp_date_split[1]) - 1]} ${imp_date_split[2]}, ${imp_date_split[0]}`;
					var accomplishment = ((projectDetails.accomplished / projectDetails.steps) * 100).toFixed(1);
					var currentdate = new Date();
					var diff = new Date(projectDetails.implementation_date) - currentdate;
					var days = diff / 1000 / 60 / 60 / 24					
					

					switch (priority) {
						case "urgent":
							modal.find('#modal-icon-nearing').removeClass().addClass('ti-alarm-clock text-danger modal-icon');
							// modal.find('.modal-title').text(currentProject);
							// modal.find('#modal-description-nearing').text(title);
							modal.find('#days').removeClass().addClass('text-danger');
							// modal.find('#days').text(Math.floor(days));
							modal.find('#implementation-date').removeClass().addClass('text-danger');
							// modal.find('#implementation-date').text(display_date);
							// modal.find('#accomplishment').text(accomplishment);
							// modal.find('#workflow').text(projectDetails.workflow);
							// var finallink = "project-details?refno="+projectDetails.project_ref_no;
							// document.getElementById("link").href=finallink;
							// document.getElementById("to-prioritize").value=projectDetails.project_ref_no;

				
							;
							
							break;
						case "high":
							modal.find('#modal-icon-nearing').removeClass().addClass('ti-alert text-warning modal-icon');
							modal.find('#days').removeClass().addClass('text-warning');
							modal.find('#implementation-date').removeClass().addClass('text-warning');												
							break;
						case "low":
							modal.find('#modal-icon-nearing').removeClass().addClass('ti-calendar text-info modal-icon');
							modal.find('#days').removeClass().addClass('text-info');
							modal.find('#implementation-date').removeClass().addClass('text-info');							
							break;
					}


							modal.find('.modal-title').text(currentProject);
							modal.find('#modal-description-nearing').text(title);						
							modal.find('#days').text(Math.floor(days));
							modal.find('#implementation-date').text(display_date);
							modal.find('#accomplishment').text(accomplishment);
							modal.find('#workflow').text(projectDetails.workflow);
							var finallink = "project-details?refno="+projectDetails.project_ref_no;
							document.getElementById("link").href=finallink;
							document.getElementById("to-prioritize").value=projectDetails.project_ref_no;					

				}
			});

		});


	});
	
	</script>





</body>

</html>
