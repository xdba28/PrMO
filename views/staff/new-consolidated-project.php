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
		if(Token::check("newConsolidatedProject", Input::get('newConsolidatedProject'))){
				
	
			$staff = new Staff();
			$form_ref_no = Input::get('q');
	
			$origins = json_decode($_POST['origins'], true);
			$originsEncoded = json_encode($origins, JSON_FORCE_OBJECT);
			//echo "<pre>",print_r($test),"</pre>";
			// foreach ($origins as $value) {
			// 	echo $value;
			// }
			// echo "<pre>",print_r($originsEncoded),"</pre>";
			// echo "<pre>",print_r($_POST),"</pre>";
			// die('yourehre');



			try{

				$project_ref_no = StringGen::projectRefno('GDS'); //gds here should be dynamic for expansion, place type picker
				$mydate= explode("/", Input::get('implementation'));
				$finalDate = $mydate[2]."-".$mydate[1]."-".$mydate[0];

				$staff->startTrans(); //start transaction

				$staff->register('projects', array(
					'request_origin' => $originsEncoded,
					'project_ref_no' => $project_ref_no,
					'project_title' => Input::get('title'),
					'ABC' => Input::get('ABC'),
					'MOP' => 'TBE',
					'type' => 'consolidated',
					'end_user' => $_POST['endusers'],
					'project_status' => 'PROCESSING',
					'workflow'	=> 'For evaluation of technical working group',
					'date_registered' => Date::translate('test', 'now'),
					'implementation_date' => $finalDate
				));

				$formCount = 1;
				$formlimit = count($origins);
				foreach ($origins as $form) {

					$staff->register('project_logs', array(
						'referencing_to' => $form,
						'remarks' => "project request form {$form} was linked to a newly registered consolidated project with the project reference {$project_ref_no}.",
						'logdate' => Date::translate('test', 'now'),
						'type' =>  'IN'
					));

					$staff->register('notifications', array(
						'recipient' => $_POST['endusers'],
						'message' => "Project request form {$form} was linked to a newly registered consolidated project with the project reference {$project_ref_no}.",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "project-details?refno=".base64_encode($project_ref_no)
					));

					notif(json_encode(array(
						'receiver' => $_POST['enduser'],
						'message' => "Project request form {$form} was linked to a newly registered consolidated project with the project reference {$project_ref_no}.",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "project-details?refno=".base64_encode($project_ref_no)
					)));
					
					if($formlimit === $formCount){
						$MessageText .= $form." ";
					}else{
						$MessageText .= $form.", ";
					}

					$formCount++;
				}

				// $staff->register('outgoing', array(

				// 	'project' =>  $project_ref_no,
				// 	'transmitting_to' => 'TWG',
				// 	'specific_office' => 'TWG',
				// 	'remarks' => 'none',
				// 	'transactions' => 'EVALUATION',
				// 	'date_registered' => Date::translate('test', 'now')

				// ));

				$staff->register('project_logs', array(
					'referencing_to' => $project_ref_no,
					'remarks' => "project {$project_ref_no} queued to outgoing documents for pre-procurement evaluation.",
					'logdate' => date('Y-m-d H:i:s', strtotime('+1 second')),
					'type' =>  'IN'
				));



				$staff->endTrans(); //commit 


				Session::flash("ProjRegMult", "Project request forms: ".$MessageText."successfully registered as a consolidated project with project reference no. ".$project_ref_no);

				
				//send SMS notifications


				Redirect::To('new-consolidated-project');
				exit();

			}catch(Execption $e){
				Syslog::put($e,null,'error_log');
				Session::flash('FATAL_ERROR', 'Processed transactions are automatically canceled. ERRORCODE:0001');
			}


		}
	}



?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Consolidated Project</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
    <?php include_once'../../includes/parts/admin_styles.php'; ?>

	<script>
		var OBJ = 
		<?php
		$staff = new Staff();
		echo json_encode($staff->allPRJO_req_detail());		
		?>;
		var ProjReg = '<?php 
		if(Session::exists("ProjRegMult")) echo Session::flash("ProjRegMult");
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
                    <h2>Consolidated Project Registration</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>New Consolidated Project</strong>
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
				if(isset($_POST['forms'])){

					$admin = new admin();
					$counter = 0;
					//$request = $admin->get('project_request_forms', array('form_ref_no', '=', $_GET['q']));
					//$enduser = $admin->get('enduser', array('edr_id', '=', $request->requested_by));
					//$office = $admin->get('units', array('ID', '=', $enduser->edr_designated_office));
					//echo "<pre>",print_r($request), "</pre>";
			?>
						
            <div class="row">
                <div class="col-md-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Forms Details</h5>
                        </div>
                        <div>
                            <div class="ibox-content no-padding border-left-right">
                                <img alt="image" class="img-fluid" src="../../assets/pics/profile-bg.png">
                            </div>
                            <div class="ibox-content profile-content">
								<h5 class="text-danger">
                                    Related PR / JO Forms
                                </h5>							
                                <!-- <h3>asdasd asdad </h3> -->
								<div class="ibox-content">
									<?php
										$overallCost = 0;
										$enduserNames = [];

											foreach ($_POST['forms'] as $form) {
												$popOver = "popOver".$counter;
												$formInfo =  $user->get('project_request_forms', array('form_ref_no', '=', $form));
												$formLots = $user->getAll('lots', array('request_origin', '=', $form));

													$totalCost = 0;
													foreach ($formLots as $lot) {
														$totalCost += $lot->lot_cost;
													}

												$overallCost += $totalCost;
												$enduserNames = array_merge($enduserNames, array($formInfo->requested_by => $user->fullnameOfEnduser($formInfo->requested_by)));
									?>
									<ul>

										<li><button type="button" class="btn btn-success btn-xs btn-rounded btn-outline" id="<?php echo $popOver;?>" data-trigger="hover" title="Title" data-placement="right" data-content="<?php echo $formInfo->title;?>"><?php echo $form;?></button>
											<ul>
												<li style="margin-left:17px"><span class="badge badge-info">Enduser</span> <i class="fas fa-caret-right"></i> <?php echo $user->fullnameOfEnduser($formInfo->requested_by);?></li>
												<li style="margin-left:17px"><span class="badge badge-info">Purpose</span> <i class="fas fa-caret-right"></i> <i>"<?php echo $formInfo->purpose;?>"</i></li>
												<li style="margin-left:17px"><span class="badge badge-danger">Total / Est Cost</span> <i class="fas fa-caret-right"></i> 	<b>&#x20b1; <?php echo number_format($totalCost, 2);?></b></li>
											</ul>
										</li>

									</ul><br>
									<?php
										$counter++;
										}

										// echo "<pre>",print_r($enduserNames),"</pre>";
									?>
								</div>

								<h5 class="text-danger">
                                   Project Summary
                                </h5>

								<div class="">
										<p class="inline"><i class="fas fa-hand-holding-usd" style="font-size:18px;"></i> Total Cost <i class="fas fa-caret-right"></i> <b class="text-danger">&#x20b1;<?php echo number_format($overallCost, 2);?></b></p>
										<br>
										<p class="inline"><i class="fas fa-users" style="font-size:18px;">
											</i> Enduser/s <i class="fas fa-caret-down"></i> 
											<?php echo '<ul><li style="margin-left:50px">'.implode('</li> <li style="margin-left:50px">', $enduserNames).'</li></ul>';?>
										</p>
										<br>																									
								</div>								
								<!-- <h5 class="text-navy">
                                    About the Enduser
                                </h5>

								
								
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
                                </div> -->
                            </div>
						</div>
					</div>
                </div>
                <div class="col-md-8">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>New Consolidated Project</h5>
                        </div>
                        <div class="ibox-content">
						    <h2>
                                Project checklist
                            </h2>
                            <p class="alert alert-info">Please do verify the following requirements to be valid and correct based on the actual submission of project requirements.</p>						
							<div class="row">
								
									<div class="col-sm-6 b-r"> 
										<form id="new-project" name="register" method="POST" action="">
										<input type="text" name="origins" value='<?php echo json_encode($_POST['forms'])?>' hidden>

                                        <div class="checkbox checkbox-info checkbox-success">
                                            <input id="checkbox1" type="checkbox" required oninvalid="this.setCustomValidity('This checklist must be followed ')"oninput="this.setCustomValidity('')">
                                            <label for="checkbox1">
                                                CAF (Certificate of Availability of Funds) is valid and correct.
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-info checkbox-success">
                                            <input id="checkbox2" type="checkbox" required oninvalid="this.setCustomValidity('This checklist must be followed ')"oninput="this.setCustomValidity('')">
                                            <label for="checkbox2">
                                               PPMP/APP/Suplemental documents are attached and validated by the personnel incharge in the PRMO.
                                            </label>
                                        </div>											
                                        <div class="checkbox checkbox-info checkbox-success">
                                            <input id="checkbox3" type="checkbox" required oninvalid="this.setCustomValidity('This checklist must be followed ')"oninput="this.setCustomValidity('')">
                                            <label for="checkbox3">
                                                Complete documents that the office may reqiure and necessary under specific conditions applied.
                                            </label>
                                        </div>
										<div class="form-group mt-20">
											<label for="ABC" class="form-label">ABC</label> <input type="number" min="<?php echo $overallCost;?>" step="0.01" id="ABC" name="ABC" class="form-control form-input" required>
										</div>				
										<div class="form-group" id="data_2" >
											<label class="font-normal">Implementation date</label>
											<div class="input-group date" id="popOver0" data-trigger="hover" title="Instructions" data-placement="top" data-content="If the project has multiple implementation date, register closest date.">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="implementation" class="form-control" value="" required>
											</div>
										</div>

										
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="title" class="my-blue">Project title</label> <textarea name="title" id="title" placeholder="New Project Title" class="form-control" rows="10" required></textarea>
										</div>	
											<input type="text" name="newConsolidatedProject" value="<?php echo Token::generate('newConsolidatedProject');?>" hidden readonly>
											<?php

												$endusersId = array_values(array_flip($enduserNames));
												$jsonNames = json_encode($endusersId, JSON_FORCE_OBJECT);
											
											?>
											<input type="text" name="endusers" value='<?php echo $jsonNames;?>' hidden readonly>
										
									</div>	
									<div class="col-lg-12">
									
										<button class="btn btn-primary btn-rounded pull-right" type="submit" form="new-project">Submit</button>
										<a href="new-consolidated-project" class="btn btn-danger btn-rounded pull-right" style="margin-right:5px">Cancel</a>
										</form>	
									</div>
							</div>
                        </div>
                    </div>

                </div>
            </div>	
			
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
								<form action="" method="POST" name="select" enctype="multipart/form-data">
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
			// var endusersArray = [];
			// endusersArray.push(

				<?php
					// echo "'".implode("', '", $endusersId)."'";
					
				?>

			// );


			// 	console.log(endusersArray);
			// $('#hiddenIds').val(JSON.stringify(endusersArray)); //store array

			//  var value = $('#hiddenIds').val(); //retrieve array
			//  value = JSON.parse(value);	
	</script>	
	<script>

	$(document).ready(function(){

		if(ProjReg !== ""){
			swal({
				title: "Projects successfully registered!",
				text: ProjReg,
				type: 'success'
			});
		}

		function start(){
			$('#nwprj-tbl-data').html('');
			OBJ.forEach(function(el, index){
				if(el.registered === false){
					var user = el.req_by.split(":");
					var data_tmp = `
					<tr>
						<td style="text-align:center"><input type="checkbox" class="i-checks" name="forms[]" value="${el.id}"></td>
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

		$(document.select).on('submit', function(){
			let boolean = null;
			let selected = $('.i-checks:checked');
			if(selected.length >= 2){
				selected.each(function(i, e){
					let sel_id = OBJ.find(function(el){
						return el.id === e.value
					});

					if(sel_id.log_exist === false){
						boolean = false;
					}
				});
			}else{
				if(selected.length === 0){
					swal({
						title: "Action invalid!",
						text: "Please select a request form",
						confirmButtonColor: "#DD6B55",
						type: "error"
					});
				}else{
					swal({
						title: "Action invalid!",
						text: "Please select more than 1 request forms.",
						confirmButtonColor: "#DD6B55",
						type: "error"
					});
				}
				return false;
			}

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
