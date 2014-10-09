function embed_code_init(col_id, prefix) {
	$("input[name*='" + col_id + "'].embed_code:visible").each(function(i) {
		$(this).val('{' + prefix + (i + 1) + '}');
	});
}
