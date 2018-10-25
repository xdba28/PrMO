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

    <title>PrMO OPPTS | Empty Page</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/staff_side_nav.php'; ?>
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
                <div class="col-sm-12">
                    <h2>Outgoing Documents</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Tasks</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Outgoing</strong>
                        </li>
                    </ol>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">

			<?php
				$outgoing = $user->selectAll('outgoing');
				$evalCount = 0;
				$signCount = 0;
				$generalCount = 0;
				foreach ($outgoing as $document) {
					switch ($document->transactions) {
						case 'EVALUATION':
							$evalCount++;
							break;
						
						case 'SIGNATURES':
							$signCount++;
							break;

						default:
							$generalCount++;
							break;
					}
				}
			?>
		
            <div class="row">
                <div class="col-lg-12 animated fadeInRight">
					<div class="ibox">
						<div class="ibox-title">
							<h2>For Technical Member Evaluation <span class="label label-warning" style="font-size:24px"><?php echo $evalCount;?> </span></h2>
							<div class="ibox-tools">
								<a id="t0" class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover dataTables-example" >
								<thead>
								<tr>
									<th><input btn-t="twg" type="checkbox" class="i-checks"> Select all</th>
									<th>Title</th>
									<th>Transmitting</th>
									<th>Office</th>
									<th>Date Queued</th>
								</tr>
								</thead>
								<tbody>

								<?php
									foreach ($outgoing as $document){
										
										if($document->transactions == "EVALUATION"){
											$project = $user->get('projects', array('project_ref_no', '=', $document->project));
								?>
								<tr class="">
									<td class="tdcheck"><input data="twg" type="checkbox" class="i-checks" name="twg[]" id="<?php echo $document->project;?>"> <label for="<?php echo $document->project;?>"><?php echo $document->project;?></label></td>
									<td class="td-project-title"><label for="<?php echo $document->project;?>"><?php echo $project->project_title;?></label></td>
									<td class="center">TWG</td>
									<td class="center">TWG</td>
									<td class="center"><?php echo Date::translate($document->date_registered, 1);?></td>
								</tr>
								<?php
										}
									}
								?>						

								</tbody>
								<tfoot>
								<tr>
									<th class="tdcheck"><input btn-t="twg" type="checkbox" class="i-checks"> Select all</th>
									<th>Title</th>
									<th>Transmitting</th>
									<th>Office</th>
									<th>Date Queued</th>
								</tr>
								</tfoot>
								</table>
								<button type="button" id="tOut" class="btn btn-primary btn-rounded pull-right" style="margin-right:20px"><i class="fas fa-external-link-alt"></i> Out Selected</button><br><br>
							</div>
							
							

						</div>
					</div>
				</div>
                <div class="col-lg-12 animated fadeInLeft">
					<div class="ibox ">
						<div class="ibox-title">
							<h2>For Signatures <span class="label label-warning" style="font-size:24px"><?php echo $signCount;?> </span></h2>
							<div class="ibox-tools">
								<a id="t1" class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content">

							<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover dataTables-example" >
						<thead>
						<tr>
							<th><input btn-t="out" type="checkbox" class="i-checks"> Select all</th>
							<th>Title</th>
							<th>Transmitting</th>
							<th>Office</th>
							<th>Date Queued</th>
						</tr>
						</thead>
						<tbody>

						<?php
							foreach ($outgoing as $document){
								
								if($document->transactions == "SIGNATURES"){
									$project = $user->get('projects', array('project_ref_no', '=', $document->project));
									$unit = $user->get('units', array('ID', '=', $document->transmitting_to));
						?>
						<tr class="">
							<td class="tdcheck"><input type="checkbox" data="out" class="i-checks" name="input[]" id="<?php echo $document->project;?>"> <label for="<?php echo $document->project;?>"><?php echo $document->project;?></label></td>
							<td class="td-project-title"><label for="<?php echo $document->project;?>"><?php echo $project->project_title;?></label></td>
							<td class="center"><?php echo $unit->office_name;?></td>
							<td class="center"><?php echo $document->specific_office;?></td>
							<td class="center"><?php echo Date::translate($document->date_registered, 1);?></td>
						</tr>
						<?php
								}
							}
						?>						

						</tbody>
						<tfoot>
						<tr>
							<th class="tdcheck"><input btn-t="out" type="checkbox" class="i-checks"> Select all</th>
							<th>Title</th>
							<th>Transmitting</th>
							<th>Office</th>
							<th>Date Queued</th>
						</tr>
						</tfoot>
						</table>
							</div>

						</div>
					</div>
				</div>	
                <div class="col-lg-12 animated fadeInRight">
					<div class="ibox ">
						<div class="ibox-title">
							<h2>General Documents <span class="label label-warning" style="font-size:24px"><?php echo $generalCount;?></span></h2>
							<div class="ibox-tools">
								<a id="t2" class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
							</div>
						</div>
						<div class="ibox-content">

							<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover dataTables-example" >
						<thead>
						<tr>
							<th><input btn-t="gen" type="checkbox" class="i-checks"> Select all</th>
							<th>Title</th>
							<th>Transmitting</th>
							<th>Office</th>
							<th>Date Queued</th>
						</tr>
						</thead>
						<tbody>

						<?php
							foreach ($outgoing as $document){
								
								if(($document->transactions != "SIGNATURES") && ($document->transactions != "EVALUATION")){
									$project = $user->get('projects', array('project_ref_no', '=', $document->project));
									$unit = $user->get('units', array('ID', '=', $document->transmitting_to));
						?>
						<tr class="">
							<td class="tdcheck"><input type="checkbox" data="gen" class="i-checks" name="input[]" id="<?php echo $document->project;?>"> <label for="<?php echo $document->project;?>"><?php echo $document->project;?></label></td>
							<td class="td-project-title"><label for="<?php echo $document->project;?>"><?php echo $project->project_title;?></label></td>
							<td class="center"><?php echo $unit->office_name;?></td>
							<td class="center"><?php echo $document->specific_office;?></td>
							<td class="center"><?php echo Date::translate($document->date_registered, 1);?></td>
						</tr>
						<?php
							  }
							}
						?>						

						</tbody>
						<tfoot>
						<tr>
							<th class="tdcheck"><input btn-t="gen" type="checkbox" class="i-checks"> Select all</th>
							<th>Title</th>
							<th>Transmitting</th>
							<th>Office</th>
							<th>Date Queued</th>
						</tr>
						</tfoot>
						</table>
							</div>

						</div>
					</div>
				</div>					
            </div>

			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

    <?php include '../../includes/parts/admin_scripts.php'; ?>
    <!-- Page-Level Scripts -->


</body>
<script>

	$(document).ready(function(){
		var DataTable_Twg = $('#DataTable_Twg').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
				{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
					}
				}]
		});

		var DataTable_Signiture = $('#DataTable_Signiture').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
				{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
					}
				}]
		});

		var DataTable_GenDoc = $('#DataTable_GenDoc').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
				{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
					}
				}]
		});
		
		$('#tOut').on('click', function(e){
			var data_twg = [];
			$('[name="twg[]"]:checked').each(function(i, v){
				data_twg.push($(this).attr("id"));
			});
			if(data_twg.length !== 0)
			{
				SendDoSomething("POST", "../xhr-files/xhr-staff-aid-out.php", {
					outgoing: data_twg
				}, {
					do: function(res){
						swal({
							title: "Success!",
							text: "Document(s) successfully logged out.",
							type: "success"
						});
						if(res.outgoing !== null){
							// erase table
							DataTable_Twg.row('table#DataTable_Twg > tbody > tr').remove().draw(false);
							$('table#DataTable_Twg > tbody').html('');
							res.outgoing.forEach(function(e, i){
								// DataTable_Twg.row.add([
								// 	e.project_title,

								// ]);
								$('table#DataTable_Twg > tbody').append(`<tr class="odd">
									<td class="tdcheck"><input data="twg" type="checkbox" class="i-checks" name="twg[]" id="${e.project}"> <label for="${e.project}">${e.project}</label></td>
									<td class="td-project-title"><label for="${e.project}">${e.project_title}</label></td>
									<td class="center">${e.transmitting_to}</td>
									<td class="center">${e.specific_office}</td>
									<td class="center">${e.date_registered}</td>
								</tr>`);
								TwgDataTable.draw();
							});
						}else{
							// erase tables
							DataTable_Twg.row('table#DataTable_Twg > tbody > tr').remove().draw(false);
							// DataTable_Twg.row('tr:has(td:has(div.checked:has(input:checked)))').remove().draw(false);
						}
					}
				});
			}
			else
			{
				swal({
					title: "No selected document!",
					text: "Please select a document.",
					type: "error",
					confirmButtonColor: "#DD6B55"
				});
			}
		});
	});

	
</script>
</html>