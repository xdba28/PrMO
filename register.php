<?php
    require_once 'core/outer-init.php';

    $guest = new Guest();

    $units = $guest->AllUnits();


    if (Input::exists()){
        if(Token::check(Input::get('token'))){
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
                    'unique_edr_email'   => 'enduser'
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
                    'required' => true
                ],
                'password' => [
                    'min' => '2',
                    'required' => true
                ],
                'confirm' => [
                    'required' => true,
                    'matches' => 'password'
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

                    $guest->request('account_requests', array(

                        'fname' => Input::get('name'),
                        'midle_name' => Input::get('midlename'),
                        'last_name' => Input::get('surname'),
                        'ext_name' => Input::get('extname'),
                        'employee_id' => Input::get('emp_id'),
						'contact' => Input::get('contact'),
                        'email' => Input::get('email'),
                        'designation' => Input::get('unit'),
                        'username' => Input::get('userName'),
                        'userpassword' => Input::get('password'),
						'submitted' => date('Y-m-d H:i:s')

                    ));
                    
                    Session::flash('request_success', 'Your requests has been successfuly submited.');
                    Redirect::To('index');

                }catch(Exception $e){
                    die($e->getMessage());
                }

            }else{        
              foreach($validation->errors() as $error){
                  echo $error,'<br>';
              }
        
            }               
        }
            
    }
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item</a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item</a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item</a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item</a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
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
                            <div class="alert alert-info">
                                Your account requests will be validated by the Procurement Management office to assure the eligibility of the requestor, this process only requires a minimum amount of time.
                                We will send your Login information through your provided email address as soon as it is validated by our incharge personnel.
                            </div>


                            <form id="form" class="wizard-big" method="POST">
							
                                <h1>Profile</h1>
                                <fieldset>
                                    <h2>Profile Information</h2>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group" id="popOver" data-trigger="hover" title="Instruction" data-placement="right" data-content="You should also include second name if any.">
                                                <label>First name *</label>
                                                <input id="name" name="name" type="text" class="form-control required">
                                            </div>
                                            <div class="form-group" id="popOver1" data-trigger="hover" title="Instruction" data-placement="right" data-content="Middle name should be complete and not just initials.">
                                                <label>Middle name *</label>
                                                <input id="midlename" name="midlename" type="text" class="form-control required">
                                            </div>											
                                            <div class="form-group">
                                                <label>Last name *</label>
                                                <input id="surname" name="surname" type="text" class="form-control required">
                                            </div>
                                            <div class="form-group"  id="popOver2" data-trigger="hover" title="Instruction" data-placement="right" data-content="Eg. Jr, Sr, II, III">
                                                <label>Extension name </label>
                                                <input id="extname" name="extname" type="text" class="form-control">
                                            </div>										
											
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Designation office / Unit *</label>
                                                <select class="form-control m-b required" name="unit">
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
                                            <div class="form-group">
                                                <label>Phone No. *</label>
                                                <input id="contact" name="contact" type="text" data-mask="9999 999 9999" class="form-control required">
                                            </div>										
                                            <div class="form-group">
                                                <label>Email *</label>
                                                <input id="email" name="email" type="text" class="form-control required email">
                                            </div>    
                                            <div class="form-group">
                                                <label>Employee No. *</label>
                                                <input id="emp_id" name="emp_id" type="text" class="form-control required">
                                            </div>											
                                        </div>
                                        
                                    </div>

                                </fieldset>
                                <h1>Account</h1>
                                <fieldset>
                                    <h2>Account Information</h2>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label>Username *</label>
                                                <input id="userName" name="userName" type="text" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Password *</label>
                                                <input id="password" name="password" type="password" class="form-control required">
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password *</label>
                                                <input id="confirm" name="confirm" type="password" class="form-control required">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="text-center">
                                                <div style="margin-top: 20px">
                                                    <i class="fa fa-sign-in" style="font-size: 180px;color: #e5e5e5 "></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>									
                                </fieldset>

                                <h1>Terms and Condition</h1>
                                <fieldset>
                                    <h2>Terms and Conditions</h2>
                                    <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">I agree with the Terms and Conditions.</label>
                                    <input type="hidden" name="token" value="<?php echo Token::generate();?>">       
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
		});

		$(document).ready(function () {
			$('#popOver1').popover();
		});

		$(document).ready(function () {
			$('#popOver2').popover();
		});
	</script>


</body>

</html>
