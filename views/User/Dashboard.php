<?php 

    require_once('../../core/init.php');

    $user = new User(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../index');
        die();
    }
    
        /* This is for the validator modal standard user */
        $user = new User();
        $data = $user->userData(Session::get(Config::get('session/session_name')));
        $myArray = array('default');
        foreach($data as $element => $val){
            array_push($myArray, $val);
        }
    
        $commonFields =  "'". implode("', '", $myArray) ."'";

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
                    if($user->update('edr_account', 'account_id', $ID, array(
                        'newAccount' => 0,
                        'username' => Input::get('new_username'),
                        'salt' => $salt,
                        'userpassword' => Hash::make(Input::get('new_password'), $salt)
                        
                        ))){
                        Session::delete("accounttype");
                        Session::put("accounttype", 0);
                        Session::flash('accountUpdated', 'Your Account has been succesfuly updated, Please Re-Login');
                        $user->logout();
                        Redirect::To('../../index');
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

    <title>PrMO OPPTS | Dashboard</title>

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
                    <h2>End User Dashboard</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Dashboard</strong>
                        </li>
                    </ol>
                </div>
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
				<div class="row">
					<?php
						
						//echo "<pre>",print_r($_SESSION),"</pre>";
					
						if(Session::exists('greet')){
							//Session::flash('greet');
							$now =  date('H');
							if($now < 12){
								$checked = "";
								$secondGreeting = "Good Evening";
							}else if(($now == 12) || ($now < 18)){
								$checked = "checked";
								$secondGreeting = "Good Afternoon";
							}else{
								$checked = "checked";
								$secondGreeting = "Good Evening";
							}
							
							
							
					?>
					
						<div class="col-lg-4">
							<input id="greetingsSwitch" class="switch" <?php echo $checked;?> type="checkbox">
							<div class="switch-day-night">
								<div class="top" >
									<div class="sun-moon"></div>
									<div class="cloud a"><span></span></div>
									<div class="cloud b"><span></span></div>
									<div class="cloud c"><span></span></div>
								</div>
								<div class="bottom">
									<div class="text" data-day="Goodmorning <?php echo $currentUser[2];?>" data-night="<?php echo $secondGreeting;?> <?php echo $currentUser[2];?>"></div>
								
								</div>
							</div>	
						</div>

						<div class="col-lg-8">
							<div class="ibox-content forum-container">

								<div class="forum-title">
									<div class="float-right forum-desc">
										
										<div id="clockbox" style="color:black; font-size:18px"></div>
									</div>
									<h3>General Information</h3>
								</div>

								<div class="forum-item">
									<div class="row">
										<div class="col-md-8">
											<div class="forum-icon">
												<i class="fas fa-file" style="color:#089edb"></i>
											</div>
											<a href="#" class="forum-item-title">Projects</a>
											<div class="forum-sub-title">Shows basic status records of projects related to you.</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
											   1
											</span>
											<div>
												<small>Total</small>
											</div>
										</div>
										
										<div class="col-md-1 forum-info">
											<span class="views-number">
												1
											</span>
											<div>
												<small>Ongoing</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												0
											</span>
											<div>
												<small>Finished</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
											   0
											</span>
											<div>
												<small>Failed</small>
											</div>
										</div>									
									</div>
								</div>
								
								<div class="forum-item">
									<div class="row">
										<div class="col-md-9">
											<div class="forum-icon">
												<i class="fas fa-star" style="color:#b1e831"></i>
											</div>
											<a href="#" class="forum-item-title">Important Updates</a>
											<div class="forum-sub-title">Includes important updates which needs your attention and action like Release of BAC Resolution,  Notice of Award, Pre-procurement issues to be resolved, etc...</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												0
											</span>
											<div>
												<small>BAC Reso</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												0
											</span>
											<div>
												<small>NOA</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												0
											</span>
											<div>
												<small>Evaluation Issue/s</small>
											</div>
										</div>
									</div>
								</div>
								
								<div class="forum-item">
									<div class="row">
										<div class="col-md-9">
											<div class="forum-icon">
												<i class="fas fa-folder-open" style="color:#ffc107"></i>
											</div>
											<a href="#" class="forum-item-title">Request Forms</a>
											<div class="forum-sub-title">Summary of Forms created by the system, Unreceived forms, and Pending revision request to be confirmed by procurement aids.</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												1
											</span>
											<div>
												<small>Created</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
												0
											</span>
											<div>
												<small>Unreceived</small>
											</div>
										</div>
										<div class="col-md-1 forum-info">
											<span class="views-number">
											   0
											</span>
											<div>
												<small>Pending Revision</small>
											</div>
										</div>
									</div>
								</div>

							</div>
							<br>
						</div>
						
						
						<!--user guidlines-->
						<div class="container" style="padding: 50px">
							<div class="text-center">
								<h1 class="animated fadeInDown text-navy ">User Guidline</h1>
							</div>
							<hr role="tournament1" style="margin-bottom: 10px;">
							<div class="container" style="margin:0px 0px 60px 47.49%;">
							  <div class="chevron"></div>
							  <div class="chevron"></div>
							  <div class="chevron"></div>
							</div>							
							
							
						<br><br>
						  <div class="row">
							<div class="col">
							  <div class="main-timeline">
								<div class="timeline">
								  <a href="#" class="timeline-content">
									<span class="timeline-year">Step 1</span>
									<div class="timeline-icon">
									  <i class="fas fa-file-invoice" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Request Form Creation</h3>
									  <p class="description">
										PrMO Online Procurement Project Tracking System Innovates the creation of Requests forms from manual and non-uniform kind of creation of Purchase Request and Job Order Forms. You can now create request forms guided by the system to minimize erroneous practice of creating request forms.										
									  </p><br>
									  
									  <p class="description">
										To create your first request form click on the <code>Request Forms</code> from the side navigation and pick from which requests form you wish to create.
									  </p>									  
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="#" class="timeline-content">
									<span class="timeline-year">Step 2</span>
									<div class="timeline-icon">
									  <i class="far fa-handshake" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Printing and Submission</h3>
									  <p class="description">
										After filling up the required field for your request and submitting the form, the system automatically generate the request form based on what your inputs are, by then you will be directed to a page where you can edit, delete, and review all your created forms with its status.										
									  </p><br>
									  
									  <p class="description">
										In the actual submission side, we still follow the tradional practice where you personally submits the request form/s together with the other requirement but as an improvement feature in the system, an incharge personnel receives your request form both physically and digitally then validates your actual submission from your inputed request data in the system.
									  </p>									  
									  
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="#" class="timeline-content">
									<span class="timeline-year">Step 3</span>
									<div class="timeline-icon">
									  <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Monitoring</h3>
									  <p class="description">
										Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="#" class="timeline-content">
									<span class="timeline-year">Step 4</span>
									<div class="timeline-icon">
									  <i class="fas fa-users" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Evaluation</h3>
									  <p class="description">
										Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="#" class="timeline-content">
									<span class="timeline-year">2017</span>
									<div class="timeline-icon">
									  <i class="fa fa-globe" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Angular</h3>
									  <p class="description">
										Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="#" class="timeline-content">
									<span class="timeline-year">2017</span>
									<div class="timeline-icon">
									  <i class="fa fa-apple" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Laravel</h3>
									  <p class="description">
										Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
									  </p>
									</div>
								  </a>
								</div>
								<div class="timeline">
								  <a href="#" class="timeline-content">
									<span class="timeline-year">2017</span>
									<div class="timeline-icon">
									  <i class="fa fa-edit" aria-hidden="true"></i>
									</div>
									<div class="content">
									  <h3 class="title">Creapure</h3>
									  <p class="description">
										Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
									  </p>
									</div>
								  </a>
								</div>
							  </div>
							</div>
						  </div>
						</div>    						
					
					<?php
						
						}else{
					?>
					
						<h1>This is after greetings</h1>
							
					<?php
						}
					?>

				
				</div>
				
				
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>
	<script>
		function toggleGreetings(){
			setTimeout(function(){
				$('#greetingsSwitch').trigger('click');
			}, 300);			
		}
		
	
	</script>

	<script>
		$(window).scroll(function() {
		  $('.wpb_animate_when_almost_visible').each(function() {
			  //console.log(this+$(this).position().top);
			if ($(window).scrollTop()+$(window).height() >= $(this).position().top && $(window).scrollTop() < $(this).position().top + $(this).height()) {
			  //console.log(this+$(this).position().top);
			  //if ($(this).hasClass('wpb_start_animation')){
			  if (!$(this).hasClass('wpb_start_animation')){
			  $(this).addClass('wpb_start_animation');
			  }
			}
			else if ($(this).hasClass('wpb_start_animation')) {
			  //console.log(this+$(this).position().top);
			  //if ($(this).hasClass('wpb_start_animation')){
			  $(this).removeClass('wpb_start_animation');
			  //}
			}
		  });
		});		
	</script>

	<script type="text/javascript">
		var tday=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
		var tmonth=["January","February","March","April","May","June","July","August","September","October","November","December"];

		function GetClock(){
		var d=new Date();
		var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate();
		var nhour=d.getHours(),nmin=d.getMinutes(),ap;
		if(nhour==0){ap=" AM";nhour=12;}
		else if(nhour<12){ap=" AM";}
		else if(nhour==12){ap=" PM";}
		else if(nhour>12){ap=" PM";nhour-=12;}

		if(nmin<=9) nmin="0"+nmin;

		var clocktext=""+tday[nday]+", "+tmonth[nmonth]+" "+ndate+" "+nhour+":"+nmin+ap+"";
		document.getElementById('clockbox').innerHTML=clocktext;
		}

		GetClock();
		setInterval(GetClock,1000);
	</script>	
	<?php include_once'../../includes/parts/user_scripts.php'; ?>

</body>

</html>
