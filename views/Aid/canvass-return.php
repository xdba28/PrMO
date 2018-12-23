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

		$user->startTrans();

		$gds = htmlspecialchars($_POST['gds']);

		foreach($_POST['lot'] as $lot){

			$publication = $user->projectPublication($gds, htmlspecialchars($lot['title']), htmlspecialchars($lot['cost']));
			$canvass = $user->get('canvass_forms', array('publication_reference', '=', $publication));

			if($lot['per_item']){

				foreach($lot['items'] as $item){
					$user->register('canvass_returns', array(
						'canvass_forms_id' => $canvass->id,
						'item_id' => $item['id'],
						'supplier' => htmlspecialchars($lot['supplier']),
						'price' => htmlspecialchars($item['price']),
						'remarks' => htmlspecialchars($item['remarks']),
					));
				}

			}else{

				foreach($lot['items'] as $item){
					$user->register('canvass_returns', array(
						'canvass_forms_id' => $canvass->id,
						'item_id' => NULL,
						'supplier' => htmlspecialchars($lot['supplier']),
						'price' => htmlspecialchars($item['price']),
						'remarks' => htmlspecialchars($lot['remarks']),
					));
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
				
				$lots = $user->getAll('publication', array('gds_reference', '=', $id));
				$lot_count = 0;
				$item_count = 0;

				echo '<input type="hidden" name="gds" value="'.$id.'">';

				foreach($lots as $lot){
					// echo "<pre>".print_r($lot)."</pre>";
	?>	
				<div class="row">
					<div class="col-lg-12  animated fadeInRight">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Lot Title: <?php echo $lot->title;?> &nbsp;&nbsp;<br>Lot Cost: <?php echo "&#8369; ".$lot->cost;?></h5>
							</div>
							<div class="ibox-content">
							Supplier: <input type="text" name="lot[<?php echo $lot_count;?>][supplier]" > <br><br>
										<input type="hidden" name="lot[<?php echo $lot_count;?>][title]" value="<?php echo $lot->title;?>">
										<input type="hidden" name="lot[<?php echo $lot_count;?>][cost]" value="<?php echo $lot->cost;?>">
								<div class="table-responsive">
					<?php
						$canvassForms = $user->selectCanvassForm($id, $lot->title, $lot->id);

						// echo "<pre>".print_r($canvassForms['CanvassDetails'])."</pre>";
						// echo "<pre>".print_r($canvassForms['items'])."</pre>";

						if($canvassForms['CanvassDetails']->per_item){
							if($canvassForms['CanvassDetails']->type === "PR"){
					?>
								<input type="hidden" name="lot[<?php echo $lot_count;?>][per_item]" value="<?php echo $canvassForms['CanvassDetails']->per_item;?>">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Mode of Procurement</th>
												<th>Stock No.</th>
												<th>Unit</th>
												<th>Item Description</th>
												<th>Quantity</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>
												<th>Price Offered</th>
												<th>Remark</th>
											</tr>
										</thead>
										<tbody>
									<?php
										foreach($canvassForms['items'] as $item){
											echo '
											<tr>
												<td>'.$item->mode.'</td>
												<td>'.$item->stock_no.'</td>
												<td>'.$item->unit.'</td>
												<td>'.$item->item_description.'</td>
												<td>'.$item->quantity.'</td>
												<td>'.$item->unit_cost.'</td>
												<td>'.$item->total_cost.'</td>
												<td>
													<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'">
													<input class="form-control" type="number" step="0.01" min="0.00" name="lot['.$lot_count.'][items]['.$item_count.'][price]">
												</td>
												<td><input type="text" class="form-control" name="lot['.$lot_count.'][items]['.$item_count.'][remarks]"></td>
											</tr>
											';
											$item_count++;
										}
									?>
										</tbody>
									</table>
						<?php
							}elseif($canvassForms['CanvassDetails']->type === "JO"){
						?>
								<input type="text" name="lot[<?php echo $lot_count;?>][per_item]" value="<?php echo $canvassForms['CanvassDetails']->per_item;?>">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Mode of Procurement</th>
												<th>List Title</th>
												<th>Tags</th>
												<th>Price Offered</th>
												<th>Remark</th>
											</tr>
										</thead>
										<tbody>
									<?php
										foreach($canvassForms['items'] as $item){
											echo '
											<tr>
												<td>'.$item->mode.'</td>
												<td>'.$item->header.'</td>
												<td>'.$item->tags.'</td>
												<td>
													<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'">
													<input class="form-control" type="number" step="0.01" min="0.00" name="lot['.$lot_count.'][items]['.$item_count.'][price]">
												</td>
												<td><input type="text" class="form-control" name="lot['.$lot_count.'][items]['.$item_count.'][remarks]"></td>
											</tr>
											';
											$item_count++;
										}
									?>
										</tbody>
									</table>
						<?php
							}
						}else{

							if($canvassForms['CanvassDetails']->type === "PR"){
						?>
								<input type="text" name="lot[<?php echo $lot_count;?>][per_item]" value="<?php echo $canvassForms['CanvassDetails']->per_item;?>">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Mode of Procurement</th>
												<th>Stock No.</th>
												<th>Unit</th>
												<th>Item Description</th>
												<th>Quantity</th>
												<th>Unit Cost</th>
												<th>Total Cost</th>
												<th>Price Offered</th>
											</tr>
										</thead>
										<tbody>
									<?php
										foreach($canvassForms['items'] as $item){
											echo '
											<tr>
												<td>'.$item->mode.'</td>
												<td>'.$item->stock_no.'</td>
												<td>'.$item->unit.'</td>
												<td>'.$item->item_description.'</td>
												<td>'.$item->quantity.'</td>
												<td>'.$item->unit_cost.'</td>
												<td>'.$item->total_cost.'</td>
												<td>
													<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'">
													<input class="form-control" step="0.01" min="0.00" type="number" name="lot['.$lot_count.'][items]['.$item_count.'][price]">
												</td>
											</tr>
											';
											$item_count++;
										}
									?>
										</tbody>
									</table>
								Remark: <input type="text" name="lot[<?php echo $lot_count;?>][remarks]">
						<?php
							}elseif($canvassForms['CanvassDetails']->type === "JO"){
						?>
								<input type="text" name="lot[<?php echo $lot_count;?>][per_item]" value="<?php echo $canvassForms['CanvassDetails']->per_item;?>">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Mode of Procurement</th>
												<th>List Title</th>
												<th>Tags</th>
												<th>Price Offered</th>
											</tr>
										</thead>
										<tbody>
									<?php
										foreach($canvassForms['items'] as $item){
											echo '
											<tr>
												<td>'.$item->mode.'</td>
												<td>'.$item->header.'</td>
												<td>'.$item->tags.'</td>
												<td>
													<input type="hidden" name="lot['.$lot_count.'][items]['.$item_count.'][id]" value="'.$item->item_id.'">
													<input class="form-control" step="0.01" min="0.00" type="number" name="lot['.$lot_count.'][items]['.$item_count.'][price]">
												</td>
											</tr>
											';
											$item_count++;
										}
									?>
										</tbody>
									</table>
								Remark: <input type="text" name="lot[<?php echo $lot_count;?>][remarks]">
						<?php
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

	});
</script>
</html>
