var windowloaded = function() {
	/*$('a.delete-confirm').on('click', function(e) {
		var deleteEntry = confirm('Are you sure you want to delete this?');
		if(!deleteEntry) {
			e.preventDefault();
		}
	});*/
	
	$('body').delegate('a.delete-confirm', 'click', function(e) {
		var deleteEntry = confirm('Are you sure you want to delete this?');
		if(!deleteEntry) {
			e.preventDefault();
		}
	});
	
	$('body').delegate('a.scroll', 'click', function(e) {		
		e.preventDefault();
		$('html,body').animate({ scrollTop: $(this.hash).offset().top }, 500);
	});
}

$(function(){
	windowloaded();
}); 