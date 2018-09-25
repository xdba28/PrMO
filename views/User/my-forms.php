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

    <title>PrMO OPPTS | My Forms</title>

	<?php include_once'../../includes/parts/user_styles.php'; ?>

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
                    <h2>Forms Created</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>PR/JO Created</strong>
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
			<div class="row">
				<div class="col-lg-12">
					<div class="wrapper wrapper-content animated fadeInUp">
						<div class="row">
							<div class="col-lg-12 animated fadeInRight">
								<div class="alert alert-danger">
									<a class="alert-link">Note that requests that are already passed the Technical Member's evaluation cannot be modified by the requestor or the endusers. Only the Procurement Aids has the privilage to modify or update your requests after passing the evaluation. It is adviced to head to PrMO and state your concern with regards updating your request/s concern.</a>
								</div>
							</div>
						</div>
						<div class="row">
						
							<?php
							
							if(!isset($_GET['q'])){
								
							$requests = $user->myRequests(Session::get(Config::get('session/session_name')), true);
							
							foreach($requests as $request){
								
								$time = strtotime($request->date_created);
								
							?>
								<div class="col-lg-4">
									<div class="panel panel-info rem">
										<div class="panel-heading" style="color:black">
											<i class="fa fa-info-circle side" style="color:black"></i> <?php echo $request->form_ref_no?>
										</div>
										<div class="panel-body">
											<!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p> -->
											<h3><?php echo $request->title?></h3>
											<hr style="background-color:#23c6c8">
											
											<div class="">
												<p class="inline">Number of Lots:</p>
												<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $request->number_of_lots;?></p>
												<br>
												<p class="inline">Date Created:</p>
												<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo date("F j, Y / m:i:s A", $time);?></p>															
											</div>	
											<div class="panel-footer">
												<a href="?q=<?php echo $request->form_ref_no;?>"><button class="btn btn-warning btn-outline pull-right btn-rounded" style="margin-bottom:10px; margin-right:-15px">Details</button></a>
											</div>											
										</div>
									</div>
								</div>		
							<?php
								}
							}else{
								
								$refno = $_GET['q'];
							?>
							
							<div class="col-lg-12">
								<div class="ibox ">
									<div class="ibox-title">
										<h5>Border Table </h5>
										<div class="ibox-tools">
											<a class="collapse-link">
												<i class="fa fa-chevron-up"></i>
											</a>
										</div>
									</div>
									<div class="ibox-content">

										<table class="table table-bordered table-hover">
											<thead>
											<tr>
												<th>#</th>
												<th>First Name</th>
												<th>Last Name</th>
												<th>Username</th>
											</tr>
											</thead>
											<tbody>
											<tr>
												<td>1</td>
												<td>Mark</td>
												<td>Otto</td>
												<td>@mdo</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Jacob</td>
												<td>Thornton</td>
												<td>@fat</td>
											</tr>
											<tr>
												<td>3</td>
												<td>Larry</td>
												<td>the Bird</td>
												<td>@twitter</td>
											</tr>
											</tbody>
										</table>

									</div>
								</div>
							</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
			</div><br><br><br><br><br>

			<!-- Main Content End -->
			<button class="back-to-top" type="button"></button>			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once'../../includes/parts/user_scripts.php'; ?>

</body>

</html>
