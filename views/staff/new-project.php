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

	<script>
		var OBJ = 
		<?php
			$array = [
				'ID' => [
					'fname' => 'Denver',
					'lname' => 'Arancillo'
				]
			];
			echo json_encode($array);
		?>;
		console.log(OBJ.ID.fname);
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

				  <div class="row">
								<div class="col-sm-8">
									<div class="ibox">
										<div class="ibox-content">
											<span class="text-muted small float-right">Last Refresh: <i class="fa fa-clock"></i> <?php echo date('l F j, Y g:i:s A'); ?></span>
											<h2>Unregistered Projects</h2>
											<p>
												You can search a Purchase request or Job order by its title or end user's name. But it is adviced to search through its Reference number indicated in the printed hard copy of the actual Purchase request or Job order form.
											</p>
											<div class="input-group">
												<input type="text" placeholder="Search client " class="input form-control" id="filter">
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
																<tbody>

                                                                <tr>
                                                                    <th>Reference No.</th>
                                                                    <th>End User</th>
                                                                    <th>Date Created</th>
                                                                    <th>Action</th>
                                                                </tr>

                                                                <?php

                                                                    $user =  new Staff();
                                                                    $requests = $user->pr_jo_requests();

                                                                    foreach($requests as $request){
                                                                        $fullname = $user->fullnameOf($request->requested_by);
                                                                        $date_created = strtotime($request->date_created);
                                                                

                                                                ?>
																<tr>																	
																	<td><a href="#<?php echo $request->form_ref_no;?>" class="client-link"><?php echo $request->form_ref_no;?></a></td>
																	<td><?php echo $fullname;?></td>
																	
																	<td><i class="fa fa-clock"> </i> <?php echo date('F j, Y g:i:s A', $date_created);?></td>
																	<td><button class="ladda-button btn-rounded btn btn-warning" value="" data-style="zoom-in">Receive</button></td>
																</tr>

                                                                <?php
                                                                  }
                                                                ?>

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
												
												<?php
														$user =  new Staff();
														$requests = $user->pr_jo_requests();
														$c=0;
														foreach($requests as $request){
															
															//sample counter for popover
															$c++;
															
															$fullname = $user->fullnameOf($request->requested_by);
															$date_created = strtotime($request->date_created);
												?>
												
												<div id="<?php echo $request->form_ref_no; ?>" class="tab-pane">
													<div class="row m-b-lg">
														<div class="col-lg-12">
															<strong>
																<h3>Request Title</h3>
															</strong>

															<p>
																<h2><?php echo $request->title; ?></h2>
															</p>
															<strong>Request Summary</strong>

															<ul class="list-group clear-list">
																<li class="list-group-item fist-item">
																	<span class="float-right"> 09:00 pm </span>
																	Something
																</li>
																<li class="list-group-item">
																	<span class="float-right"> 10:16 am </span>
																	Something
																</li>
																<li class="list-group-item">
																	<span class="float-right"> 10:16 am </span>
																	Something
																</li><br>																
															</ul>
															<button type="button" class="btn btn-warning btn-sm btn-block" id="popOver<?php echo $c;?>" data-trigger="hover" title="Instructions" data-placement="left" data-content="Click on this to download a soft copy of the original PR / JO created in the system to compare it to the actual submission of the Enduser."><i class="ti-split-h"></i> Compare to Original</button>
															<button type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-download"></i> Register Now</button>															
														</div>
													</div>
												
														<div class="full-height-scroll">

															<strong>Last activity</strong>

															<ul class="list-group clear-list">
																<li class="list-group-item fist-item">
																	<span class="float-right"> 09:00 pm </span>
																	place here the latest activity
																</li>
																<li class="list-group-item">
																	<span class="float-right"> <?php echo date('M j, Y g:i a', $date_created);?> </span>
																	Form Created and Downloaded by the Enduser
																</li>
															</ul>

															<hr/>
														</div><br><br><br><br><br><br>
													
												</div>
												
												<?php
														}
												?>												
												
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

    <?php include_once '../../includes/parts/admin_scripts.php'; ?>
	
	<script>

	$(document).ready(function(){

		$(document.body).on("click",".client-link",function(e){
			e.preventDefault()
			$(".selected .tab-pane").removeClass('active');
			$($(this).attr('href')).addClass("active");
		});

	});


	</script>
	<script>

		$(document).ready(function (){

			// Bind normal buttons
			Ladda.bind( '.ladda-button',{ timeout: 2000 });

			// Bind progress buttons and simulate loading progress
			Ladda.bind( '.progress-demo .ladda-button',{
				callback: function( instance ){
					var progress = 0;
					var interval = setInterval( function(){
						progress = Math.min( progress + Math.random() * 0.1, 1 );
						instance.setProgress( progress );

						if( progress === 1 ){
							instance.stop();
							clearInterval( interval );
						}
					}, 200 );
				}
			});


			var l = $( '.ladda-button-demo' ).ladda();

			l.click(function(){
				// Start loading
				l.ladda( 'start' );
					

					//i cant do php stuffs here like die();

				// Timeout example
				// Do something in backend and then stop ladda
				setTimeout(function(){
					l.ladda('stop');
				},12000)


			});

		});

	</script>

</body>

</html>
