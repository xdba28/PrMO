<?php

    require_once('../../core/init.php');

    $user = new Admin(); 

    
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
    }

	$proj = $user->selectAll('projects');
?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PrMO OPPTS | Procurement Aid</title>
    <link href="../../assets/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="../../assets/css/plugins/fullcalendar/fullcalendar.print.css" rel='stylesheet' media='print'>
	<?php include_once'../../includes/parts/admin_styles.php'; ?>

</head>

<body class="">

    <div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<?php include '../../includes/parts/side_nav_header.php'; ?>
					<?php include '../../includes/parts/aid_side_nav.php'; ?>
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
                <div class="col-sm-4">
                    <h2>Procurement Calendar</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">This is</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>Procurement Calendar</strong>
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
				<div class="row animated fadeInDown">
					<div class="col-lg-12">
						<div class="ibox myShadow">
							<div class="ibox-title">
								<h5>Procurement Projects with their nearest Implementation Date</h5>
							</div>
							<div class="ibox-content">
								<div id="calendar"></div>
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
	<!-- Full Calendar -->
	<script src="../../assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
	<script>

		$(document).ready(function() {



			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});

			/* initialize the external events
			 -----------------------------------------------------------------*/


			$('#external-events div.external-event').each(function() {

				// store data so the calendar knows to render an event upon drop
				$(this).data('event', {
					title: $.trim($(this).text()), // use the element's text as the event title
					stick: true // maintain when user navigates (see docs on the renderEvent method)
				});

				// make the event draggable using jQuery UI
				$(this).draggable({
					zIndex: 1111999,
					revert: true,      // will cause the event to go back to its
					revertDuration: 0  //  original position after the drag
				});

			});


			/* initialize the calendar
			 -----------------------------------------------------------------*/
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			

			const cal = $('#calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				editable: false,
				droppable: true, // this allows things to be dropped onto the calendar
				drop: function() {
					// is the "remove after drop" checkbox checked?
					if ($('#drop-remove').is(':checked')) {
						// if so, remove the element from the "Draggable Events" list
						$(this).remove();
					}
				},
				eventRender: function(eventObj, $el) {
					$el.popover({
						title: eventObj.refno,
						content: eventObj.title,
						trigger: 'hover',
						placement: 'top',
						container: 'body'
					});
				},
				events: [
					<?php
						foreach($proj as $d){
							$date = explode("-", $d->implementation_date);
							echo "{
								title: '{$d->project_title}',
								start: new Date({$date[0]}, {$date[1]}-1, {$date[2]}),
								refno: '{$d->project_ref_no}',
								url: 'project-details?refno={$d->project_ref_no}',
								allDay: true
							},";
						}
					?>
					// {
					//     title: 'TEST PROJECT',
					//     start: new Date(y, m, d)
					// },
					// {
					//     title: 'Long Event',
					//     start: new Date(y, m, d-5),
					//     end: new Date(y, m, d-2)
					// },
					// {
					//     id: 999,
					//     title: 'Repeating Event',
					//     start: new Date(y, m, d-3, 16, 0),
					//     allDay: false
					// },
					// {
					//     id: 999,
					//     title: 'Repeating Event',
					//     start: new Date(y, m, d+4, 16, 0),
					//     allDay: false
					// },
					// {
					//     title: 'Meeting',
					//     start: new Date(y, m, d, 10, 30),
					//     allDay: false
					// },
					// {
					//     title: 'Lunch',
					//     start: new Date(y, m, d, 12, 0),
					//     end: new Date(y, m, d, 14, 0),
					//     allDay: false
					// },
					// {
					//     title: 'Birthday Party',
					//     start: new Date(y, m, d+1, 19, 0),
					//     end: new Date(y, m, d+1, 22, 30),
					//     allDay: false
					// },
					// {
					//     title: 'Click for Google',
					//     start: new Date(y, m, 28),
					//     end: new Date(y, m, 29),
					//     url: 'http://google.com/'
					// }
				]
			});


		});

	</script>

</body>

</html>
