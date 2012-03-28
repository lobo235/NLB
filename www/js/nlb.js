var $main = function(input) {
	alert(input + ' hi');
	console.log('this is a test');
	$('#main').each(function(i, e) {
		this.value = e.value;
		alert(i);
	});
}

window.onload = $main;