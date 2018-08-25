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
            </div>
			
			<!-- Main Content -->
            <div class="wrapper wrapper-content">
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
						<div class="tabs-container">
							<ul class="nav nav-tabs">
								<li><a class="nav-link active" data-toggle="tab" href="#tab-3">Project &nbsp&nbsp<i class="ti-folder" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-4">Particulars &nbsp&nbsp<i class="ti-pencil-alt" style="font-size:18px"></i></a></li>
								<li><a class="nav-link" data-toggle="tab" href="#tab-5">Something &nbsp&nbsp<i class="ti-bookmark" style="font-size:18px"></i></a></li>
							</ul>
							<div class="tab-content">
								<form id="agik" action="" method="POST"></form>
								<div id="tab-3" class="tab-pane active">
									<div class="panel-body">
									   <h2>Project Information</h2>

										<p>Specify the required fields to generate the Job Order Form that suits your need.</p>
										<div class="row">
											<div class="col-lg-7">
												<div class="form-group">
													<label>Project title *</label>
													<input form="agik" id="title" name="title" type="text" class="form-control">
												</div>
												<div class="form-group">
													<label>Overall Estimated Cost *</label>
													<input id="estimated_cost" name="estimated_cost" type="text" class="form-control">
												</div>
												<div class="form-group">
													<label>Number of Lots *</label>
													<input id="lots" name="lots" type="number" min=1 class="form-control">
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
								<div id="tab-4" class="tab-pane">
									<div class="panel-body">
										<h2>Particulars Setting</h2>

										<p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
											and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

										<div class="row">
											<div class="col-lg-7">
												<div class="form-group">
													<label>fields</label>
													<input id="title" name="title" type="text" class="form-control">
												</div>
												<div class="form-group">
													<label>fields</label>
													<input id="estimated_cost" name="estimated_cost" type="text" class="form-control">
												</div>
												<div class="form-group">
													<label>Number of Lots</label>
													<input id="lot" name="lot" type="number" min="1" class="form-control">
												</div>
											</div>
											<div class="col-lg-3">
												<div class="text-center">
													<div style="margin-left: 100px">
														<i class="ti-pencil-alt" style="font-size: 180px;color: #FFD700 "></i>
													</div>
												</div>
											</div>	
										</div>
									</div>
								</div>
								<div id="tab-5" class="tab-pane">
									<div class="panel-body">
										<strong>Donec quam felis</strong>

										<p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
											and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

										<p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
											sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
									</div>
								</div>
								<input form="agik" type="submit">
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
				var obj = []
				$('#wf-stp-2').html('');
				for(let i = 0 ; i < lots ; i++)
				{
					obj.push({tag: 0});
					var tmp_lot = `
					<div class="col-lg-6">
						<div class="ibox">
							<div class="ibox-title">
								<h5>Lot Number ${i + 1}</h5>
							</div>
							<div class="ibox-content" id="lot-${i}">
								<div>
									<p class="font-bold">Header Name: </p>
									<input type="text" name="lot${i}[list-name][]" class="form-control">
									<br>
									<p class="font-bold">Tags:</p>
									<input class="form-control" name="lot${i}[list][]" id="lot-${i}-tag-0" data-role="tagsinput">
									<br>
								</div>
							</div>
							<button type="button" data-type="btn" data-tag="lot-${i}">Click</button>
							
						</div>
					</div>`;
					$(`#wf-stp-2`).append(tmp_lot);
					$(`#lot-${i}-tag-0`).tagsinput();
				}

				$('[data-type="btn"]').on('click', function()
				{
					var num = $(this).attr("data-tag").split("-");
					obj[num[1]].tag++;
					var tg_num = obj[num[1]].tag;
					var list_tmp = `
					<div>
						<p class="font-bold">Header Name: </p>
						<input type="text" name="lot${num[1]}[list-name][]" class="form-control">
						<br>
						<p class="font-bold">Tags:</p>
						<input class="form-control" name="lot${num[1]}[list][]" id="lot-${num[1]}-tag-${tg_num}" data-role="tagsinput">
						<br>
					<div>`;
					$(`#${num[0]}-${num[1]}`).append(list_tmp);
					$(`#lot-${num[1]}-tag-${tg_num}`).tagsinput();
				});
			});
		});

    </script>


</body>

</html>