<?php 

    require_once('../../core/init.php');

	$user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
	}

	if(!empty($_POST) && empty($_GET)){

		try {
			$user->startTrans();
				if($user->register('units', array(
					'office_name' => Input::get('unit_name'),
					'acronym' => Input::get('acr'),
					'campus' => Input::get('camp')
				))){
					Syslog::put('System Units update');
				}else{
					Syslog::put('System Units update',null,'failed');
				}
			$user->endTrans();
			Session::flash('responce', 'Succesfully added college/unit.');
			Redirect::to('college-units');
			die();
		} catch (Exception $e) {
			Syslog::put($e,null,'error_log');
			Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0001');
		}



	}elseif(!empty($_POST) && !empty($_GET)){
	

		try {
			$user->startTrans();
				if(empty(Input::get('acr'))){
					$acr = "N/A";
				}else{
					$acr = Input::get('acr');
				}

				if($user->register('offices', array(
					'unit' => base64_decode($_GET['u']),
					'specific_office' => Input::get('office'),
					'acronym' => $acr
				))){
					Syslog::put('System offices update');
				}else{
					Syslog::put('System offices update',null,'failed');
				}
			$user->endTrans();
			Session::flash('responce', 'Succesfully added office.');
			Redirect::to('college-units?u='.$_GET['u']);
			die();
		} catch (Exception $e) {
			Syslog::put($e,null,'error_log');
			Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0002');
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

	<?php
	if(empty($_GET)){
	?>
	<script>
		var responce = '<?php 
			if(Session::exists("responce")){
				echo Session::flash('responce');
			}else{
				echo "";
			}
		?>';
		const SampleData = [
			
			<?php
			$units = $user->selectAll('units');
			foreach ($units as $unit) {
				if(isset($count)){$count++;}else{$count=1;}
				echo '
					{
						no: "'.$count.'",
						id: "'.$unit->ID.'",
						acronym: "'.$unit->acronym.'",
						office: "'.$unit->office_name.'",
						note: "'.$unit->note.'",
						n_pos: "'.$unit->note_position.'",
						verify: "'.$unit->verifier.'",
						v_pos: "'.$unit->verifier_position.'",
						approve: "'.$unit->approving.'",
						a_pos: "'.$unit->approving_position.'"
					},
					';
				}
		?>
		];
	</script>
	<?php
	}else{
	?>
	<script>
		var responce = '<?php 
			if(Session::exists("responce")){
				echo Session::flash('responce');
			}else{
				echo "";
			}
		?>';
		const offices = [
			<?php
				$offices = $user->getAll('offices', array('unit', '=', base64_decode($_GET['u'])));
				foreach($offices as $key => $office){
					$count = $key + 1;
					echo '
					{
						no: "'.$count.'",
						id: "'.$office->office_id.'",
						specific_office: "'.$office->specific_office.'",
						acronym: "'.$office->acronym.'"
					},
					';
				}

			?>
		];
	</script>
	<?php
	}
	?>
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
                    <h2>Colleges and Units Setting</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">System Settings</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Colleges and Units</strong>
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
				if(empty($_GET)){
			?>
			<div class="wrapper wrapper-content animated fadeInUp">
				<div class="row">
					<div class="col-lg-12 animated fadeInRight">
					<div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5>College and Office Unit Settings</h5>
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
							<table class="footable table table-striped toggle-arrow-tiny" data-filter="#filter">
                                <thead>
									<tr>
										<th>Acronym</th>
										<th>College / Unit</th>
										<th>Noted By</th>
										<th>Noted By Job title</th>
										<th>Verifier</th>
										<th>Verifier Job title</th>
										<th>Approving</th>
										<th>Approving Job title</th>
										<th>View Offices</th>
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
							<button class="btn btn-outline btn-success btn-rounded" id="btnAdd">Add College / Unit</button>
							<button class="btn btn-outline btn-primary btn-rounded pull-right" id="save">Save Changes</button><br><br><br>
                        </div>

						<div class="ibox-content animated fadeInDown none" id="addUnit">
							<div class="row">
								
								<div class="col-sm-6">
									<form id="profile" role="form" method="POST" enctype="multipart/form-data">
									<div class="form-group mt-20">
										<label class="form-label" for="unit_name">College / Unit Name</label>
										<input id="unit_name" name="unit_name" class="form-input" type="text" required>
									</div>			
									<div class="form-group mt-20">
										<label class="form-label" for="camp">Campus</label>
										<input id="camp" name="camp" class="form-input" type="text" required>
									</div>															
								</div>
								<div class="col-sm-6"> 												
									<div class="form-group mt-20">
										<label class="form-label" for="acr">Acronym</label>
										<input id="acr" name="acr" class="form-input" type="text" required>
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
            </div>
			<button class="back-to-top" type="button"></button>

			<?php
				}else{

					$unit = $user->get('units', array('ID', '=', base64_decode($_GET['u'])));
			?>

			<div class="wrapper wrapper-content animated fadeInUp">
				<div class="row">
					<div class="col-lg-12 animated fadeInRight">
					<div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5><?php echo $unit->office_name." offices"?></h5>
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
							<table class="footable table table-striped toggle-arrow-tiny" data-filter="#filter">
                                <thead>
									<tr>
										<th>Offices</th>
										<th class="center">Acronym</th>
										<th class="center">Action</th>
									</tr>
                                </thead>
								<tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination float-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                                <tbody id="t-data">

                                </tbody>
                            </table>
							<button class="btn btn-outline btn-success btn-rounded" id="btnAdd">Add Office</button>
							<button class="btn btn-outline btn-primary btn-rounded pull-right" id="save">Save Changes</button><br><br><br>
                        </div>

						<div class="ibox-content animated fadeInDown none" id="addUnit">
							<div class="row">
								
								<div class="col-sm-6">
									<form id="profile" role="form" method="POST" enctype="multipart/form-data">
									<div class="form-group mt-20">
										<label class="form-label" for="unit_name">Office name</label>
										<input id="unit_name" name="office" class="form-input" type="text" required>
									</div>																		
								</div>
								<div class="col-sm-6"> 												
									<div class="form-group mt-20">
										<label class="form-label" for="acr">Acronym</label>
										<input id="acr" name="acr" class="form-input" type="text">
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
            </div>
			<button class="back-to-top" type="button"></button>

			<?php
				}
			?>

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
<?php
if(empty($_GET)){
?>
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

	SampleData.forEach(function(e, i){
		let temp = `
		<tr>
			<td>
				<strong style="color:#F37123">${e.acronym}</strong>
			</td>
			<td>
				<strong style="color:#009bdf">${e.office}</strong>
			</td>
			<td>
				<a href="#" data-name="note" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.note} </a>
			</td>
			<td>
				<a href="#" data-name="n_pos" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.n_pos} </a>
			</td>
			<td>
				<a href="#" data-name="verify" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.verify} </a>
			</td>
			<td>
				<a href="#" data-name="v_pos" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.v_pos} </a>
			</td>
			<td>
				<a href="#" data-name="approve" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.approve} </a>
			</td>
			<td>
				<a href="#" data-name="a_pos" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.a_pos} </a>
			</td>
			<td>
				<a href="college-units?u=${btoa(e.id)}" class="btn btn-outline btn-success btn-rounded btn-sm">View Offices</a>
			</td>
		`;
		$('#t-data').append(temp);
	});

	$.fn.editable.defaults.mode = 'inline';

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
		SendDoNothing("POST", 'xhr-update-signatory.php', {
			col: Edit
		}, {
			title: 'Success!',
			text: 'Successfully updated signatories.'
		});
	});

	$('#btnAdd').on('click', function(){
		$('#addUnit').toggleClass('none');
	});
});
</script>
<?php
}else{
?>
<script>
	$(function(){

		if(responce !== ''){
			swal({
				title: "Success!",
				text: responce,
				type: "success"
			});
		}

		var Edit = [];

		offices.forEach(function(e, i){
			let temp = `
			<tr>
				<td>
					<a href="#" data-name="specific_office" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.specific_office} </a>
				</td>
				<td class="center">
					<a href="#" data-name="acronym" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.acronym} </a>
				</td>
				<td class="center">
					<button data-id="${e.id}" data-name="${e.specific_office}" class="btn btn-rounded btn-outline btn-danger">Delete</button>
				</td>
			</tr>
			`;
			$('#t-data').append(temp);
		});

		$.fn.editable.defaults.mode = 'inline';

		$('[dataFor="edit"]').editable({
			success: function(r, v){
				let _ = $(this);
				let prop = _.attr('data-name');
				let n = offices.find(function(el){
					return el.no === _.attr('data-pk');
				});

				let inx = offices.indexOf(n);
				offices[inx][prop] = v;

				let editData = Edit.find(function(el){
					return el.no === offices[inx].no
				});
				
				if(typeof editData === 'undefined'){
					Edit.push(offices[inx]);
				}else{
					Edit.splice(Edit.indexOf(editData), 1);
					Edit.push(offices[inx]);
				}

			}
		});

		$('[data-id]').on('click', function(){
			let id = this.dataset.id;
			let currentEl = this;
			sweet({
				title: "Action: Delete",
				text: `Are you sure you want to delete ${this.dataset.name}?`,
				type: "question",
				showCancelButton: true,
				confirmButtonText: "Proceed",
				allowOutsideClick: false
			}, {
				do: function(r){
					if(r.dismiss === 'cancel'){
						swal({
							title: "Action dismissed.",
							text: "",
							type: "info"
						});
					}else{
						SendDoSomething("POST", "xhr-delete-office.php", {
							id: id
						}, {
							do: function(r){
								swal({
									title: "Success!",
									text: "Successfully deleted office.",
									type: "success"
								});
								currentEl.parentNode.parentNode.remove();
							}
						});
					}
				}
			});
			
		});

		$('#save').on('click', function(){
			SendDoNothing("POST", 'xhr-update-office.php', {
				col: Edit
			}, {
				title: 'Success!',
				text: 'Successfully updated signatories.'
			});
		});

		$('#btnAdd').on('click', function(){
			$('#addUnit').toggleClass('none');
		});
	});
</script>
<?php
}
?>
</html>
