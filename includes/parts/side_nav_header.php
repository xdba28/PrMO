<?php
	
	$hold = $user->fullname();
	$currentUser = json_decode($hold,true);

		
	// echo "<pre>",print_r($user->data()),"</pre>";

	if($user->data()->group_ === "1"){
		$logoutDir = "logout";
	}else{
		$logoutDir = "../logout";
	}


?>

<li class="nav-header">
	<div class="dropdown profile-element">
		<img src="../../assets/pics/flaticons/random/account.png" alt="" height="42" width="42">
		<div class="row" style="background:#283243; display:block; padding-left:10px">
		<a data-toggle="dropdown" class="dropdown-toggle" href="#">
			<span class="block m-t-xs font-bold"><?php echo $currentUser[0];?></span>
			<span class=" text-xs block"><?php echo $currentUser[1];?><b class="caret"></b></span>
		</a>
		<ul class="dropdown-menu animated fadeInRight m-t-xs">
			<li><a class="dropdown-item" href="#">Profile</a></li>
			<li class="dropdown-divider"></li>
			<li><a class="dropdown-item" href="<?php echo $logoutDir;?>">Logout</a></li>
		</ul>
		</div>
	</div>
	<div class="logo-element">
	<!-- <i class="fab fa-500px" style="font-size:40px; color:red"></i> -->
	
		PrMO

	</div>
</li>