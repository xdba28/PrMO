<?php 

    require_once('../../core/init.php');
	// point na to
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
            <div class="wrapper wrapper-content animated fadeInUp" >
				<div class="row" id="step1">
				
					<?php
						if(isset($_GET['q'])){
							$refno = $_GET['q'];
							
							$project = $user->get('projects', array('project_ref_no', '=', $refno));
							
							if($project){
								
								$projectDetails = $user->projectDetails($refno);
									
					?>
					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Content of project <a style="color:#009bdf"><?php echo $refno;?></a> "<a style="color:#F37123"><?php echo $project->project_title;?></a>".</h5>
							</div>
							<div class="ibox-content"><button data-toggle="modal" data-target="#summary">try</button>
								<h2><a style="color:#2a9c97">Step 1 of  &nbsp3</a><br>Items Selection</h2>
								<div class="row">
									<div class="col-sm-9">
										<div class="alert alert-danger">
											Choose from the following items below you wish to be resorted for canvass. 
											<br><code>Note: Refer from the DBMPS checklist if applicable.</code>
										</div>										
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label style="color:red">Number of Canvass forms needed *</label>
											<input type="number" min="1" id="canvassCount" placeholder="Important" class="form-control">
										</div>								
									</div>
							
								</div>
								<?php
								foreach($projectDetails as $details){
									if(!isset($animation)){$animation = "animated fadeInRight";}else{$animation = ($animation == "animated fadeInRight") ? $animation = "animated fadeInLeft" : $animation = "animated fadeInRight";}

										if($details['type'] === "PR"){
								?>		
								
										<h5 style="margin-bottom:10px">Content of <a style="color:#009bdf"><?php echo $details['req_origin'];?></a></h5>
										<div class="table-responsive">
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
														<input type="checkbox" class="i-checks" mop="<?php echo $details['type'].'-'.$lot['l_id'].'-'.$lotContent['id'] ?>" data='<?php echo $details['type']."{|}".$lot['l_title']."{|}".json_encode($lotContent);?>'>
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
									</div>
										
									<?php
										}else{
										?>
											<h5 style="margin-bottom:10px">Content of <a style="color:#009bdf"><?php echo $details['req_origin'];?></a></h5>
											<div class="table-responsive">
												<table class="table table-bordered table-stripped toggle-arrow-tiny">
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
																	<input type="checkbox" class="i-checks" mop="<?php echo $details['type'].'-'.$lot['l_id'].'-'.$lotContent['id'] ?>" data='<?php echo $details['type']."{|}".$lot['l_title']."{|}".json_encode($lotContent)."{|}".$lot['l_cost'];?>'>
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
											</div>
									<?php
										}
									}
									?>
									
							</div>
							<div class="ibox-footer col-lg-12">
							<div class="row">
								<div class="col-lg-10">
									Note that this is a raw arrangement by the enduser. You can resort, merge items from different lots to a one lot, set item for single canvass, or rearrange items for specific canvass in the next step.
								</div>
								<div class="col-lg-2">
									<span class="pull-right">
										<button type="button" id="bResort" class="btn btn-rounded btn-primary">Proceed <i class="ti-angle-double-right"></i></button>
									</span>
								</div>	
							</div>								
							</div>
						</div>
					</div>

					<?php
								
							}else{
								include('../../includes/errors/404.php');
								echo"<br><br><br><br><br><br>";
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
				
				<div id="step2" class="row animated fadeInUp" style="display:none">
					<div class="col-lg-10">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Items rearrangement for canvass of project <a style="color:#009bdf"><?php echo $refno;?></a> "<a style="color:#F37123"><?php echo $project->project_title;?></a>".</h5>
							</div>
							<form method="POST" action="">
							<div class="ibox-content">
								<div class="row">
									<div class="col-md-9">
										<h2><a style="color:#2a9c97">Step 2 of  &nbsp3</a><br>Items Resorting</h2>
									</div>
									<div class="col-md-3">
										<div class="btn-group pull-right" style="margin-right:15px">
											<button type="button" class="btn btn-info btn-rounded dropdown-toggle" data-toggle="dropdown"><i class="fas fa-chess-pawn" style="font-size: 1.7em; color:#514e4e"></i> <span>Actions / Options</span>&nbsp;&nbsp;</button>
											<ul class="dropdown-menu">
												<li><a class="dropdown-item" log="upd" action="1" href="#"><i class="fas fa-check green side"></i> Action 1</a></li>
												<li><a class="dropdown-item" log="upd" action="1" href="#"><i class="fas fa-check green side"></i> Action 2</a></li>
												<li><a class="dropdown-item" log="upd" action="1" href="#"><i class="fas fa-check green side"></i> Action 3 </a></li>
										</div>
									</div>									
								</div>
								<div id="pr-content">

								</div>

								<br>
								
								<div id="jo-content">

								</div>
							</div>
							<div class="ibox-footer col-lg-12">
								<span class="float-right">
									<button type="button" id="backbtn" class="btn btn-rounded btn-primary" style="display:none;"><i class="ti-angle-double-left"></i> Back</button>
									<button type="submit" class="btn btn-rounded btn-primary">Submit <i class="ti-angle-double-right"></i></button>
								</span>
								<div class="col-lg-10">
								In this part, You can now rearrange all selected items from part 1 to be canvassed. You can merge them in a single lot or rearrange it to single canvass per item.
								</div>
							</div>
							</form>
						</div>
					</div>
					<div class="col-lg-2" style="margin-top:-25px">
						<div class="ibox">
							<div class="">
								<h2>Canvass list</h2>
								<p  style="margin-top:-12px">Items being sorted is organized and grouped here.</p>
							</div>
							<div class="">
								<div id="c1" class="widget lazur-bg text-center">
									<h4>Canvass 1</h4>
									<div class="m-b-md">
										<h1 class="m-s">451226 items</h1>
										<small>Bicol feed delights catering services</small>
									</div>
								</div>
								<div class="widget yellow-bg text-center">
									<h4>Canvass 2</h4>
									<div class="m-b-md">
										<h1 class="m-s">456 items</h1>
										<small>Bicol feed delights catering services</small>
									</div>
								</div>	
								<div class="widget red-bg text-center">
									<div class="m-b-md">
										<h1 class="m-s">456 items</h1>
										<small>Bicol feed delights catering services</small>
									</div>
								</div>									
							</div>								
						</div>
					</div>
				</div><br><br>
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

		const mop_peritem = <?php 
			if($project->mop_peritem === NULL){
				echo "''";
			}else{
				echo $project->mop_peritem;
			}
		?>;

		console.log(mop_peritem);

		$("#test").click(function(){
			// shine
			$("#c1").addClass("shine-me");
			setTimeout(function(){
				$("#c1").removeClass("shine-me");
			}, 500);
		});

		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1000);
		
		$('#bResort').on('click', function(){
			let rItems = $('input[type="checkbox"]:checked');
			let canvassCount = $('#canvassCount').val();
			let moparray = [];

				let count = 0;
				let prT_header = false;
				let lotCounter = [];
				
				// $('#step1').attr('style', 'display:none;');
				// $('#step2').attr('style', '');
				$('#backbtn').attr('style', '');
				$('#pr-content').html('');
				$('#jo-content').html('');

				rItems.each(function(i, e){

					let mode = Object.keys(mop_peritem).find(function(e1){
						return mop_peritem[e1].find(function(e2){
							return e.getAttribute('mop') === e2
						});
					});

					if(typeof mode !== "undefined"){
						if(moparray.indexOf(mode) === -1){
							moparray.push(mode);
						}
					}

					let rDetails = e.getAttribute('data').split('{|}');
					let rSpec = JSON.parse(rDetails[2]);
					switch(rDetails[0]){
						case "PR":
							if(!prT_header){
								$('#pr-content').html(`<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>
									<th>Lot Origin</th><th style="text-align:center;">New Lot</th><th>Stock No</th>
									<th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th><th>Total Cost</th>
								</tr></thead><tbody id="pr-detail"></tbody></table></div><br>`);
								prT_header = true;
							}

							$('#pr-detail').append(`<tr>
								<td>${rDetails[1]}</td>
								<td>
								<select id="lot-${count}" name="new-lot-${count}" class="form-control">
									<option value="Common Office Supplies">Common Office Supplies</option>
									<option value="Paper Materials & Products">Paper Materials & Products</option>          
									<option value="Hardware Supplies">Hardware Supplies</option>
									<option value="Sporting Supplies">Sporting Supplies</option>
									<option value="Common Janitorial/Cleaning Supplies">Common Janitorial/Cleaning Supplies</option>
									<option value="ICT Supplies">ICT Supplies</option>
									<option value="Laboratory Supplies">Laboratory Supplies</option>
									<option value="Computer Supplies">Computer Supplies</option>
								</select>
								</td>
								<td>${rSpec.stock_no}</td>
								<td>${rSpec.unit}</td><td>${rSpec.desc}</td><td>${rSpec.qty}</td>
								<td>${rSpec.uCost}</td><td>${rSpec.tCost}</td>
								<input type="hidden" value='${JSON.stringify(rSpec)}' name="item_details-${count}">
									</tr>`);
							$(`#lot-${count}`).val(rDetails[1]);
							count++;
							break;
						case "JO":
							$('#jo-content').append(`<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>
								<th>Lot Origin</th><th style="text-align:center;">New Lot</th><th>List Title</th>
								<th>Tags</th><th>Lot Estimated Cost</th><th>Notes</th>
							</tr></thead><tbody id="jo-detail-${count}"></tbody></table></div><br>`);
							
							$(`#jo-detail-${count}`).append(`<tr><td>${rDetails[1]}</td>
								<td><input type="text" class="form-control"></td>
								<td>${rSpec.header}</td>
								<td>${rSpec.tags}</td>
								<td>${rDetails[3]}</td>
								<td><input type="hidden" value='${JSON.stringify(rSpec)}' name="item_details-${count}">
								</td></tr>`);
							$(`#lot-${count}`).val(rDetails[1]);
							count++;
							break;
						default:
							break;
					}
				});

			console.log(moparray);
			$('#MOPCount').text(moparray.length);
			$('#summary').modal('show');
		});

		$('#backbtn').on('click', function(){
			$('#step1').attr('style', '');
			$('#step2').attr('style', 'display:none');
			$(this).attr('style', 'display:none');
		});



	});
</script>
</html>
