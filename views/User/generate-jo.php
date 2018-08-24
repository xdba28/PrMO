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
											<div class="row">
												<div class="col-lg-8">
												<br><br>
													<div class="form-group">
														<label>Project title *</label>
														<input id="title" name="title" type="text" class="form-control">
													</div>
													<div class="form-group">
														<label>Overall Cost *</label>
														<input id="estimated_cost" name="estimated_cost" type="text" class="form-control">
													</div>
													<div class="form-group">
														<label>Number of Lots</label>
														<input id="lot" name="lot" type="number" min="1" class="form-control">
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
											<div class="row" id="wf-stp-2" style="overflow-y: auto; height: 450px">
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
