<?php 
    require_once('../../core/init.php');
    $user = new Admin(); 
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
	}

	    
?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">

    <title>PrMO OPPTS | My Forms</title>
	<link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include_once '../../includes/parts/admin_styles.php'; ?>

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
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Forms Created</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>PR/JO Created</strong>
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
			<div class="row">
				<div class="col-lg-12">
					<div class="wrapper wrapper-content animated fadeInUp">

						<div class="row">
			
						
							<?php
							
								if(!isset($_GET['q'])){
								
							?>
				<div class="col-lg-12 animated fadeInRight">
					<div class="panel panel-default rem">
						<div class="panel-heading">
						   <i class="fa fa-info-circle side" style="color:black"></i> Guides and Legend
						</div>
						<div class="panel-body">
							 <ul class="list-group clear-list m-t">
								<li class="list-group-item">
									<span class="label" style="background:#66B92E; color:white">1</span> Forms with the green status indicator are request forms that are not yet submitted and received by the PrMO, therefore you have the full privilage to update or delete any listing on your request.
								</li>
								<li class="list-group-item">
									<span class="label" style="background:#DA932C; color:white">2</span> Forms with the orange status indicator are request forms that are already submitted and received by the PrMO or in the process of Technical Member evaluation, Therefore modifying of listing in this request are strictly monitored by our personnels, also modification on this request requires approval from our personnel for your modification/s to be applied whether it is your personal reason or modification required by the Technical Member.
								</li>
								<li class="list-group-item">
									<span class="label" style="background:#D65B4A; color:white">3</span> Forms with the red status indicator are request forms that are already passed the Technical Member evaluation, meaning you cannot manually modify any listing in your request. It is adviced to head to PrMO and state your concern with regards updating your request/s listing.
								</li>							
							 </ul>											
						</div>
					</div>
				</div>
                <div class="col-lg-12 animated fadeInLeft">
                    <div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5>All Forms Created</h5>
							<form id="delete-form" action="" method="POST" enctype="multipart/form-data">
								<input type="text" name="to-delete-token" value="<?php echo Token::generate('to-delete-token');?>" hidden>
							</form>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-5 m-b-xs"><select class="form-control-sm form-control input-s-sm inline">
                                    <option value="0">Purchase Requets</option>
                                    <option value="1">Job Orders</option>
                                </select>
                                </div>
                                <div class="col-sm-4 m-b-xs">
									<!--Middle Collumn-->
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group"><input placeholder="Search" id="filter" type="text" class="form-control form-control-sm"> <span class="input-group-append"> <button type="button" class="btn btn-sm btn-primary">Go!
                                    </button> </span></div>

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="footable table table-striped " data-filter=#filter>
                                    <thead>
                                    <tr>

                                        <th>no. </th>
                                        <th>Reference </th>
										<th>Linked to </th>
                                        <th>Title </th>
										<th class="center">Status </th>
                                        <th>Date Created</th>
										<th style="text-align:center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php 
										$myRequests = $user->getAll('project_request_forms', array('requested_by', '=', Session::get(Config::get('session/session_name'))));
										// echo "<pre>",print_r($myRequests),"</pre>";
										if($myRequests){
										foreach($myRequests as $request){
											if(isset($count)){$count++;}else{$count=1;}
												
											if($request->status == "unreceived"){
												$displayStatus = '<span class="status-text status-green">Unreceived</span>';
												$actionButton  = '<div class="btn-group">
																	 <a href="my-forms?q='.base64_encode($request->form_ref_no).'" class="btn btn-info btn-sm btn-rounded btn-outline" style="color:black">Details</a>
																	 <button name="form-to-delete" value="'.$request->form_ref_no.'" form="delete-form" class="btn btn-warning btn-sm btn-rounded btn-outline" style="color:black">Delete</button>
																 </div>';

											}else{
												if($user->isEvaluated($request->form_ref_no)){
													$displayStatus = '<span class="status-text status-red">Evaluation Passed</span>';
													$actionButton = '<a href="my-forms?q='.base64_encode($request->form_ref_no).'" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a>';
												}else{
													$displayStatus = '<span class="status-text status-orange">Received</span>';
													$actionButton = '<a href="my-forms?q='.base64_encode($request->form_ref_no).'" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a>';
												}
												
											}

												//check if this is already a project and related to what project reference
												$isProject = $user->isProject("projects", "request_origin", $request->form_ref_no);
												
												if($isProject) {
													$relatedTo = $isProject->project_ref_no;
												}else{
													$relatedTo = '<a style="color:red">NA</a>';
													
												}											

									?>
										<tr>
											<td><?php echo $count;?></td>
											<td><?php echo $request->form_ref_no;?></td>
											<td><?php echo $relatedTo;?></td>
											<td style="max-width:300px"><?php echo $request->title;?></td>
											<td class="status left"><?php echo $displayStatus;?></td>
											<td><?php echo Date::translate($request->date_created, 2);?></td>
											<td class="center"><?php echo $actionButton;?></td>
										</tr>		

									<?php 
										}
									}else{
										echo '<td colspan="7" style="text-align:center">No Data Available</td>';
									}
									?>
                
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
							
							
							<?php
								
							}else{
								
								$refno = base64_decode($_GET['q']);
								$admin = new Admin();
								$valid_request = $admin->selectAll("project_request_forms");
			
								$valid = false;
			
								foreach ($valid_request as $request){
									if($refno == $request->form_ref_no){
										$valid = true;
										$status = $request->status;
									}
								}
				
								if(!$valid){
									include('../../includes/errors/404.php');
									exit();						
								}
								$type = substr($refno, 0, 2);
								if($type == "PR"){
							?>

							<?php
								$user = new User();
								$numberOfLots = $user->numberOfLots($refno);
								$lots =  $numberOfLots->numberOfLots;
								$transition =  "animated fadeInLeft";
								
								$formInfo =  $user->get('project_request_forms', array('form_ref_no', '=', $refno));
								$isProject = $user->isProject('projects', 'request_origin', $refno);
								if($isProject){
									$accomplishment = number_format(($isProject->accomplished / $isProject->steps) * 100, 1);
									$addionalContent = '
										<h4 class="text-left" style="color: #F37123">Registered as a project</h4>
									
									<p style="margin-left:20px"><u><b><a href="project-details?refno='.base64_encode($isProject->project_ref_no).'">'.$isProject->project_ref_no.' - '.$isProject->project_title.'</a></b></u></p>
										<h4 class="text-left" style="color: #F37123">Accomplishment</h4>
											<small>'.$accomplishment.'%</small>
											<div class="progress progress-mini">
												<div style="width:'.$accomplishment.'%;" class="progress-bar"></div>
											</div><p><b>'.$isProject->workflow.'</b></p>
											<br>
											<button onclick="window.open(\'../../bac/forms/project-request?id='.$refno.'\');" type="button" class="btn btn-primary btn-rounded btn-sm">
												<i class="fa fa-download"></i> Download Form
											</button>

									';
								}else{
									$addionalContent = '';
								}
								
								$lotCosts = $user->getAll('lots', array('request_origin', '=', $refno));
								$infoTotalLotCost = 0;
								foreach($lotCosts as $cost){
									$infoTotalLotCost += $cost->lot_cost;
								}
								
								echo '
									<div class="col-lg-12">
										<div class="panel panel-info">
											<div class="panel-heading">
												<i class="fa fa-info-circle"></i> Info Panel
												<a href ="my-forms" class="btn btn-default btn-rounded  btn-xs pull-right" style="color:black"><i class="ti-angle-double-left"></i> Back to My Forms</a>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-6 b-r"> 
														<h4 class="text-left" style="color: #F37123">Form Reference</h4>
															<p style="margin-left:20px;"><b>'.$refno.'</b></p>
														<h4 class="text-left" style="color: #F37123">Status</h4>
															<p style="margin-left:20px"><b>'.strtoupper($formInfo->status).'</b></p>
														<h4 class="text-left" style="color: #F37123">Total Request Cost</h4>
															<h3 style="margin-left:20px"><i>&#x20b1; '.number_format($infoTotalLotCost ,2).'</i></h3>		
													</div>
													<div class="col-sm-6">
													'.$addionalContent.'
													</div>
												</div>
									
												
											</div>

										</div>
									</div>								
								';
								for($x = 0; $x<$lots; $x++){
									if(($numberOfLots->numberOfLots == 1) && ($numberOfLots->lot_no == '101')){
										$currentLot = '101';
										$showLot = 'No lot specified';
									}else{
										$currentLot =$x+1;
										$showLot = "Lot ".$currentLot;
									}
									$transition = ($transition == "animated fadeInRight") ? $transition = "animated fadeInLeft" : $transition = "animated fadeInRight";
									$content = $user->getContent($refno, $type, $currentLot);
									$limiter = '';
							?>

							<div class="col-lg-12 <?php echo $transition?>">
								<div class="ibox myShadow">
									<div class="ibox-title">
										<h5><?php echo $showLot, " - ", $content[0]->lot_title;?></h5>
									</div>
									<div class="ibox-content">
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
												//echo "<pre>",print_r($content),"</pre>";
												$showTotalLotCost = 0;
												foreach($content as $detail){
													$item_details = [
														'lot' => $currentLot,
														'lot_id' => $detail->lot_id,
														'item_id' => $detail->ID,
														'stock_no' => $detail->stock_no,
														'unit' => $detail->unit,
														'desc' => $detail->item_description,
														'qty' => $detail->quantity,
														'uCost' => $detail->unit_cost,
														'tCost' => $detail->total_cost
													];
													
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
														<td colspan="7" style="text-align:center; background-color:#4ac24a; color:black">Total Lot Cost</td>
														<td style="background-color:#fa4711; color:white">&#x20b1; '.number_format($showTotalLotCost,2).'</td>
													</tr>';
											?>
											</tbody>
										</table>
										

									</div>
								</div>
							</div>


							<?php
									}
								}else if($type == "JO"){
									
									
									$user = new User();
									$numberOfLots = $user->numberOfLots($refno);
									$lots =  $numberOfLots->numberOfLots;
									$transition =  "animated fadeInLeft";

								$formInfo =  $user->get('project_request_forms', array('form_ref_no', '=', $refno));
								$isProject = $user->isProject('projects', 'request_origin', $refno);
								if($isProject){
									$accomplishment = number_format(($isProject->accomplished / $isProject->steps) * 100, 1);
									$addionalContent = '
										<h4 class="text-left" style="color: #F37123">Registered as a project</h4>
											<p style="margin-left:20px"><u><b><a href="project-details?refno='.base64_encode($isProject->project_ref_no).'">'.$isProject->project_ref_no.' - '.$isProject->project_title.'</a></b></u></p>
										<h4 class="text-left" style="color: #F37123">Accomplishment</h4>
											<small>'.$accomplishment.'%</small>
											<div class="progress progress-mini">
												<div style="width:'.$accomplishment.'%;" class="progress-bar"></div>
											</div><p><b>'.$isProject->workflow.'</b></p>
											<br>
											<button onclick="window.open(\'../../bac/forms/project-request?id='.$refno.'\');" type="button" class="btn btn-primary btn-rounded btn-sm">
												<i class="fa fa-download"></i> Download Form
											</button>
									';
								}else{
									$addionalContent = '';
								}
								
								$lotCosts = $user->getAll('lots', array('request_origin', '=', $refno));
								$infoTotalLotCost = 0;
								foreach($lotCosts as $cost){
									$infoTotalLotCost += $cost->lot_cost;
								}
								
								echo '
									<div class="col-lg-12">
										<div class="panel panel-info">
											<div class="panel-heading">
												<i class="fa fa-info-circle"></i> Info Panel
												<a href ="my-forms" class="btn btn-default btn-rounded  btn-xs pull-right" style="color:black"><i class="ti-angle-double-left"></i> Back to My Forms</a>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-6 b-r"> 
														<h4 class="text-left" style="color: #F37123">Form Reference</h4>
															<p style="margin-left:20px;"><b>'.$refno.'</b></p>
														<h4 class="text-left" style="color: #F37123">Status</h4>
															<p style="margin-left:20px"><b>'.strtoupper($formInfo->status).'</b></p>
														<h4 class="text-left" style="color: #F37123">Total Request Cost</h4>
															<h3 style="margin-left:20px"><i>&#x20b1; '.number_format($infoTotalLotCost ,2).'</i></h3>		
													</div>
													<div class="col-sm-6">
													'.$addionalContent.'
													</div>
												</div>
									
												
											</div>

										</div>
									</div>								
								';									
									
									for($x = 0; $x<$lots; $x++){
										$currentLot = $x + 1;
										$content = $user->getContent($refno, $type, $currentLot);
										$transition = ($transition == "animated fadeInRight") ? $transition = "animated fadeInLeft" : $transition = "animated fadeInRight";
										
									
							?>

								<div class="col-lg-12 <?php echo $transition;?>">
									<div class="ibox myShadow">
										<div class="ibox-title">
											<h5>Lot <?php echo $currentLot;?> - <?php echo $content[0]->lot_title;?></h5>

										</div>
										<div class="ibox-content">
											<div class="row">
												<div class="col-lg-4">
													<h4 class="text-left" style="color: #F37123">Lot Estimated Cost</h4>
													<div class="widget style1 lazur-bg">
														<div class="row vertical-align">
															<div class="col-1">												
																<a class="fa fa-3x" style="font-weight:400; color:#3f4141">&#x20b1;</a>
															</div>
															<div class="col-10">
																<h2 class="font-bold" style="color:#3f4141"><?php echo number_format($content[0]->lot_cost,2);?></h2>
															</div>
														</div>
													</div>
												</div>	
												<div class="col-lg-8">
													<h4 class="text-left" style="color: #F37123">Lot Notes</h4>
													<div class="widget style1 yellow-bg">
														<div class="row vertical-align">
															<div class="col-1">												
																<i class="ti-bookmark-alt fa-3x" style="color:#"></i>
															</div>
															<div class="col-10">
																<p style="color:#3f4141; font-size:15px"><i>"<?php echo $content[0]->note;?>".</i></p>
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
														foreach($content as $detail){
															$item_details = [
																'lot' => $currentLot,
																'lot_id' => $detail->lot_id,
																'item_id' => $detail->ID,
																'header' => $detail->header,
																'lot_cost' => $detail->lot_cost,
																'tags' => str_replace(",", ", ", $detail->tags),
																'notes' => $detail->note
															];
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

										</div>
									</div>
								</div>

							<?php
									}
								}
								
								if($user->isEvaluated($refno)){
									echo'

									
									';
								}else{
								
								}

							}
							?>

						</div>
					</div>
				</div>
			</div><br><br><br><br><br>

			<!-- Main Content End -->
			<button class="back-to-top" type="button"></button>
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once '../../includes/parts/admin_scripts.php'; ?>

</body>
</html>