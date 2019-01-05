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

    <title>PrMO OPPTS | Evaluation</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>
	<script>
		// this is a boolean variable
		// check if the evaluation has an issue
		<?php
			$hasEvaluationIssue = $user->checkProjectIssue(base64_decode($_GET['q']));
			if($hasEvaluationIssue){
				$issue = true;
			}else{
				$issue = "false";
			}

		?>
		
		
		var issue = <?php 
			echo $issue;
		?>;
	</script>
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
                    <li class="">
                        <a aria-expanded="false" role="button" href="../technical-member"> Dashboard</a>
                    </li>
                    <li class="dropdown">
                        <a  role="button" href="./#evaluation-list"> Evaluation</a>
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

                </ul>
				
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<span class="m-r-sm text-muted welcome-message">Welcome to PrMO OPPTS</span>
					</li>
					
					<li class="dropdown">
						<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="NotifClick">
							 <i class="fa fa-bell"></i>  <span class="label label-primary" style="right: 14px; top:8px;">8</span>
						</a>
						<ul class="dropdown-menu dropdown-alerts" id="NotifList">
                        <li>
                            <a href="mailbox.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="profile.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="float-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="grid_options.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="#" class="dropdown-item">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
						</ul>
					</li>
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


			<?php
			
			
					if( (isset($_GET['q'])) && (!empty($_GET['q'])) ){
							$refno = base64_decode($_GET['q']);
							$project = $user->get('projects', array('project_ref_no', '=', $refno));
							
							
							if($project){
								$endusers = json_decode($project->end_user);

								// update immidiately that this project is being evaluated and seen, "only if the accomplished is 0 or waiting for evaluation"
								if($project->accomplished == 0){

									$user->startTrans();

										$user->update('projects', 'project_ref_no', $refno, array(
											'accomplished' => '1'
										));

										foreach ($endusers as $enduser) {
											$user->register("notifications", array(
												'recipient' => $enduser,
												'message' => "Project Request {$project->project_ref_no} is currently being evaluated",
												'datecreated' => Date::translate('test', 'now'),
												'seen' => 0,
												'href' => ""
		
											));
										}

										$user->register("project_logs", array(
											'referencing_to' => $project->project_ref_no,
											'remarks' => "Project details of {$project->project_ref_no} is being evaluated.",
											'logdate' => date('Y-m-d H:i:s', strtotime('+1 second')),
											'type' =>  'OUT'
										));

										#send SMS to end user "Project Request {$project->project_ref_no} is currently being evaluated now"

									$user->endTrans();
								}

								
							
			?>

				<div class="container">
					<?php 
						$forms = json_decode($project->request_origin);

						foreach ($forms as $form){
							// get the general detail for this form
							$formDetails = $user->get('project_request_forms', array('form_ref_no', '=', $form));
							// sum all the cost foreach form "ABC/TOTAL COST" perlot
							$lots = $user->getAll('lots', array('request_origin', '=', $form));
							$lotCost = 0;
							foreach ($lots as $lot) {
								$lotCost += $lot->lot_cost;
							}
							
					?>	
					
							<div class="row">
								<div class="col-lg-12">
									<div class="panel panel-info">
										<div class="panel-heading">
											<i class="fa fa-info-circle"></i> Info Panel
											
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-6 b-r"> 
													<h4 class="text-left" style="color: #F37123">Form Reference</h4>
														<p style="margin-left:20px;"><b><?php echo $form." - ".$formDetails->title;?></b></p>
													<h4 class="text-left" style="color: #F37123">Purpose</h4>
														<p style="margin-left:20px"><b><?php echo $formDetails->purpose;?></b></p>
													<h4 class="text-left" style="color: #F37123">Total Request Cost</h4>
														<h3 style="margin-left:20px"><i>&#x20b1; <?php echo number_format($lotCost, 2);?></i></h3>		
												</div>
												<div class="col-sm-6">
													<h4 class="text-left" style="color: #F37123">Lots</h4>
													<div class="row">

														<?php
															foreach ($lots as $lot) {

																if(($lot->lot_no == 101) && ($lot->lot_title)){
																	$displayLot = "Static lot - No lot setted";
																}else{
																	$displayLot = "Lot ".$lot->lot_no;
																}
																$parameter = "'#".$lot->request_origin."-".$lot->lot_no."'";
																echo '
																<div class="col-lg-6" style="margin-right:-15px; margin-top:10px">													
																	<a href="#'.$lot->request_origin.'-'.$lot->lot_no.'" onclick="openPanel('.$parameter.')" class="btn btn-success btn-outline btn-block"> '.$displayLot.' </a>
																	
																</div>
																';
															}
														?>
													</div>
												</div>
											</div>
								
											
										</div>

									</div>
								</div>
								
								<?php
								
									foreach ($lots as $lot) {
										if(($lot->lot_no == 101) && ($lot->lot_title)){
											$iboxTitle = "Static Lot";
										}else{
											$iboxTitle = "Lot ".$lot->lot_no." - ".$lot->lot_title;
										}
								?>
								<div class="col-lg-12">
									<div class="ibox collapsed myShadow">
										<div class="ibox-title">
											<h5>Contents of <?php echo $iboxTitle;?></h5>
											<div class="ibox-tools">
												<a id="<?php echo $lot->request_origin."-".$lot->lot_no;?>" class="collapse-link">
													<i class="fa fa-chevron-up"></i>
												</a>
											</div>
										</div>
										<div class="ibox-content">
											
											<?php
												$type = substr($lot->request_origin, 0, 2);
												if($type === "PR"){
													//echo "<pre>",print_r($lot),"</pre>";
													$requestContent = $user->getContent($lot->request_origin, $type, $lot->lot_no);
													//echo "<pre>",print_r($requestContent),"</pre>";													
											?>
												<table class="table table-bordered table-hover">
													<thead>
													<tr>
														<th>Item</th>
														<th>Stock No.</th>
														<th>Unit</th>
														<th>Description</th>
														<th>Quantity</th>
														<th>Unit Cost</th>
														<th>Total Cost</th>
													</tr>
													</thead>
													<tbody>
													<?php
														$line = 1;
														$showTotalLotCost = 0;
														foreach($requestContent as $detail){
															
															$showTotalLotCost = $showTotalLotCost + $detail->total_cost;
															
													?>
													<tr>
														<td><?php echo $line;?></td>
														<td><?php echo $detail->stock_no; ?></td>
														<td><?php echo $detail->unit; ?></td>
														<td class="tddescription"><?php echo $detail->item_description; ?></td>
														<td><?php echo $detail->quantity; ?></td>
														<td><?php echo $detail->unit_cost; ?></td>
														<td>&#x20b1; <?php echo number_format($detail->total_cost,2); ?></td>
													</tr>

													<?php
														$line++;
														}
														echo '<tr>
																<td colspan="6" style="text-align:center; background-color:#4ac24a; color:black">Total Lot Cost</td>
																<td style="background-color:#fa4711; color:white">&#x20b1; '.number_format($showTotalLotCost,2).'</td>
															</tr>';
													?>
													</tbody>
												</table>

											<?php
												}else if($type === "JO"){												
													$requestContent = $user->getContent($lot->request_origin, $type, $lot->lot_no);
													
											?>
												<div class="row">
													<div class="col-lg-4">
														<h4 class="text-left" style="color: #F37123">Lot Estimated Cost</h4>
														<div class="widget style1 lazur-bg">
															<div class="row vertical-align">
																<div class="col-1">												
																	<a class="fa fa-3x" style="font-weight:400; color:#3f4141">&#x20b1;</a>
																</div>
																<div class="col-10">
																	<h2 class="font-bold" style="color:#3f4141"><?php echo number_format($lot->lot_cost,2);?></h2>
																</div>
															</div>
														</div>
													</div>	
													<div class="col-lg-8">
														<h4 class="text-left" style="color: #F37123">Lot Comment</h4>
														<div class="widget style1 yellow-bg">
															<div class="row vertical-align">
																<div class="col-1">												
																	<i class="ti-bookmark-alt fa-3x" style="color:#"></i>
																</div>
																<div class="col-10">
																	<p style="color:#3f4141; font-size:15px"><i>"<?php echo $lot->note;?>".</i></p>
																</div>
															</div>
														</div>
													</div>													
												</div>

												<table class="footable table table-stripped toggle-arrow-tiny">
													<thead>
													<tr>
														<th>List Title</th>
														<th>Tags</th>
													</tr>
													</thead>
													<tbody>

														<?php
															foreach($requestContent as $detail){
														?>
															<tr>
																<td><?php echo $detail->header;?></td>
																<td><?php echo str_replace(",", ", ", $detail->tags);?></td>
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
											<?php
												}										
											?>
										</div>
									</div>
								</div>
								<?php
								}
								?>

							</div>
							<hr role="tournament5">

					<?php
						}
					?>
				</div>
				
			<?php
							}else{
								include('../../includes/errors/404.php');
								echo"<br><br><br><br><br><br>";
							}
					}else{
								include('../../includes/errors/404.php');
								echo"<br><br><br><br><br><br>";
					}

				
			?>
        </div>


		<div class="radial">
		  <a href="./#evaluation-list" class="fas fa-angle-double-left fa-2x" title="Back to Evaluation List" id="fa-1"></a>
		  <button class="far fa-edit fa-2x" data-toggle="modal" data-target="#twgEvaluation" data-toevaluate="<?php echo $refno;?>" data-evaluatortwg="<?php echo $user->fullnameOf(Session::get(Config::get('session/session_name'))) ?>" title="Register Comment and MOP" id="fa-2"></button>
		  <button class="fas fa-chevron-up static-back-to-top fa-2x" title="Back to Top" id="fa-3"></button>
		  <button class="fab">
			<div class="fas fa-plus fa-2x" id="plus"></div>
		  </button>
		</div>
		
		<!--<button class="back-to-top" type="button"></button>		-->
        <div class="footer" style="z-index:1">
			<?php include '../../includes/parts/footer.php'; ?>
        </div>

        </div>
    </div>


    <?php include '../../includes/parts/admin_scripts.php'; ?>
<!-- Password meter -->
	<script src="../../assets/js/plugins/pwstrength/pwstrength-bootstrap.min.js"></script>
	<script src="../../assets/js/plugins/pwstrength/zxcvbn.js"></script>
	
	<script>
$(document).ready(function(){

	if(issue){
		$('[dataFor="pre-proc-eval-issue-twg"]').html(`
		<div class="alert alert-danger">
			<p>This project has a previous issue with technical member's evaluation.</p><br> <pre><?php echo $project->evaluators_comment;?></pre> Choose below from the options if the issue above has been resolved or not.
		</div>								
		<div class="radio radio-danger" style="padding-left:5px">
			<input type="radio" name="resolution" id="radio1" value="no" required>
			<label for="radio1" class="text-danger">
				Check this if you consider this comment as another issue to be resolved or cleared by the enduser.
			</label>
		</div>
		<div class="radio radio-info" style="padding-left:5px">
			<input type="radio" name="resolution" id="radio2" value="yes" required checked>
			<label for="radio2" class="text-success">
				Check this if this re-evaluation is a resolution from the previous evaluation issue.
			</label>
		</div>`);
	}else{
		$('[dataFor="pre-proc-eval-issue-twg"]').html(`
		<div class="checkbox checkbox-danger" style="padding-left:5px">
			<input id="checkbox-issue" type="checkbox" name="issue">
			<label for="checkbox-issue" class="text-warning font-italic">
				Check this if you consider this comment as an issue to be resolved or cleared by the enduser.
			</label>
		</div>`);
	}
});

  $('.radial').hover (function(){
    $('.radial').toggleClass('open');
  });

	</script>

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
