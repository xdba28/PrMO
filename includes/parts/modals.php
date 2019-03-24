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
				setTimeout(function(){
					window.open(`../../bac/forms/pre-eval-form?g=${res.projectreference}`);
					if($('meta[name="group"]').attr('content') === 'group7'){
						setTimeout(function(){
							window.location = 'index';
						}, 2000);
					}	
				}, 2000);
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
								<input type="hidden" id="rq-num" name="rq-num">
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

                            <form id="newAccount" action="" method="POST" role="form">
                                <div class="row" id="pwd-container">
                                    <div class="col-sm-12">
                                        <div id="username-div" class="form-group">
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
								<td class="center"><?php echo $document->transmitting_to;?></td>
								<td class="center"><?php echo $document->specific_office;?></td>
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
		<form id="FormSearchModal" class="search" action="search-results" method="GET"><span id="RefSearch"></span>
		<input type="search" name="q" placeholder="Title, Keyword, Reference No." autocomplete="off" required="required"/>
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
<div id="userEdit" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-expanded">
		<div class="modal-content">
		
			<div class="modal-header">
				<h3 class="modal-title">Update Project Details</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>			
			</div>
			<div class="modal-body">
				<div class="col-lg-12 animated fadeInRight" id="userEditContent">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" dataFor="userEditSubmit">
					<i class="far fa-edit"></i>
					<span>Update</span>&nbsp;
				</button>
			</div>
		</div>
	</div>
</div>

<!-- pre procurement evaluation registration -->

<div class="modal fade" id="pre-proc-evaluation" tabindex="-1" role="dialog" aria-labelledby="preprocTitle" aria-hidden="true">
  <div class="modal-dialog  modal-expanded" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="preprocTitle">Pre-Procurement Evaluation Result Registration</h3>
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
							
								 <div class="i-checks">
									<label class="font-bold my-blue" id="pre-eval-whole">
										<input type="radio" value="whole" checked name="a"> &nbsp;Mode of Procurement
									</label>
									<a class="font-bold my-blue" style="color:black">(As a whole project)</a>
								 </div>
								 
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
							
							<div class="i-checks">
								<label class="font-bold my-blue" id="pre-eval-individial">
									<input type="radio" value="perItem" name="a">  Individual Listing of Mode of Procurement 
								</label>
								<a class="font-bold my-blue" style="color:black">(Identify individual Mode of Procurement)</a>
								
							</div><br>
							<div id="pre-eval-formData" class="animated fadeInRight" style="display:none;">

							</div>
							<div class="form-group">
								<label class="font-bold my-blue">Evaluator's Comment</label>
								<div dataFor="pre-proc-eval-issue">
								</div>
									<textarea name="comment" id="comment" placeholder="Specify technical member's comment" class="form-control" rows="7" ></textarea>									
							</div>
						</div>
					</div>
					<input type="text" hidden value="PreprocResult" name="action">
				</form>				
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="ModalSubmit('PreprocModal', 'pre-proc-evaluation')">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- pre procurement evaluation registration from twg-->

<div class="modal fade" id="twgEvaluation" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="evaluationTitle" aria-hidden="true">
  <div class="modal-dialog  modal-expanded" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="evaluationTitle">Pre-Procurement Evaluation Result Registration</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

			<form id="twgPreprocModal" method="POST" enctype="multipart/form-data" role="form">
				<div class="row" id="">
					<div class="col-sm-12">
						<div class="form-group"> 
							<label for="" class="font-bold my-blue">Project Reference no.</label>
							<input type="text" class="form-control" id="test11" name="projectReference" required autocomplte="off" Readonly>
						</div>								
						<div class="alert alert-success">
							
							<p>
								<i class="fas fa-info"></i>
								Note that you can only declare a single mode of procurement in this registration form. In cases that two or more mode of procurement is required, or there is a need for <i><b>DBMPS Checking Result</b></i> first to finalize the individual declarations of mode of procurement.  the responsibility of registering individual mode of procurement is passed to the procurement aid. If so choose <i><b>"Multiple Mode of Procurement" </b></i> below as <b>Mode of Procurement Specification</b>.
							</p><br>
							<p>
								Also, consider that if you choose the <i><b>"Multiple Mode of Procurement"</b></i> option, all other declaration you made in this form except <i><b>"Evaluator's Comment"</b></i>  will be registered for referencing of the procurement aid in declaring individual mode of procurement for each item.
							</p><br>

							<p>
								<i class="fas fa-info"></i>
								<b style="color:red">Important:</b> If you declared that this evaluation has an <i><b>"issue to be resolved or cleared by the end user"</i></b>&nbsp <b style="color:red">and</b> You chose <i><b>"Single Mode of Procurement"</i></b>  as <b>Mode of Procurement Specification</b>, The responsibility of declaring if this evaluation has been resolved or not for the following reevaluation lies on this account unless you choose <i><b>"Multiple Mode of Procurement"</i></b>.
							</p>
						</div>
						<div class="form-group">
							<label for="" class="font-bold my-blue">Mode of Procurement Specification</label>
							<div class="">
								<div class="radio radio-success">
									<input type="radio" name="mopOption" id="mopOption1" value="overall" checked>
									<label for="mopOption1">
										Single Mode of Procurement
									</label>
								</div>
								<div class="radio radio-success">
									<input type="radio" name="mopOption" id="mopOption2" value="muptiple">
									<label for="mopOption2">
										Multiple Mode of Procurement
									</label>
								</div>
							</div>							

						</div>
						<div class="form-group">
						   <label for="" class="font-bold my-blue">Specific Mode of Procurement (Single Mode of Procurement)</label>
						   <div class="input-group date">
							   <span class="input-group-addon"><i class="fa fa-list"></i></span>
							   <select class="form-control m-b" name="MOPbyTwg">
								   <option value="TBE">Choose...</option>
								   <option value="PB">Public Bidding</option>
								   <option value="SVP">Small Value Procurement</option>
								   <option value="DC">Direct Contracting</option>
								   <option value="NEGO">Negociated Procurement</option>
								   <option value="Shopping">Shopping</option>
								   <option value="RO">Repeat Order</option>
								   <option value="LSB">Limited Source Bidding</option>
							   </select>
						   </div>
					   </div>
					   <br>
					   <div id="pre-eval-formData" class="animated fadeInRight" style="display:none;">

					   </div>
					   <div class="form-group">
						   <label class="font-bold my-blue">Evaluator's Comment</label>
						   <div dataFor="pre-proc-eval-issue-twg">
						   </div>
							   <textarea name="commentbyTwg" id="comment1" placeholder="Specify technical member's comment" class="form-control" rows="7" ></textarea>
							   <input type="text" value="hello" id="evaluatortwg" name="evaluator" hidden>							
					   </div>
					</div>
				</div>
				<input type="text" hidden value="twgPreprocResult" name="action">
			</form>	
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="ModalSubmit('twgPreprocModal', 'twgEvaluation')">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- reset password  / Super admin -->
		<div class="modal inmodal" id="resetPassword" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content animated bounceIn">
					<div class="modal-header">
						<img src="../../assets/pics/resetPassword.png" height="100" width="100">
						<h4 class="modal-title">Modal title</h4>
						<small id="office" class="font-bold">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</small>
					</div>
					<div class="modal-body">
						<div class="well">
							<h3><i class="fas fa-info"></i>
								Reminder
							</h3>
							New Password will be sent automatically to user's registered number. <a id="phone" class="font-bold" style="color:#438CFC; font-size:20px">default</a>
						</div><br>
							<div class="input-group">
								<span class="input-group-addon"><i class="fas fa-lock"></i></span><input type="text" id="s-user-Npass" class="form-control" placeholder="New Password">
							</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" data-reset="user">Reset</button>
					</div>
				</div>
			</div>
		</div>


<div id="profile-setting" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		
			<div class="modal-header">
				<h3 class="modal-title">Test Modal</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>			
			</div>
			<div class="modal-body" id="rcontent-container">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" form="singleForm" id="profile-submit">
					<i class="fas fa-user-check"></i>
					<span>Update</span>&nbsp;
				</button>
			</div>
		</div>
	</div>
</div>


<div class="modal inmodal" id="profile-setting-small" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated fadeInUp">
			<div class="modal-header">
				
				<i class="default" id="modal-icon-small"></i>
				<h4 class="modal-title">Modal title</h4>
				<small class="font-bold" id="modal-description-small">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</small>
			</div>
			<div class="modal-body" id="rcontent-container-small">

						
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" form="singleForm1" id="profile-submit">
					<i class="fas fa-user-check"></i>
					<span>Update</span>&nbsp;
				</button>
			</div>
		</div>
	</div>
</div>

<div id="profile-photo-modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
		
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12"><h3 class="m-t-none m-b">Update Profile Photo</h3>

						<p>Update your profile photo by browsing your files.</p>

						<form role="form" method="POST" enctype="multipart/form-data">
							<div class="form-group">
								<div class="input-group" style="margin-top:5px">
								  <div class="custom-file">
									<input type="file" class="custom-file-input" name="profilePhoto" id="profile-photo-in" aria-describedby="" accept="image/*" required>
									<label class="custom-file-label" for="profile-photo-in">Choose file</label>
								  </div>
								</div>
								<input type="text" name="profile-photo-token" value="<?php echo Token::generate('profile-photo-token');?>" hidden>
							</div>							
							<div>
								<button class="btn btn-sm btn-info btn-outline float-right m-t-n-xs" type="submit" style="margin-left:5px"><strong>Update</strong></button>
								<button class="btn btn-sm btn-danger btn-outline float-right m-t-n-xs" type="button" data-dismiss="modal"><strong>Cancel</strong></button>
								
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- publication summary -->
<div id="summary" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		
			<div class="modal-header">
				<h3 class="modal-title">Publication & BAC Resolution Summary</h3>		
			</div>
			<div class="modal-body">
					<div class="row">
							<div class="col-lg-6">
								<div class="widget style1 lazur-bg" style="height:110px">
									<div class="row">
										<div class="col-4">
											<i class="fas fa-file-contract fa-5x"></i>
										</div>
										<div class="col-8 text-right">
											<span>Mode of Procurement Classified</span>
											<h2 class="font-bold" id="MOPCount"></h2>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="widget style1 yellow-bg" style="height:110px">
									<div class="row">
										<div class="col-4">
											<i class="far fa-copy fa-5x"></i>
										</div>
										<div class="col-8 text-right">
											<span>Pair of BAC Resolution & Publication</span>
											<h2 class="font-bold" id="MOPCountMult"></h2>
										</div>
									</div>
								</div>
							</div>						

					</div>
					<!-- here is the clickable grids for viewing --><hr>
					<div class="row">
					
						<div class="col-lg-12">
							   <div class="alert alert-success">
									<i class="fas fa-info"></i> Click on the pdf icon to preview document.
							   </div>
							   <div >
								    <div class="my-file-box">
									   <div class="file">
										   <a href="#">
											   <span class="corner"></span>

											   <div class="icon">
												   <i class="fas fa-file-pdf"></i>
											   </div>
											   <div class="file-name">
												   BAC Reso-Recommending & Publication_Direct Contracting.pdf
											   </div>
										   </a>
									   </div>

								   </div>

								   <div class="my-file-box">
									   <div class="file">
										   <a href="#">
											   <span class="corner"></span>

											   <div class="icon">
												   <i class="fas fa-file-pdf"></i>
											   </div>
											   <div class="file-name">
												   BAC Reso-Recommending & Publication_SVP.pdf
											   </div>
										   </a>
									   </div>

								   </div>

								   <div class="my-file-box">
									   <div class="file">
										   <a href="#">
											   <span class="corner"></span>

											   <div class="icon">
												   <i class="fas fa-file-pdf"></i>
											   </div>
											   <div class="file-name">
												   BAC Reso-Recommending & Publication_Shopping.pdf
											   </div>
										   </a>
									   </div>

								   </div>							
							   </div>
					
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-rounded btn-outline">
					<i class="fas fa-print"></i>
					<span>Print Now</span>&nbsp;
				</button>
				<button id="resort-savePrint" data-dismiss="modal" type="button" class="btn btn-success btn-rounded btn-outline">
					<i class="far fa-save"></i>
					<span>Save and print Later</span>&nbsp;
				</button>
				<button type="button" class="btn btn-danger btn-rounded btn-outline" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<!-- director's action to nearing projects -->
<div class="modal inmodal" id="nearing-projects" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated fadeInUp">
			<div class="modal-header">
				
				<i class="fas fa-users modal-icon" id="modal-icon-nearing"></i>
				<h4 class="modal-title">Modal title</h4>
				<small class="font-bold" id="modal-description-nearing">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</small>
			</div>
			<div class="modal-body" id="nearing-projects-detail">


                        <div class="">

                            <div class="forum-title">
                                <h3>Project Summary</h3>
                            </div>

                            <div class="forum-item active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="forum-icon">
                                            <i class="fas fa-business-time"></i>
                                        </div>
                                        <a class="forum-item-title">Available Time</a>
                                        <div class="forum-sub-title">Starting from this day, we still have <b><a id="days" class=""></a></b> more days to work on this project. Required implementation date is on <b><a id="implementation-date" class=""></a></b></div>
                                    </div>
                                </div>
                            </div>
                            <div class="forum-item active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="forum-icon">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <a class="forum-item-title">Current Status</a>
                                        <div class="forum-sub-title">This project is currently in <b><a id="accomplishment"></a></b>% accomplishment and is currently <b><a id="workflow"></a></b>. Click this <a id="link" href="" style="color:#009bdf">Link</a> for more info.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="forum-item active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="forum-icon">
                                            <i class="fas fa-chess-pawn"></i>
                                        </div>
                                        <a class="forum-item-title">What can we do?</a>
                                        <div id="" class="forum-sub-title">
											<form id="project-options" method="POST" enctype="multipart/form-data">
												<button type="submit" name="to-prioritize" id="to-prioritize" class="btn btn-danger btn-outline btn-block">Prioritize this project</button>
											</form>
											<input type="text" form="project-options" name="directors-action" hidden value="<?php echo Token::generate('directors-action');?>">
											
										</div>
                                    </div>
                                </div>
                            </div>

                        </div>			
			
						
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-outline" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>



