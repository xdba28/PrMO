<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<body>
	<div>

	</div>
</body>
<script>
	
	var obj = {
		data: "asd"
	};


	async function call()
	{
		let response = await fetch("xhr-sample.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify(obj),
		})
		let data = await response.json();
		document.querySelector('div').innerHTML = data.post.data;
	}

	document.addEventListener("DOMContentLoaded", function(){
		// fetch("composer.json")
		// 	.then(function(response){
		// 		return response.json();
		// 	})
		// 	.then(function(data){
		// 		let j = JSON.stringify(data);
		// 		document.querySelector('div').innerHTML = j;
		// 	});
		call();
	});
</script>
</html>