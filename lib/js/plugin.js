jQuery(document).ready(function($){
				
	// Set up the toggle effect:
	$('.more-content a.read-more').on('click', function(e) {
	  $(this).closest('.more-content').toggleClass('show');
	  var text = $(this).text();
	  $(this).text(text == read_more_inline_script_vars.less_text ? read_more_inline_script_vars.more_text : read_more_inline_script_vars.less_text );
	  e.preventDefault();
	});
	
});