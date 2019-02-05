<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

	$reports = $user->dashboardReports();
   

?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Reports</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include_once'../../includes/parts/admin_styles.php'; ?>
    <!-- orris -->
    <link href="../../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
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
                    <h2>Procurement Reports</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Reports</strong>
                        </li>
                    </ol>
                </div>

            </div>
			
			<!-- Main Content -->
        <div class="wrapper wrapper-content animated fadeInRight" >

			<?php  $reports=$user->dashboardReports(); ?>
			<div class="p-w-md m-t-sm">
				<div class="row">

					<div class="col-lg-4">
						<div class="row">
							<div class="col-lg-12">
								<h2>Overall Projects Classification</h2>
								<div id="morris-donut-chart" class="text-center"></div>
							</div>
							<div class="col-lg-12">
								<h3>Classification Breakdown</h3>
								<div class="row text-center">


								<?php

									if(isset($reports["current_projects_breakdown"])){
										$breakdownCount = count($reports["current_projects_breakdown"]);
											if($breakdownCount == 1){$col = "col-lg-12";}else if($breakdownCount == 2){$col = "col-lg-6";}else if($breakdownCount == 3){$col = "col-lg-4";}
										for ($x=1; $x <= $breakdownCount; $x++){ 
											echo '
												<div class="'.$col.'">
													<canvas id="doughnutChart'.$x.'" width="120" height="120" style="margin: 18px auto 0"></canvas>
													<h5 id="doughnutLabel'.$x.'">Default Label</h5>
												</div>
											';
										}
										
									}
								
								?>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-8">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Projects Success Ratio</h5>
							</div>
							<div class="ibox-content">
								<div class="alert alert-success">Try clicking on the legends below to toggle their display.</div>
								<div>
									<canvas id="barChart" height="180"></canvas>
								</div>
							</div>
						</div>
					</div>
					
				</div>

		
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>All Ongoing Projects</h5>
							</div>
							<div class="ibox-content">

								<div class="table-responsive">
									<table id="ongoing_report" class="table table-striped table-bordered table-hover dataTables-example" >
									
									<thead>
										<tr>
											<th>#</th>
											<th>Reference</th>
											<th>Title</th>
											<th>MOP</th>
											<th>ABC</th>
											<th>Date Registered</th>
											<th>Implementation</th>
											<th>Workflow</th>
											<th>Accomplishment</th>
										</tr>
									</thead>
									
									<tbody>

										<?php

											$counter = 0;
											if($reports["current_projects"]){
											foreach ($reports["current_projects"] as $project){
												$counter++;

												$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1); //computation on progress report
												
											
										?>
										<tr class="gradeX">
											<td><?php echo $counter;?></td>
											<td>
												<?php echo $project->project_ref_no;?>
											</td>
											<td><?php echo $project->project_title;?></td>
											<td class="center"><?php echo $project->MOP;?></td>
											<td class="center"><?php echo Date::translate($project->ABC, "php");?></td>
											<td class="center"><?php echo Date::translate($project->date_registered, "2");?></td>
											<td class="center"><?php echo Date::translate($project->implementation_date, "2");?></td>
											<td class="center"><?php echo $project->workflow;?></td>
											<td class="center"><?php echo $accomplishment;?>%</td>										
										</tr>


										<?php
											}
										}
										?>
									</tbody>
									
									<tfoot>
										<tr>
											<th>#</th>
											<th>Reference</th>
											<th>Title</th>
											<th>MOP</th>
											<th>ABC</th>
											<th>Date Registered</th>
											<th>Implementation</th>
											<th>Workflow</th>
											<th>Accomplishment</th>
										</tr>
									</tfoot>
									</table>
								</div>

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
	
	<!-- Report Scripts -->
	
	<!-- Morris -->
    <script src="../../assets/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="../../assets/js/plugins/morris/morris.js"></script>
    <!-- ChartJS-->
    <script src="../../assets/js/plugins/chartJs/Chart.min.js"></script>
   
	
	<!-- Data for pie -->
    <script>
		$(function() {

			<?php
			
				if($projects = $user->dashboardReports()){
					foreach($projects["current_projects"] as $project){
						$origin = json_decode($project->request_origin, true);

						$prCounter = 0;
						$joCounter = 0;

						foreach($origin as $list){
							$identifier = substr($list, 0, 2);

							switch ($identifier) {
								case "PR":
									$prCounter++;
									break;
							
								case "JO":
									$joCounter++;
									break;
							}

						}

						if(($prCounter > 0) AND ($joCounter == 0)){
							#pure pr
							$prArray[] = $project;
						}else if(($joCounter > 0) AND ($prCounter == 0)){
							#pure jo
							$joArray[] = $project;
						}
					}

				}


				

				echo count($reports["current_projects_breakdown"]);
				

			?>
			
			Morris.Donut({
				element: 'morris-donut-chart',
				data: [
					{ label: "Purchase Request", value: <?php 
						if(isset($prArray)){
							echo count($prArray);
						}else{
							echo "0";
						}
					?> },
					{ label: "Job Orders", value: <?php
						if(isset($joArray)){
							echo count($joArray);
						}else{
							echo "0";
						}
					?> },
					{ label: "Mixed", value: <?php 
							$mixed = $user->getAll("projects", array("type", "=", "consolidated"));
							if($mixed){
								echo count($mixed);
							}else{
								echo "0";
							}
					?> } 
					],
				resize: true,
				colors: ['#f8ac59', '#1B6AA5','#b6325e'],
			});		
			
			
		});
	</script>
	
	
	<!--Mini Donut data-->
    <script>
        $(document).ready(function() {

			var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };

			// count available reports
			
			<?php
						if(isset($reports["current_projects_breakdown"])){
							$breakdown_count = count($reports["current_projects_breakdown"]);
							$x = 1;

							foreach ($reports["current_projects_breakdown"] as $key => $value) {
								
								$processing = 0;
								$finished = 0;
								$failed = 0;

								// loop through individual values and identify their status
								switch ($key) {
									case 'PR':

										foreach ($value as $individualProject) {
											
											switch ($individualProject->project_status) {
												case 'PROCESSING':
													$processing++;
													break;
												case 'PAUSED':
													$processing++;
													break;
												case 'FINISHED':
													$finished++;
													break;
												case 'FAILED':
													$failed++;
													break;	
											}
										}
										echo 'document.getElementById("doughnutLabel'.$x.'").innerHTML = "Purchase Request";';
										break;
									case 'JO':
										foreach ($value as $individualProject) {
											
											switch ($individualProject->project_status) {
												case 'PROCESSING':
													$processing++;
													break;
												case 'PAUSED':
													$processing++;
													break;
												case 'FINISHED':
													$finished++;
													break;
												case 'FAILED':
													$failed++;
													break;	
											}
										}									
										echo 'document.getElementById("doughnutLabel'.$x.'").innerHTML = "Job Order";';
										break;
									case 'MIXED':
										foreach ($value as $individualProject) {
											
											switch ($individualProject->project_status) {
												case 'PROCESSING':
													$processing++;
													break;
												case 'PAUSED':
													$processing++;
													break;
												case 'FINISHED':
													$finished++;
													break;
												case 'FAILED':
													$failed++;
													break;	
											}
										}									
										echo 'document.getElementById("doughnutLabel'.$x.'").innerHTML = "Mixed";';
										break;
								}
								echo'
								var doughnutData = {
									labels: ["Ongoing","Finished","Failed"],
									datasets: [{
										data: ['.$processing.','.$finished.','.$failed.'],
										backgroundColor: ["#65c7d0","#8CC63E","#ea3c14"]
									}]
								};
								var ctx4 = document.getElementById("doughnutChart'.$x.'").getContext("2d");
								new Chart(ctx4, {type: "doughnut", data: doughnutData, options:doughnutOptions});
							';

								$x++;
							}

							unset($processing);
							unset($paused);
							unset($finished);
							unset($failed);

						}
			?>			

        });
    </script>	
	
	<!-- Data for bar and line graph -->
	<script>
		$(function (){


			<?php

					$months = $user->success_ratio();

					foreach ($months as $month) {
						$registered[] = $month["registered"];
						$finished[] = $month["finished"];
						$failed[] = $month["failed"];
					}
					
			?>



			var barData = {
				labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
				datasets: [
					{
						label: "Registered Projects",
						borderColor: "rgb(0, 0, 0)",
						backgroundColor: '#65c7d0',
						pointBorderColor: "#fff",
						data: [<?php echo implode(", ",$registered);?>]
					},
					{
						label: "Projects Awarded",
						backgroundColor: '#8CC63E',
						borderColor: "rgb(0, 0, 0)",
						pointBackgroundColor: "rgba(26,179,148,1)",
						pointBorderColor: "#fff",
						data: [<?php echo implode(", ",$finished);?>]
					},
					{
						label: "Projects Failed",
						backgroundColor: '#ea3c14',
						borderColor: "rgb(0, 0, 0)",
						pointBackgroundColor: "rgba(26,179,148,1)",
						pointBorderColor: "#fff",
						data: [<?php echo implode(", ",$failed);?>]
					}
				]
			};

			var barOptions = {
				responsive: true
			};


			var ctx2 = document.getElementById("barChart").getContext("2d");
			new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});


		});	
	</script>
<script>
	$(function(){
		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1000);
	});
</script>

</body>

</html>
