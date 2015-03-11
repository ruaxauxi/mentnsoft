$(document).ready(function(){
	$('#shipment_id').combobox({});
	 
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
});//ready