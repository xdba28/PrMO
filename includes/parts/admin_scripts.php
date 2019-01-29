<?php 
include_once '../../includes/parts/modals.php'; 
require_once "../../functions/account-verifier.php";
?>



<!-- ***********************************Mainly scripts/Defaults*************************************** -->
<script src="../../assets/js/jquery-3.1.1.min.js"></script>
<script src="../../assets/js/popper.min.js"></script>
<script src="../../assets/js/bootstrap.js"></script>
<script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<script src="../../assets/js/inspinia.js"></script>
<script src="../../assets/js/plugins/pace/pace.min.js"></script>



<!-- ***********************************FROM FORM ADVANCE RESOURCES*************************************** --> 


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


<!-- DROPZONE -->
<!-- <script src="../../assets/js/plugins/dropzone/dropzone.js"></script> -->
<!-- CodeMirror -->
<!-- <script src="../../assets/js/plugins/codemirror/codemirror.js"></script> -->
<!-- <script src="../../assets/js/plugins/codemirror/mode/xml/xml.js"></script> -->


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
<!-- <script src="../../assets/dropify/js/dropify.min.js"></script> -->


<!-- Always Set Last --> 
<!-- Denver's Custom JS -->
<script src="../../includes/js/custom.js"></script>
<script>
	const audio = new Audio('../../assets/audio/definite.mp3');
	// const audio = new Audio('../../assets/audio/Badger Scream.mp3');

	$(function(){
		// Enable pusher logging - don't include this in production
		// Pusher.logToConsole = true;

		var Notif = new Pusher('6afb55a56f2b4a235c4b', {
			cluster: 'ap1',
			forceTLS: true
		});

		var Notif_channel = Notif.subscribe('notif');
		Notif_channel.bind('admin', function(data){
			let msg = JSON.parse(data);
			if(msg.receiver === $('meta[name="auth"]').attr('content') || msg.receiver === $('meta[name="group"]').attr('content')){
				$('#message').remove();
				$('#NotifCount').show();
				let NotifCount = document.getElementById('NotifCount');
				if(NotifCount.innerText === ''){
					NotifCount.innerText = 1;
				}else{
					let add = parseFloat(NotifCount.innerText) + 1;
					NotifCount.innerText = (add).toFixed(0);
				}
				
				if(typeof msg.href !== 'undefined'){
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
					}
				}
			}, false, {
				f: function(){}
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
	// outgoing data tables
	const DataTable_Twg = $('#DataTable_Twg').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
		buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
				}
			}]
	});

	const DataTable_Signiture = $('#DataTable_Signiture').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
		buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
				}
			}]
	});

	const DataTable_GenDoc = $('#DataTable_GenDoc').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
		buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
				}
			}]
	});

	const DataTables_DocUpdate = $('#DataTables_DocUpdate').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
	buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
		{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
			customize: function (win){
				$(win.document.body).addClass('white-bg');
				$(win.document.body).css('font-size', '10px');
				$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
			}
		}]
	});
	
	const ongoing_report = $('#ongoing_report').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
		buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
				customize: function (win){
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
				}
			}]
	});	
</script>


    <script>
		// CUSTOM GLOBAL SCRIPTS
		$(function(){
			try {
				// side nav active
				var path = window.location.pathname.split("/");
				var link = document.querySelector(`[href="${path[path.length - 1]}"]`);
				var sLink = ['Dashboard', 'Calendar', 'Reports', 'evaluation'];
				var higherLevelpages = [
					{pages: ['resort-items', 'canvass-return', 'project-details', 'award'], link: 'Ongoing-projects'}
				];

				var highlevelpage = higherLevelpages.find(function(e1){
					return e1.pages.find(function(e2){
						return e2 === path[path.length - 1]
					});
				});

				if(typeof highlevelpage !== "undefined"){
					link = document.querySelector(`[href="${highlevelpage.link}"]`);
				}

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
			} catch (error) {
				
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
											let reason = document.querySelector('[name="LogRem"]').value;
											if(reason === ""){
												return false;
											}else{
												return escapeHtml(reason);
											}
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
												SendDoSomething("POST", "../xhr-files/staff-aid-upd-log.php", {
													outgoing: updLog,
													action: LogType,
													remarks: res.value
												}, {
													do: function(res){
														swal({
															title: "Success!",
															text: "Document(s) successfully updated.",
															type: "success"
														});
														
														if(res.twg !== null){
															DataTable_Twg.clear().draw();
															res.twg.forEach(function(e, i){
																DataTable_Twg.row.add([
																	`<input type="checkbox" data="twg" class="i-checks" name="twg[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
																	`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
																	'TWG',
																	'TWG',
																	e.date_registered
																]);
															});
															DataTable_Twg.draw();							
														}else{
															DataTable_Twg.clear().draw();
														}

														if(res.sign !== null){
															DataTable_Signiture.clear().draw();
															res.sign.forEach(function(e, i){
																DataTable_Signiture.row.add([
																	`<input type="checkbox" data="out" class="i-checks" name="sign[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
																	`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
																	e.transmitting_to,
																	e.specific_office,
																	e.date_registered
																]);
															});
															DataTable_Signiture.draw();
														}else{
															DataTable_Signiture.clear().draw();
														}

														if(res.gen !== null){
															DataTable_GenDoc.clear().draw();
															res.gen.forEach(function(e, i){
																DataTable_GenDoc.row.add([
																	`<input type="checkbox" data="gen" class="i-checks" name="general[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
																	`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
																	e.transmitting_to,
																	e.specific_office,
																	e.transaction,
																	e.remark,
																	e.date_registered
																]);
															});
															DataTable_GenDoc.draw();
														}else{
															DataTable_GenDoc.clear().draw();
														}

														if(res.updateDoc !== null){
															DataTables_DocUpdate.clear().draw();
															res.updateDoc.forEach(function(e, i){
																DataTables_DocUpdate.row.add([
																	`<input type="checkbox" data="gen" class="i-checks" name="updOutLog[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
																	`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
																	'TWG',
																	'TWG',
																	e.date_registered
																]);
															});
															DataTables_DocUpdate.draw();
														}else{
															DataTables_DocUpdate.clear().draw();
														}

														if(res.forEval.bool){
															swal({
																title: "Evaluation form downloading",
																text: "Download of Pre-procurement evaluation form will start shortly.",
																type: "info"
															});
															setTimeout(function(){
																res.forEval.data.forEach(function(e, i){
																	window.open(`../../bac/forms/pre-eval-form.php?g=${e}`);
																});
															}, 3500);
														}
														
														$('.i-checks').iCheck({
															checkboxClass: 'icheckbox_square-green',
															radioClass: 'iradio_square-green'
														});

													}
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
										SendDoSomething("POST", "../xhr-files/staff-aid-upd-log.php", {
											outgoing: updLog,
											action: LogType,
											remarks: res.value
										}, {
											do: function(res){
												swal({
													title: "Success!",
													text: "Document(s) successfully updated.",
													type: "success"
												});
												
												if(res.twg !== null){
													DataTable_Twg.clear().draw();
													res.twg.forEach(function(e, i){
														DataTable_Twg.row.add([
															`<input type="checkbox" data="twg" class="i-checks" name="twg[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
															`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
															'TWG',
															'TWG',
															e.date_registered
														]);
													});
													DataTable_Twg.draw();							
												}else{
													DataTable_Twg.clear().draw();
												}

												if(res.sign !== null){
													DataTable_Signiture.clear().draw();
													res.sign.forEach(function(e, i){
														DataTable_Signiture.row.add([
															`<input type="checkbox" data="out" class="i-checks" name="sign[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
															`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
															e.transmitting_to,
															e.specific_office,
															e.date_registered
														]);
													});
													DataTable_Signiture.draw();
												}else{
													DataTable_Signiture.clear().draw();
												}

												if(res.gen !== null){
													DataTable_GenDoc.clear().draw();
													res.gen.forEach(function(e, i){
														DataTable_GenDoc.row.add([
															`<input type="checkbox" data="gen" class="i-checks" name="general[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
															`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
															e.transmitting_to,
															e.specific_office,
															e.transaction,
															e.remark,
															e.date_registered
														]);
													});
													DataTable_GenDoc.draw();
												}else{
													DataTable_GenDoc.clear().draw();
												}

												if(res.updateDoc !== null){
													DataTables_DocUpdate.clear().draw();
													res.updateDoc.forEach(function(e, i){
														DataTables_DocUpdate.row.add([
															`<input type="checkbox" data="gen" class="i-checks" name="updOutLog[]" id="${e.project}"> <label for="${e.project}">${e.project}</label>`,
															`<td class="td-project-title"><label for="${e.project}">${e.title}</label></td>`,
															'TWG',
															'TWG',
															e.date_registered
														]);
													});
													DataTables_DocUpdate.draw();
												}else{
													DataTables_DocUpdate.clear().draw();
												}

												if(res.forEval.bool){
													swal({
														title: "Evaluation form downloading",
														text: "Download of Pre-procurement evaluation form will start shortly.",
														type: "info"
													});
													setTimeout(function(){
														res.forEval.data.forEach(function(e, i){
															window.open(`../../bac/forms/pre-eval-form.php?g=${e}`);
														});
													}, 3500);
												}
												
												$('.i-checks').iCheck({
													checkboxClass: 'icheckbox_square-green',
													radioClass: 'iradio_square-green'
												});

											}
										});

										// reload pages		
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

			/**** Varying modal content ****/

	

			// twg evaluation result modal
			$('#twgEvaluation').on('show.bs.modal', function (event) {
			var button1 = $(event.relatedTarget) // Button that triggered the modal
			var toevaluate = button1.data('toevaluate') // Extract info from data-* attributes
			var evaluatortwg = button1.data('evaluatortwg')
			
			var evalmodal = $(this);
			// evalmodal.find('#test11').val(toevaluate);
			 document.getElementById("test11").value = toevaluate;
			 document.getElementById("evaluatortwg").value = evaluatortwg;
			})			
		
			//available actions modal
			$('#actionsModal').on('show.bs.modal', function (event) {
				var OutGoingProjectModalBody = document.getElementById('OutGoingProjectModal');
				OutGoingProjectModalBody.innerHTML = "";
				$('[dataFor="OutGoingProjectModal"]').toggleClass('sk-loading');
				var button = $(event.relatedTarget) // Button that triggered the modal
				var reference = button.data('reference') // Extract info from data-* attributes
				// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
				// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

				var form_data = null;

				SendDoSomething("POST", "../xhr-files/xhr-show-actions.php", {
					ref: reference
				}, {
					do:function(res){
						let availableActions = res.fetchedResult;
						let classtype = "";
						let icon = "";
						let cardAction = "";

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
								case "Proceed to resorting unavailable items from DBMPS for canvass":
									classtype = "lazur-bg"
									icon = "fas fa-chess-pawn";
									cardAction = `href="resort-items?q=${btoa(reference)}"`;
									break;
								case "Dismiss Project for all items are available in DBM":
									classtype = "yellow-bg"
									icon = "fas fa-check";
									cardAction = `href="#"`;
									break;
								case "No Actions Available":
									classtype = "yellow-bg";
									icon = "far fa-hand-paper";
									cardAction = ``;
									break;
								case "Register Canvass returns":
									classtype = "lazur-bg";
									icon = "fas fa-file-import ";
									cardAction = `href="canvass-return?q=${btoa(reference)}"`;
									break;
								case "Proceed to NOA, PO/LO Creation":
									classtype = "lazur-bg";
									icon = "fas fa-chess-pawn";
									cardAction = `href="award?q=${btoa(reference)}"`;
									break;
								case "Queue documents to outgoing for signatories":
									classtype = "lazur-bg";
									icon = "fas fa-book";
									cardAction = `data-standing="${res.standing}"`;
									break;
								case "Print Publication and Canvass form":
									classtype = "lazur-bg";
									icon = "far fa-file-alt";
									cardAction = `href="project-details?refno=${btoa(reference)}&m=1"`;
									break;
								case "Print Abstract of Bid":
									classtype = "lazur-bg";
									icon = "far fa-file-alt";
									cardAction = `href="project-details?refno=${btoa(reference)}&m=1"`;
									break;
								case "Print BAC Resolution":
									classtype = "lazur-bg";
									icon = "far fa-file-alt";
									cardAction = `href="project-details?refno=${btoa(reference)}&m=1"`;
									break;
								case "Print NOA, PO/LO":
									classtype = "lazur-bg";
									icon = "far fa-file-alt";
									cardAction = `href="project-details?refno=${btoa(reference)}&m=1"`;
									break;
								case "Queue Document to outgoing documents for Conforme":
									classtype = "lazur-bg";
									icon = "fas fa-book";
									cardAction = `data-standing="${res.standing}"`;
									break;
								case "Finish project":
									classtype = "lazur-bg";
									icon = "fas fa-book";
									cardAction = `data-standing="${res.standing}"`;
									break;
								default:
									classtype = "lazur-bg";
									icon = "fas fa-chess-pawn";
									cardAction = ``;
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

							$('[data-standing="5"]').on('click', function(){
								// update workflow to 6
								// register workflow to outgoing
								SendDoSomething("POST", 'xhr-update-workflow.php', {
									workflow: res.standing,
									gds: reference
								}, {
									do: function(res){
										$('#actionsModal').modal('hide');
										swal({
											title: "Success!",
											text: "Successfully queued project for outgoing.",
											type: "success",
										});
									}
								});
							});


							$('[data-standing="6"]').on('click', function(){
								// register project in outgoing
								// check if project is in outgoing
								SendDoSomething("POST", 'xhr-update-workflow.php', {
									workflow: res.standing,
									gds: reference
								}, {
									do: function(res){
										$('#actionsModal').modal('hide');
										if(res.remark === ""){
											swal({
												title: "Success!",
												text: "Successfully queued project for outgoing.",
												type: "success",
											});
										}else{
											swal({
												title: "Notice!",
												text: res.remark,
												type: "info",
											});
										}
									}
								});
							});

							$('[data-standing="7"]').on('click', function(){
								// update to 8
								// register project in outgoing
								// check if project is in outgoing
								SendDoSomething("POST", 'xhr-update-workflow.php', {
									workflow: res.standing,
									gds: reference
								}, {
									do: function(res){
										$('#actionsModal').modal('hide');
										if(res.remark === ""){
											swal({
												title: "Success!",
												text: "Successfully queued project for outgoing.",
												type: "success",
											});
										}else{
											swal({
												title: "Notice!",
												text: res.remark,
												type: "info",
											});
										}
									}
								});
							});

							$('[data-standing="8"]').on('click', function(){
								// update to 8
								// register project in outgoing
								// check if project is in outgoing
								SendDoSomething("POST", 'xhr-update-workflow.php', {
									workflow: res.standing,
									gds: reference
								}, {
									do: function(res){
										$('#actionsModal').modal('hide');
										if(res.remark === ""){
											swal({
												title: "Success!",
												text: "Successfully queued project for outgoing.",
												type: "success",
											});
										}else{
											swal({
												title: "Notice!",
												text: res.remark,
												type: "info",
											});
										}
									}
								});
							});

							$('[data-standing="9"]').on('click', function(){
								// finish project
								// register project in outgoing
								// check if project is in outgoing
								SendDoSomething("POST", 'xhr-update-workflow.php', {
									workflow: res.standing,
									gds: reference
								}, {
									do: function(res){
										$('#actionsModal').modal('hide');
										swal({
											title: "Success!",
											text: "Project successfully finished.",
											type: "success",
										});
									}
								});
							});

							if(res.issue){
								$('[dataFor="pre-proc-eval-issue"]').html(`
								<div class="alert alert-danger">
									This project has a previous issue with technical member's evaluation. Choose below from the options if this issue has been resolved or not.
								</div>								
								<div class="radio radio-danger" style="padding-left:5px">
									<input type="radio" name="resolution" id="radio1" value="no" required>
									<label for="radio1" class="text-danger">
										Check this if you consider this comment as another issue to be resolved or cleared by the enduser.
									</label>
								</div>
								<div class="radio radio-info" style="padding-left:5px">
									<input type="radio" name="resolution" id="radio2" value="yes" required >
									<label for="radio2" class="text-success">
										Check this if this re-evaluation is a resolution from the previous evaluation issue.
									</label>
								</div>`);
							}else{
								$('[dataFor="pre-proc-eval-issue"]').html(`
								<div class="checkbox checkbox-danger" style="padding-left:5px">
									<input id="checkbox1" type="checkbox" name="issue">
									<label for="checkbox1" class="text-warning font-italic">
										Check this if you consider this comment as an issue to be resolved or cleared by the enduser.
									</label>
								</div>`);
							}
						}
						$('[dataFor="OutGoingProjectModal"]').toggleClass('sk-loading');

						$('#pre-eval-formData').html('');
						res.formData.forEach(function(e, i){
							if(e.type === "PR"){
								$('#pre-eval-formData').append(`<div class="table-responsive">
								<table class="table table-bordered"><thead><tr>
									<th>Origin</th>
									<th>Stock No.</th>
									<th>Unit</th>
									<th>Description</th>
									<th>Mode of Procurement</th>
								</tr></thead>
								<tbody preEval="tbody-${i}">
								</tbody></table></div><br>`);
							}else if(e.type === "JO"){
								$('#pre-eval-formData').append(`<div class="table-responsive">
								<table class="table table-bordered"><thead><tr>
									<th>Origin</th>
									<th>List Title</th>
									<th>Tags</th>
									<th>Mode of Procurement</th>
								</tr></thead>
								<tbody preEval="tbody-${i}">
								</tbody></table></div><br>`);							
							}
							
							e.lots.forEach(function(e1, i1){
								e1.lot_items.forEach(function(e2, i2){
									if(e.type === "PR"){
										$(`[preEval="tbody-${i}"]`).append(`<tr>
											<td>${e.req_origin} - ${e1.l_title}</td>
											<td>${e2.stock_no}</td>
											<td>${e2.unit}</td>
											<td>${e2.desc}</td><td>
											<input type="text" name="item[]" value="${e.type}-${e1.l_id}-${e2.id}" hidden>
											<select class="form-control m-b" name="individialMOP[]">
												<option value="">Choose...</option>
												<option value="Public Bidding">Public Bidding</option>
												<option value="SVP">Small Value Procurement</option>
												<option value="Direct Contracting">Direct Contracting</option>
												<option value="Negociated Procurement">Negociated Procurement</option>
												<option value="Shopping">Shopping</option>
												<option value="Repeat Order">Repeat Order</option>
												<option value="Limited Source Bidding">Limited Source Bidding</option>
											</select>
											</td></tr>`);
									}else if(e.type === "JO"){
										$(`[preEval="tbody-${i}"]`).append(`<tr>
											<td>${e.req_origin} - ${e1.l_title}</td>
											<td>${e2.header}</td>
											<td>${e2.tags}</td><td>
											<input type="text" name="item[]" value="${e.type}-${e1.l_id}-${e2.id}" hidden>
											<select class="form-control m-b" name="individialMOP[]">
												<option value="">Choose...</option>
												<option value="Public Bidding">Public Bidding</option>
												<option value="SVP">Small Value Procurement</option>
												<option value="Direct Contracting">Direct Contracting</option>
												<option value="Negociated Procurement">Negociated Procurement</option>
												<option value="Shopping">Shopping</option>
												<option value="Repeat Order">Repeat Order</option>
												<option value="Limited Source Bidding">Limited Source Bidding</option>
											</select>
											</td></tr>`);
									}
								});
							});
						});
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

				$('#pre-eval-individial').on('click', function(){
					$('#pre-eval-formData').removeClass('fadeOutLeft').addClass('fadeInRight').attr('style', '');
					$('[name="MOP"]').prop('disabled', true).val('');
				});

				$('#pre-eval-whole').on('click', function(){
					$('#pre-eval-formData').removeClass('fadeInRight').addClass('fadeOutLeft');
					$('[name="MOP"]').prop('disabled', false);
					setTimeout(function(){
						$('#pre-eval-formData').attr('style', 'display:none');
					}, 500);
				});

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
		});
		// END OF CUSTOM GLOBAL SCRIPTS
		</script>


		<!-- <script>
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
    </script> -->

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
	
	<!-- <script> //dropify
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
	</script> -->
	
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
				source: ["Job Order","Procurement Aide","Head Secretariat", "Director", "Staff", "Technical Member"]
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
		$(function(){

			var DataTables_userOverview = $('#DataTables_userOverview').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
				{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
					}
				}]
			});

			var DataTables_overallLogs = $('#DataTables_overallLogs').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
				{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
					}
				}]
			});

			// var DataTables_updateRequests = $('#DataTables_updateRequests').DataTable({pageLength: 25,responsive: true,dom: '<"html5buttons"B>lTfgitp',
			// buttons: [{extend: 'copy'},{extend: 'csv'},{extend: 'excel', title: 'ExampleFile'},
			// 	{extend: 'pdf', title: 'ExampleFile'},{extend: 'print',
			// 		customize: function (win){
			// 			$(win.document.body).addClass('white-bg');
			// 			$(win.document.body).css('font-size', '10px');
			// 			$(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
			// 		}
			// 	}]
			// });

		});
	</script>

	<script> //search script
		$(function(){
			$('#FormSearchModal').submit(function(e) {
				// e.preventDefault(); this shit prevents the form from submitting
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

$('button.static-back-to-top').click(function() {
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