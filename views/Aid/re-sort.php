<?php

    require_once('../../core/init.php');

    $user = new Admin(); 

    
    if($user->isLoggedIn()){
     //do nothing
    }else{
       Redirect::To('../../blyte/acc3ss');
        die();
	}

	if(!empty($_POST)){
		echo "<pre>".print_r($_POST)."</pre>";
		die();
	}
	
	$refno = $_GET['q'];
	$project = $user->projectDetails($refno);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Consolidate Items</title>
	<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
			text-align: center;
		}
	</style>
	<script>
		var obj = <?php 
			echo json_encode($project)
		?>;
		console.log(obj);
	</script>
</head>
<body>
	<div id="proj-details">

	</div>
	<div id="table">

	</div>
	<button type="button" id="consolidate" style="margin-left:60%">Consolidate</button>
	<hr>
	<br>

	<div id="selected-consolidate">
	
	</div>
</body>
<script src="../../assets/js/jquery-3.1.1.min.js"></script>
<script>
	$(function(){
		
		var table_count = 0;
		obj.forEach(function(p, i){
			let proj_details = `
				Title: ${p.title}<br>
				Request Origin: ${p.req_origin} - Ref No: ${p.refno}<br>
				MOP: ${p.MOP}<br>
				ABC: ${p.ABC}<br>
				<br>`;

			document.getElementById('proj-details').innerHTML += proj_details;

			p.lot_details.forEach(function(lot, i){
				let table_temp = `
					${lot.l_title}
					<table style="width:70%;">
						<thead>
							<tr>
								<th>Select</th>
								<th>stock no</th>
								<th>unit</th> 
								<th>description</th>
								<th>quantity</th>
								<th>unit cost</th>
								<th>total cost</th>
							</tr>
						</thead>
						<tbody id="tbody-${table_count}">
						</tbody>
					</table><br><br>`;

				document.getElementById('table').innerHTML += table_temp;

				lot.l_details.forEach(function(it){
					let item_temp = `
						<tr>
							<td><input type="checkbox" data='${lot.l_title}~${JSON.stringify(it)}'></td>
							<td>${it.stock_no}</td>
							<td>${it.unit}</td>
							<td>${it.desc}</td>
							<td>${it.qty}</td>
							<td>${it.uCost}</td>
							<td>${it.tCost}</td>
						</tr>`;

					document.getElementById(`tbody-${table_count}`).innerHTML += item_temp;
				});
				table_count++;

			});

		});

		document.getElementById('consolidate').addEventListener('click', function(){
			let count = 0;
			document.getElementById('selected-consolidate').innerHTML = `
				<form method="POST" action="" id="submit">
					<table style="width:70%;">
						<thead>
							<tr>
								<th>stock no</th>
								<th>unit</th> 
								<th>description</th>
								<th>quantity</th>
								<th>unit cost</th>
								<th>total cost</th>
								<th>New Lot</th>
							</tr>
						</thead>
						
							<tbody id="tbody">
							</tbody>
						
					</table><br>
					<button type="submit" style="margin-left:60%">Submit</button>
				</form>`;

			for(let selected of document.querySelectorAll('input[type="checkbox"]:checked')){
				let item = selected.getAttribute('data').split("~");
				let item_data = JSON.parse(item[1]);
				let consolidate_temp = `
					<tr>
						<td>${item_data.stock_no}</td>
						<td>${item_data.unit}</td>
						<td>${item_data.desc}</td>
						<td>${item_data.qty}</td>
						<td>${item_data.uCost}</td>
						<td>${item_data.tCost}</td>
						<td>
							<input type="hidden" value='${JSON.stringify(item_data)}' name="item_details-${count}">
							<select id="sel-conso-${count}" name="new-lot-${count}">
								<option value="Common Office Supplies">Common Office Supplies</option>
								<option value="Paper Materials & Products">Paper Materials & Products</option>          
								<option value="Hardware Supplies">Hardware Supplies</option>
								<option value="Sporting Supplies">Sporting Supplies</option>
								<option value="Common Janitorial/Cleaning Supplies">Common Janitorial/Cleaning Supplies</option>
								<option value="ICT Supplies">ICT Supplies</option>
								<option value="Laboratory Supplies">Laboratory Supplies</option>
								<option value="Computer Supplies">Computer Supplies</option>
							</select>
						</td>
					</tr>`;

				document.getElementById('tbody').innerHTML += consolidate_temp;
				document.getElementById(`sel-conso-${count}`).value = item[0];
				count++;
			}

		});

	});
</script>
</html>