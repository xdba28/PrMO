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

    <title>PrMO OPPTS | Revision Requests</title>

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
                <div class="col-sm-4">
                    <h2>Revision Requests</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Revision Requests</strong>
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
            <div class="wrapper wrapper-content animated fadeInUp">
			
				<?php
					if(!isset($_GET['q'])){
						
						$allUpdateRequests = $user->selectAll('form_update_requests');
					
				?>
				<div class="row">
					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Received Purchase requests and Job Order forms</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content">
							
							
								<div class="table-responsive">
									<input type="text" class="form-control form-control-sm m-b-xs" id="filter" placeholder="Search in table">
									<table class="footable table table-stripped toggle-arrow-tiny" data-filter=#filter>
									
										<thead>
											<tr>

												<th data-toggle="true">#</th>
												<th>Requested by</th>
												<th>Reference No.</th>
												<th>Date Requested</th>
												<th>Requested Action</th>
													<th data-hide="all">Current Workflow</th>
													<th data-hide="all">Completion</th>
													<th data-hide="all">Purpose</th>
													<th data-hide="all">Project Origin</th>
													<th data-hide="all"><i>My Response</i></th>
												<th style="text-align:center">Action</th>
											</tr>
										</thead>
										
										<tbody>
										
											<?php
												foreach($allUpdateRequests as $request){
													if(isset($count)){$count++;}else{$count=1;}
														$projectDetail = $user->like('projects', 'request_origin', $request->form_origin);
														
													if($projectDetail){
														$displayProject = ''.$projectDetail->project_ref_no.' - <a style="color:#F37123">"'.$projectDetail->project_title.'"</a>';
														$workflow = $projectDetail->workflow;
														$accomplishment = number_format(($projectDetail->accomplished / $projectDetail->steps) * 100, 1);
														$completion = '
																<small>'.$accomplishment.'%</small>
																<div class="progress progress-mini">
																	<div style="width:'.$accomplishment.'%;" class="progress-bar"></div>
																</div>';
														
													}else{
														$displayProject = '<a style="color:black">Received but Unregistered as a Project</a>';
														$workflow = 'NA';
														$completion = 'NA';
													}
													
													if($request->action == "delete"){$color="red";}else{$color="green";}
													
													
											?>
								                                      
													<tr>
														<td><?php echo $count;?></td>
														<td><?php echo $user->fullnameOfEnduser($request->requested_by);?></td>
														<td style="color:#009bdf"><?php echo $request->form_origin;?></td>
														<td><?php echo Date::translate($request->date_registered, 1);?></td>
														<td style="color:<?php echo $color;?>"><?php echo strtoupper($request->action);?></td>
															<td><?php echo $workflow;?></td>
															<td><?php echo $completion;?></td>											
															<td><?php echo $request->purpose;?></td>
															<td><b><?php echo $displayProject;?></b></td>
															<td><?php echo $request->response;?></td>
														<td style="text-align:center">
															<a href="?q=<?php echo $request->ID;?>" class="btn btn-info btn-outline btn-sm" style="color:black"><i class="ti-layers-alt"></i> view changes </a>								
														</td>
													</tr>
											<?php
												}
											?>
													
												
												
										</tbody>
										
										<tfoot>
											<tr>
												<td colspan="6">
													<ul class="pagination float-right"></ul>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>							

							</div>
						</div>
					</div>
				</div>
				<?php
				}else{
					$reference =  base64_decode($_GET['q']);

					$request = $user->get('form_update_requests', array('ID', '=', $reference));

					if($request){
						//echo "<pre>",print_r($request),"</pre>";
						if($request->original_data === "NA"){
							$updateData =  json_decode($request->update_data);
						}else{
							$originalData = json_decode($request->original_data);
							$updateData =  json_decode($request->update_data);
						}
															$count = 0;
															foreach($updateData->items as $item){
																$count++;
															}
															
															if($request->action == "update"){
																$action = "updated";
															}else{
																$action = "deleted";
															}
															if($count > 1){
																$heading = "Items listed below are requested to be ".$action." with the following reason.";
															}else{
																$heading = "Item listed below is requested to be ".$action." with the following reason.";
															}
							
							//echo "<pre>",print_r($request),"</pre>";
							//echo "<pre>",print_r($originalData),"</pre>";
							//echo "<pre>",print_r($updateData),"</pre>";

				?>

				<div class="row">
					<div class="col-lg-12 animated fadeInRight">
						<?php
							if($request->action === "update"){

						?>
							<!-- update content -->
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Update Request for item/s of <a style="color:#009bdf"><?php echo $request->form_origin;?></a></h5>
								<div class="ibox-tools">
									<a href ="Revision-requests" class="btn btn-info btn-rounded btn-outline btn-xs" style="color:black"><i class="ti-angle-double-left"></i> Back to Requests</a>
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
									<a class="fullscreen-link">
										<i class="fa fa-expand"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content">
								<h2><?php echo $heading;?></h2>
								<input type="text" disabled="" placeholder="<?php echo $request->purpose;?>" class="form-control"><br>
								
								<?php
									$type = substr($request->form_origin, 0,2);
									
									if($type === "PR"){
										
									
									?>
									<h4 style="color: #F37123">Original Data</h4>
									<table class="table">
										<thead>
											<tr>
												<th style="text-align:center">#</th>
												<th>Item no.</th>
												<th>From lot</th>
												<th>Stock no.</th>
												<th>Unit</th>
												<th>Description</th>
												<th>Qty</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>											
											</tr>
										</thead>
										<tbody>
										
											<?php
												$counter = 0;
												foreach($originalData as $item){
													$counter++;
													$contents = $user->getContent($request->form_origin, $type, $item->lot);
													$lotInfo = $user->get('lots', array('lot_id', '=', $item->lot_id));
													
														//compare the $item->id to $content to know what item no this $item is
														foreach($contents as $key => $content){
															if($item->item_id == $content->ID){
																$form_item_no = $key + 1;
															}
														}	
													
													echo '
														<tr>
															<td style="background-color:green; color:white; text-align:center; width:40px">'.$counter.'</td>
															<td>'.$form_item_no.'</td>
															<td>'.$item->lot.' - '.$lotInfo->lot_title.'</td>
															<td>'.$item->stock_no.'</td>
															<td>'.$item->unit.'</td>
															<td>'.$item->desc.'</td>
															<td>'.$item->qty.'</td>
															<td>'.$item->uCost.'</td>
															<td>'.$item->tCost.'</td>
															
														</tr>
													
													';
												}
											?>

										</tbody>
									</table><br>
									
									<h4 style="color: #F37123">Update Data</h4><a>(Red shaded numbers corresponds to Green shaded numbers.)</a>
									<table class="table">
										<thead>
											<tr>
												<th style="text-align:center">#</th>
												<th>Stock no.</th>
												<th>Unit</th>
												<th>Description</th>
												<th>Qty</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>											
											</tr>
										</thead>
										<tbody>
											<?php
												$counter = 0;
												foreach($updateData->items as $item){
													$counter++;
													

													
													echo '
														<tr>
															<td style="background-color:red; color:white; text-align:center; width:40px">'.$counter.'</td>
															<td>'.$item->stockNo.'</td>
															<td>'.$item->unit.'</td>
															<td>'.$item->description.'</td>
															<td>'.$item->quantity.'</td>
															<td>'.$item->unitCost.'</td>
															<td>'.$item->totalCost.'</td>
															
														</tr>
													
													';
												}
											?>
										</tbody>
									</table>									
								<?php
								}else if($type === "JO"){
									
								?>
									<h4 style="color: #F37123">Original Data</h4>
									<table id="original" class="table">
										<thead>
											<tr>
												<th style="text-align:center">#</th>
												<th>Item no.</th>
												<th>From lot</th>
												<th>List title</th>
												<th>Tags</th>
												<th>Lot Cost</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$counter = 0;
												foreach($originalData as $item){
													$contents = $user->getContent($request->form_origin, $type, $item->lot);
													$lotInfo = $user->get('lots', array('lot_id', '=', $item->lot_id));
													
														//compare the $item->id to $content to know what item no this $item is
														foreach($contents as $key => $content){
															if($item->item_id == $content->ID){
																$form_item_no = $key + 1;
															}
														}													
													
													$counter++;
													echo '
													<tr>
														<td style="background-color:green; color:white; text-align:center; width:40px">'.$counter.'</td>
														<td>'.$form_item_no.'</td>
														<td>'.$item->lot.' - '.$lotInfo->lot_title.'</td>
														<td>'.$item->header.'</td>
														<td>'.$item->tags.'</td>
														<td>'.$item->lot_cost.'</td>
													</tr>
													';
												}
											?>
										</tbody>
									</table><br>
									
									<h4 style="color: #F37123">Update Data</h4><a>(Red shaded numbers corresponds to Green shaded numbers.)</a>
									<table id="compareData" class="table">
										<thead>
											<tr>
												<th style="text-align:center">#</th>
												<th>List title</th>
												<th>Tags</th>							
											</tr>
										</thead>
										<tbody>
											<?php
												$counter = 0;
												foreach($updateData->items as $item){
																		
													
													$counter++;
													echo '
													<tr>
														<td style="background-color:red; color:white; text-align:center; width:40px">'.$counter.'</td>
														<td>'.$item->list.'</td>
														<td>'.$item->tags.'</td>
													</tr>
													';
												}
											?>
										</tbody>
									</table><br>
									
									<h4 style="color: #F37123">Update Data foreach affected lot/s</h4>
									<div id="updateCards" class="row">
										<?php
											$affectedLots = $updateData->affectedLots;
											foreach($affectedLots as $affectedLot){
												$dataParts = explode("blyt322", $affectedLot);
												$lotInfo = $user->get('lots', array('lot_id', '=', $dataParts[1]));
										?>
										<div class="col-lg-3">
											<div class="panel panel-info">
												<div class="panel-heading">
													Lot <?php echo $dataParts[0], " - ", $lotInfo->lot_title;?>
												</div>
												<div class="panel-body">								
														<h4 class="text-left" style="color: #F37123">Estimated Cost</h4>
															<p class="text-center" style="font-size:20px">&#8369; <?php echo number_format($dataParts[2], 2);?></p>
														<h4 class="text-left" style="color: #F37123">Lot Note</h4>
															<p class="text-left"><i>"<?php echo $dataParts[3];?>"</i></p>														
												</div>

											</div>
										</div>	
										<?php
											}
										?>
									</div>
								
								<?php
									
								}
								?>
								
								<div class="pull-right">
									<button type="button" action="click" class="btn btn-info btn-outline btn-rounded">Grant</button>
									<button type="button" action="click" class="btn btn-danger btn-outline btn-rounded">Decline</button>
								</div><br><br><br>
							</div>
						</div>							
						<?php
							}else if($request->action === "delete"){

						?>
						<!-- delete content -->
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Deletion Request for item/s of <a style="color:#009bdf"><?php echo $request->form_origin;?></a></h5>
								<div class="ibox-tools">
									<a href ="Revision-requests" class="btn btn-info btn-rounded btn-outline btn-xs" style="color:black"><i class="ti-angle-double-left"></i> Back to Requests</a>
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
									<a class="fullscreen-link">
										<i class="fa fa-expand"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content">
								<h2><?php echo $heading;?></h2>
								<input type="text" disabled="" placeholder="<?php echo $request->purpose;?>" class="form-control"><br>
								
								<?php
									if($updateData->type === "PR"){
									
								?>
									<table class="table">
										<thead>
										<tr>
											<th>#</th>
											<th>Item no.</th>
											<th>From lot</th>
											<th>Stock no.</th>
											<th>Unit</th>
											<th>Description</th>
											<th>Qty</th>
											<th>Unit Cost</th>
											<th>Total Cost</th>											
										</tr>
										</thead>
										<tbody>
											<?php
												$counter = 0;
												foreach($updateData->items as $item){
													$contents = $user->getContent($request->form_origin, $updateData->type, $item->lot);
													
														//compare the $item->id to $content to know what item no this $item is
														foreach($contents as $key => $content){
															if($item->item_id == $content->ID){
																$form_item_no = $key + 1;
															}
														}
													
													
													$counter++;
													echo '
														<tr>
															<td>'.$counter.'</td>
															<td>'.$form_item_no.'</td>
															<td>'.$item->lot.'</td>
															<td>'.$item->stock_no.'</td>
															<td>'.$item->unit.'</td>
															<td>'.$item->desc.'</td>
															<td>'.$item->qty.'</td>
															<td>'.$item->uCost.'</td>
															<td>'.$item->tCost.'</td>
														</tr>
													';
												}
											?>
										</tbody>
									</table>
								<?php
								}else{
									
								?>
									
									<table class="table">
										<thead>
											<tr>
												<th>#</th>
												<th>Item no.</th>
												<th>From lot</th>
												<th>List title</th>
												<th>Tags</th>							
											</tr>
										</thead>
										<tbody>
											<?php
												$counter = 0;
												foreach($updateData->items as $item){
													$contents = $user->getContent($request->form_origin, $updateData->type, $item->lot);

														//compare the $item->id to $content to know what item no this $item is
														foreach($contents as $key => $content){
															if($item->item_id == $content->ID){
																$form_item_no = $key + 1;
															}
														}
													
													
													$counter++;
													echo '
														<tr>
															<td>'.$counter.'</td>
															<td>'.$form_item_no.'</td>
															<td>'.$item->lot.'</td>
															<td>'.$item->header.'</td>
															<td>'.$item->tags.'</td>
														</tr>
													';
												}
											?>
										</tbody>
									</table>

								<?php
									
								}
								?>
								
								<div class="pull-right">
									<button type="button" action="click" class="btn btn-info btn-outline btn-rounded">Grant</button>
									<button type="button" action="click" class="btn btn-danger btn-outline btn-rounded">Decline</button>
								</div><br><br><br>
							</div>
						</div>
						<?php
							}
						?>

					</div>
				</div>

				<?php
					}else{
						include('../../includes/errors/404.php');
						echo"<br><br><br><br><br><br>";
					}
				}
				?>
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
<script>
	$(function(){
		var request_id = '<?php
			if(isset($_GET['q'])){
				echo $_GET['q'];
			}else{
				echo "";
			}
		?>';
		$('[action="click"]').on('click', function(){
			let innertext = $(this).text();

			if(innertext !== "Decline"){
				SendDoSomething("POST", 'xhr-revision-action.php', {
					request: request_id,
					action: innertext
				}, {
					do: function(res){
						swal({
							title: `Successfully ${innertext}`,
							text: "",
							type: "success"
						});
					}
				});
			}else{
				sweet({
					title: 'Reason for edit decline',
					type: "info",
					showCancelButton: true,
					confirmButtonText: "Submit",
					allowOutsideClick: false,
					html: '<input type="text" class="form-control" name="edit-decline">',
					focusConfirm: false,
					preConfirm: function(){
						return document.querySelector('[name="edit-decline"]').value;
					}
				}, {
					do: function(res){
						if(res.dismiss === "cancel"){
							swal({
								title: "Action dismissed.",
								text: "",
								type: "info"
							});
						}else if(res.value !== "undefined"){
							SendDoSomething("POST", 'xhr-revision-action.php', {
								request: request_id,
								action: innertext,
								remark: escapeHtml(res.value)
							}, {
								do: function(d){
									swal({
										title: `Successfully ${innertext}`,
										text: "",
										type: "success"
									});
								}
							});
						}
					}
				});
			}

		});

	});// doc ready end
</script>
</html>
