function SendDoNothing(type = "", url = "", data = {}, swalset = {}){
	$.ajax({
		type: type,
		url: url,
		data: data,
		timeout: 5000,
		success: function(d){
			if(d.success && d.success !== 'error'){
				swal({
					title: swalset.title,
					text: swalset.text,
					type: "success"
				});
			}else{
				swal({
					title: "An error occurred!",
					text: "Error in data sent.",
					type: "error"
				});
			}
		},
		error: function(){
			swal({
				title: "An error occurred!",
				text: "Cannot send data.",
				type: "error"
			});
		}
	});
}

function SendDoSomething(type = "", url = "", data = {}, code = {
	do: function(d){},
	f: function(){
		swal({
			title: "An error occured!",
			text: "Cannot send data.",
			type: "error"
		});
	}
}, poll = false){
	$.ajax({
		type: type,
		url: url,
		data: data,
		timeout: 5000
	}).done(function(d){
		code.do(d);
	}).fail(function(){
		code.f();
	});
	if(poll){
		setTimeout(function(){
			SendDoSomething(type, url, data, code, true)
		}, 15000);
	}
}

async function sweet(param = {}, code = {
	do: function(res){}
}){
	var {value: res} = await swal(param);
	code.do(res);
}