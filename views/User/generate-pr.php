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
	
	<?php include_once '../../includes/parts/user_scripts.php'; ?>

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
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
					<!-- Content here-->

				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<h5>Wizard with Validation</h5>
								</div>
								<div class="ibox-content">
									<h2>
										Purchase Order Specification Requirements
									</h2>
									<p>
										Specify the required fields for your need to generate the Purchase Order Form that suits your need.
									</p>

									<form id="form" action="#" class="wizard-big">
										<h1>Project</h1>
										<fieldset>
											<h2>Project Information</h2>
											<div class="row">
												<div class="col-lg-8">
												<br><br>
													<div class="form-group">
														<label>Project title *</label>
														<input id="title" name="title" type="text" class="form-control">
													</div>
													<div class="form-group">
														<label>Overall Estimated Cost *</label>
														<input id="estimated_cost" name="estimated_cost" type="text" class="form-control">
													</div>
													<div class="form-group">
														<label>Number of Lots</label>
														<input id="items" name="items" type="number" min="1" class="form-control">
													</div>

												</div>
												<div class="col-lg-4">
													<div class="text-center">
														<div style="margin-top: 20px">
															<i class="ti-layout-tab" style="font-size: 180px;color: #FFD700 "></i>
														</div>
													</div>
												</div>
											</div>

										</fieldset>
										<h1>Particulars</h1>
										<fieldset>
											<h2>Project Information</h2>
											<div class="row" id="wf-stp-2">
												<!-- step 2 -->



											</div>
										</fieldset>

										<h1>Warning</h1>
										<fieldset>
											<div class="text-center" style="margin-top: 120px">
												<h2>You did it Man :-)</h2>
											</div>
										</fieldset>

										<h1>Finish</h1>
										<fieldset>
											<h2>Terms and Conditions</h2>
											<input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">I agree with the Terms and Conditions.</label>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Phone</th>
						<th>Something</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>Administration</td>
                        <td>(171) 555-2222</td>
						<td>something</td>
                        <td>
							<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                            <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                            <a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Peter Parker</td>
                        <td>Customer Service</td>
                        <td>(313) 555-5735</td>
						<td>something</td>
                        <td>
							<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                            <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                            <a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Fran Wilson</td>
                        <td>Human Resources</td>
                        <td>(503) 555-9931</td>
						<td>something</td>
                        <td>
							<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                            <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                            <a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>      
                </tbody>
            </table>

					
			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	


</body>

</html>
