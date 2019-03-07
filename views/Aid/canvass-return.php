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

		// echo "<pre>".print_r($_POST)."</pre>";
		// die();

		$user->startTrans();

		$gds = htmlspecialchars($_POST['gds']);

		$item_fail_count = 0;
		$lot_fail_count = 0;

		foreach($_POST['lot'] as $lot){

			if($lot['per_item']){

				// lot details
				$user->update('canvass_forms', 'id', $lot['lot_id'], array(
					'lot_fail_option' => 'By Item',
				));

				$supp_count = 0;
				foreach($lot['supplier'] as $supplier){
					
					// supplier details
					$user->register('canvass_supplier', array(
						'form_id' => $lot['lot_id'],
						'supplier' => htmlspecialchars($supplier),
						'remark' => NULL,
						'award' => 0
					));

					$supplier_id = $user->lastId();

					// item details
					for($i = 0; $i < $lot['item_count']; $i++){

						$item_fail = (isset($lot['fail'][$i])) ? true : false;
						if($item_fail){
							$item_fail_count++;
						}

						if($lot['type'] === "PR"){
							// update canvass_items_pr
							$user->update('canvass_items_pr', 'id', $lot['items'][$i]['id'], array(
								'item_fail' => $item_fail
							));
						}elseif($lot['type'] === "JO"){
							// update canvass_items_jo
							$user->update('canvass_items_jo', 'id', $lot['items'][$i]['id'], array(
								'item_fail' => $item_fail
							));
						}

						$user->register('canvass_quotation', array(
							'supplier_id' => $supplier_id,
							'item_id' => $lot['items'][$i]['id'],
							'offered' => htmlspecialchars($lot['offer'][$i][$supp_count]),
							'price' => htmlspecialchars($lot['items'][$i][$supp_count]),
							'remark' => htmlspecialchars($lot['remark'][$i][$supp_count]),
							'award_selected' => NULL
						));

						// count items failed
					}
					$supp_count++;
				}

				// notif if fail

			}else{

				$lot_fail = (isset($lot['fail'])) ? "1" : "0";

				// lot details
				$user->update('canvass_forms', 'id', $lot['lot_id'], array(
					'lot_fail_option' => $lot_fail,
				));

				if($lot_fail == "1"){
					$lot_fail_count++;
				}

				$supp_count = 0;
				foreach($lot['supplier'] as $supplier){

					// supplier details
					$user->register('canvass_supplier', array(
						'form_id' => $lot['lot_id'],
						'supplier' => htmlspecialchars($supplier),
						'remark' => htmlspecialchars($lot['remark'][$supp_count]),
						'award' => 0
					));

					$supplier_id = $user->lastId();

					// item details
					for($i = 0; $i < $lot['item_count']; $i++){

						$user->register('canvass_quotation', array(
							'supplier_id' => $supplier_id,
							'item_id' => $lot['items'][$i]['id'],
							'offered' => htmlspecialchars($lot['offer'][$i][$supp_count]),
							'price' => htmlspecialchars($lot['items'][$i][$supp_count]),
							'remark' => NULL,
							'award_selected' => NULL
						));

					}
					$supp_count++;
				}
			}
		}

		$user->update('projects', 'project_ref_no', $gds, array(
			'accomplished' => '5',
			'workflow' => 'Release of Abstract of Bid and BAC Resolution'
		));

		if($item_fail_count > 0){
			$user->register('project_logs',  array(
				'referencing_to' => $gds,
				'remarks' => "Project ".$gds." has declaired ".$item_fail_count." item/s as failed.",
				'logdate' => Date::translate('now', 'now'),
				'type' => 'IN'
			));
		}

		if($lot_fail_count > 0){
			$user->register('project_logs',  array(
				'referencing_to' => $gds,
				'remarks' => "Project ".$gds." has declaired ".$lot_fail_count." lot/s as failed.",
				'logdate' => Date::translate('now', 'now'),
				'type' => 'IN'
			));
		}
		
		// important update abstract & reso created
		$user->register('project_logs',  array(
			'referencing_to' => $gds,
			'remarks' => "AWARD^Abstract^Abstract of Bids is now available",
			'logdate' => Date::translate('now', 'now'),
			'type' => 'IN'
		));

		$end_user = $user->get('projects', array('project_ref_no', '=', $gds));
		foreach(json_decode($end_user->end_user, true) as $end_user){
			$user_details = $user->get('enduser', array('edr_id', '=', $end_user));
			
			$user->register('notifications', array(
				'recipient' => $end_user,
				'message' => "Abstract of Bids of project ".$gds." is now available",
				'datecreated' => Date::translate('test', 'now'),
				'seen' => 0,
				'href' => "project-details?refno=".base64_encode($gds)
			));
			notif(json_encode(array(
				'receiver' => $end_user,
				'message' => "Abstract of Bids of project ".$gds." is now available",
				'date' => Date::translate(Date::translate('test', 'now'), '1'),
				'href' => "project-details?refno=".base64_encode($gds)
			)));
			

			if($item_fail_count > 0){
				notif(json_encode(array(
					'receiver' => $end_user,
					'message' => "Project ".$gds." has declaired ".$item_fail_count." item/s as failed.",
					'date' => Date::translate(Date::translate('test', 'now'), '1'),
					'href' => "project-details?refno=".base64_encode($gds)
				)));
				// sms
				sms($user_details->phone, 'System', "Project ".$gds." has declaired ".$item_fail_count." item/s as failed.");
			}

			if($lot_fail_count > 0){
				notif(json_encode(array(
					'receiver' => $end_user,
					'message' => "Project ".$gds." has declaired ".$lot_fail_count." lot/s as failed.",
					'date' => Date::translate(Date::translate('test', 'now'), '1'),
					'href' => "project-details?refno=".base64_encode($gds)
				)));
				// sms
				sms($user_details->phone, 'System', "Project ".$gds." has declaired ".$lot_fail_count." lot/s as failed.");
			}
		
		}

		// NOTIFICATION OF AWARD TO END USER
		// notif if bidding failed or bidding succeeded


		$user->endTrans();

		Session::flash('update', 'Abstract of Bids successfully created.');
		Redirect::To('project-details?refno='.base64_encode($gds));
		die();

	}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Canvass Result</title>
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

			<div class="wrapper wrapper-content animated fadeInUp">
			<form action="" method="POST">
				

	<?php
		if(!empty($_GET)){
			$id = base64_decode($_GET['q']);

			$project = $user->get('projects', array('project_ref_no', '=', $id));

			if($project){
				
				$lots = $user->getAll('canvass_forms', array('gds_reference', '=', $id));
				$lot_count = 0;
				$item_count = 0;

				echo '<input type="hidden" name="gds" value="'.$id.'">';

				$option_1 = '';
				$option_2 = '';
				foreach($user->selectAll('supplier') as $supplier){
					if($supplier->type === "Services"){
						$option_1 .= '<option value="'.$supplier->s_id.'">'.$supplier->name.'</option>';
					}else{
						$option_2 .= '<option value="'.$supplier->s_id.'">'.$supplier->name.'</option>';						
					}
				}

				foreach($lots as $lot){


					$canvassForms = $user->selectCanvassForm($id, $lot->title, $lot->id);
					// echo "<pre>".print_r($canvassForms)."</pre>";
					// die();
					
					// if($canvassForms->CanvassDetails->type === "PR"){
					// 	$option = $option_2;
					// }else{
					// 	$option = $option_1;
					// }
					$option = '
						<option>Select Supplier</option>
						<optgroup label="-- Supplies --">
							'.$option_2.'
						</optgroup>
						<optgroup label="-- Services ---">
							'.$option_1.'
						</optgroup>
					';

					$btn_identify = ($lot->per_item) ? "true" : "false" ;

	?>	
				<div class="row">
					<div class="col-lg-12  animated fadeInRight">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<div class="row">
									<div class="col-lg-6">
									<?php
										if($canvassForms->CanvassDetails->per_item){
											echo '<h5>Lot Title: '.$lot->title.' --- '.Date::translate($lot->cost, 'php').'<br></h5>';
										}else{
											echo '
											<h5>
												Lot Title: '.$lot->title.' --- '.Date::translate($lot->cost, 'php').'<br>
												Declare Bid Failure:&nbsp;&nbsp;<input type="checkbox" name="lot['.$lot_count.'][fail]">
											</h5>
											';
										}
									?>
									</div>
									<div class="col-lg-6" style="text-align: right;">
										<button type="button" data-type="add" data-fcount="0" data-table="<?php echo 'table-lot-'.$lot_count;?>" data-idty="<?php echo $btn_identify;?>" data-itmcnt="<?php echo count($canvassForms->items);?>" data-name="<?php echo 'lot['.$lot_count.']';?>" data-prjtype="<?php echo $canvassForms->CanvassDetails->type?>" class="btn btn-primary btn-outline">
											<i class="fa fa-plus"></i>&nbsp;Add Supplier
										</button>
										<button type="button" data-type="del" data-table="<?php echo 'table-lot-'.$lot_count;?>" data-idty="<?php echo $btn_identify;?>" data-itmcnt="<?php echo count($canvassForms->items);?>" class="btn btn-danger btn-outline">
											<i class="fa fa-times"></i>&nbsp;Delete Supplier
										</button>	
									</div>
								</div>
							</div>
							<div class="ibox-content">
								<input type="hidden" name="lot[<?php echo $lot_count;?>][title]" value="<?php echo $lot->title;?>" required>
								<input type="hidden" name="lot[<?php echo $lot_count;?>][cost]" value="<?php echo $lot->cost;?>" required>
								<input type="hidden" name="lot[<?php echo $lot_count;?>][lot_id]" value="<?php echo $lot->id?>" required>
								<input type="hidden" name="lot[<?php echo $lot_count;?>][item_count]" value="<?php echo count($canvassForms->items)?>">
								<input type="hidden" name="lot[<?php echo $lot_count;?>][type]" value="<?php echo $canvassForms->CanvassDetails->type?>">
								
								
								<div class="table-responsive">
					<?php

						if($canvassForms->CanvassDetails->per_item){
							if($canvassForms->CanvassDetails->type === "PR"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'.$canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered" id="table-lot-'.$lot_count.'">
										<thead id="">
											<tr>
												<th>Declare Item Bid Failure</th>
												<th>Unit</th>
												<th>Item Description</th>
												<th>Quantity</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>
												<th>	
													<select name="lot['.$lot_count.'][supplier][0]" type="text" class="form-control form-control-sm" required>
														'.$option.'
													</select>
												</th>
											</tr>
										</thead>
										<tbody>';
									
										foreach($canvassForms->items as $item){
											echo '
											<tr>
												<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'" required>
												<td><input name="lot['.$lot_count.'][fail]['.$item_count.']" type="checkbox"></td>
												<td>'.$item->unit.'</td>
												<td>'.$item->item_description.'</td>
												<td>'.$item->quantity.'</td>
												<td>'.Date::translate($item->unit_cost, 'php').'</td>
												<td>'.Date::translate($item->total_cost, 'php').'</td>
												<td>
													<textarea name="lot['.$lot_count.'][offer]['.$item_count.'][]" rows="2" cols="30" class="form-control form-control-sm" type="text" placeholder="Offer"></textarea>
													<input name="lot['.$lot_count.'][items]['.$item_count.'][]" type="number" step="0.01" min="0.00" class="form-control form-control-sm" placeholder="Unit Price">
													<input name="lot['.$lot_count.'][remark]['.$item_count.'][]" list="list-remark" type="text" class="form-control form-control-sm" placeholder="Remark" style="margin-bottom: 10px">
												</td>
											</tr>
											';
											$item_count++;
										}
									echo '
										</tbody>
									</table>';
						
							}elseif($canvassForms->CanvassDetails->type === "JO"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'.$canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered" id="table-lot-'.$lot_count.'">
										<thead>
											<tr>
												<th>Item Bid Fail</th>
												<th>List Title</th>
												<th>Tags</th>
												<th>
													<select name="lot['.$lot_count.'][supplier][0]" type="text" class="form-control form-control-sm" required>
														'.$option.'
													</select>
												</th>
											</tr>
										</thead>
										<tbody>';
									
										foreach($canvassForms->items as $item){
											echo '
											<tr>
												<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'" required>
												<td><input name="lot['.$lot_count.'][fail]['.$item_count.']" type="checkbox"></td>
												<td>'.$item->header.'</td>
												<td>'.$item->tags.'</td>
												<td>
													<textarea hidden name="lot['.$lot_count.'][offer]['.$item_count.'][]" rows="2" cols="30" class="form-control form-control-sm" type="text" placeholder="Offer"></textarea>
													<input name="lot['.$lot_count.'][items]['.$item_count.'][]" type="number" step="0.01" min="0.00" class="form-control form-control-sm" placeholder="Price">
													<input name="lot['.$lot_count.'][remark]['.$item_count.'][]" list="list-remark" type="text" class="form-control form-control-sm" placeholder="Remark" style="margin-bottom: 10px">
												</td>
											</tr>
											';
											$item_count++;
										}
									echo '
										</tbody>
									</table>';
						
							}
						}else{

							if($canvassForms->CanvassDetails->type === "PR"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'.$canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered" id="table-lot-'.$lot_count.'">
										<thead>
											<tr>
												<th>Unit</th>
												<th>Item Description</th>
												<th>Quantity</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>
												<th>
													<select name="lot['.$lot_count.'][supplier][0]" type="text" class="form-control form-control-sm" required>
														'.$option.'
													</select>
													<input name="lot['.$lot_count.'][remark][0]" list="list-remark" type="text" class="form-control form-control-sm" required placeholder="Remark">
												</th>
											</tr>
										</thead>
										<tbody>';
									
										foreach($canvassForms->items as $item){
											echo '
											<tr>
												<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'" required>
												<td>'.$item->unit.'</td>
												<td>'.$item->item_description.'</td>
												<td>'.$item->quantity.'</td>
												<td>'.Date::translate($item->unit_cost, 'php').'</td>
												<td>'.Date::translate($item->total_cost, 'php').'</td>
												<td>
													<textarea name="lot['.$lot_count.'][offer]['.$item_count.'][]" rows="2" cols="30" class="form-control form-control-sm" type="text" placeholder="Offer"></textarea>
													<input name="lot['.$lot_count.'][items]['.$item_count.'][]" type="number" step="0.01" min="0.00" class="form-control form-control-sm" placeholder="Unit Price">
												</td>
											</tr>
											';
											$item_count++;
										}
									echo '
										</tbody>
									</table>';
						
							}elseif($canvassForms->CanvassDetails->type === "JO"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'.$canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered" id="table-lot-'.$lot_count.'">
										<thead>
											<tr>
												<th>Mode of Procurement</th>
												<th>List Title</th>
												<th>Tags</th>
												<th>
													<select name="lot['.$lot_count.'][supplier][0]" type="text" class="form-control form-control-sm" required>
														'.$option.'
													</select>
													<input name="lot['.$lot_count.'][remark][0]" list="list-remark" type="text" class="form-control form-control-sm" placeholder="Remark" required>
												</th>
											</tr>
										</thead>
										<tbody>';
									
										foreach($canvassForms->items as $item){
											echo '
											<tr>
												<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'" required>
												<td>'.$item->mode.'</td>
												<td>'.$item->header.'</td>
												<td>'.$item->tags.'</td>
												<td>
													<textarea hidden name="lot['.$lot_count.'][offer]['.$item_count.'][]" rows="1" cols="30" class="form-control form-control-sm" type="text" placeholder="Offer"></textarea>
													<input name="lot['.$lot_count.'][items]['.$item_count.'][]" type="number" step="0.01" min="0.00" class="form-control form-control-sm" placeholder="Price">
												</td>
											</tr>
											';
											$item_count++;
										}
									echo '
										</tbody>
									</table>';
						
							}
						}
						?>
								</div>
							</div>

						</div>
					</div>
				</div>
	

			<?php
				$lot_count++;
				$item_count = 0;
				}

			}else{
				include('../../includes/errors/404.php');
				echo"<br><br><br><br><br><br>";
			}
		}else{
			include('../../includes/errors/404.php');
			echo"<br><br><br><br><br><br>";
		}	
	?>
			<div style="text-align:right; margin-right: 25px;">
				<button class="btn btn-rounded btn-primary">Submit</button>
			</div>
			</form>
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

		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1000);

		$('[list="list-remark"]').typeahead({
			source: ["Rank 1","Rank 2","Rank 3","DQ: Exceeded ABC","DQ: Incomplete Bid","DQ: No Bid", "DQ: Non-compliant"]
		});

		$('[data-type]').on('click', function(){
			if(this.dataset.type === "add"){
				let el = document.querySelector(`#${this.dataset.table}`).childNodes;
				let name = this.dataset.name

				if(this.dataset.idty === "true"){
					this.dataset.fcount = parseInt(this.dataset.fcount) + 1;
					let fcount = this.dataset.fcount;

					el[1].childNodes[1].innerHTML += `
						<th>
							<select name="${name}[supplier][${fcount}]" type="text" class="form-control form-control-sm" required>
								<?php echo $option;?>
							</select>
						</th>
					`;

					let tempcount = 0;
					el[3].childNodes.forEach(function(e ,i){
						if((i % 2) !== 0){
							e.innerHTML += `
								<td>
									<textarea name="${name}[offer][${tempcount}][]" rows="2" cols="30" class="form-control form-control-sm" type="text" placeholder="Offer"></textarea>
									<input name="${name}[items][${tempcount}][]" type="number" step="0.01" min="0.00" class="form-control form-control-sm" placeholder="Unit Price">
									<input name="${name}[remark][${tempcount}][]" list="list-remark" type="text" class="form-control form-control-sm" placeholder="Remark" style="margin-bottom: 10px">
								</td>
							`;
							tempcount++;
						}
					});
					$('[list="list-remark"]').typeahead({
						source: ["Rank 1","Rank 2","Rank 3","DQ: Exceeded ABC","DQ: Incomplete Bid","DQ: No Bid", "DQ: Non-compliant"]
					});
				}else{
					this.dataset.fcount = parseInt(this.dataset.fcount) + 1;
					
					if(this.dataset.prjtype === "PR"){

						el[1].childNodes[1].innerHTML += `
							<th>
								<select name="${name}[supplier][${this.dataset.fcount}]" type="text" class="form-control form-control-sm" required>
									<?php echo $option;?>
								</select>
								<input name="${name}[remark][${this.dataset.fcount}]" list="list-remark" type="text" class="form-control form-control-sm" required placeholder="Remark">
							</th>
						`;

						let tempcount = 0;
						el[3].childNodes.forEach(function(e ,i){
							if((i % 2) !== 0){
								e.innerHTML += `
									<td>
										<textarea name="${name}[offer][${tempcount}][]" rows="2" cols="30" class="form-control form-control-sm" type="text" placeholder="Offer"></textarea>
										<input name="${name}[items][${tempcount}][]" type="number" step="0.01" min="0.00" class="form-control form-control-sm" placeholder="Unit Price">
									</td>
								`;
								tempcount++;
							}
						});

						$('[list="list-remark"]').typeahead({
							source: ["Rank 1","Rank 2","Rank 3","DQ: Exceeded ABC","DQ: Incomplete Bid","DQ: No Bid", "DQ: Non-compliant"]
						});

					}else{

						el[1].childNodes[1].innerHTML += `
							<th>
								<select name="${name}[supplier][${this.dataset.fcount}]" type="text" class="form-control form-control-sm" required>
									<?php echo $option;?>
								</select>
								<input name="${name}[remark][${this.dataset.fcount}]" list="list-remark" type="text" class="form-control form-control-sm" required placeholder="Remark">
							</th>
						`;

						let tempcount = 0;
						el[3].childNodes.forEach(function(e ,i){
							if((i % 2) !== 0){
								e.innerHTML += `
									<td>
										<textarea hidden name="${name}[offer][${tempcount}][]" rows="2" cols="30" class="form-control form-control-sm" type="text" placeholder="Offer"></textarea>
										<input name="${name}[items][${tempcount}][]" type="number" step="0.01" min="0.00" class="form-control form-control-sm" placeholder="Price">
									</td>
								`;
								tempcount++;
							}
						});

						$('[list="list-remark"]').typeahead({
							source: ["Rank 1","Rank 2","Rank 3","DQ: Exceeded ABC","DQ: Incomplete Bid","DQ: No Bid", "DQ: Non-compliant"]
						});


					}

				}
			}else if(this.dataset.type === "del"){
				let addbtn = this.previousSibling.previousSibling;
				let el = document.querySelector(`#${this.dataset.table}`).childNodes;
				if(addbtn.dataset.fcount !== "0"){
					addbtn.dataset.fcount = parseInt(addbtn.dataset.fcount) - 1;
					el[1].childNodes[1].lastElementChild.remove();
					el[3].childNodes.forEach(function(e, i){
						if((i % 2) !== 0){
							e.lastElementChild.remove();
						}
					});
				}
			}
		});
	});
</script>
</html>
