<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

        /* This is for the validator modal admin level */
        $user = new Admin();
        $data = $user->userData(Session::get(Config::get('session/session_name')));
        $myArray = array('default');
        foreach($data as $element => $val){
            array_push($myArray, $val);
        }
    
        $commonFields =  ","." '". implode("', '", $myArray) ."'";

    if(Input::exists()){
        if(Token::check("passwordToken", Input::get('passwordToken'))){

            $validate = new Validate();

            $validation = $validate->check($_POST, array(
                    'new_username' => [
                        'required' => true,
                        'unique' => 'edr_account',
                        'unique' => 'prnl_account'
                    ],
                    'new_password' => [
                        'required' => true
                    ],
                    'password_again' => [
                        'matches' => 'new_password'
                    ]
            ));

            if($validation->passed()){
                $user = new User();
                $salt = Hash::salt(32);
                $ID = Session::get(Config::get('session/session_name'));

                try{
                    if($user->update('prnl_account', 'account_id', $ID, array(
                        'newAccount' => 0,
                        'username' => Input::get('new_username'),
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('new_password'), $salt)
                        
                        ))){
                        Session::delete("accounttype");
                        Session::put("accounttype", 0);
                        Session::flash('accountUpdated', 'Your Account has been succesfuly updated, Please Re-Login');
                        $user->logout();
                        Redirect::To('../../blyte/acc3ss');
                    }
                }catch(Exception $e){
                    die($e->getMessage());
                }
                
            }else{
                foreach($validation->errors() as $error){
                    echo $error,"<br>";
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

    <title>PrMO OPPTS | Empty Page</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

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

        <div id="page-wrapper" class="gray-bg" style="background-color:#c2c2ca">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-12">
                    <h2>Staff</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Breadcrumb</strong>
                        </li>
                    </ol>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content animated fadeInUpBig">
			
			
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
            <div class="col-lg-5">

                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Knob input <small>http://anthonyterrien.com/knob/</small></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#" class="dropdown-item">Config option 1</a>
                                </li>
                                <li><a href="#" class="dropdown-item">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <h3>
                            Knob - Dial
                        </h3>
                        <p>
                            Easily create knob input style.
                        </p>
                        <div class="text-center">
                            <small>Simple knob example</small><br/><br/>
                            <div class="m-r-md inline">
                            <input type="text" value="75" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" />
                            </div>
                            <div class="m-r-md inline">
                            <input type="text" value="25" class="dial m-r" data-fgColor="#1AB394" data-width="85" data-height="85"/>
                            </div>
                            <div class="m-r-md inline">
                            <input type="text" value="50" class="dial m-r" data-fgColor="#1AB394" data-width="85" data-height="85"/>
                            </div>
                        </div>
                        <div class="text-center">
                            <small>Dynamic knob example</small><br/><br/>
                            <div class="m-r-md inline">
                            <input type="text" value="75" class="dial m-r-sm" data-fgColor="#ED5565" data-width="85" data-height="85" data-cursor=true data-thickness=.3/>
                            </div>
                            <div class="m-r-md inline">
                            <input type="text" value="25" class="dial m-r" data-fgColor="#ED5565" data-width="85" data-height="85" data-step="1000" data-min="-15000" data-max="15000" data-displayPrevious=true/>
                            </div>
                            <div class="m-r-md inline">
                            <input type="text" value="60" class="dial m-r" data-fgColor="#ED5565" data-width="85" data-height="85" data-angleOffset=-125 data-angleArc=250 />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox ">
					<div class="ibox-title">
						<h5>Chosen select <small>http://harvesthq.github.io/chosen/</small></h5>
						<div class="ibox-tools">
							<a class="collapse-link">
								<i class="fa fa-chevron-up"></i>
							</a>
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="fa fa-wrench"></i>
							</a>
							<ul class="dropdown-menu dropdown-user">
								<li><a href="#" class="dropdown-item">Config option 1</a>
								</li>
								<li><a href="#" class="dropdown-item">Config option 2</a>
								</li>
							</ul>
							<a class="close-link">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<div class="ibox-content">
						<p>
							With chosen select uesr can fase chose from large in select input.
						</p>
						<div class="form-group">
							<label class="font-normal">Basic example</label>
							<div>
								<select data-placeholder="Choose a Country..." class="chosen-select"  tabindex="2">
									<option value="">Select</option>
									<option value="United States">United States</option>
									<option value="United Kingdom">United Kingdom</option>
									<option value="Afghanistan">Afghanistan</option>
									<option value="Aland Islands">Aland Islands</option>
									<option value="Albania">Albania</option>
									<option value="Algeria">Algeria</option>
									<option value="American Samoa">American Samoa</option>
									<option value="Andorra">Andorra</option>
									<option value="Angola">Angola</option>
									<option value="Anguilla">Anguilla</option>
									<option value="Antarctica">Antarctica</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="font-normal">Multi select</label>
							<div>
								<select data-placeholder="Choose a Country..." class="chosen-select" multiple style="width:350px;" tabindex="4">
									<option value="">Select</option>
									<option value="United States">United States</option>
									<option value="United Kingdom">United Kingdom</option>          
									<option value="Vanuatu">Vanuatu</option>
									<option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
									<option value="Viet Nam">Viet Nam</option>
									<option value="Virgin Islands, British">Virgin Islands, British</option>
									<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
									<option value="Wallis and Futuna">Wallis and Futuna</option>
									<option value="Western Sahara">Western Sahara</option>
									<option value="Yemen">Yemen</option>
									<option value="Zambia">Zambia</option>
									<option value="Zimbabwe">Zimbabwe</option>
								</select>
							</div>
						</div>

					</div>
                </div>

            </div>
                <div class="col-lg-7">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Range Slider <small>https://github.com/IonDen/ion.rangeSlider</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                Sliders
                            </h3>
                            <p>
                                Are perfect for range select option.
                            </p>
                            <div class="m-b-sm">
                                <small ><strong>Example of:</strong> Set diapason from 0 to 5000, Adding postfix "+" to max value, Set slider type to double, Dollar symbol as prefix, Enable grid  </small>
                            </div>
                            <div id="ionrange_1"></div>

                            <div class="m-b-sm m-t">
                                <small ><strong>Example of:</strong> Set diapason from 0 to 10, Set fractional step value: 0.1, Enable grid  </small>
                            </div>
                            <div id="ionrange_2"></div>

                            <div class="m-b-sm m-t">
                                <small ><strong>Example of:</strong> Set diapason from -50 to +50, Set FROM value to 0, Add degree symbol as postfix  </small>
                            </div>
                            <div id="ionrange_3"></div>

                            <div class="m-b-sm m-t">
                                <small ><strong>Example of:</strong>Change common slider numbers to custom (Month names). Using values array for that. Array can be any length, Slider will change min and max number automaticaly to fit values array length, Step parameter also will be changed to one, to allow to choose items in values array </small>
                            </div>
                            <div id="ionrange_4"></div>

                            <div>
                            <a class="btn btn-white float-right btn-sm" href="https://github.com/IonDen/ion.rangeSlider" target="_blank">Read about API</a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>TouchSpin <small>http://www.virtuosoft.eu/code/bootstrap-touchspin/</small></h5>
                        </div>
                        <div class="ibox-content">
                            <p>
                                A mobile and touch friendly input spinner component for Bootstrap 3. It supports the mousewheel and the up/down keys.
                            </p>

                            <div class="row">
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Basic example with empty value
                                    </p>

                                    <input class="touchspin1" type="text" value="" name="demo1">
                                </div>
                                <div class="col-md-4">
                                    <p class="font-bold">
                                        Example with postfix
                                    </p>
                                    <input class="touchspin2" type="text" value="55" name="demo2">
                                </div>
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Example with vertical button alignment:
                                    </p>
                                    <input class="touchspin3" type="text" value="" name="demo3">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Dual Listbox</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <p>
                                Bootstrap Dual Listbox is a responsive dual listbox widget optimized for Twitter Bootstrap. It works on all modern browsers and on touch devices.
                            </p>

                            <form id="form" action="#" class="wizard-big">
                                <select class="form-control dual_select" multiple>
                                    <option value="United States">United States</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Australia">Australia</option>
                                    <option selected value="Austria">Austria</option>
                                    <option selected value="Bahamas">Bahamas</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">

                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Select2 <small>https://select2.github.io/</small></h5>
                        </div>
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        Select2 is a jQuery based replacement for select boxes. It can take a regular select box and turn it into:
                                    </p>
                                    <select class="select2_demo_1 form-control">
                                        <option value="1">Option 1</option>
                                        <option value="2">Option 2</option>
                                        <option value="3">Option 3</option>
                                        <option value="4">Option 4</option>
                                        <option value="5">Option 5</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <p>
                                        A placeholder value can be defined and will be displayed until a selection is made.
                                    </p>
                                    <select class="select2_demo_3 form-control">
                                        <option></option>
                                        <option value="Bahamas">Bahamas</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Barbados">Barbados</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Belize">Belize</option>
                                        <option value="Benin">Benin</option>
                                    </select>

                                </div>
                                <div class="col-md-4">
                                    <p>
                                        Select2 also supports multi-value select boxes. The select below is declared with the multiple attribute.
                                    </p>
                                    <select class="select2_demo_2 form-control" multiple="multiple">
                                        <option value="Mayotte">Mayotte</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                        <option value="Moldova, Republic of">Moldova, Republic of</option>
                                        <option value="Monaco">Monaco</option>
                                        <option value="Mongolia">Mongolia</option>
                                        <option value="Montenegro">Montenegro</option>
                                        <option value="Montserrat">Montserrat</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Mozambique">Mozambique</option>
                                        <option value="Myanmar">Myanmar</option>
                                        <option value="Namibia">Namibia</option>
                                        <option value="Nauru">Nauru</option>
                                        <option value="Nepal">Nepal</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="New Caledonia">New Caledonia</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Nicaragua">Nicaragua</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Range Slider <small>http://refreshless.com/nouislider/</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                Range Slider
                            </h3>
                            <p>
                                Simple and clean range select slider.
                            </p>
                            <div class="row m-b-lg">
                                <div class="col-lg-12">
                                    <div id="drag-fixed" class="slider_red"></div>
                                </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p class="font-bold">Basic example</p>
                                    <div id="basic_slider"></div>
                                </div>
                                <div class="col-lg-6">
                                    <p class="font-bold">Range select example</p>
                                    <div id="range_slider"></div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-lg-5">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Data Picker <small>https://github.com/eternicode/bootstrap-datepicker</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                Data picker
                            </h3>
                            <p>
                                Simple and easy select a time for a text input using your mouse.
                            </p>

                            <div class="form-group" id="data_1">
                                <label class="font-normal">Simple data input format</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="03/04/2014">
                                </div>
                            </div>

                            <div class="form-group" id="data_2">
                                <label class="font-normal">One Year view</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="08/09/2014">
                                </div>
                            </div>

                            <div class="form-group" id="data_3">
                                <label class="font-normal">Decade view</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="10/11/2013">
                                </div>
                            </div>

                            <div class="form-group" id="data_4">
                                <label class="font-normal">Month select</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="07/01/2014">
                                </div>
                            </div>

                            <div class="form-group" id="data_5">
                                <label class="font-normal">Range select</label>
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control-sm form-control" name="start" value="05/14/2014"/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control-sm form-control" name="end" value="05/22/2014" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>ClockPicker <small>http://weareoutman.github.io/clockpicker/</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                ClockPicker
                            </h3>
                            <p>
                                A clock-style timepicker for Bootstrap (or jQuery).
                            </p>

                            <div class="input-group clockpicker" data-autoclose="true">
                                <input type="text" class="form-control" value="09:30" >
                                <span class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Date Range Picker <small>http://www.daterangepicker.com/</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                Date Range Picker
                            </h3>
                            <p>
                                A JavaScript widget for choosing date ranges.
                                Designed to work with the Bootstrap CSS framework.
                            </p>

                            <input class="form-control" type="text" name="daterange" value="01/01/2015 - 01/31/2015" />

                            <h4>All options example</h4>
                            <div id="reportrange" class="form-control">
                                <i class="fa fa-calendar"></i>
                                <span></span> <b class="caret"></b>
                            </div>
                        </div>
                    </div>

                    <div class="ibox ">
                        <div class="ibox-title  back-change">
                        <h5>Color picker <small>http://mjolnic.github.io/bootstrap-colorpicker/</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                Colorpicker
                            </h3>
                            <p>
                                Colorpicker plugin for the Twitter Bootstrap toolkit.
                            </p>

                            <h5>As normal input</h5>
                            <input type="text" class="form-control demo1" value="#5367ce" />
                            <h5>As a link</h5>
                            <a data-color="rgb(255, 255, 255)" id="demo_apidemo" class="btn btn-white btn-block colorpicker-element" href="#">Change background color</a>
                            <br/>
                        </div>
                    </div>

                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Bootstrap Tags Input</h5>
                        </div>
                        <div class="ibox-content">
                            <p>
                                jQuery plugin providing a Twitter Bootstrap user interface for managing tags
                            </p>


                            <p class="font-bold">
                                Basic example with few initial tags
                            </p>

                            <input class="tagsinput form-control" type="text" value="Amsterdam,Washington,Sydney,Beijing,Cairo"/>


                        </div>
                    </div>

                </div>
                <div class="col-lg-7">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Input Mask <small>http://jasny.github.io/bootstrap/</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                Input Mask
                            </h3>
                            <p>
                                Input masks allows a user to more easily enter data where you would like them to enter the data in a certain format.
                            </p>
                            <form class="m-t-md" action="#">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-sm-2 col-form-label">ISBN 1</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="999-99-999-9999-9" placeholder="">
                                        <span class="form-text">999-99-999-9999-9</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">ISBN 2</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="999 99 999 9999 9" placeholder="">
                                        <span class="form-text">999 99 999 9999 9</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">ISBN 3</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="999/99/999/9999/9" placeholder="">
                                        <span class="form-text">999/99/999/9999/9</span>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">IPV4</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="999.999.999.9999" placeholder="">
                                        <span class="form-text">192.168.100.200</span>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tax ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="99-9999999" placeholder="">
                                        <span class="form-text">99-9999999</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="(999) 999-9999" placeholder="">
                                        <span class="form-text">(999) 999-9999</span>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Currency</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="$ 999,999,999.99" placeholder="">
                                        <span class="form-text">$ 999,999,999.99</span>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-mask="99/99/9999" placeholder="">
                                        <span class="form-text">dd/mm/yyyy</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Switcher <small>http://abpetkov.github.io/switchery/</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h3>
                                Switcher
                            </h3>
                            <p>
                                Is iOS 7 style switches for your checkboxes.
                            </p>
                            <input type="checkbox" class="js-switch" checked />
                            <input type="checkbox" class="js-switch_2" checked />
                            <br/>
                            <br/>
                            <input type="checkbox" class="js-switch_3"  />
                            <input type="checkbox" class="js-switch_4" checked />
                        </div>
                    </div>

                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Custom switch </h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <h4>
                                Custom switch
                            </h4>
                            <p>
                                Pure CSS3 On/Off flipswitches with animated transitions.
                            </p>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked class="onoffswitch-checkbox" id="example1">
                                    <label class="onoffswitch-label" for="example1">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" class="onoffswitch-checkbox" id="example2">
                                    <label class="onoffswitch-label" for="example2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked disabled class="onoffswitch-checkbox" id="example3">
                                    <label class="onoffswitch-label" for="example3">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" disabled class="onoffswitch-checkbox" id="example4">
                                    <label class="onoffswitch-label" for="example4">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    </div>
                    </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title  back-change">
                        <h5>Image cropper <small>http://fengyuanchen.github.io/cropper/</small></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#" class="dropdown-item">Config option 1</a>
                                </li>
                                <li><a href="#" class="dropdown-item">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <p>
                            A simple jQuery image cropping plugin.
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="image-crop">
                                    <img src="img/p3.jpg">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Preview image</h4>
                                <div class="img-preview img-preview-sm"></div>
                                <h4>Comon method</h4>
                                <p>
                                    You can upload new image to crop container and easy download new cropped image.
                                </p>
                                <div class="btn-group">
                                    <label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" accept="image/*" name="file" id="inputImage" class="hide">
                                        Upload new image
                                    </label>
                                    <label title="Donload image" id="download" class="btn btn-primary">Download</label>
                                </div>
                                <h4>Other method</h4>
                                <p>
                                    You may set cropper options with <code>$(image}).cropper(options)</code>
                                </p>
                                <div class="btn-group">
                                    <button class="btn btn-white" id="zoomIn" type="button">Zoom In</button>
                                    <button class="btn btn-white" id="zoomOut" type="button">Zoom Out</button>
                                    <button class="btn btn-white" id="rotateLeft" type="button">Rotate Left</button>
                                    <button class="btn btn-white" id="rotateRight" type="button">Rotate Right</button>
                                    <button class="btn btn-warning" id="setDrag" type="button">New crop</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title  back-change">
                            <h5>Awesome bootstrap checkbox</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <h3>
                                            Basic
                                        </h3>
                                        <p>
                                            Supports bootstrap brand colors: <code>.checkbox-primary</code>, <code>.checkbox-info</code> etc.
                                        </p>
                                        <div class="checkbox">
                                            <input id="checkbox1" type="checkbox">
                                            <label for="checkbox1">
                                                Default
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-primary">
                                            <input id="checkbox2" type="checkbox" checked="">
                                            <label for="checkbox2">
                                                Primary
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-success">
                                            <input id="checkbox3" type="checkbox">
                                            <label for="checkbox3">
                                                Success
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-info">
                                            <input id="checkbox4" type="checkbox">
                                            <label for="checkbox4">
                                                Info
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-warning">
                                            <input id="checkbox5" type="checkbox" checked="">
                                            <label for="checkbox5">
                                                Warning
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-danger">
                                            <input id="checkbox6" type="checkbox" checked="">
                                            <label for="checkbox6">
                                                Check me out
                                            </label>
                                        </div>
                                        <p>Checkboxes without label text</p>
                                        <div class="checkbox">
                                            <input type="checkbox" id="singleCheckbox1" value="option1" aria-label="Single checkbox One">
                                            <label></label>
                                        </div>
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="singleCheckbox2" value="option2" checked="" aria-label="Single checkbox Two">
                                            <label></label>
                                        </div>
                                        <p>Inline checkboxes</p>
                                        <div class="checkbox">
                                            <input type="checkbox" id="inlineCheckbox1" value="option1">
                                            <label for="inlineCheckbox1"> Inline One </label>
                                        </div>
                                        <div class="checkbox checkbox-success">
                                            <input type="checkbox" id="inlineCheckbox2" value="option1" checked="">
                                            <label for="inlineCheckbox2"> Inline Two </label>
                                        </div>
                                        <div class="checkbox">
                                            <input type="checkbox" id="inlineCheckbox3" value="option1">
                                            <label for="inlineCheckbox3"> Inline Three </label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <h3>
                                            Circled
                                        </h3>
                                        <p>
                                            <code>.checkbox-circle</code> for roundness.
                                        </p>
                                        <div class="checkbox checkbox-circle">
                                            <input id="checkbox7" type="checkbox">
                                            <label for="checkbox7">
                                                Simply Rounded
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox-info checkbox-circle">
                                            <input id="checkbox8" type="checkbox" checked="">
                                            <label for="checkbox8">
                                                Me too
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <h3>
                                            Radio
                                        </h3>
                                        <p>
                                            Supports bootstrap brand colors: <code>.radio-primary</code>, <code>.radio-danger</code> etc.
                                        </p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="radio">
                                                    <input type="radio" name="radio1" id="radio1" value="option1" checked="">
                                                    <label for="radio1">
                                                        Small
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" name="radio1" id="radio2" value="option2">
                                                    <label for="radio2">
                                                        Big
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="radio radio-danger">
                                                    <input type="radio" name="radio2" id="radio3" value="option1">
                                                    <label for="radio3">
                                                        Next
                                                    </label>
                                                </div>
                                                <div class="radio radio-danger">
                                                    <input type="radio" name="radio2" id="radio4" value="option2" checked="">
                                                    <label for="radio4">
                                                        One
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <p>Radios without label text</p>
                                        <div class="radio">
                                            <input type="radio" id="singleRadio1" value="option1" name="radioSingle1" aria-label="Single radio One">
                                            <label></label>
                                        </div>
                                        <div class="radio radio-success">
                                            <input type="radio" id="singleRadio2" value="option2" name="radioSingle1" checked="" aria-label="Single radio Two">
                                            <label></label>
                                        </div>
                                        <p>Inline radios</p>
                                        <div class="radio radio-info">
                                            <input type="radio" id="inlineRadio1" value="option1" name="radioInline" checked="">
                                            <label for="inlineRadio1"> Inline One </label>
                                        </div>
                                        <div class="radio">
                                            <input type="radio" id="inlineRadio2" value="option2" name="radioInline">
                                            <label for="inlineRadio2"> Inline Two </label>
                                        </div>
                                    </fieldset>
                                </div>
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

    <?php include '../../includes/parts/admin_scripts.php'; ?>
<!-- Password meter -->
	<script src="../../assets/js/plugins/pwstrength/pwstrength-bootstrap.min.js"></script>
	<script src="../../assets/js/plugins/pwstrength/zxcvbn.js"></script>
	
	<script>	
		$(document).ready(function(){
           // Example 4 password meter
            var options4 = {};
            options4.ui = {
                container: "#pwd-container",
                viewports: {
                    progress: ".pwstrength_viewport_progress4",
                    verdict: ".pwstrength_viewport_verdict4"
                }
            };

            options4.common = {

                zxcvbn: true,
				zxcvbnTerms: ['asdasdasd', 'shogun', 'bushido', 'daisho', 'seppuku' <?php 
					if(isset($commonFields)) echo $commonFields;
					else{
						echo  $commonFields = '';
					}
				?>],
                userInputs: ['#year', '#new_username']
            };
            $('.example4').pwstrength(options4);

			
			//password valide
			var password = document.getElementById("new_password")
			  , confirm_password = document.getElementById("password_again");

			function validatePassword(){
			  if(password.value != confirm_password.value) {
				confirm_password.setCustomValidity("Passwords Don't Match");
			  } else {
				confirm_password.setCustomValidity('');
			  }
			}

			password.onchange = validatePassword;
			confirm_password.onkeyup = validatePassword;						
		})
	
	</script>

</body>

</html>
