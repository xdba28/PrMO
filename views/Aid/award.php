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

    <title>PrMO OPPTS | Award</title>
	<?php include "../../includes/parts/admin_styles.php"?>

</head>

<body class="fixed-navigation">
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
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Canvass Returns</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Ongoing Projects</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                       <a href="Dashboard" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>
			</div>

	<?php
		if(!empty($_GET)){
			$id = base64_decode($_GET['q']);

			$project = $user->get('projects', array('project_ref_no', '=', $id));

			if($project){
	?>	
			
			<div class="wrapper wrapper-content animated fadeInUp">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox">
							<div class="ibox-title">
								<?php echo '<h5>Select Lot from '.$id.'</h5>';?>
							</div>
							<div class="ibox-content">
								<div class="row">
									<div class="col-lg-3">
										<select name="lot" id="lot" class="form-control">
											<option value="">Select Lot</option>
											<?php
												foreach($user->getAll('canvass_forms', array('gds_reference', '=', $id)) as $key => $lot){
													$num = $key + 1;
													// check if lot awarded
													if($lot->lot_fail_option !== "1"){
														echo '<option value="'.$lot->id.'-'.$lot->title.'">Lot '.$num.' - '.$lot->title.'</option>';
													}
												}
											?>
										</select>
									</div>
									<div class="col-lg-9">
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													<label class="form-label" for="place">Place of delivery</label>
													<input type="text" id="place" name="place" class="form-input" required>
												</div>
												<div class="form-group">
													<div class="input-group date">
														<span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span>
														<input type="text" id="date" name="date" class="form-control" placeholder="Date of delivery">
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label class="form-label" for="term">Delivery term</label>
													<input type="text" id="term" name="term" class="form-input" required>
												</div>
												<div class="form-group">
													<label class="form-label" for="pay">Mode of payment</label>
													<input type="text" id="pay" name="pay" class="form-input" required>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="lot-data">

				</div>
			<?php

			}else{
				include('../../includes/errors/404.php');
				echo"<br><br><br><br><br><br>";
			}
		}else{
			include('../../includes/errors/404.php');
			echo"<br><br><br><br><br><br>";
		}	
	?>
				
				<!-- </form> -->
			</div>
			<button class="back-to-top" type="button"></button>
			<div class="footer">
				<?php include '../../includes/parts/footer.php';?>
			</div>
        </div>
    </div>
	<?php include_once '../../includes/parts/modals.php';?>
    <?php include_once '../../includes/parts/admin_scripts.php'; ?>

</body>
<script>
	$(document).ready(function(){
		$('.input-group.date').datepicker({
			todayBtn: "linked",
			keyboardNavigation: false,
			forceParse: false,
			calendarWeeks: true,
			autoclose: true
		});

		// setTimeout(function(){
		// 	$('#minimizer').trigger('click');
		// }, 1000);

		$('#lot').on('change', function(){
			if(this.value !== ""){
				var canvass_form_id = this.value.split('-')[0];
				SendDoSomething("GET", "xhr-get-lot.php", {
					id: '<?php echo $id;?>',
					lot: this.value
				}, {
					do: function(d){
						console.log(d);
	
						let supplier = '';
						d.suppliers.forEach(function(e, i){
							supplier += `<option value="${e.cvsp_id}">${e.name}</option>`;
						});
	
						$('#lot-data').html(`
						<div class="row">
							<div class="col-lg-12  animated fadeInRight">
								<div class="ibox">
									<div class="ibox-title">
										<div class="row">
											<div class="col-lg-6">
												<h4>${d.lot.CanvassDetails.title} --- ${d.lot.CanvassDetails.cost}</h4>
											</div>
											<div class="col-lg-6">
												<div class="row">
													<div class="col-lg-6">
														<select class="form-control" id="selected-supplier">
															<option>Select Supplier</option>
															${supplier}
														</select>
													</div>
													<div class="col-lg-6" id="remark">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="ibox-content">
										<input type="hidden" id="lot-type" value="${d.lot.CanvassDetails.per_item}">
										<input type="hidden" id="req-type" value="${d.lot.CanvassDetails.type}">
										<div class="table-responsive">
											<table class="table table-bordered">
												<thead id="head">
												</thead>
												<tbody id="body">
												</tbody>
											</table>
										</div>
										<div class="right">
											<button type="button" class="btn btn-rounded btn-primary" id="btn-award"></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						`);
	
						if(d.lot.CanvassDetails.per_item === "1"){
							document.getElementById('selected-supplier').parentNode.className = 'col-lg-12';
							document.getElementById('remark').className = '';

							if(d.lot.CanvassDetails.type === "PR"){
								$('#head').html(`<tr><th>Award Item</th><th>Item Description</th><th>Quantity</th>
									<th>Unit Cost</th><th>Total Cost</th><th>Offered Item</th><th>Offered Unit Price</th>
									<th>Offered Total Price</th><th>Remark</th></tr>`);
								$('#btn-award').text('Award Selected');
							}else{
								$('#head').html(`<tr>
									<th>Award Item</th>
									<th>Description</th>
									<th>Tags</th>
									<th>Offered Price</th>
									<th>Remark</th>
								</tr>`);
								$('#btn-award').text('Award Selected');
							}
						}else{
							if(d.lot.CanvassDetails.type === "PR"){
								$('#remark').html(`<h3>Remark:  <div id="supplier-remark"></div></h3>`);
								$('#head').html(`<tr><th>Item Description</th><th>Quantity</th><th>Unit Cost</th>
									<th>Total Cost</th><th>Offered Item</th><th>Offered Unit Price</th>
									<th>Offered Total Price</th></tr>`);
								$('#btn-award').text('Award');
							}else{
								$('#remark').html(`<h3>Remark:  <div id="supplier-remark"></div></h3>`);
								$('#head').html(`<tr>
									<th>Description</th>
									<th>tags</th>
									<th>Offered Price</th>
								</tr>`);
								$('#btn-award').text('Award');
							}
						}
	
						$('#selected-supplier').on('change', function(){
							$('#body').html('');
							let selected_supplier = this.value;
							let supplier_remark = d.suppliers.find(function(el){
								return el.cvsp_id === selected_supplier;
							});
							$('#supplier-remark').html(supplier_remark.remark);
							d.lot.items.forEach(function(e, i){
								let supplier = d.canvass_returns[i].find(function(el){
									return el.cvsp_id === selected_supplier;
								});

								if(e.awarded !== "1"){

									if(d.lot.CanvassDetails.per_item === "1"){
										// if else PR : JO
										if(d.lot.CanvassDetails.type === "PR"){
											$('#body').append(`
											<tr>
												<input type="hidden" id="supplier-id" value="${supplier.cvsp_id}">
												<td class="center"><input type="checkbox" data-type="award" class="i-checks" data-itemid="${e.item_id}"></td>
												<td>${e.item_description}</td>
												<td>${e.quantity}</td>
												<td>&#x20b1; ${formatMoney(e.unit_cost)}</td>
												<td>&#x20b1; ${formatMoney(e.total_cost)}</td>
												<td>${supplier.offered}</td>
												<td>&#x20b1; ${formatMoney(supplier.price)}</td>
												<td>&#x20b1; ${formatMoney(supplier.price * e.quantity)}</td>
												<td>${supplier.item_remark}</td>
											</tr>
											`);	
										}else{
											$('#body').append(`
											<tr>
												<input type="hidden" id="supplier-id" value="${supplier.cvsp_id}">
												<td class="center"><input type="checkbox" data-type="award" class="i-checks" data-itemid="${e.item_id}"></td>
												<td>${e.header}</td>
												<td>${e.tags}</td>
												<td>&#x20b1; ${formatMoney(supplier.price)}</td>
												<td>${supplier.item_remark}</td>
											</tr>
											`);
										}
										$('.i-checks').iCheck({
											checkboxClass: 'icheckbox_square-green',
											radioClass: 'iradio_square-green'
										});
									}else{
										// if else PR : JO
										if(d.lot.CanvassDetails.type === "PR"){
											$('#body').append(`
											<tr>
												<input type="hidden" id="supplier-id" value="${supplier.cvsp_id}">
												<td>${e.item_description}</td>
												<td>${e.quantity}</td>
												<td>&#x20b1; ${formatMoney(e.unit_cost)}</td>
												<td>&#x20b1; ${formatMoney(e.total_cost)}</td>
												<td>${supplier.offered}</td>
												<td>&#x20b1; ${formatMoney(supplier.price)}</td>
												<td>&#x20b1; ${formatMoney(supplier.price * e.quantity)}</td>
											</tr>
											`);
										}else{
											$('#body').append(`
											<tr>
												<input type="hidden" id="supplier-id" value="${supplier.cvsp_id}">
												<td>${e.header}</td>
												<td>${e.tags}</td>
												<td>&#x20b1; ${formatMoney(supplier.price)}</td>
											</tr>
											`);
										}
									}
								}

							});
						});
						
						$('#btn-award').on('click', function(){
							// get delevery info
							let place = $('#place');
							let date = $('#date');
							let term = $('#term');
							let pay = $('#pay');
							let lot_type = $("#lot-type").val();

							if(lot_type === "1"){
								let selected = $('[data-type="award"]:checked');
								if(selected.length !== 0 && place.val() !== '' && date.val() !== '' && term.val() !== '' && pay.val() !== ''){
									let items = [];
									let delivery = {
										place: place.val(),
										date: date.val(),
										term: term.val(),
										pay: pay.val()
									};
									selected.each(function(i, e){
										items.push(e.dataset.itemid);
									});

									SendDoSomething("POST", 'xhr-award-submit.php', {
										items, 
										delivery, 
										lot_type: lot_type, 
										supplier: $('#supplier-id').val(), 
										cvf_id: canvass_form_id,
										req_type: $('#req-type').val(),
										gds: '<?php echo $id?>'
									}, {
										do: function(result){
											console.log(result);
											if(result.success === 'true'){
												swal({
													title: "Success!",
													text: "Lot successfully awarded.",
													type: "success"
												});
												$('#lot').val('');
												$('#lot-data').html('');
												place.val('');
												date.val('');
												term.val('');
												pay.val('');
											}
										}
									});
								}else{
									swal({
										title: "Invalid action!",
										text: "Please select an item and fill up delivery details.",
										confirmButtonColor: "#DD6B55",
										type: "error"
									});
								}
							}else{
								if(place.val() !== '' && date.val() !== '' && term.val() !== '' && pay.val() !== ''){
									let delivery = {
										place: place.val(),
										date: date.val(),
										term: term.val(),
										pay: pay.val()
									};
									SendDoSomething("POST", 'xhr-award-submit.php', {
										delivery,
										lot_type: lot_type, 
										supplier: $('#supplier-id').val(), 
										cvf_id: canvass_form_id,
										req_type: $('#req-type').val(),
										gds: '<?php echo $id?>'
									}, {
										do: function(result){
											console.log(result);
											if(result.success === 'true'){
												swal({
													title: "Success!",
													text: "Lot successfully awarded.",
													type: "success"
												});
												$('#lot').val('');
												$('#lot-data').html('');
												place.val('');
												date.val('');
												term.val('');
												pay.val('');
											}
										}
									});
								}else{
									swal({
										title: "Invalid action!",
										text: "Fill up delivery details.",
										confirmButtonColor: "#DD6B55",
										type: "error"
									});
								}
							}


						});
	
					}
	
				});
				// end of SendDoNothing
			}else{
				$('#lot-data').html('');
			}

		});
	});
</script>
</html>
