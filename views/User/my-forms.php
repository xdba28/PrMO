<?php 
    require_once('../../core/init.php');
    $user = new User(); 
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

    <title>PrMO OPPTS | My Forms</title>

	<?php include_once '../../includes/parts/user_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/user_side_nav.php'; ?>
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
                                        <th>Title </th>
										<th class="center">Status </th>
                                        <th>Date Created</th>
										<th style="text-align:center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php 
										$myRequests = $user->getAll('project_request_forms', array('requested_by', '=', Session::get(Config::get('session/session_name'))));
										//echo "<pre>",print_r($myRequests),"</pre>";
										foreach($myRequests as $request){
											if(isset($count)){$count++;}else{$count=1;}
												
											if($request->status == "unreceived"){
												$displayStatus = '<span class="status-text status-green">Unreceived</span>';
												$actionButton  = '<div class="btn-group">
																	 <a href="my-forms?q='.$request->form_ref_no.'" class="btn btn-info btn-sm btn-rounded btn-outline" style="color:black">Details</a>
																	 <a class="btn btn-warning btn-sm btn-rounded btn-outline">Delete</a>                             
																 </div>';

											}else{
												if($user->isEvaluated($request->form_ref_no)){
													$displayStatus = '<span class="status-text status-red">Evaluation Passed</span>';
													$actionButton = '<a href="my-forms?q='.$request->form_ref_no.'" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a>';
												}else{
													$displayStatus = '<span class="status-text status-orange">Received</span>';
													$actionButton = '<a href="my-forms?q='.$request->form_ref_no.'" class="btn btn-white btn-sm"><i class="ti-layers-alt"></i> details </a>';
												}
												
											}

									?>
										<tr>
											<td><?php echo $count;?></td>
											<td><?php echo $request->form_ref_no;?></td>
											<td style="max-width:300px"><?php echo $request->title;?></td>
											<td class="status left"><?php echo $displayStatus;?></td>
											<td><?php echo Date::translate($request->date_created, 2);?></td>
											<td class="center"><?php echo $actionButton;?></td>
										</tr>		

									<?php 
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
								
								$refno = $_GET['q'];
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
								for($x = 0; $x<$lots; $x++){
									if(($numberOfLots->numberOfLots == 1) && ($numberOfLots->lot_no == '101')){
										$currentLot = '101';
										$showLot = 'No lot specified';
									}else{
										$currentLot =$x+1;
										$showLot = "Lot ".$currentLot;
									}
									
									$content = $user->getContent($refno, $type, $currentLot);
									$limiter = '';
							?>

							<div class="col-lg-12">
								<div class="ibox ">
									<div class="ibox-title">
										<h5><?php echo $showLot, " - ", $content[0]->lot_title;?></h5>
										<div class="ibox-tools">
											<a class="collapse-link">
												<a href ="my-forms" class="btn btn-info btn-rounded btn-outline btn-xs" style="color:black"><i class="ti-angle-double-left"></i> Back to My Forms</a>
												<a class="dropdown-toggle" data-toggle="dropdown" href="#">
													<i class="fa fa-wrench"></i>
												</a>
												<ul class="dropdown-menu dropdown-user">
													<li><a href="#" class="dropdown-item">Delete Lot</a></li>
												</ul>												
											</a>
										</div>
									</div>
									<div class="ibox-content">
										<table class="table table-bordered table-hover">
											<thead>
											<tr>
												<th>Select</th>
												<th>Line</th>
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
												foreach($content as $detail){
													$item_details = [
														'lot' => $currentLot,
														'item_id' => $detail->ID,
														'stock_no' => $detail->stock_no,
														'unit' => $detail->unit,
														'desc' => $detail->item_description,
														'qty' => $detail->quantity,
														'uCost' => $detail->unit_cost,
														'tCost' => $detail->total_cost
													];
													
											?>
											<tr>
												<td style="text-align:center;">
													<input type="checkbox" class="i-checks" details='<?php echo json_encode($item_details);?>'>
												</td>
												<td><?php echo $line;?></td>
												<td><?php echo $detail->stock_no; ?></td>
												<td><?php echo $detail->unit; ?></td>
												<td class="tddescription"><?php echo $detail->item_description; ?></td>
												<td><?php echo $detail->quantity; ?></td>
												<td><?php echo $detail->unit_cost; ?></td>
												<td><?php echo $detail->total_cost; ?></td>
											</tr>

											<?php
												$line++;
												}
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
									for($x = 0; $x<$lots; $x++){
										$currentLot = $x + 1;
										$content = $user->getContent($refno, $type, $currentLot);
							?>

                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Lot <?php echo $currentLot;?> - <?php echo $content[0]->lot_title;?></h5>

                            <div class="ibox-tools">
								<a href ="my-forms" class="btn btn-info btn-rounded btn-outline btn-xs" style="color:black"><i class="ti-angle-double-left"></i> Back to My Forms</a>
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
									<i class="fa fa-wrench"></i>
								</a>
								<ul class="dropdown-menu dropdown-user">
									<li><a href="#" class="dropdown-item">Delete Lot</a></li>
								</ul>								
                            </div>
                        </div>
                        <div class="ibox-content">

                            <table class="footable table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>
									<th>Select</th>
                                    <th>List Title</th>
                                    <th>Lot Estimated Cost</th>
                                    <th>Tags</th>
                                    <th>Notes</th>
                                </tr>
                                </thead>
                                <tbody>

									<?php
										foreach($content as $detail){
											$item_details = [
												'lot' => $currentLot,
												'item_id' => $detail->ID,
												'header' => $detail->header,
												'lot_cost' => $detail->lot_cost,
												'tags' => str_replace(",", ", ", $detail->tags),
												'notes' => $detail->note
											];
									?>
										<tr>
											<td style="text-align:center;">
												<input type="checkbox" class="i-checks" details='<?php echo json_encode($item_details);?>'>
											</td>
											<td><?php echo $detail->header;?></td>
											<td><?php echo $detail->lot_cost;?></td>
											<td><?php echo str_replace(",", ", ", $detail->tags);?></td>
											<td><?php echo $detail->note;?></td>
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
									//do nothing
								}else{
								echo '<br>
								<div style="margin-left:72%">
									<button class="btn btn-lg btn-rounded btn-outline btn-primary" id="edit">Edit Selected</button>
									<button class="btn btn-lg btn-rounded btn-outline btn-danger" id="del">Delete Selected</button>
								</div>';
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

	<?php include_once '../../includes/parts/user_scripts.php'; ?>

</body>
<script>
	
	document.addEventListener('DOMContentLoaded', function(){
		<?php
			if(Session::exists("Request")){
			echo "window.open('../../bac/forms/pr-jo-doc');";
			}
		?>
		var ProjType = '<?php 
			if(!empty($type)) echo $type;
			?>';
		var obj = null;
		var OriginalData = {
			origin_form: '<?php if(!empty($refno)) echo $refno;?>',
			type: ProjType,
			status: '<?php if(!empty($status)) echo $status;?>'
		};
		var EditData = new Object();
		var DeleteData = {
			origin_form: '<?php if(!empty($refno)) echo $refno;?>',
			type: ProjType,
			status: '<?php if(!empty($status)) echo $status;?>'
		};
		var act = null;

		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});

		$('#edit').on('click', function(e){
			if($('.i-checks:checked').length !== 0){
				act = 'update';
				$('[dataFor="userEditSubmit"]').html('Update').attr('class', 'btn btn-primary');
				$('[dataFor="userEdit"]').html('');
				if(ProjType === "PR"){
					let array = [];
					let lot = [];
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th><th>Stock No.</th>
						<th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th>
						<th>Total Cost</th></tr></thead><tbody dataFor="userEditAppend"></tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						array.push(obj);

						let lotfind = lot.find(function(el){
							return el === obj.lot
						});
						
						if(typeof lotfind === 'undefined'){
							lot.push(obj.lot);
						}

						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>
									<input type="text" name="lot-${i}" value="${obj.lot}" hidden>${obj.lot}
									<input type="text" name="item_id-${i}" value="${obj.item_id}" hidden>
								</td>
								<td><input type="text" name="stockNo-${i}" class="form-control" value="${obj.stock_no}"></td>
								<td><input type="text" name="unit-${i}" class="form-control" value="${obj.unit}"></td>
								<td><textarea name="description-${i}" cols="30" rows="1" maxlength="1000" class="form-control">${obj.desc}</textarea></td>
								<td><input type="number" name="quantity-${i}" data="qty" class="form-control" min="1" value="${obj.qty}"></td>
								<td><input type="number" name="unitCost-${i}" data="unit" class="form-control" step=".01" min="0.01" value="${obj.uCost}"></td>
								<td><input type="number" name="totalCost-${i}" class="form-control" readonly step=".01" min="0.01" value="${obj.tCost}"></td>
							</tr>`);
					});
					OriginalData.items = array;
					OriginalData.lotref = lot;
					
					$('[data="qty"]').on('change', function(){
						let inx = $(this).attr('name').split("-");
						$(`[name="totalCost-${inx[1]}"]`).val(($(this).val() * $(`[name="unitCost-${inx[1]}"]`).val()).toFixed(2));
					});

					$('[data="unit"]').on('change', function(){
						let inx = $(this).attr('name').split("-");
						$(`[name="totalCost-${inx[1]}"]`).val(($(`[name="quantity-${inx[1]}"]`).val() * $(this).val()).toFixed(2));
					});
	
					$('#userEdit').modal('show');
				}else if(ProjType === "JO"){
					let array = [];
					let lot = [];
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th>
						<th>List Title</th><th>Lot Estimated Cost</th><th>Tags</th>
						<th>Notes</th></tr></thead><tbody dataFor="userEditAppend">
						</tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						array.push(obj);

						let lotfind = lot.find(function(el){
							return el === obj.lot
						});
						
						if(typeof lotfind === 'undefined'){
							lot.push(obj.lot);
						}

						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>
									<input type="text" name="lot-${i}" value="${obj.lot}" hidden>${obj.lot}
									<input type="text" name="item_id-${i}" value="${obj.item_id}" hidden>
								</td>
								<td><input type="text" name="list-${i}" class="form-control" value="${obj.header}"></td>
								<td><input type="number" name="cost-${i}" class="form-control" step=".01" min="0.01" value="${obj.lot_cost}"></td>
								<td><input type="text" name="tags-${i}" dataFor="tags" class="form-control" data-role="tagsinput" value="${obj.tags}"></td>
								<td><textarea name="notes-${i}" placeholder="Some text" class="form-control">${obj.notes}</textarea></td>		
							</tr>`);
					});
					OriginalData.items = array;
					OriginalData.lotref = lot;
					$('[dataFor="tags"]').tagsinput();
					$('#userEdit').modal('show');
				}
			}else{
				swal({
					title: "No Items selected for editing!",
					text: "Please select an item.",
					type: "error",
					confirmButtonColor: "#DD6B55"
				});
			}
		});

		$('#del').on('click', function(){
			if($('.i-checks:checked').length !== 0){
				act = 'delete';
				$('[dataFor="userEditSubmit"]').html('Delete').attr('class', 'btn btn-danger');
				$('[dataFor="userEdit"]').html('');
				if(ProjType === "PR"){
					let array = [];
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th><th>Stock No.</th>
						<th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th>
						<th>Total Cost</th></tr></thead><tbody dataFor="userEditAppend"></tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						array.push(obj);
						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>
									<input type="text" name="lot-${i}" value="${obj.lot}" hidden>${obj.lot}
									<input type="text" name="item_id-${i}" value="${obj.item_id}" hidden>
								</td>
								<td><input type="text" readonly name="stockNo-${i}" class="form-control" value="${obj.stock_no}"></td>
								<td><input type="text" readonly name="unit-${i}" class="form-control" value="${obj.unit}"></td>
								<td><textarea readonly name="description-${i}" cols="30" rows="1" maxlength="1000" class="form-control">${obj.desc}</textarea></td>
								<td><input type="number" readonly name="quantity-${i}" data="qty" class="form-control" min="1" value="${obj.qty}"></td>
								<td><input type="number" readonly name="unitCost-${i}" data="unit" class="form-control" step=".01" min="0.01" value="${obj.uCost}"></td>
								<td><input type="number" readonly name="totalCost-${i}" class="form-control" readonly step=".01" min="0.01" value="${obj.tCost}"></td>
							</tr>`);
					});
					DeleteData.items = array;
					$('#userEdit').modal('show');
				}else if(ProjType === "JO"){
					let array = [];
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th>
						<th>List Title</th><th>Lot Estimated Cost</th><th>Tags</th>
						<th>Notes</th></tr></thead><tbody dataFor="userEditAppend">
						</tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						array.push(obj);
						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>
									<input type="text" name="lot-${i}" value="${obj.lot}" hidden>${obj.lot}
									<input type="text" name="item_id-${i}" value="${obj.item_id}" hidden>
								</td>
								<td><input readonly type="text" name="list-${i}" class="form-control" value="${obj.header}"></td>
								<td><input readonly type="number" name="cost-${i}" class="form-control" step=".01" min="0.01" value="${obj.lot_cost}"></td>
								<td><input disabled type="text" name="tags-${i}" dataFor="tags" class="form-control" data-role="tagsinput" value="${obj.tags}"></td>
								<td><textarea readonly name="notes-${i}" placeholder="Some text" class="form-control">${obj.notes}</textarea></td>		
							</tr>`);
					});
					DeleteData.items = array;
					$('[dataFor="tags"]').tagsinput();
					$(".bootstrap-tagsinput").prop("style", 'background: #E4E4E4; border: 1px solid #E4E4E4;');
					$('#userEdit').modal('show');
				}
			}else{
				swal({
					title: "No items to be deleted!",
					text: "Please select an item to be deleated.",
					type: "error",
					confirmButtonColor: "#DD6B55"
				});
			}
		});

		document.querySelector('[dataFor="userEditSubmit"]').addEventListener('click', function(){
			if(act === 'update'){
				if(ProjType === "PR"){
					let editArray = [];
					OriginalData.items.forEach(function(e, i){
						editArray.push({
							lot: $(`[name="lot-${i}"]`).val(),
							item_id: $(`[name="item_id-${i}"]`).val(),
							stockNo: $(`[name="stockNo-${i}"]`).val(),
							unit: $(`[name="unit-${i}"]`).val(),
							description: $(`[name="description-${i}"]`).val(),
							quantity: $(`[name="quantity-${i}"]`).val(),
							unitCost: $(`[name="unitCost-${i}"]`).val(),
							totalCost: $(`[name="totalCost-${i}"]`).val()
						});
					});
					EditData.items = editArray;
				}else if(ProjType === "JO"){
					let editArray = [];
					OriginalData.items.forEach(function(e, i){
						editArray.push({
							lot: $(`[name="lot-${i}"]`).val(),
							item_id: $(`[name="item_id-${i}"]`).val(),
							list: $(`[name="list-${i}"]`).val(),
							cost: $(`[name="cost-${i}"]`).val(),
							tags:  $(`[name="tags-${i}"]`).val(),
							notes: $(`[name="notes-${i}"]`).val()
						});
					});
					EditData.items = editArray;
				}
				
				SendDoSomething("POST", "xhr-item-update.php", {
					orig: OriginalData,
					edit: EditData,
					action: act
				}, {
					do: function(d){
						$('[dataFor="userEditClose"]').trigger('click');
						if(d.notif){
							swal({
								title: "Success!",
								text: "Your Requests has been successfully submited we'll notify you if your request has been approved.",
								type: "success"
							});
						}else{
							swal({
								title: "Success!",
								text: "Item(s) successfully updated",
								type: "success"
							});
						}
						// reload table
					}
				}, false, {
					f: function(){
						$('[dataFor="userEditClose"]').trigger('click');
						swal({
							title: "An error occurred!",
							text: "Cannot send data.",
							type: "error"
						});
					}
				});
			}else if(act === 'delete'){
				SendDoSomething("POST", "xhr-item-update.php", {
					del: DeleteData,
					action: act
				}, {
					do: function(d){
						$('[dataFor="userEditClose"]').trigger('click');
						swal({
							title: "Success!",
							text: "Successfully deleted.",
							type: "success"
						});
						// reload table
					}
				}, false, {
					f: function(){
						$('[dataFor="userEditClose"]').trigger('click');
						swal({
							title: "An error occurred!",
							text: "Cannot send data.",
							type: "error"
						});
					}
				});
			}
		});
	});
</script>
</html>