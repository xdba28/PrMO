<?php 

    require_once('../../core/init.php');

    $user = new Admin(); 

    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
	}


?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Empty Page</title>

	<?php include_once'../../includes/parts/admin_styles.php'; ?>

	<script>
		var OBJ = 
		<?php
		$user = new Staff();
		echo json_encode($user->allPRJO_req_detail());		
		?>;
		console.log(OBJ);
	</script>
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

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
					<?php include '../../includes/parts/admin_header.php'; ?>
				</nav>
			
			</div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Project Registration</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Projects</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>New Project</strong>
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
            <div class="wrapper wrapper-content animated fadeInUp">
			
			<?php
				if(isset($_GET['q'])){

					//$admin = new admin();
					//$request = $admin->get('project_request_forms', array('form_ref_no', '=', $_GET['q']));
					//$enduser = $admin->get('enduser', array('edr_id', '=', $request->requested_by));
					//$office = $admin->get('units', array('ID', '=', $enduser->edr_designated_office));
					//echo "<pre>",print_r($request), "</pre>";
			?>
						
						<!-- page content of registration for consolidated projects -->
			
			<?php
				}else{
			?>
			
						<!-- content of all project to be consilidate like in the new project -->
			<?php
				}
			?>						
			
            </div>
			<!-- Main Content End -->
			
            <div class="footer">
				<?php include '../../includes/parts/footer.php'; ?>
            </div>

        </div>
    </div>

    <?php include_once '../../includes/parts/admin_scripts.php'; ?>	
	<script>

	$(document).ready(function(){

		function start(){
			OBJ.forEach(function(el, index)
			{
				var user = el.req_by.split(":");
				var data_tmp = `
				<tr>
					<td><a href="#${el.id}" class="client-link">${el.id}</a></td>
					<td>${user[1]}</td>
					<td><i class="fa fa-clock"></i> ${el.date_created}</td>
					<td><button class="ladda-button btn-rounded btn btn-warning" proj="${el.id}" data-style="zoom-in">Receive</button></td>
				</tr>`;
				$('#nwprj-tbl-data').append(data_tmp);
				if(el.log_exist === false) $(`[proj="${el.id}"]`).prop('disabled', false);
				else $(`[proj="${el.id}"]`).prop('disabled', true);
			});

			$(document.body).on("click",".client-link",function(e)
			{
				e.preventDefault();
				var ID = $(this).attr('href').split("#");
				var PROJ = OBJ.find(function(el)
				{
					return el.id === ID[1];
				});

				if(typeof PROJ !== "undefined")
				{
					$('[data="side-panel"]').attr("id", PROJ.id);
					$('[data="side-panel"] h2').html(PROJ.title);
					$('#popOver0').attr("proj-comp", PROJ.id);
					$('#btnlink').attr("href", `?q=${PROJ.id}`)

					if(PROJ.log_exist === true) $('#registerNow').prop('disabled', false);
					else $('#registerNow').prop('disabled', true);

					$('#lot-data').html('');
					PROJ.lot_details.forEach(function(el, index)
					{
						if(PROJ.type === "PR")
						{
							if(el.l_title === 'static lot'){
								var lot_temp = `
								<li class="list-group-item fist-item">
									<span class="float-right"> No. of Items ${el.numReq}</span>
									Unspecified Lot
								</li>`;
							}else{
								var lot_temp = `
								<li class="list-group-item fist-item">
									<span class="float-right"> No. of Items ${el.numReq}</span>
									${el.l_title}
								</li>`;						
							}
						}
						else if(PROJ.type === "JO")
						{
							var lot_temp = `
							<li class="list-group-item fist-item">
								<span class="float-right"> No. of List ${el.numReq}</span>
								${el.l_title}
							</li>`;	
						}
						$('#lot-data').append(lot_temp);
					});
					$('span[date="created"]').html(PROJ.date_created);

					$(".selected .tab-pane").removeClass('active');
					$($(this).attr('href')).addClass("active");
				}
				else
				{
					swal({
						title: "An Error Occurred!",
						text: "Please reload the Page."
					});
				}

				$('#popOver0').on('click', function(){
					window.open(`view-proj?id=${$(this).attr("proj-comp")}`);
				});

			});

			$('.ladda-button').ladda();
			$('[proj]').on('click', function()
			{
				var SendBtn = $(this);
				SendBtn.ladda('start');
				var xhrData = JSON.stringify(OBJ.find(function(el){
					return el.id === SendBtn.attr("proj");
				}));

				$.ajax({
					type: "POSt",
					url: "xhr-receive-proj.php",
					data: {obj: xhrData},
					timeout: 5000,
					success: function(data)
					{
						if(typeof data === "object" && data !== null && !(data.success === false))
						{
							OBJ = data;
							swal({
								title: 'Project Received!',
								text: `You can now register ${SendBtn.attr("proj")} as a new project.`,
								confirmButtonColor: "#DD6B55",
								type: 'success',
								timer: 13000
							});
						}
						else if(data.success === false)
						{
							swal({
								title: "An Error Occurred!",
								text: "Request Not Processed"
							});
						}
						SendBtn.ladda('stop');
						$('#nwprj-tbl-data').html('');
						start();
						console.log("asdasd");
					},
					error: function()
					{
						swal({
							title: "An Error Occurred!",
							text: "Request Not Processed"
						});
						SendBtn.ladda('stop');
					}
				});
			});
		}
		start();
	});


	</script>
</body>

</html>
