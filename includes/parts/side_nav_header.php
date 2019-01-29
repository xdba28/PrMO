<?php
	
	$hold = $user->fullname();
	$currentUser = json_decode($hold,true);

		
	// echo "<pre>",print_r($user->data()),"</pre>";

	if($user->data()->group_ === "1"){
		$logoutDir = "logout";
		$userdata = $user->get("enduser", array("edr_id", "=", Session::get(Config::get('session/session_name'))));
		$profilePhoto =  $userdata->edr_profile_photo;
	}else{
		$logoutDir = "../logout";
		$userdata = $user->get("personnel", array("prnl_id", "=", Session::get(Config::get('session/session_name'))));
		$profilePhoto =  $userdata->prnl_profile_photo;
		
	}


?>

<li class="nav-header">
	<div class="dropdown profile-element">
		<img src="<?php
			if(is_null($profilePhoto)){
				echo "../../assets/pics/flaticons/random/avatar1.jpg";
			}else{
				echo "../../data/profile_images/".$profilePhoto;
			}
		?>" alt="" class="rounded-circle" height="55" width="55">
		<div class="row" style="background:#283243; display:block; padding-left:10px">
		<a data-toggle="dropdown" class="dropdown-toggle" href="#">
			<span class="block m-t-xs font-bold"><?php echo $currentUser[0];?></span>
			<span class=" text-xs block"><?php echo $currentUser[1];?><b class="caret"></b></span>
		</a>
		<ul class="dropdown-menu animated fadeInRight m-t-xs">
			<li><a class="dropdown-item" href="profile">Profile</a></li>
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