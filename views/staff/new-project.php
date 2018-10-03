<?php 

    require_once('../../core/init.php');

	$user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
	}

	

	
	if(Input::exists()){
		if(Token::check("newProject", Input::get('newProject'))){
				
	
			$staff = new Staff();
			$form_ref_no = Input::get('q');

			$enduser = ["0" => $_POST['enduser']];
			$enduser_encoded = json_encode($enduser, JSON_FORCE_OBJECT);


			try{

			$project_ref_no = StringGen::projectRefno('GDS'); //gds here should be dynamic for expansion, place type picker

			$staff->startTrans(); //start transaction

			$staff->register('projects', array(
				'request_origin' => $form_ref_no,
				'project_ref_no' => $project_ref_no,
				'project_title' => Input::get('title'),
				'ABC' => Input::get('ABC'),
				'MOP' => 'TBE',
				'type' => 'single',
				'end_user' => $enduser_encoded,
				'project_status' => 'PROCESSING',
				'workflow'	=> 'For evaluation of technical working group',
				'date_registered' => date('Y-m-d H:i:s')
			));

			$staff->register('project_logs', array(
				'referencing_to' => $form_ref_no,
				'remarks' => "project request {$form_ref_no} registered as a single project with the reference no of {$project_ref_no}.",
				'logdate' => date('Y-m-d H:i:s'),
				'type' =>  'IN'
			));

			$staff->register('outgoing', array(

				'project' =>  $project_ref_no,
				'transmitting_to' => 'TWG',
				'specific_office' => 'TWG',
				'remarks' => 'none',
				'transactions' => 'EVALUATION',
				'date_registered' => date('Y-m-d H:i:s')

			));

			$staff->endTrans(); //commit

			Session::flash("ProjReg", "Project successfully registered!");
			//disable the "register" now button in the new-project page to prevent any data discrepancy
			//pop some sweet alert after project registration NOTE: Pop the sweet alert in the "localhost/prmo/views/staff/new-project" NOT in the "localhost/prmo/views/staff/new-project?q='form_ref_no' "
			//send SMS notifications

			}catch(Execption $e){
				die($e->getMessage());
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

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

	<script>
		var OBJ = 
		<?php
		$user = new Staff();
		echo json_encode($user->allPRJO_req_detail());		
		?>;
		var ProjReg = '<?php 
		if(Session::exists("ProjReg")) Session::flash("ProjReg");
		else echo "";
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
                    <h2>Project Registration</h2>
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

					$admin = new Admin();
					$valid_request = $admin->selectAll("project_request_forms");

					$valid = false;

					foreach ($valid_request as $request){
						if($_GET['q'] == $request->form_ref_no){
							$valid = true;
						}
					}
	
					if(!$valid){
						include('../../includes/errors/404.php');
						exit();						
					}

					$request = $admin->get('project_request_forms', array('form_ref_no', '=', $_GET['q']));
					$enduser = $admin->get('enduser', array('edr_id', '=', $request->requested_by));
					$office = $admin->get('units', array('ID', '=', $enduser->edr_designated_office));
			?>
			
            <div class="row">
                <div class="col-md-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Request</h5> <h5 class="text-danger"><?php echo $request->form_ref_no; ?></h5>
                        </div>
                        <div>
                            <div class="ibox-content no-padding border-left-right">
                                <img alt="image" class="img-fluid" src="../../assets/pics/profile-bg.png">
                            </div>
                            <div class="ibox-content profile-content">
								<h5 class="text-navy">
                                    About the Request
                                </h5>							
                                <h3><?php echo $request->title;?></h3>
								
								<h5 class="text-navy">
                                    About the Enduser
                                </h5>
								<div class="">
									<p class="inline"><i class="ti-user" style="font-size:18px;"></i> </p>
									<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $admin->fullnameOfEnduser($request->requested_by);?></p>
									<br>
									<p class="inline"><i class="fa fa-phone" style="font-size:18px;"></i> </p>
									<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $enduser->phone;?></p>
									<br>
									<p class="inline"><i class="ti-email" style="font-size:18px;"></i> </p>
									<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $enduser->edr_email;?></p>
									<br>
									<p class="inline"><i class="fa fa-institution" style="font-size:18px;"></i> </p>
									<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $office->office_name;?></p>																	
								</div>								

								
								
                                <h5 class="text-navy">
                                    something
                                </h5>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.
                                </p>
                                <div class="row m-t-lg">
                                    <div class="col-md-4">
                                        <span class="bar">5,3,9,6,5,9,7,3,5,2</span>
                                        <h5><strong>10</strong> Requests</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="line">5,3,9,6,5,9,7,3,5,2</span>
                                        <h5><strong>8</strong> Success</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="bar">5,3,2,-1,-3,-2,2,3,5,2</span>
                                        <h5><strong>2</strong> Failure</h5>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
                </div>
                <div class="col-md-8">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>New Project</h5>
                        </div>
                        <div class="ibox-content">
						    <h2>
                                Project checklist
                            </h2>
                            <p class="alert alert-info">Please do verify the following requirements to be valid and correct based on the actual submission of project requirements.</p>						
							<div class="row">
								
									<div class="col-sm-6 b-r"> 
										<form id="new-project" role="form" method="POST">								
                                        <div class="checkbox checkbox-info checkbox-circle">
                                            <input id="checkbox1" type="checkbox" required oninvalid="this.setCustomValidity('This checklist must be followed ')"oninput="this.setCustomValidity('')">
                                            <label for="checkbox1">
                                                CAF (Certificate of Availability of Funds) is valid and correct.
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-info checkbox-circle">
                                            <input id="checkbox2" type="checkbox" required oninvalid="this.setCustomValidity('This checklist must be followed ')"oninput="this.setCustomValidity('')">
                                            <label for="checkbox2">
                                               PPMP/APP/Suplemental documents are attached and validated by the personnel incharge in the PRMO.
                                            </label>
                                        </div>											
                                        <div class="checkbox checkbox-info checkbox-circle">
                                            <input id="checkbox3" type="checkbox" required oninvalid="this.setCustomValidity('This checklist must be followed ')"oninput="this.setCustomValidity('')">
                                            <label for="checkbox3">
                                                Complete documents that the office may reqiure and necessary under specific conditions applied.
                                            </label>
                                        </div>
										<div class="form-group mt-20">
											<label for="ABC" class="form-label">ABC</label> <input type="number" min="0.01" step="0.01" id="ABC" name="ABC" class="form-control form-input" required>
										</div>				
										<label class="font-normal">Nearest Implementation date</label>
										<div class="input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="08/09/2014">
										</div>
										
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="title" class="my-blue">Project title</label> <textarea name="title" id="title" class="form-control" rows="10" required><?php echo $request->title;?></textarea>
										</div>	
											<input type="text" name="newProject" value="<?php echo Token::generate('newProject');?>" hidden readonly>
											<input type="text" name="enduser" value="<?php echo $request->requested_by;?>" hidden readonly>
										</form>		
									</div>	
									<div class="col-lg-12">
												<button class="btn btn-primary btn-rounded pull-right" type="submit" form="new-project">Submit</button>
												<a href="new-project" class="btn btn-danger btn-rounded pull-right" style="margin-right:5px">Cancel</a>	
									</div>
							</div>
                        </div>
                    </div>

                </div>
            </div>	
			
			
			<?php
				}else{
			?>
			
			<div class="row">
				<div class="col-sm-8">
					<div class="ibox">
						<div class="ibox-content">
							<!-- <span class="text-muted small float-right">
									Last Refresh: <i class="fa fa-clock"></i>
							</span> -->
							<h2>Unregistered Projects</h2>
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
							<span class="float-right small"><button type="button" id="loading-example-btn" class="btn btn-white btn-sm" onClick="refreshPage()"><i class="fa fa-refresh"></i> Refresh</button></span>
							<ul class="nav nav-tabs">
								<li><a class="nav-link active" data-toggle="tab" href="#tab-1"><i class="fa fa-info-circle"></i> Requests</a></li>                             
							</ul>
							<div class="tab-content">
								<div id="tab-1" class="tab-pane active">
									<div class="full-height-scroll">
										<div class="table-responsive">
											<table class="footable table table-striped table-hover" data-filter=#filter>
												<tr>
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
											<a id="btnlink" ><button class="btn btn-primary btn-sm btn-block mt-10" id="registerNow"><i class="fa fa-download"></i> Register Now</button></a>
							
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

		function poll(){
			$.ajax({
				type: "GET",
				url: "xhr-receive-proj.php",
				timeout: 5000,
				success: function(d){
					OBJ = d
					$('#nwprj-tbl-data').html('');
					start();
					setTimeout(poll, 15000);
				},
				error: function(){
					setTimeout(poll, 15000);
				}
			});
		}

		if(ProjReg !== ""){
			swal({
				title: ProjReg,
				text: "",
				confirmButtonColor: "#DD6B55",
				type: 'success',
				timer: 13000
			});
		}

		function start(){
			$('#nwprj-tbl-data').html('');
			OBJ.forEach(function(el, index){
				if(el.registered === false){
					var user = el.req_by.split(":");
					var data_tmp = `
					<tr>
						<td><a href="#${el.id}" class="client-link">${el.id}</a></td>
						<td>${user[1]}</td>
						<td><i class="fa fa-clock"></i> ${el.date_created}</td>
						<td><button class="ladda-button btn-rounded btn btn-warning" proj="${el.id}" data-style="zoom-in">Receive</button></td>
					</tr>`;
					$('#nwprj-tbl-data').append(data_tmp);
					if(el.log_exist === true) $(`[proj="${el.id}"]`).prop('disabled', true);
					else $(`[proj="${el.id}"]`).prop('disabled', false);
				}
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
					$('#btnlink').attr("href", `?q=${PROJ.id}`)

					if(PROJ.log_exist) $('#registerNow').prop('disabled', false);
					else $('#registerNow').prop('disabled', true);

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
						text: "Please reload the Page."
					});
				}
				$('#popOver0').on('click', function(){
					window.open(`view-proj?id=${$(this).attr("proj-comp")}`);
				});
			});

			$('.ladda-button').ladda();
			$('[proj]').on('click', function(){
				var SendBtn = $(this);
				SendBtn.ladda('start');
				var xhrData = JSON.stringify(OBJ.find(function(el){
					return el.id === SendBtn.attr("proj");
				}));

				$.ajax({
					type: "POSt",
					url: "xhr-receive-proj.php",
					data: {obj: xhrData},
					timeout: 5000,
					success: function(data){
						if(typeof data === "object" && data !== null && !(data.success === false)){
							OBJ = data;
							swal({
								title: 'Project Received!',
								text: `You can now register ${SendBtn.attr("proj")} as a new project.`,
								confirmButtonColor: "#DD6B55",
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
					error: function(){
						swal({
							title: "An Error Occurred!",
							text: "Request Not Processed"
						});
						SendBtn.ladda('stop');
					}
				});
			});
		}
		start();
		setTimeout(poll, 15000);
	});

	</script>
</body>

</html>
