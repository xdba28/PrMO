<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<!-- <link href="../assets/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet"> -->
	<script src="../assets/sweetalert2/dist/sweetalert2.all.min.js"></script>
</head>
<body>
	<div>

	</div>
</body>
<script>

	async function sweet(param, code = {
		do: function(res){}
	}){
		var {value: res} = await swal(param);
		code.do(res);
	}

	sweet({
		title: 'Remarks of Log',
		input: 'text',
		showCancelButton: true,
		confirmButtonText: "Submit",
		allowOutsideClick: false,
	}, {
		do: function(r){
			document.querySelector('div').innerHTML = r;
		}
	});


	// (async function getIpAddress () {
	// const ipAPI = 'https://api.ipify.org?format=json'

	// const inputValue = fetch(ipAPI)
	// .then(response => response.json())
	// .then(data => data.ip)

	// const {value: ipAddress} = await swal({
	// title: 'Enter your IP address',
	// input: 'text',
	// inputValue: inputValue,
	// showCancelButton: true,
	// inputValidator: (value) => {
	// 	return !value && 'You need to write something!'
	// }
	// })

	// if (ipAddress) {
	// swal(`Your IP address is ${ipAddress}`)
	// }

	// })()



	// sweet({
	// 	title: 'Remarks of Log',
	// 	input: 'text',
	// 	showCancelButton: true,
	// 	confirmButtonText: "Submit",
	// 	allowOutsideClick: false,
	// }, {
	// 	do: function(res){
	// 		console.log(res);
	// 	}
	// });


	// swal({
	// 	title: 'Remarks of Log',
	// 	input: 'text',
	// 	showCancelButton: true,
	// 	confirmButtonText: "Submit",
	// 	showLoaderOnConfirm: true,
	// 	allowOutsideClick: false,
	// 	preConfirm: (login) => {

	// 	},
	// })

	// async function sweet(){
	// 	const {value: text} = await swal({
	// 		title: ""
	// 	});
	// }

	// (async function getEmail () {
	// 	const {value: email} = await swal({
	// 	title: 'Input email address',
	// 	input: 'text',
	// 	inputPlaceholder: 'Remark'
	// 	})

	// 	if (email) {
	// 	swal('Entered email: ' + email)
	// 	}
	// })()

	// var {value: text} = swal({
	// 	title: "asd",
	// 	input: "text",
	// 	inputPlaceholder: "Remark"
	// });

	
		

	// var obj = {
	// 	data: "asd"
	// };


	// async function call()
	// {
	// 	let response = await fetch("xhr-sample.php", {
	// 		method: "POST",
	// 		headers: {
	// 			"Content-Type": "application/json"
	// 		},
	// 		body: JSON.stringify(obj),
	// 	})
	// 	let data = await response.json();
	// 	document.querySelector('div').innerHTML = data.post.data;
	// }

	// document.addEventListener("DOMContentLoaded", function(){
	// 	fetch("composer.json")
	// 		.then(function(response){
	// 			return response.json();
	// 		})
	// 		.then(function(data){
	// 			let j = JSON.stringify(data);
	// 			document.querySelector('div').innerHTML = j;
	// 		});
	// 	call();
	// });
</script>
</html>