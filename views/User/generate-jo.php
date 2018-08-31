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
                <div class="col-sm-8">
                    <div class="title-action">
                        <a href="Dashboard" class="btn btn-primary"><i class="ti-angle-double-left"></i> Back to Dashboard</a>
                    </div>
                </div>				
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs-container">
							<ul class="nav nav-tabs">
								<li><a class="nav-link active" data-toggle="tab" href="#tab-1">Project &nbsp&nbsp<i class="ti-folder" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-2">Particulars &nbsp&nbsp<i class="ti-pencil-alt" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-3">Signatories &nbsp&nbsp<i class="ti-user" style="font-size:18px"></i></a></li>
							</ul>
							<div class="tab-content">
								<div><form method="POST" id="jo_form"></form></div>
								<div id="tab-1" class="tab-pane active">
									<div class="panel-body">
									   <h2>Project Information</h2>

										<p>Specify the required fields to generate the Job Order Form that suits your need.</p>
										<div class="row">
											<div class="col-lg-7">
												<div class="form-group">
													<label>Project title *</label>
													<input id="title" name="title" type="text" class="form-control" form="jo_form">
												</div>
												<div class="form-group">
													<label>Overall Estimated Cost *</label>
													<input id="estimated_cost" name="estimated_cost" type="text" class="form-control" form="jo_form">
												</div>
												<div class="form-group">
													<label>Number of Lots *</label>
													<input id="lot" name="lot" type="number" min=1 class="form-control" form="jo_form">
												</div>
											</div>
											<div class="col-lg-3">
												<div class="text-center">
													<div style="margin-left: 100px">
														<i class="ti-layout-tab" style="font-size: 180px;color: #FFD700 "></i>
													</div>
												</div>
											</div>	
										</div>

									</div>	
								</div>
								<div id="tab-2" class="tab-pane">
									<div class="panel-body">
										<h2>Particulars Setting</h2>
										<p>Some shitty explaination what the hell is going on</p>

												<div class="">
													<div class="add-project" id="popOver" data-trigger="hover" title="Friendly Reminder" data-placement="left" data-content="It seems that you're being a little confused here 🤔 that I catch your attention. Cheer up‼ because we're here to guide you. 😉👌 Click on the button to proceed 👉">											
														<button type="button" class="btn btn-danger btn-rounded btn-outline">New to this <i class="fa fa-question" style="font-weight:900"></i></button>
													</div>
												</div>										
										
										<div class="row" id="wf-stp-2">
											<div class="col-lg-12" >
												<h1>No Lots Set.</h1>
											</div>
										</div>
									</div>
								</div>
								<div id="tab-3" class="tab-pane">
									<div class="panel-body">
										   <h2>Project Signatories</h2>

											<p>Specify all signatories to finalized this form.</p>
											
											<div class="row">
												<div class="col-lg-7">
													<div class="form-group">
														<label>End User *</label>
														<input id="enduser" name="enduser" type="text" value="Nico Ativo" class="form-control" disabled form="jo_form">
													</div>
													<div class="form-group">
														<label>Noted By *</label>
														<input id="noted" name="noted" type="text"  class="form-control" form="jo_form">
													</div>
													<div class="form-group">
														<label>Verified By *</label>
														<input id="verified" name="verified" type="text"  class="form-control" form="jo_form">
													</div>
													<div class="form-group">
														<label>Aproved By *</label>
														<input id="approved" name="approved" type="text"  class="form-control" form="jo_form">
														<input type="text" name="rowCount" readonly form="jo_form">
													</div>													
												</div>
												<div class="col-lg-3">
													<div class="text-center">
														<div style="margin-left: 100px;  margin-top:20px">
															<i class="ti-user" style="font-size: 180px;color: #FFD700;"></i>
														</div>
													</div>
												</div>	
												<div class="col-md-7">
													<button class="btn btn-primary btn-outline pull-right" type="submit" form="jo_form">Finish</button>
													<button class="btn btn-danger btn-outline pull-right" style="margin-right:5px">Cancel</button>													
												</div>
											</div>											
									</div>
								</div>							
							</div>
						</div>
					</div>
				
				</div><br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br>
			</div>
			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

	<?php include '../../includes/parts/user_scripts.php'; ?>

    <script>
        $(document).ready(function(){

			$('#lot').on('change', function()
			{
				var lots = this.value;
				var obj = [];
				$('#wf-stp-2').html('');
				for(let i = 0 ; i < lots ; i++)
				{
					obj.push({tag: 0});
					var tmp_lot = `
					<div class="col-lg-6">
						<div class="ibox" style="border: 1px solid rgba(28,110,164,0.63); padding:20px">
							<div class="alert alert-info">
								<center><h5>Lot Number ${i + 1}</h5></center>
								
							</div>
							<div class="ibox-content" id="lot-${i}" style="-webkit-box-shadow: -1px 4px 9px 0px rgba(0,0,0,0.66);-moz-box-shadow: -1px 4px 9px 0px rgba(0,0,0,0.66);box-shadow: -1px 4px 9px 0px rgba(0,0,0,0.66);">
							
							<div class="form-group">
								<label>Lot Title *</label>
								<input type="text" class="form-control" name="L${i}-title" form="jo_form">
							</div>
							<hr style="	height: 10px; border: 0; box-shadow: 0 10px 10px -10px #8c8b8b inset;">
							
	
								<div>
									<br>
									<p class="font-bold">List Name: </p>
									<input type="text" name="L${i}-listname-0" class="form-control" form="jo_form">
									<br>
									<p class="font-bold">&#128204; Tags:</p>
									<input class="form-control" name="L${i}-tags-0" id="lot-${i}-tag-0" data-role="tagsinput" form="jo_form">
									<br>
								</div>
							</div><br>
							<button class="btn btn-primary btn-rounded pull-right" data-type="btn" data-tag="lot-${i}" type="button"><span class="bold">Add List&nbsp;&nbsp;</span><i class="ti-plus"></i></button><br>


							
						</div>
					</div>`;
					$(`#wf-stp-2`).append(tmp_lot);
					$(`#lot-${i}-tag-0`).tagsinput();
				}
				$('[name="rowCount"]').val(JSON.stringify(obj));

				$('[data-type="btn"]').on('click', function()
				{
					var num = $(this).attr("data-tag").split("-");
					obj[num[1]].tag++;
					var tg_num = obj[num[1]].tag;
					var list_tmp = `
					<div>
						<br><hr style="	height: 6px; background: url(http://ibrahimjabbari.com/english/images/hr-12.png) repeat-x 0 0;border: 0;">
						<p class="font-bold">List Name: </p>
						<input type="text" name="L${num[1]}-listname-${tg_num}" class="form-control" form="jo_form">
						<br>
						<p class="font-bold">&#128204; Tags:</p>
						<input class="form-control" name="L${num[1]}-tags-${tg_num}" id="lot-${num[1]}-tag-${tg_num}" data-role="tagsinput" form="jo_form">
						<br>
					<div>`;
					$(`#${num[0]}-${num[1]}`).append(list_tmp);
					$(`#lot-${num[1]}-tag-${tg_num}`).tagsinput();
					$('[name="rowCount"]').val(JSON.stringify(obj));
				});
			});
		});

    </script>



</body>

</html>
