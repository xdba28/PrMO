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
    $EnduserInfo = $sa->standardUsers();
	
	//echo "<pre>",print_r($PersonnelInfo),"</pre>";



   

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Standard Users</title>
	<?php include "../../includes/parts/admin_styles.php";?>
	<script>
		var PersUpdate = '<?php 
		if(Session::exists("PersUpdate")) Session::flash("PersUpdate");
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
                    <h2>Standard Users Management</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Users</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Manage Users</strong>
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
			

			
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox myShadow">
                        <div class="ibox-title">
                            <h5>Standard Users Accounts</h5>

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
										<th>Office</th>
										<th>Position</th>
										<th data-hide="all">Employee ID</th>
										<th data-hide="all">Email</th>
										<th data-hide="all">Phone</th>
										
										<th>Action</th>
									</tr>
                                </thead>
								
                                <tbody>
                                <?php

                                    foreach($EnduserInfo as $data){
                                        if($data->edr_ext_name == 'XXXXX'){
                                            $fullname = $data->edr_fname." ".$data->edr_mname." ".$data->edr_lname;
                                        }else{
                                            $fullname = $data->edr_fname." ".$data->edr_mname." ".$data->edr_lname." ".$data->edr_ext_name;
                                        }

                                        // if($data->status == "ACTIVATED"){

                                        //     $color = "text-navy";
                                        //     $option = "Deactivate Account";

                                        // }else{
                                        //     $color = "text-danger";
                                        //     $option = "Activate Account";
                                        // }
                                       

                                        echo '                                        
                                            <tr>
                                                <td>'.$fullname.'</td>
                                                <td>'.$data->office_name.'</td>
                                                <td>'.$data->edr_job_title.'</td>
                                                <td>'.$data->edr_id.'</td>
                                                <td>'.$data->edr_email.'</td>
                                                <td>'.$data->phone.'</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button data-toggle="dropdown" class="btn btn-warning btn-xs dropdown-toggle">Options </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" data-toggle="modal" data-name="'.$fullname.'" data-phone="'.$data->phone.'" data-target="#resetPassword" data-id="'.$data->edr_id.'" data-office="'.$data->office_name.'">Reset Password</a></li>
                                                            <li class="dropdown-divider"></li>
                                                        </ul>
                                                    </div>									
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
		});
	</script>

</html>
