<script>
	function ModalSubmit(id, modal){
		var DataModal = $(`#${id}`).serialize();
		SendDoSomething("POST", "../xhr-files/xhr-modal-actions.php", DataModal, {
			do: function(res){
				$(`#${modal}`).modal('hide');
				swal({
					title: "Action successful!",
					text: "Project Updated.",
					type: "success"
				});
			}
		});
	}
</script>


<!--Superadmin / account-request.php-->
<div id="decline_modal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12"><h3 class="m-t-none m-b">Add Remarks</h3>

                        <p>State your reason for declining this request.</p>

                        <form role="form" method="POST">
                            <div class="form-group has-warning">
								<label>Account request of</label>
								<input type="text" id="rq-mdl-name" disabled class="form-control">
								<input type="hidden" id="rq-hid" name="rq-hid">
							</div>
                            <div class="form-group">
								<label>Reason</label>
								<input type="text" name="rq-rsn" placeholder="Eg. Incorrect designation office" id="rq-mdl-rsn" class="form-control">
								<input type="hidden" name="declineToken" value="<?php echo Token::generate('declineToken');?>">						
							</div>
                    </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>			
        </div>
    </div>
</div>

<!--New account-->
<div id="new-user-modal" class="modal fade" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12"><h3 class="m-t-none m-b">Finish your account setup by changing your username and password</h3>

                            <form id="newAccount" action="Dashboard" method="POST" role="form">
                                <div class="row" id="pwd-container">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="new_username" class="form-label">New Username</label>
                                            <input type="text" class="form-input" id="new_username" name="new_username" required autocomplte="off">
                                        </div>
                                        <div class="form-group mt-20">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control example4 form-input" id="new_password" name="new_password" required>
                                        </div>
										<div class="form-group mt-20">
											<label for="password_again" class="form-label">Confirm Password</label>
											<input type="password" id="password_again" name="password_again" class="form-control form-input" required>
                                            <input type="text" name="passwordToken" value="<?php echo Token::generate('passwordToken');?>" hidden required>
										</div>
										<br>
                                        <div class="form-group">Password Meter
                                            <span class="font-bold pwstrength_viewport_verdict4"></span>
                                            <span class="pwstrength_viewport_progress4"></span>

                                            
                                        </div>
                                    </div>
                                </div>
                            </form>						

						
                    </div>
				</div>
			</div>
			<div class="modal-footer">
            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
				<button type="submit" class="btn btn-primary" form="newAccount">Finish</button>

			</div>			
        </div>
    </div>
</div>




<!-- test modal -->

<div id="returning" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	
		<div class="modal-header">
			<h3 class="modal-title">Update Released Documents</h3>
			<button type="button" modal="RelOut" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>			
		</div>		
		
		<div class="modal-body">

		<?php 
			$admin = new Admin();
			$released = $admin->selectAll('outgoing_register');
		?>
		
		<div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-content">

                        <div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="DataTables_DocUpdate">
							<thead>
							<tr>
								<th><input btn-t="updOutChk" type="checkbox" class="i-checks"> Select all</th>
								<th>Title</th>
								<th>Transmitting</th>
								<th>Office</th>
								<th>Date/time Released</th>
							</tr>
							</thead>
							<tbody>
							<?php 
								foreach($released as $document){
									$project = $admin->get('projects', array('project_ref_no', '=', $document->project));
							?>
							<tr class="">
								<td class="tdcheck"><input data="upLog" type="checkbox" class="i-checks" name="updOutLog[]" id="<?php echo $document->project;?>"> <label for="<?php echo $document->project;?>"><?php echo $document->project;?></label></td>
								<td class="td-project-title"><label for="<?php echo $document->project;?>"><?php echo $project->project_title;?></label></td>
								<td class="center">TWG</td>
								<td class="center">TWG</td>
								<td class="center"><?php echo Date::translate($document->date_registered, 1);?></td>
							</tr>
							<?php
								}
							?>
							</tbody>
							<tfoot>
							<tr>
								<th class="tdcheck"><input btn-t="updOutChk" type="checkbox" class="i-checks"> Select all</th>
								<th>Title</th>
								<th>Transmitting</th>
								<th>Office</th>
								<th>Date/time Released</th>
							</tr>
							</tfoot>
							</table>
							
							<div class="btn-group pull-right" style="margin-right:15px">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="far fa-edit"></i> <span>Update Selected documents to</span>&nbsp;&nbsp;</button>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" log="upd" action="1" href="#"><i class="fas fa-check green side"></i> Successfuly Received</a></li>
									<li><a class="dropdown-item" log="upd" action="2" href="#" data-toggle="tooltip" data-placement="top" title="Actions Successfully taken and returned immidiately"><i class="fas fa-exchange-alt blue side" ></i> Received & Immidiately Returned<br></a></li>
									<li><a class="dropdown-item" log="upd" action="3" href="#"><i class="fas fa-reply orange side" ></i> Return Document to Outgoing Queue<br></a></li>
									<li class="dropdown-divider"></li>
									<li><a type="demo3" class="dropdown-item demo3" log="upd" action="4" href="#"><i class="far fa-times-circle red side" ></i> Failed to Receive</a></li>
								</ul>
							</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
			
		</div>
		<!-- <div class="modal-footer">
			<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>			 -->
			
    </div>
  </div>
</div>


<!-- Search Modal -->
<div id="search-reference" class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalCenterTitle">Search Logs of a specific Project</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

		<!-- asdasdsa -->
		<form id="FormSearchModal" class="search"><span id="RefSearch"></span>
		<input type="search" name="q" placeholder="Enter Reference No." autocomplete="off" required="required"/>
		<button type="submit"><img src="../../assets/pics/flaticons/search.png" alt="search" height="42" width="42"></button>
		</form><br>


      </div>

    </div>
  </div>
</div>


<!-- Actions modal for admin -->

<div class="modal fade" id="actionsModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content ibox-content" dataFor="OutGoingProjectModal" style="padding:0px">
	
	   <div class="sk-spinner sk-spinner-double-bounce">
			<div class="sk-double-bounce1"></div>
			<div class="sk-double-bounce2"></div>
	   </div>
	   
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">test tittle</h4>
        <button id="actionsModalClose" type="button" class="close" data-dismiss="modal" aria-label="Close" dataFor="OutGoingProjectModalClose">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  
      <div class="modal-body" id="OutGoingProjectModal">

      </div>
	  
      <div class="modal-footer" style="padding:.5rem; ">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
	  
    </div>
  </div>
</div>

<!-- myforms?q=PROJECT-ID -->
<div id="userEdit" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		
			<div class="modal-header">
				<h3 class="modal-title">Update Project Details</h3>
				<button type="button" dataFor="userEditClose" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>			
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox">
							<div class="ibox-content">
		
								<div class="table-responsive">
									<form id="userEditForm">
										<table class="table table-striped table-bordered table-hover" dataFor="userEdit">

										</table>
									</form>
									<div class="btn-group pull-right" style="margin-right:15px">
										<button type="button" class="btn btn-primary" dataFor="userEditSubmit">
											<i class="far fa-edit"></i>
											<span>Update</span>&nbsp;
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>				
		</div>
	</div>
</div>

<!-- pre procurement evaluation registration -->

<div class="modal fade" id="pre-proc-evaluation" tabindex="-1" role="dialog" aria-labelledby="preprocTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="preprocTitle">Pre-Procurement Evaluation result Registration</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

				<form id="PreprocModal" method="POST" enctype="multipart/form-data" role="form">
					<div class="row" id="">
						<div class="col-sm-12">
							<div class="form-group"> 
								<label for="projectReference" class="font-bold my-blue">Project Reference no.</label>
								<input type="text" class="form-control" id="projectReference" name="projectReference" required autocomplte="off" Readonly>
							</div>						
							<div class="form-group">
								<label for="evaluator" class="form-label">Evaluator</label>
								<input type="text" class="form-input" id="evaluator" name="evaluator" required autocomplte="off">
							</div>
							<div class="form-group">
								<label class="font-bold my-blue">Mode of Procurement</label>
								<div class="input-group date">
									<span class="input-group-addon"><i class="fa fa-list"></i></span>
									<select class="form-control m-b" name="MOP">
                                        <option value="">Choose...</option>
                                        <option value="Public Bidding">Public Bidding</option>
                                        <option value="SVP">Small Value Procurement</option>
                                        <option value="Direct Contracting">Direct Contracting</option>
										<option value="Negociated Procurement">Negociated Procurement</option>
										<option value="Shopping">Shopping</option>
										<option value="Repeat Order">Repeat Order</option>
										<option value="Limited Source Bidding">Limited Source Bidding</option>
                                    </select>
								</div>
							</div>

							<div class="form-group">
								<label class="font-bold my-blue">Evaluator's Comment</label>
								<div dataFor="pre-proc-eval-issue">

								
								</div>
								
											
												<!-- if this project has no record of "ISSUE^pre procurement evaluation" in the project logs show this -->
												<!-- <div class="checkbox checkbox-danger" style="padding-left:5px">
													<input id="checkbox1" type="checkbox" name="issue">
													<label for="checkbox1" class="text-warning font-italic">
														Check this if you consider this comment as an issue to be resolved or cleared by the enduser.
													</label>
												</div> -->

												
												<!-- if this project has a record of "ISSUE^pre procurement evaluation" in the project logs show this -->												
                                                <!-- <div class="radio radio-danger" style="padding-left:5px">
                                                    <input type="radio" name="issue_again" id="radio1" value="option1">
                                                    <label for="radio1" class="text-warning">
                                                        Check this if you consider this comment as an issue to be resolved or cleared by the enduser.
                                                    </label>
                                                </div>
                                                <div class="radio radio-info" style="padding-left:5px">
                                                    <input type="radio" name="resolution" id="radio2" value="option2">
                                                    <label for="radio2" class="text-info">
                                                        Check this if this evaluation is a resolution from the previous evaluation issue.
                                                    </label>
                                                </div> -->
                                      


								
									<textarea name="comment" id="comment" placeholder="Specify technical member's comment" class="form-control" rows="7" ></textarea>									
							</div>
						</div>
					</div>
					<input type="text" hidden value="PreprocResult" name="action">
				</form>				
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="ModalSubmit('PreprocModal', 'pre-proc-evaluation')">Submit</button>
      </div>
    </div>
  </div>
</div>

