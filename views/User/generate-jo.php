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
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
				<div class="wrapper wrapper-content animated fadeInRight">

					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<h5>Wizard with Validation</h5>
								</div>
								<div class="ibox-content">
									<h2>
										Job Order Specification Requirements
									</h2>
									<p>
										Specify the required fields for your need to generate the Job Order Form that suits your need.
									</p>

									<form id="form" action="#" class="wizard-big">
										<h1>Project</h1>
										<fieldset>
											<h2>Project Information</h2>
											<div class="row" style="height: 520px; overflow-y : auto;">
												<div class="col-lg-8">
												<br><br>
													<div class="form-group">
														<label>Project title *</label>
														<input id="title" name="title" type="text" class="form-control required">
													</div>
													<div class="form-group">
														<label>Estimated Cost *</label>
														<input id="estimated_cost" name="estimated_cost" type="text" class="form-control required">
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
											<h2>Profile Information</h2>
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label>First name *</label>
														<input id="name" name="name" type="text" class="form-control required">
													</div>
													<div class="form-group">
														<label>Last name *</label>
														<input id="surname" name="surname" type="text" class="form-control required">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label>Email *</label>
														<input id="email" name="email" type="text" class="form-control required email">
													</div>
													<div class="form-group">
														<label>Address *</label>
														<input id="address" name="address" type="text" class="form-control">
													</div>
												</div>
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
			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include_once'../../includes/parts/user_scripts.php'; ?>


</body>

</html>
