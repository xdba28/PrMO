<?php 

    require_once('../../core/init.php');
    $user = new Admin(); 

    
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
	}

	if(!empty($_POST)){

		$range = range('A', 'Z');
		$canvassFormCount = 0;

		$canvass = json_decode($_POST['canvass'], true);

		$user->startTrans();

		foreach($canvass['forms'] as $form){

			$user->register('canvass_forms', array(
				'gds_reference' => $canvass['gds'],
				'type' => $form['type'],
				'form_count' => $range[$canvassFormCount]
			));

			$canvassForm = $user->get('canvass_forms', array('gds_reference', '=', $canvass['gds']));

			foreach($form['items'] as $item){

				if($form['type'] === 'PR'){

					$user->register('canvass_items_pr', array(
						'canvass_forms_id' => $canvassForm->id,
						'stock_no' => $item['details']['stock_no'],
						'unit' => $item['details']['unit'],
						'item_description' => $item['details']['desc'],
						'quantity' => $item['details']['qty'],
						'unit_cost' => $item['details']['uCost'],
						'total_cost' => $item['details']['tCost']
					));

				}elseif($form['type'] === 'JO'){
					
					$user->register('canvass_items_jo', array(
						'canvass_forms_id' => $canvassForm->id,
						'header' => $item['details']['header'],
						'tags' => $item['details']['tags']
					));

				}

			}

			$canvassFormCount++;
		}

		foreach($canvass['noMop'] as $mode){

			$user->register('publication', array(
				'gds_reference' => $canvass['gds'],
				'MOP' => $mode['mode']
			));

			$publicationID = $user->projectPublication($canvass['gds'], $mode['mode']);

			foreach($mode['lot_titles'] as $prop){

				$user->register('publication_lots', array(
					'publication_id' => $publicationID,
					'title' => $prop['title'],
					'cost' => $prop['cost']
				));

			}

		}

		// update steps
		
		//get the json file for step details
		$json = file_get_contents('../xhr-files/jsonsteps.json');
		//Decode JSON
		$stepsStructure = json_decode($json,true);

		$updateProject = $user->get('projects', array('project_ref_no', '=', $canvass['gds']));

		if($updateProject->mop_peritem === NULL){

			// get MOP and update the appropriate workflow and accomplishment

		}else{
	
			$user->update('projects', 'project_ref_no', $canvass['gds'], array(
				'accomplished' => '4',
				'workflow' => "Canvassing"
			));

		}

		$user->endTrans();

		die();

	}
   

?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Resorting Items</title>

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
							$refno = base64_decode($_GET['q']);
							
							$project = $user->get('projects', array('project_ref_no', '=', $refno));

							if($project->mop_peritem === NULL){
								$ModeOfProcurement = $project->MOP;
							}else{
								$ModeOfProcurement = json_decode($project->mop_peritem);
							}
							
							if($project){
								
								$projectDetails = $user->projectDetails($refno);
									
					?>
					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Content of project <a style="color:#009bdf"><?php echo $refno;?></a> "<a style="color:#F37123"><?php echo $project->project_title;?></a>".</h5>
							</div>
							<div class="ibox-content">
								<h2><a style="color:#2a9c97">Step 1 of  &nbsp3</a><br>Items Selection</h2>
								<div class="row">
									<div class="col-sm-12">
										<div class="alert alert-success">
											Choose from the following items below you wish to be resorted for canvass. 
											<br><code>Note: Refer from the DBMPS checklist if applicable.</code>
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
												<th>MOP</th>
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

														$prMOPDetails = $details['type'].'-'.$lot['l_id'].'-'.$lotContent['id'];
														$prItemDetails = base64_encode($details['type']."{|}".$lot['l_title']."{|}".json_encode($lotContent)."{|}".$lot['l_cost']);

														if($project->mop_peritem !== NULL){
															foreach($ModeOfProcurement as $key => $itemMode){
																foreach($itemMode as $item){
																	if($item === $prMOPDetails){
																		$itemSpecificMode = $key;
																	}
																}
															}
														}else{
															$itemSpecificMode = $ModeOfProcurement;
														}
											
											?>

												<tr>
													<td style="text-align:center;">
														<input type="checkbox" class="i-checks" data-cvn="step1" data-ref="<?php echo base64_encode($prMOPDetails);?>" data-mop="<?php echo base64_encode($itemSpecificMode); ?>" data-item='<?php echo $prItemDetails; ?>'>
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
													<td><?php echo $itemSpecificMode;?></td>
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
														<th>MOP</th>
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
															
																$joMOPdetail = $details['type'].'-'.$lot['l_id'].'-'.$lotContent['id'];
																$joitemDetails = base64_encode($details['type']."{|}".$lot['l_title']."{|}".json_encode($lotContent)."{|}".$lot['l_cost']);

																if($project->mop_peritem !== NULL){
																	foreach($ModeOfProcurement as $key => $itemMode){
																		foreach($itemMode as $item){
																			if($item === $joMOPdetail){
																				$itemSpecificMode = $key;
																			}
																		}
																	}
																}else{
																	$itemSpecificMode = $ModeOfProcurement;
																}		
																
													?>												
															<tr>
																<td style="text-align:center;">
																	<input type="checkbox" class="i-checks" data-cvn="step1" data-ref="<?php echo base64_encode($joMOPdetail);?>" data-mop="<?php echo base64_encode($itemSpecificMode);?>" data-item='<?php echo $joitemDetails;?>'>
																</td>
																<th><?php echo $itemCount;?></th>
																<th><?php echo $lotCount;?> - <?php echo $lot['l_title'];?></th>
																<td><?php echo $itemSpecificMode ?></td>
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
							<form method="POST" action="" name="canvass">
							<div class="ibox-content">
								<div class="row">
									<div class="col-md-6">
										<h2><a style="color:#2a9c97">Step 2 of  &nbsp3</a><br>Items Resorting</h2>
									</div>
									<div class="col-md-3">
										<div class="btn-group pull-right" style="margin-right:15px">
											<button type="button" class="btn btn-info btn-rounded dropdown-toggle" data-toggle="dropdown"><i class="fas fa-chess-pawn" style="font-size: 1.7em; color:#514e4e"></i> <span>Selected Items Options</span>&nbsp;&nbsp;</button>
											<ul class="dropdown-menu" id="CanvassDropDown">
											</ul>
										</div>
									</div>									
									<div class="col-md-3">
										<div class="form-group">
											<label style="color:red">Number of Canvass forms needed *</label>
											<input type="number" min="1" max="30" id="canvassCount" placeholder="Important" class="form-control">
										</div>								
									</div>
									<div class="col-md-12">
										<div class="alert alert-success">
											<i class="fas fa-info"></i> After Submiting and Finishing this form <strong>BAC Resolution Recommending Mode of Procurement</strong>, <strong>Publication</strong>, And <strong>Canvass Forms</strong> created will be automatically printed respectively.
										</div>										
									</div>									
								</div>
								<div id="pr-content">

								</div>

								<br>
								
								<div id="jo-content">

								</div>

								<input type="hidden" name="canvass">

							</div>
							<div class="ibox-footer col-lg-12">
								<span class="float-right">
									<button type="button" id="backbtn" class="btn btn-rounded btn-primary" style="display:none;"><i class="ti-angle-double-left"></i> Back</button>
									<button type="submit" class="btn btn-rounded btn-primary">Submit and Finish <i class="ti-angle-double-right"></i></button>
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
								<br>
							</div>
							<div id="CanvassList" class="animated fadeInUp" style="display:none">
								
								<!-- <div id="c1" class="widget lazur-bg text-center shine-me">
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
								</div>									 -->
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

	<div class="modal fade" id="canvasItems" tabindex="-1" role="dialog" aria-labelledby="preprocTitle" aria-hidden="true">
		<div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="preprocTitle">Pre-Procurement Evaluation result Registration</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>



    <?php include '../../includes/parts/admin_scripts.php'; ?>

	

</body>
<script>

	$(function(){
		const mop = '<?php echo $project->MOP;?>;';

		var Canvass = {
			gds: '<?php echo $project->project_ref_no?>',
			forms: []
		};

		// var Canvass = {
		// 	title: '<?php 
			// echo $project->project_title;
			?>',
		// 	gds: '<?php 
		// echo $project->project_ref_no?>',
		// 	abc: <?php 
		// echo $project->ABC;?>,
		// 	requested_by: <?php 
		// echo $project->end_user;?>,
		// 	evaluator: '<?php
		//  echo $project->evaluator;?>',
		// 	forms: []
		// };

		var SelectedItems = [];

		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1000);
		
		$('#bResort').on('click', function(){
			let rItems = $('[data-cvn="step1"]:checked');
			if(rItems.length !== 0){

				$('[data-resort-items="files"]').html('');
				
				if(mop.includes(',')){
					let moparray = [];
					let eachMop = mop.split(',');

					$('#MOPCount').html('');

					rItems.each(function(i, e){
						let itemDetail = atob(e.dataset.item).split('{|}')
						let elementMode = atob(e.dataset.mop);

						// find obj with an MOP of elementMode
						let index_obj = moparray.find(function(el){
							return el.mode === elementMode 
						});

						if(index_obj === undefined){

							moparray.push({
								mode: elementMode,
								lot_titles: [{title: itemDetail[1], cost: itemDetail[3]}]
							});

						}else{

							// NOTE add lot title total cost

							// add lot title to obj with the same MOP
							let index_array = moparray.indexOf(index_obj);
							if(moparray[index_array].lot_titles.indexOf(itemDetail[1]) === -1){

								moparray[index_array].lot_titles.push({title: itemDetail[1], cost: itemDetail[3]});
							}
						}

					});
					
					Canvass.noMop = moparray;
					
					moparray.forEach(function(e, i){
						$('[data-resort-items="files"]').append(`
							<div class="my-file-box">
								<div class="file">
									<a href="#">
										<span class="corner"></span>
										<div class="icon">
											<i class="fas fa-file-pdf"></i>
										</div>
										<div class="file-name">
											BAC Reso-Recommending & Publication ${e}.pdf
											<div class="dropdown">
												<a href="#" class="toggle-dropdown" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
												<ul class="dropdown-menu">
													<input type="text" class="form-control" placeholder="Classification">
												</ul>
											</div>												
										</div>
									</a>
								</div>
							</div>`);
						
						$('#MOPCount').append(`${eachMop[i]}<br>`);
					});
					
					$('#MOPCountMult').text(moparray.length * 2);
				}else{
					$('[data-resort-items="files"]').append(`
						<div class="my-file-box">
							<div class="file">
								<a href="#">
									<div class="icon">
										<i class="fas fa-file-pdf"></i>
									</div>
									<div class="file-name">
										BAC Reso-Recommending & Publication ${mop}.pdf
										<div class="dropdown">
											<a href="#" class="toggle-dropdown" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
											<ul class="dropdown-menu">
												<input type="text" class="form-control" placeholder="Classification">
											</ul>
										</div>								
									</div>							
								</a>
							</div>
						</div>`);
					$('#MOPCount').text(mop);
					$('#MOPCountMult').text(2);

					Canvass.noMop = mop;
				}
				$('#summary').modal('show');
			}else{
				swal({
					title: "Action invalid!",
					text: "Please select an item",
					confirmButtonColor: "#DD6B55",
					type: "error"
				});
			}

		});


		$('#resort-savePrint').on('click', function(){

			SelectedItems = [];

			let rItems = $('[data-cvn="step1"]:checked');
			let count = 0;
			let prT_header = false;

			$('#pr-content').html('');
			$('#jo-content').html('');

			rItems.each(function step2(i, e){
				let rDetails = atob(e.dataset.item).split('{|}');
				let rSpec = JSON.parse(rDetails[2]);
				let cur_mop = atob(e.dataset.mop);

				// append selected items
				SelectedItems.push(atob(e.dataset.ref));

				switch(rDetails[0]){
					case "PR":
						if(!prT_header){
							$('#pr-content').html(`<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>
								<th>MOP</th><th>Select</th><th>Lot Origin</th><th>Stock No</th>
								<th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th><th>Total Cost</th>
							</tr></thead><tbody id="pr-detail"></tbody></table></div><br>`);
							prT_header = true;
						}

						$('#pr-detail').append(`<tr>
							<td style="text-align:center;"><input type="checkbox" data-cvn="step2" data-ref="${e.dataset.ref}" data-details="${btoa(rDetails[2])}" data-lot="${e.dataset.item}"  class="i-checks"></td>
							<td>${atob(e.dataset.mop)}</td>
							<td>${rDetails[1]}</td>
							<td>${rSpec.stock_no}</td>
							<td>${rSpec.unit}</td><td>${rSpec.desc}</td><td>${rSpec.qty}</td>
							<td>${rSpec.uCost}</td><td>${rSpec.tCost}</td>
								</tr>`);
						$(`#lot-${count}`).val(rDetails[1]);
						break;
					case "JO":
						$('#jo-content').append(`<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>
							<th>Select</th><th>MOP</th><th>Lot Origin</th>
							<th>Tags</th><th>Lot Estimated Cost</th><th>Notes</th>
						</tr></thead><tbody id="jo-detail-${count}"></tbody></table></div><br>`);
						
						$(`#jo-detail-${count}`).append(`<tr><td style="text-align:center;"><input type="checkbox" data-cvn="step2" data-ref="${e.dataset.ref}" data-details="${btoa(rDetails[2])}" data-lot="${e.dataset.item}" class="i-checks"></td>
							<td>${atob(e.dataset.mop)}</td>
							<td>${rDetails[1]}</td>
							<td>${rSpec.header}</td>
							<td>${rSpec.tags}</td>
							<td>${rDetails[3]}</td>
							</tr>`);
						$(`#lot-${count}`).val(rDetails[1]);
						break;
					default:
						break;
				}
				count++;
			});


			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});

			$('#step1').attr('style', 'display:none;');
			$('#step2').attr('style', '');
			$('#backbtn').attr('style', '');

		});


		$('#backbtn').on('click', function(){
			$('#step1').attr('style', '');
			$('#step2').attr('style', 'display:none');
			$(this).attr('style', 'display:none');
		});


		$('#canvassCount').on('change', function(){
			$('#resort-savePrint').trigger('click');
			let color = ['lazur', 'yellow', 'red'];
			let count = 0, canvassObject = [];
			let elem_CanvassList = $('#CanvassList');
			let elem_CanvassDropDown = $('#CanvassDropDown');

			elem_CanvassList.attr('style', 'display:none');
			elem_CanvassList.html('');
			elem_CanvassDropDown.html('');

			for(let i = 0 ; $(this).val() > i ; i++){
				elem_CanvassList.append(`
					<div class="widget ${color[count]}-bg text-center" data-toggle="modal" data-canv-modal="${i}" data-target="#canvasItems">
						<h4>Canvass ${i + 1}</h4>
						<div class="m-b-md">
							<h1 class="m-s" data-canv-itemCount="${i}">0 item</h1>
							<!-- <input type="text" class="form-control form-control-sm" placeholder="Lot Name"> -->
						</div>
					</div>`);

				if(count === 2){
					count = 0;
				}else{
					count++;
				}

				elem_CanvassDropDown.append(`<li>
					<a class="dropdown-item" data-canv-no="${i}">
					<i class="fas fa-check green side"></i> Canvas ${i + 1}</a>
				</li>`);

				// Canvass.forms.push({no: i, items: [], type: ""});
				canvassObject.push({no: i, items: [], type: ""});

				$(`[data-canv-no="${i}"]`).on('click', function(){
					let C_elem = $(this), text;
					let cvnsItemSel = $('[data-cvn="step2"]:checked');
					let C_elem_attr = C_elem.attr('data-canv-no');

					if(cvnsItemSel.length !== 0){

						cvnsItemSel.each(function(i, e){
							let dataset_ref_decode = atob(e.dataset.ref);
							let lot_details = atob(e.dataset.lot).split('{|}');


							// listing of items per canvass
							// Canvass.forms[C_elem_attr].items.push({
							// 	ref: dataset_ref_decode, 
							// 	details: JSON.parse(atob(e.dataset.details))
							// });

							canvassObject[C_elem_attr].items.push({
								ref: dataset_ref_decode, 
								details: JSON.parse(atob(e.dataset.details))
							});



							// Canvass.forms[C_elem_attr].type = lot_details[0];
							canvassObject[C_elem_attr].type = lot_details[0];
							
							// remove from SelectedItems
							SelectedItems.splice(SelectedItems.indexOf(dataset_ref_decode), 1);



							e.parentNode.parentNode.parentNode.remove();
						});
						
						Canvass.forms = canvassObject;

						if(Canvass.forms[C_elem_attr].items.length === 1){
							text = `${Canvass.forms[C_elem_attr].items.length} item`;
						}else{
							text = `${Canvass.forms[C_elem_attr].items.length} items`;
						}

						$(`[data-canv-itemCount="${i}"]`).text(text);
						$(`[data-canv-modal="${i}"]`).addClass("shine-me");
						setTimeout(function(){
							$(`[data-canv-modal="${i}"]`).removeClass("shine-me");
						}, 500);


					}else{
						swal({
							title: "Action invalid!",
							text: "Please select an item",
							confirmButtonColor: "#DD6B55",
							type: "error"
						});
					}

				
					console.log(Canvass);


					$('[name="canvass"]').val(JSON.stringify(Canvass));
				});

				$(`[data-canv-modal="${i}"]`).on('click', function(){
					
					console.log(Canvass.forms[$(this).attr('data-canv-modal')].items);
					// CanvassObject[$(this).attr('data-canv-modal')].items.forEach(function(e, i){
					// 	console.log(JSON.parse(e.details));

					// 	// append in modal

					// });

					$('[name="canvass"]').val(JSON.stringify(Canvass));
				});

			}

			setTimeout(function(){
				elem_CanvassList.attr('style', '');
			}, 50);

		});

		$(document.canvass).on('submit', function(){
			console.log(SelectedItems);
			if(SelectedItems.length !== 0){
				swal({
					title: "Action invalid!",
					text: "There are items with no assigned Canvass form.",
					confirmButtonColor: "#DD6B55",
					type: "error"
				})
				return false;
			}
		});

	});
</script>
</html>
