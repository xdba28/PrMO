<?php
	
	$hold = $user->fullname();
	$currentUser = json_decode($hold,true);
	
	//echo "<pre>",print_r($hold),"</pre>";
	

?>

<li class="nav-header">
	<div class="dropdown profile-element">
		<img alt="image" class="rounded-circle" src="../../assets/pics/flaticons/maze.png" height="42" width="42">
		<div class="row" style="background:#283243; display:block; padding-left:10px">
		<a data-toggle="dropdown" class="dropdown-toggle" href="#">
			<span class="block m-t-xs font-bold"><?php echo $currentUser[0];?></span>
			<span class=" text-xs block"><?php echo $currentUser[1];?><b class="caret"></b></span>
		</a>
		<ul class="dropdown-menu animated fadeInRight m-t-xs">
			<li><a class="dropdown-item" href="#">Profile</a></li>
			<li class="dropdown-divider"></li>
			<li><a class="dropdown-item" href="../logout">Logout</a></li>
		</ul>
		</div>
	</div>
	<div class="logo-element">
	<!-- <i class="fab fa-500px" style="font-size:40px; color:red"></i> -->
	<img src="../../assets/pics/flaticons/035-user.png" alt="" height="42" width="42">
	</div>
</li>