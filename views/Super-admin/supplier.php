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

		try {
			$user->startTrans();

				if($user->register('Supplier', array(
					'name' => htmlspecialchars($_POST['supplier']),
					'address' => htmlspecialchars($_POST['address']),
					'type' => htmlspecialchars($_POST['type']),
					'tin' => htmlspecialchars($_POST['tin'])
				))){
					Syslog::put('Register new supplier');
					$responce = "Succesfully added supplier.";
					unset($_POST);					
				}else{
					Syslog::put('Register new supplier',null,"failed");
				}

			$user->endTrans();


		} catch (Exception $e) {
			Syslog::put($e,null,'error_log');
			Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0001');
		}




	}


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Overview</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include "../../includes/parts/admin_styles.php"?>

	<script>
		var responce = '<?php 
			if(isset($responce)){
				echo $responce;
			}else{
				echo "";
			}
		?>';
		const SampleData = [
			
			<?php
			$suppliers = $user->selectAll('supplier');
			$count = 0;
			foreach ($suppliers as $supplier) {
				// $C = $user->get('project_category', array('pc_id', '=', $supplier->category));
				echo '
					{
						id: "'.$supplier->s_id.'",
						no: "'.$count.'",
						name: "'.$supplier->name.'",
						address: "'.$supplier->address.'",
						type: "'.$supplier->type.'",
						tin: "'.$supplier->tin.'",
					},
					';
				$count++;
			}
		?>
		];
	</script>
	<style>
		.none {
			display: none;
		}
	</style>
</head>

<body class="fixed-navigation">
    <div id="wrapper">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/superadmin_side_nav.php'; ?>
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
                    <h2>Suppliers</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">System Settings</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Suppliers</strong>
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
				
				

				<div class="row">
					<div class="col-lg-12  animated fadeInRight">
					
                    
					<div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5>List of Suppliers</h5>

                        </div>
                        <div class="ibox-content">
							<div class="alert alert-info">
                               Here you can edit the default data per College / Office Unit like the personnel incharge of noting, verifying, and approving the Purchase Requests or Job Orders. Click on the underlined field to edit. After finalizing all your changes click the "Save Changes" button at the bottom-right of this page.
                            </div>
							<div class="row">
								<div class="col-sm-9 m-b-xs">
								</div>
								<div class="col-sm-3">
									<div class="input-group mb-3">
										<input type="text" class="form-control form-control-sm" placeholder="Search" id="filter">
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<table class="footable table table-striped toggle-arrow-tiny" data-filter="#filter">
									<thead>
										<tr>
											<th>Supplier</th>
											<th>Address</th>
											<th>Type</th>
											<th>TIN</th>
										</tr>
									</thead>
									<tbody id="t-data">

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
							<button class="btn btn-outline btn-success btn-rounded" id="btnAdd">Add Supplier</button>
							<button class="btn btn-outline btn-primary btn-rounded pull-right" id="save">Save Changes</button><br><br><br>
                        </div>

						<div class="ibox-content animated fadeInDown none" id="addUnit">
							<div class="row">
								
								<div class="col-sm-6">
									<form id="profile" role="form" method="POST" enctype="multipart/form-data">
									<div class="form-group mt-20">
										<label class="form-label" for="unit_name">Supplier</label>
										<input id="unit_name" name="supplier" class="form-input" type="text" required>
									</div>			
									<div class="form-group mt-20">
										<label class="form-label" for="camp">Address</label>
										<input id="camp" name="address" class="form-input" type="text" required>
									</div>															
								</div>
								<div class="col-sm-6"> 
									<div class="form-group mt-20">
										<select name="type" class="form-input" type="text" required>
											<option>Select Type</option>
											<option value="Supplies">Supplies</option>
											<option value="Services">Services</option>
											<?php
												// foreach ($user->selectAll('project_category') as $key => $value) {
												// 	echo '<option value="'.$value->pc_id.'">'.$value->category.'</option>';
												// }
											?>
										</select>
									</div>							
									<div class="form-group mt-20">
										<label class="form-label" for="acr">TIN</label>
										<input id="acr" name="tin" class="form-input" type="text" required data-mask="999-999-999-999">
									</div>	
									</form>							
								</div>
								<div class="col-lg-12">
									<button class="btn btn-primary btn-rounded pull-right" type="submit" form="profile">Submit</button>
								</div>									
							</div>
						</div>

                    </div>
                
					
					</div>
				</div>
		
			<!-- <td>
				<a href="#" data-name="category" category="${i}" data-pk="${e.no}" data-type="select"> ${cat.text} </a>
			</td> -->
			
            </div>
			<button class="back-to-top" type="button"></button>
			<div class="footer">
				<?php include '../../includes/parts/footer.php';?>
			</div>

        </div>

    </div>
	<?php include_once '../../includes/parts/modals.php';?>
    <?php include_once '../../includes/parts/admin_scripts.php'; ?>
	<link rel="stylesheet" href="../../assets/bootstrap3-editable/css/bootstrap-editable.css">
	<script src="../../assets/bootstrap3-editable/js/bootstrap-editable.min.js"></script>


</body>
<script>
$(document).ready(function () {

	if(responce !== ''){
		swal({
			title: "Success!",
			text: responce,
			type: "success"
		});
	}

	var Edit = [];

	// const categories = [
	<?php
		// foreach($user->selectAll('project_category') as $category){
		// 	echo '{value: '.$category->pc_id.', text: "'.$category->category.'"},';
		// }
	?>
	// ];

	$.fn.editable.defaults.mode = 'inline';

	

	SampleData.forEach(function(e, i){
		// var cat = categories.find(function(el){
		// 	return el.value === e.category
		// });

		let temp = `
		<tr>
			<td>
				<a href="#" data-name="name" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.name} </a>
			</td>
			<td>
				<a href="#" data-name="address" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.address} </a>
			</td>
			<td>
				<a href="#" data-name="type" type="${i}" data-pk="${e.no}" data-type="select"> ${e.type} </a>
			</td>
			<td>
				<a href="#" data-name="tin" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.tin} </a>
			</td>
		`;
		$('#t-data').append(temp);

		// $(`[category="${i}"]`).editable({
		// 	value: e.category,
		// 	source: categories,
		// 	success: function(r, v){
		// 		let _ = $(this);
		// 		let prop = _.attr('data-name');
		// 		let n = SampleData.find(function(el){
		// 			console.log(el);
		// 			return el.no === _.attr('data-pk');
		// 		});

		// 		let inx = SampleData.indexOf(n);
		// 		SampleData[inx][prop] = v;

		// 		let editData = Edit.find(function(el){
		// 			return el.no === SampleData[inx].no
		// 		});
				
		// 		if(typeof editData === 'undefined'){
		// 			Edit.push(SampleData[inx]);
		// 		}else{
		// 			Edit.splice(Edit.indexOf(editData), 1);
		// 			Edit.push(SampleData[inx]);
		// 		}

		// 	}
		// });

		var type;
		if(e.type === "Supplies"){
			type = 1;
		}else{
			type = 2;
		}

		$(`[type="${i}"]`).editable({
			value: type,
			source: [
				{value: 1, text: "Supplies"},
				{value: 2, text: "Services"}
			],
			success: function(r, v){
				let _ = $(this);
				let prop = _.attr('data-name');
				let n = SampleData.find(function(el){
					console.log(el);
					return el.no === _.attr('data-pk');
				});

				let inx = SampleData.indexOf(n);
				SampleData[inx][prop] = v;

				let editData = Edit.find(function(el){
					return el.no === SampleData[inx].no
				});
				
				if(typeof editData === 'undefined'){
					Edit.push(SampleData[inx]);
				}else{
					Edit.splice(Edit.indexOf(editData), 1);
					Edit.push(SampleData[inx]);
				}

			}
		});

	});


	$('[dataFor="edit"]').editable({
		success: function(r, v){
			let _ = $(this);
			let prop = _.attr('data-name');
			let n = SampleData.find(function(el){
				return el.no === _.attr('data-pk');
			});

			let inx = SampleData.indexOf(n);
			SampleData[inx][prop] = v;

			let editData = Edit.find(function(el){
				return el.no === SampleData[inx].no
			});
			
			if(typeof editData === 'undefined'){
				Edit.push(SampleData[inx]);
			}else{
				Edit.splice(Edit.indexOf(editData), 1);
				Edit.push(SampleData[inx]);
			}

		}
	});

	$('#save').on('click', function(){
		SendDoNothing("POST", 'xhr-update-supplier.php', {
			col: Edit
		}, {
			title: 'Success!',
			text: 'Successfully updated supplier.'
		});
	});

	$('#btnAdd').on('click', function(){
		$('#addUnit').toggleClass('none');
	});
});
</script>
</html>
