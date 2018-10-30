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
							<div class="col-lg-12 animated fadeInRight">
								<div class="alert alert-info">
									<a class="alert-link">Note that requests that are already passed the Technical Member's evaluation cannot be modified by the requestor or the endusers. Only the Procurement Aids has the privilage to modify or update your requests after passing the evaluation. It is adviced to head to PrMO and state your concern with regards updating your request/s concern.</a>
								</div>
							</div>
						</div>
						<div class="row">
			
						
							<?php
							
							if(!isset($_GET['q'])){

							echo'
							
                                <div class="col-lg-8">
                                    <div class="panel panel-default rem">
                                        <div class="panel-heading">
                                           <i class="fa fa-info-circle side" style="color:black"></i> Guides and Legend
                                        </div>
                                        <div class="panel-body">
                                            <p>Kindly notice the header color of each cards and refer to this legend.</p>
											 <ul class="list-group clear-list m-t">
												<li class="list-group-item">
													<span class="label label-info">1</span> Cards with the teal header are request forms that are not yet submitted and received by the PrMO, therefore you have the full privilage to update or delete any listing on your request.
												</li>
												<li class="list-group-item">
													<span class="label label-warning">2</span> Cards with the orange header are request forms that are already submitted and received by the PrMO or in the process of Technical Member evaluation, Therefore modifying of listing in this request are strictly monitored by our personnels, also modification on this request requires approval from our personnel for your modification/s to be applied whether it is your personal reason or modification required by the Technical Member.
												</li>
												<li class="list-group-item">
													<span class="label label-danger">3</span> Cards with the red header are request forms that are already passed the Technical Member evaluation, meaning you cannot manually modify any listing in your request. It is adviced to head to PrMO and state your concern with regards updating your request/s listing.
												</li>							
											 </ul>											
                                        </div>

                                    </div>
                                </div>								
							
							';
							
							$requests = $user->myRequests(Session::get(Config::get('session/session_name')), true); // requests that are already received
							
							foreach($requests as $request){

								if($user->isEvaluated($request->form_ref_no)){
									$colorClass = "panel-danger";
								}else{
									$colorClass = "panel-warning";
								}
								
							?>
								<div class="col-lg-4">
									<div class="panel <?php echo $colorClass;?> rem1">
										<div class="panel-heading" style="color:black">
											Ref:   <?php echo $request->form_ref_no;?>
										</div>
										<div class="panel-body">
											<h3><?php echo $request->title;
											
											?></h3>
											<hr style="background-color:#23c6c8">
											
											<div class="">
												<p class="inline">Number of Lots:</p>
												<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $request->number_of_lots;?></p>
												<br>
												<p class="inline">Date Created:</p>
												<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo Date::translate($request->date_created, 1);?></p>															
											</div>	
											<div class="panel-footer">
												<a href="?q=<?php echo $request->form_ref_no;?>"><button class="btn btn-warning pull-right btn-rounded" style="margin-bottom:10px; margin-right:-15px">Details</button></a>
											</div>	
										</div>
									</div>
								</div>		
							<?php
								}											
								$requests = $user->myRequests(Session::get(Config::get('session/session_name')), false); // requests that are not yet received
								 
								foreach($requests as $request){
							?>
							
								<div class="col-lg-4">
									<div class="panel panel-info rem1">
										<div class="panel-heading" style="color:black">
											Ref: <?php echo $request->form_ref_no?>
										</div>
										<div class="panel-body">
											<h3><?php echo $request->title?></h3>
											<hr style="background-color:#23c6c8">
											
											<div class="">
												<p class="inline">Number of Lots:</p>
												<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $request->number_of_lots;?></p>
												<br>
												<p class="inline">Date Created:</p>
												<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo Date::translate($request->date_created, 1);?></p>															
											</div>	
											<div class="panel-footer">
												<a href="#"><button class="btn btn-danger pull-right btn-rounded" style="margin-bottom:10px; margin-right:-15px; margin-left:3px">Delete</button></a>
												<a href="?q=<?php echo $request->form_ref_no;?>"><button class="btn btn-info pull-right btn-rounded" style="margin-bottom:10px;">Details</button></a>
											</div>											
										</div>
									</div>
								</div>								
							
							<?php
								} 
							}else{
								
								$refno = $_GET['q'];

								$admin = new Admin();
								$valid_request = $admin->selectAll("project_request_forms");
			
								$valid = false;
			
								foreach ($valid_request as $request){
									if($refno == $request->form_ref_no){
										$valid = true;
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
												<i class="fa fa-chevron-up"></i>
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
														'refno' => $refno,
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
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
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
												'refno' => $refno,
												'lot' => $currentLot,
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
								echo '<br>
								<div style="margin-left:72%">
									<button class="btn btn-lg btn-rounded btn-outline btn-primary" id="edit">Edit Selected</button>
									<button class="btn btn-lg btn-rounded btn-outline btn-danger" id="del">Delete Selected</button>
								</div>';
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
		var obj = Object;
		var OriginalData = null;
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
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th><th>Stock No.</th>
						<th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th>
						<th>Total Cost</th></tr></thead><tbody dataFor="userEditAppend"></tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>${obj.lot}</td>
								<td><input type="text" name="prUpStk[]" class="form-control" value="${obj.stock_no}"></td>
								<td><input type="text" name="prUpUnt[]" class="form-control" value="${obj.unit}"></td>
								<td><textarea name="prUpDesc[]" cols="30" rows="1" maxlength="1000" class="form-control">${obj.desc}</textarea></td>
								<td><input type="number" name="prUpQty[]" index="${i}" class="form-control" min="1" value="${obj.qty}"></td>
								<td><input type="number" name="prUpUc[]" index="${i}" class="form-control" step=".01" min="0.01" value="${obj.uCost}"></td>
								<td><input type="number" name="prUpTc[]" index="${i}" class="form-control" readonly step=".01" min="0.01" value="${obj.tCost}"></td>
							</tr>`);
					});
					OriginalData = $('#userEditForm').serializeArray();
	
					$('[name="prUpQty[]"]').on('change', function(){
						let inx = $(this).attr('index');
						$(`[name="prUpTc[]"][index="${inx}"]`).val(($(this).val() * $(`[name="prUpUc[]"][index="${inx}"]`).val()).toFixed(2))
					});
	
					$('[name="prUpUc[]"]').on('change', function(){
						let inx = $(this).attr('index');
						$(`[name="prUpTc[]"][index="${inx}"]`).val(($(`[name="prUpQty[]"][index="${inx}"]`).val() * $(this).val()).toFixed(2))
					});
					$('#userEdit').modal('show');
				}else{
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th>
						<th>List Title</th><th>Lot Estimated Cost</th><th>Tags</th>
						<th>Notes</th></tr></thead><tbody dataFor="userEditAppend">
						</tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>${obj.lot}</td>
								<td><input type="text" name="joList[]" class="form-control" value="${obj.header}"></td>
								<td><input type="number" name="joCost[]" class="form-control" step=".01" min="0.01" value="${obj.lot_cost}"></td>
								<td><input type="text" name="joTags[]" class="form-control" data-role="tagsinput" value="${obj.tags}"></td>
								<td><textarea name="joNotes[]" placeholder="Some text" class="form-control">${obj.notes}</textarea></td>		
							</tr>`);
					});
					$('[name="joTags"]').tagsinput();
					OriginalData = $('#userEditForm').serializeArray();
					$('#userEdit').modal('show');
				}
			}else{
				swal({
					title: "No selected document!",
					text: "Please select a document.",
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
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th><th>Stock No.</th>
						<th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th>
						<th>Total Cost</th></tr></thead><tbody dataFor="userEditAppend"></tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>${obj.lot}</td>
								<td><input readonly type="text" name="prUpStk[]" class="form-control" value="${obj.stock_no}"></td>
								<td><input readonly type="text" name="prUpUnt[]" class="form-control" value="${obj.unit}"></td>
								<td><textarea readonly name="prUpDesc[]" cols="30" rows="1" maxlength="1000" class="form-control">${obj.desc}</textarea></td>
								<td><input readonly type="number" name="prUpQty[]" class="form-control" min="1" value="${obj.qty}"></td>
								<td><input readonly type="number" name="prUpUc[]" class="form-control" step=".01" min="0.01" value="${obj.uCost}"></td>
								<td><input readonly type="number" name="prUpTc[]" class="form-control" readonly step=".01" min="0.01" value="${obj.tCost}"></td>
							</tr>`);
					});
					$('#userEdit').modal('show');
				}else{
					$('[dataFor="userEdit"]').append(`<thead><tr><th>Lot No.</th>
						<th>List Title</th><th>Lot Estimated Cost</th><th>Tags</th>
						<th>Notes</th></tr></thead><tbody dataFor="userEditAppend">
						</tbody>`);
					$('.i-checks:checked').each(function(i){
						obj = JSON.parse($(this).attr('details'));
						$('[dataFor="userEditAppend"]').append(`<tr>
								<td>${obj.lot}</td>
								<td><input readonly type="text" name="joList[]" class="form-control" value="${obj.header}"></td>
								<td><input readonly type="number" name="joCost[]" class="form-control" step=".01" min="0.01" value="${obj.lot_cost}"></td>
								<td><input readonly type="text" name="joTags[]" class="form-control" data-role="tagsinput" value="${obj.tags}"></td>
								<td><textarea readonly name="joNotes[]" placeholder="Some text" class="form-control">${obj.notes}</textarea></td>		
							</tr>`);
					});
					$('[name="joTags"]').tagsinput();
					OriginalData = $('#userEditForm').serializeArray();
					$('#userEdit').modal('show');
				}
			}else{
				swal({
					title: "No selected document!",
					text: "Please select a document.",
					type: "error",
					confirmButtonColor: "#DD6B55"
				});
			}
		});

		document.querySelector('[dataFor="userEditSubmit"]').addEventListener('click', function(){
			var EditData = $('#userEditForm').serializeArray();
			SendDoSomething("POST", "xhr-item-update.php", {
				orig: OriginalData,
				edit: EditData,
				action: act
			}, {
				do: function(d){
					$('[dataFor="userEditClose"]').trigger('click');
					swal({
						title: "Success!",
						text: "Successfully updated.",
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
		});
	});
</script>
</html>