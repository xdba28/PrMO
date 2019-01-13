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

    <title>PrMO OPPTS | Committee</title>
	<?php include "../../includes/parts/admin_styles.php"?>
	<script>
		const SampleData = [<?php
			$units = $user->selectAll('commitee');
			foreach ($units as $unit) {
				$office = $user->get('units', array('ID', '=', $unit->unit_office));
				if(isset($count)){
					$count++;
				}else{
					$count=1;
				}
				switch($unit->type){
					case "GEN":
						$type = 1;
						break;
					case "GDS":
						$type = 2;
						break;
					case "INF":
						$type = 3;
						break;
					default:
						break;
				}
				echo '{
					id: "'.$unit->ID.'",
					no: "'.$count.'",
					name: "'.$unit->name.'",
					position: "'.$unit->position.'",
					type: '.$type.',
					unit_office: '.$office->ID.'
				},';
			}
		?>];

	const officeNames = [<?php
		$offices = $user->selectAll('units');
		foreach($offices as $off){
			echo '{value: '.$off->ID.', text: "'.$off->office_name.'"},';
		}
	?>];
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
                    <h2>Committee Settings</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">System Settings</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Committee</strong>
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
                            <h5>Bicol Univerity Bids and Awards Committee</h5>

                        </div>
                        <div class="ibox-content">
							<div class="alert alert-success">
                               Here you can edit / update data of each member of the Bids and Awards Committee.
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Position</th>
									<th>Full Name</th>
                                    <th>Specification</th>
                                    <th>Unit / Office</th>
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
$(document).ready(function(){

	var Edit = [];

	$.fn.editable.defaults.mode = 'inline';

	SampleData.forEach(function(e, i){
		let num = 0;
		let type = '';
		switch(e.type){
			case 1:
				num = 1;
				type = "General";
				break;
			case 2:
				num = 2;
				type = "Goods and Services";
				break;
			case 3:
				num = 3;
				type = "Infrastructure";
				break;
			default:
				type = "unset";
				break;
		}

		let officeName = officeNames.find(function(el){
			return e.unit_office === el.value
		});


		let temp = `
		<tr>
			<td>
				<strong style="color:#F37123">${e.position}</strong>
			</td>
			<td>
				<a href="#" data-name="name" data-pk="${e.no}" data-type="text" dataFor="edit"> ${e.name} </a>
			</td>
			<td>
				<a href="#" data-name="type" bac_type="${i}" data-pk="${e.no}" data-type="select"> ${type} </a>
			</td>
			<td>
				<a href="#" data-name="unit_office" office="${i}" data-pk="${e.no}" data-type="select"> ${officeName.text} </a>
			</td>
		<tr>`;
		$('#t-data').append(temp);

		$(`[bac_type="${i}"]`).editable({
			value: num,
			source: [
				{value: 1, text: "General"},
				{value: 2, text: "Goods and Services"},
				{value: 3, text: "Infrastructure"}
			],
			success: function(r, v){
				let curElem = $(this);
				let prop = curElem.attr('data-name');
				let n = SampleData.find(function(el){
					return el.no === curElem.attr('data-pk');
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

		$(`[office="${i}"]`).editable({
			value: e.unit_office,
			source: officeNames,
			success: function(r, v){
				let curElem = $(this);
				let prop = curElem.attr('data-name');
				let n = SampleData.find(function(el){
					return el.no === curElem.attr('data-pk');
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
			let curElem = $(this);
			let prop = curElem.attr('data-name');
			let n = SampleData.find(function(el){
				return el.no === curElem.attr('data-pk');
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
		SendDoNothing("POST", 'xhr-update-commitee.php', {
			col: Edit
		}, {
			title: 'Success!',
			text: 'Successfully updated commitee.'
		});
	});

});
</script>
</html>
