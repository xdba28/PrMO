<?php 

    require_once('../../core/init.php');

    $user = new User(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
    }
   

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrMO OPPTS | Empty Page</title>


	<?php include_once '../../includes/parts/user_styles.php'; ?>

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
                       <a href="Dashboard.php" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>				
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs-container">
							<ul class="nav nav-tabs">
								<li><a class="nav-link active" data-toggle="tab" href="#tab-1">Project &nbsp&nbsp<i class="ti-folder" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-2">Particulars &nbsp&nbsp<i class="ti-pencil-alt" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-3">Signatories &nbsp&nbsp<i class="ti-user" style="font-size:18px"></i></a></li>
							</ul>
							<div class="tab-content">
								
								<div id="tab-1" class="tab-pane active">
									<div class="panel-body">
									   <h2>Project Information</h2>

										<p>Specify the required fields to generate the Job Order Form that suits your need.</p>
										<div class="row">
											<div class="col-lg-7">
												<div class="form-group">
													<label>Project title *</label>
													<input id="title" name="title" type="text" class="form-control">
												</div>
												<div class="form-group">
													<label>Overall Estimated Cost *</label>
													<input id="estimated_cost" name="estimated_cost" type="text" class="form-control">
												</div>
												<div class="form-group">
													<label>Number of Lots *</label>
													<input id="lots" name="lots" type="number" min=1 class="form-control">
												</div>
											</div>
											<div class="col-lg-3">
												<div class="text-center">
													<div style="margin-left: 100px">
														<i class="ti-layout-tab" style="font-size: 180px;color: #FFD700 "></i>
													</div>
												</div>
											</div>	
										</div>

									</div>	
								</div>
								<div id="tab-2" class="tab-pane">
									<div class="panel-body">
										<h2>Particulars Setting</h2>

										<p>Some shitty explaination what the hell is going on</p>

										<div class="row">
											<div class="col-lg-12"><!-- edit the size of this class Den 6 7 or 8 bahala ka -->
												<button class="btn btn-warning btn-outline btn-block" >Dito mo ilalagay par yung pamatay nating logic</button> <!--Erase this buttton, place the repetitive -->
											</div>
		
										</div>
									</div>
								</div>
								<div id="tab-3" class="tab-pane">
									<div class="panel-body">
										   <h2>Project Signatories</h2>

											<p>Specify all signatories to finalized this form.</p>
											
											<div class="row">
												<div class="col-lg-7">
													<div class="form-group">
														<label>End User *</label>
														<input id="enduser" name="enduser" type="text" value="Nico Ativo" class="form-control" disabled>
													</div>
													<div class="form-group">
														<label>Noted By *</label>
														<input id="noted" name="noted" type="text"  class="form-control">
													</div>
													<div class="form-group">
														<label>Verified By *</label>
														<input id="verified" name="verified" type="text"  class="form-control">
													</div>
													<div class="form-group">
														<label>Aproved By *</label>
														<input id="approved" name="approved" type="text"  class="form-control">
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
													<button class="btn btn-primary btn-outline pull-right">Finish</button>
													<button class="btn btn-danger btn-outline pull-right" style="margin-right:5px">Cancel</button>													
												</div>
											</div>											
									</div>
								</div>							
							</div>
						</div>
					</div>
				
				</div><br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br>
			</div>
			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include '../../includes/parts/user_scripts.php'; ?>

    <script>
        $(document).ready(function(){

			document.getElementById('lot').addEventListener('change', function()
			{
				var lots = this.value;
				$('#wf-stp-2').html('');
				for(let i = 1 ; i <= lots ; i++)
				{
					var tmp_lot = `
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">
								<h5>Lot Number ${i}</h5>
							</div>
							<div class="ibox-content" id="lot.${i}">
								<div>
									<p class="font-bold">Header Name: </p>
									<input type="text" name="lot${i}[list-name][]" class="form-control">
									<br>
									<p class="font-bold">Tags:</p>
									<input class="form-control" name="lot${i}[list][]" id="lot.${i}-tag-${i}" data-role="tagsinput">
									<br>
								</div>
							</div>
							<button type="button" onclick="addList('lot.${i}')">Click</button>
							
						</div>
					</div>`;
					$(`#wf-stp-2`).append(tmp_lot);
					$(`[data-role="tagsinput"]:last`).tagsinput();
				}

			})

	   });
	   

		function addList(lot)
		{
			var num = lot.split(".");
			var list_tmp = `
			<div>
				<p class="font-bold">Header Name: </p>
				<input type="text" name="lot${num[1]}[list-name][]" class="form-control">
				<br>
				<p class="font-bold">Tags:</p>
				<input class="form-control" name="lot${num[1]}[list][]" data-role="tagsinput">
				<br>
			<div>`;
			$(`#${lot}`).append(list_tmp);
			// document.getElementById(lot).innerHTML += list_tmp;
			// $('[data-role="tagsinput"]:last').tagsinput();
		}
    </script>


</body>

</html>
