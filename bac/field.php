<?php
	require '../classes/Date.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<script src="../includes/js/custom.js"></script>
	<script src="../assets/sweetalert2/dist/sweetalert2.all.min.js"></script>
</head>
<body>
	<div>
		<input type="text" name="a">
		<button id="btn">Send</button>
	</div>
</body>
<script>

	document.addEventListener("DOMContentLoaded", function() {
		const vara = 'sync';

		swal({
			title: "Confirm?",
			text: "",
			type: "question",
			showCancelButton: true,
			confirmButtonText: "Proceed",
			allowOutsideClick: false,
			preConfirm: function(res){
				return res;
			}
		}, function(res){
			alert('confirm')
			console.log(res);
		});


		alert(vara);
	});


	// document.querySelector('#btn').addEventListener('click', function(){
	// 	var a = document.querySelector('[name="a"]').value;
	// 	alert(a);
	// });

// (async function getFormValues () {
// const {value: formValues} = await swal({
//   title: 'Multiple inputs',
//   html:
//     '<input id="swal-input1" class="swal2-input">' +
//     '<input id="swal-input2" class="swal2-input">',
//   focusConfirm: false,
//   preConfirm: () => {
//     return document.getElementById('swal-input1').value    
//   }
// })

// if (formValues) {
//   console.log(formValues);
// }

// })()

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

	// console.log(typeof asd);

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
	// 	// f: function(res){
	// 	// 	console.log(res);
	// 	// }
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