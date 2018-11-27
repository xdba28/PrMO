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

    <title>PrMO OPPTS | Overview</title>
	<?php include "../../includes/parts/admin_styles.php"?>

	<script>

		const SampleData = [
			
			<?php
			$units = $user->selectAll('units');
			foreach ($units as $unit) {
				if(isset($count)){$count++;}else{$count=1;}
				echo '
					{
						no: "'.$count.'",
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
                    <h2>User Overview</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Users</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Overview</strong>
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
                            <h5>College and Office Unit Settings </h5>

                        </div>
                        <div class="ibox-content">
							<div class="alert alert-info">
                               Here you can edit the default data per College / Office Unit like the personnel incharge of noting, verifying, and approving the Purchase Requests or Job Orders. Click on the underlined field to edit. After finalizing all your changes click the "Save Changes" button at the bottom-right of this page.
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
									<th>Accronym</th>
                                    <th>College / Unit</th>
                                    <th>Noted By</th>
                                    <th>Noted By Job title</th>
                                    <th>Verifier</th>
									<th>Verifier Job title</th>
                                    <th>Approving</th>
									<th>Approving Job title</th>
                                </tr>
                                </thead>
                                <tbody id="t-data">

                                </tbody>
                            </table>
						<button class="btn btn-outline btn-primary btn-rounded pull-right" id="save">Save Changes</button><br><br><br>
                        </div>
                    </div>
                
					
					</div>
				</div>
		
				
			
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
});
</script>
</html>
