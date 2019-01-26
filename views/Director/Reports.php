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

    <title>PrMO OPPTS | Reports</title>

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
						<h2>Projects Classification<h2>
						<div id="morris-donut-chart" ></div>
					</div>
					
					<div class="col-lg-8">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Projects Success Ratio</h5>
							</div>
							<div class="ibox-content">
								<div>
									<canvas id="barChart" height="140"></canvas>
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
			
			Morris.Donut({
				element: 'morris-donut-chart',
				data: [
					{ label: "Purchase Request", value: 12 },
					{ label: "Job Orders", value: 30 },
					{ label: "Mixed", value: 20 } 
					],
				resize: true,
				colors: ['#f8ac59', '#23c6c8','#b6325e'],
			});		
			
			
		});
	</script>
	
	<!-- Data for bar and line graph -->
	<script>
		$(function () {



			var barData = {
				labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
				datasets: [
					{
						label: "Registered Projects",
						borderColor: "rgb(0, 0, 0)",
						backgroundColor: 'rgb(0, 153, 255)',
						pointBorderColor: "#fff",
						data: [65, 59, 80, 81, 56, 55, 40, 80, 360]
					},
					{
						label: "Projects Awarded",
						backgroundColor: 'rgb(57, 230, 0)',
						borderColor: "rgb(0, 0, 0)",
						pointBackgroundColor: "rgba(26,179,148,1)",
						pointBorderColor: "#fff",
						data: [28, 48, 40, 19, 86, 27, 90, 80, 81, 56, 55, 40]
					},
					{
						label: "Projects Failed",
						backgroundColor: 'rgb(255, 71, 26)',
						borderColor: "rgb(0, 0, 0)",
						pointBackgroundColor: "rgba(26,179,148,1)",
						pointBorderColor: "#fff",
						data: [28, 48, 40, 19, 86, 27, 90, 80, 81, 56, 55, 40]
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
