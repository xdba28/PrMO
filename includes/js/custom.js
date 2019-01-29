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
					confirmButtonColor: "#DD6B55",
					type: "error"
				});
			}
		},
		error: function(){
			if(navigator.onLine){
				swal({
					title: "An error occurred!",
					text: "Cannot send data.",
					confirmButtonColor: "#DD6B55",
					type: "error"
				});
			}else{
				swal({
					title: "An error occured!",
					text: "No internet connection detected.",
					confirmButtonColor: "#DD6B55",
					type: "error"	
				});
			}
		}
	});
}

function SendDoSomething(type = "", url = "", data = {}, code = {
	do: function(d){}
}, poll = false, Err = {
	f: function(){
		if(!poll){
			if(window.navigator.onLine){
				swal({
					title: "An error occurred!",
					text: "Cannot send data.",
					confirmButtonColor: "#DD6B55",
					type: "error"
				});
			}else{
				swal({
					title: "An error occured!",
					text: "No internet connection detected.",
					confirmButtonColor: "#DD6B55",
					type: "error"	
				});
			}
		}
	}
}){
	$.ajax({
		type: type,
		url: url,
		data: data,
		timeout: 5000
	}).done(function(d){
		code.do(d);
	}).fail(function(){
		Err.f();
	});
	if(poll){
		setTimeout(function(){
			SendDoSomething(type, url, data, code, true, Err)
		}, 60000);
	}
}

async function sweet(param = {}, code = {
	do: function(res){}
}, Err = {
	f: function(res){}
}){
	let res = await swal(param);
	if(typeof res !== "undefined"){
		code.do(res);
	}else{
		Err.f(res);
	}
}

function escapeHtml(text){
	var map = {
	  '&': '&amp;',
	  '<': '&lt;',
	  '>': '&gt;',
	  '"': '&quot;',
	  "'": '&#039;'
	};
  
	return text.replace(/[&<>"']/g, function(m){
		return map[m];
	});
}

function formatMoney(n, c, d, t) {
	var c = isNaN(c = Math.abs(c)) ? 2 : c,
	  d = d == undefined ? "." : d,
	  t = t == undefined ? "," : t,
	  s = n < 0 ? "-" : "",
	  i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
	  j = (j = i.length) > 3 ? j % 3 : 0;
  
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};