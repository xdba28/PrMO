<?php 
include_once '../../includes/parts/modals.php'; 
require_once "../../functions/account-verifier.php";
?>

<!-- Mainly scripts -->
<script src="../../assets/js/jquery-3.1.1.min.js"></script>
<script src="../../assets/js/popper.min.js"></script>
<script src="../../assets/js/bootstrap.js"></script>
<script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Peity -->
<script src="../../assets/js/plugins/peity/jquery.peity.min.js"></script>
<script src="../../assets/js/demo/peity-demo.js"></script>

<!-- Chosen -->
<script src="../../assets/js/plugins/chosen/chosen.jquery.js"></script>


<!-- Input Mask-->
<script src="../../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../../assets/js/inspinia.js"></script>
<script src="../../assets/js/plugins/pace/pace.min.js"></script>

<!-- Toast -->
<script src="../../assets/js/plugins/toastr/toastr.min.js"></script>

<!-- FooTable -->
<script src="../../assets/js/plugins/footable/footable.all.min.js"></script>

<!-- Steps -->
<script src="../../assets/js/plugins/steps/jquery.steps.min.js"></script>

<!-- Jquery Validate -->
<script src="../../assets/js/plugins/validate/jquery.validate.min.js"></script>


<!-- Typehead -->
<script src="../../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>


<!-- iCheck -->
<script src="../../assets/js/plugins/iCheck/icheck.min.js"></script>

<!-- Ladda -->
<script src="../../assets/js/plugins/ladda/spin.min.js"></script>
<script src="../../assets/js/plugins/ladda/ladda.min.js"></script>
<script src="../../assets/js/plugins/ladda/ladda.jquery.min.js"></script>

                 <!-- script below is non existing to admin scripts -->

<!-- JSKnob -->
<script src="../../assets/js/plugins/jsKnob/jquery.knob.js"></script>
<!-- Data picker -->
<script src="../../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<!-- NouSlider -->
<script src="../../assets/js/plugins/nouslider/jquery.nouislider.min.js"></script>
<!-- Switchery -->
<script src="../../assets/js/plugins/switchery/switchery.js"></script>
<!-- IonRangeSlider -->
<script src="../../assets/js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
<!-- MENU -->
<script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<!-- Color picker -->
<script src="../../assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- Clock picker -->
<script src="../../assets/js/plugins/clockpicker/clockpicker.js"></script>
<!-- Image cropper -->
<script src="../../assets/js/plugins/cropper/cropper.min.js"></script>
<!-- Date range use moment.js same as full calendar plugin -->
<script src="../../assets/js/plugins/fullcalendar/moment.min.js"></script>
<!-- Date range picker -->
<script src="../../assets/js/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Select2 -->
<script src="../../assets/js/plugins/select2/select2.full.min.js"></script>
<!-- TouchSpin -->
<script src="../../assets/js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>
<!-- Tags Input -->
<script src="../../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

<!-- Dual Listbox -->
<script src="../../assets/js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script>
<!-- Password meter -->
<script src="../../assets/js/plugins/pwstrength/pwstrength-bootstrap.min.js"></script>
<script src="../../assets/js/plugins/pwstrength/zxcvbn.js"></script>
<!-- dataTables -->
<script src="../../assets/js/plugins/dataTables/datatables.min.js"></script>
<script src="../../assets/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

<!-- Notification -->
<script src="../../assets/js/pusher.min.js"></script>

<!-- Sweet Alert -->
<script src="../../assets/sweetalert2/dist/sweetalert2.all.min.js"></script>

<!-- Always Set Last -->
<!-- Denver's Custom JS -->
<script src="../../includes/js/custom.js"></script>
<script>
	const audio = new Audio('../../assets/audio/definite.mp3');
	
	$(function(){
		// Enable pusher logging - don't include this in production
		// Pusher.logToConsole = true;

		var Notif = new Pusher('6afb55a56f2b4a235c4b', {
			cluster: 'ap1',
			forceTLS: true
		});

		const title = document.querySelector('title').innerText;
		const NotifCount = document.getElementById('NotifCount');
		function titleChange(){
			if(NotifCount.innerText === ''){
				document.querySelector('title').innerText = title;
			}else{
				let count = parseFloat(NotifCount.innerText);
				let new_title = `(${count}) ${title}`;
				document.querySelector('title').innerText = new_title;
			}
		}
		titleChange();

		var Notif_channel = Notif.subscribe('notif');
		Notif_channel.bind('update', function(data){
			let msg = JSON.parse(data);
			if(msg.receiver === $('meta[name="auth"]').attr('content')){
				$('#message').remove();
				$('#NotifCount').show();
				
				if(NotifCount.innerText === ''){
					NotifCount.innerText = 1;
				}else{
					let add = parseFloat(NotifCount.innerText) + 1;
					NotifCount.innerText = (add).toFixed(0);
				}
				titleChange();
				
				if(msg.href !== undefined){
					$('#NotifList').prepend(`<li class="active"><a href="${msg.href}" class="dropdown-item"><div>
					<i class="fa fa-bell fa-fw"></i> ${msg.message}</div><small>Time: ${msg.date}</small></a></li>
					<li class="dropdown-divider"></li>`);
				}else{
					$('#NotifList').prepend(`<li class="active"><a href="#" class="dropdown-item"><div>
					<i class="fa fa-bell fa-fw"></i> ${msg.message}</div><small>Time: ${msg.date}</small></a></li>
					<li class="dropdown-divider"></li>`);
				}

				audio.play();
				toastr.options = {
					"progressBar": true,
					"preventDuplicates": false,
					"showDuration": "400",
					"hideDuration": "1000",
					"timeOut": "6000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				}
				toastr.info(msg.date, msg.message);
			}
		});

		$('#NotifClick').on('click', function(){
			SendDoSomething("POST", "../xhr-files/xhr-notif-update.php", {
				id: $('meta[name="auth"]').attr('content')
			}, {
				do: function(res){
					if(res.success){
						$('#NotifCount').hide();
						document.getElementById('NotifCount').innerText = '';
						titleChange();
					}
				}
			}, false, {
				f: function(){
					
				}
			});
		});

		$('#NotifClick').focusout(function(){
			setTimeout(function(){
				$('#NotifList li.active').removeClass('active');
			}, 300);
		});

	});
</script>

<script>
	$(function(){


			/**** DATA TABLES ****/

			// var DataTables_finishedprojects = $('#DataTables_finishedprojects').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			// buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
			// 	{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
			// 		customize: function (win){
			// 			$(win.document.body).addClass('white-bg');
			// 			$(win.document.body).css('font-size', '10px');
			// 			$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
			// 		}
			// 	}]
			// });			
			
			/**** Varying modal content ****/

			// PROFILE SETTING
			$('#profile-setting').on('show.bs.modal', function (event) {
			var editButton = $(event.relatedTarget) // Button that triggered the modal
			var request_content = editButton.data('rcontent') // Extract info from data-* attributes
			var currentUser = editButton.data('user')


			
			 var profilemodal = $(this);
				switch (request_content){
					case "name":


						profilemodal.find('.modal-title').text('Personal Information Setting: Fullname')
						var fname =  editButton.data('fname')
						var mname =  editButton.data('mname')
						var lname =  editButton.data('lname')
						var extname =  editButton.data('extname')
						var token = "<?php echo Token::generate('nametoken');?>"

							if(extname === "XXXXX"){
								extname = "";
							}
						
						$('#rcontent-container').html(`

							<form id="singleForm" method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<label>First name</label>
									<input id="fname" name="fname" type="text" value="${fname}" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Middle name</label>
									<input id="mname" name="mname" type="text" value="${mname}" class="form-control" required>
								</div>											
								<div class="form-group">
									<label>Last name</label>
									<input id="lname" name="lname" type="text" value="${lname}" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Extension name</label>
									<input id="extname" name="extname" type="text" value="${extname}" class="form-control">
								</div>
								<input name="nametoken" type="text" value="${token}" hidden>
								<input name="user" type="text" value="${currentUser}" hidden>
							
							</form>
						
						
						`);
						break;
				
					default:
						break;
				}

						
			});	
			
			// PROFILE SETTING SMALL
			$('#profile-setting-small').on('show.bs.modal', function (event) {
			var editButton = $(event.relatedTarget) // Button that triggered the modal
			var request_content = editButton.data('rcontent') // Extract info from data-* attributes
			var currentUser = editButton.data('user')


			
			 var profilemodal = $(this);
				switch (request_content){
					case "email":


						profilemodal.find('.modal-title').text('Email Address Update')
						profilemodal.find('#modal-icon-small').removeClass().addClass('far fa-envelope modal-icon');
						profilemodal.find('#modal-description-small').text('Make sure your email address is always updated for future announcements')
						var toupdate =  editButton.data('toupdate')
						var token = "<?php echo Token::generate('emailtoken');?>"

						
						$('#rcontent-container-small').html(`
							<form id="singleForm1" method="POST" enctype="multipart/form-data">
								<div class="form-group"><label>Email Address</label>
									
										<div class="input-group m-b">
												<div class="input-group-prepend">
													<span class="input-group-addon">@</span>
												</div>
												<input type="email" name="newemail" value="${toupdate}" placeholder="Enter your new email" class="form-control" required>
										</div>
								</div>
								<input name="emailtoken" type="text" value="${token}" hidden>
								<input name="user" type="text" value="${currentUser}" hidden>
							</form>
						`);
						break;
						
					case "phone":


						profilemodal.find('.modal-title').text('Phone Number Update')
						profilemodal.find('#modal-icon-small').removeClass().addClass('fas fa-phone modal-icon');
						profilemodal.find('#modal-description-small').text('Make sure your phone number is always updated to receive all important updates')
						var toupdate =  editButton.data('toupdate')
						

						
						var token = "<?php echo Token::generate('phonetoken');?>"

						
						$('#rcontent-container-small').html(`
							<form id="singleForm1" method="POST" enctype="multipart/form-data">
								<div class="form-group"><label>Phone Number</label>
									
										<div class="input-group m-b">
												<div class="input-group-prepend">
													<span class="input-group-addon">+63</span>
												</div>
												<input type="text" min="10" max="10" name="newphone" value="${toupdate}" data-mask="9999999999" placeholder="Enter your new phone number" class="form-control" required>
												
										</div>
								</div>
								<input name="phonetoken" type="text" value="${token}" hidden>
								<input name="user" type="text" value="${currentUser}" hidden>
							</form>
						`);
						break;
					case "designation":


						profilemodal.find('.modal-title').text('Designation / College Unit Update')
						profilemodal.find('#modal-icon-small').removeClass().addClass('fas fa-institution modal-icon');
						profilemodal.find('#modal-description-small').text('Office or College Unit you are under')
						var toupdate =  editButton.data('toupdate')
						var collegelist = `
							
							<?php
								
								if($units = $user->selectAll("units")){
									foreach($units as $unit){
										echo '
												<option value="'.$unit->ID.'">'.$unit->office_name.'</option>
											
										';
									}
								}
							
							?>
						
						`
						
						
						var token = "<?php echo Token::generate('designationtoken');?>"

						
						$('#rcontent-container-small').html(`
							<form id="singleForm1" method="POST" enctype="multipart/form-data">
								<div class="form-group"><label>Designation</label>
									
												<select class="form-control m-b required" name="newunit" required="" >
														<option value="${toupdate}"> Select... </option>
														${collegelist}
												</select>
								</div>
								<input name="designationtoken" type="text" value="${token}" hidden>
								<input name="user" type="text" value="${currentUser}" hidden>
							</form>
						`);
						break;
					case "office":


						profilemodal.find('.modal-title').text('Specific Office Update')
						profilemodal.find('#modal-icon-small').removeClass().addClass('fas fa-briefcase modal-icon');
						profilemodal.find('#modal-description-small').text('Specific office under your designation or college unit')
						var toupdate =  editButton.data('toupdate')
						var token = "<?php echo Token::generate('specificofficetoken');?>"

						
						$('#rcontent-container-small').html(`
							<form id="singleForm1" method="POST" enctype="multipart/form-data">
								<div class="form-group"><label>Specific Office</label>
									
										<div class="input-group m-b">
												<div class="input-group-prepend">
													<span class="input-group-addon"><i class="fas fa-briefcase"></i></span>
												</div>
												<input type="text" name="newspecificoffice" value="${toupdate}" placeholder="Specific Office" class="form-control" required>
										</div>
								</div>
								<input name="specificofficetoken" type="text" value="${token}" hidden>
								<input name="user" type="text" value="${currentUser}" hidden>
							</form>
						`);
						break;
					case "username":


						profilemodal.find('.modal-title').text('Username Update')
						profilemodal.find('#modal-icon-small').removeClass().addClass('fas fa-user modal-icon');
						profilemodal.find('#modal-description-small').text('')
						var toupdate =  editButton.data('toupdate')
						var token = "<?php echo Token::generate('usernametoken');?>"

						
						$('#rcontent-container-small').html(`
							<form id="singleForm1" method="POST" enctype="multipart/form-data">
								<div class="form-group"><label>New Username</label>
									
										<div class="input-group m-b">
												<div class="input-group-prepend">
													<span class="input-group-addon"><i class="fas fa-user-shield"></i></span>
												</div>
												<input type="text" name="newusername" value="${toupdate}" placeholder="Enter your new username" class="form-control" required>
										</div>
								</div>
								<input name="usernametoken" type="text" value="${token}" hidden>
								<input name="user" type="text" value="${currentUser}" hidden>
							</form>
						`);
						break;
					case "password":


						profilemodal.find('.modal-title').text('Password Update')
						profilemodal.find('#modal-icon-small').removeClass().addClass('fas fa-lock modal-icon');
						profilemodal.find('#modal-description-small').text('It is adviced to regularly update your password')
						var toupdate =  editButton.data('toupdate')
						var token = "<?php echo Token::generate('newpasstoken');?>"

						
						$('#rcontent-container-small').html(`
							<form id="singleForm1" method="POST" enctype="multipart/form-data">
								<div class="form-group"><label>Current Password</label>
									
										<div class="input-group m-b">
												<div class="input-group-prepend">
													<span class="input-group-addon"><i class="fas fa-unlock"></i></span>
												</div>
												<input type="password" id="currentpassword" name="currentpassword" value="" placeholder="Current password" class="form-control" required>
										</div>
								</div>
								<div class="form-group"><label>New Password</label>
									
										<div class="input-group m-b">
												<div class="input-group-prepend">
													<span class="input-group-addon"><i class="fas fa-key"></i></span>
												</div>
												<input type="password" id="newpassword" name="newpassword" value="" placeholder="New password" class="form-control" required>
										</div>
								</div>
								<div class="form-group">
									
										<div class="input-group m-b">
												<div class="input-group-prepend">
													<span class="input-group-addon"><i class="fas fa-key"></i></span>
												</div>
												<input type="password" id="" name="passwordagain" value="" placeholder="Re-type new password" class="form-control" required>
										</div>
								</div>														
								<input name="newpasstoken" type="text" value="${token}" hidden>
								<input name="user" type="text" value="${currentUser}" hidden>
							</form>
						`);
						break;						
				
					default:
						break;
				}

						
			});


	});

</script>

<script>
    $(document).ready(function(){
		// side nav active	
		try {
			var path = window.location.pathname.split("/");
			var link = document.querySelector(`[href='${path[path.length - 1]}']`);
			var sLink = ['Dashboard'];
			var higherLevelpages = [
				{pages: ['project-details'], link: 'current-projects'}
			];
			
			var highlevelpage = higherLevelpages.find(function(e1){
				return e1.pages.find(function(e2){
					return e2 === path[path.length - 1]
				});
			});

			if(highlevelpage !== undefined){
				link = document.querySelector(`[href="${highlevelpage.link}"]`);
			}

			if(path[path.length - 1] !== "profile"){
				switch (path[path.length - 1]){
					case sLink.find(function(el){
						return path[path.length - 1] === el
					}):
						link.parentNode.setAttribute("class", "active");
						break;
					default:
						link.parentNode.parentNode.parentNode.setAttribute("class", "active");
						link.parentNode.parentNode.setAttribute("class", "nav nav-second-level collapse in")
						link.parentNode.setAttribute("class", "active");
						break;
				}
			}
		} catch (error) {
			
		}	

        $("#wizard").steps();
        $("#form").steps({
            bodyTag: "fieldset",
            onStepChanging: function (event, currentIndex, newIndex)
            {
                // Always allow going backward even if the current step contains invalid fields!
                if (currentIndex > newIndex)
                {
                    return true;
                }

                // Forbid suppressing "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age").val()) < 18)
                {
                    return false;
                }

                var form = $(this);

                // Clean up if user went backward before
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    $(".body:eq(" + newIndex + ") label.error", form).remove();
                    $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                }

                // Disable validation on fields that are disabled or hidden.
                form.validate().settings.ignore = ":disabled,:hidden";

                // Start validation; Prevent going forward if false
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {
                // Suppress (skip) "Warning" step if the user is old enough.
                if (currentIndex === 2 && Number($("#age").val()) >= 18)
                {
                    $(this).steps("next");
                }

                // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                if (currentIndex === 2 && priorIndex === 3)
                {
                    $(this).steps("previous");
                }
            },
            onFinishing: function (event, currentIndex)
            {
                var form = $(this);

                // Disable validation on fields that are disabled.
                // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                form.validate().settings.ignore = ":disabled";

                // Start validation; Prevent form submission if false
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                var form = $(this);

                // Submit form input
                form.submit();
            }
        }).validate({
                    errorPlacement: function (error, element)
                    {
                        element.before(error);
                    },
                    rules: {
                        confirm: {
                            equalTo: "#password"
                        }
                    }
                });

				
		
    });
	

</script>

    <script>
        $(document).ready(function(){

            $('.tagsinput').tagsinput({
                tagClass: 'label label-primary'
            });

            var $image = $(".image-crop > img")
            $($image).cropper({
                aspectRatio: 1.618,
                preview: ".img-preview",
                done: function(data) {
                    // Output the result data for cropping image.
                }
            });

            var $inputImage = $("#inputImage");
            if (window.FileReader) {
                $inputImage.change(function() {
                    var fileReader = new FileReader(),
                            files = this.files,
                            file;

                    if (!files.length) {
                        return;
                    }

                    file = files[0];

                    if (/^image\/\w+$/.test(file.type)) {
                        fileReader.readAsDataURL(file);
                        fileReader.onload = function () {
                            $inputImage.val("");
                            $image.cropper("reset", true).cropper("replace", this.result);
                        };
                    } else {
                        showMessage("Please choose an image file.");
                    }
                });
            } else {
                $inputImage.addClass("hide");
            }

            $("#download").click(function() {
                window.open($image.cropper("getDataURL"));
            });

            $("#zoomIn").click(function() {
                $image.cropper("zoom", 0.1);
            });

            $("#zoomOut").click(function() {
                $image.cropper("zoom", -0.1);
            });

            $("#rotateLeft").click(function() {
                $image.cropper("rotate", 45);
            });

            $("#rotateRight").click(function() {
                $image.cropper("rotate", -45);
            });

            $("#setDrag").click(function() {
                $image.cropper("setDragMode", "crop");
            });

            var mem = $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            var yearsAgo = new Date();
            yearsAgo.setFullYear(yearsAgo.getFullYear() - 20);

            $('#selector').datepicker('setDate', yearsAgo );


            $('#data_2 .input-group.date').datepicker({
                startView: 1,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "dd/mm/yyyy"
            });

            $('#data_3 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });

            $('#data_4 .input-group.date').datepicker({
                minViewMode: 1,
                keyboardNavigation: false,
                forceParse: false,
                forceParse: false,
                autoclose: true,
                todayHighlight: true
            });

            $('#data_5 .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });

            var elem = document.querySelector('.js-switch');
            var switchery = new Switchery(elem, { color: '#1AB394' });

            var elem_2 = document.querySelector('.js-switch_2');
            var switchery_2 = new Switchery(elem_2, { color: '#ED5565' });

            var elem_3 = document.querySelector('.js-switch_3');
            var switchery_3 = new Switchery(elem_3, { color: '#1AB394' });

            var elem_4 = document.querySelector('.js-switch_4');
            var switchery_4 = new Switchery(elem_4, { color: '#f8ac59' });
                switchery_4.disable();

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

            $('.demo1').colorpicker();

            var divStyle = $('.back-change')[0].style;
            $('#demo_apidemo').colorpicker({
                color: divStyle.backgroundColor
            }).on('changeColor', function(ev) {
                        divStyle.backgroundColor = ev.color.toHex();
                    });

            $('.clockpicker').clockpicker();

            $('input[name="daterange"]').daterangepicker();

            $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

            $('#reportrange').daterangepicker({
                format: 'MM/DD/YYYY',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2015',
                dateLimit: { days: 60 },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'right',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-primary',
                cancelClass: 'btn-default',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            });

            $(".select2_demo_1").select2();
            $(".select2_demo_2").select2();
            $(".select2_demo_3").select2({
                placeholder: "Select a state",
                allowClear: true
            });


            $(".touchspin1").TouchSpin({
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $(".touchspin2").TouchSpin({
                min: 0,
                max: 100,
                step: 0.1,
                decimals: 2,
                boostat: 5,
                maxboostedstep: 10,
                postfix: '%',
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $(".touchspin3").TouchSpin({
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('.dual_select').bootstrapDualListbox({
                selectorMinimalHeight: 160
            });

			


				$('[data-toggle="tooltip"]').tooltip();
				var actions = $("table td:last-child").html();
				// Append table with add row form on add new button click
				$(".add-new").click(function(){
					$(this).attr("disabled", "disabled");
					var index = $("table tbody tr:last-child").index();
					var row = '<tr>' +
						'<td><input type="text" class="form-control" name="name" id="name"></td>' +
						'<td><input type="text" class="form-control" name="department" id="department"></td>' +
						'<td><input type="text" class="form-control" name="phone" id="phone"></td>' +
						'<td><input type="text" class="form-control" name="something" id="something"></td>' +
						'<td>' + actions + '</td>' +
					'</tr>';
					$("table").append(row);		
					$("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
					$('[data-toggle="tooltip"]').tooltip();
				});
				// Add row on add button click
				$(document).on("click", ".add", function(){
					var empty = false;
					var input = $(this).parents("tr").find('input[type="text"]');
					input.each(function(){
						if(!$(this).val()){
							$(this).addClass("error");
							empty = true;
						} else{
							$(this).removeClass("error");
						}
					});
					$(this).parents("tr").find(".error").first().focus();
					if(!empty){
						input.each(function(){
							$(this).parent("td").html($(this).val());
						});			
						$(this).parents("tr").find(".add, .edit").toggle();
						$(".add-new").removeAttr("disabled");
					}		
				});
				// Edit row on edit button click
				$(document).on("click", ".edit", function(){		
					$(this).parents("tr").find("td:not(:last-child)").each(function(){
						$(this).html('<input type="text" class="form-control" value="' + $(this).text() + '">');
					});		
					$(this).parents("tr").find(".add, .edit").toggle();
					$(".add-new").attr("disabled", "disabled");
				});
				// Delete row on delete button click
				$(document).on("click", ".delete", function(){
					$(this).parents("tr").remove();
					$(".add-new").removeAttr("disabled");
				});


        });

        $('.chosen-select').chosen({width: "100%"});

        $("#ionrange_1").ionRangeSlider({
            min: 0,
            max: 5000,
            type: 'double',
            prefix: "$",
            maxPostfix: "+",
            prettify: false,
            hasGrid: true
        });

        $("#ionrange_2").ionRangeSlider({
            min: 0,
            max: 10,
            type: 'single',
            step: 0.1,
            postfix: " carats",
            prettify: false,
            hasGrid: true
        });

        $("#ionrange_3").ionRangeSlider({
            min: -50,
            max: 50,
            from: 0,
            postfix: "°",
            prettify: false,
            hasGrid: true
        });

        $("#ionrange_4").ionRangeSlider({
            values: [
                "January", "February", "March",
                "April", "May", "June",
                "July", "August", "September",
                "October", "November", "December"
            ],
            type: 'single',
            hasGrid: true
        });

        $("#ionrange_5").ionRangeSlider({
            min: 10000,
            max: 100000,
            step: 100,
            postfix: " km",
            from: 55000,
            hideMinMax: true,
            hideFromTo: false
        });

        $(".dial").knob();

        var basic_slider = document.getElementById('basic_slider');

        noUiSlider.create(basic_slider, {
            start: 40,
            behaviour: 'tap',
            connect: 'upper',
            range: {
                'min':  20,
                'max':  80
            }
        });

        var range_slider = document.getElementById('range_slider');

        noUiSlider.create(range_slider, {
            start: [ 40, 60 ],
            behaviour: 'drag',
            connect: true,
            range: {
                'min':  20,
                'max':  80
            }
        });

        var drag_fixed = document.getElementById('drag-fixed');

        noUiSlider.create(drag_fixed, {
            start: [ 40, 60 ],
            behaviour: 'drag-fixed',
            connect: true,
            range: {
                'min':  20,
                'max':  80
            }
        });


    </script>
	
    <script>
		$(document).ready(function () {
			$('#popOver').popover();
			$('#popOver1').popover();
			$('#popOver2').popover();
			$('.footable').footable();
            $('.footable2').footable();


			if(newuser == true){
				$('#new-user-modal').modal('show');	
			}     
            
            $('input').focus(function(){
			  $(this).parents('.form-group').addClass('focused');
			});

			$('input').blur(function(){
			  var inputValue = $(this).val();
			  if ( inputValue == "" ) {
				$(this).removeClass('filled');
				$(this).parents('.form-group').removeClass('focused');  
			  } else {
				$(this).addClass('filled');
			  }
			})  

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


			// toust toggle

			<?php
			
				// FATAL ERROR NOTIFICATIONS
				if(Session::exists("FATAL_ERROR")){
					
					echo '
						audio.play();
						toastr.options = {
						"closeButton": true,
						"debug": true,
						"progressBar": false,
						"preventDuplicates": false,
						"positionClass": "toast-top-full-width",
						"onclick": null,
						"showDuration": "400",
						"hideDuration": "1000",
						"timeOut": "60000",
						"extendedTimeOut": "60000",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
						}
						toastr.error("'.Session::flash("FATAL_ERROR").'", "Fatal Error");
					
					';				
					
				}
				// VALIDATION ERRORS

				if(isset($validation)){
					if($validation->errors()){
						$default_time_out = 20000;
						foreach ($validation->errors() as $error_type => $error_message) {
								
								echo '
									audio.play();
									toastr.options = {
									"closeButton": true,
									"debug": true,
									"progressBar": true,
									"preventDuplicates": false,
									"positionClass": "toast-top-full-width",
									"onclick": null,
									"showDuration": "400",
									"hideDuration": "1000",
									"timeOut": "'.$default_time_out.'",
									"extendedTimeOut": "10000",
									"showEasing": "swing",
									"hideEasing": "linear",
									"showMethod": "fadeIn",
									"hideMethod": "fadeOut"
									}
									toastr.warning("'.$error_message.'", "'.$error_type.'");
								
								';
								$default_time_out += 5000;			
						}
					}

				}

				
				// SUCCESS NOTIFICATIONS

				if(isset($success_notifs)){
						
						foreach ($success_notifs as $notif) {
								
								echo '
									audio.play();
									toastr.options = {
										"progressBar": true,
										"preventDuplicates": false,
										"showDuration": "400",
										"hideDuration": "1000",
										"timeOut": "6000",
										"extendedTimeOut": "1000",
										"showEasing": "swing",
										"hideEasing": "linear",
										"showMethod": "fadeIn",
										"hideMethod": "fadeOut"
									}
									toastr.info("'.$notif.'", "Success");
								
								';
						}			

				}			
			
			?>
	

			
		});

	</script>	

	<script>
// Back to top
var amountScrolled = 200;
var amountScrolledNav = 25;

$(window).scroll(function() {
  if ( $(window).scrollTop() > amountScrolled ) {
    $('button.back-to-top').addClass('show');
  } else {
    $('button.back-to-top').removeClass('show');
  }
});

$('button.back-to-top').click(function() {
  $('html, body').animate({
    scrollTop: 0
  }, 800);
  return false;
});

// Ignore this
// This is just for content manipulation
var skeleton = '<div class="skeleton"><div class="skeleton-wrapper"><div class="skeleton-wrapper-inner"><div class="skeleton-wrapper-body"><div class="skeleton-avatar"></div><div class="skeleton-author"></div><div class="skeleton-label"></div><div class="skeleton-content-1"></div><div class="skeleton-content-2"></div><div class="skeleton-content-3"></div></div></div></div></div>';
for(var i=0;i<10;i++){
  $('#content').append(skeleton); 
}

// Add waves effect
Waves.attach('button.back-to-top', 'waves-effect');
Waves.init();
</script>