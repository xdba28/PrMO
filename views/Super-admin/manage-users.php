<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }


    $sa = new Super_admin();
    $PersonnelInfo = $sa->personnels();
	
	if(Input::exists()){
		if(Token::check('updateInfoToken', Input::get('updateInfoToken'))){
			
			$sa = new Super_admin();
            $ID = $_GET['q'];
            
			try{

				if((empty($_POST["extName"])) OR (is_numeric(Input::get('extName'))) OR (preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', Input::get('extName'))) ){
					$ext = "XXXXX";
				}else{
					$ext = Input::get('extName');
				}
			  
				$sa->startTrans();
					$sa->update('prnl_account', 'account_id', $ID, array(
						'group_' => Input::get('group')
					));
					$sa->update('personnel', 'prnl_id', $ID, array(
						
						'prnl_fname' => Input::get('firstName'),
						'prnl_mname' => Input::get('middleName'),
						'prnl_lname' => Input::get('lastName'),
						'prnl_ext_name' => $ext,
						'prnl_designated_office' => Input::get('unit'),
						'current_specific_office' => Input::get('specific-office'),
						'prnl_job_title' => Input::get('jobTitle'),
						'prnl_id' => Input::get('employeeId')

					));
				$sa->endTrans();

				$special_notifs[] = "User information successfully updated.";
				Syslog::put('Admin account update');
				
				
			}catch(Exception $e){
				Syslog::put($e,null,'error_log');
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0001");
			}
			
		}else if(Token::check('status-toggle-token', Input::get('status-toggle-token'))){
			try{

				$parts = explode("+", Input::get('action-request'));
				if($parts[0] === "deactivate"){
					$user->startTrans();
						$user->update("prnl_account", "account_id", $parts[1], array(
							'status' => 'DEACTIVATED'
						));
					$user->endTrans();
					$messageFiller = "deactivated";
					Syslog::put('Deactivate admin account '.$parts[1]);
				}else if($parts[0] === "activate"){
					$user->startTrans();
						$user->update("prnl_account", "account_id", $parts[1], array(
							'status' => 'ACTIVATED'
						));
					$user->endTrans();
					$messageFiller = "activated";
					Syslog::put('Activate admin account '.$parts[1]);
				}


				
				Session::flash("SA_SPECIAL_ACTION", "Account successfully {$messageFiller}.");
				Redirect::To('manage-users');

			}catch(Exception $e){
				Syslog::put($e,null,'error_log');
				Session::flash("FATAL_ERROR", "Processed transactions are automatically canceled. ERRORCODE:0002");
			}
		}
	}


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Admins</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include "../../includes/parts/admin_styles.php";?>
	<script>
		var PersUpdate = '<?php 
		if(Session::exists("PersUpdate")) echo Session::flash("PersUpdate");
		else echo "";
		?>';
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
                    <h2>Admin Accounts Management</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">User Accounts</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Admins</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        <a href="Dashboard" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInUp">
			
			<?php
				if(isset($_GET['q'])){

                $personnel = new Super_admin();
                $name = $personnel->fullnameOF($_GET['q']);
                $data = $personnel->personnelData($_GET['q']);
				

			?>
            <div class="row">
                <div class="col-md-4 animated fadeInLeft">
                    <div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5>Profile General details</h5>
                        </div>
                        <div>
                            <div class="ibox-content no-padding border-left-right">
                                <img alt="image" class="img-fluid" src="../../assets/pics/profile-bg.png">
                            </div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $name;?></strong></h4>
								<div class="">
									<p class="inline"><i class="ti-id-badge" style="font-size:18px;"></i> </p>
									<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $data->prnl_id;?></p>
									<br>
									<p class="inline"><i class="ti-email" style="font-size:18px;"></i> </p>
									<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $data->prnl_email;?></p>
									<br>
									<p class="inline"><i class="fa fa-phone" style="font-size:18px;"></i> </p>
									<p class="inline" style="font-size:13px"> &nbsp&nbsp <?php echo $data->phone;?></p>									
								</div>								

								

                            </div>
						</div>
					</div>
                </div>
                <div class="col-md-8 animated fadeInRight">
                    <div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5>Update Form</h5>
                        </div>
                        <div class="ibox-content">
						    <h2>
                                Personnel Info
                            </h2>
                            <p class="alert alert-info">Take note that not all data here are editable for some data are just for referencing purposes only.</p>						
							<div class="row">
								
									<div class="col-sm-6 b-r"> 
										<form id="update_personnel_form" role="form" method="POST">								
											<div class="form-group"><label>First name</label> <input type="text" value="<?php echo $data->prnl_fname;?>" name="firstName" class="form-control"></div>
											<div class="form-group"><label>Middle name</label> <input type="text" value="<?php echo $data->prnl_mname;?>" name="middleName" class="form-control"></div>
											<div class="form-group"><label>Last name</label> <input type="text" value="<?php echo $data->prnl_lname;?>" name="lastName" class="form-control"></div>
											<div class="form-group"><label>Extension name</label> <input type="text" value="<?php 
											
												if($data->prnl_ext_name === "XXXXX"){
													echo "";
												}else{
													echo $data->prnl_ext_name;
												}?>" name="extName" class="form-control"></div>
											<div class="form-group">
												<label>Designation / Unit</label>
												<select class="form-control m-b required" id="unitselection" name="unit">
													<option value="<?php echo $data->ID;?>"> <?php echo $data->office_name;?></option>
													<?php
													
														$units = $personnel->selectAll('units');
														foreach($units as $unit){
															echo "<option value ='{$unit->ID}'>{$unit->office_name}</option>";
														}
													?>
												</select>
											</div>	
											<div class="form-group">
												<label>Specific Office</label>
												<div class="input-group">
													<input id="typeahead-specific-office" value="<?php echo $data->current_specific_office;?>" name="specific-office" type="text" class="form-control" required>
												</div>												
											</div>																							

									</div>
									<div class="col-sm-6">
									<div class="form-group"><label>Job Title</label> <input type="text" value="<?php echo $data->prnl_job_title;?>" name="jobTitle" class="form-control"></div>
											<div class="form-group"><label>Employee ID</label> <input type="text" value="<?php echo $data->prnl_id;?>" name="employeeId" class="form-control"></div>
											<div class="form-group"><label>Account username</label> <input type="text" value="<?php echo $data->username;?>" disabled class="form-control"></div>
											<div class="form-group"><label>Original Group</label> <input type="text" value="<?php echo $data->group_name;?>" disabled class="form-control"></div>
											<div class="form-group">
												<label>Set Group</label>
												<select class="form-control m-b required" name="group">
													<option value="<?php echo $data->group_id;?>"> Select... </option>
													<?php
													
														$groups = $personnel->selectAll('group');
														foreach($groups as $group){
															echo "<option value ='{$group->group_id}'>{$group->name}</option>";
														}
													?>
												</select>
											</div>							
											<div class="form-group"><label>Account Status</label> <input type="text" value="<?php echo $data->status;?>" disabled class="form-control"></div> 
											<input type="text" name="updateInfoToken" value="<?php echo Token::generate('updateInfoToken');?>" hidden readonly>
										</form>		
									</div>	
									<div class="col-lg-12">
												<button class="btn btn-primary btn-rounded pull-right" type="submit" form="update_personnel_form">Update</button>
												<a href="manage-users" class="btn btn-danger btn-rounded pull-right" style="margin-right:5px">Cancel</a>	
									</div>
							</div>
                        </div>
                    </div>

                </div>
            </div>				
			<?php
			}else{
			?>	
			
            <div class="row">
                <div class="col-lg-12 animated fadeInRight">
                    <div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5>Personnel Accounts</h5>
							<form action="" id="status-toggler" method="POST" enctype="multipart/form-data">
								<input type="text" name="status-toggle-token" value="<?php echo Token::generate("status-toggle-token");?>" hidden>
							</form>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <input type="text" class="form-control form-control-sm m-b-xs" id="filter" placeholder="Search in table">
                            <table class="footable table table-stripped toggle-arrow-tiny" data-filter=#filter>
							
                                <thead>
									<tr>

										<th data-toggle="true">Name</th>
										<th>Username</th>
										<th>Account Type</th>
										
											<th data-hide="all">Employee ID</th>
											<th data-hide="all">Email</th>
											<th data-hide="all">Phone</th>
											<th data-hide="all">Designation</th>
											<th data-hide="all">Specific Office</th>
											<th data-hide="all">Position</th>

										<th>Account Status</th>
										<th>Action</th>
									</tr>
                                </thead>
								
                                <tbody>
                                <?php

									// echo "<pre>",print_r($PersonnelInfo),"</pre>";
									
                                    foreach($PersonnelInfo as $data){
                                        if($data->prnl_ext_name == 'XXXXX'){
                                            $fullname = $data->prnl_fname." ".$data->prnl_mname." ".$data->prnl_lname;
                                        }else{
                                            $fullname = $data->prnl_fname." ".$data->prnl_mname." ".$data->prnl_lname." ".$data->prnl_ext_name;
                                        }

                                        if($data->status == "ACTIVATED"){

                                            $color = "text-navy";
                                            $option = "Deactivate Account";
											$action = "deactivate";

                                        }else{
                                            $color = "text-danger";
                                            $option = "Activate Account";
											$action = "activate";
                                        }
                                       

										if($data->prnl_id != "163-141"){
											echo '
                                            <tr>
                                                <td>'.$fullname.'</td>
												<td>'.$data->username.'</td>
												<td>'.$data->name.'</td>                                                

													<td>'.$data->prnl_id.'</td>
													<td>'.$data->prnl_email.'</td>
													<td>'.$data->phone.'</td>
													<td>'.$data->office_name.'</td>
													<td>current office</td>
													<td>'.$data->prnl_job_title.'</td>

												<td class="'.$color.'"><b><a>'.$data->status.'</a></b></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button data-toggle="dropdown" class="btn btn-warning btn-xs dropdown-toggle">Options </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="?q='.$data->prnl_id.'">Update Info</a></li>
                                                            <li><a class="dropdown-item" data-toggle="modal" data-name="'.$fullname.'" data-phone="'.$data->phone.'" data-target="#resetPassword" data-id="'.$data->prnl_id.'" data-office="'.$data->office_name.'">Reset Password</a></li>
                                                            <li class="dropdown-divider"></li>
                                                            <li><button type="submit" class="dropdown-item nicecolor" form="status-toggler" name="action-request" value="'.$action.'+'.$data->prnl_id.'" >'.$option.'</button></li>
															<li class="dropdown-divider"></li>
                                                        </ul>
                                                    </div>									
                                                </td>
                                            </tr>                                                                                
                                        
                                        ';
										}


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
				
			<?php	
				}
			?>
					


            </div>
			<div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
			</div>

        </div>

    </div>

	<?php include "../../includes/parts/admin_scripts.php"?>

</body>

	<script>
		$(function()
		{
			if(PersUpdate !== "")
			{
				swal({
					title: PersUpdate,
					text: "",
					confirmButtonColor: "#DD6B55",
					type: 'success',
					timer: 13000
				});
			}
			
			var details = {};

			$('#resetPassword').on('show.bs.modal', function(event){
				var button = $(event.relatedTarget); // Button that triggered the modal
				var recipient = button.data('name'); // Extract info from data-* attributes
				var office = button.data('office');
				var id = button.data('id');
				var phone = button.data('phone');
				var modal = $(this);

				details.id = id;
				details.office = office;
				details.recipient = recipient;
				details.phone = phone;
				details.user = "admin";

				modal.find('#phone').html(phone);
				modal.find('.modal-title').html('Reset Account Password <br> <a style="color:#06425C">' + recipient + '</a>');
				modal.find('#office').html(office);
			});

			$('[data-reset="user"]').on('click', function(){
				details.newPass = $('#s-user-Npass').val();
				if(details.newPass !== ''){
					SendDoNothing("POST", "xhr-reset.php", details, {
						title: "Success!",
						text: "Successfully reset user's account."
					});
					$('#resetPassword').modal('hide');
				}else{
					$('#resetPassword').modal('hide');
					swal({
						title: "Action invalid!",
						text: "Enter a new password.",
						confirmButtonColor: "#DD6B55",
						type: "error"
					});
				}
			});

		});


	</script>
	<script>
		$(document).ready(function(){
			$("#typeahead-specific-office").typeahead({

				<?php 
					if($offices = $user->getAll("offices", array("unit", "=", $data->ID))){
						foreach($offices as $office){
							$available_offices[] = $office->specific_office;
						}

						$finalSuggestions = '"'.implode('", "',$available_offices).'"';
					}else{
						$finalSuggestions = "";
					}
				
				?>

				source: [<?php echo $finalSuggestions;?>]
			});	

			$('[name="unit"]').on('change', function(){
				SendDoSomething("POST", "xhr-get-offices.php", {
					id: this.value
				}, {
					do: function(r){
						$('#typeahead-specific-office').typeahead('destroy');
						$("#typeahead-specific-office").typeahead({
							source: r.offices
						});
					}
				});
			});

		});
	</script>


</html>
