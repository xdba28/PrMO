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
                    <h2>Purchase Request Form</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Request Forms</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Purchase Request</strong>
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
					<!-- Content here-->

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
													<label class="font-normal"></label>
													<div>
														<select data-placeholder="Choose Category" class="chosen-select" multiple style="width:350px;" tabindex="4" name="category">															
															<option value="1">Common Office Supplies</option>
															<option value="2">Paper Materials & Products</option>          
															<option value="3">Hardware Supplies</option>
															<option value="4">Sporting Supplies</option>
															<option value="5">Common Janitorial/Cleaning Supplies</option>
															<option value="6">IT Supplies</option>
															<option value="7">Laboratory Supplies</option>
															<option value="8">Computer Supplies</option>
														</select>
													</div>
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
											<div class="col-lg-12">	 
												<div class="ibox">
													<div class="ibox-title">
														<div class="project-alert alert-warning">
															<h5>Below is the Item List for Lot 1 - Common Office Supplies</h5>
														</div>
													
														<div class="add-project">
															<button class="btn btn-success btn-rounded btn-outline" href="#">Add Listing <i class="ti ti-plus" style="font-weight:900"></i></button>
														</div>
													</div>
													<div class="ibox-content">



														<table class="table table-bordered">
															<thead>
															<tr>
																<th class="center">Stock No.</th>
																<th class="center">Unit</th>
																<th class="center">Item Description</th>
																<th class="center">Quantity</th>
																<th class="center">Unit Cost</th>
																<th class="center">Total Cost</th>
															</tr>
															</thead>
															<tbody>
															<tr>
																<td></td>
																<td class="center">box</td>
																<td>Ballpen, black 50s/box</td>
																<td class="center">1</td>
																<td class="right">450.00</td>
																<td class="right">450.00</td>																
															</tr>
															<tr>
																<td></td>
																<td class="center">box</td>
																<td>Ballpen, blue 50s/box</td>
																<td class="center">4</td>
																<td class="right">450.00</td>
																<td class="right">1800.00</td>	
															</tr>
															<tr>
																<td></td>
																<td class="center">box</td>
																<td>Ballpen, red 50s/box</td>
																<td class="center">1</td>
																<td class="right">450.00</td>
																<td class="right">450.00</td>
															</tr>
															</tbody>
														</table>


													
													</div>
												</div>
												
												<div class="ibox">
													<div class="ibox-title">
														<div class="project-alert alert-warning">
															<h5>Below is the Item List for Lot 2 - Common Janitorial Supplies</h5>
														</div>
													
														<div class="add-project">
															<button class="btn btn-success btn-rounded btn-outline" href="#">Add Listing <i class="ti ti-plus" style="font-weight:900"></i></button>
														</div>
													</div>
													<div class="ibox-content">


										
														<table class="table table-bordered">
															<thead>
															<tr>
																<th class="center">Stock No.</th>
																<th class="center">Unit</th>
																<th class="center">Item Description</th>
																<th class="center">Quantity</th>
																<th class="center">Unit Cost</th>
																<th class="center">Total Cost</th>
															</tr>
															</thead>
															<tbody>
															<tr>
																<td></td>
																<td class="center">box</td>
																<td>Ballpen, black 50s/box</td>
																<td class="center">1</td>
																<td class="right">450.00</td>
																<td class="right">450.00</td>																
															</tr>
															<tr>
																<td></td>
																<td class="center">box</td>
																<td>Ballpen, blue 50s/box</td>
																<td class="center">4</td>
																<td class="right">450.00</td>
																<td class="right">1800.00</td>	
															</tr>
															<tr>
																<td></td>
																<td class="center">box</td>
																<td>Ballpen, red 50s/box</td>
																<td class="center">1</td>
																<td class="right">450.00</td>
																<td class="right">450.00</td>
															</tr>
															</tbody>
														</table>


													</div>
												</div>					
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

	<?php include_once '../../includes/parts/user_scripts.php'; ?>


</body>

</html>
