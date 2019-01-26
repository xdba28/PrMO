<li>
	<a href="Dashboard"><img src="../../assets/pics/flaticons/random/dashboard.png" alt="" height="22" width="22"> <span class="nav-label">Dashboard</span></a>
</li>
<li>
	<a href="Calendar"><img src="../../assets/pics/flaticons/random/planning.png" alt="" height="22" width="22"> <span class="nav-label">Calendar</span></a>
</li>
<li>
    <a href="#"><img src="../../assets/pics/flaticons/random/shopping-list.png" alt="" height="22" width="22"> <span class="nav-label">Tasks</span> <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="Outgoing">Outgoing</a></li>
        <li><a href="#" data-toggle="modal" data-target="#returning">Released Documents</a></li>					
    </ul>
</li>
<li>
    <a href="#"><img src="../../assets/pics/flaticons/internet-security/030-file-1.png" alt="" height="22" width="22"> <span class="nav-label">Projects</span> <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="Ongoing-projects">Ongoing Projects</a></li>
        <li><a href="Finished">Finished / Failed Projects</a></li>
		<li>
			<a href="Revision-requests">Revision Requests 
				<span class="label label-info float-right" style="color:black; font-weight:200px">
				<?php
					$REVISION_OBJ = new Admin();
					$REVISION_COUNT = $REVISION_OBJ->selectAll('form_update_requests');
					echo count($REVISION_COUNT);
				?>
				</span>
			</a>
		</li>
    </ul>
</li>

<li>
	<a href="logs-and-references"><img src="../../assets/pics/flaticons/random/decision-making.png" alt="" height="22" width="22"> <span class="nav-label">Logs & References</span> <span class="fa arrow"></span></a>
	<ul class="nav nav-second-level collapse">
		<li><a id="search" href="#" data-toggle="modal" data-target="#search-reference">Search</a></li>
		<li><a href="Logs">Overall Logs</a></li>
	</ul>
</li>