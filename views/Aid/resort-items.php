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

		$canvass = json_decode($_POST['canvass'], true);

		$user->startTrans();

		foreach($canvass['forms'] as $form){

			$count = 1;
			$titleCount = count($form['publication']['title']);
			$canvass_Title = '';

			foreach($form['publication']['title'] as $title){
				if($titleCount === 1){
					$canvass_Title = $title;
				}elseif($titleCount > 1){
					if($count === $titleCount){
						$canvass_Title .= 'and '.$title;
					}else{
						$canvass_Title .= $title.', ';
					}
				}
				$count++;
			}

			$user->register('publication', array(
				'gds_reference' => $canvass['gds'],
				'title' => $canvass_Title,
				'cost' => $form['publication']['cost']
			));

		}

		foreach($canvass['forms'] as $form){

			$count = 1;
			$titleCount = count($form['publication']['title']);
			$canvass_Title = '';

			foreach($form['publication']['title'] as $title){
				if($titleCount === 1){
					$canvass_Title = $title;
				}elseif($titleCount > 1){
					if($count === $titleCount){
						$canvass_Title .= 'and '.$title;
					}else{
						$canvass_Title .= $title.', ';
					}
				}
				$count++;
			}

			$publicationForm = $user->projectPublication($canvass['gds'], $canvass_Title, $form['publication']['cost']);
		
			$user->register('canvass_forms', array(
				'publication_reference' => $publicationForm,
				'type' => $form['type'],
				'per_item' => $form['per_item']
			));

			// $canvassForm = $user->cavassForm($publicationForm);
			$canvassForm = $user->get('canvass_forms', array('publication_reference', '=', $publicationForm));

			foreach($form['items'] as $item){

				if($form['type'] === 'PR'){

					$user->register('canvass_items_pr', array(
						'canvass_forms_id' => $canvassForm->id,
						'stock_no' => $item['details']['stock_no'],
						'unit' => $item['details']['unit'],
						'item_description' => $item['details']['desc'],
						'quantity' => $item['details']['qty'],
						'unit_cost' => $item['details']['uCost'],
						'total_cost' => $item['details']['tCost'],
						'mode' => $item['mode']
					));

				}elseif($form['type'] === 'JO'){
					
					$user->register('canvass_items_jo', array(
						'canvass_forms_id' => $canvassForm->id,
						'header' => $item['details']['header'],
						'tags' => $item['details']['tags'],
						'mode' => $item['mode']
					));

				}
			}
		}

		// // update steps
		
		// //get the json file for step details
		// $json = file_get_contents('../xhr-files/jsonsteps.json');
		// //Decode JSON
		// $stepsStructure = json_decode($json,true);

		// $updateProject = $user->get('projects', array('project_ref_no', '=', $canvass['gds']));

		// if($updateProject->mop_peritem === NULL){

		// 	// get MOP and update the appropriate workflow and accomplishment

		// }else{
	
		// 	$user->update('projects', 'project_ref_no', $canvass['gds'], array(
		// 		'accomplished' => '4',
		// 		'workflow' => "Canvassing"
		// 	));

		// }

		$user->endTrans();

		// redirect to project 

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
								<h2><a style="color:#2a9c97">Step 1 of  &nbsp2</a><br>Items Selection</h2>
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
										<h2><a style="color:#2a9c97">Step 2 of  &nbsp2</a><br>Items Resorting</h2>
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

		var SelectedItems = [];

		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1000);
		
		$('#bResort').on('click', function(){

			SelectedItems = [];

			let rItems = $('[data-cvn="step1"]:checked');
			let count = 0, prT_header = false;

			if(rItems.length !== 0){

				$('#pr-content').html('');
				$('#jo-content').html('');

				rItems.each(function step2(i, e){
					let rDetails = atob(e.dataset.item).split('{|}');
					let rSpec = JSON.parse(rDetails[2]);

					// append selected items
					// for checking if there are items left for selection
					SelectedItems.push(atob(e.dataset.ref));

					switch(rDetails[0]){
						case "PR":
							if(!prT_header){
								$('#pr-content').html(`<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr>
									<th>Select</th><th>MOP</th><th>Lot Origin</th><th>Stock No</th>
									<th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th><th>Total Cost</th>
								</tr></thead><tbody id="pr-detail"></tbody></table></div><br>`);
								prT_header = true;
							}

							$('#pr-detail').append(`<tr>
								<td style="text-align:center;"><input type="checkbox" data-cvn="step2" data-mop="${e.dataset.mop}" data-ref="${e.dataset.ref}" data-details="${btoa(rDetails[2])}" data-lot="${e.dataset.item}"  class="i-checks"></td>
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
							
							$(`#jo-detail-${count}`).append(`<tr><td style="text-align:center;"><input type="checkbox" data-cvn="step2" data-mop="${e.dataset.mop}" data-ref="${e.dataset.ref}" data-details="${btoa(rDetails[2])}" data-lot="${e.dataset.item}" class="i-checks"></td>
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
			
			}else{
				swal({
					title: "Action invalid!",
					text: "Please select an item",
					confirmButtonColor: "#DD6B55",
					type: "error"
				});
			}

		});


		$('#backbtn').on('click', function(){
			$('#step1').attr('style', '');
			$('#step2').attr('style', 'display:none');
			$(this).attr('style', 'display:none');
		});


		$('#canvassCount').on('change', function(){

			Canvass.forms = [];

			$('#bResort').trigger('click');
			let canvassObject = [];
			let elem_CanvassList = $('#CanvassList');
			let elem_CanvassDropDown = $('#CanvassDropDown');

			elem_CanvassList.attr('style', 'display:none').html('');
			elem_CanvassDropDown.html('');

			for(let i = 0 ; $(this).val() > i ; i++){
				elem_CanvassList.append(`
					<div class="ibox">
						<div class="ibox-content">
							<h3 class="m-b-md">Canvass form ${i + 1}</h3>
							<h4>Lot title:</h4>
							<div data-canv-lots="${i}">
							</div>
							<h4 data-canv-itemCount="${i}">Items: 0</h4>
							Canvass per item <input type="checkbox" value="true" name="perItem-${i}">
							<br>
							<button class="btn btn-rounded btn-sm btn-primary" data-toggle="modal" data-canv-modal="${i}" data-target="#canvasItems">Show Items</button>
						</div>
					</div>`);

				elem_CanvassDropDown.append(`<li>
					<a class="dropdown-item" data-canv-no="${i}">
					<i class="fas fa-arrow-right green side"></i> Canvas ${i + 1}</a>
				</li>`);

				canvassObject.push({no: i, items: [], type: "", per_item: 0});
				// Canvass.forms.push({no: i, items: [], type: "", perItem: 0});

				$(`[data-canv-no="${i}"]`).on('click', function(){
					let C_elem = $(this), text;
					let cvnsItemSel = $('[data-cvn="step2"]:checked');
					let C_elem_attr = C_elem.attr('data-canv-no');
					let swalText = '';
					
					let lot = {
						title: [],
						cost: 0
					};

					if(cvnsItemSel.length !== 0){

						cvnsItemSel.each(function(i, e){
							let lot_details = atob(e.dataset.lot).split('{|}');
							
							// find same lot name
							let curtitle = lot.title.find(function(el){
								return el === lot_details[1];
							});

							let item = JSON.parse(atob(e.dataset.details));
							if(curtitle === undefined){
								lot.title.push(lot_details[1]);
								lot.cost += parseFloat(item.tCost);
							}else{
								lot.cost += parseFloat(item.tCost);
							}

							let dataset_ref_decode = atob(e.dataset.ref);
							// listing of items per canvass
							canvassObject[C_elem_attr].items.push({
								ref: dataset_ref_decode, 
								details: item,
								mode: atob(e.dataset.mop)
							});
							// Canvass.forms[C_elem_attr].items.push({
							// 	ref: dataset_ref_decode, 
							// 	details: JSON.parse(atob(e.dataset.details))
							// });
							canvassObject[C_elem_attr].type = lot_details[0];
							// Canvass.forms[C_elem_attr].type = lot_details[0];
						});

						if(lot.title.length > 1){

							lot.title.forEach(function(e, i){
								swalText += `${e}<br>`;
							});

							sweet({
								title: "Merge the following lots?",
								html: `
								<div style="text-align:center;">
									${swalText}
								</div>`,
								type: "info",
								showCancelButton: true,
								confirmButtonText: "Proceed",
								allowOutsideClick: false
							}, {
								do:function(res){
									if(res.dismiss === "cancel"){
										swal({
											title: "Action dismissed.",
											text: "",
											type: "info"
										});
										console.log(Canvass);
									}else if(res.value !== "undefined"){
										console.log(Canvass);
										cvnsItemSel.each(function(i, e){
											SelectedItems.splice(SelectedItems.indexOf(atob(e.dataset.ref)), 1);
											e.parentNode.parentNode.parentNode.remove();
										});
										Canvass.forms = canvassObject;
										Canvass.forms[C_elem_attr].publication = lot;
										// canvassObject[C_elem_attr].publication = lot;
										$(`[data-canv-lots=${i}]`).html(swalText);
										$(`[data-canv-itemCount="${i}"]`).text(`Items: ${Canvass.forms[C_elem_attr].items.length}`);
									}
								}
							});
						}else{
							console.log(Canvass);
							cvnsItemSel.each(function(i, e){
								SelectedItems.splice(SelectedItems.indexOf(atob(e.dataset.ref)), 1);
								e.parentNode.parentNode.parentNode.remove();
							});
							Canvass.forms = canvassObject;
							Canvass.forms[C_elem_attr].publication = lot;
							// canvassObject[C_elem_attr].publication = lot;
							$(`[data-canv-lots=${i}]`).html(lot.title[0]);
							$(`[data-canv-itemCount="${i}"]`).text(`Items: ${Canvass.forms[C_elem_attr].items.length}`);
						}

					}else{
						swal({
							title: "Action invalid!",
							text: "Please select an item",
							confirmButtonColor: "#DD6B55",
							type: "error"
						});
					}


					$('[name="canvass"]').val(JSON.stringify(Canvass));
				});

				$(`[name="perItem-${i}"]`).on('change', function(){
					let C_elem_attr = $(this).attr('name').split('-')[1];
					if(this.checked){
						Canvass.forms[C_elem_attr].per_item = 1;
					}else{
						Canvass.forms[C_elem_attr].per_item = 0;
					}
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

			// console.log(Canvass);

			setTimeout(function(){
				elem_CanvassList.attr('style', '');
			}, 50);

		});

		$(document.canvass).on('submit', function(){
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
