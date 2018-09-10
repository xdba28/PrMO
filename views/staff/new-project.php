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
			$user = new Staff();
			echo $user->allPRJO_req_detail();
		?>;
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
															<!-- <button type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-download"></i> Register Now</button>															 -->
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
		OBJ.forEach(function(el, index){
			var data_tmp = `
			<tr>																	
				<td><a href="#${el.id}" class="client-link">${el.id}</a></td>
				<td>${el.req_by}</td>
				<td><i class="fa fa-clock"></i> ${el.date_created}</td>
				<td><button class="ladda-button btn-rounded btn btn-warning" data-style="zoom-in">Receive</button></td>
			</tr>`;
			$('#nwprj-tbl-data').append(data_tmp);
		});

		$(document.body).on("click",".client-link",function(e){
			var ID = $(this).attr('href').split("#");
			var PROJ = OBJ.find(function(el){
				return el.id === ID[1];
			});

			if(typeof PROJ !== "undefined")
			{
				$('[data="side-panel"]').attr("id", PROJ.id);
				$('[data="side-panel"] h2').html(PROJ.title);

				$('#lot-data').html('');
				PROJ.lot_details.forEach(function(el, index){
					if(PROJ.type === "PR")
					{
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
					}
					else if(PROJ.type === "JO")
					{
						var lot_temp = `
						<li class="list-group-item fist-item">
							<span class="float-right"></span>
							${el.l_title}
						</li>`;	
					}
					$('#lot-data').append(lot_temp);
				});
				$('span[date="created"]').html(PROJ.date_created);

				e.preventDefault();
				$(".selected .tab-pane").removeClass('active');
				$($(this).attr('href')).addClass("active");
			}
			else
			{
				swal({
					title: "An Error Occured",
					text: "Please reload the Page."
				});
			}
		});

        var l = $( '.ladda-button' ).ladda();

		l.click(function(){
			l.ladda( 'start' );
			if(window.XMLHttpRequest){
				xhr = new XMLHttpRequest();
			}else{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xhr.open('POST', 'ajax/receive-proj.php', true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send();
			xhr.onreadystatechange = function()
			{
				if(this.readyState == 4 && this.status == 200)
				{
					alert(this.responseText);
				}
				else
				{
					
				}
			};

		});

	});

	</script>

</body>

</html>
