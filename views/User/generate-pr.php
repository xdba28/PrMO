<?php 
    require_once('../../core/init.php');
    $user = new User(); 
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
	}
	
	if(Input::exists()){
		if(Token::check("prToken" ,Input::get('prToken'))){
			if(Input::get('lot_count') == ''){
					
					try{
						//register project details in "project_request_forms"table
						$current_year = date('Y');
						$form_ref_no =  'PR'.$current_year.'-'.StringGen::generate(); //this would be the refence number of the form
						$date_created =  date('Y-m-d H:i:s'); //this would be a the identifier for registering of lots
						$number_of_lots = Input::get('lot_count'); // number of lots for this request form
			
						$rows_per_lot = json_decode(Input::get('row_count'), true); //decode th row counter per lot
						$myArray[0] = $rows_per_lot["lst"] + 1;
					
						$user->register('project_request_forms', array(
			
							'form_ref_no' => $form_ref_no,
							'title' => Input::get('title'),
							'requested_by' => Session::get(Config::get('session/session_name')),
							'noted_by' => Input::get('noted'),
							'verified_by' => Input::get('verified'),
							'approved_by' => Input::get('approved'),
							'type' => 'PR',
							'date_created' => $date_created
			
						));
						//register static lot details in "lots" table
						$user->register('lots', array(
		
							'request_origin' => $form_ref_no,
							'lot_no' => 101,
							'lot_title' => 'static lot',
							'lot_cost' => Input::get('L0-TLC'),
							'note' => 'none'
		
						));
						//register all item rows related to the static lot in "lot_content_for_pr" table		
						$temp = $user->ro_ln_composite($form_ref_no, 101);
						$lot_id = $temp->lot_id;
						for($y=0; $y<$myArray[0]; $y++){ //$y is item per lot level					
		
							$stock_no = 'L0-stk-'.$y;    			//L0-stk-0
							$unit = 'L0-unit-'.$y;		 			//L0-unit-0
							$item_description = 'L0-desc-'.$y;		//L0-desc-0
							$quantity = 'L0-qty-'.$y;				//L0-qty-0
							$unit_cost = 'L0-Ucst-'.$y;		 		//L0-Ucst-0
							$total_cost = 'L0-Tcst-'.$y;		 	//L0-Tcst-0
		
							$user->register('lot_content_for_pr', array(
		
								'lot_id_origin' => $lot_id,
								'stock_no' => Input::get($stock_no),
								'unit' => Input::get($unit),
								'item_description' => Input::get($item_description),
								'quantity' => Input::get($quantity),
								'unit_cost' => Input::get($unit_cost),
								'total_cost' => Input::get($total_cost)
		
							));
						}
						
						//proceed to printing the actual form
						Session::flash('Request', $form_ref_no.":PR");
						Redirect::To('../../bac/forms/pr-jo-doc');

						//pop sweet alert "Your request has been registered and ready to download";
						//after some seconds? redirect to pr/jo created
					}catch(Exception $e){
						die($e->getMessage());
					}
			}else{
					
				try{
					//register project details in "project_request_forms"table
					$current_year = date('Y');
					$form_ref_no =  'PR'.$current_year.'-'.StringGen::generate(); //this would be the refence number of the form
					$date_created =  date('Y-m-d H:i:s'); //this would be a the identifier for registering of lots
					$number_of_lots = Input::get('lot_count'); // number of lots for this request form
		
					$rows_per_lot = json_decode(Input::get('row_count'), true); //decode th row counter per lot
					$counter = 0;
					foreach($rows_per_lot as $element){
						$myArray[$counter] = $element["lst"] + 1;
						$counter++;
					}
		
					$user->register('project_request_forms', array(
		
						'form_ref_no' => $form_ref_no,
						'title' => Input::get('title'),
						'requested_by' => Session::get(Config::get('session/session_name')),
						'noted_by' => Input::get('noted'),
						'verified_by' => Input::get('verified'),
						'approved_by' => Input::get('approved'),
						'type' => 'PR',
						'date_created' => $date_created
		
					));
		
					//register lot general details in "lots" table
					for($x=0; $x<$number_of_lots; $x++){  //$x is lot level
					
		
						$lot_title = 'L'.$x.'-title'; //L${index}-title				
						$lot_cost = 'L'.$x.'-TLC';
						$lot_no = $x + 1;
		
						$user->register('lots', array(
		
							'request_origin' => $form_ref_no,
							'lot_no' => $lot_no,
							'lot_title' => Input::get($lot_title),
							'lot_cost' => Input::get($lot_cost),
							'note' => 'none'
		
						));
		
			
						//register all item rows per lot in "lot_content_for_pr" table		
						$temp = $user->ro_ln_composite($form_ref_no, $lot_no);
						$lot_id = $temp->lot_id;
						
						for($y=0; $y<$myArray[$x]; $y++){ //$y is item per lot level					
		
							$stock_no = 'L'.$x.'-stk-'.$y;    			//L${index}-stk-0
							$unit = 'L'.$x.'-unit-'.$y;		 			//L${index}-unit-0
							$item_description = 'L'.$x.'-desc-'.$y;		//L${index}-desc-0
							$quantity = 'L'.$x.'-qty-'.$y;				//L${index}-qty-0
							$unit_cost = 'L'.$x.'-Ucst-'.$y;		 	//L${index}-Ucst-0
							$total_cost = 'L'.$x.'-Tcst-'.$y;		 	//L${index}-Tcst-0
		
							$user->register('lot_content_for_pr', array(
		
								'lot_id_origin' => $lot_id,
								'stock_no' => Input::get($stock_no),
								'unit' => Input::get($unit),
								'item_description' => Input::get($item_description),
								'quantity' => Input::get($quantity),
								'unit_cost' => Input::get($unit_cost),
								'total_cost' => Input::get($total_cost)
		
							));
						}	
					}
		
					//proceed to printing the actual form
					Session::flash('Request', $form_ref_no.":PR");
					Redirect::To('../../bac/forms/pr-jo-doc');
					
				}catch(Exception $e){
					die($e->getMessage());
				}				
			}
	  	}
	}
 
					
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Empty Page</title>

	<?php include_once'../../includes/parts/user_styles.php'; ?>


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
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Purchase Request Form</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Request Forms</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Purchase Request</strong>
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
            <div class="wrapper wrapper-content">
					<!-- Content here-->

			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs-container">
							<ul class="nav nav-tabs">
								<li><a class="nav-link active" data-toggle="tab" href="#tab-1">Project &nbsp&nbsp<i class="ti-folder" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-2">Particulars &nbsp&nbsp<i class="ti-pencil-alt" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-3">Signatories &nbsp&nbsp<i class="ti-user" style="font-size:18px"></i></a></li>
							</ul>
							<div class="tab-content">
								<div><form id="pr_form" method="POST"></div>
								
								<div id="tab-1" class="tab-pane active">
									<div class="panel-body">
									   <h2>Project Information</h2>

										<p>Specify the required fields to generate the Job Order Form that suits your need.</p>
										<div class="row">
											<div class="col-lg-7">
												<div class="form-group">
													<label>Project title *</label>
													<input id="title" name="title" type="text" class="form-control" form="pr_form" required>
												</div>
												<div class="form-group">
													<label>Overall Estimated Cost *</label>
													<input id="estimated_cost" name="estimated_cost" type="text" class="form-control" form="pr_form" required>
												</div>
												<div class="form-group"id="popOver" data-trigger="hover" title="Reminder" data-placement="right" data-content="If you wish to have an uncategorized item list, you can leave this field blank and immediately proceed to step 2 `Particulars`  ">
													<label class="font-normal"></label>
													<div>
														<select data-placeholder="Choose Category" class="chosen-select" multiple style="width:350px;" tabindex="4" name="category" form="pr_form">															
															<option value="Common Office Supplies">Common Office Supplies</option>
															<option value="Paper Materials & Products">Paper Materials & Products</option>          
															<option value="Hardware Supplies">Hardware Supplies</option>
															<option value="Sporting Supplies">Sporting Supplies</option>
															<option value="Common Janitorial/Cleaning Supplies">Common Janitorial/Cleaning Supplies</option>
															<option value="IT Supplies">IT Supplies</option>
															<option value="Laboratory Supplies">Laboratory Supplies</option>
															<option value="Computer Supplies">Computer Supplies</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-3">
												<div class="text-center">
													<div style="margin-left: 100px">
														<i class="ti-layout-tab" style="font-size: 180px;color: #FFD700 "></i>
													</div>
												</div>
											</div>	
										</div>

									</div>	
								</div>
								<div id="tab-2" class="tab-pane">
									<div class="panel-body">
										<h2>Particulars Setting</h2>
										<p>Some shitty explaination what the hell is going on</p>

										<div class="row">
											<div class="col-lg-12" id="stp-2">
											<div class="ibox">
												<div class="ibox-title">
													<div class="project-alert alert-warning">
														<h5>Below is the item list for uncategorized Purchase Request<input type="text" hidden name="L0-title" form="pr_form" readonly></h5>
													</div>
													<div class="add-project">
													<div class="btn-group">
														<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-rounded btn-outline">Add Specific No. of Rows</button>
														<ul class="dropdown-menu">
															<li><input type="number" id="rowCount" class="form-control" min="1"></li>
														</ul>
													</div>													
														<button type="button" data-type="lst-add" id="pr-static" class="btn btn-success btn-rounded btn-outline">Add Listing <i class="ti ti-plus" style="font-weight:900"></i></button>
													</div>
												</div>
												<div class="ibox-content">
													<table class="table table-bordered">
														<thead>
														<tr>
															<th class="center">Stock No.</th>
															<th class="center">Unit</th>
															<th class="center">Item Description</th>
															<th class="center">Quantity</th>
															<th class="center">Unit Cost</th>
															<th class="center">Total Cost</th>
														</tr>
														</thead>
														<tbody id="pr-tbl-static">
															<tr id="pr-0-r-0">
																<td><input type="text" name="L0-stk-0" data-cnt="pr-0-lst-0" class="form-control" form="pr_form"></td>
																<td class="center"><input type="text" name="L0-unit-0" data-cnt="pr-0-lst-0" class="form-control" form="pr_form" required></td>
																<td><textarea rows="1" cols="30" name="L0-desc-0" data-cnt="pr-0-lst-0" class="form-control" maxlength="1000" form="pr_form" required></textarea></td>
																<td class="center"><input type="number" step=".01" data="qty" data-cnt="pr-0-qty-lst-0" name="L0-qty-0" class="form-control" min="0.01" form="pr_form" required></td>
																<td class="right"><input type="number" step=".01" data="Ucst" data-cnt="pr-0-Ucst-lst-0" name="L0-Ucst-0" class="form-control" min="0.01" form="pr_form" required></td>
																<td class="right"><input type="number" step=".01" data="Tsct" data-cnt="pr-0-Tsct-lst-0" name="L0-Tcst-0" class="form-control" min="0.01" readonly form="pr_form" required></td>																
															</tr>
														</tbody>
															<tr>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td>Total Lot Cost: </td>
																<td><input type="number" step=".01" class="form-control" name="L0-TLC" readonly form="pr_form" required></td>
															</tr>
													</table>
													<div style="text-align: right">
														<button type="button" data="del" data-list="pr-0-r-0" class="btn btn-danger btn-rounded btn-outline">Delete Last Row <i class="ti ti-minus" style="font-weight:900"></i></button>
													</div>
												</div>
											</div>
											</div>		
										</div>
									</div>
								</div>
								<div id="tab-3" class="tab-pane">
									<div class="panel-body">
										   <h2>Project Signatories</h2>

											<p>Specify all signatories to finalized this form.</p>
											
											<div class="row">
												<div class="col-lg-7">
													<div class="form-group">
														<label>End User *</label>
														<input id="enduser" name="enduser" type="text" value="<?php echo $user->fullname();?>" class="form-control" readonly form="pr_form" required>
													</div>
													<div class="form-group">
														<label>Noted By *</label>
														<input id="noted" name="noted" type="text"  class="form-control" form="pr_form" required>
													</div>
													<div class="form-group">
														<label>Verified By *</label>
														<input id="verified" name="verified" type="text"  class="form-control" form="pr_form" required>
													</div>
													<div class="form-group">
														<label>Aproved By *</label>
														<input id="approved" name="approved" type="text"  class="form-control" form="pr_form" required>
													</div>													
												</div>
												<div class="col-lg-3">
													<div class="text-center">
														<div style="margin-left: 100px;  margin-top:20px">
															<i class="ti-user" style="font-size: 180px;color: #FFD700;"></i>
														</div>
													</div>
												</div>	
												<div class="col-md-7">
													<input type="text" name="prToken" value="<?php echo Token::generate("prToken");?>" hidden form="pr_form">
													<input type="text"  id="row_count" name="row_count" class="form-control" readonly form="pr_form">
													<input type="text"  id="lot_count" name="lot_count" class="form-control" readonly form="pr_form">	

													<button class="btn btn-primary btn-outline pull-right" type="submit" form="pr_form">Finish</button>
													<button class="btn btn-danger btn-outline pull-right" style="margin-right:5px">Cancel</button>													
												</div>
											</div>											
									</div>
								</div>
								
							</div>
						</div>
					</div>
				
				</div><br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br>
			</div>
				
											
				

 

					
			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once '../../includes/parts/user_scripts.php'; ?>


</body>
<script>
	$(function()
	{
		var objStat = {lst: 0};
		$('#row_count').val(JSON.stringify(objStat));
		$('#pr-static').on('click', function()
		{
			objStat = JSON.parse($('#row_count').val());
			objStat.lst++;
			var tmp_static = `
			<tr id="pr-0-r-${objStat.lst}">
				<td><input type="text" name="L0-stk-${objStat.lst}" data-cnt="pr-0-lst-0" class="form-control" form="pr_form"></td>
				<td class="center"><input type="text" name="L0-unit-${objStat.lst}" data-cnt="pr-0-lst-0" class="form-control" form="pr_form" required></td>
				<td><textarea rows="1" cols="30" name="L0-desc-${objStat.lst}" data-cnt="pr-0-lst-${objStat.lst}" class="form-control" maxlength="1000" form="pr_form" required></textarea></td>
				<td class="center"><input type="number" step=".01" data="qty" data-cnt="pr-0-qty-lst-${objStat.lst}" name="L0-qty-${objStat.lst}" class="form-control" min="0.01" form="pr_form" required></td>
				<td class="right"><input type="number" step=".01" data="Ucst" data-cnt="pr-0-Ucst-lst-${objStat.lst}" name="L0-Ucst-${objStat.lst}" class="form-control" min="0.01" form="pr_form" required></td>
				<td class="right"><input type="number" step=".01" data="Tsct" data-cnt="pr-0-Tsct-lst-${objStat.lst}" name="L0-Tcst-${objStat.lst}" class="form-control" min="0.01" readonly form="pr_form" required></td>
			</tr>`;
			$('#pr-tbl-static').append(tmp_static);
			$('#row_count').val(JSON.stringify(objStat));
			$('[data="qty"]').on('change', function()
			{
				var TC = 0;
				var Q_qty = $(this).val();
				var Q_el_data = $(this).attr("data-cnt").split("-");
				var Q_Ucst = $(`[data-cnt="pr-0-Ucst-lst-${Q_el_data[4]}"]`).val();
				$(`[data-cnt="pr-0-Tsct-lst-${Q_el_data[4]}"]`).val((Q_qty * Q_Ucst).toFixed(2));
				$(`[data-cnt|="pr-0-Tsct-lst"]`).each(function(){
					if($(this).val() !== "") TC += parseFloat($(this).val());
				});
				$(`[name="L0-TLC"]`).val((TC).toFixed(2));				
			});
			$('[data="Ucst"]').on('change', function()
			{
				var TC = 0;
				var U_Ucst = $(this).val();
				var U_el_data = $(this).attr("data-cnt").split("-");
				var U_qty = $(`[data-cnt="pr-0-qty-lst-${U_el_data[4]}"]`).val();
				$(`[data-cnt="pr-0-Tsct-lst-${U_el_data[4]}"]`).val((U_qty * U_Ucst).toFixed(2));
				$(`[data-cnt|="pr-0-Tsct-lst"]`).each(function(){
					if($(this).val() !== "") TC += parseFloat($(this).val());
				});
				$(`[name="L0-TLC"]`).val((TC).toFixed(2));
			});
		});

		$('#rowCount').on('change', function()
		{
			var objStat = {lst: 0};
			$('#pr-tbl-static').html('');
			for(let i = 0 ; i < $(this).val() ; i++)
			{
				var tmp_static = `
				<tr id="pr-0-r-${objStat.lst}">
					<td><input type="text" name="L0-stk-${objStat.lst}" data-cnt="pr-0-lst-0" class="form-control" form="pr_form"></td>
					<td class="center"><input type="text" name="L0-unit-${objStat.lst}" data-cnt="pr-0-lst-0" class="form-control" form="pr_form" required></td>
					<td><textarea rows="1" cols="30" name="L0-desc-0" data-cnt="pr-0-lst-${objStat.lst}" class="form-control" maxlength="1000" form="pr_form" required></textarea></td>
					<td class="center"><input type="number" step=".01" data="qty" data-cnt="pr-0-qty-lst-${objStat.lst}" name="L0-qty-${objStat.lst}" class="form-control" min="0.01" form="pr_form" required></td>
					<td class="right"><input type="number" step=".01" data="Ucst" data-cnt="pr-0-Ucst-lst-${objStat.lst}" name="L0-Ucst-${objStat.lst}" class="form-control" min="0.01" form="pr_form" required></td>
					<td class="right"><input type="number" step=".01" data="Tsct" data-cnt="pr-0-Tsct-lst-${objStat.lst}" name="L0-Tcst-${objStat.lst}" class="form-control" min="0.01" readonly form="pr_form" required></td>
				</tr>`;
				$('#pr-tbl-static').append(tmp_static);
				$('[data="qty"]').on('change', function()
				{
					var TC = 0;
					var Q_qty = $(this).val();
					var Q_el_data = $(this).attr("data-cnt").split("-");
					var Q_Ucst = $(`[data-cnt="pr-0-Ucst-lst-${Q_el_data[4]}"]`).val();
					$(`[data-cnt="pr-0-Tsct-lst-${Q_el_data[4]}"]`).val((Q_qty * Q_Ucst).toFixed(2));
					$(`[data-cnt|="pr-0-Tsct-lst"]`).each(function(){
						if($(this).val() !== "") TC += parseFloat($(this).val());
					});
					$(`[name="L0-TLC"]`).val((TC).toFixed(2));				
				});
				$('[data="Ucst"]').on('change', function()
				{
					var TC = 0;
					var U_Ucst = $(this).val();
					var U_el_data = $(this).attr("data-cnt").split("-");
					var U_qty = $(`[data-cnt="pr-0-qty-lst-${U_el_data[4]}"]`).val();
					$(`[data-cnt="pr-0-Tsct-lst-${U_el_data[4]}"]`).val((U_qty * U_Ucst).toFixed(2));
					$(`[data-cnt|="pr-0-Tsct-lst"]`).each(function(){
						if($(this).val() !== "") TC += parseFloat($(this).val());
					});
					$(`[name="L0-TLC"]`).val((TC).toFixed(2));
				});
				objStat.lst++;
			}
			objStat.lst--;
			$('#row_count').val(JSON.stringify(objStat));
		});

		$('[data="qty"]').on('change', function()
		{
			var TC = 0;
			var Q_qty = $(this).val();
			var Q_el_data = $(this).attr("data-cnt").split("-");
			var Q_Ucst = $(`[data-cnt="pr-0-Ucst-lst-${Q_el_data[4]}"]`).val();
			$(`[data-cnt="pr-0-Tsct-lst-${Q_el_data[4]}"]`).val((Q_qty * Q_Ucst).toFixed(2));
			$(`[data-cnt|="pr-0-Tsct-lst"]`).each(function(){
				if($(this).val() !== "") TC += parseFloat($(this).val());
			});
			$(`[name="L0-TLC"]`).val((TC).toFixed(2));				
		});

		$('[data="Ucst"]').on('change', function()
		{
			var TC = 0;
			var U_Ucst = $(this).val();
			var U_el_data = $(this).attr("data-cnt").split("-");
			var U_qty = $(`[data-cnt="pr-0-qty-lst-${U_el_data[4]}"]`).val();
			$(`[data-cnt="pr-0-Tsct-lst-${U_el_data[4]}"]`).val((U_qty * U_Ucst).toFixed(2));
			$(`[data-cnt|="pr-0-Tsct-lst"]`).each(function(){
				if($(this).val() !== "") TC += parseFloat($(this).val());
			});
			$(`[name="L0-TLC"]`).val((TC).toFixed(2));
		});

		var arry = [];
		$('.chosen-select').chosen({width: "100%"}).on('change', function()
		{
			var TC = 0;
			var obj = [];
			$('#stp-2').html('');
			arry = $(this).val();
			arry.forEach(function(element, index)
			{								
				obj.push({lst: 0});
				var lst_tmp = `
				<div class="ibox">
					<div class="ibox-title">
						<div class="project-alert alert-warning">
							<h5>Below is the Item List for Lot ${index + 1} - ${element} <input type="text" hidden name="L${index}-title" form="pr_form" readonly value = "${element}"></h5>
						</div>
						<div class="add-project">
							<button type="button" data-type="lst-add" data-list="pr-${index}" class="btn btn-success btn-rounded btn-outline">Add Listing <i class="ti ti-plus" style="font-weight:900"></i></button>
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-bordered">
							<thead>
							<tr>
								<th class="center">Stock No.</th>
								<th class="center">Unit</th>
								<th class="center">Item Description</th>
								<th class="center">Quantity</th>
								<th class="center">Unit Cost</th>
								<th class="center">Total Cost</th>
							</tr>
							</thead>
							<tbody id="pr-tbl-${index}">
								<tr id="pr-${index}-r-0">
									<td><input type="text" name="L${index}-stk-0" data-cnt="pr-${index}-lst-0" class="form-control" form="pr_form"></td>
									<td class="center"><input type="text" name="L${index}-unit-0" data-cnt="pr-${index}-lst-0" class="form-control" form="pr_form" required></td>
									<td><textarea rows="1" cols="30" name="L${index}-desc-0" data-cnt="pr-${index}-lst-0" class="form-control" maxlength="1000" form="pr_form" required></textarea></td>
									<td class="center"><input type="number" step=".01" data="qty" data-cnt="pr-${index}-qty-lst-0" name="L${index}-qty-0" class="form-control" min="0.01" form="pr_form" required></td>
									<td class="right"><input type="number" step=".01" data="Ucst" data-cnt="pr-${index}-Ucst-lst-0" name="L${index}-Ucst-0" class="form-control" min="0.01" form="pr_form" required></td>
									<td class="right"><input type="number" step=".01" data="Tsct" data-cnt="pr-${index}-Tsct-lst-0" name="L${index}-Tcst-0" class="form-control" min="0.01" readonly form="pr_form" required></td>																
								</tr>
							</tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>Total Lot Cost: </td>
									<td><input type="number" step=".01" class="form-control" name="L${index}-TLC" readonly form="pr_form" required></td>
								</tr>
						</table>
						<div style="text-align: right">
							<button type="button" data="del" data-list="pr-${index}-r-0" class="btn btn-danger btn-rounded btn-outline">Delete Last Row <i class="ti ti-minus" style="font-weight:900"></i></button>
						</div>
					</div>
				</div>`;
				$('#stp-2').append(lst_tmp);
				$('#row_count').val(JSON.stringify(obj));
			});

			$('#lot_count').val(arry.length);
			$('[data="qty"]').on('change', function()
			{
				var TC = 0;
				var Q_qty = $(this).val();
				var Q_el_data = $(this).attr("data-cnt").split("-");
				var Q_Ucst = $(`[data-cnt="pr-${Q_el_data[1]}-Ucst-lst-${Q_el_data[4]}"]`).val();
				$(`[data-cnt="pr-${Q_el_data[1]}-Tsct-lst-${Q_el_data[4]}"]`).val((Q_qty * Q_Ucst).toFixed(2));
				$(`[data-cnt|="pr-${Q_el_data[1]}-Tsct-lst"]`).each(function(){
					if($(this).val() !== "") TC += parseFloat($(this).val());
				});
				$(`[name="L${Q_el_data[1]}-TLC"]`).val((TC).toFixed(2));				
			});

			$('[data="Ucst"]').on('change', function()
			{
				var TC = 0;
				var U_Ucst = $(this).val();
				var U_el_data = $(this).attr("data-cnt").split("-");
				var U_qty = $(`[data-cnt="pr-${U_el_data[1]}-qty-lst-${U_el_data[4]}"]`).val();
				$(`[data-cnt="pr-${U_el_data[1]}-Tsct-lst-${U_el_data[4]}"]`).val((U_qty * U_Ucst).toFixed(2));
				$(`[data-cnt|="pr-${U_el_data[1]}-Tsct-lst"]`).each(function(){
					if($(this).val() !== "") TC += parseFloat($(this).val());
				});
				$(`[name="L${U_el_data[1]}-TLC"]`).val((TC).toFixed(2));
			});

			$('[data-type="lst-add"]').on('click', function()
			{
				var pr_num = $(this).attr("data-list").split("-");
				obj[pr_num[1]].lst++;
				$('#row_count').val(JSON.stringify(obj));
				var tr_tmp = `
				<tr id="pr-${pr_num[1]}-r-${obj[pr_num[1]].lst}">
					<td><input type="text" name="L${pr_num[1]}-stk-${obj[pr_num[1]].lst}" data-cnt="pr-${pr_num[1]}-lst-${obj[pr_num[1]].lst}" class="form-control" form="pr_form"></td>
					<td class="center"><input type="text" name="L${pr_num[1]}-unit-${obj[pr_num[1]].lst}" data-cnt="pr-${pr_num[1]}-lst-${obj[pr_num[1]].lst}" class="form-control" form="pr_form" required></td>
					<td><textarea rows="1" cols="30" name="L${pr_num[1]}-desc-${obj[pr_num[1]].lst}" data-cnt="pr-${pr_num[1]}-lst-${obj[pr_num[1]].lst}" class="form-control" maxlength="1000" form="pr_form" required></textarea></td>
					<td class="center"><input type="number" step=".01" data="qty" data-cnt="pr-${pr_num[1]}-qty-lst-${obj[pr_num[1]].lst}" name="L${pr_num[1]}-qty-${obj[pr_num[1]].lst}" class="form-control" min="0.01" form="pr_form" required></td>
					<td class="right"><input type="number" step=".01" data="Ucst" data-cnt="pr-${pr_num[1]}-Ucst-lst-${obj[pr_num[1]].lst}" name="L${pr_num[1]}-Ucst-${obj[pr_num[1]].lst}" class="form-control" min="0.01" form="pr_form" required></td>
					<td class="right"><input type="number" step=".01" data="Tsct" data-cnt="pr-${pr_num[1]}-Tsct-lst-${obj[pr_num[1]].lst}" name="L${pr_num[1]}-Tcst-${obj[pr_num[1]].lst}" class="form-control" min="0.01" readonly form="pr_form" required></td>																
				</tr>`;
				$(`#pr-tbl-${pr_num[1]}`).append(tr_tmp);
				$('[data="qty"]').on('change', function()
				{
					var TC = 0;
					var Q_qty = $(this).val();
					var Q_el_data = $(this).attr("data-cnt").split("-");
					var Q_Ucst = $(`[data-cnt="pr-${Q_el_data[1]}-Ucst-lst-${Q_el_data[4]}"]`).val();
					$(`[data-cnt="pr-${Q_el_data[1]}-Tsct-lst-${Q_el_data[4]}"]`).val((Q_qty * Q_Ucst).toFixed(2));
					$(`[data-cnt|="pr-${Q_el_data[1]}-Tsct-lst"]`).each(function(){
						if($(this).val() !== "") TC += parseFloat($(this).val());
					});
					$(`[name="L${Q_el_data[1]}-TLC"]`).val((TC).toFixed(2));
				});

				$('[data="Ucst"]').on('change', function()
				{
					var TC = 0;
					var U_Ucst = $(this).val();
					var U_el_data = $(this).attr("data-cnt").split("-");
					var U_qty = $(`[data-cnt="pr-${U_el_data[1]}-qty-lst-${U_el_data[4]}"]`).val();
					$(`[data-cnt="pr-${U_el_data[1]}-Tsct-lst-${U_el_data[4]}"]`).val((U_qty * U_Ucst).toFixed(2));
					$(`[data-cnt|="pr-${U_el_data[1]}-Tsct-lst"]`).each(function(){
						if($(this).val() !== "") TC += parseFloat($(this).val());
					});
					$(`[name="L${U_el_data[1]}-TLC"]`).val((TC).toFixed(2));
				});			
			});

			$('[data="del"]').on('click', function()
			{
				var rowData = $(this).attr("data-list").split("-");
				var remove = JSON.parse($('#row_count').val());
				$(`#pr-${rowData[1]}-r-${remove[rowData[1]].lst}`).remove();
				if(obj[rowData[1]].lst !== -1)
				{
					obj[rowData[1]].lst--;
					$('#row_count').val(JSON.stringify(obj));
				}
			});
		});
		
		$('[data="del"]').on('click', function()
		{
			var rowData = $(this).attr("data-list").split("-");
			var remove = JSON.parse($('#row_count').val());
			$(`#pr-${rowData[1]}-r-${remove.lst}`).remove();
			if(objStat.lst !== -1)
			{
				objStat.lst--;
				$('#row_count').val(JSON.stringify(objStat));
			}
		});
	});
</script>
</html>