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

		foreach($_POST['lot'] as $lot){

			// $publication = $user->projectPublication($gds, htmlspecialchars($lot['title']), htmlspecialchars($lot['cost']));
			// $canvass = $user->get('canvass_forms', array('publication_reference', '=', $publication));

			if($lot['per_item']){

				foreach($lot['items'] as $item){

					$user->register('canvass_returns', array(
						'canvass_forms_id' => $lot['lot_id'],
						'item_id' => $item['id'],
						'remarks' => NULL,
						'lot_fail' => NULL
					));

					$returns_id = $user->lastId();
					$count = 0;

					foreach($item['supplier'] as $supplier){

						$item_fail = (isset($item['fail'][$count])) ? true : false;

						$user->register('canvass_quotation', array(
							'returns_id' => $returns_id,
							'supplier' => htmlspecialchars($supplier),
							'price' => htmlspecialchars($item['price'][$count]),
							'remark' => htmlspecialchars($item['remark'][$count]),
							'item_fail' => $item_fail
						));

						$count++;
					}

				}

			}else{

				
				foreach($lot['items'] as $item){

					$lot_fail = (isset($lot['fail'])) ? true : false;
					
					$user->register('canvass_returns', array(
						'canvass_forms_id' => $lot['lot_id'],
						'item_id' => NULL,
						'remarks' => htmlspecialchars($lot['remarks']),
						'lot_fail' => $lot_fail
					));

					$returns_id = $user->lastId();
					$count = 0;

					foreach($item['supplier'] as $supplier){

						$user->register('canvass_quotation', array(
							'returns_id' => $returns_id,
							'supplier' => htmlspecialchars($supplier),
							'price' => htmlspecialchars($item['price'][$count]),
							'remark' => NULL,
							'item_fail' => NULL
						));

						$count++;
					}


				}

			}

		}


		// redirect to print abstract

		$user->endTrans();

	}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Overview</title>
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
				$buttonCount = 0;

				echo '<input type="hidden" name="gds" value="'.$id.'">';

				foreach($lots as $lot){
					
	?>	
				<div class="row">
					<div class="col-lg-12  animated fadeInRight">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Lot Title: <?php echo $lot->title;?> &nbsp;&nbsp;<br>Lot Cost: <?php echo "&#8369; ".$lot->cost;?></h5>
							</div>
							<div class="ibox-content">
								<input type="hidden" name="lot[<?php echo $lot_count;?>][title]" value="<?php echo $lot->title;?>" required>
								<input type="hidden" name="lot[<?php echo $lot_count;?>][cost]" value="<?php echo $lot->cost;?>" required>
								<input type="hidden" name="lot[<?php echo $lot_count;?>][lot_id]" value="<?php echo $lot->id?>" required>
								<div class="table-responsive">
					<?php
						$canvassForms = $user->selectCanvassForm($id, $lot->title, $lot->id);

						if($canvassForms->CanvassDetails->per_item){
							if($canvassForms->CanvassDetails->type === "PR"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'.$canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Unit</th>
												<th>Item Description</th>
												<th>Quantity</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>
												<th>Actions</th>
												<th></th>
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
												<td>'.$item->unit_cost.'</td>
												<td>'.$item->total_cost.'</td>
												<td style="text-align:center">
													<button type="button" data-type="add" data-btn-num="'.$buttonCount.'" data-name="lot['.$lot_count.'][items]['.$item_count.']" data-peritem="true" data-fcount="0" class="btn btn-primary btn-outline btn-xs" style="margin-bottom:5px;">
														<i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add Input
													</button>
													<br>
													<button type="button" data-type="del" data-btn-num="'.$buttonCount.'" class="btn btn-danger btn-outline btn-xs">
														<i class="fa fa-times"></i>&nbsp;Delete Input
													</button>										
												</td>
												<td>
													<div class="row" id="row-'.$buttonCount.'">
														<div class="col-lg-2">
															<label>Supplier: </label>
															<label>Price: </label>
															<label>Remark: </label>
															<label>Fail: </label>
															<input type="checkbox" class="i-checks" name="lot['.$lot_count.'][items]['.$item_count.'][fail][0]">
														</div>
														<div class="col-lg-10">
															<input class="form-control form-control-sm" type="text" name="lot['.$lot_count.'][items]['.$item_count.'][supplier][]" required>
															<input class="form-control form-control-sm" step="0.01" min="0.00" type="number" name="lot['.$lot_count.'][items]['.$item_count.'][price][]" required>
															<input class="form-control form-control-sm" type="text" name="lot['.$lot_count.'][items]['.$item_count.'][remark][]" required>
														</div>
													</div>
												</td>
											</tr>
											';
											$item_count++;
											$buttonCount++;
										}
									echo '
										</tbody>
									</table>';
						
							}elseif($canvassForms->CanvassDetails->type === "JO"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'.$canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>List Title</th>
												<th>Tags</th>
												<th>Actions</th>
												<th></th>
											</tr>
										</thead>
										<tbody>';
									
										foreach($canvassForms->items as $item){
											echo '
											<tr>
												<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'" required>
												<td>'.$item->header.'</td>
												<td>'.$item->tags.'</td>
												<td style="text-align:center">
													<button type="button" data-type="add" data-btn-num="'.$buttonCount.'" data-name="lot['.$lot_count.'][items]['.$item_count.']" data-peritem="true" class="btn btn-primary btn-outline btn-xs" style="margin-bottom:5px;">
														<i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add Input
													</button>
													<br>
													<button type="button" data-type="del" data-btn-num="'.$buttonCount.'" class="btn btn-danger btn-outline btn-xs">
														<i class="fa fa-times"></i>&nbsp;Delete Input
													</button>										
												</td>
												<td>
													<div class="row" id="row-'.$buttonCount.'">
														<div class="col-lg-2">
															<label>Supplier: </label>
															<label>Price: </label>
															<label>Remark: </label>
															<label>Fail: </label>
															<input type="checkbox" class="i-checks" name="lot['.$lot_count.'][items]['.$item_count.'][fail][]">
														</div>
														<div class="col-lg-10">
															<input class="form-control form-control-sm" type="text" name="lot['.$lot_count.'][items]['.$item_count.'][supplier][]" required>
															<input class="form-control form-control-sm" step="0.01" min="0.00" type="number" name="lot['.$lot_count.'][items]['.$item_count.'][price][]" required>
															<input class="form-control form-control-sm" type="text" name="lot['.$lot_count.'][items]['.$item_count.'][remark][]" required>
														</div>
													</div>
												</td>
											</tr>
											';
											$item_count++;
											$buttonCount++;
										}
									echo '
										</tbody>
									</table>';
						
							}
						}else{

							if($canvassForms->CanvassDetails->type === "PR"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'.$canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Unit</th>
												<th>Item Description</th>
												<th>Quantity</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>
												<th>Actions</th>
												<th></th>
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
												<td>'.$item->unit_cost.'</td>
												<td>'.$item->total_cost.'</td>
												<td style="text-align:center">
													<button type="button" data-type="add" data-btn-num="'.$buttonCount.'" data-name="lot['.$lot_count.'][items]['.$item_count.']" data-peritem="false" class="btn btn-primary btn-outline btn-xs" style="margin-bottom:5px;">
														<i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add Input
													</button>
													<br>
													<button type="button" data-type="del" data-btn-num="'.$buttonCount.'" class="btn btn-danger btn-outline btn-xs">
														<i class="fa fa-times"></i>&nbsp;Delete Input
													</button>										
												</td>
												<td>
													<div class="row" id="row-'.$buttonCount.'">
														<div class="col-lg-2">
															<label>Supplier: </label>
															<label>Price: </label>
														</div>
														<div class="col-lg-10">
															<input class="form-control form-control-sm" type="text" name="lot['.$lot_count.'][items]['.$item_count.'][supplier][]" required>
															<input class="form-control form-control-sm" step="0.01" min="0.00" type="number" name="lot['.$lot_count.'][items]['.$item_count.'][price][]" required>
														</div>
													</div>
												</td>
											</tr>
											';
											$item_count++;
											$buttonCount++;
										}
									echo '
										</tbody>
									</table>
								Remark: <input type="text" name="lot['.$lot_count.'][remarks]" required>
								Fail: <input type="checkbox" name="lot['.$lot_count.'][fail]" class="i-checks">';
						
							}elseif($canvassForms->CanvassDetails->type === "JO"){
								echo '
								<input type="hidden" name="lot['.$lot_count.'][per_item]" value="'. $canvassForms->CanvassDetails->per_item.'" required>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Mode of Procurement</th>
												<th>List Title</th>
												<th>Tags</th>
												<th>Price Offered</th>
												<th>Actions</th>
												<th></th>
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
												<td style="text-align:center">
													<button type="button" data-type="add" data-btn-num="'.$buttonCount.'" data-name="lot['.$lot_count.'][items]['.$item_count.']" data-peritem="false" class="btn btn-primary btn-outline btn-xs" style="margin-bottom:5px;">
														<i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add Input
													</button>
													<br>
													<button type="button" data-type="del" data-btn-num="'.$buttonCount.'" class="btn btn-danger btn-outline btn-xs">
														<i class="fa fa-times"></i>&nbsp;Delete Input
													</button>										
												</td>
												<td>
													<div class="row" id="row-'.$buttonCount.'">
														<div class="col-lg-2">
															<label>Supplier: </label>
															<label>Price: </label>
														</div>
														<div class="col-lg-10">
															<input class="form-control form-control-sm" type="text" name="lot['.$lot_count.'][items]['.$item_count.'][supplier][]" required>
															<input class="form-control form-control-sm" step="0.01" min="0.00" type="number" name="lot['.$lot_count.'][items]['.$item_count.'][price][]" required>
														</div>
													</div>
												</td>
											</tr>
											';
											$item_count++;
											$buttonCount++;
										}
									echo '
										</tbody>
									</table>
								Remark: <input type="text" name="lot['.$lot_count.'][remarks]" required>
								Fail: <input type="checkbox" name="lot['.$lot_count.'][fail]" class="i-checks">';
						
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
					
			<button class="btn btn-rounded btn-success">Submit</button>
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
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});

		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1000);

		$('[data-type]').on('click', function(){
			if(this.dataset.type === "add"){
				if(this.dataset.peritem === "true"){
					$(`#row-${this.dataset.btnNum}`).append(`
						<div class="col-lg-2">
							<label>Supplier: </label>
							<label>Price: </label>
							<label>Remark: </label>
							<label>Fail: </label>
							<input type="checkbox" class="i-checks" name="${this.dataset.name}[fail][${parseInt(this.dataset.fcount) + 1}]">
						</div>
						<div class="col-lg-10">
							<input class="form-control form-control-sm" type="text" name="${this.dataset.name}[supplier][]" required>
							<input class="form-control form-control-sm" step="0.01" min="0.00" type="number" name="${this.dataset.name}[price][]" required>
							<input class="form-control form-control-sm" type="text" name="${this.dataset.name}[remark][]" required>
						</div>
					`);
					this.dataset.fcount = parseInt(this.dataset.fcount) + 1;
					$('.i-checks').iCheck({
						checkboxClass: 'icheckbox_square-green',
						radioClass: 'iradio_square-green'
					});
				}else{
					$(`#row-${this.dataset.btnNum}`).append(`
						<div class="col-lg-2">
							<label>Supplier: </label>
							<label>Price: </label>
						</div>
						<div class="col-lg-10">
							<input class="form-control form-control-sm" type="text" name="${this.dataset.name}[supplier][]" required>
							<input class="form-control form-control-sm" step="0.01" min="0.00" type="number" name="${this.dataset.name}[price][]" required>
						</div>
					`);
				}
			}else if(this.dataset.type === "del"){
				let del_array = $(`#row-${this.dataset.btnNum} div`);
				
				if(this.dataset.peritem === "true"){
					del_array[del_array.length - 1].remove();
					del_array[del_array.length - 2].remove();
				}else{
					del_array[del_array.length - 1].remove();
					del_array[del_array.length - 2].remove();
					del_array[del_array.length - 3].remove();
				}

				let addBtn = this.parentNode.childNodes[1];
				if(addBtn.dataset.fcount !== "-1"){
					addBtn.dataset.fcount = parseInt(addBtn.dataset.fcount) - 1;
				}
			}
		});
	});
</script>
</html>
