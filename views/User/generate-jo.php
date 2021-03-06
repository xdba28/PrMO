<?php 

    require_once('../../core/init.php');

	$user = new User(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
	}

	if(Input::exists()){
	
		if(Token::check("joToken",Input::get('joToken'))){

			try{				
				//register project details in "project_request_forms"table
				$current_year = date('Y');
				$form_ref_no =  'JO'.$current_year.'-'.StringGen::generate(); //this would be the refence number of the form
				$date_created =  date('Y-m-d H:i:s'); //this would be a the identifier for registering of lots
				$number_of_lots = Input::get('lot'); // number of lots for this request form
	
				$rows_per_lot = json_decode($_POST['rowCount'], true); //decode the row counter per lot
				$counter = 0;
				foreach($rows_per_lot as $element){
					$myArray[$counter] = $element["tag"] + 1;
					$counter++;
				}
				// $myArray[0] = $rows_per_lot["lst"] + 1;

				$user->register('project_request_forms', array(
		
					'form_ref_no' => $form_ref_no,
					'title' => Input::get('title'),
					'purpose' => Input::get('purpose'),
					'requested_by' => Session::get(Config::get('session/session_name')),
					'noted_by' => Input::get('noted'),
					'verified_by' => Input::get('verified'),
					'approved_by' => Input::get('approved'),
					'type' => 'JO',
					'date_created' => $date_created
	
				));
						
					//register lot general details in "lots" table
					for($x=0; $x<$number_of_lots; $x++){  //$x is lot level
					
		
						$lot_title = 'L'.$x.'-title'; //L${index}-title				
						$lot_cost = 'L'.$x.'-ELC';
						$lot_note = 'L'.$x.'-note';
						$lot_no = $x + 1;
		
						$user->register('lots', array(
		
							'request_origin' => $form_ref_no,
							'lot_no' => $lot_no,
							'lot_title' => Input::get($lot_title),
							'lot_cost' => Input::get($lot_cost),
							'note' => Input::get($lot_note)
		
						));
		
			
						//register all item rows per lot in "lot_content_for_pr" table		
						$temp = $user->ro_ln_composite($form_ref_no, $lot_no);
						$lot_id = $temp->lot_id;
						
						for($y=0; $y<$myArray[$x]; $y++){ //$y is item per lot level					
		
							$listname = 'L'.$x.'-listname-'.$y;    		//L${i}-listname-0
							$tags = 'L'.$x.'-tags-'.$y;		 			//L${i}-tags-0
		
							$user->register('lot_content_for_jo', array(
		
								'lot_id_origin' => $lot_id,
								'header' => Input::get($listname),
								'tags' => Input::get($tags)
		
							));
						}	
					}

					Syslog::put("create jo form");
		
					//proceed to printing the actual form						
					Session::flash('Request', $form_ref_no.":JO");
					sleep(3);
					Redirect::To('my-forms');
					exit();
		
			}catch(Exception $e){
				Syslog::put($e,null,"error_log");
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0001");
			}
		}
	}
   

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">

    <title>PrMO OPPTS | Job Order</title>


	<?php include_once '../../includes/parts/user_styles.php'; ?>

	<script>
		function form(){
			$('div.ibox-content').toggleClass('sk-loading');
			swal({
				title: "Success!",
				text: "Request form will be downloaded shortly.",
				type: "success"
			});
		}
	</script>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/user_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Job Order Form</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Request Forms</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Job Order</strong>
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
				<div class="row">
					<div class="col-lg-12 animated fadeInRight">
						<div class="ibox myShadow">
							<div class="ibox-content">
								<div class="sk-spinner sk-spinner-double-bounce">
										<div class="sk-double-bounce1"></div>
										<div class="sk-double-bounce2"></div>
								</div>	
								<div class="tabs-container">
									<ul class="nav nav-tabs">
										<li><a class="nav-link active" data-toggle="tab" href="#tab-1">Project &nbsp&nbsp<i class="ti-folder" style="font-size:18px"></i></a></li>
										<li><a class="nav-link" data-toggle="tab" href="#tab-2">Particulars &nbsp&nbsp<i class="ti-pencil-alt" style="font-size:18px"></i></a></li>
										<li><a class="nav-link" data-toggle="tab" href="#tab-3">Signatories &nbsp&nbsp<i class="ti-user" style="font-size:18px"></i></a></li>
									</ul>
									<div class="tab-content">
										<div><form method="POST" id="jo_form" onsubmit="form()"></form></div>
										<div id="tab-1" class="tab-pane active">
											<div class="panel-body">
											   <h2><a style="color:#2a9c97">Step 1 of  &nbsp3</a><br>Project Information</h2>

											   <div class="alert alert-success">
												  Note: If your Project has multiple separated Purchase Requests or Job Orders, you and worry free to merge your requests forms in the system, our personnel incharge would be the one to merge Purchase requests and Joborder to one single project.
											   </div>

												<p>Specify the required fields to generate the Job Order Form that suits your need.</p><br>

												<div class="row">
													<div class="col-lg-7">
														<div class="form-group">
															<label>Project title *</label>
															<input id="title" name="title" type="text" class="form-control" form="jo_form" required>
														</div>
														<div class="form-group">
															<label>Purpose *</label>
															<input id="purpose" name="purpose" type="text" class="form-control" form="jo_form" required>
														</div>
														<div class="form-group">
															<label>Number of Lots *</label>
															<input id="lot" name="lot" type="number" min=1 class="form-control" form="jo_form" required>
														</div>
													</div>
													<div class="col-lg-3">
														<div class="text-center">
															<div style="margin-left: 100px">
																<i class="ti-layout-tab" style="font-size: 180px;color: #FFD700 "></i>
															</div>
														</div>
													</div>	
													<div class="col-lg-7">
														<a id="#tab-1" href="#tab-1" data="tab" class="btn btn-primary pull-right">Next</a>								
													</div>											
												</div>

											</div>	
										</div>
										<div id="tab-2" class="tab-pane">
											<div class="panel-body">
												<h2><a style="color:#2a9c97">Step 2 of  &nbsp3</a><br>Particulars Setting</h2>
												<p>List all your item needed to the corresponding fields.</p>

														<div class="">
															<div class="add-project" id="popOver" data-trigger="hover" title="Friendly Reminder" data-placement="left" data-content="It seems that you're a bit confused here 🤔 that I catch your attention. Cheer up‼ Cause we're here to guide you. 😉👌 Click on the button to proceed 👉">											
																<button type="button" class="btn btn-danger btn-rounded btn-outline">New to this <i class="fa fa-question" style="font-weight:900"></i></button>
															</div>
														</div>										
												
												<div class="row" id="wf-stp-2">
													<div class="col-lg-12" >
														<h1>No Lots Set.</h1>
													</div>
													<div class="col-lg-12">
														<a id="#tab-2" href="#tab-2" data="tab" class="btn btn-primary pull-right" style="margin-right: 20px">Next</a>								
													</div>											
												</div>
											</div>
										</div>
										<div id="tab-3" class="tab-pane">
											<div class="panel-body">
												   <h2><a style="color:#2a9c97">Step 3 of  &nbsp3</a><br>Project Signatories</h2>

													<p>Specify all signatories to finalized this form.</p>
													
													<div class="row">
													<?php
														$enduserData = $user->get('enduser', array('edr_id', '=', $user->data()->account_id));
														$enduserUnitData = $user->get('units', array('ID', '=', $enduserData->edr_designated_office));
														$signatories = array();
														foreach ($enduserUnitData as $key => $value) {
															if($value == "unset"){
																$signatories[$key] = "No data available";
															}else{
																$signatories[$key] = $value;
															}
														}

														// echo "<pre>",print_r($signatories),"</pre>";
													?>											
														<div class="col-lg-7">
															<div class="form-group">
																<label>End User *</label>
																<input id="enduser" name="enduser" type="text" value="<?php echo $currentUser[0];?>" class="form-control" disabled form="jo_form" required>
															</div>
															<div class="form-group">
																<label>Noted By *</label>
																<input id="noted" name="noted" type="text" value="<?php echo $signatories['note'];?>" class="form-control" form="jo_form" readonly>
															</div>
															<div class="form-group">
																<label>Verified By *</label>
																<input id="verified" name="verified" type="text" value="<?php echo $signatories['verifier'];?>" class="form-control" form="jo_form" readonly>
															</div>
															<div class="form-group">
																<label>Aproved By *</label>
																<input id="approved" name="approved" type="text" value="<?php echo $signatories['approving'];?>" class="form-control" form="jo_form" readonly>
																<input type="text" name="rowCount" readonly form="jo_form" hidden required>
																<input type="text" name="joToken" readonly hidden value="<?php echo Token::generate("joToken");?>" required form="jo_form">
															</div>													
														</div>
														<div class="col-lg-3">
															<div class="text-center">
																<div style="margin-left: 100px;  margin-top:20px">
																	<i class="ti-user" style="font-size: 180px;color: #FFD700;"></i>
																</div>
															</div>
														</div>	
														<div class="col-md-7">
															<button class="btn btn-primary btn-outline pull-right" type="submit" form="jo_form">Finish</button>
															<a href="Dashboard"><button type="button" class="btn btn-danger btn-outline pull-right" style="margin-right:5px">Cancel</button></a>
														</div>
													</div>											
											</div>
										</div>							
									</div>
								</div>								
							</div>
						</div>
					</div>
				</div>
				<br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br>
			</div>
			

			<!-- Main Content End -->
			<button class="back-to-top" type="button"></button>		
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include '../../includes/parts/user_scripts.php'; ?>

    <script>
        $(document).ready(function(){

			$('[data="tab"]').on('click', function(){
				var tab = $(this).attr("id").split("-");
				$(`a[href="${tab[0]}-${tab[1]}"]`).removeClass('active show');
				$(`#tab-${tab[1]}`).removeClass('active show');
				tab[1]++;
				$(`a[href="${tab[0]}-${tab[1]}"]`).addClass('active show');
				$(`#tab-${tab[1]}`).addClass('active show');
			});

			$('#lot').on('change', function()
			{
				var lots = this.value;
				var obj = [];
				$('#wf-stp-2').html('');
				for(let i = 0 ; i < lots ; i++)
				{
					obj.push({tag: 0});
					var tmp_lot = `
					<div class="col-lg-6">
						<div class="ibox" style="border: 1px solid rgba(28,110,164,0.63); padding:20px">
							<div class="alert alert-info">
								<center><h5>Lot Number ${i + 1}</h5></center>
								
							</div>
							<div class="ibox-content" id="lot-${i}" style="-webkit-box-shadow: -1px 4px 9px 0px rgba(0,0,0,0.66);-moz-box-shadow: -1px 4px 9px 0px rgba(0,0,0,0.66);box-shadow: -1px 4px 9px 0px rgba(0,0,0,0.66);">
							
							<div class="form-group">
								<label>Lot Title *</label>
								<input type="text" class="form-control" name="L${i}-title" form="jo_form" required>
								<label>Estimated Cost *</label>
								<input type="number" class="form-control" name="L${i}-ELC" form="jo_form" required>								
							</div>
							<hr style="	height: 10px; border: 0; box-shadow: 0 10px 10px -10px #8c8b8b inset;">
							
	
								<div>
									<br>
									<p class="font-bold">List Name: </p>
									<input type="text" name="L${i}-listname-0" class="form-control" form="jo_form" required>
									<br>
									<p class="font-bold">&#128204; Tags:</p>
									<input class="form-control" name="L${i}-tags-0" id="lot-${i}-tag-0" data-role="tagsinput" form="jo_form">
									<br>
								</div>
							</div><br>

							<div class="form-group">
								<label>Note</label>					
								<textarea placeholder="Some text" class="form-control" name="L${i}-note" form="jo_form"></textarea>							
							</div>							
							<button class="btn btn-primary btn-rounded pull-right" data-type="btn" data-tag="lot-${i}" type="button"><span class="bold">Add List&nbsp;&nbsp;</span><i class="ti-plus"></i></button><br>


							
						</div>
					</div>`;
					$(`#wf-stp-2`).append(tmp_lot);
					$(`#lot-${i}-tag-0`).tagsinput();
				}
				$('[name="rowCount"]').val(JSON.stringify(obj));

				$('[data-type="btn"]').on('click', function()
				{
					var num = $(this).attr("data-tag").split("-");
					obj[num[1]].tag++;
					var tg_num = obj[num[1]].tag;
					var list_tmp = `
					<div>
						<br><hr style="	height: 6px; background: url(../../assets/pics/hr-12.png) repeat-x 0 0;border: 0;">
						<p class="font-bold">List Name: </p>
						<input type="text" name="L${num[1]}-listname-${tg_num}" class="form-control" form="jo_form" required>
						<br>
						<p class="font-bold">&#128204; Tags:</p>
						<input class="form-control" name="L${num[1]}-tags-${tg_num}" id="lot-${num[1]}-tag-${tg_num}" data-role="tagsinput" form="jo_form">
						<br>
					<div>`;
					$(`#${num[0]}-${num[1]}`).append(list_tmp);
					$(`#lot-${num[1]}-tag-${tg_num}`).tagsinput();
					$('[name="rowCount"]').val(JSON.stringify(obj));
				});
			});
		});

    </script>



</body>

</html>
