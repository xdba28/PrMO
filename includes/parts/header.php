<div class="navbar-header">
	<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
	<form role="search" class="navbar-form-custom" action="search_results.html">
		<div class="form-group">
			<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
		</div>
	</form>
</div>
<ul class="nav navbar-top-links navbar-right">
	<li>
		<span class="m-r-sm text-muted welcome-message">Welcome to PrMO OPPTS</span>
	</li>

<<<<<<< HEAD
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#" class="dropdown-item">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="#" class="dropdown-item">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="float-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="#" class="dropdown-item">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="#" class="dropdown-item">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="logout">
                        <i class="fa fa-sign-out-alt"></i> Log out
                    </a>
                </li>
            </ul>
=======
	<li class="dropdown">
		<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="NotifClick">
			<i class="fa fa-bell"></i>  
			<?php 
				$ClassNotif = new User();
				$notif = $ClassNotif->listNotification();
				if($notif['count']->seen === '0') echo '<span class="label label-primary" id="NotifCount" style="display: none;"></span>';
				else echo '<span class="label label-primary" id="NotifCount">'.$notif['count']->seen.'</span>';
			?>
		</a>
		<ul class="dropdown-menu dropdown-alerts" id="NotifList">
			<?php
				if(!empty($notif['list'])){
					foreach($notif['list'] as $n){
						if($n->seen === '0'){
							?>
								<li class="active">
									<?php 
										if($n->href === null) echo '<a href="#" class="dropdown-item">';
										else echo '<a href="'. $n->href .'" class="dropdown-item">';
									?>
										<div>
											<i class="fa fa-bell fa-fw"></i> <?php echo $n->message;?>
										</div>
										<small>Time: <?php echo Date::translate($n->datecreated, '1');?></small>
									</a>
								</li>
								<li class="dropdown-divider"></li>
							<?php
						}else{
							?>
								<li>
									<?php
										if($n->href === null) echo '<a href="#" class="dropdown-item">';
										else echo '<a href="'. $n->href .'" class="dropdown-item">';
									?>
										<div>
											<i class="fa fa-bell fa-fw"></i> <?php echo $n->message;?>
										</div>
										<small>Time: <?php echo Date::translate($n->datecreated, '1');?></small>
									</a>
								</li>
								<li class="dropdown-divider"></li>
							<?php
						}
					}
				}else{
					?>
					<div id="message">
						<li>
							<a href="#" class="dropdown-item">
								<div>
									<i class="fa fa-bell fa-fw"></i> No Messages
								</div>
							</a>
						</li>
						<li class="dropdown-divider"></li>
					</div>
					<?php
				}
			?>
			</li>
		</ul>
	</li>
	<li>
		<a href="logout">
			<i class="fa fa-sign-out-alt"></i> Log out
		</a>
	</li>
</ul>
>>>>>>> denver
