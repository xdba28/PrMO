<?php 
include_once '../../includes/parts/modals.php'; 
require_once "../../functions/account-verifier.php";
?>



<!-- ***********************************Mainly scripts/Defaults*************************************** -->
<script src="../../assets/js/jquery-3.1.1.min.js"></script>
<script src="../../assets/js/popper.min.js"></script>
<script src="../../assets/js/bootstrap.js"></script>
<!-- Custom and plugin javascript -->
<script src="../../assets/js/inspinia.js"></script>
<script src="../../assets/js/plugins/pace/pace.min.js"></script>
<script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>


<!-- ***********************************FROM FORM ADVANCE RESOURCES*************************************** --> 

<!-- Menu -->
<script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<!-- Chosen -->
<script src="../../assets/js/plugins/chosen/chosen.jquery.js"></script>
<!-- Input Mask-->
<script src="../../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../assets/js/plugins/iCheck/icheck.min.js"></script>
<!-- Tags Input -->
<script src="../../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<!-- Date range use moment.js same as full calendar plugin --><!-- Date range picker --><!-- Data picker -->
<script src="../../assets/js/plugins/fullcalendar/moment.min.js"></script>
<script src="../../assets/js/plugins/daterangepicker/daterangepicker.js"></script>
<script src="../../assets/js/plugins/cropper/cropper.min.js"></script>
<script src="../../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- Dual Listbox -->
<script src="../../assets/js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script>
<!-- Notification -->
<script src="../../assets/js/pusher.min.js"></script>



<!-- ***********************************FORM ADVANCE RESOURCES******************************************** --> 

<!-- Color picker -->
<!-- <script src="js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script> -->
<!-- Clock picker -->
<!-- <script src="js/plugins/clockpicker/clockpicker.js"></script -->
<!--  Data picker -->
<!-- <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script> -->
<!-- NouSlider -->
<!-- <script src="js/plugins/nouslider/jquery.nouislider.min.js"></script> -->
<!-- Switchery -->
<!-- <script src="js/plugins/switchery/switchery.js"></script> -->
<!-- IonRangeSlider -->
<!-- <script src="js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script> -->
<!-- JSKnob -->
<!-- <script src="js/plugins/jsKnob/jquery.knob.js"></script> -->
<!-- Select2 -->
<!-- <script src="js/plugins/select2/select2.full.min.js"></script> -->
<!-- TouchSpin -->
<!-- <script src="js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script> -->




<!-- ***********************WALA SA FORM ADVANCE ******************************** -->
<!-- dataTables -->
<script src="../../assets/js/plugins/dataTables/datatables.min.js"></script>
<script src="../../assets/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<!-- Sweet Alert -->
<script src="../../assets/sweetalert2/dist/sweetalert2.all.min.js"></script>
<!-- Crypto-js -->
<script src="../../assets/js/plugins/crypto-js/crypto-js.js"></script>
<!-- Ladda -->
<script src="../../assets/js/plugins/ladda/spin.min.js"></script>
<script src="../../assets/js/plugins/ladda/ladda.min.js"></script>
<script src="../../assets/js/plugins/ladda/ladda.jquery.min.js"></script>
<!-- Steps -->
<script src="../../assets/js/plugins/steps/jquery.steps.min.js"></script>
<!-- Jquery Validate -->
<script src="../../assets/js/plugins/validate/jquery.validate.min.js"></script>
<!-- Typehead -->
<script src="../../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>
<!-- Toastr -->
<script src="../../assets/js/plugins/toastr/toastr.min.js"></script>
<!-- FooTable -->
<script src="../../assets/js/plugins/footable/footable.all.min.js"></script>
<!-- Peity -->
<!-- <script src="../../assets/js/plugins/peity/jquery.peity.min.js"></script>
<script src="../../assets/js/demo/peity-demo.js"></script> -->


<!-- **********************************EXTERNAL**********************************-->
<script src="../../assets/dropify/js/dropify.min.js"></script>

<!-- Always Set Last --> 
<!-- Denver's Custom JS -->
<script src="../../includes/js/custom.js"></script>
<script>
	$(function(){
		// Enable pusher logging - don't include this in production
		Pusher.logToConsole = true;

		var Notif = new Pusher('6afb55a56f2b4a235c4b', {
			cluster: 'ap1',
			forceTLS: true
		});

		var Notif_channel = Notif.subscribe('notif');
		Notif_channel.bind('update', function(data){
			let msg = JSON.parse(data);
			if(msg.receiver === $('meta[name="auth"]').attr('content')){
				let NotifCount = document.getElementById('NotifCount');
				let add = parseFloat(NotifCount.innerText) + 1;
				NotifCount.innerText = (add).toFixed(0);
				$('#NotifList').append(`<li><a href="#" class="dropdown-item"><div>
					<i class="fa fa-envelope fa-fw"></i> ${msg.message}</div></a></li>
					<li class="dropdown-divider"></li>`);


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
				toastr.info(msg.message);
			}
		});
	});
</script>

    <script>
		// CUSTOM GLOBAL SCRIPTS
		$(function(){
			// side nav active
			var path = window.location.pathname.split("/");
			var link = document.querySelector(`[href='${path[path.length - 1]}']`);
			var sLink = ['Dashboard', 'Calendar'];
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
		
			// modal
			$('[log="upd"]').on('click', function(){
				var updLog = [];
				var LogType = $(this).attr("action");
				$('[name="updOutLog[]"]:checked').each(function(){
					updLog.push($(this).attr("id"));
				});
				if(updLog.length !== 0){
					$('[modal="RelOut"]').trigger('click');
					switch(LogType){
						case "4":
							swal({
								title: "Delivery Fail",
								text: "Add remarks to this report for a reliable referencing.",
								type: "warning",
								showCancelButton: true,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Update",
								allowOutsideClick: false
							}).then(function(res){
								if(res.value){
									sweet({
										title: 'Remarks of Log',
										type: "info",
										showCancelButton: true,
										confirmButtonText: "Submit",
										allowOutsideClick: false,
										html: `
										<input type="text" name="LogRem" list="reason" placeholder="type of choose your reason">
										<datalist id="reason">
											<option value="office unattended">
										</datalist>
										`,
										focusConfirm: false,
										preConfirm: function(){
											return document.querySelector('[name="LogRem"]').value
										}
									}, {
										do: function(res){
											if(res.dismiss === "cancel"){
												swal({
													title: "Action dismissed.",
													text: "",
													type: "info"
												});
											}else if(res.value !== "undefined"){
												SendDoNothing("POST", "../xhr-files/staff-aid-upd-log.php", {
													outgoing: updLog,
													action: LogType,
													remarks: res.value
												}, {
													title: "Success!",
													text: "Document(s) successfully updated."
												});
											}
										}
									});
								}else{
									swal({
										title: "Action dismissed.",
										text: "",
										type: "info"
									});
								}
							});
							break;
						default:
							sweet({
								title: "Action: " + this.innerText,
								text: "Are you sure with this action?",
								type: "question",
								showCancelButton: true,
								confirmButtonText: "Proceed",
								allowOutsideClick: false
							}, {
								do: function(res){
									if(res.dismiss === "cancel"){
										swal({
											title: "Action dismissed.",
											text: "",
											type: "info"
										});
									}else if(res.value !== "undefined"){
										SendDoNothing("POST", "../xhr-files/staff-aid-upd-log.php", {
											outgoing: updLog,
											action: LogType,
											remarks: res
										}, {
											title: "Success!",
											text: "Document(s) successfully updated."
										});
									}
								}
							});
							break;
					}
				}else{
					$('[modal="RelOut"]').trigger('click');
					swal({
						title: "No selected document!",
						text: "Please select a document.",
						type: "error",
						confirmButtonColor: "#DD6B55"
					});
				}
			});
			// modal
		
			//available actions modal
			$('#actionsModal').on('show.bs.modal', function (event) {
				var OutGoingProjectModalBody = document.getElementById('OutGoingProjectModal');
				OutGoingProjectModalBody.innerHTML = "";
				$('[dataFor="OutGoingProjectModal"]').toggleClass('sk-loading');
				var button = $(event.relatedTarget) // Button that triggered the modal
				var reference = button.data('reference') // Extract info from data-* attributes
				// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
				// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

				SendDoSomething("POST", "../xhr-files/xhr-show-actions.php", {
					ref:reference
				}, {
					do:function(res){
						var availableActions = res.fetchedResult;
						var classtype = "";
						var icon = "";
						var cardAction = "";

						for(let i of availableActions){

							switch (i) {
								case "Return to Enduser due to incompliance":
									classtype = "red-bg";
									icon = "fas fa-exclamation-triangle";
									//cardAction = `data-toggle="modal" data-target="#pre-proc-evaluation"`;
									break;

								case "Register Pre-procurement Evaluation result":
									classtype = "lazur-bg";
									icon = "fas fa-chess-pawn";
									cardAction = `data-toggle="modal" data-target="#pre-proc-evaluation" data-dismiss="modal"`;
									break;
								case "Update Released Document from Technical Member":
									classtype = "lazur-bg";
									icon = "fas fa-chess-pawn";
									cardAction = `data-toggle="modal" data-target="#returning" data-dismiss="modal"`;
									break;
							
								default:
									classtype = "lazur-bg";
									icon = "fas fa-chess-pawn";
									break;
							}
							
							OutGoingProjectModalBody.innerHTML += `
							<a id="actionCard" ${cardAction}>
								<div class="widget style1 ${classtype}">
									<div class="row">
										<div class="col-4">
											<i class="${icon} fa-5x"></i>
										</div>
										<div class="col-8 text-right">
											<span><h3>${i}</h3></span>
										</div>
									</div>
								</div>
							</a>`;
						}
						$('[dataFor="OutGoingProjectModal"]').toggleClass('sk-loading');
					}
				}, false, {
					f: function(){
						$('[dataFor="OutGoingProjectModalClose"]').trigger('click');
						$('[dataFor="OutGoingProjectModal"]').toggleClass('sk-loading');
						swal({
							title: "An error occurred!",
							text: "Cannot send data.",
							type: "error"
						});
					}
				});
				var modal = $(this);
				modal.find('.modal-title').text('Available Actions to Project ' + reference);
				//modal.find('.modal-body input').val(reference);
				document.getElementById("projectReference").value = reference;
			});
		
			
			//outgoing documents table collapse all div
			setTimeout(function(){
				$('#t0').trigger('click');
				$('#t1').trigger('click');
				$('#t2').trigger('click');
			},700);

			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});

			var twg_ck = false;
			$('[btn-t="twg"]').on('ifChanged', function(event){
				if(twg_ck){
					$('[data="twg"]').iCheck('uncheck');
					$('[btn-t="twg"]').iCheck('uncheck')
					twg_ck = false;
				}else{
					$('[data="twg"]').iCheck('check');
					$('[btn-t="twg"]').iCheck('check')
					twg_ck = true;
				}
			});

			var out = false
			$('[btn-t="out"]').on('ifChanged', function(event){
				if(out){
					$('[data="out"]').iCheck('uncheck');
					$('[btn-t="out"]').iCheck('uncheck');
					out = false;
				}else{
					$('[data="out"]').iCheck('check');
					$('[btn-t="out"]').iCheck('check');
					out = true;
				}
			});

			var gen = false;
			$('[btn-t="gen"]').on('ifChanged', function(event){
				if(gen){
					$('[data="gen"]').iCheck('uncheck');
					$('[btn-t="gen"]').iCheck('uncheck');
					gen = false;
				}else{
					$('[data="gen"]').iCheck('check');
					$('[btn-t="gen"]').iCheck('check');
					gen = true;
				}
				
			});	

			var updOut = false;
			$('[btn-t="updOutChk"]').on('ifChanged', function(event){
				if(updOut){
					$('[data="upLog"]').iCheck('uncheck');
					$('[btn-t="updOutChk"]').iCheck('uncheck');
					updOut = false;
				}else{
					$('[data="upLog"]').iCheck('check');
					$('[btn-t="updOutChk"]').iCheck('check');
					updOut = true;
				}
			});

			var tst_rdy = '<?php 
				if(Session::exists('toust')) echo Session::flash('toust');
				else echo "0";
			?>';

			if(tst_rdy !== "0")
			toastr.success(tst_rdy);

		});
		// END OF CUSTOM GLOBAL SCRIPTS
		</script>


		<script>
        $(document).ready(function(){

			Dropzone.options.dropzoneForm = {
				paramName: "file", // The name that will be used to transfer the file
				maxFilesize: 2, // MB
				dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)"
			};
            var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code2"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code3"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code4"), {
                lineNumbers: true,
                matchBrackets: true
            });


            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

       });
    </script>

    <script> //wizard
        $(document).ready(function(){
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
	
	<script> //dropify
	$(function() {
		$('.dropify').dropify();

		var drEvent = $('#dropify-event').dropify();
		drEvent.on('dropify.beforeClear', function(event, element) {
			return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
		});

		drEvent.on('dropify.afterClear', function(event, element) {
			alert('File deleted');
		});

		$('.dropify-fr').dropify({
			messages: {
				default: 'Glissez-déposez un fichier ici ou cliquez',
				replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
				remove: 'Supprimer',
				error: 'Désolé, le fichier trop volumineux'
			}
		});
	});
	</script>
	
	<script> //search modal animation timer
		function refreshPage(){
			window.location.reload();
		} 

		$('#search').on('click', function(){
			setTimeout(function(){
					$('#RefSearch').trigger('click');

			},500);
		});
	</script>	
	
	<script> //document.ready
		$(document).ready(function () {
			$('#popOver0').popover();
			$('#popOver1').popover();
			$('#popOver2').popover();
			$('#popOver3').popover();
			$('#popOver4').popover();
			$('#popOver5').popover();
			$('#popOver6').popover();
			$('#popOver7').popover();
			$('#popOver8').popover();
			$('#popOver9').popover();
			$('#popOver10').popover();
			$('#popOver11').popover();
			$('#popOver12').popover();
			$('#popOver13').popover();
			$('#popOver14').popover();
			
            $('input[name="daterange"]').daterangepicker();

			if(newuser == true){
				$('#new-user-modal').modal('show');
				
			}

            $('.tagsinput').tagsinput({
                tagClass: 'label label-primary'
            });

			$('.footable').footable();
			$('.footable2').footable();
			
			$("#typeahead").typeahead({
				source: ["Job Order","Procurement Aid","Head Secretariat", "Director", "Staff"]
			});
		

        

        $('.chosen-select').chosen({width: "100%"});

			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
			});
					
			$('input').focus(function(){
			  $(this).parents('.form-group').addClass('focused');
			});

			//google like input
			$('input').blur(function(){
			  var inputValue = $(this).val();
			  if ( inputValue == "" ) {
				$(this).removeClass('filled');
				$(this).parents('.form-group').removeClass('focused');  
			  } else {
				$(this).addClass('filled');
			  }
			});



		});

	</script>

	<script>
		var DataTables_DocUpdate = null;
		$(function(){
			DataTables_DocUpdate = $('#DataTables_DocUpdate').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
				{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
					}
				}]
			});
		});
	</script>

	<script> //search script
		$(function(){
			$('#FormSearchModal').submit(function(e) {
				e.preventDefault();
				if ($(this).hasClass('active')) 
				$(this).removeClass('active');
			});


			$('.search span').click(function(e) {
				
				var $parent = $(this).parent();

				if (!$parent.hasClass('active')) {
				
				$parent
					.addClass('active')
					.find('input:first')
					.on('blur', function() {
					if (!$(this).val().length) $parent.removeClass('active');
					}
				);
				
				}
			});
		});
	</script>
	
    <script> //form advance script
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


        });
	</script>

	<script>
		// $(function(){
		//     var elem = document.querySelector('.js-switch');
        //     var switchery = new Switchery(elem, { color: '#1AB394' });

        //     var elem_2 = document.querySelector('.js-switch_2');
        //     var switchery_2 = new Switchery(elem_2, { color: '#ED5565' });

        //     var elem_3 = document.querySelector('.js-switch_3');
        //     var switchery_3 = new Switchery(elem_3, { color: '#1AB394' });

        //     var elem_4 = document.querySelector('.js-switch_4');
        //     var switchery_4 = new Switchery(elem_4, { color: '#f8ac59' });
        //         switchery_4.disable();
		// });
	</script>
	
	<script>
		$(function(){

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
		});

    </script>
