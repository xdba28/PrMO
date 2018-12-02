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
		foreach($_POST['project'] as $id){
			echo $id . "<br>";
		}
		// echo "<pre>".print_r($_POST)."</pre>";
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

	<script>
		var OBJ = 
		<?php
		$staff = new Staff();
		echo json_encode($staff->allPRJO_req_detail());		
		?>;
		// var ProjReg = '<?php 
		// if(Session::exists("ProjReg")) echo Session::flash("ProjReg");
		// else echo "";
		?>';
		console.log(OBJ);
	</script>
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
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Consolidated Project Registration</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>New Project</strong>
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
            <div class="wrapper wrapper-content animated fadeInUp">
			
			<?php
				if(isset($_GET['q'])){

					//$admin = new admin();
					//$request = $admin->get('project_request_forms', array('form_ref_no', '=', $_GET['q']));
					//$enduser = $admin->get('enduser', array('edr_id', '=', $request->requested_by));
					//$office = $admin->get('units', array('ID', '=', $enduser->edr_designated_office));
					//echo "<pre>",print_r($request), "</pre>";
			?>
						
						<!-- page content of registration for consolidated projects -->
			
			<?php
				}else{
			?>
			
						<!-- content of all project to be consilidate like in the new project -->
			<div class="row">
				<div class="col-sm-8">
					<div class="ibox">
						<div class="ibox-content">
							<!-- <span class="text-muted small float-right">
									Last Refresh: <i class="fa fa-clock"></i>
							</span> -->
							<h2>Available Request Forms for Consolidated Project</h2>
							<p>
								You can search a Purchase request or Job order by its title or end user's name. But it is adviced to search through its Reference number indicated in the printed hard copy of the actual Purchase request or Job order form.
							</p>
							<div class="input-group">
								<input type="text" placeholder="Search Reference No." class="input form-control" id="filter">
								<span class="input-group-append">
										<button type="button" class="btn btn btn-primary"> <i class="fa fa-search"></i> Search</button>
								</span>
							</div>
							<div class="clients-list">
								<form action="" method="POST" name="form" enctype="multipart/form-data">
									<span class="float-right small"><button type="submit" id="" class="btn btn-primary btn-sm"><i class="fas fa-list-ol" style="font-size:18px"></i> Register Selected forms</button></span>
									<ul class="nav nav-tabs">
										<li><a class="nav-link active" data-toggle="tab" href="#tab-1"><i class="fa fa-info-circle"></i> Requests</a></li>                             
									</ul>
									<div class="tab-content">
										<div id="tab-1" class="tab-pane active">
											<div class="full-height-scroll">
												<div class="table-responsive">
													<table class="footable table table-striped table-hover" data-filter="#filter">
														<tr>
															<th>Select</th>
															<th>Reference No.</th>
															<th>End User</th>
															<th>Date Created</th>
															<th>Action</th>
														</tr>
														<tbody id="nwprj-tbl-data">
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="ibox selected">

						<div class="ibox-content">
							<div class="tab-content">
								<div id="default-tab" class="tab-pane active">
									<div class="row m-b-lg">
		
									</div>
									<div class="client-detail middle-box text-center animated fadeInUp">
											<h2><i class="fa fa-info-circle"></i> Click on the Reference No. to view request details.</h2>
									</div><br><br><br>
								</div>
								
								
								<div id="" class="tab-pane" data="side-panel">
									<div class="row m-b-lg">
										<div class="col-lg-12">
											<strong>
												<h3>Request Title</h3>
											</strong>

											<p>
												<h2> </h2>
											</p>
											
											<button type="button" class="btn btn-warning btn-sm btn-block" id="popOver0" data-trigger="hover" title="Instructions" data-placement="left" data-content="Click on this to download a soft copy of the original PR / JO created in the system to compare it to the actual submission of the Enduser."><i class="ti-split-h"></i> Compare to Original</button>
											<!-- <a href="" class="btn btn-primary btn-sm btn-block" id="registerNow"><i class="fa fa-download"></i> Register Now</a> -->
											<!-- <a id="btnlink" ><button class="btn btn-primary btn-sm btn-block mt-10" id="registerNow"><i class="fa fa-download"></i> Register Now</button></a> -->
							
										</div>
									</div>
								
										<div class="full-height-scroll">

											<strong>Request Summary</strong>

											<ul class="list-group clear-list">
												<div id="lot-data">
												</div>
												<li class="list-group-item">
													Form Created and Downloaded by the Enduser:
													<span class="float-center" date="created"> </span>
												</li>
											</ul>

											<hr/>
										</div>
										<br><br><br><br><br><br>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>						
			<?php
				}
			?>						
			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

    <?php include_once '../../includes/parts/admin_scripts.php'; ?>	
	<script>

	$(document).ready(function(){

		// if(ProjReg !== ""){
		// 	var ProjRegMesg = ProjReg.split("|");
		// 	var ProjRegDetail = ProjRegMesg[1].split(":");
		// 	swal({
		// 		title: ProjRegMesg[0],
		// 		text: `Ref no: ${ProjRegDetail[1]} is now registered as Project ${ProjRegDetail[0]}`,
		// 		type: 'success'
		// 	});
		// }

		function start(){
			$('#nwprj-tbl-data').html('');
			OBJ.forEach(function(el, index){
				if(el.registered === false){
					var user = el.req_by.split(":");
					var data_tmp = `
					<tr>
						<td style="text-align:center"><input type="checkbox" class="i-checks" name="project[]" value="${el.id}"></td>
						<td dataFor="active"><a href="#${el.id}" class="client-link">${el.id}</a></td>
						<td>${user[1]}</td>
						<td><i class="fa fa-clock"></i> ${el.date_created}</td>
						<td><button class="ladda-button btn-rounded btn btn-warning" proj="${el.id}" data-style="zoom-in">Receive</button></td>
					</tr>`;
					$('#nwprj-tbl-data').append(data_tmp);
					if(el.log_exist){
						$(`[proj="${el.id}"]`).prop('disabled', true);
						$(`[proj="${el.id}"]`).prop('class', 'ladda-button btn-rounded btn btn-basic');
					}
					else $(`[proj="${el.id}"]`).prop('disabled', false);
				}
			});

			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});


			$(document.body).on("click",".client-link",function(e){
				e.preventDefault();
				var ID = $(this).attr('href').split("#");
				var PROJ = OBJ.find(function(el){
					return el.id === ID[1];
				});

				if(typeof PROJ !== "undefined"){
					$('[data="side-panel"]').attr("id", PROJ.id);
					$('[data="side-panel"] h2').html(PROJ.title);
					$('#popOver0').attr("proj-comp", PROJ.id);


					$('#lot-data').html('');
					PROJ.lot_details.forEach(function(el, index){
						if(PROJ.type === "PR"){
							if(el.l_title === 'static lot'){
								var lot_temp = `
								<li class="list-group-item fist-item">
									<span class="float-right"> No. of Items ${el.numReq}</span>
									Unspecified Lot
								</li>`;
							}else{
								var lot_temp = `
								<li class="list-group-item fist-item">
									<span class="float-right"> No. of Items ${el.numReq}</span>
									${el.l_title}
								</li>`;						
							}
						}else if(PROJ.type === "JO"){
							var lot_temp = `
							<li class="list-group-item fist-item">
								<span class="float-right"> No. of List ${el.numReq}</span>
								${el.l_title}
							</li>`;	
						}
						$('#lot-data').append(lot_temp);
					});
					$('span[date="created"]').html(PROJ.date_created);
					$(".selected .tab-pane").removeClass('active');
					$($(this).attr('href')).addClass("active");
				}else{
					swal({
						title: "An Error Occurred!",
						text: "Please reload the Webpage.",
						type: "error"
					});
				}
				$('#popOver0').on('click', function(){
					// window.open(`view-proj?id=${$(this).attr("proj-comp")}`);
					window.open(`../../bac/pdf/${$(this).attr("proj-comp")}.pdf`);
				});
			});

			$('.ladda-button').ladda();
			$('[proj]').on('click', function(){
				var SendBtn = $(this);
				SendBtn.ladda('start');
				var xhrData = JSON.stringify(OBJ.find(function(el){
					return el.id === SendBtn.attr("proj");
				}));

				SendDoSomething("POST", "xhr-receive-proj.php", {
					obj: xhrData
				}, {
					do: function(data){
						if(typeof data === "object" && data !== null){
							OBJ = data;
							swal({
								title: 'Project Received!',
								text: `You can now register ${SendBtn.attr("proj")} as a new project.`,
								type: 'success',
								timer: 13000
							});
						}else if(data.success === false){
							swal({
								title: "An Error Occurred!",
								text: "Request Not Processed"
							});
						}
						SendBtn.ladda('stop');
						$('#nwprj-tbl-data').html('');
						start();
					},
					f: function(){
						swal({
							title: "An Error Occurred!",
							text: "Request Not Processed",
							confirmButtonColor: "#DD6B55",
							type: "error"
						});
						SendBtn.ladda('stop');
					}
				});
			});

			$('[dataFor="active"]').on('click', function(){
				$('#nwprj-tbl-data  tr').attr('style', '');
				$(this).parent().css("background", "#34495E").css('color', 'white');
			});
		}
		start();
		setTimeout(function(){
			SendDoSomething("GET", "xhr-receive-proj.php", null, {
				do: function(d){
					OBJ = d;
					$('#nwprj-tbl-data').html('');
					start();
				},
			}, true)
		}, 60000);

		$(document.form).on('submit', function(){
			let boolean = null;
			$('.i-checks:checked').each(function(i, e){
				let sel_id = OBJ.find(function(el){
					return el.id === e.value
				});

				if(sel_id.log_exist === false){
					boolean = false;
				}
			});
			if(boolean === false){
				swal({
					title: "Unreceived project in selection!",
					text: "There is an unreceived project in the selection.",
					confirmButtonColor: "#DD6B55",
					type: "error"
				});
				return boolean
			}
		});

	});

	</script>
</body>

</html>
