function embed_code_init(field_id, prefix) {
	var embed_code_wrapper = '#hold_field_'+field_id;
	$(embed_code_wrapper + ' .embed_code:visible').each( function(i) {
		$(this).val('{'+prefix+(i+1)+'}');
	});
}