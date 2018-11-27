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

    <title>PrMO OPPTS | Director</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/staff_side_nav.php'; ?>
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
                    <h2>Search Results</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Search</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Search Results</strong>
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
            <div class="wrapper wrapper-content ">
				<div class="row">
					<div class="col-lg-12 animated fadeInUp">
						<div class="ibox ">
							<div class="ibox-content">
								<?php 
									
									$searchResults = $user->searchProject(Input::get('q'));
									
									
									$resultCount = count($searchResults);
									if($resultCount < 2){
										$displayCount = $resultCount." result";
									}else{
										$displayCount = $resultCount." results";
									}
									
								
								?>							
								<h2>
									Showing <?php echo $displayCount;?> found for: <span class="text-navy">“<?php echo Input::get('q');?>”</span>
								</h2>
								<small>Request time  (0.23 seconds)</small>

								<div class="search-form">
									<form action="search-results" method="get">
										<div class="input-group">
											<input type="text" name="q" placeholder="Title, Keyword, Reference No." name="search" class="form-control form-control-lg">
											<div class="input-group-btn">
												<button class="btn btn-lg btn-primary" type="submit">
													Search
												</button>
											</div>
										</div>

									</form>
								</div>
								
							
								<?php 
									if($resultCount > 0){
										$transition =  "animated fadeInLeft";
										foreach($searchResults as $project){
											$transition = ($transition == "animated fadeInRight") ? $transition = "animated fadeInLeft" : $transition = "animated fadeInRight";
											
											
											$endusers =  json_decode($project->end_user, true);
											$endusersNameArray = array();
											
												foreach($endusers as $enduser){
													array_push($endusersNameArray, $user->fullnameOfEnduser($enduser));
												}
											
											$endusersDisplayName = implode(", ",$endusersNameArray);
											
											$logs = $user->getAll('project_logs', array('referencing_to', '=', $project->project_ref_no));
											$noOflogs = count($logs);
												
											$displayString = array();
											
												foreach($logs as $log){
													
													$pos = strpos($log->remarks, "^");
													if($pos === false){
														#push the log directly to display string
														array_push($displayString, $log->remarks);
													}else{
														$explodedLog = explode("^", $log->remarks);
														array_push($displayString, $explodedLog[2]);
													}
												}



								?>

								
								<div class="hr-line-dashed"></div>
								<div class="search-result <?php echo $transition;?>">
									<h3><a href="project-details?refno=<?php echo $project->project_ref_no;?>"><?php echo $project->project_title;?></a></h3>
									<a href="#" class="search-link"><?php echo$endusersDisplayName;?></a>
									<p>
										<?php echo $project->project_ref_no;?>
									</p>
									<strong>Latest Logs:</strong>

									<?php
										if($noOflogs < 2){
											echo '
												<p>
												• '.substr($displayString[$noOflogs-1], 0, 180).'
												</p>
											';

										}else{
											echo '
												<p>
												• '.substr($displayString[$noOflogs-1], 0, 180).'
												</p>
			
												<p>
												• '.substr($displayString[$noOflogs-2], 0, 180).'
												</p>											
											';
										}
									?>


								</div>
		
								
								<?php 
																			// echo "<pre>",print_r($displayString),"</pre>";
																			// echo $noOflogs. "logs read";
										}
									}else{
								?>		
								<div class="hr-line-dashed"></div>
								<h1 style="padding-left:10px"><i class="far fa-frown-open"></i> No Items Found.</h1>
								
								<?php 
									}
								?>									
								
								
								
								<div class="hr-line-dashed"></div>
								
							</div>
						</div>
					</div>
				</div>
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

    <?php include '../../includes/parts/admin_scripts.php'; ?>



</body>

</html>
