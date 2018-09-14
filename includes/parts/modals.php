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
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" form="newAccount">Finish</button>

			</div>			
        </div>
    </div>
</div>