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

    <title>PrMO OPPTS | Director</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/director_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg" style="background-color:#e7e7ec">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Director's Dashboard</h2>
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
            <div class="wrapper wrapper-content"><h1>Procurement Entries</h1>

				<?php
										
					$yearEntries = $user->dashboard_procurement_entries('year');
					$monthEntries = $user->dashboard_procurement_entries('month');
					$weekEntries = $user->dashboard_procurement_entries('week');
					$dayEntries = $user->dashboard_procurement_entries('day');
					

				?>

				<div class="row col-lg-12 ">
						<div class="col-lg-3 animated fadeInUp">
							<div class="ibox widget">
								<div class="ibox-title">
									<span class="label label-warning float-right pull-right">2018</span>
									<h5>Yearly Entries</h5>
								</div>
								<div class="ibox-content" style="min-height:115px; max-height:900px">
									<h1 class="no-margins"><?php echo $yearEntries[0];?></h1>
									 <small>Total Entries</small>
										<?php
											if($yearEntries[1] == "No Comparison Data available from previous year."){
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $yearEntries[1];?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($yearEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-info";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $yearEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last year's number of entries.</small></div><br><br>

										<?php
											}
										?>
										
								   
								</div>
							</div>
						</div>
						<div class="col-lg-3 animated fadeInDown">
							<div class="ibox">
								<div class="ibox-title">
									<span class="label label-warning float-right pull-right"><?php echo Date::translate('test','month');?></span>
									<h5>Monthly</h5>
								</div>
								<div class="ibox-content" style="height:115px">
									<h1 class="no-margins"><?php echo $monthEntries[0];?></h1>
									<small>Total Entries</small>
										<?php
											if($monthEntries[1] == "No Comparison Data available from previous month."){
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $monthEntries[1];?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($monthEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-success";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $monthEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last month's number of entries.</small></div><br><br>

										<?php
											}
										?>
									
								</div>
							</div>
						</div>
						<div class="col-lg-3 animated fadeInUp">
							<div class="ibox ">
								<div class="ibox-title">
									<span class="label label-warning float-right pull-right"><?php echo Date::translate('test','weekno');?> <i class="fas fa-info"></i></span>
									<h5>Weekly</h5>
								</div>
								<div class="ibox-content" style="height:115px">
									<h1 class="no-margins"><?php echo $weekEntries[0];?></h1>
									<small>Total Entries</small>
										<?php
											if($weekEntries[1] == "No Comparison Data available from previous week."){
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $weekEntries[1];?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($weekEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-success";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $weekEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last week's number of entries.</small></div><br><br>

										<?php
											}
										?>						
								</div>
							</div>
						</div>
						<div class="col-lg-3 animated fadeInDown">
							<div class="ibox ">
								<div class="ibox-title">
									<span class="label label-warning float-right pull-right"><?php echo Date::translate('test','today');?></span>
									<h5>Today's Entries</h5>
								</div>
								<div class="ibox-content" style="height:115px">
									<h1 class="no-margins"><?php echo $dayEntries[0];?></h1>
									<small>Total Entries</small>
										<?php
											if($dayEntries[1] == "No Comparison Data available from previous day."){
												$dayEntries = "No Comparison Data available from yesterday."
										?>	
											
										<div class="stat-percent font-bold text-danger"><small><?php echo $dayEntries;?></small></div><br><br>

										<?php
											}else{

												
												$pre = substr($dayEntries[1], 0, 1);
												if($pre == "-"){
													$Icon = "fas fa-level-down-alt";
													$color = "text-danger";
												}else{
													$Icon = "fas fa-level-up-alt";
													$color = "text-info";
													
												}
										?>

										<div class="stat-percent font-bold <?php echo $color;?>"><?php echo $dayEntries[1];?>% <i class="<?php echo $Icon;?>"></i><small> compared to last yesterday's number of entries.</small></div><br><br>

										<?php
											}
										?>								
								</div>
							</div>
						</div>
				</div>
				<div class="row col-lg-12">
					<div class="col-lg-7 animated fadeInUp">
						<?php
							$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
							$yql_query = 'select * from weather.forecast where woeid = 2346660 ';
							$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "%20and%20u%20%3D%20'c'&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
							
							// Make call with cURL
							$session = curl_init($yql_query_url);
							curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
							$json = curl_exec($session);
							// Convert JSON to PHP object
							 $WeatherphpObj =  json_decode($json, true);
							 //echo "<pre>", print_r($WeatherphpObj), "</pre>";
							
						?>
						
						<div class="ibox ">
							<div class="ibox-title">
								<h5>Todays Weather Status</h5>
								<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
								</div>
							</div>
							<div class="ibox-content">
										<div id="top">
										  <div class="location"><?php echo $WeatherphpObj['query']['results']['channel']['location']['city'],", ",$WeatherphpObj['query']['results']['channel']['location']['country'];?></div>
										  <div class="time"><?php echo $WeatherphpObj['query']['results']['channel']['lastBuildDate'];?></div>
										  <div class="status"><?php echo $WeatherphpObj['query']['results']['channel']['item']['condition']['text'];?></div>
										</div>

										<div id="left-information">
										  <img src="../../assets/weather/png/<?php echo weatherConditionIcon($WeatherphpObj['query']['results']['channel']['item']['condition']['code']); ?>.png" alt="status" class="thumbnail" />
										  <div class="temperature"><?php echo $WeatherphpObj['query']['results']['channel']['item']['condition']['temp'];?></div>
										  <div class="unit">°C</div>
										</div>

										<div id="right-information">
										  <span>Humidity: <?php echo $WeatherphpObj['query']['results']['channel']['atmosphere']['humidity'];?>%</span><br/>
										  <span>Pressure: <?php echo $WeatherphpObj['query']['results']['channel']['atmosphere']['pressure'], " ",$WeatherphpObj['query']['results']['channel']['units']['pressure'];?></span><br/>
										  <span>Wind speed: <?php echo $WeatherphpObj['query']['results']['channel']['wind']['speed'], " ",$WeatherphpObj['query']['results']['channel']['units']['speed'];?></span>
										</div>

										<div id="forecast">
											  <ul>
													<?php
														for($x = 0; $x<7; $x++){
															$conditionCode = $WeatherphpObj['query']['results']['channel']['item']['forecast'][$x]['code'];

															$displayIcon = weatherConditionIcon($conditionCode);
													?>
													<li>
													<div><?php echo $WeatherphpObj['query']['results']['channel']['item']['forecast'][$x]['day'];?></div>
													<img src="../../assets/weather/png/<?php echo $displayIcon; ?>.png" alt="icon" height="32" width="32"/>
													<b><?php echo $WeatherphpObj['query']['results']['channel']['item']['forecast'][$x]['high'];?>°</b> <?php echo $WeatherphpObj['query']['results']['channel']['item']['forecast'][$x]['low'];?>°
													</li>
													<?php
														}
													?>
											  </ul>
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
				zxcvbnTerms: ['asdasdasd', 'shogun', 'bushido', 'daisho', 'seppuku', <?php 
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

	<?php
	function weatherConditionIcon($conditionCode) {
		switch($conditionCode){
			case '0':
				# tornado...
				$icon = 'tornado';
				break;	
			case '1':
				# tropical storm...
				$icon = 'tropical-storm';
				break;
			case '2':
				# hurricane...
				$icon = 'hurricane';
				break;
			case '3':
				# severe thunderstorms...
				$icon = 'tropical-storm';
				break;	
			case '4':
				# thunderstorms...
				$icon = 'thunderstorm';
				break;
			case '8':
				# drizzle...
				$icon = 'drizzle';
				break;
			case '9':
				# drizzle...
				$icon = 'drizzle';
				break;	
			case '10':
				# freezing rain...
				$icon = 'rain';
				break;
			case '11':
				# shower...
				$icon = 'drizzle';
				break;
			case '12':
				# shower...
				$icon = 'drizzle';
				break;	
			case '20':
				# foggy...
				$icon = 'foggy';
				break;
			case '24':
				# windy...
				$icon = 'windy';
				break;	
			case '25':
				# cold...
				$icon = 'cold';
				break;
			case '26':
				# cloudy...
				$icon = 'cloudy';
				break;
			case '27':
				# mostly cloudy (night)...
				$icon = 'cloudynight';
				break;	
			case '28':
				# mostly cloudy (day)...
				$icon = 'cloudyday';
				break;
			case '29':
				# partly cloudy (night)...
				$icon = 'cloudynight';
				break;
			case '30':
				# partly cloudy (day)...
				$icon = 'cloudyday';
				break;	
			case '31':
				# clear (night)...
				$icon = 'clearnight';
				break;
			case '32':
				# sunny...
				$icon = 'sunny';
				break;
			case '33':
				# fair (night)...
				$icon = 'cloudynight';
				break;	
			case '34':
				# fair (day)...
				$icon = 'cloudyday';
				break;

			case '36':
				# hot...
				$icon = 'hot';
				break;	
			case '37':
				# isolated thunderstorm...
				$icon = 'thunderstorm';
				break;
			case '38':
				# scattered thunder storm...
				$icon = 'thunderstorm';
				break;
			case '39':
				# scattered thunder storm...
				$icon = 'thunderstorm';
				break;	
			case '44':
				# cloudy...
				$icon = 'cloudy';
				break;
			case '45':
				# thundershower...
				$icon = 'thunderstorm';
				break;	
			case '47':
				# isolated thunder shower...
				$icon = 'thunderstorm';
				break;
			default:
			$icon = 'cloudyday';
			break;			
		}		

		return $icon;
	}
	
	?>



</body>

</html>
