<?php
	require_once 'core/outer-init.php';

    $guest = new Guest();

    $units = $guest->AllUnits();

    if (Input::exists()){
		
        if(Token::check('termsToken', Input::get('termsToken'))){
            //allow to submit the form
            $validate = new Validate();
            $validation = $validate->check($_POST, array(

                'name' => [
                    'min' => '2',
                    'required' => true
                ],
                'midlename' => [
                    'min' => '2',
                    'required' => true
                ],
                'surname' => [
                    'min' => '2',
                    'required' => true
                ],
                'email' => [
                    'required' => true,
					'unique_edr_email'   => 'enduser',
					'unique_prnl_email'   => 'personnel'
                ],          
                'unit' => [
                    'required' => true
                ],
                'emp_id' => [
                    'required' => true,
                    'unique_edr_id'   => 'enduser'
                ],				
                'userName' => [
                    'min' => '2',
                    'max' => '50',
					'unique'   => 'edr_account',
					'unique'   => 'prnl_account',
                    'required' => true
                ],
                'acceptTerms' => [                    
                    'required' => true
                ],        
				'contact' => [                    
					'required' => true
				]				
			));
			
            if($validation->passed()){
                $salt = Hash::salt(32);
                try{

					$guest->startTrans();

                    $guest->request('account_requests', array(

                        'fname' => Input::get('name'),
                        'midle_name' => Input::get('midlename'),
                        'last_name' => Input::get('surname'),
                        'ext_name' => Input::get('extname'),
                        'employee_id' => Input::get('emp_id'),
						'contact' => "+63".Input::get('contact'),
                        'email' => Input::get('email'),
                        'designation' => Input::get('unit'),
                        'username' => Input::get('userName'),
						'submitted' => date('Y-m-d H:i:s'),
						'specific_office' => Input::get('specific_office'),
						'jobtitle' => Input::get('jobtitle')

					));
					
					$guest->request('notifications', array(
						'recipient' => "163-141",
						'message' => "A new account request has been made.",
						'datecreated' => Date::translate('test', 'now'),
						'seen' => 0,
						'href' => "account-request"
					));

					$guest->endTrans();
				
					Session::flash('request_success', 'Your requests has been successfuly submited.');
					
					notif(json_encode(array(
						'receiver' => "163-141",
						'message' => "A new account request has been made.",
						'date' => Date::translate(Date::translate('test', 'now'), '1'),
						'href' => "account-request"
					)), true);

					// sms

					Redirect::To('index');
					exit();

                }catch(Exception $e){
                    die($e->getMessage());
                }

            }else{
            //   foreach($validation->errors() as $error){
            //       echo $error,'<br>';
            //   }
			  
			//   die("error");    
			echo "<pre>",print_r($validation->errors()),"</pre>";    
            }               
        }
            
    }
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="assets/pics/flaticons/men.png" type="image/x-icon">

    <title>PrMO OPPTS | Account Request</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="assets/css/plugins/steps/jquery.steps.css" rel="stylesheet">
	

	    
	
	<link href="assets/my_style.css" rel="stylesheet">

</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">
            <!--<div class="navbar-header">-->
                <!--<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">-->
                    <!--<i class="fa fa-reorder"></i>-->
                <!--</button>-->

                <a href="#" class="navbar-brand">OPPTS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-reorder"></i>
                </button>

            <!--</div>-->
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav mr-auto">
                    <li class="active">
                        <a aria-expanded="false" role="button" href="index.php"> Back to Login page</a>
                    </li>
          

                </ul>

            </div>
        </nav>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
                     <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Wizard with Validation</h5>
                        </div>
                        <div class="ibox-content">
                            <h2>
                                 Account Request Form
                            </h2>
                            <p>
                                Fill Up the fields with correct and accurate data.
                            </p>
                           
                                
                         							
                            <div class="alert alert-success">
                                Your account requests will be validated by the Procurement Management office to assure the eligibility of the requestor.
                                The system will send your Login information through your provided phone number as soon as it is validated by our incharge personnel.
                            </div>
							
                            <form id="form" action="" class="wizard-big" method="POST" enctype="multipart/form-data">
                                <h1>Profile</h1>
                                <fieldset>
                                    <h2>Profile Information</h2><code>Fields labeled with asterisk(*) are required.</code>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group" id="popOver" data-trigger="active" title="Instruction" data-placement="top" data-content="You should also include second name if any.">
                                                <label>First name *</label>
                                                <input id="name" name="name" type="text" class="form-control" required>
                                            </div>
                                            <div class="form-group" id="popOver1" data-trigger="active" title="Instruction" data-placement="top" data-content="Middle name should be complete and not just initials.">
                                                <label>Middle name *</label>
                                                <input id="midlename" name="midlename" type="text" class="form-control" required>
                                            </div>											
                                            <div class="form-group">
                                                <label>Last name *</label>
                                                <input id="surname" name="surname" type="text" class="form-control" required>
                                            </div>
                                            <div class="form-group"  id="popOver2" data-trigger="active" title="Instruction" data-placement="top" data-content="Eg. Jr, Sr, II, III. Leave it blank if not applicable.">
                                                <label>Extension name </label>
                                                <input id="extname" name="extname" type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Job title *</label>
                                                <input id="jobtitle" name="jobtitle" type="text" class="form-control" required>
                                            </div>												
											
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Designation Office / Unit *</label>
                                                <select class="form-control m-b required" name="unit">
													<option>Select Office/College</option>
                                                    <?php
                                                        foreach($units as $unit){
                                                            $name = $unit->office_name;
                                                            if($unit->acronym == ""){
                                                                $acronym = "No Acronym";
                                                            }else{
                                                                $acronym = $unit->acronym;
                                                            }

                                                            echo "<option value ='{$unit->ID}'>{$name} - {$acronym}</option>";
                                                        }
                                                    ?>
												</select>
                                            </div>			
											<div class="form-group"  id="popOver3" data-trigger="hover" title="Note" data-placement="top" data-content="The specified Office / Department will be the transmitting location whenever we have document to be delivered with regards to you.">
                                                <label>Specific Office *</label>
                                                <input id="specific_office" name="specific_office" type="text" d class="form-control" required>
                                            </div>									
                                            <div class="form-group">
                                                <label>Phone No. *</label>
                                                
												<div class="input-group">
													<span class="input-group-addon">+63</span><input id="contact" name="contact" type="text" data-mask="9999999999" class="form-control" required>
												</div>												
                                            </div>										
                                            <div class="form-group">
                                                <label>Email *</label>
                                                <input id="email" name="email" type="email" class="form-control" required>
                                            </div>    
                                            <div class="form-group">
                                                <label>Employee No. *</label>
                                                <input id="emp_id" name="emp_id" type="text" class="form-control" required>
                                            </div>											
                                        </div>
                                        
                                    </div>

                                </fieldset>
                                <h1>Account</h1>
                                <fieldset>
                                    <h2>Account Information</h2>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Username *</label>
                                                <input id="userName" name="userName" type="text" class="form-control required">
                                            </div>
                                            <!-- <div class="form-group">
                                                <label>Password *</label>
                                                <input id="password" name="password" type="password" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password *</label>
                                                <input id="confirm" name="confirm" type="password" class="form-control required">
                                            </div> -->
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="text-center">
                                                <div style="margin-top: 20px">
                                                    <i class="fa fa-sign-in" style="font-size: 180px;color: #e5e5e5 "></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>									
                                </fieldset>

                                <h1>Terms and Condition</h1>
                                <fieldset style="">
                                    <h2>Terms and Conditions </h2>Last updated: (January 2019)
									<p>
                                    	Please read and understand the terms and conditions of use carefully by the system before accessing and using the service provided by the platform. By accessing the Online Procurement Project Tracking and Monitoring System for BU Procurement Office platform and using all its services and features you duly accept and agree to be bound by the terms and conditions and our <b>Privacy Policy</b> of this agreement. If you wish not to agree to any of this terms and policies then you may not use this system's services and featues of or platform. The terms "we" and "us"  refers to the developers of the system.
									</p><br>
									
                                    <h3 style="color:#17a2b8;">Content</h3>
									<p>
										Our Service allows you to create Purchase Request,Job Order and track your projects and it also messages/emails you to the update of your current request. You are responsible for the history of your projects, to the ongoing and on-hold projects. You are also responsible in managing your request prior to submission in editing and deleting a request. You are also responsible in updating and maintaning your profiles, in managing like editing and uploading pictures of your profile. We provide an OTP or One Time password for your first log in for security and verification purpose only.     
									</p><br>

									<h3 style="color:#17a2b8;">Privacy</h3>
									<p>
										<!-- Privacy is the most important thing that we promote. We will not disclose any personal information to any third party.  -->
										Privacy is the most important thing that we promote and to provide all end users from the standard users to admins of the system, we must process information about you. The types of information we collect depend on how you use the system.
									</p><br>

										<ul style="padding-left:40px">
											
											<li>
												<h4>Basic Personal Information</h4>
												<p>We only use your basic information for sending live notifications, SMS notifications and for future updates only. But most importantly we do not disclose any personal information to any third party.</p>

												<ul id="mylist" style="padding-left:40px;">
													<li>Name</li>
													<li>Phone number</li>
													<li>Email Address</li>
													<li>Designation and specific office</li>
												</ul>
											</li>
											<li>
												<h4>Requests Information</h4>
												<ul id="mylist" style="padding-left:40px;">
													<li>Request specifics</li>
													<li>Purposes</li>
													<li>Signatories names</li>
												</ul>
											</li>
										</ul>

									<h3 style="color:#17a2b8;">Scope and Limitation</h3>
									<p>
										As our duty to develop the Online Procurement Project Tracking System (OPPTS) started, we came up to various specific set of features and enhancement in/on the current existing practices conducted in the Procurement Management Office. These features and enhancement aims to lessen the average time on finishing projects handled in the Procument Management Office and to help the personnels to optimize and maximize their working practices to produce more tasks accomplishment but lessen the weight our personnels carrying in their shoulders at the same time.
									</p><br>

										<ul style="padding-left:40px">
											<li>
												<h4>Scope</h4>
												<ul id="mylist" style="padding-left:40px;">
													<li>Project tracking where the end user can track the specifics of their existing project.</li>
													<li>Live dashboard notications and SMS notifications.</li>
													<li>Viewing of project logs to trace the movement of the project documents.</li>
													<li>Guided creation of purchase request and job order.</li>
													<li>Viewing, edit, and deletion of forms created (purchase request and job order).</li>
													<li>Viewing of revision requests related to created forms.</li>
												</ul>
											</li>
											<li>
												<h4>Limitations</h4>
												<ul id="mylist" style="padding-left:40px;">
													<li>Features and enhancement only covers pre-procurement and procurement phase only.</li>
													<li>Transactions and updates after procurement phase are not registered in the system.</li>
													<li>	SMS notifications are only available for important updates like release of BAC Resolution and Issues encountered during technical member evaluation.</li>
													<li>Notifications are limited to live dashbord and SMS notifications only.</li>
												</ul>
											</li>
										</ul>

                                    <h3 style="color:#17a2b8;">Changes</h3>
									<p>
										We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material we will try to provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.
									</p>
                                        

                                    <!-- <h3>Contact Us</h3> -->
                                        
                                        <!-- If you have any questions about these Terms, please contact us. -->

									<br>
                                    <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">I agree with the Terms and Conditions.</label>
                                    <input type="hidden" name="termsToken" value="<?php echo Token::generate('termsToken');?>">  
                                </fieldset>

                                <h1>Finish</h1>
                                <fieldset>
                                    <center><h2 style="margin-top:220px">You can expect for your Login information with a maximum of 1 Working day.</h2></center>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer">
            <?php include_once 'includes/parts/footer.php'; ?>
        </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="assets/js/jquery-3.1.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
	<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/sweetalert2/dist/sweetalert2.all.min.js"></script>	

	<!-- Input Mask-->
    <script src="assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="assets/js/inspinia.js"></script>
    <script src="assets/js/plugins/pace/pace.min.js"></script>

    <!-- Steps -->
    <script src="assets/js/plugins/steps/jquery.steps.min.js"></script>

    <!-- Jquery Validate -->
    <script src="assets/js/plugins/validate/jquery.validate.min.js"></script>
	
    <!-- Password meter -->
    <script src="assets/js/plugins/pwstrength/pwstrength-bootstrap.min.js"></script>
	<script src="assets/js/plugins/pwstrength/zxcvbn.js"></script>

	<script src="includes/js/custom.js"></script>
	<script src="assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>



    <script>
	
        $(document).ready(function(){




            // Example 4
            var options4 = {};
            options4.ui = {
                container: "#pwd-container4",
                viewports: {
                    progress: ".pwstrength_viewport_progress4",
                    verdict: ".pwstrength_viewport_verdict4"
                }
            };
            options4.common = {
                zxcvbn: true,
                zxcvbnTerms: ['samurai', 'shogun', 'bushido', 'daisho', 'seppuku'],
                userInputs: ['#year', '#familyname']
            };
            $('.example4').pwstrength(options4);


		


        })

    </script>


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
       });
    </script>

    <script>
		$(document).ready(function () {
			$('#popOver').popover();
			$('#popOver1').popover();
			$('#popOver2').popover();
			$('#popOver3').popover();

			$('[name="unit"]').on('change', function(){
				SendDoSomething("POST", "views/Super-admin/xhr-get-offices.php", {
					id: this.value
				}, {
					do: function(r){
						$('#specific_office').typeahead('destroy');
						$("#specific_office").typeahead({
							source: r.offices
						});
					}
				});
			});

			<?php
			
				// FATAL ERROR NOTIFICATIONS
				if(Session::exists("FATAL_ERROR")){
					
					echo '
						audio.play();
						toastr.options = {
						"closeButton": true,
						"debug": true,
						"progressBar": false,
						"preventDuplicates": false,
						"positionClass": "toast-top-full-width",
						"onclick": null,
						"showDuration": "400",
						"hideDuration": "1000",
						"timeOut": "60000",
						"extendedTimeOut": "60000",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
						}
						toastr.error("'.Session::flash("FATAL_ERROR").'", "Fatal Error");
					
					';				
					
				}
				// VALIDATION ERRORS

				if(isset($validation)){
					if($validation->errors()){
						$default_time_out = 20000;
						foreach ($validation->errors() as $error_type => $error_message) {
								
								echo '
									audio.play();
									toastr.options = {
									"closeButton": true,
									"debug": true,
									"progressBar": true,
									"preventDuplicates": false,
									"positionClass": "toast-top-full-width",
									"onclick": null,
									"showDuration": "400",
									"hideDuration": "1000",
									"timeOut": "'.$default_time_out.'",
									"extendedTimeOut": "10000",
									"showEasing": "swing",
									"hideEasing": "linear",
									"showMethod": "fadeIn",
									"hideMethod": "fadeOut"
									}
									toastr.warning("'.$error_message.'", "'.$error_type.'");
								
								';
								$default_time_out += 5000;			
						}
					}

				}

				
				// SUCCESS NOTIFICATIONS

				if(isset($success_notifs)){
						
						foreach ($success_notifs as $notif) {
								
								echo '
									audio.play();
									toastr.options = {
										"progressBar": true,
										"preventDuplicates": false,
										"showDuration": "400",
										"hideDuration": "1000",
										"timeOut": "6000",
										"extendedTimeOut": "1000",
										"showEasing": "swing",
										"hideEasing": "linear",
										"showMethod": "fadeIn",
										"hideMethod": "fadeOut"
									}
									toastr.info("'.$notif.'", "Success");
								
								';
						}			

				}			
			
			?>				
		});

	</script>


</body>

</html>
