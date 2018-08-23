<?php 

    require_once('../../core/init.php');

	$user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

    // $user = $user->profile(Session::get(Config::get('session/session_name')));

	$sa = new Super_admin();
	$requests = $sa->requests();
	$registered = $sa->registered_users();


	if (Input::exists()){
        if(Token::check(Input::get('token'))){
			//allow to submit the form
			
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'rq-hid' => [
                    'required' => true
                ],
                'rq-rsn' => [
                    'min' => '4',
                    'required' => true
                ]
            ));
    
            if($validation->passed()){
                try{
                    $sa->update_request(Input::get('rq-rsn'), Input::get('rq-hid'));
                    
                    Session::flash('toust', 'Request for Account Denied');
                    // Redirect::To('account-request');

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

    <title>INSPINIA | Dashboard v.3</title>
	<?php include "../../includes/parts/admin_styles.php"?>

	<script>
		function ps_mdl_d(name, id){
			document.getElementById('rq-mdl-name').value = name;
			document.getElementById('rq-hid').value = id;	
		}

		function approve(id)
		{
			$.ajax(
			{
				type: 'post',
				url: '',
				data: id,
				success: function(data)
				{
					toastr.success("Message here");
				},
				error: function(jqXHR, textStatus, errorThrown)
				{
					console.log(`
						jqXHR: ${jqXHR}
						textStatus: ${textStatus}
						errorThrown: ${errorThrown}
					`);
				}
			})
		}
	</script>


</head>

<body class="fixed-navigation">
    <div id="wrapper">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/superadmin_side_nav.php'; ?>
				</ul>

			</div>
		</nav>

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Account Requests</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Requests</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Account</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        <a href="" class="btn btn-primary">This is action area</a>
                    </div>
                </div>
            </div>

            <div class="wrapper wrapper-content">



                <div class="row">

                    <div class="col-lg-6">
                        <div class="ibox ">
                            <div class="ibox-title">
								<h5>Registered End Users</h5>
                                <span class="label label-info float-right pull-right">Today</span>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo count($registered);?></h1>                               
                                <small>Active</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ibox ">
                            <div class="ibox-title">
								<h5>Pending / Uncleared Requests</h5>
                                <span class="label label-info float-right pull-right">Today</span>           
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo count($requests);?></h1>
                                <small>Issues may be incorrect data</small>
                            </div>
                        </div>
                    </div>

                </div>
				
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>All Pending Requests from End-Users</h5>

                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
            
              
                            </div>
                        </div>
                        <div class="ibox-content">

                            <table class="footable table table-stripped toggle-arrow-tiny">
                                <thead>
                                <tr>

                                    <th data-toggle="true">Requestor</th>
                                    <th>Unit</th>
                                    <th>Status</th>
									<th data-hide="all">Phone</th>
                                    <th data-hide="all">Email</th>                                                        
                                    <th data-hide="all">Requested</th>
									<th data-hide="all">Employee Id</th>
									<th data-hide="all">Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
								
								<?php														
									
									foreach($requests as $request){
										if($request->ext_name == "none"){
											$fullname = $request->fname." ".$request->last_name;
										}else{
											$fullname = $request->fname." ".$request->last_name." ".$request->ext_name;
										}
										
										$color = ($request->status == "pending") ? "text-navy" : "text-danger";
										$color1 = ($request->remarks == "none") ? "" : "text-danger";
										
										
										$time = strtotime($request->submitted);
										$final = date("l F j, Y g:i:sa", $time); 
										
									//<td><span class="pie">90/100</span></td>	
										echo '
											<tr>
												<td>'.$fullname.'</td>
												<td>'.$request->office_name.'</td>
												<td><a class="'.$color.'">'.$request->status.'</a></td>
												<td>'.$request->contact.'</td>
												<td>'.$request->email.'</td>																					
												<td>'.$final.'</td>
												<td><b>'.$request->employee_id.'</b></td>
												<td><a class="'.$color1.'">'.$request->remarks.'</a></td>
												<td>
													<a onclick="approve(\''.$request->ID.'\')"><i class="fa fa-check text-navy"></i></a> 
													
													<a data-toggle="modal" data-target="#decline_modal" onclick="ps_mdl_d(\''.$fullname.'\', \''.$request->ID.'\')">
														<i class="fa fa-close text-danger" style="margin-left:20px"></i>
													</a>
												</td>
											</tr>																				
										';
									}
								?>
								

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination float-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>



            </div>
			<div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
			</div>

        </div>
        <div id="right-sidebar">
            <div class="sidebar-container">

                <ul class="nav nav-tabs navs-3">

                    <ul class="nav nav-tabs navs-2">
                        <li>
                            <a class="nav-link active" data-toggle="tab" href="#tab-3"> Settings </a>
                        </li>					
                        <li>
                            <a class="nav-link" data-toggle="tab" href="#tab-2"> Projects </a>
                        </li>
                    </ul>

                <div class="tab-content">


                    <div id="tab-3" class="tab-pane active">

                        <div class="sidebar-title">
                            <h3><i class="fa fa-gears"></i> Settings</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>

                        <div class="setings-item">
                    <span>
                        Show notifications
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                                    <label class="onoffswitch-label" for="example">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Disable Chat
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
                                    <label class="onoffswitch-label" for="example2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Enable history
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                                    <label class="onoffswitch-label" for="example3">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Show charts
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                                    <label class="onoffswitch-label" for="example4">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Offline users
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
                                    <label class="onoffswitch-label" for="example5">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Global search
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
                                    <label class="onoffswitch-label" for="example6">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Update everyday
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                                    <label class="onoffswitch-label" for="example7">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-content">
                            <h4>Settings</h4>
                            <div class="small">
                                I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                And typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                            </div>
                        </div>

                    </div>
                    <div id="tab-2" class="tab-pane">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-cube"></i> Latest projects</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>



                    </div>				
                </div>

            </div>



        </div>
    </div>
	<?php include_once '../../includes/parts/modals.php'; ?>
    <!-- Mainly scripts -->
	<script src="../../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../../assets/js/popper.min.js"></script>
    <script src="../../assets/js/bootstrap.js"></script>
    <script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>



    <!-- Peity -->

    <script src="../../assets/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="../../assets/js/demo/peity-demo.js"></script>
	
	
    <!-- Custom and plugin javascript -->
    <script src="../../assets/js/inspinia.js"></script>
    <script src="../../assets/js/plugins/pace/pace.min.js"></script>
	<script src="../../assets/js/plugins/toastr/toastr.min.js"></script>

	
    <!-- FooTable -->
    <script src="../../assets/js/plugins/footable/footable.all.min.js"></script>

    <script>
        $(document).ready(function() {

            var lineData = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 80, 81, 56, 55, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});



        });
    </script>
	
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

			var tst_rdy = '<?php 
				if(Session::exists('toust')) echo Session::flash('toust');
				else echo "0";
			?>';
			
			if(tst_rdy !== "0")
			toastr.success(tst_rdy);

        });

    </script>
</body>
</html>
