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
								<input type="hidden" name="token" value="<?php echo Token::generate();?>">						
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