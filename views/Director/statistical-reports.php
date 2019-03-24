<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

	$reports = $user->dashboardReports();
	$date = new DateTime();
   

?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Statistical Reports</title>
    <link rel="shortcut icon" href="../../assets/pics/flaticons/men.png" type="image/x-icon">
	<?php include_once'../../includes/parts/admin_styles.php'; ?>
    <!-- orris -->
    <link href="../../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
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
                    <h2>Procurement Reports</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            This is
						</li>
						<li class="breadcrumb-item">
                            <strong>Reports</strong>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Statistical Reports</strong>
                        </li>
                    </ol>
                </div>

            </div>
			
			<!-- Main Content -->
			<div class="wrapper wrapper-content animated fadeInRight">

			</div>
			<!-- Main Content End -->
			<button class="back-to-top" type="button"></button>		
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>
	



    <?php include '../../includes/parts/admin_scripts.php'; ?>

   
	

<script>
	$(function(){
		setTimeout(function(){
			$('#minimizer').trigger('click');
		}, 1400);
	});
</script>

</body>

</html>
