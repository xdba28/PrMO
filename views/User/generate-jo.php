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
														<input id="lot" name="lot" type="number" min="0" class="form-control">
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

    <!-- Mainly scripts -->
    <script src="../../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../../assets/js/popper.min.js"></script>
    <script src="../../assets/js/bootstrap.js"></script>
    <script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../../assets/js/inspinia.js"></script>
    <script src="../../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Steps -->
    <script src="../../assets/js/plugins/steps/jquery.steps.min.js"></script>

    <!-- Jquery Validate -->
	<script src="../../assets/js/plugins/validate/jquery.validate.min.js"></script>
	
	<script src="../../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>


    <script>
        $(document).ready(function(){
            $("#wizard").steps();
            $("#form").steps({
                bodyTag: "fieldset",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Always allow going backward even if the current step contains invalid fields!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }

                    // Forbid suppressing "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age").val()) < 18)
                    {
                        return false;
                    }

                    var form = $(this);

                    // Clean up if user went backward before
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $(".body:eq(" + newIndex + ") label.error", form).remove();
                        $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                    }

                    // Disable validation on fields that are disabled or hidden.
                    form.validate().settings.ignore = ":disabled,:hidden";

                    // Start validation; Prevent going forward if false
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Suppress (skip) "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age").val()) >= 18)
                    {
                        $(this).steps("next");
                    }

                    // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        $(this).steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    var form = $(this);

                    // Disable validation on fields that are disabled.
                    // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                    form.validate().settings.ignore = ":disabled";

                    // Start validation; Prevent form submission if false
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    var form = $(this);

                    // Submit form input
                    form.submit();
                }
            }).validate({
                        errorPlacement: function (error, element)
                        {
                            element.before(error);
                        },
                        rules: {
                            confirm: {
                                equalTo: "#password"
                            }
                        }
                    });

			
			document.getElementById('lot').addEventListener('change', function()
			{
				
				var wf_lot = document.getElementById('wf-stp-2');
				var lots = this.value;
				wf_lot.innerHTML = '';
				for(let i = 1 ; i <= lots ; i++)
				{
					var tmp_lot = `
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">
								<h5>Lot Number ${i}</h5>
							</div>
							<div class="ibox-content">
								<div id="lot.${i}">
									<p class="font-bold">Header Name: </p>
									<input type="text" name="lot${i}[list-name][]" class="form-control">
									<br>
									<p class="font-bold">Tags:</p>
									<input class="form-control" name="lot${i}[list][]" id="tag-${i}" data-role="tagsinput">
									<br>
								</div>
							<button type="button" onclick="addList('lot.${i}')">Click</button>
							</div>
							
						</div>
					</div>`;
					wf_lot.innerHTML += tmp_lot;
					$('input[name="lot'+i+'[list][]"]').tagsinput();
				}

			})

	   });
	   

	   function addList(lot)
	   {
		   var num = lot.split(".");
		   var list_tmp = `
		   <div id="lot.${num[1]}">
				<p class="font-bold">Header Name: </p>
				<input type="text" name="lot${num[1]}[list-name][]" class="form-control">
				<br>
				<p class="font-bold">Tags:</p>
				<input class="form-control" name="lot${num[1]}[list][]" data-role="tagsinput">
				<br>
			</div>`;

		   document.getElementById(lot).innerHTML += list_tmp;
		   $('input[name="lot'+num[1]+'[list][]"]').tagsinput();
	   }
    </script>


</body>

</html>
