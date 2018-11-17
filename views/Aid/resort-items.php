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

    <title>PrMO OPPTS | Resorting Items</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/aid_side_nav.php'; ?>
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
                <div class="col-sm-6">
                    <h2>Procurement Aid Dashboard</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">Ongoing Projects</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">Actions</a>
                        </li>						
                        <li class="breadcrumb-item active">
                            <strong>Resort Items</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <div class="title-action">
                    <a href="Dashboard" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content animated fadeInUp">
				<div id="step1" class="row">
				
					<?php
						if(isset($_GET['q'])){
							$refno = $_GET['q'];
							
							$project = $user->get('projects', array('project_ref_no', '=', $refno));
							
							if($project){
								
								$projectDetails = $user->projectDetails($refno);
								//echo "<pre>",print_r($projectDetails),"</pre>";
									
					?>
					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Content of project <a style="color:#009bdf"><?php echo $refno;?></a> "<a style="color:#F37123"><?php echo $project->project_title;?></a>".</h5>
							</div>
							<div class="ibox-content">
								<h2><a style="color:#2a9c97">Step 1 of  &nbsp3</a><br>Items Selection</h2>
								<div class="alert alert-danger">
									Choose from the following items below you wish to be resorted for canvass. 
									<br><code>Note: Refer from the DBMPS checklist if applicable.</code>
								</div>
								<?php
								foreach($projectDetails as $details){
									if(!isset($animation)){$animation = "animated fadeInRight";}else{$animation = ($animation == "animated fadeInRight") ? $animation = "animated fadeInLeft" : $animation = "animated fadeInRight";}

										if($details['type'] === "PR"){
								?>		
								
										<h5 style="margin-bottom:10px">Content of <a style="color:#009bdf"><?php echo $details['req_origin'];?></a></h5>
										<table class="table table-bordered table-hover <?php echo $animation;?>">
											<thead>
											<tr>
												<th>Select</th>
												<th>Item</th>
												<th>From lot</th>
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
												$lotCount = 0;
												//outer loop
												foreach($details['lots'] as $lot){
													$lotCount++;
													$itemCount = 0;
													//inner loop
													foreach($lot['lot_items'] as $lotContent){
														$itemCount++;
											
											?>

												<tr>
													<td style="text-align:center;">
														<input type="checkbox" class="i-checks">
													</td>
													<td><?php echo $itemCount;?></td>
													<td>
														<?php 
															if($lot['l_title'] === "static lot"){
																echo "<code>no lot</code>";
															}else{
																echo $lotCount, " - ", $lot['l_title'];
															}
														?>
													</td>
													<td><?php echo $lotContent['stock_no'];?></td>
													<td><?php echo $lotContent['unit'];?></td>
													<td class="tddescription"><?php echo $lotContent['desc'];?></td>
													<td><?php echo $lotContent['qty'];?></td>
													<td><?php echo $lotContent['uCost'];?></td>
													<td><?php echo $lotContent['tCost'];?></td>
												</tr>
												
											<?php
													}
													//inner loop end
												}
												//outer loop end
											
											?>
				
											</tbody>
										</table>
										
									<?php
										}else{
										?>
											<h5 style="margin-bottom:10px">Content of <a style="color:#009bdf"><?php echo $details['req_origin'];?></a></h5>
											<table class="footable table table-bordered table-stripped toggle-arrow-tiny">
												<thead>
												<tr>
													<th>Select</th>
													<th>Item</th>
													<th>From lot</th>													
													<th>List Title</th>
													<th>Tags</th>
													<th>Lot Estimated Cost</th>												
													<th>Notes</th>
												</tr>
												</thead>
												<tbody>

												<?php
													$lotCount = 0;
													//outer loop
													foreach($details['lots'] as $lot){
														$lotCount++;
														$itemCount = 0;
														//inner loop
														foreach($lot['lot_items'] as $lotContent){
															$itemCount++;
												
												?>												
														<tr>
															<td style="text-align:center;">
																<input type="checkbox" class="i-checks" details=''>
															</td>
															<th><?php echo $itemCount;?></th>
															<th><?php echo $lotCount;?> - <?php echo $lot['l_title'];?></th>
															<td><?php echo $lotContent['header'];?></td>
															<td><?php echo $lotContent['tags'];?></td>
															<td><?php echo $lot['l_cost'];?></td>
															<td class="tddescription"><?php echo $lot['l_note'];?></td>
														</tr>
													
												<?php
														}
														//inner loop end
													}
													//outer loop end
												
												?>
												</tbody>
											</table>										
									<?php
										}
									}
									?>
									
							</div>
							<div class="ibox-footer col-lg-12">
								<span class="float-right">
									<button type="button" class="btn btn-rounded btn-primary">Proceed <i class="ti-angle-double-right"></i></button>
								</span>
								<div class="col-lg-10">
								Note that this is a raw arrangement by the enduser. You can resort, merge items from different lots to a one lot, set item for single canvass, or rearrange items for specific canvass in the next step.
								</div>
							</div>
						</div>
					</div>

					<?php
								
							}else{
								include('../../includes/errors/404.php');
							}
						}else{
					?>
					
					
					<h1>q Unset</h1>
					
					
					<?php
						}
					?>
		
				
				</div>
				
				<!-- denver! after this comment will be the second content or the step 2 after selecting the items to be resorted. also if the project has a pr and jo make them appear in 2 table. 1 table for all items in pr(sama sama na whether its from pr1 and pm2)
					but in the case of jo make every single jo to be separated from other jo.
					
					EG. project something has 2pr and 2 jo
					
						1. all selected items from pr 1 and 2 should appear in 1 table, just put some delimiter like the header "item" and "from lot" from the table above to avoid confusion.
						2. since we have 2 jo, make them in a separeted table. we cant combine them for it will massively confuse the aid. it is not as easy as the pr because it is just items unlike
						   here it is services, and we dont want to mess up here.
						3. in the end we will come up having 3 tables. 1 merged table for pr and 2 separate table for each JO.
						4. then proceed to your sorting magic. create canvass, like what we're doing in creation of jo we create lots but in this case it is canvass form we are creating.
						   then we can select items then put it to the created canvass.
						
				-->
				
				<div id="step2" class="row">
					<div class="col-lg-12">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Items rearrangement for canvass of project <a style="color:#009bdf"><?php echo $refno;?></a> "<a style="color:#F37123"><?php echo $project->project_title;?></a>".</h5>
							</div>
							<div class="ibox-content">
								<h2><a style="color:#2a9c97">Step 2 of  &nbsp3</a><br>Items Resorting</h2>

							</div>
							<div class="ibox-footer col-lg-12">
								<span class="float-right">
									<button type="button" class="btn btn-rounded btn-primary">Proceed <i class="ti-angle-double-right"></i></button>
								</span>
								<div class="col-lg-10">
								sample text for step 2 footer.
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
