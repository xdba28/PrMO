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

	<script>
		function prEdit(e){
			var prData = e.parentNode.parentNode.parentNode.children;
			document.querySelector('[dataFor="userEdit"]').innerHTML = `<thead><tr>
			<th>Stock No.</th><th>Unit</th><th>Description</th><th>Quantity</th><th>Unit Cost</th><th>Total Cost</th></tr>
			</thead><tbody><tr><form id="EditForm"><input type="text" name="editType" value="PR" hidden form="EditForm">
				<input type="text" name="projId" hidden form="EditForm">
					<td><input type="text" name="prUpStk" id="prUpStk" class="form-control" form="EditForm"></td>
					<td><input type="text" name="prUpUnt" id="prUpUnt" class="form-control" form="EditForm"></td>
					<td><textarea name="" id="prUpDesc" cols="30" rows="1" maxlength="1000" class="form-control" form="EditForm"></textarea></td>
					<td><input type="number" name="prUpQty" id="prUpQty" class="form-control" min="1" form="EditForm"></td>
					<td><input type="number" name="prUpUc" id="prUpUc" class="form-control" step=".01" min="0.01" form="EditForm"></td>
					<td><input type="number" name="prUpTc" id="prUpTc" class="form-control" readonly step=".01" min="0.01" form="EditForm"></td>
					</form></tr></tbody>`;

			var btnQ = document.getElementById('prUpQty');
			var btnUc = document.getElementById('prUpUc');
			var btnTc = document.getElementById('prUpTc');
			
			document.querySelector('[name="projId"]').value = prData[0].innerText;
			document.getElementById('prUpStk').value = prData[2].innerText;
			document.getElementById('prUpUnt').value = prData[3].innerText;
			document.getElementById('prUpDesc').value = prData[4].innerText;
			btnQ.value = prData[5].innerText;
			btnUc.value = prData[6].innerText;
			btnTc.value = prData[7].innerText;

			btnQ.addEventListener('change', function(){
				btnTc.value = (this.value * btnUc.value).toFixed(2);
			});

			btnUc.addEventListener('change', function(){
				btnTc.value = (btnQ.value * this.value).toFixed(2);
			});
		}

		function joEdit(e){
			var joData = e.parentNode.parentNode.parentNode.children;
			document.querySelector('[dataFor="userEdit"]').innerHTML = `<thead><tr>
			<th data-toggle="true">List Title</th><th>Lot Estimated Cost</th><th data-hide="all">Tags</th>
			<th data-hide="all">Notes</th></tr></thead><tbody><tr><form id="EditForm">
				<input type="text" name="projId" hidden form="EditForm">
				<input type="text" name="editType" value="JO" hidden form="EditForm">
				<td><input type="text" name="joList" id="joList" class="form-control" form="EditForm"></td>
				<td><input type="number" name="joCost" id="joCost" class="form-control" form="EditForm" step=".01" min="0.01"></td>
				<td><input type="text" name="joTags" id="joTags" class="form-control" form="EditForm" data-role="tagsinput"></td>
				<td><textarea name="joNotes" id="joNotes" placeholder="Some text" class="form-control" form="EditForm"></textarea></td>		
				</form></tr></tbody>`;
			document.querySelector('[name="projId"]').value = joData[0].innerText;
			document.getElementById('joList').value = joData[1].innerText;
			document.getElementById('joCost').value = joData[2].innerText;
			document.getElementById('joTags').value = joData[3].innerText;
			document.getElementById('joNotes').value = joData[4].innerText;
			$('#joTags').tagsinput();
		}

		function del(e){
			var delData = e.parentNode.parentNode.parentNode.children;
			SendDoSomething("POST", "xhr-item-update.php", {
				editType: "DEL",
				projId: delData[0].innerText
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
	</script>
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
													<span class="label label-warning">2</span> Cards with the orange header are request forms that are already submitted and received by the PrMO or in the process of Technical Member evaluation, Therefore modifying of listing in this request are strictly monitored by our personnels, also modification on this request requires approval from our personnel for your modification/s to be applied wether it is your personal reason or modification required by the Technical Member.
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
											<?php echo $request->form_ref_no?>
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
												<th>Line</th>
												<th>Stock No.</th>
												<th>Unit</th>
												<th>Description</th>
												<th>Quantity</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>
												<th>Actions</th>
											</tr>
											</thead>
											<tbody>
											<?php
												$line = 1;
												foreach($content as $detail){
											?>
											<tr>
												<td style="display:none;"><?php echo $refno.":".$detail->lot_id.":".$detail->ID?></td>
												<td><?php echo $line;?></td>
												<td><?php echo $detail->stock_no; ?></td>
												<td><?php echo $detail->unit; ?></td>
												<td class="tddescription"><?php echo $detail->item_description; ?></td>
												<td><?php echo $detail->quantity; ?></td>
												<td><?php echo $detail->unit_cost; ?></td>
												<td><?php echo $detail->total_cost; ?></td>
												<td class="text-center">
													<div class="btn-group">

													<?php
														if($user->isEvaluated($refno)){
															echo '<a class="btn btn-danger btn-rounded btn-outline" style="color:red">Disabled</a>';
														}else{
															echo '
															<button class="btn-outline btn-success btn btn-xs" onclick="prEdit(this)" style="" data-toggle="modal" data-target="#userEdit">Edit</button>
															<button class="btn-outline btn-danger btn btn-xs" onclick="del(this)">Delete</button>
															';
														}
													?>

													</div>
												</td>
												
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

                                    <th data-toggle="true">List Title</th>
                                    <th>Lot Estimated Cost</th>
                                    <th data-hide="all">Tags</th>
                                    <th data-hide="all">Notes</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

									<?php
										foreach($content as $detail){
									?>
										<tr>
										<td style="display:none;"><?php echo $refno.":".$detail->lot_id.":".$detail->ID?></td>
											<td><?php echo $detail->header;?></td>
											<td><?php echo $detail->lot_cost;?></td>
											<td><?php echo str_replace(",", ", ", $detail->tags);?></td>
											<td><?php echo $detail->note;?></td>
											<td class="text-center">
												<div class="btn-group">
													<button class="btn-outline btn-success btn btn-xs" style="" onclick="joEdit(this)" data-toggle="modal" data-target="#userEdit">Edit</button>
													<button class="btn-outline btn-danger btn btn-xs" onclick="del(this)">Delete</button>
												</div>
											</td>									
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
		document.querySelector('[dataFor="userEditSubmit"]').addEventListener('click', function(){
			var EditData = $('#EditForm').serialize();
			SendDoSomething("POST", "xhr-item-update.php", EditData, {
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
